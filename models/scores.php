<?php
	class Score {
		public $player, $score, $rank, $raw, $percentile, $first_created;

		// This class doubles as score retrieval and score storage class.
		// I know, right?
		// To look for a score, just pass an array with hash: { "hash" => $hash }
		// Otherwise, pass an array with data provided by the table.
		public function __construct($data) {
			$db = Application::$db;
			if (isset($data["hash"])) {
				try {
					$stmt= $db->prepare("SELECT * FROM `throne_scores` WHERE `hash` = :hash");
					$stmt->execute(array(':hash' => $data["hash"]));
					$result = $stmt->fetchAll();
				} catch (Exception $e) {
					die ("Error reading score: " . $e->getMessage());
				}
				$data = $result[0];
				$data["player"] = new Player(array("search" => $data["steamId"]));
				$data["raw"] = $data;
			}

			$this->player = $data["player"];
			$this->percentile = $data["percentile"];
			$this->score = $data["score"];
			$this->rank = $data["rank"];
			$this->raw = $data["raw"];

        	if ($data['first_created'] == "0000-00-00 00:00:00") {
            	$data['first_created'] = "n/a";
        	}

        	$this->first_created = $data["first_created"];
		}

		public function to_array() {
			return array("player" 	=> $this->player->to_array(),
						 "score" 	=> $this->score,
						 "rank"		=> $this->rank,
						 "first_created" => $this->first_created,
						 "percentile" => ceil($this->percentile * 100),
						 "raw" 		=> $this->raw);
		}
	}
?>
