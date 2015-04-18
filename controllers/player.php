<?php 
function render($twig, $sdata = array()) {
	if (isset($_GET["steamid"])) {
		
		$player = new Player(array("search"=>$_GET["steamid"]));

		$scoreboard = new Leaderboard();
		$scores = $scoreboard->create_player($player->steamid)->to_array(0, -1);

		$data = array("player" => $player, "scores" => $scores,
			"rank" => $player->get_rank(), "total" => $scoreboard->get_global_stats(),
			"scores_graph" => array_reverse($scoreboard->to_array(0, 30)));
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