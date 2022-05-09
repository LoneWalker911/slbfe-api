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
    public $reg_date;
    public $password;
    public $Q;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }


    // Get Posts
    public function read() {
      // Create query
      $query = 'SELECT `citizen`.*, `profession`.profession AS Prof FROM citizen , login, profession WHERE login.status=1 AND profession.id=citizen.profession AND citizen.id=login.user_id AND login.user_type=1 ORDER BY reg_date DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    public function updateLocation() {

          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"Citizen ID Invalid");


          // Create query
          $query = 'UPDATE citizen SET latitude=:latitude, longitude=:longitude WHERE id=:id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':latitude', $this->latitude);
          $stmt->bindParam(':longitude', $this->longitude);


          // Execute query
        if($stmt->execute()){
              return array('status' => 1, 'message' => 'Location updated successfully.');
          }
        else{
              throw new PowerfulAPIException(500, null);
          }
    }


    // Get Single Citizen
    public function read_single() {
          // Create query
          $query = 'SELECT citizen.*, `profession`.`Profession` AS Prop, citizenqualifications.qualifications AS CQC, citizenqualifications.birthcert AS birthCert, citizenqualifications.cv AS CV, citizenqualifications.passport AS Passport, citizenqualifications.validation AS Validation
          FROM citizen
          LEFT JOIN citizenqualifications ON citizen.id = citizenqualifications.id
          JOIN profession ON citizen.profession = profession.ID
          WHERE
          citizen.NIC=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->NIC);

          // Execute query
          $stmt->execute();

          if($stmt->rowCount()!=1)
            return false;

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->id = $row['id'];
          $this->NIC = $row['NIC'];
          $this->first_name = $row['first_name'];
          $this->last_name = $row['last_name'];
          $this->dob = $row['dob'];
          $this->lat = $row['latitude'];
          $this->long = $row['longitude'];
          $this->profession = $row['Prop'];
          $this->email = $row['email'];
          $this->affiliation = $row['affiliation'];
          $this->ContactName1 = $row['ContactName1'];
          $this->ContactName2 = $row['ContactName2'];
          $this->Contact1 = $row['Contact1'];
          $this->Contact2 = $row['Contact2'];
          $this->reg_date = $row['reg_date'];
          $this->Q = array('Qualifications'=>$row['CQC'], 'CV'=>$row['CV'], 'birthCert'=>$row['birthCert'], 'Passport'=>$row['Passport'], 'Validation'=>$row['Validation']);

          return true;
    }


    // Get Single Citizen
    public function readSingleById() {
          // Create query
          $query = 'SELECT citizen.*, `profession`.`Profession` AS Prop, citizenqualifications.qualifications AS CQC, citizenqualifications.birthcert AS birthCert, citizenqualifications.cv AS CV, citizenqualifications.passport AS Passport, citizenqualifications.validation AS Validation
          FROM citizen
          LEFT JOIN citizenqualifications ON citizen.id = citizenqualifications.id
          JOIN profession ON citizen.profession = profession.ID
          WHERE
          citizen.id=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          if($stmt->rowCount()!=1)
            return false;

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->id = $row['id'];
          $this->NIC = $row['NIC'];
          $this->first_name = $row['first_name'];
          $this->last_name = $row['last_name'];
          $this->dob = $row['dob'];
          $this->lat = $row['latitude'];
          $this->long = $row['longitude'];
          $this->profession = $row['Prop'];
          $this->email = $row['email'];
          $this->affiliation = $row['affiliation'];
          $this->ContactName1 = $row['ContactName1'];
          $this->ContactName2 = $row['ContactName2'];
          $this->Contact1 = $row['Contact1'];
          $this->Contact2 = $row['Contact2'];
          $this->reg_date = $row['reg_date'];
          $this->Q = array('Qualifications'=>$row['CQC'], 'CV'=>$row['CV'], 'birthCert'=>$row['birthCert'], 'Passport'=>$row['Passport'], 'Validation'=>$row['Validation']);

          return true;
    }



    public function isidValid() {
          // Create query
          $query = 'SELECT 1 FROM citizen WHERE id=?';

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



    // Delete Account
    public function delete() {
          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"ID Invalid");

          // Create query
          $query = 'UPDATE login SET status=0 WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return array('status' => 1, 'message' => 'Account Deactivated.');
          }


          return array('status' => 0, 'message' => 'Process has failed.');
    }


    // Get Citizen Contacts
    public function getContacts() {
          if(!$this->isidValid())
            throw new PowerfulAPIException(422,"ID Invalid");

          // Create query
          $query = 'SELECT Contact1, ContactName1 , ContactName2, Contact2, email FROM ' . $this->table . ' WHERE id = :id';


          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return array('email' => $row['email'], 'Family' => array(array('Name' => $row['ContactName1'], 'Contact' => $row['Contact1']), array('Name' => $row['ContactName2'], 'Contact' => $row['Contact2'])));
          }


          return array('status' => 0, 'message' => 'Process has failed.');
    }





  }
