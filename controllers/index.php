<?php
function render($twig, $sdata = array()) {

	// Get current page
	if (isset($_GET["page"]) && (int)$_GET["page"] > 0) {
		$page = $_GET["page"]; 
	} else {
		$page = 0;
	}

	// Get currently active livestreams
    $oStreams = new Streams(4);
	$oStreams->streams;

    // Get today's leaderboards
    $leaderboards_today = new Leaderboard();
    $today = $leaderboards_today->create_global(0)->to_array($page * 30, 30);

    // Get yesterday's leaderboards
    $leaderboards_yesterday = new Leaderboard();
    $yesterday = $leaderboards_yesterday->create_global(1)->to_array(0, 5);

	$data = array(
        'location' => "daily",
        'date' => $leaderboards_today->date,
        'scores' => $today,
        'scores_yesterday' => $yesterday,
        'streams' => $oStreams->streams,
        'streamcount' => count($oStreams->streams),
        'global' => $leaderboards_today->get_global_stats(),
        'page' => $page + 1
    );
 	echo $twig->render('index.php', array_merge($sdata, $data));
 }
 ?>