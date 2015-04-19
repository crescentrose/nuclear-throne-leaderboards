<?php 
class Application {
	
	private $database_password, $database_host, $database_username, $database_name;
	public static $db, $connection_count;

	public static function connect() {
		$config_file = file_get_contents("config/config.json");
		$config = json_decode($config_file, true);

		self::$db = new PDO('mysql:host='. $config["Database"]["host"] .';dbname='. $config["Database"]["name"] . ';charset=utf8', $config["Database"]["username"], $config["Database"]["password"], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		self::$connection_count += 1;
		return self::$db;
	}

	public static function generate_token($steamid) {
		$token = base64_encode(openssl_random_pseudo_bytes(16));
		$stmt = self::$db->prepare("INSERT INTO `throne_tokens`(`token`, `user_id`, `last_accessed`) VALUES(:token, :steamid, CURRENT_TIMESTAMP())");
		$stmt->execute(array(":token" => $token, "steamid" => $steamid));
		setcookie("authtoken", $token, time()+60*60*24*14);
	}

	public static function check_login($token) {
		$stmt = self::$db->prepare("SELECT * FROM `throne_tokens` WHERE `token` = :token");
		$stmt->execute(array(":token" => $token));
		$data = $stmt->fetchAll();

		if (count($data) > 0) {
				$stmt = self::$db->prepare("UPDATE `throne_tokens` SET `last_accessed` = CURRENT_TIMESTAMP() WHERE `token` = :token");
				$stmt->execute(array(":token" => $token));
			return $data[0]["user_id"];
		} else {
			return false;
		}
   	}

   	public static function remove_token($token) {
		$stmt = self::$db->prepare("DELETE FROM `throne_tokens` WHERE `token` = :token");
		$stmt->execute(array(":token" => $token));
   	}

   	public static function remove_all_tokens($userid) {
		$stmt = self::$db->prepare("DELETE FROM `throne_tokens` WHERE `user_id` = :userid");
		$stmt->execute(array(":userid" => $userid));
   	}
	
}

?>