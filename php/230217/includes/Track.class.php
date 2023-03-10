<?php

class Track
{

  private $db;


  public function __construct($db)
  {
    $this->db = $db;
  }


  public function getTotal($filters = [])
  {
    $where = $this->buildWhere($filters);
    $sql = "SELECT COUNT(id) AS total FROM 230217_tracks $where";
    return $this->db->executeQuery($sql, $filters)[0]->total;
  }


  public function getAll($offset = 0, $limit = 50, $filters = [])
  {
    $where = $this->buildWhere($filters);
    $sql = "SELECT id, track_id, track_name, artist_name, genre FROM 230217_tracks $where LIMIT $limit OFFSET $offset";
    return $this->db->executeQuery($sql, $filters);
  }

  public function getById($id, $offset = 0, $limit = 50)
  {
    $filters = ["id" => $id];
    $where = $this->buildWhere($filters);
    $sql = "SELECT * FROM 230217_tracks $where LIMIT $limit OFFSET $offset";
    return $this->db->executeQuery($sql, $filters);
  }

  public function insertIntoDb($body)
  {
    $table_name = '230217_tracks';

    // Build the column names and values for the INSERT query
    $columns = array_keys($body);

    $placeholders = implode(',', array_map(function ($key) {
      return ":$key";
    }, $columns));


    $sql = "INSERT INTO $table_name (" . implode(',', $columns) . ") VALUES ($placeholders)";



    $id = $this->db->executeInsert($sql, $body);
    return $id;
  }

  public function removeById($id)
  {
    $filters = ["id" => $id];
    $where = $this->buildWhere($filters);
    $sql = "SELECT COUNT(*) as count FROM 230217_tracks $where";
    $result = $this->db->executeQuery($sql, $filters, PDO::FETCH_ASSOC);
    $count = $result[0]['count'];

    if ($count > 0) {
      $sql = "DELETE FROM 230217_tracks $where";
      $this->db->executeQuery($sql, $filters);
      return "track with ID: $id has been deleted";
    } else {
      return "ID not found";
    }
  }


  private function buildWhere($filters)
  {
    $where = '';
    if (count($filters)) {
      $where = [];
      foreach ($filters as $key => $value) {
        $where[] = "$key = :$key";
      }
      $where = 'WHERE ' . implode(' AND ', $where);
    }
    return $where;
  }
}
