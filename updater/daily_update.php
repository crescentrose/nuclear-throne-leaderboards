<?php
require "config.php";

if (!isset($db_username)) { $db_username="root"; } // My dev box sucks.

// This file should be in a cron job to run every hour, depending on
// service load.

function update_alltime() {
  global $db_username, $db_password;

  $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $db->beginTransaction();

    $db->query("TRUNCATE TABLE throne_alltime");

    $db->query("INSERT INTO throne_alltime(steamid, score) 
  		SELECT throne_scores.steamid, SUM(score) as score 
  		FROM `throne_scores`
  		LEFT JOIN throne_players ON throne_scores.steamid = throne_players.steamid
  		WHERE suspected_hacker = 0
  		GROUP BY throne_scores.steamid
  		ORDER BY score DESC");
    $db->commit();
  } catch (PDOException $ex) {
    $db->rollBack();
    echo $ex->getMessage();
  }
  echo "All time database update successful. \n";
}

update_alltime();
?>
