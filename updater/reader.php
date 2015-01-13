<?php
require "config.php";

if (!isset($db_username)) { $db_username="root"; } // My dev box sucks.

// This file should be in a cron job to run every 15 minutes, depending on
// service load.
// With one second pause between downloads, this should be enough provided that
// there are less than 900 new scores every 15 minutes.

// This function will pull the latest leaderboard data from Steam and update the
// data for that day's daily in the database for caching purposes.
function update_leaderboard($leaderboardId = "") {
  global $db_username, $db_password;
  if ($leaderboardId === "") {
    // Fetch the XML file for Nuclear Throne.
    $xmlLeaderboardList = file_get_contents('http://steamcommunity.com/stats/242680/leaderboards/?xml=1');
    // Make a SimpleXMLElement instance to read the file.
    $leaderboardReader = new SimpleXMLElement($xmlLeaderboardList);
    // Find last leaderboard in the file (i.e., today's daily)
    $lastLeaderboardElemenent = $leaderboardReader->xpath("/response/leaderboard[last()]/lbid");
    $leaderboardId = (int)$lastLeaderboardElemenent[0];

    $lastLeaderboardDate = $leaderboardReader->xpath("/response/leaderboard[last()]/name");
    $cleanDate = array();
    preg_match("/^daily_lb_([0-9]+)$/", $lastLeaderboardDate[0], $cleanDate);
    $leaderboardDate = (int)$cleanDate[1] - 16421;
    $todayDate = new DateTime('2014-12-17');
    $todayDate->add(new DateInterval('P' . $leaderboardDate . 'D'));
  } else {
    $xmlLeaderboardList = file_get_contents('http://steamcommunity.com/stats/242680/leaderboards/?xml=1');
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
  $xmlLeaderboardData = file_get_contents($leaderboardUrl);

  // Instance another SimpleXMLElement to read from it.
  $xmlLeaderboard = new SimpleXMLElement($xmlLeaderboardData);

  // Connect to the database.
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  // Purge scores from today so that there are no rank collisions.
  $stmt = $db->prepare("DELETE FROM throne_scores WHERE dayId = ?;");
  $stmt->execute(array($leaderboardId));
  $stmt = $db->prepare("INSERT IGNORE INTO throne_dates(`dayId`, `date`) VALUES(:dayId, :day)");
  $stmt->execute(array(':dayId' => $leaderboardId, ':day' => $todayDate->format('Y-m-d')));
  echo "Begin update: " . date("Y-m-d H:i:s") . "\n";
  try {

    $db->beginTransaction();

    foreach ($xmlLeaderboard->entries->entry as $entry) {
      // For each score, we shall make a unique hash, by combining their steamid
      // and today's daily leaderboard ID.
      $hash = md5($leaderboardId . $entry->steamid );
      // Prepare the SQL statement
      $stmt = $db->prepare("INSERT INTO throne_scores(hash, dayId,steamId,score,rank) VALUES(:hash, :dayId,:steamID,:score,:rank);");
      // Insert data into the database, pulled straight from XML.
      $stmt->execute(array(':hash' => $hash, ':dayId' => $leaderboardId, ':steamID' => $entry->steamid, ':score' => $entry->score, ':rank' => $entry->rank));

      // Log.
      echo 'Add #' . $entry->rank . ': ' . $entry->steamid . ' (score: '. $entry->score .")\n";

    }
    // Commit our efforts.
    $db->commit();

  } catch (PDOException $ex) {
    // Failsafe

    $db->rollBack();
    echo $ex->getMessage();
  }
}
function update_steam_profiles() {
  global $db_username, $db_password;
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  set_time_limit(0);

  // Check which steam ids are eligible for an update
  // SteamIDs will update only if they've been active on the leaderboards in the
  // past five days and if they have not been updated in the past three days (or
  // at all)

  $result = $db->query('SELECT DISTINCT throne_scores.steamId
  FROM throne_scores
  LEFT JOIN throne_players ON throne_scores.steamId = throne_players.steamid
  WHERE throne_scores.last_updated > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 5 DAY))
  AND (throne_players.last_updated < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 3 DAY))
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
        $xmlUserData = file_get_contents("http://steamcommunity.com/profiles/" . $row['steamId'] . "/?xml=1");
        $user = new SimpleXMLElement($xmlUserData);

        $stmt = $db->prepare("INSERT INTO throne_players(steamid, name, avatar) VALUES(:steamid, :name, :avatar) ON DUPLICATE KEY UPDATE name=VALUES(name), avatar=VALUES(avatar);");
        $stmt->execute(array(':steamid' => $row['steamId'], ':name' => $user->steamID, ':avatar' => $user->avatarIcon));

        // Log the update.
        echo '[' . $c . '/' . $t . '] Updated ' . $row['steamId'] . " as " . $user->steamID ."\n";

        // Wait for a second so that we don't piss off Lord GabeN and mistakenly
        // DoS Steam.
        sleep(1);
        $c = $c + 1;

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

  $streamJson = file_get_contents("https://api.twitch.tv/kraken/search/streams?limit=25&q=nuclear+throne");
  $streams = json_decode($streamJson, true);

  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $db->beginTransaction();

    $db->query("TRUNCATE TABLE throne_streams");

    foreach ($streams['streams'] as $stream) {  
      $stmt = $db->prepare("INSERT INTO throne_streams(name, status, viewers, preview) VALUES(:name, :status, :viewers, :preview)");
      // ON DUPLICATE KEY UPDATE name=VALUES(name), avatar=VALUES(avatar);
      $stmt->execute(array(':name' => $stream['channel']['name'], ':status' => $stream['channel']['status'], ':viewers' => $stream['viewers'], ':preview' => $stream['preview']['small']));
    }

    $db->commit();
  } catch (PDOException $ex) {
    $db->rollBack();
    echo $ex->getMessage();
  }
  echo "Twitch update successful. \n";
}

// I don't know why I made them into functions.
if (isset($argv[1])) {
  update_leaderboard($argv[1]);
} else {
  update_leaderboard();
}
update_steam_profiles();
update_twitch();
?>
