<?php

class CitizenCtrl
{


  function authorize()
  {

    if (isset($_SERVER["Authorization"]) && strlen($_SERVER["Authorization"])==32)
      {
        if($data = Login::authorize($_SERVER["Authorization"]))
        {
          global $user_details;
          $user_details=array('user_id'=>$data['user_id'], 'user_type'=>$data['user_type']);
          return true;
        }
      }
      return false;
  }


  /**
   * @url GET /citizen
   */
public function readAll()
{
  $database = new Database();
  $db = $database->connect();

  $citizen = new Citizen($db);
  // Citizen Information query
  $result = $citizen->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any citizen
  if($num > 0) {
    // Citizen array
    $citizens_arr = array();
     //$citizens_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $citizen_item = array(
        'id' => $id,
        'NIC' => $NIC,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'profession' => $profession,
        'affiliation' => $affiliation,
        'reg_date' => $reg_date,
        'email' => $email,
        'status' => $status
      );

      // Push to "data"
      array_push($citizens_arr, $citizen_item);

    }

    // Output
    return ($citizens_arr);

  } else {
    // No Posts
    return(
      array('message' => 'No Citizens Found')
    );
  }
}


  /**
   * @url GET /citizen/professions
   * @noAuth
   */
public function readProfessions()
{
  $database = new Database();
  $db = $database->connect();

  $ProfessionObj = new Profession($db);
  // Citizen Information query
  $result = $ProfessionObj->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any citizen
  if($num > 0) {
    // Citizen array
    $professions = array();
    //$professions['LL'] = "ppop";
     //$citizens_arr['data'] = array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $professions[$ID] = $Profession;

    }

    // Output
    return $professions;

  } else {
    // No Posts
      throw new PowerfulAPIException(404, null);
      return 0;

  }
}


/**
 * @url PUT /citizen/$id
* @noAuth
 */
public function citizen1($data)
{
  if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
    else if($GLOBALS['user_details']['user_type'] == 2){}
      else
        throw new PowerfulAPIException(401,'');
  echo "arg1 : ".$data->arg1;

}

/**
 * @url POST /citizen/$id
* @noAuth
 */
public function citizen1($data)
{
  echo "arg1 : ".$data->arg1;

}

/**
 * @url GET /citizen/$id
 */
public function readSingle($id)
{
$database = new Database();
$db = $database->connect();

$citizen = new Citizen($db);

// Get ID
$citizen->id = $id;

// Get post
$citizen->read_single();

// Create array
$citizen_arr = array(
  'id' => $citizen->id,
  'NIC' => $citizen->NIC,
  'first_name' => $citizen->first_name,
  'last_name' => $citizen->last_name,
  'latitude' => $citizen->lat,
  'longitude' => $citizen->long,
  'profession' => $citizen->profession,
  'affiliation' => $citizen->affiliation,
  'reg_date' => $citizen->reg_date,
  'email' => $citizen->email,
  'status' => $citizen->status
);

// Output
return $citizen_arr;
}



  /**
   * @url POST /citizen
   */
 public function citizenCreate()
{
  $database = new Database();
  $db = $database->connect();

  $citizen = new Citizen($db);

  // Get Data
  if(isset($_POST['nic']))
    $citizen->NIC = Validate::input($_POST['nic']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['first_name']))
    $citizen->first_name = Validate::input($_POST['first_name']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['last_name']))
    $citizen->last_name = Validate::input($_POST['last_name']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['latitude']))
    $citizen->lat = Validate::input($_POST['latitude']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['longitude']))
    $citizen->long = Validate::input($_POST['longitude']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['dob']))
    $citizen->dob = Validate::input($_POST['dob']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['profession']))
    $citizen->profession = Validate::input($_POST['profession']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['affiliation']))
    $citizen->affiliation = empty($_POST['affiliation']) ? NULL : Validate::input($_POST['affiliation']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['Contact1']))
    $citizen->Contact1 = Validate::input($_POST['Contact1']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['ContactName1']))
    $citizen->ContactName1 = Validate::input($_POST['ContactName1']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['Contact2']))
    $citizen->Contact2 = empty($_POST['Contact2']) ? NULL : Validate::input($_POST['Contact2']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['ContactName2']))
    $citizen->ContactName2 = empty($_POST['ContactName2']) ? NULL : Validate::input($_POST['ContactName2']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['email']))
    $citizen->email = Validate::input($_POST['email']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['password']))
    $citizen->password = Validate::input($_POST['password']);
  else
    throw new PowerfulAPIException(400,'');



  // Create Citizen and login user acc
  return $citizen->create();

}


  /**
   * @url PUT /citizen1/$id
   */
 public function citizenUpload($id)
{



  $database = new Database();
  $db = $database->connect();

  $citizen = new Citizen($db);

  // Get Data
  if(isset($_POST['qulification']) && !empty($_POST['qulification']))
    $citizen->NIC = Validate::input($_POST['qulification']);

  if(isset($_PUT['cv']) && !empty($_PUT['cv']))
    $citizen->first_name = Validate::input($_POST['first_name']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['last_name']))
    $citizen->last_name = Validate::input($_POST['last_name']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['latitude']))
    $citizen->lat = Validate::input($_POST['latitude']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['longitude']))
    $citizen->long = Validate::input($_POST['longitude']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['dob']))
    $citizen->dob = Validate::input($_POST['dob']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['profession']))
    $citizen->profession = Validate::input($_POST['profession']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['affiliation']))
    $citizen->affiliation = empty($_POST['affiliation']) ? NULL : Validate::input($_POST['affiliation']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['Contact1']))
    $citizen->Contact1 = Validate::input($_POST['Contact1']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['ContactName1']))
    $citizen->ContactName1 = Validate::input($_POST['ContactName1']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['Contact2']))
    $citizen->Contact2 = empty($_POST['Contact2']) ? NULL : Validate::input($_POST['Contact2']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['ContactName2']))
    $citizen->ContactName2 = empty($_POST['ContactName2']) ? NULL : Validate::input($_POST['ContactName2']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['email']))
    $citizen->email = Validate::input($_POST['email']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['password']))
    $citizen->password = Validate::input($_POST['password']);
  else
    throw new PowerfulAPIException(400,'');



  // Create Citizen and login user acc
  return $citizen->create();

}




}
 ?>
