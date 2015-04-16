<?php
	class Player {
		public $steamid, $name, $avatar, $avatar_medium, $suspected_hacker, $admin, $raw;

		public function __construct($data) {
			if (isset($data["search"])) {
				$db = new Database();
				$result = $db->execute("SELECT * FROM `throne_players`
					WHERE steamid = :steamid",
					array(':steamid' => $data["search"]))[0];
				$data = $result->fetchAll();
			}

			$this->steamid = $data["steamid"];

			if ($data['avatar'] === "") {
            	$this->avatar_medium = "/img/no-avatar.png";
            	$this->avatar        = "/img/no-avatar-small.png";
        	} else {
        		$this->avatar = $data["avatar"];
				$this->avatar_medium = substr($data['avatar'], 0, -4) . "_medium.jpg";
				
        	}
        	if ($data['name'] === "") {
            	$data['name'] = "[no profile]";
        	} 
        	$this->name = $data['name'];
			$this->suspected_hacker = $data["suspected_hacker"];
			$this->admin = $data["admin"];
			$this->raw = $data["raw"];
		}

		public function to_array() {
			return array("steamid" 				=> $this->steamid,
						 "name"					=> $this->name,
						 "avatar" 				=> $this->avatar,
						 "suspected_hacker"		=> $this->suspected_hacker,
						 "admin"				=> $this->admin,
						 "raw" 					=> $this->raw);
		}
	}
