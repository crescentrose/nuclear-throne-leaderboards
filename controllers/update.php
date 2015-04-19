<?php
function render($twig, $sdata) {
	echo $twig->render('404.php', $sdata);
}


function json($sdata) {
	switch ($_GET["act"]) {
		case "scoreupdate":
			if (!isset($_SESSION["steamid"])) {
				echo json_encode(array("error" => "You are not logged in."));
				break;
			}
			$verify = "/^(https?\:\/\/)?w{3}?\.?(youtu|youtube|twitch)\.(com|be|tv)\/.*$/"
			if (preg_match($verify, $_POST["video"]) === false) {
				echo json_encode(array("error" => "Bad link."));
				break;
			}
			$db = Application::$db;
			$stmt = $db->prepare("UPDATE `throne_scores` SET `video` = :video, `comment` = :comment WHERE `hash` = :hash");
			$stmt->execute(array(":video" => $_POST["video"], "comment" => $_POST["comment"], ":hash" => $_POST["hash"]));

			if ($stmt->rowCount() < 1) {
				echo json_encode(array("error" => "Update failed."));
				break;
			}
			break;
		default: 
			echo json_encode(array("error" => "Wrong method."));
	}
}
?>