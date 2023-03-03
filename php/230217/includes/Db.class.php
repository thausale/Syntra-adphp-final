<?php

class Db
{

    // Database configuration
    private $host = 'db';
    private $dbname = 'syntrafs';
    private $username = 'root';
    private $password = 'rootpass';
    private $port = 3306;
    private $pdo;

    // Create a new PDO instance
    public function __construct()
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                    $this->username,
                    $this->password
                );
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }
    }

    public function executeQuery($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //   public function getRows($table, ) {

    //   }

    //   public function getTracks()
    //   {
    //       $pdo = $this->connect();
    //       $stmt = $pdo->prepare("SELECT * FROM 230217_tracks LIMIT 10");
    //       $stmt->execute();
    //       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //   }

}
