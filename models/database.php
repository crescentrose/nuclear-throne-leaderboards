<?php

class Database {
	public $db;
	private $query;

	public function __construct() {
		$this->db = Application::connect();
	}

	public function execute($query, $arguments = array()) {
		$stm = $this->db->prepare($query);
		$stm->execute($arguments);	
		return $stm->fetchAll();
	}
}

?>