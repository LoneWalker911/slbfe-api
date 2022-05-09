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
   * @noAuth
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
        'dob' => $dob,
        'profession' => $Prof,
        'affiliation' => $affiliation,
        'reg_date' => $reg_date,
        'email' => $email
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
      array_push($professions,array('ID'=>$ID, 'Pro'=> $Profession)) ;

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
 *
 */
public function citizenUpdate($id,$data)
{
  // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
  //   else if($GLOBALS['user_details']['user_type'] == 2){}
  //     else
  //       throw new PowerfulAPIException(401,'');

                $database = new Database();
                $db = $database->connect();


                if(isset($data->qualifications) && !empty($data->qualifications))
                  {
                    $citizenQ = new CitizenQualification($db);

                    $citizenQ->id = Validate::input($id);

                    $citizenQ->qualifications = Validate::input($data->qualifications);

                    return $citizenQ->updateQ();
                  }

                  if(isset($data->latitude) && !empty($data->latitude) && isset($data->longitude) && !empty($data->longitude))
                    {
                      $citizen = new Citizen($db);

                      $citizen->id = Validate::input($id);

                      $citizen->longitude = Validate::input($data->longitude);
                      $citizen->latitude = Validate::input($data->latitude);

                      return $citizen->updateLocation();
                    }




}

/**
 * @url GET /citizen/$id/qualifications
 *
 */
public function citizenReadQ($id)
{
  // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
  //   else if($GLOBALS['user_details']['user_type'] == 2){}
  //     else
  //       throw new PowerfulAPIException(401,'');

                $database = new Database();
                $db = $database->connect();

                $citizenQ = new CitizenQualification($db);

                $citizenQ->id = Validate::input($id);

                return  $citizenQ->readQ();

}

  /**
   * @url POST /citizen/$id
   * @noAuth
   */
    public function citizenQUpload($id,$data)
    {
        // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
        //   else if($GLOBALS['user_details']['user_type'] == 2){}
        //     else
        //       throw new PowerfulAPIException(401,'');

              $database = new Database();
              $db = $database->connect();

              $citizenQ = new CitizenQualification($db);

              $citizenQ->id = Validate::input($id);


              return $citizenQ->uploadQ();
    }



/**
 * @url DELETE /citizen/$id
 * @noAuth
 */
    public function citizenDelete($id)
    {
        // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
        //   else if($GLOBALS['user_details']['user_type'] == 2){}
        //     else
        //       throw new PowerfulAPIException(401,'');

              $database = new Database();
              $db = $database->connect();

              $citizen = new Citizen($db);

              $citizen->id = Validate::input($id);

              return $citizen->delete();
    }

/**
 * @url GET /citizen/$id/contacts
 *
 */
  public function citizenContacts($id)
  {
      // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
      //   else if($GLOBALS['user_details']['user_type'] == 2){}
      //     else
      //       throw new PowerfulAPIException(401,'');

            $database = new Database();
            $db = $database->connect();

            $citizen = new Citizen($db);

            $citizen->id = Validate::input($id);

            return $citizen->getContacts();
    }


/**
 * @url GET /citizen/find
 *
 */
  public function citizenQSearch()
  {
      // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
      //   else if($GLOBALS['user_details']['user_type'] == 2){}
      //     else
      //       throw new PowerfulAPIException(401,'');

            $database = new Database();
            $db = $database->connect();

            $citizenQ = new CitizenQualification($db);

            $citizenQ->qualifications = Validate::input($_REQUEST['q']);

            $result = $citizenQ->search();;
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
                  'email' => $email
                );

                // Push to "data"
                array_push($citizens_arr, $citizen_item);

              }

              // Output
              return ($citizens_arr);

            } else {
              // No Posts
                throw new PowerfulAPIException(404, "No Citizens Found");
            }


    }



/**
 * @url GET /citizen/$nic
 */
  public function readSingle($nic)
  {
    $database = new Database();
    $db = $database->connect();

    $citizen = new Citizen($db);

    // Get ID
    $citizen->NIC = $nic;



    if(!$citizen->read_single())
      throw new PowerfulAPIException(404,'');


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
      'ContactName1' => $citizen->ContactName1,
      'Contact1' => $citizen->Contact1,
      'ContactName2' => $citizen->ContactName2,
      'Contact2' => $citizen->Contact2,
      'dob' => $citizen->dob,
      'reg_date' => $citizen->reg_date,
      'email' => $citizen->email,
      'Q'=>$citizen->Q
    );

    // Output
    return $citizen_arr;
  }


  /**
   * @url GET /citizen/id/$id
   */
    public function readSingleById($id)
    {
      $database = new Database();
      $db = $database->connect();

      $citizen = new Citizen($db);

      // Get ID
      $citizen->id = $id;


      if(!$citizen->readSingleById())
        throw new PowerfulAPIException(404,'');


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
        'ContactName1' => $citizen->ContactName1,
        'Contact1' => $citizen->Contact1,
        'ContactName2' => $citizen->ContactName2,
        'Contact2' => $citizen->Contact2,
        'dob' => $citizen->dob,
        'reg_date' => $citizen->reg_date,
        'email' => $citizen->email,
        'Q'=>$citizen->Q
      );

      // Output
      return $citizen_arr;
    }


  /**
   * @url PUT /citizen/$id/validate
   *
   */
  public function citizenQValidate($id,$data)
  {
    // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
    //   else if($GLOBALS['user_details']['user_type'] == 2){}
    //     else
    //       throw new PowerfulAPIException(401,'');

          $database = new Database();
          $db = $database->connect();

          $citizenQ = new CitizenQualification($db);

          $citizenQ->id = Validate::input($id);


          if(isset($data->validation) && ($data->validation == 2 || $data->validation == 1))
            {
              $citizenQ->validation = Validate::input($data->validation);

              return $citizenQ->validateQ();
            }
            else
              throw new PowerfulAPIException(400,'');


  }



  /**
   * @url POST /citizen
   * @noAuth
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


  if(isset($_POST['ContactName2']))
    $citizen->ContactName2 = empty($_POST['ContactName2']) ? NULL : Validate::input($_POST['ContactName2']);


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
