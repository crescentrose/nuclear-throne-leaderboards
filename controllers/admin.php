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
		if ($_GET["act"] == "update" && isset($_GET["player"])) {
			if($_SESSION["admin"] > 0) {
				update_profile($_GET["player"]);
				header("Location: /player/" . $_GET["player"]);
			} else {
				echo $twig->render("404.php", $sdata);
			}
		}
	} else {
		echo $twig->render("404.php", $sdata);
	}
}

function json($sdata) {
	if (isset($_GET["act"])) {
		if ($_GET["act"] == "update-twitch") {
			if(isset($_SESSION["admin"]) || $_POST["twitch_steamid"] == $_SESSION["steamid"]) {
				$player = new Player(array("search" => $_POST["twitch_steamid"]));
				if ($player->set_twitch($_POST["twitch_user"])) {
					echo "{'result':'success'}";
				} else {
					echo "{'result':'error'}";
				}
			}
		}
	}
}
?>
