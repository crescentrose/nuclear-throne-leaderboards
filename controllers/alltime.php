<?php 
function render($twig, $sdata = array()) {
	if(isset($_GET["page"])) {
		$page = max((int)$_GET["page"], 0);
	} else {
		$page = 0;
	}

	$leaderboard = new Leaderboard();

	if (!isset($_GET["sort"])) {
		$sort = "score";
	} elseif ($_GET["sort"] == "total") {
		$sort = "score";
	} elseif ($_GET["sort"] == "avg") {
		$sort = "average";
	} elseif ($_GET["sort"] == "runs") {
		$sort = "runs";
	} else {
		$sort = "score";
	}
	$data = array("scores" => $leaderboard->create_alltime($page * 30, 30, $sort, "DESC"), "sort_by" => $_GET["sort"], "page" => $page);
	if ($data != false) {
		echo $twig->render('alltime.php', array_merge($sdata, $data));
	} else {
		echo $twig->render('404.php', $sdata);
	}
}
?>