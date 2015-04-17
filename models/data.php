<?php
date_default_timezone_set("UTC");
global $db;

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