<?php 
function render($twig, $sdata = array()) {
	if (isset($_GET["hash"])) {
		$score =  get_score($_GET["hash"]);
		if ($score != false) {
			echo $twig->render('score.php', array_merge($score, $sdata));
		} else {
			echo $twig->render('404.php', $sdata);
		}

	} else {
		echo $twig->render('404.php', $sdata);
	}
}
?>