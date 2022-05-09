<?php

include_once 'Validate.php';

  class Complaint {
    // DB stuff
    private $conn;
    private $table = 'complaints';

    // Citizen Properties
    public $id;
    public $citizen_id;
    public $complaint;
    public $officer_id;
    public $response;
    public $added_at;
    public $response_at;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }



    public function fileComplaint() {

          $citizen = new Citizen($this->conn);

          $citizen->id = $this->citizen_id;

          if(!$citizen->isidValid())
            throw new PowerfulAPIException(422,"Citizen ID Invalid - 3");


          // Create query
          $query = 'INSERT INTO complaints
                      (citizen_id, complaint)
                    VALUES
                      (:citizen_id, :complaint)';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':citizen_id', $this->citizen_id);
          $stmt->bindParam(':complaint', $this->complaint);


          // Execute query
        if($stmt->execute()){
              return array('status' => 1, 'message' => 'Complaint added successfully.');
          }
        else{
              return array('status' => 0, 'message' => 'Process has failed.');
          }
    }


    public function getComplaints() {

          // Create query
          $query = 'SELECT * FROM complaints ORDER BY added_at ASC';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Execute query
        if($stmt->execute()){

          if($stmt->rowCount()>0){

            $complaints_arr = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              extract($row);

              if($response_at==null)


              $complaint = array(
                'id' => $id,
                'citizen_id' => $citizen_id,
                'complaint' => $complaint,
                'officer_id' => $officer_id,
                'response' => $response,
                'added_at' => $added_at,
                'response_at' => $response_at
              );

              // Push to "data"
              array_push($complaints_arr, $complaint);

            }

          return $complaints_arr;
        }
        else
            throw new PowerfulAPIException(404, "No Citizens Found");
    }
        else{
              return array('status' => 0, 'message' => 'Process has failed.');
          }
    }


    public function getComplaintsById() {

          // Create query
          $query = 'SELECT * FROM complaints WHERE citizen_id=? ORDER BY added_at ASC';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $this->citizen_id);

          // Execute query
        if($stmt->execute()){

          if($stmt->rowCount()>0){

            $complaints_arr = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              extract($row);

              $complaint = array(
                'id' => $id,
                'citizen_id' => $citizen_id,
                'complaint' => $complaint,
                'officer_id' => $officer_id,
                'response' => $response,
                'added_at' => $added_at,
                'response_at' => $response_at
              );

              // Push to "data"
              array_push($complaints_arr, $complaint);

            }

          return $complaints_arr;
        }
        else
            throw new PowerfulAPIException(404, "No Complaints Found");
    }
        else{
              return array('status' => 0, 'message' => 'Process has failed.');
          }
    }


    public function getComplaintsId() {

          // Create query
          $query = 'SELECT * FROM complaints WHERE id=? ORDER BY added_at ASC';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $this->id);

          // Execute query
        if($stmt->execute()){

          if($stmt->rowCount()>0){

            $complaints_arr = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              extract($row);

              $complaint = array(
                'id' => $id,
                'citizen_id' => $citizen_id,
                'complaint' => $complaint,
                'officer_id' => $officer_id,
                'response' => $response,
                'added_at' => $added_at,
                'response_at' => $response_at
              );

              // Push to "data"
              array_push($complaints_arr, $complaint);

            }

          return $complaints_arr;
        }
        else
            throw new PowerfulAPIException(404, "No Complaints Found");
    }
        else{
              return array('status' => 0, 'message' => 'Process has failed.');
          }
    }

    public function isidValid() {
          // Create query
          $query = 'SELECT 1 FROM complaints WHERE id=? AND response_at IS NULL';

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


    public function response() {

          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"Complaint ID Invalid / Already Responded123");


          // Create query
          $query = 'UPDATE '.$this->table.' SET response=:response, officer_id=:officer_id, response_at=NOW() WHERE id=:id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':response', $this->response);
          $stmt->bindParam(':officer_id', $this->officer_id);


          // Execute query
        if($stmt->execute()){
              return array('status' => 1, 'message' => 'Response recorded successfully.');
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
