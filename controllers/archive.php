<?php 
if (isset($_GET["date"])) {
	if(isset($_GET["page"])) {
		$data =  get_archive($_GET["date"], $_GET["page"]);
	} else {
		$data =  get_archive($_GET["date"]);
	}
	if ($data != false) {
		echo $twig->render('archive_display.php', $data);
	} else {
		echo $twig->render('404.php', array());
	}

} else {
	echo $twig->render('archive_picker.php', array());
}
?>