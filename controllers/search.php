<?php

function render($twig, $sdata = array()) {
  $term = html_entity_decode($_GET["q"]);
  $results = Application::search_user($term);
  if (count($results) == 1) {
    header("Location: /player/" . $results[0]["steamid"]);
  } else {
    $data = array("results" => $results, "count" => count($results), "query" => $_GET["q"]);
  }
  echo $twig->render('search.php', array_merge($sdata, $data));
}

?>
