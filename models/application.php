<?php 
class Application {
	
	private $database_password, $database_host, $database_username, $database_name;

	public static function connect() {
		$config_file = file_get_contents("config.json");
		$config = json_decode($config_file, true);
		return new PDO('mysql:host='. $config["Database"]["host"] .';dbname='. $config["Database"]["name"] . ';charset=utf8', $config["Database"]["username"], $config["Database"]["password"], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	
}

?>