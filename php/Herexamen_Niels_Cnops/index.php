<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "./includes/db.inc.php";
require "./includes/functions.inc.php";



if (isset($_GET["delete"]) && $_GET["delete"] !== "") {

  $id = $_GET["delete"];
  deleteFromDatabase($id);
  $info = getMovieById($id);
  global $info;
}


$movies = getMovies();
print '<pre>';
// print_r($movies[0]);
// print_r($_GET["delete"]);


$pdo = null
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies Ranking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">

        <h2>
            <? printDeletedInfo() ?>
        </h2>
        <h2><?= printEditInfo() ?></h2>
        <h1>Movies Ranking</h1>

        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Genre</th>
                    <th>Studio</th>
                    <th>Year</th>
                    <th>Score</th>
                    <th></th>
                </tr>
            </thead>


            <tbody>
                <?php foreach ($movies as $key => $movie) { ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $movie->name ?></td>
                    <td><?= $movie->genre ?></td>
                    <td><?= $movie->studio ?></td>
                    <td><?= $movie->year ?></td>
                    <td><?= $movie->score ?></td>
                    <td><a href="edit.php?id=<?= $movie->id ?>">edit</a> - <a
                            href="index.php?delete=<?= $movie->id ?>">delete</a></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>
</body>

</html>