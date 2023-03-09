<?php
require "includes/Db.class.php";
require "includes/Track.class.php";

$args = $_REQUEST;
$args['qsparts'] = explode('/', $args['qs']);











$response = new StdClass;

switch ($args['qsparts'][1]) {
  case 'tracks':
    $db = new Db();
    $track = new Track($db);
    $limit = 50;

    $filters = [];
    $allowed_filters = [
      'genre',
      'artist_name',
      'track_id'
    ];

    foreach ($_GET as $key => $value) {
      if (!in_array($key, $allowed_filters) && $key !== 'page' && $key != 'qs') {
        http_response_code(500);
        $response->error = "$key is not a valid filter.";
        break 2;
      }
    }

    foreach ($allowed_filters as $allowed_filter) {
      if (isset($args[$allowed_filter])) {
        $filters[$allowed_filter] = $args[$allowed_filter];
      }
    }

    if (isset($args['genre'])) {
      $filters['genre'] = $args['genre'];
    }
    if (isset($args['track_id'])) {
      $filters['track_id'] = $args['track_id'];
    }

    if (isset($args['artist_name'])) {
      $filters['artist_name'] = $args['artist_name'];
    }

    $response->page = 1;
    $response->total_items = $track->getTotal($filters);
    $response->total_pages = ceil($response->total_items / $limit);

    if (
      isset($args['page']) &&
      (is_numeric($args['page'])) &&
      ($args['page'] <= $response->total_pages) &&
      ($args['page'] > 0)
    ) {
      $response->page = (int)$args['page'];
    }

    // if (isset($filters['track_id']) || isset($filters['genre']) || isset($filters['artist_name'])) {
    //   $response->error = "not a valid search item";
    // }
    // $filtercount = 0;

    // isset($filters['track_id']) ? $filtercount++ : "";
    // isset($filters['genre']) ? $filtercount++ : "";
    // isset($filters['artist_name']) ? $filtercount++ : "";
    // if (count($filters) != $filtercount) {
    //   $response->error = "wrong filter";
    //   exit;
    // } else {
    //   exit;
    //   $response->results = $track->getAll(($response->page - 1) * $limit, $limit, $filters);
    // } We moesten zorgen dat als we een query meegeven die we niet kennen we een error tonen
    // ik vind het ambetant omdat Kristof heel anders werkte dan ik,
    //Hij doet de getal in de index, ik deed de getal in de class en gaf het object terug
    //Nu kan ik het moeilijk tegenhouden zonder alles te herschrijven, en ik ben mijn oude code kwijtgeraakt
    // var_dump($args);




    if ($response->page < $response->total_pages) {
      $filters['page'] = $response->page + 1;
      $response->next_page_url = 'http://localhost/230217/api/v1/tracks?' . http_build_query($filters);
    }

    if ($response->page > 1) {
      $filters['page'] = $response->page - 1;
      $response->next_page_url = 'http://localhost/230217/api/v1/tracks?' . http_build_query($filters);
    };

    // if (count($filters) > 0 && $response->results == []) {
    //   $response->error = "no data found";
    // }

    break;


  case "track":
    $db = new Db();
    $track = new Track($db);
    $limit = 50;

    if (isset($args["qsparts"][2]) && !empty($args["qsparts"][2])) {

      $id = $args["qsparts"][2];
      $response->data = $track->getById($id)[0];

      if (empty($response->data)) {
        $response->error = "Data is empty";
      }
    } else {
      $response->error = "This is not a valid endpoint.";
    }


    break;

  default:
    $response->error = "This is not a valid endpoint.";
    break;
}


header('Content-Type: application/json; charset=utf-8');
print json_encode($response);
exit;