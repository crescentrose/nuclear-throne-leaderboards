<?php

class Leaderboard {

	public $scores, $date;
	private $db;

	public function __construct() {
		$this->db = Application::connect();
	}

	// Creates a leaderboard based on either a date in YYYY-MM-DD format or 
	// an offset from today's date.
	// Offsets the leaderboard from the start with $start and defines its length
	// with $size.
	// Returns the class instance for easy manipulation. Results are stored in 
	// $this->scores.
	public function create_global($date, $start, $size, $order_by = "rank", $direction = "ASC") {
		if (is_int($date)) {
			$leaderboard = $this->db->query('SELECT * FROM throne_dates ORDER BY dayId DESC');
    		$result = $leaderboard->fetchAll();
    		$date = $result[$date]['date'];
		} 
		$this->date = $date;
		return $this->make_leaderboard("date", $date, $start, $size, $order_by, $direction);
	}

	// Creates a leaderboard based on a steamid. 
	public function create_player($player, $start, $size, $order_by = "date", $direction = "DESC") {
		return $this->make_leaderboard("throne_players", $player, $start, $size, $order_by, $direction);
	}

	public function to_array() {
		$array_scores = array();
		foreach ($this->scores as $score) {
			$array_scores[] = $score->to_array();
		}
		return $array_scores;
	}

	public function get_global_stats() {
		return $this->db->query('SELECT COUNT(*) AS amount, ROUND(AVG(score)) AS average 
			FROM throne_scores
			LEFT JOIN throne_dates ON throne_scores.dayId = throne_dates.dayId
			WHERE `date` = "' . $this->date . "\"")->fetchAll(PDO::FETCH_ASSOC)[0];
	}
	
	// Helper function to help build the query.
	private function make_leaderboard($where, $condition, $start, $size, $order_by, $direction) {

		try {
			$query = $this->db->prepare("SELECT * FROM `throne_scores`
				LEFT JOIN throne_dates ON throne_scores.dayId = throne_dates.dayId
				LEFT JOIN throne_players ON throne_scores.steamId = throne_players.steamid
				LEFT JOIN 
						((SELECT COUNT(*) AS wins, steamid 
						FROM throne_scores 
						WHERE rank = 1	
						GROUP BY steamid) AS w)
				ON w.steamid = throne_scores.steamid
				WHERE `$where` = :cnd
				ORDER BY `$order_by` $direction
				LIMIT :str, :siz");
			$query->execute(array(":cnd" => $condition, 
				":str" => $start,
				":siz" => $size));
			$entries = $query->fetchAll();
		} catch (Exception $e) {
			die ("Error fetching leaderboard: " . $e->getMessage());
		}

		$scores = array();
		
		foreach ($entries as $entry) {
			$player = new Player(array(	"steamid" => $entry["steamid"],
										"name" => $entry["name"],
										"avatar" => $entry["avatar"],
										"suspected_hacker" => $entry["suspected_hacker"],
										"admin" => $entry["admin"],
										"raw" => $entry));

			$scores[] = new Score(array(	"player" => $player,
											"score" => $entry["score"],
											"rank" => $entry["rank"],
											"first_created" => $entry["first_created"],
											"raw" => $entry));
		}
		$this->scores = $scores;
		return $this;
	}
}

?>