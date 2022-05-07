<?php

class OfficerCtrl
{


  function authorize()
  {

    if (isset($_SERVER["Authorization"]) && strlen($_SERVER["Authorization"])==32)
      {
        if($data = Login::authorize($_SERVER["Authorization"]))
        {
          if($data['user_type']!=2) return false;
          global $user_details;
          $user_details=array('user_id'=>$data['user_id'], 'user_type'=>$data['user_type']);
          return true;
        }
      }
      return false;
  }




  /**
   * @url POST /officer
   * @noAuth
   */
 public function officerCreate()
{
  $database = new Database();
  $db = $database->connect();

  $officer = new Officer($db);

  // Get Data
  if(isset($_POST['nic']))
    $officer->NIC = Validate::input($_POST['nic']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['first_name']))
    $officer->first_name = Validate::input($_POST['first_name']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['last_name']))
    $officer->last_name = Validate::input($_POST['last_name']);
  else
    throw new PowerfulAPIException(400,'');


  if(isset($_POST['dob']))
    $officer->dob = Validate::input($_POST['dob']);
  else
    throw new PowerfulAPIException(400,'');


  if(isset($_POST['email']))
    $officer->email = Validate::input($_POST['email']);
  else
    throw new PowerfulAPIException(400,'');

  if(isset($_POST['password']))
    $officer->password = Validate::input($_POST['password']);
  else
    throw new PowerfulAPIException(400,'');



  // Create Citizen and login user acc
  return $officer->create();

}







}
 ?>
