<?php
	class Player {
		public $steamid, $name, $avatar, $twitch, $avatar_medium, $suspected_hacker, $admin, $raw, $rank, $donated;
		private $db;

		public function __construct($data) {
			$this->db = Application::$db;
			if (isset($data["search"])) {
				$stmt = $this->db->prepare("SELECT * FROM `throne_players`
					LEFT JOIN
						((SELECT COUNT(*) AS wins, steamid
						FROM throne_scores
						WHERE rank = 1
						GROUP BY steamid) AS w) ON w.steamid = throne_players.steamid
					WHERE throne_players.steamid = :steamid");
				$stmt->execute(array(':steamid' => $data["search"]));
				$data = $stmt->fetchAll();
				if (!isset($data[0])) {
					$data["steamid"] = false;
					return;
				} else {
					$data = $data[0];
					$data["steamid"] = $data[0]; // wat
				}

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
			@$this->rank = $data["rank"]; // I don't know how this works, but it works.
			$this->admin = $data["admin"];
			$this->twitch = $data["twitch"];
			$this->donated = $data["donated"];
			if (isset($data["raw"])) {
				$this->raw = $data["raw"];
			}
		}

		public function get_rank() {
			$stmt = $this->db->prepare("SELECT d.*, c.ranks
                FROM (
                	SELECT    score, @rank:=@rank+1 ranks
               			FROM (
		        			SELECT DISTINCT score
		            		FROM throne_alltime a
							ORDER BY score DESC
                        ) t, (SELECT @rank:= 0) r
					) c
				INNER JOIN throne_alltime d ON c.score = d.score
				WHERE d.steamid = :steamid");
			$stmt->execute(array(':steamid' => $this->steamid));
			if ($stmt->rowCount() != 1) {
				return -1;
			}
			$data = $stmt->fetchAll()[0];
			return $data["ranks"];
		}

		public function get_rank_today() {
			$userboard = new Leaderboard();
			$score = $userboard->create_player($this->steamid, "date", "DESC", 0, 1, 0)->to_array();
			if (isset($score[0]["rank"])) {
				return $score[0]["rank"];
			} else {
				return false;
			}
		}

		public function set_twitch($twitch) {
			if ($this->twitch == $twitch) {
				return true;
			}
			$stmt = $this->db->prepare("UPDATE throne_players SET twitch = :twitch WHERE steamid = :steamid");
			$stmt->execute(array(":twitch" => $twitch, ":steamid" => $this->steamid));
			if ($stmt->rowCount() != 1) {
				return false;
			} else {
				return true;
			}
		}

		public function to_array() {
			return array("steamid" 				=> $this->steamid,
						 "name"					=> $this->name,
						 "avatar" 				=> $this->avatar,
						 "avatar_medium"		=> $this->avatar_medium,
						 "suspected_hacker"		=> $this->suspected_hacker,
						 "admin"				=> $this->admin,
						"rank"					=> $this->rank,
						"donated"					=> $this->donated,
						"twitch"					=> $this->twitch,
						 "raw" 					=> $this->raw);
		}
	}
