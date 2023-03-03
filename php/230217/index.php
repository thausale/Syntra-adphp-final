<?php

require "includes/Db.class.php";
require "includes/Track.class.php";

$db = new Db();
$track = new Track($db);


// $tracks = Db::getTracks();
// $tracks = $db->getTracks();



print "<pre>";
print_r($track->getAllTracks());
