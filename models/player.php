<?php
	class Player {
		public $steamid, $name, $avatar, $avatar_medium, $suspected_hacker, $admin, $raw;

		public function __construct($data) {
			if (isset($data["search"])) {
				$db = Application::connect();
				$stmt = $db->prepare("SELECT * FROM `throne_players`
					LEFT JOIN 
						((SELECT COUNT(*) AS wins, steamid 
						FROM throne_scores 
						WHERE rank = 1	
						GROUP BY steamid) AS w) ON w.steamid = throne_players.steamid
					WHERE throne_players.steamid = :steamid");
				$stmt->execute(array(':steamid' => $data["search"]));
				$data = $stmt->fetchAll()[0];
				$data["raw"] = $data;
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

		public function get_rank() {
			// TODO
		}

		public function get_total_kills() {
			// TODO
		}

		public function to_array() {
			return array("steamid" 				=> $this->steamid,
						 "name"					=> $this->name,
						 "avatar" 				=> $this->avatar,
						 "avatar_medium"		=> $this->avatar_medium,
						 "suspected_hacker"		=> $this->suspected_hacker,
						 "admin"				=> $this->admin,
						 "raw" 					=> $this->raw);
		}
	}
