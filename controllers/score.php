<?php 
if (isset($_GET["hash"])) {
	$score =  get_score($_GET["hash"]);
	if ($score != false) {
		echo $twig->render('score.php', $score);
	} else {
		echo $twig->render('404.php', array());
	}

} else {
	echo $twig->render('404.php', array());
}
?>