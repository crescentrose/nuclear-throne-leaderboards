<?php

class Leaderboard {

	public $scores, $date, $global_stats;
	private $db;

	public function __construct() {
		$this->db = Application::$db;
	}

	public function create_alltime($start = 0, $size = 30, $order_by = "score", $direction = "DESC") {
		$this->db->query("SET @prev_value = NULL");
		$this->db->query("SET @rank_count = 0");
		$stmt = $this->db->prepare("SELECT  d.*, p.*, c.ranks, w.*
                      				FROM (
                        				SELECT    $order_by, @rank:=@rank+1 Ranks
                        				FROM (
		                                    SELECT  DISTINCT $order_by
		                                    FROM    throne_alltime a
		                                    ORDER   BY $order_by DESC
                                		) t, (SELECT @rank:= 0) r
                               		) c
			                      	INNER JOIN throne_alltime d ON c.$order_by = d.$order_by
			                      	LEFT JOIN throne_players p ON p.steamid = d.steamid
			                      	LEFT JOIN ((SELECT COUNT(*) as wins, steamid FROM throne_scores WHERE rank = 1 GROUP BY steamid) AS w) ON w.steamid = d.steamid
			                      	ORDER BY c.ranks ASC
									LIMIT :start, :size");
		$stmt->execute(array(":start" => $start, ":size" => $size));
		$entries = $stmt->fetchAll();

		return $entries;
	}

	// Creates a leaderboard based on either a date in YYYY-MM-DD format or
	// an offset from today's date.
	// Offsets the leaderboard from the start with $start and defines its length
	// with $size.
	// Returns the class instance for easy manipulation. Results are stored in
	// $this->scores.
	public function create_global($date, $order_by = "rank", $direction = "ASC", $start = 0, $length = 30) {
		if (is_int($date)) {
			$leaderboard = $this->db->query('SELECT * FROM throne_dates ORDER BY dayId DESC');
    		$result = $leaderboard->fetchAll();
    		$dateId = $result[$date]['dayId'];
    		$date = $result[$date]['date'];
		}
		$this->date = $date;
		$stats = $this->db->query("SELECT COUNT(*) AS runcount, AVG(score) AS avgscore
			FROM throne_scores
			LEFT JOIN throne_dates ON throne_scores.dayId = throne_dates.dayId
			WHERE `date` = '" . $date . "'");
		$this->global_stats = $stats->fetchAll()[0];
		return $this->make_leaderboard("date", $date, $order_by, $direction, $start, $length);
	}

	// Creates a leaderboard based on a steamid.
	public function create_player($steamid, $order_by = "date", $direction = "DESC", $start = 0, $length = 0, $date = -1) {
		return $this->make_leaderboard("throne_scores`.`steamid", $steamid, $order_by, $direction, $start, $length, $date);
	}

	public function to_array($start = 0, $length = -1) {
		$array_scores = array();
		if ($length == -1) {
			$length = count($this->scores) + 1;
		}
		foreach (array_slice($this->scores, $start, $length, TRUE) as $score) {
			$array_scores[] = $score->to_array();
		}
		return $array_scores;
	}

	public function get_global_stats() {
		$array_scores = array();

		foreach ($this->scores as $score) {
			$array_scores[] = $score->score;
		}

		asort($array_scores);

		$stats = array();
		$stats["count"] = count($array_scores);
		$stats["sum"] = array_sum($array_scores);
		$stats["average"] = round($stats["sum"] / max($stats["count"], 1));
		$top10 = array_slice($array_scores, -10);
		$stats["average_top10"] = round(array_sum($top10) / max(count($top10), 1));
		if ($stats["sum"] > 999) {
			$stats["ksum"] = floor($stats["sum"] / 1000) . "K";
		} else {
			$stats["ksum"] = $stats["sum"];
		}

		return $stats;
		/* return $this->db->query('SELECT COUNT(*) AS amount, ROUND(AVG(score)) AS average
			FROM throne_scores
			LEFT JOIN throne_dates ON throne_scores.dayId = throne_dates.dayId
			WHERE `date` = "' . $this->date . "\"")->fetchAll(PDO::FETCH_ASSOC)[0]; */
	}

	// Helper function to help build the query.
	private function make_leaderboard($where, $condition, $order_by, $direction, $start = 0, $len = 0, $date = -1) {

		if ($len > 0) {
			$limit = "LIMIT $start, $len";
		} else {
			$limit = "";
		}

		if ($date > -1) {
			$leaderboard = $this->db->query('SELECT * FROM throne_dates ORDER BY dayId DESC');
    		$result = $leaderboard->fetchAll();
    		$date_today = $result[$date]['date'];
    		$date_query = "AND throne_dates.date = '" . $date_today . "'";
		} else {
			$date_query = "";
		}
		try {
			$query = $this->db->prepare("SELECT * FROM `throne_scores`
				LEFT JOIN throne_dates ON throne_scores.dayId = throne_dates.dayId
				LEFT JOIN throne_players ON throne_scores.steamId = throne_players.steamid
				LEFT JOIN
					((SELECT COUNT(*) AS wins, steamid
					FROM throne_scores
					WHERE rank = 1
					GROUP BY steamid) AS w) ON w.steamid = throne_scores.steamid
				LEFT JOIN (
					SELECT dayid AS d, COUNT(*) AS runs
					FROM throne_scores
					GROUP BY dayid) x ON x.d = throne_scores.dayId
				WHERE `$where` = :cnd
				$date_query
				ORDER BY `$order_by` $direction
				$limit"	);
			$query->execute(array(":cnd" => $condition));
			$entries = $query->fetchAll();

		} catch (Exception $e) {
			die ("Error fetching leaderboard: " . $e->getMessage());
		}

		$scores = array();

		$parsedown = new Parsedown();
		foreach ($entries as $entry) {

			$meta = array("wins" => $entry["wins"]);
			$meta_scores = array("date" => $entry["date"],
				"hash" => $entry["hash"],
				"video" => $entry["video"],
				"comment" => $parsedown->text($entry["comment"]));
			$player = new Player(array(	"steamid" => $entry["steamId"],
										"name" => $entry["name"],
										"avatar" => $entry["avatar"],
										"suspected_hacker" => $entry["suspected_hacker"],
										"admin" => $entry["admin"],
										"twitch" => $entry["twitch"],
										"donated" => $entry["donated"],
										"raw" => $meta));

			$scores[] = new Score(array(	"player" => $player,
											"score" => $entry["score"],
											"hidden" => $entry["hidden"],
											"rank" => $entry["rank"],
											"first_created" => $entry["first_created"],
											"percentile" => $entry["rank"] / $entry["runs"],
											"raw" => $meta_scores));
		}
		$this->scores = $scores;
		return $this;
	}

}

?>
