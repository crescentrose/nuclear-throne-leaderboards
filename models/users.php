<?php

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
?>