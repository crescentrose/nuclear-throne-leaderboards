<?php 
class Streams {
	private $db;
	public $streams;

	public function __construct($limit = 3) {
		$this->db = Application::connect();
		$this->streams = $this->db->query('SELECT * FROM throne_streams ORDER BY viewers DESC LIMIT 0,3')->fetchAll();
	}

}

?>