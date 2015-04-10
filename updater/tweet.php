<?php
require("config.php");
require("codebird.php");

// Connect to the database.
$db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// Get latest day ID (clumsy!)
$daily      = $db->query('SELECT * FROM throne_dates ORDER BY dayId DESC LIMIT 0,2');
$result     = $daily->fetchAll();
$today      = $result[0]['dayId'];
$today_date = $result[0]['date'];

$winner = $db->query('SELECT throne_scores.score, throne_players.name FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId WHERE throne_scores.dayId = ' . $today . ' ORDER BY rank ASC LIMIT 0,1');
$result = $winner->fetchAll();

\Codebird\Codebird::setConsumerKey($twitter_settings["consumer_key"], $twitter_settings["consumer_secret"]);
$cb = \Codebird\Codebird::getInstance();
$cb->setToken($twitter_settings["oauth_access_token"], $twitter_settings["oauth_access_token_secret"]);

$params = array(
  'status' => $result[0]['name'] . " is today's winner with " . $result[0]['score'] . " kills!"
);
$reply = $cb->statuses_update($params);

?>
