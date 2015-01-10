<?php 
if (isset($_GET["hash"])) {
	echo $twig->render('score.php', get_score($_GET["hash"]));
} else {
	echo $twig->render('index.php', get_latest_daily());
}
?>