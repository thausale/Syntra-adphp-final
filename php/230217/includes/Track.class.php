<?php

class Track
{

    private $db;
    private $genre;
    private $name;
    //...

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllTracks()
    {
        $sql = "SELECT * FROM 230217_tracks LIMIT 100";
        return $this->db->executeQuery($sql);
    }
}
