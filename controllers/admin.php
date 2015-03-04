<?php 
function render($twig, $sdata = array()) {
	if (isset($_GET["act"]) && isset($_SESSION["admin"])) {
		if ($_GET["act"] == "hide" && isset($_GET["hash"])) {
			if($_SESSION["admin"] > 0) {
				hide_score($_GET["hash"]);
				header("Location: /player/" . $_GET["player"]);
			} else {
				echo $twig->render("404.php", $sdata);
			}
		}		
		if ($_GET["act"] == "show" && isset($_GET["hash"])) {
			if($_SESSION["admin"] > 0) {
				hide_score($_GET["hash"], 0);
				header("Location: /player/" . $_GET["player"]);
			} else {
				echo $twig->render("404.php", $sdata);
			}
		}	
		if ($_GET["act"] == "mark" && isset($_GET["player"])) {
			if($_SESSION["admin"] > 0) {
				mark_hacker($_GET["player"]);
				header("Location: /player/" . $_GET["player"]);
			} else {
				echo $twig->render("404.php", $sdata);
			}
		}	
		if ($_GET["act"] == "unmark" && isset($_GET["player"])) {
			if($_SESSION["admin"] > 0) {
				mark_hacker($_GET["player"], 0);
				header("Location: /player/" . $_GET["player"]);
			} else {
				echo $twig->render("404.php", $sdata);
			}
		}	
	} else {
		echo $twig->render("404.php", $sdata);
	}
}
?>