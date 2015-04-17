<?php 
function render($twig, $sdata = array()) {
	if (isset($_GET["steamid"])) {
		
		$player = new Player(array("search"=>$_GET["steamid"]));

		$scoreboard = new Leaderboard();

		// Display the last 10 scores
		$scores = $scoreboard->create_player($player->steamid, 0, 10)->to_array();

		$data = array("player" => $player, "scores" => $scores);
		if ($data != false) {
			echo $twig->render('player.php', array_merge($sdata, $data));
		} else {
			echo $twig->render('404.php', $sdata);
		}
	} else {
		echo $twig->render('404.php', $sdata);
	}
}
?>