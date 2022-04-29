<?php

  class Profession {
    // DB stuff
    private $conn;
    private $table = 'profession';

    // Profession Properties
    public $id;
    public $Profession;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    //Validating ID
    public function isProfValid() {
          // Create query
          $query = 'SELECT 1 FROM ' . $this->table . ' WHERE ID=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          // Get row count
          $num = $stmt->rowCount();

          if($num > 0)
            return 1;

            return 0;
    }

    // Get Posts
    public function read() {
      // Create query
      $query = 'SELECT ID,Profession FROM ' . $this->table . ' ORDER BY Profession ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

  }



 ?>
