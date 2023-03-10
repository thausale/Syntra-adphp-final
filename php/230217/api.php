<?php
require "includes/Db.class.php";
require "includes/Track.class.php";

$args = $_REQUEST;
$args['qsparts'] = explode('/', $args['qs']);

$response = new StdClass;
$response->referer = $_SERVER["HTTP_REFERER"];

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

    $response->results = $track->getAll(($response->page - 1) * $limit, $limit, $filters);

    if ($response->page < $response->total_pages) {
      $filters['page'] = $response->page + 1;
      $response->next_page_url = 'http://localhost/230217/api/v1/tracks?' . http_build_query($filters);
    }

    if ($response->page > 1) {
      $filters['page'] = $response->page - 1;
      $response->previous_page_url = 'http://localhost/230217/index.php?page=' . http_build_query($filters);
    }

    break;

  case 'track':

    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST':
        $db = new Db();
        $track = new Track($db);

        $body = $_POST;
        // var_dump($body);


        $id = $track->insertIntoDb($body);
        $response->message = "successfully inserted with ID $id";

        break;


      case "GET":
        $db = new Db();


        //Code to get the data from the table for validation
        // $columns = $db->describeTable();
        // foreach ($columns as $column) {
        //   echo "{$column['Field']} ({$column['Type']})\n";
        // }
        // var_dump($columns);


        // var_dump($args['qsparts'][2]);
        if (isset($args['qsparts'][2]) && !empty($args['qsparts'][2])) {
          $db = new Db();
          $track = new Track($db);
          $response->results = $track->getById($args['qsparts'][2]);
        } else {
          http_response_code(404);
          $response->error = "This is not a valid endpoint.";
        }
        break;

      case "DELETE":
        if (isset($args['qsparts'][2]) && !empty($args['qsparts'][2])) {
          $id = $args['qsparts'][2];
          $db = new Db();
          $track = new Track($db);
          $response->message = $track->removeById($args['qsparts'][2]);
        }
        break;

      default:
        // handle unsupported HTTP methods
        http_response_code(404);
        $response->error = "This is not a valid endpoint.";
        break;
    }
    break;




  default:
    // handle unsupported HTTP methods
    http_response_code(404);
    $response->error = "This is not a valid endpoint.";
    break;
}


header('Content-Type: application/json; charset=utf-8');
print json_encode($response);
exit;
