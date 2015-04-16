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
			$scores = $leaderboard->create_global($_GET["date"], ($page - 1) * 30, 30)->to_array();
			$data = array(
	        	'year' => $date->format("Y"),
	        	'month' => $date->format("m"),
	        	'day' => $date->format("d"),
	        	'count' => count($scores),	
	        	'scores' => $scores,
	        	'page' => $page
	    	);
			echo $twig->render('archive_display.php', array_merge($sdata, $data));
		} catch (Exception $e) {
			echo $twig->render('archive_picker.php', $sdata);
		}
	} else {
		echo $twig->render('archive_picker.php', $sdata);
	}

}
?>