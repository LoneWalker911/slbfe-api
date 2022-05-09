<?php
  class Database {
    // DB Params
    private $host = 'localhost';
    private $db_name = 'slbfe-api';
    private $username = 'root';
    private $password = '';


    // private $host = 'santhawa.lk';
    // private $db_name = 'santhawa_api';
    // private $username = 'santhawa_api';
    // private $password = '!?pIN{pvn!*x';


    // private $host = 'api-database.c2uijc6ieiin.us-east-2.rds.amazonaws.com';
    // private $db_name = 'api-database';
    // private $username = 'admin';
    // private $password = 'jFXquwSzudijN8wmCIAW';
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try {
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->exec("set names utf8mb4");
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }
