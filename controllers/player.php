<?php 
function render($twig, $sdata = array()) {
	if (isset($_GET["steamid"])) {
		$data =  get_player($_GET["steamid"]);
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