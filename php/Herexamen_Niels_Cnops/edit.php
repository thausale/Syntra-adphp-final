<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "./includes/db.inc.php";
require "./includes/functions.inc.php";






// VALIDATIONS
$year = date("Y");
$errors = [
    "Please fill in all the fields before submitting", "Name can not be empty", "score can not be 0 or below", "score can not be above 100",
    "studio can not be empty", "year has to be between 1900 and $year"
];

if (!isset($_GET["id"])) {
    header("Location: index.php");
}


if (isset($_POST) && count($_POST) == 7 || count($_POST) == 6 && count($_POST) !== 0) {
    $id = $_GET["id"];
    if ($_POST["name"] == "") {
        header("Location: edit.php?error=1&id=$id");
    } else if ($_POST["studio"] == "") {
        header("Location: edit.php?error=4&id=$id");
    } else if ($_POST["year"] > $year || $_POST["year"] < 1900) {
        header("Location: edit.php?error=5&id=$id");
    } else if ($_POST["score"] > 100) {
        header("Location: edit.php?error=3&id=$id");
    } else if ($_POST["score"] < 1) {
        header("Location: edit.php?error=2&id=$id");
    }





    //POSTING
    else {

        $data = $_POST;
        if (isset($data["published"])) {
            $data["published"] = 1;
        } else {
            $data["published"] = 0;
        }
        updateDatabase($data);
    }
}

//GET BY ID

$movie = getMovieById($_GET["id"])[0];

$genre = $movie->genre;
$genres = ["Romance", "Comedy", "Drama", "Animation", "Fantasy", "Action", "SciFi", "Horror"]

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit page</title>

    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 90vh;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 70vw;
            font-size: xx-large;
        }

        form * {
            font-size: xx-large;
        }

        #checkbox {
            transform: scale(3);
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
    <h2><?= printError($errors) ?></h2>
    <a href="index.php"><button>home</button></a>
    <form action="edit.php?id=<?= $_GET["id"] ?>" method="post">
        <input type="hidden" name="id" value="<?= $_GET["id"] ?>">
        <p>name</p>
        <input type="text" name="name" id="" placeholder="<?= $movie->name ?>">
        <p>studio</p>
        <input type="text" name="studio" id="" placeholder="<?= $movie->studio ?>">
        <p>genre</p>
        <select name="genre">
            <?php foreach ($genres as $item) {
                phpLoverFunction($item);
            } ?>
        </select>
        <p>score</p>
        <input type="number" name="score" id="score" placeholder="<?= $movie->score ?>">
        <p>year</p>
        <input type="number" name="year" id="year" placeholder="<?= $movie->year ?>">
        <p>published</p>
        <input type="checkbox" name="published" id="checkbox">
        <button type="submit">Submit</button>
    </form>
</body>

</html>