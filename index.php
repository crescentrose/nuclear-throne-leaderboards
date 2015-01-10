<?php

// Darwinian 100% Professional PHP Controller Page Router Thing(tm)(r)
// Safety of using in production: 1%

// enable development options
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require "config.php";

// include and register Twig auto-loader
require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader/*, array('cache' => 'cache', 'debug' => false)*/);

// List legal controllers - everything else will go to 404.
$controller_list = array();

foreach (glob("controllers/*.php") as $filename)
{
  preg_match("/controllers\/(\w*)\.php/xi", $filename, $match);
  $controller_list[] = $match[1];
}

// include all models
foreach (glob("models/*.php") as $filename)
{
  include $filename;
}


// route requests
if (isset($_GET['do'])) {
  // see if the page requested is in the controllers list
  if (array_search($_GET['do'], $controller_list) === false) {
    // if not, output 404
    echo $twig->render('404.php', get_config());
  } else {
    // Include the controller for the requested file
    include "controllers/" . $_GET["do"] . ".php";
  }
} else {
  echo $twig->render('index.php', get_latest_daily());
}

?>
