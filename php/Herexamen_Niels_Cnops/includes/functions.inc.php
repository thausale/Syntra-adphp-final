<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getMovies()
{
  global $pdo;
  $sql = "SELECT * FROM 230302_movies WHERE published = 1 ORDER BY score DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_OBJ);
  return $result;
};

function getMovieById($id)
{
  global $pdo;
  $data = [
    "id" => $id
  ];
  $sql = "SELECT * FROM 230302_movies WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($data);
  $result = $stmt->fetchAll(PDO::FETCH_OBJ);
  return $result;
};


function deleteFromDatabase($id)
{
  global $pdo;
  $data = ["id" => $id];
  $sql = "UPDATE  230302_movies SET published = 0 WHERE id = " . $id;
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
}



function printDeletedInfo()
{
  if (isset($_GET["delete"]) && $_GET["delete"] !== "") {
    global $info;
    $name = $info[0]->name;
    echo "You have deleted {$name}";
  }
}

//YOU NEED cnPDOconnection FOR THE GLOBAL VARIABLE!
function updateDatabase($data)
{

  var_dump($data);
  $id = $data["id"];
  $name = $data["name"];
  global $pdo;
  $sql = "UPDATE `230302_movies` SET `name` = :name, `studio` = :studio , `genre` = :genre , `score` = :score , `year` = :year , `published` = :published WHERE `id` = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($data);

  header("location: index.php?editId=$id&name=$name");
}


function printError($errors)
{
  if (isset($_GET["error"])) {
    print $errors[$_GET["error"]];
  }
}

function printEditInfo()
{

  if (isset($_GET["editId"]) && isset($_GET["name"])) {
    $name = $_GET["name"];
    print "Your changes to $name were saved!";
  }
}

function phpLoverFunction($optionField)
{
  global $genre;
  if ($optionField === $genre) {
    print "<option value=$optionField selected>$optionField </option>";
  } else {
    print "<option value=$optionField>$optionField </option>";
  }
}
