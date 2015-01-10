<?php
date_default_timezone_set("UTC");

function get_latest_daily() {
  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $players = array();

  // Get latest day ID (clumsy!)
  $result = $db->query('SELECT DISTINCT dayId FROM throne_scores ORDER BY dayId DESC LIMIT 0,1');
  foreach ($result as $dateid) {
    $today = $dateid['dayId'];
  }
  foreach($db->query('SELECT throne_scores.steamId, throne_scores.rank, throne_scores.score, throne_players.name, throne_players.avatar FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId WHERE throne_scores.dayId = ' . $today . ' ORDER BY rank ASC LIMIT 0, 100') as $row) {
    $players[] = $row;
  }
  return array('date' => date('Y-m-d'), 'players' => $players);
} ?>
