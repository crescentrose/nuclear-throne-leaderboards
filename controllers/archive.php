<?php 
function render($twig, $sdata = array()) {

	if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else {
		$page = 0;	
	}

	if (isset($_GET["date"])) {
		try {
			$date = new DateTime($_GET["date"]);

			$leaderboard = new Leaderboard();
			$scores = $leaderboard->create_global($_GET["date"], "rank", "ASC", ($page - 1) * 30, 30)->to_array();
			$data = array(
				'location' => "archive",
				'global' =>$leaderboard->global_stats,
	        	'year' => $date->format("Y"),
	        	'month' => $date->format("m"),
	        	'day' => $date->format("d"),
	        	'count' => count($scores),	
	        	'scores' => $scores,
	        	'page' => $page
	    	);
			echo $twig->render('archive.php', array_merge($sdata, $data));
		} catch (Exception $e) {
			render_yesterday($twig, $sdata);
		}
	} else {
			render_yesterday($twig, $sdata);
	}
}

function render_yesterday($twig, $sdata = array()) {
	if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else {
		$page = 1;	
	}

	$leaderboard = new Leaderboard();
	$scores = $leaderboard->create_global(1, "rank", "ASC", ($page - 1) * 30, 30)->to_array();
	$date = new DateTime($leaderboard->date);
	$data = array(
		'location' => "archive",
		'global' =>$leaderboard->global_stats,
	    'year' => $date->format("Y"),
	    'month' => $date->format("m"),
	    'day' => $date->format("d"),
	    'count' => count($scores),	
	    'scores' => $scores,
	    'page' => $page
	);
	echo $twig->render('archive.php', array_merge($sdata, $data));
}

function json($sdata) {
	
}
?>