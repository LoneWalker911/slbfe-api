<?php

class LoginCtrl
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
   * @url GET /login/check
   */
public function readAll()
{

  return $GLOBALS['user_details'];
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
   * @url POST /login
   * @noAuth
   */
   public function Login()
{
    $database = new Database();
    $db = $database->connect();

    $login = new Login($db);

    // Get Data
    if(isset($_POST['username']))
      $login->username = Validate::input($_POST['username']);
    else
      throw new PowerfulAPIException(400,'');

    if(isset($_POST['password']))
      $login->password = Validate::input($_POST['password']);
    else
      throw new PowerfulAPIException(400,'');

    $login->password = md5($login->username.$login->password);


    // Get post
    return $login->login();
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
    $citizen->Conatct1 = Validate::input($_POST['Contact1']);
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
    $citizen->password = Validate::Password($_POST['password']);
  else
    throw new PowerfulAPIException(400,'');



  // Check NIC
  return $citizen->create();

}




}
 ?>
