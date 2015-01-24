<?php
function render($twig, $sdata = array()) {
	if (isset($_GET["page"]) && (int)$_GET["page"] > 0) {
		$data = array('data' => get_latest_daily($_GET["page"]));
  	} else {
 		$data = array('data' => get_latest_daily());
 	}
 	echo $twig->render('index.php', array_merge($sdata, $data));
 }
 ?>