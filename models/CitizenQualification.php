<?php

include_once 'Validate.php';
include_once 'Citizen.php';

  class CitizenQualification extends Citizen {
    // DB stuff
    private $conn;
    private $table = 'citizenqualifications';

    // Citizen Properties
    public $qualifications;
    public $birthcert;
    public $validation=0;
    public $cv;
    public $passport;

    // Constructor with DB
    public function __construct($db) {
      parent::__construct($db);
      $this->conn = $db;
    }


    // Get Single Citizen Qualification
    public function readQ() {

          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"Citizen ID Invalid");

          // Create query
          $query = 'SELECT qualifications,validation,cv,birthcert,passport FROM ' . $this->table . ' WHERE id=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          if($stmt->rowCount()>0){

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->qualifications = $row['qualifications'];
          $this->validation = $row['validation'];
          $this->cv = $row['cv'];
          $this->birthcert = $row['birthcert'];
          $this->passport = $row['passport'];

          return array('qualifications' => $this->qualifications, 'cv' => $this->cv, 'birthcert' => $this->birthcert, 'passport' => $this->passport, 'validatation' => $this->validation);
        }
        else
            throw new PowerfulAPIException(404, "No Citizens Found");
    }



    public function updateQ() {

          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"Citizen ID Invalid");


          // Create query
          $query = 'INSERT INTO citizenqualifications
                      (id, qualifications, validation)
                    VALUES
                      (:id, :qualifications, :validation)
                    ON DUPLICATE KEY UPDATE
                      qualifications  = VALUES(qualifications),
                      validation  = VALUES(validation)';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':qualifications', $this->qualifications);
          $stmt->bindParam(':validation', $this->validation);


          // Execute query
        if($stmt->execute()){
              return array('status' => 1, 'message' => 'Qualification updated successfully.');
          }
        else{
              return array('status' => 0, 'message' => 'Process has failed.');
          }
    }


    public function validateQ() {

          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"Citizen ID Invalid");


          // Create query
          $query = 'UPDATE '.$this->table.' SET validation=:validation WHERE id=:id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':validation', $this->validation);


          // Execute query
        if($stmt->execute()){
              if($this->validation==2)
              return array('status' => 1, 'message' => 'Qualification validated successfully.');

              if($this->validation==1)
              return array('status' => 1, 'message' => 'Qualification rejected successfully.');

          }
        else{
              return array('status' => 0, 'message' => 'Process has failed.');
          }
    }


    private function processFile($file,$name,$extention)
    {
      if(empty($file["tmp_name"]))
        return null;

      $target_dir = "C:/xampp/htdocs/slbfe-api/Files/".$this->id.'/';
      $url = "http://localhost/slbfe-api/Files/".$this->id.'/';

      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      if(!in_array(strtoupper($ext),$extention))
        throw new PowerfulAPIException(422,"Invalid File Type for ".$name);

      $target_file = $target_dir . $name . "." . $ext;
      if(!file_exists($target_dir)) mkdir($target_dir);
      move_uploaded_file($file["tmp_name"],$target_file);
      return $url .=$name . "." . $ext;

    }


    // Upload Files
    public function uploadQ() {

      if(!$this->isidValid())
        throw new PowerfulAPIException(422,"Citizen ID Invalid");

        //  if(empty($_FILES["cv"]) || empty($_FILES["birthcert"]) ||
        //   empty($_FILES["passport"]))
        //    throw new PowerfulAPIException(403, null);

          $this->cv = $this->processFile($_POST["cv"],"CV",['PDF','DOCX']);
          $this->birthcert = $this->processFile($_FILES["birthcert"],"BIRTHCERT",['PDF','JPG','PNG','JPEG']);
          $this->passport = $this->processFile($_FILES["passport"],"PASSPORT",['PDF','JPG','PNG','JPEG']);


          // Create query
          $query = 'INSERT INTO citizenqualifications
                      (id, cv, birthcert, passport, validation)
                    VALUES
                      (:id, :cv, :birthcert, :passport, :validation)
                    ON DUPLICATE KEY UPDATE
                      cv  = VALUES(cv),
                      birthcert  = VALUES(birthcert),
                      passport  = VALUES(passport),
                      validation  = VALUES(validation)';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          $this->cv = empty($this->cv) ? null : $this->cv;
          $this->birthcert = empty($this->birthcert) ? null : $this->birthcert;
          $this->passport = empty($this->passport) ? null : $this->passport;


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':cv', $this->cv);
          $stmt->bindParam(':birthcert',$this->birthcert );
          $stmt->bindParam(':passport', $this->passport);
          $stmt->bindParam(':validation', $this->validation);


          // Execute query
          if($stmt->execute()){
                return array('status' => 1, 'message' => 'Uploaded successfully.');
            }
          else{
                return array('status' => 0, 'message' => 'Process has failed.');
            }

    }


    // Update Post
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . '
                                SET title = :title, body = :body, author = :author, category_id = :category_id
                                WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->title = htmlspecialchars(strip_tags($this->title));
          $this->body = htmlspecialchars(strip_tags($this->body));
          $this->author = htmlspecialchars(strip_tags($this->author));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':title', $this->title);
          $stmt->bindParam(':body', $this->body);
          $stmt->bindParam(':author', $this->author);
          $stmt->bindParam(':category_id', $this->category_id);
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }

    public function search() {

          // Create query
          $query = "SELECT citizen.*
                    FROM citizenqualifications, citizen, login
                    WHERE
                          citizen.id=citizenqualifications.id AND
                          login.id=citizen.id AND
                          login.status=1 AND
                          MATCH(qualifications) AGAINST(:q IN NATURAL LANGUAGE MODE)";


          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':q', $this->qualifications);

          // Execute query
          if($stmt->execute()) {

            return $stmt;

          }


          return array('status' => 0, 'message' => 'Process has failed.');
    }

  }
