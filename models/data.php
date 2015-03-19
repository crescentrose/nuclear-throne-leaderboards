<?php
date_default_timezone_set("UTC");

function get_latest_daily($page = 0)
{
    global $db_username, $db_password;
    $db                = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    $players           = array();
    $players_yesterday = array();
    $streams           = array();
    
    // Get latest day ID (clumsy!)
    $daily      = $db->query('SELECT * FROM throne_dates ORDER BY dayId DESC LIMIT 0,2');
    $result     = $daily->fetchAll();
    $today      = $result[0]['dayId'];
    $today_date = $result[0]['date'];
    
    $yesterday = $result[1]['dayId'];
    
    foreach ($db->query('SELECT throne_scores.steamId, throne_players.suspected_hacker, throne_scores.hash, throne_scores.hidden, throne_scores.rank, throne_scores.score, throne_players.name, throne_players.avatar, w.wins FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId LEFT JOIN ((SELECT COUNT(*) as wins, steamid FROM throne_scores WHERE rank = 1 GROUP BY steamid) AS w) ON w.steamid = throne_scores.steamid WHERE throne_scores.dayId = ' . $yesterday . ' ORDER BY rank ASC LIMIT 0,5') as $row) {
        $row['avatar_medium'] = substr($row['avatar'], 0, -4) . "_medium.jpg";
        if ($row['name'] === "") {
            $row['name'] = "[no profile]";
        } //$row['name'] === ""
        if ($row['avatar'] === "") {
            $row['avatar_medium'] = "/img/no-avatar.png";
            $row['avatar']        = "/img/no-avatar-small.png";
        } //$row['avatar'] === ""
        $players_yesterday[] = $row;
    } //$db->query('SELECT throne_scores.steamId, throne_players.suspected_hacker, throne_scores.hash, throne_scores.rank, throne_scores.score, throne_players.name, throne_players.avatar FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId WHERE throne_scores.dayId = ' . $yesterday . ' ORDER BY rank ASC LIMIT 0,5') as $row
    
    foreach ($db->query('SELECT throne_scores.steamId, throne_players.suspected_hacker, throne_scores.hash, throne_scores.rank, throne_scores.score, throne_players.name, throne_scores.last_updated, throne_scores.hidden, throne_scores.first_created, throne_players.avatar, w.wins FROM throne_scores LEFT JOIN throne_players ON throne_players.steamid = throne_scores.steamId LEFT JOIN ((SELECT COUNT(*) as wins, steamid FROM throne_scores WHERE rank = 1 GROUP BY steamid) AS w) ON w.steamid = throne_scores.steamid WHERE throne_scores.dayId = ' . $today . ' ORDER BY rank ASC LIMIT ' . $page * 30 . ', 30') as $row) {
        $row['avatar_medium'] = substr($row['avatar'], 0, -4) . "_medium.jpg";
        if ($row['name'] === "") {
            $row['name'] = "[no profile]";
        } //$row['name'] === ""
        if ($row['avatar'] === "") {
            $row['avatar_medium'] = "/img/no-avatar.png";
            $row['avatar']        = "/img/no-avatar-small.png";
        } //$row['avatar'] === ""
        if ($row['first_created'] == "0000-00-00 00:00:00") {
            $row['first_created'] = "n/a";
        }
        $players[] = $row;
    }
    $results     = $db->query('SELECT * FROM throne_streams ORDER BY viewers DESC LIMIT 0,3');
    $streamcount = $results->rowCount();
    foreach ($results as $row) {
        $streams[] = $row;
    } //$results as $row
    
    $globalstats = $db->query('SELECT COUNT(*) AS amount, AVG(score) AS average FROM throne_scores WHERE throne_scores.dayId = ' . $today)->fetchAll(PDO::FETCH_ASSOC);

    return array(
        'date' => $today_date,
        'players' => $players,
        'players_yesterday' => $players_yesterday,
        'streams' => $streams,
        'streamcount' => $streamcount,
        'global' => $globalstats[0],
        'page' => $page + 1
    );
}

function get_alltime($page = 1, $sort = "total")
{
    global $db_username, $db_password;
    $db      = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    $alltime = array();
    $page    = $page - 1;
    if ($sort == "avg") {
        $result = $db->query('SELECT  d.*, p.*, c.ranks, r.runs, w.*
                      FROM (
                        SELECT    average, @rank:=@rank+1 Ranks
                        FROM (
                                    SELECT  DISTINCT average 
                                    FROM    throne_alltime a
                                    ORDER   BY average DESC
                                ) t, (SELECT @rank:= 0) r
                               ) c 
                      INNER JOIN throne_alltime d ON c.average = d.average
                      LEFT JOIN throne_players p ON p.steamid = d.steamid
                      LEFT JOIN ((SELECT COUNT(*) as wins, steamid FROM throne_scores WHERE rank = 1 GROUP BY steamid) AS w) ON w.steamid = d.steamid
                      LEFT JOIN (
                        SELECT steamid, COUNT(*) AS runs FROM throne_scores GROUP BY steamid
                      ) r ON r.steamid = d.steamid 
                        ORDER BY c.ranks LIMIT ' . $page * 30 . ', 30');
    } else {
        $result = $db->query('SELECT  d.*, p.*, c.ranks, r.runs, w.*
                      FROM (
                        SELECT    score, @rank:=@rank+1 Ranks
                        FROM (
                                    SELECT  DISTINCT Score 
                                    FROM    throne_alltime a
                                    ORDER   BY score DESC
                                ) t, (SELECT @rank:= 0) r
                               ) c 
                      INNER JOIN throne_alltime d ON c.score = d.score
                      LEFT JOIN throne_players p ON p.steamid = d.steamid
                       LEFT JOIN ((SELECT COUNT(*) as wins, steamid FROM throne_scores WHERE rank = 1 GROUP BY steamid) AS w) ON w.steamid = d.steamid
                      LEFT JOIN (
                        SELECT steamid, COUNT(*) AS runs FROM throne_scores GROUP BY steamid
                      ) r ON r.steamid = d.steamid LIMIT ' . $page * 30 . ', 30'); 
    }
    foreach ($result as $row) {
        if ($row['name'] === "") {
            $row['name'] = "[no profile]";
        } //$row['name'] === ""
        if ($row['avatar'] === "") {
            $row['avatar'] = "/img/no-avatar-small.png";
        } //$row['avatar'] === ""
        $alltime[] = $row;
    } //$db->query('SELECT d.*, p.*, c.ranks, r.runs FROM ( SELECT score, @rank:=@rank+1 Ranks FROM ( SELECT DISTINCT Score FROM throne_alltime a ORDER BY score DESC ) t, (SELECT @rank:= 0) r ) c INNER JOIN throne_alltime d ON c.score = d.score LEFT JOIN throne_players p ON p.steamid = d.steamid LEFT JOIN ( SELECT steamid, COUNT(*) AS runs FROM throne_scores GROUP BY steamid ) r ON r.steamid = d.steamid LIMIT ' . $page * 30 . ', 30') as $row
    
    return array(
        'alltime' => $alltime,
        'page' => $page + 1,
        'sort' => $sort
    );
}


function get_score($hash)
{
    global $db_username, $db_password;
    $db   = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    $stmt = $db->prepare("SELECT * FROM throne_scores LEFT JOIN throne_players on throne_scores.steamId = throne_players.steamid LEFT JOIN throne_dates ON throne_dates.dayId = throne_scores.dayId WHERE hash = :hash");
    $stmt->execute(array(
        ':hash' => $hash
    ));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() == 1) {
        $rows[0]['avatar_medium'] = substr($rows[0]['avatar'], 0, -4) . "_medium.jpg";
        if ($rows[0]['name'] === "") {
            $rows[0]['name'] = "[no profile]";
        } //$rows[0]['name'] === ""
        if ($rows[0]['avatar'] === "") {
            $rows[0]['avatar_medium'] = "/img/no-avatar.png";
            $rows[0]['avatar']        = "/img/no-avatar-small.png";
        } //$rows[0]['avatar'] === ""
        return $rows[0];
    } //$stmt->rowCount() == 1
    else {
        return false;
    }
}

function get_player($steamid)
{
    global $db_username, $db_password;
    $db = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    if ((int) $steamid == 0) {
        $steamid = convertSteamId($steamid);
        if ($steamid == false) {
            return false;
        } //$steamid == false
    } //(int) $steamid == 0
    $stmt = $db->prepare("SELECT * FROM throne_players WHERE steamid = :steamid");
    $stmt->execute(array(
        ':steamid' => $steamid
    ));
    if ($stmt->rowCount() === 0) {
        return false;
    } //$stmt->rowCount() === 0
    $rows                    = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $player                  = $rows[0];
    $player['avatar_medium'] = substr($player['avatar'], 0, -4) . "_medium.jpg";
    if ($player['name'] === "") {
        $player['name'] = "[no profile]";
    } //$player['name'] === ""
    if ($player['avatar'] === "") {
        $player['avatar_medium'] = "/img/no-avatar.png";
        $player['avatar']        = "/img/no-avatar-small.png";
    } //$player['avatar'] === ""
    $scores = array();
    $stmt   = $db->prepare('SELECT * FROM throne_scores 
                      LEFT JOIN throne_dates ON throne_scores.dayId = throne_dates.dayId
                      LEFT JOIN (SELECT dayid AS d, COUNT(*) AS runs FROM throne_scores GROUP BY dayid) x ON x.d = throne_scores.dayId
                      WHERE throne_scores.steamId = :steamid
                      ORDER BY throne_scores.dayId ASC LIMIT 100');
    $stmt->execute(array(
        ":steamid" => $steamid
    ));
    
    $allscores  = array();
    $totalscore = 0;
    $allrank    = array();
    $totalrank  = 0;
    $count      = 0;
    
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $score) {
        $score['percentage'] = ceil(((int) $score['rank'] / (int) $score['runs']) * 100);
        $score['score'] = max(0, $score['score']);	// Make sure that the score shown is at least 0.
        $scores[]            = $score;
        $totalscore += $score['score'];
        $totalrank += $score['rank'];
        $count       = $count + 1;
        $allscores[] = $score['score'];
        $allrank[]   = $score['rank'];
    } //$stmt->fetchAll(PDO::FETCH_ASSOC) as $score
    
    $stmt = $db->prepare('SELECT  d.*, c.ranks
                        FROM (
                          SELECT    score, @rank:=@rank+1 Ranks
                          FROM (
                            SELECT  DISTINCT Score 
                            FROM    throne_alltime a
                            ORDER   BY score DESC
                          ) t, (SELECT @rank:= 0) r
                        ) c 
                        INNER JOIN throne_alltime d ON c.score = d.score
                        WHERE d.steamid = :steamid');
    $stmt->execute(array(
        ":steamid" => $steamid
    ));
    if ($stmt->rowCount() === 0) {
        $player['totalrank'] = -1;
    } //$stmt->rowCount() === 0
    else {
        $row                  = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $player['totalrank']  = $row[0]['ranks'];
        $player['totalkills'] = $row[0]['score'];
    }
    
    $player['avgscore'] = round($totalscore / $count);
    $player['avgrank']  = round($totalrank / $count);
    $player['hiscore']  = max($allscores);
    $player['loscore']  = min($allscores);
    $player['hirank']   = max($allrank);
    $player['lorank']   = min($allrank);
    $player['runs']     = $count;
    return array(
        'player' => $player,
        'steamid' => $steamid,
        'scores' => $scores,
        'scores_reverse' => array_reverse($scores)
    );
}

function get_archive($dayid, $page = 1)
{
    global $db_username, $db_password;
    
    try {
        $date = new DateTime($dayid);
    }
    catch (Exception $e) {
        return false;
    }
    $page = $page - 1;
    $db   = new PDO('mysql:host=localhost;dbname=throne;charset=utf8', $db_username, $db_password, array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    $stmt = $db->prepare("SELECT * FROM throne_scores LEFT JOIN throne_players on throne_scores.steamId = throne_players.steamid LEFT JOIN throne_dates ON throne_dates.dayId = throne_scores.dayId WHERE throne_dates.`date` = :day ORDER BY rank LIMIT " . $page * 30 . ', 30');
    $stmt->execute(array(
        ':day' => $dayid
    ));
    $rows    = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $players = array();
    
    if ($stmt->rowCount() > 0) {
        foreach ($rows as $row) {
            if ($row['name'] === "") {
                $row['name'] = "[private]";
            } //$row['name'] === ""
            $players[] = $row;
        } //$rows as $row
    } //$stmt->rowCount() > 0
    else {
        return false;
    }
    return array(
        'year' => $date->format("Y"),
        'month' => $date->format("m"),
        'day' => $date->format("d"),
        'players' => $players,
        'page' => $page + 1
    );
}

// Ported from my LotusClan web admin interface thing.
function convertSteamId($steamid)
{
    $steamid = trim(strip_tags($steamid));
    // check for community id
    $xml     = @file_get_contents("http://steamcommunity.com/id/" . $steamid . "?xml=1"); // You saw no @ here. 
    
    // Verify if the community ID exists 
    if (preg_match("/<steamID64>(\d*)<\/steamID64>/", $xml, $match)) { // based regex xml reading
        return $match[1];
    } //preg_match("/<steamID64>(\d*)<\/steamID64>/", $xml, $match)
}

?>