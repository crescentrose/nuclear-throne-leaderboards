<?php 
	function render($twig, $sdata = array()) {
		echo $twig->render('about.php', $sdata);
	}
?>