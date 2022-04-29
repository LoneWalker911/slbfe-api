<?php
  class Login {
    // DB stuff
    private $conn;
    private $table = 'login';

    // Login Properties
    public $id;
    public $username;
    public $password;
    public $user_type;
    public $user_id;
    public $exptime;
    public $loginstring;
    public $reg_date;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    public static function authorize($authorization)
    {
          $database = new Database();
          $conn = $database->connect();


          $string=Validate::input($authorization);
          $query = "SELECT exptime,user_type,user_id FROM login WHERE loginstring=? AND status=1";

          $stmt = $conn->prepare($query);

          // Bind credentials
          $stmt->bindParam(1, $string);

          $stmt->execute();
          if ($stmt->rowCount() > 0)
          {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if((double)$row['exptime']>time())
            {
              return array('user_type'=>$row['user_type'], 'user_id'=>$row['user_id']);
            }
          else
            return false;
      }
    }

    // Get Posts
    public function login() {
      // Create query
      $query = 'SELECT user_type FROM ' . $this->table . ' WHERE username=? AND password=? AND status=1';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind credentials
      $stmt->bindParam(1, $this->username);
      $stmt->bindParam(2, $this->password);

      //echo $this->password;

      // Execute query
      $stmt->execute();

      if($stmt->rowCount() > 0){

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->user_type = $row['user_type'];

      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        do{
          $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
          $exptime=time()+(86400*14);
        $query = 'UPDATE ' . $this->table . ' SET loginstring=?, exptime=? WHERE username=?';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $randomString);
        $stmt->bindParam(2, $exptime);
        $stmt->bindParam(3, $this->username);

        $pass=$stmt->execute();
      }while(!$pass);

      return array('token' => $randomString, 'userType' => $this->user_type );
    }
    else
    {
      throw new PowerfulAPIException(401,'');
    }
      }


    // Get Single Citizen
    public function read_single() {
          // Create query
          $query = 'SELECT * FROM ' . $this->table . ' WHERE username=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->username);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->id = $row['id'];
          $this->username = $row['username'];
          $this->password = $row['password'];
          $this->user_type = $row['user_type'];
          $this->user_id = $row['user_id'];
          $this->exptime = $row['exptime'];
          $this->loginstring = $row['loginstring'];
          $this->reg_date = $row['reg_date'];
    }

    public function isUsernameNew() {
          // Create query
          $query = 'SELECT 1 FROM ' . $this->table . ' WHERE username=?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->username);

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

          if(!$this->isUsernameNew())
            throw new PowerfulAPIException(409, "Username already exists");
            echo "Login UN: ".$this->username."\n";
            echo "Login PW: ".$this->password."\n";
          $this->password = md5($this->username.$this->password);
          echo "Login MD5: ".$this->password."\n";


          // Create query
          $query = 'INSERT INTO ' . $this->table . ' SET username = :username, password = :password, user_type = :user_type, user_id = :user_id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':username', $this->username);
          $stmt->bindParam(':password', $this->password);
          $stmt->bindParam(':user_type', $this->user_type);
          $stmt->bindParam(':user_id', $this->user_id);

          // Execute query
          if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return 1;
          }

            return 0;




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
