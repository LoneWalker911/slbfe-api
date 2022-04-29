<?php

include_once 'Validate.php';
include_once 'Login.php';

  class Citizen {
    // DB stuff
    private $conn;
    private $table = 'citizen';

    // Citizen Properties
    public $id;
    public $NIC;
    public $first_name;
    public $last_name;
    public $dob;
    public $lat;
    public $long;
    public $profession;
    public $email;
    public $affiliation;
    public $ContactName1;
    public $ContactName2;
    public $Contact1;
    public $Contact2;
    public $status;
    public $reg_date;
    public $password;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Posts
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY reg_date DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Citizen
    public function read_single() {
          // Create query
          $query = 'SELECT * FROM ' . $this->table . ' WHERE id=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->NIC = $row['NIC'];
          $this->first_name = $row['first_name'];
          $this->last_name = $row['last_name'];
          $this->dob = $row['dob'];
          $this->lat = $row['latitude'];
          $this->long = $row['longitude'];
          $this->profession = $row['profession'];
          $this->email = $row['email'];
          $this->affiliation = $row['affiliation'];
          $this->ContactName1 = $row['ContactName1'];
          $this->ContactName2 = $row['ContactName2'];
          $this->Contact1 = $row['Contact1'];
          $this->Contact2 = $row['Contact2'];
          $this->status = $row['status'];
          $this->reg_date = $row['reg_date'];
    }

    public function isNicNew() {
          // Create query
          $query = 'SELECT 1 FROM ' . $this->table . ' WHERE NIC=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->NIC);

          // Execute query
          $stmt->execute();

          // Get row count
          $num = $stmt->rowCount();

          if($num > 0)
            return 0;

            return 1;
    }

    // Create Post
    public function create() {

          if(!Validate::NIC($this->NIC))
            throw new PowerfulAPIException(422,"NIC Invalid");

          if(!$this->isNicNew())
            throw new PowerfulAPIException(409, "NIC already registered");

          if(!Validate::Name($this->first_name))
            throw new PowerfulAPIException(422,"First Name Invalid");

          if(!Validate::Name($this->last_name))
            throw new PowerfulAPIException(422,"Last Name Invalid");

          if(!Validate::Date($this->dob))
            throw new PowerfulAPIException(422,"Date of Birth Invalid");

          if(!Validate::Latitude($this->lat))
            throw new PowerfulAPIException(422,"Latitude Invalid");

          if(!Validate::Longitude($this->long))
            throw new PowerfulAPIException(422,"Logitude Invalid");

          if(!Validate::Email($this->email))
            throw new PowerfulAPIException(422,"Email Invalid");

          if(!Validate::Profession($this->profession))
            throw new PowerfulAPIException(422,"Profession Invalid");

          if(!Validate::Name($this->affiliation) && $this->affiliation!=null)
            throw new PowerfulAPIException(422,"Affiliation Invalid");

          if(!Validate::Name($this->ContactName1))
            throw new PowerfulAPIException(422,"ContactName1 Invalid");

          if(!Validate::Mobile($this->Contact1))
            throw new PowerfulAPIException(422,"Contact1 Invalid");

          if(!Validate::Name($this->ContactName2) && $this->ContactName2!=null)
            throw new PowerfulAPIException(422,"ContactName2 Invalid");

          if(!Validate::Mobile($this->Contact2) && $this->Contact2!=null)
            throw new PowerfulAPIException(422,"Contact2 Invalid");



          // Create query
          $query = 'INSERT INTO ' . $this->table . ' SET NIC = :NIC, first_name = :first_name, last_name = :last_name, dob = :dob, latitude = :latitude, longitude = :longitude, profession = :profession, email = :email, affiliation = :affiliation, Contact1 = :Contact1, Contact2 = :Contact2, ContactName1 = :ContactName1, ContactName2 = :ContactName2';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':NIC', $this->NIC);
          $stmt->bindParam(':first_name', $this->first_name);
          $stmt->bindParam(':last_name', $this->last_name);
          $stmt->bindParam(':dob', $this->dob);
          $stmt->bindParam(':latitude', $this->lat);
          $stmt->bindParam(':longitude', $this->long);
          $stmt->bindParam(':profession', $this->profession);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':affiliation', $this->affiliation);
          $stmt->bindParam(':Contact1', $this->Contact1);
          $stmt->bindParam(':Contact2', $this->Contact2);
          $stmt->bindParam(':ContactName1', $this->ContactName1);
          $stmt->bindParam(':ContactName2', $this->ContactName2);


          // Execute query
          if($stmt->execute()) {
             $login = new Login($this->conn);
             $login->user_id = $this->conn->lastInsertId();
             $login->username = $this->NIC;
             $login->user_type = 1;

             echo "PW in Citizen: ".$this->password."\n\n";
             $login->password = $this->password;


                if($login->create()){
                    return array('status' => 2, 'message' => 'User Account Created.', 'Login_id' => $login->id);
                  }
                else
                {
                  return array('status' => 1, 'message' => 'Citizen Created. User Acc Failed.');
                }
            }
            else
              return array('status' => 0, 'message' => 'Process has failed.');

    }



    // Upload Files
    public function upload() {

          if(!Validate::NIC($this->NIC))
            throw new PowerfulAPIException(422,"NIC Invalid");

          if(!$this->isNicNew())
            throw new PowerfulAPIException(409, "NIC already registered");

          if(!Validate::Name($this->first_name))
            throw new PowerfulAPIException(422,"First Name Invalid");

          if(!Validate::Name($this->last_name))
            throw new PowerfulAPIException(422,"Last Name Invalid");

          if(!Validate::Date($this->dob))
            throw new PowerfulAPIException(422,"Date of Birth Invalid");

          if(!Validate::Latitude($this->lat))
            throw new PowerfulAPIException(422,"Latitude Invalid");

          if(!Validate::Longitude($this->long))
            throw new PowerfulAPIException(422,"Logitude Invalid");

          if(!Validate::Email($this->email))
            throw new PowerfulAPIException(422,"Email Invalid");

          if(!Validate::Profession($this->profession))
            throw new PowerfulAPIException(422,"Profession Invalid");

          if(!Validate::Name($this->affiliation) && $this->affiliation!=null)
            throw new PowerfulAPIException(422,"Affiliation Invalid");

          if(!Validate::Name($this->ContactName1))
            throw new PowerfulAPIException(422,"ContactName1 Invalid");

          if(!Validate::Mobile($this->Contact1))
            throw new PowerfulAPIException(422,"Contact1 Invalid");

          if(!Validate::Name($this->ContactName2) && $this->ContactName2!=null)
            throw new PowerfulAPIException(422,"ContactName2 Invalid");

          if(!Validate::Mobile($this->Contact2) && $this->Contact2!=null)
            throw new PowerfulAPIException(422,"Contact2 Invalid");



          // Create query
          $query = 'INSERT INTO ' . $this->table . ' SET NIC = :NIC, first_name = :first_name, last_name = :last_name, dob = :dob, latitude = :latitude, longitude = :longitude, profession = :profession, email = :email, affiliation = :affiliation, Contact1 = :Contact1, Contact2 = :Contact2, ContactName1 = :ContactName1, ContactName2 = :ContactName2';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':NIC', $this->NIC);
          $stmt->bindParam(':first_name', $this->first_name);
          $stmt->bindParam(':last_name', $this->last_name);
          $stmt->bindParam(':dob', $this->dob);
          $stmt->bindParam(':latitude', $this->lat);
          $stmt->bindParam(':longitude', $this->long);
          $stmt->bindParam(':profession', $this->profession);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':affiliation', $this->affiliation);
          $stmt->bindParam(':Contact1', $this->Contact1);
          $stmt->bindParam(':Contact2', $this->Contact2);
          $stmt->bindParam(':ContactName1', $this->ContactName1);
          $stmt->bindParam(':ContactName2', $this->ContactName2);


          // Execute query
          if($stmt->execute()) {
             $login = new Login($this->conn);
             $login->user_id = $this->conn->lastInsertId();
             $login->username = $this->NIC;
             $login->user_type = 1;

             echo "PW in Citizen: ".$this->password."\n\n";
             $login->password = $this->password;


                if($login->create()){
                    return array('status' => 2, 'message' => 'User Account Created.', 'Login_id' => $login->id);
                  }
                else
                {
                  return array('status' => 1, 'message' => 'Citizen Created. User Acc Failed.');
                }
            }
            else
              return array('status' => 0, 'message' => 'Process has failed.');

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

    // Delete Post
    public function delete() {
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }

  }
