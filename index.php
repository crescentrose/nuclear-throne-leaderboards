<?php


$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

// Darwinian 100% Professional PHP Controller Page Router Thing(tm)(r)
// Safety of using in production: 1%

require "config.php";

if ($config_development == true) {
    // enable development options
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    ini_set("xdebug.var_display_max_depth", "-1");
}

// include and register Twig auto-loader
require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig   = new Twig_Environment($loader /*, array('cache' => 'cache', 'debug' => false)*/ );


// include all models
foreach (glob("models/*.php") as $filename) {
    include $filename;
}

Application::connect();

session_start();

$openid = new LightOpenID($steam_callback);
if (!$openid->mode) {
    if (isset($_GET['login'])) {
        if ($_POST["remember-me"] == "remember-me")
            $_SESSION["persist_login"] = true;

        $openid->identity = 'http://steamcommunity.com/openid/?l=english';
        header('Location: ' . $openid->authUrl());
    }
} else {
    if ($openid->validate()) {
        $id  = $openid->identity;
        $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
        preg_match($ptn, $id, $matches);
        $_SESSION["steamid"] = $matches[1];
        $url          = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $steam_apikey . "&steamids=" . $matches[1];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.thronebutt.com");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        $json_decoded = json_decode($result);

        foreach ($json_decoded->response->players as $player) {
          $_SESSION["steamname"] = $player->personaname;
        }

        if (isset ($_SESSION["persist_login"])) {
            Application::generate_token($_SESSION["steamid"]);
        }
    }
}

if (isset($_SESSION["steamid"])) {
    $_SESSION["admin"] = check_your_privilege($_SESSION["steamid"]);
}

if (isset($_COOKIE["authtoken"]) && !isset($_SESSION["steamid"])) {
    $steamid_login = Application::check_login($_COOKIE["authtoken"]);

    if ($steamid_login != false) {
        $_SESSION["steamid"] = $steamid_login;
        $url          = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $steam_apikey . "&steamids=" . $steamid_login;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        $json_decoded = json_decode($result);

        foreach ($json_decoded->response->players as $player) {
          $_SESSION["steamname"] = $player->personaname;
        }
    }
}

if (isset($_GET["logout"])) {
    Application::remove_token($_COOKIE["authtoken"]);
    session_destroy();
    session_unset();
    header('Location: ' . $steam_callback);
}
// List legal controllers - everything else will go to 404.
$controller_list = array();

foreach (glob("controllers/*.php") as $filename) {
    preg_match("/controllers\/(\w*)\.php/xi", $filename, $match);
    $controller_list[] = $match[1];
}


// route requests
if (isset($_GET['do'])) {
    // see if the page requested is in the controllers list
    if (array_search($_GET['do'], $controller_list) === false) {
        // if not, output 404
        echo $twig->render('404.php');
    } else {
        // Include the controller for the requested file
        include "controllers/" . $_GET["do"] . ".php";
    }
} else {
    include "controllers/index.php";
}
$data = array('session' => $_SESSION,
            'weekday' => date("w") + 1,
            'get' => $_GET,
            'notice' => @file_get_contents("announcement.txt"));

if (isset($_GET['json'])) {
    json($data);
} else {
    render($twig, $data);
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 4);
    echo '<!-- Page generated in '.$total_time.' seconds. -->';
}
?>
