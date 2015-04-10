<?php
require("config.php");
require("codebird.php");

function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

// if (!isset($db_username)) { $db_username="root"; } // My dev box sucks.

// This file should be in a cron job to run every 15 minutes, depending on
// service load.
// With one second pause between downloads, this should be enough provided that
// there are less than 900 new scores every 15 minutes.

// This function will pull the latest leaderboard data from Steam and update the
// data for that day's daily in the database for caching purposes.
function update_leaderboard($leaderboardId = "") {
  global $db_username, $db_password, $twitter_settings, $steam_apikey;

  if ($leaderboardId === "") {
    // Fetch the XML file for Nuclear Throne.
    $xmlLeaderboardList = get_data('http://steamcommunity.com/stats/242680/leaderboards/?xml=1');
    // Make a SimpleXMLElement instance to read the file.
    $leaderboardReader = new SimpleXMLElement($xmlLeaderboardList);
    // Find last leaderboard in the file (i.e., today's daily)
    // Steam sometime fucks us over, so we have to account for that and grab the previous run instead.
    $found_good_leaderboard = false;
    $last = 0;
    while ($found_good_leaderboard == false) {
      if ($last == 0)
        $lastLeaderboardElemenent = $leaderboardReader->xpath("/response/leaderboard[last()]/lbid");
      else
        $lastLeaderboardElemenent = $leaderboardReader->xpath("/response/leaderboard[last()-". $last . "]/lbid");

      $leaderboardId = (int)$lastLeaderboardElemenent[0];
      
      if ($last == 0)
        $lastLeaderboardDate = $leaderboardReader->xpath("/response/leaderboard[last()]/name");
      else
        $lastLeaderboardDate = $leaderboardReader->xpath("/response/leaderboard[last()-". $last . "]/name");

      $cleanDate = array();
      preg_match("/^daily_lb_([0-9]+)$/", $lastLeaderboardDate[0], $cleanDate);
      @$leaderboardDate = (int)$cleanDate[1] - 16421;

      if ($leaderboardDate < 0) {
        $last += 1;
	      print("Leaderboard not found, going to last - ". $last . "\n");
        continue;
      } else {
        print ($leaderboardDate[0]);
        $found_good_leaderboard = true;
      }
    }

    $todayDate = new DateTime('2014-12-17');
    $todayDate->add(new DateInterval('P' . $leaderboardDate . 'D'));
  } else {
    $xmlLeaderboardList = get_data('http://steamcommunity.com/stats/242680/leaderboards/?xml=1');
    // Make a SimpleXMLElement instance to read the file.
    $leaderboardReader = new SimpleXMLElement($xmlLeaderboardList);
    $lastLeaderboardDate = $leaderboardReader->xpath("/response/leaderboard[lbid=". $leaderboardId . "]/name");

    $cleanDate = array();
    preg_match("/^daily_lb_([0-9]+)$/", $lastLeaderboardDate[0], $cleanDate);
    $leaderboardDate = (int)$cleanDate[1] - 16421;
    $todayDate = new DateTime('2014-12-17');
    $todayDate->add(new DateInterval('P' . $leaderboardDate . 'D'));
  }
  // Download the today's daily challenge leaderboard
  $leaderboardUrl = "http://steamcommunity.com/stats/242680/leaderboards/" . $leaderboardId . "/?xml=1";
  $xmlLeaderboardData = get_data($leaderboardUrl);

  // Instance another SimpleXMLElement to read from it.
  $xmlLeaderboard = new SimpleXMLElement($xmlLeaderboardData);

  // Connect to the database.
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  // Purge scores from today so that there are no rank collisions.
  // $stmt = $db->prepare("DELETE FROM throne_scores WHERE dayId = ?;");
  // $stmt->execute(array($leaderboardId));
  $stmt = $db->prepare("INSERT IGNORE INTO throne_dates(`dayId`, `date`) VALUES(:dayId, :day)");
  $stmt->execute(array(':dayId' => $leaderboardId, ':day' => $todayDate->format('Y-m-d')));

  $scores = array();

  foreach ($xmlLeaderboard->entries->entry as $entry) {
    // Don't count runs with no score (score = -1).
    if ($entry->score >= 0) {
      // For each score, we shall make a unique hash, by combining their steamid
      // and today's daily leaderboard ID.
      $hash = md5($leaderboardId . $entry->steamid );
      // We'll put all results into an array so that we can weed out the hackers.
      $scores[] = array('hash' => $hash, 'dayId' => $leaderboardId, 'steamID' => $entry->steamid, 'score' => $entry->score, 'rank' => $entry->rank);
    }
  }

  // Sort by rank.
  usort($scores, function($a, $b) {
    return $a['rank'] - $b['rank'];
  });

  // get list of banned people
  $banned = array();
  
  foreach ($db->query('SELECT steamid FROM throne_players WHERE suspected_hacker = 1') as $row) {
        $banned[] = $row['steamid'];
  }

  // get a list of hidden players for today
  foreach ($db->query('SELECT steamid FROM throne_scores WHERE hidden = 1 AND dayId = ' . $leaderboardId) as $row) {
	$banned[] = $row['steamid'];
	echo("[DEBUG] Hiding scores by " . $row['steamid'] . " today.\n");
}
    
  $rank = 1;
  $rank_hax = count($scores) + 1;
  try {

    $db->beginTransaction();

    foreach ($scores as $score) {
      if ($rank == 1) {
        if ($score["steamID"] != file_get_contents("first.txt") && $score["score"] > 300) {
          $file = fopen("first.txt", "w");
          fwrite($file, $score["steamID"]);
          fclose($file);

          \Codebird\Codebird::setConsumerKey($twitter_settings["consumer_key"], $twitter_settings["consumer_secret"]);
          $cb = \Codebird\Codebird::getInstance();
          $cb->setToken($twitter_settings["oauth_access_token"], $twitter_settings["oauth_access_token_secret"]);

          $jsonUserData = get_data("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steam_apikey."&steamids=" . $score['steamID']);
          $user = json_decode($jsonUserData, true);

          $username = $user["response"]["players"][0]["personaname"];

          $params = array(
            'status' => $username . " has taken the lead with " . $score["score"] . " kills!"
          );
          $reply = $cb->statuses_update($params);
        }

      }
      if (array_search($score['steamID'], $banned) === false) {
        // Prepare the SQL statement
        $stmt = $db->prepare("INSERT INTO throne_scores(hash, dayId, steamId, score, rank, first_created) VALUES(:hash, :dayId,:steamID,:score,:rank,NOW()) ON DUPLICATE KEY UPDATE rank=VALUES(rank), score=VALUES(score);");
        // Insert data into the database
        $stmt->execute(array(':hash' => $score['hash'], ':dayId' => $score['dayId'], ':steamID' => $score['steamID'], ':score' => $score['score'], ':rank' => $rank));
        $rank += 1;
      } else {
        // Hackers get their own special rank.
        // Prepare the SQL statement
        $stmt = $db->prepare("INSERT INTO throne_scores(hash, dayId, steamId, score, rank, hidden, first_created) VALUES(:hash, :dayId,:steamID,:score,:rank,:hidden,NOW()) ON DUPLICATE KEY UPDATE rank=VALUES(rank), score=VALUES(score), hidden=VALUES(hidden);");
        // Insert data into the database
        $stmt->execute(array(':hash' => $score['hash'], ':dayId' => $score['dayId'], ':steamID' => $score['steamID'], ':score' => $score['score'], ':rank' => $rank_hax, ":hidden" => 1));
        $rank_hax += 1;
      }
    }
    // Commit our efforts.
    $db->commit();

  } catch (PDOException $ex) {
    // Failsafe

    $db->rollBack();
    echo $ex->getMessage();
  }
  echo "Finished updating today's leaderboards.\n";
}
function update_steam_profiles() {
  global $db_username, $db_password, $steam_apikey;
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  set_time_limit(0);

  // Check which steam ids are eligible for an update
  // SteamIDs will update only if they've been active on the leaderboards in the
  // past five days and if they have not been updated in the past day (or
  // at all)

  $result = $db->query('SELECT DISTINCT throne_scores.steamId
  FROM throne_scores
  LEFT JOIN throne_players ON throne_scores.steamId = throne_players.steamid
  WHERE DATEDIFF(NOW(), throne_scores.last_updated) < 5
  AND (DATEDIFF(NOW(), throne_players.last_updated) > 1
   OR throne_players.last_updated IS NULL);');

  $t = $result->rowCount();
  // Logging.
  echo($t . " profiles to update. \n");
  $c = 0;
    try {
      $db->beginTransaction();

      foreach($result as $row) {
        // For each player, we get their profile page and save their name and a link
        // to their avatar.
        try {
          global $steam_apikey;
          $jsonUserData = get_data("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steam_apikey."&steamids=" . $row['steamId']);
          $user = json_decode($jsonUserData, true);

          $stmt = $db->prepare("INSERT INTO throne_players(steamid, name, avatar) VALUES(:steamid, :name, :avatar) ON DUPLICATE KEY UPDATE name=VALUES(name), avatar=VALUES(avatar), last_updated=NOW();");
          $stmt->execute(array(':steamid' => $row['steamId'], ':name' => $user["response"]["players"][0]["personaname"], ':avatar' => $user["response"]["players"][0]["avatar"]));

          // Log the update.
          echo '[' . $c . '/' . $t . '] Updated ' . $row['steamId'] . " as " .  $user["response"]["players"][0]["personaname"] ."\n";
        } catch (Exception $e) {
          echo '[' . $c . '/' . $t . '] Failed to update ' . $row['steamId'] . ' due to ' . $e->getMessage() . "\n";
          echo '[' . $c . '/' . $t . "]   Pulled from: " . "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steam_apikey."&steamids=" . $row['steamId'] . " \n";
          echo '[' . $c . '/' . $t . "]   Result: " .  var_dump($jsonUserData)." \n";
        }
        // Wait for 0.2 seconds so that we don't piss off Lord GabeN and mistakenly
        // DoS Steam.
        usleep(200000);
        $c = $c + 1;
        // I have to do this.
        if ($c === 500) {
          break;
        }
      } 
      $db->commit();
    } catch (PDOException $ex) {
      // Failsafe

      $db->rollBack();
      echo $ex->getMessage();
    }
}

function update_twitch() {
  global $db_username, $db_password;

  $streamJson = get_data("https://api.twitch.tv/kraken/search/streams?limit=25&q=nuclear+throne");
  $streams = json_decode($streamJson, true);

  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $db->beginTransaction();

    $db->query("TRUNCATE TABLE throne_streams");

    foreach ($streams['streams'] as $stream) {  
      $stmt = $db->prepare("INSERT INTO throne_streams(name, status, viewers, preview) VALUES(:name, :status, :viewers, :preview)");
      // ON DUPLICATE KEY UPDATE name=VALUES(name), avatar=VALUES(avatar);
      $stmt->execute(array(':name' => $stream['channel']['name'], ':status' => $stream['channel']['status'], ':viewers' => $stream['viewers'], ':preview' => str_replace("http://", "https://", $stream['preview']['small'])));
    }

    $db->commit();
  } catch (PDOException $ex) {
    $db->rollBack();
    echo $ex->getMessage();
  }
  echo "Twitch update successful. \n";
}

echo "Begin update: " . date("Y-m-d H:i:s") . "\n";

// I don't know why I made them into functions.
if (isset($argv[1])) {
  update_leaderboard($argv[1]);
} else {
  update_leaderboard();
}
update_twitch();
update_steam_profiles(); 

echo "End update: " . date("Y-m-d H:i:s") . "\n";
?>
