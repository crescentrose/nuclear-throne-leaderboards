<?php 
if (isset($_GET["steamid"])) {
	$data =  get_player($_GET["steamid"]);
	if ($data != false) {
		echo $twig->render('player.php', $data);
	} else {
		echo $twig->render('404.php', array());
	}

} else {
	echo $twig->render('404.php', array());
}
?>