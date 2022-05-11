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





}
 ?>
