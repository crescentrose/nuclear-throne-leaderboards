<?php
function render($twig, $sdata = array()) {

	if (isset($_GET["steamid"])) {

		$player = new Player(array("search"=>$_GET["steamid"]));

		if ($player->steamid == false) {
			echo $twig->render("404.php", $sdata);
			return;
		}
		// Scoreboards
		$scoreboard = new Leaderboard();
		$scores = $scoreboard->create_player($player->steamid)->to_array(0, 15);

		// Top ranks
		$top_ranks = new Leaderboard();
		$top_ranks_list = $top_ranks->create_player($player->steamid, "rank", "ASC", 0, 2)->to_array(0, -1);

		// Top scores
		$top_scores = new Leaderboard();
		$top_scores_list = $top_scores->create_player($player->steamid, "score", "DESC", 0, 2)->to_array(0, -1);

		$best_moments = array();
		$dates = array();

		foreach (array_merge($top_ranks_list, $top_scores_list) as $score) {
			if (array_search($score["raw"]["date"], $dates) === false) {
				$best_moments[] = $score;
				$dates[] = $score["raw"]["date"];
			}
		}

		$data = array("player" => $player,
			"scores" => $scores,
			"best_moments" => $best_moments,
			"rank" => $player->get_rank(),
			"total" => $scoreboard->get_global_stats(),
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

function json($sdata) {
	if (!isset($_GET["page"]))
		return false;
	// Make sure we get a number
	if(!is_int((int)$_GET["page"]))
		return false;
	if (!isset($_GET["steamid"]))
		return false;

	// Steam has a hard limit of 10 000 scoreboards
	// anything above 300 pages is overkill or malicious.

	if ((int)$_GET["page"] > 300)
		return false;

	$player = new Player(array("search"=>$_GET["steamid"]));
	if ($player == false)
		return false;

	// Scoreboards
	$scoreboard = new Leaderboard();
	$scores = $scoreboard->create_player($player->steamid, "date", "DESC", $_GET["page"] * 15, 15)->to_array(0, -1);
	$scores = array(array("scores" => $scores, "count" => $scoreboard->get_global_stats()["count"]));
	echo json_encode($scores);
}
?>
