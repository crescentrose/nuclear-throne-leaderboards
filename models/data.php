<?php
date_default_timezone_set("UTC");

function get_latest_daily() {
  global $db_username, $db_password;
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $players = array();

  // Get latest day ID (clumsy!)
  $result = $db->query('SELECT DISTINCT dayId FROM throne_scores ORDER BY dayId DESC LIMIT 0,1');
  foreach ($result as $dateid) {
    $today = $dateid['dayId'];
  }
  foreach($db->query('SELECT throne_scores.steamId, throne_scores.hash, throne_scores.rank, throne_scores.score, throne_players.name, throne_players.avatar FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId WHERE throne_scores.dayId = ' . $today . ' ORDER BY rank ASC LIMIT 0, 100') as $row) {
    $players[] = $row;
  }
  return array('date' => date('Y-m-d'), 'players' => $players);
} 


function get_score($hash) {
  global $db_username, $db_password;
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $stmt = $db->prepare("SELECT * FROM throne_scores LEFT JOIN throne_players on throne_scores.steamId = throne_players.steamid WHERE hash = :hash");
  $stmt->execute(array(':hash' => $hash));
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if ($stmt->rowCount() == 1)  {
    return $rows[0];
  } else {
    return false;
  }
} 

function get_player($steamid) { 
  global $db_username, $db_password;
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $scores = array();
  $stmt = $db->prepare('SELECT throne_scores.steamId, throne_scores.hash, throne_scores.rank, throne_scores.score, throne_players.name, throne_players.avatar FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId WHERE throne_players.steamid = :steamid ORDER BY rank ASC LIMIT 0, 100');
  $stmt->execute(array(":steamid" => $steamid));
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $score) {
    $scores[] = $score;
  }
  return array('scores' => $scores);
} 
 ?>