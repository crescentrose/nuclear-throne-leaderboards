<?php 
function render($twig, $sdata = array()) {
	if (isset($_GET["date"])) {
		if(isset($_GET["page"])) {
			$data =  get_archive($_GET["date"], $_GET["page"]);
		} else {
			$data =  get_archive($_GET["date"]);
		}
		if ($data != false) {
			echo $twig->render('archive_display.php', array_merge($sdata, $data));
		} else {
			echo $twig->render('404.php', $sdata);
		}

	} else {
		echo $twig->render('archive_picker.php', $sdata);
	}
}
?>