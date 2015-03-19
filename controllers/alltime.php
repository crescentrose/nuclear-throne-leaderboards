<?php 
function render($twig, $sdata = array()) {
	if(isset($_GET["page"])) {
		$data = get_alltime($_GET["page"], $_GET["sort"]);
	} else {
		$data = get_alltime(1, $_GET["sort"]);
	}
	if ($data != false) {
		echo $twig->render('alltime.php', array_merge($sdata, $data));
	} else {
		echo $twig->render('404.php', $sdata);
	}
}
?>