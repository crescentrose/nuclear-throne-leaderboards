<?php 
function render($twig, $sdata = array()) {
	if(isset($_GET["page"])) {
		$data = get_alltime($_GET["page"]);
	} else {
		$data = get_alltime();
	}
	if ($data != false) {
		echo $twig->render('alltime.php', array_merge($sdata, $data));
	} else {
		echo $twig->render('404.php', $sdata);
	}
}
?>