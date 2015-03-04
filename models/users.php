<?php
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

function check_your_privilege($steamid) {
	global $db_username, $db_password;
    $db                = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

    $stmt = $db->prepare("SELECT * FROM throne_players WHERE steamid = :steamid");
    $stmt->execute(array(
        ':steamid' => $steamid
    ));
    if ($stmt->rowCount() === 0) {
        return 0;
    } //$stmt->rowCount() === 0
    $rows                    = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $player                  = $rows[0];
    return $player['admin'];
}

function hide_score($hash, $state = 1) {
    global $db_username, $db_password;
    $db                = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

    $stmt = $db->prepare("UPDATE throne_scores SET hidden = :state WHERE hash = :hash");
    $stmt->execute(array(
        ':hash' => $hash,
        ':state' => $state
    ));
}

function mark_hacker($user, $state = 1) {
    global $db_username, $db_password;
    $db                = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

    $stmt = $db->prepare("UPDATE throne_players SET suspected_hacker = :state WHERE steamid = :user");
    $stmt->execute(array(
        ':user' => $user,
        ':state' => $state
    ));
}

function update_profile($userId) {
  global $db_username, $db_password, $steam_apikey;
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $jsonUserData = get_data("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steam_apikey."&steamids=" . $userId);
  
  $user = json_decode($jsonUserData, true);
  try {
        $stmt = $db->prepare("UPDATE throne_players SET name = :name, avatar = :avatar, last_updated = NOW() WHERE steamid = :steamid");
        $stmt->execute(array(':steamid' => $userId, ':name' => $user["response"]["players"][0]["personaname"], ':avatar' => $user["response"]["players"][0]["avatar"]));
    } catch (Exception $e) {
        die($e->message());
    }
}
?>