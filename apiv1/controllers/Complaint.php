<?php

class ComplaintCtrl
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
 * @url POST /complaint
 *
 */
  public function fileComplaint($data)
  {
      if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
          else
            throw new PowerfulAPIException(401,'');

        $database = new Database();
        $db = $database->connect();

        $complaint = new Complaint($db);

        if(!isset($_POST['complaint']) || !isset($_POST['citizen_id']))
            throw new PowerfulAPIException(400,'1');

        $complaint->citizen_id = Validate::input($_POST['citizen_id']);
        $complaint->complaint = Validate::input($_POST['complaint']);

        if(empty($complaint->complaint))
          throw new PowerfulAPIException(400,'2');


        return $complaint->fileComplaint();
      }


/**
 * @url PUT /complaint/$id/response
 * @noAuth
 */
  public function Response($id,$data)
  {
      if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
        else if($GLOBALS['user_details']['user_type'] == 2){}
          else
            throw new PowerfulAPIException(401,'');

        $database = new Database();
        $db = $database->connect();

        $complaint = new Complaint($db);

        if(!isset($data->response) || !isset($data->officer_id))
            throw new PowerfulAPIException(400,'');

        $complaint->id = Validate::input($id);
        $complaint->officer_id = Validate::input($data->officer_id);
        $complaint->response = Validate::input($data->response);

        if(empty($complaint->response))
          throw new PowerfulAPIException(400,'');


        return $complaint->response();
      }


/**
 * @url GET /complaint
 *
 */
  public function getComplaints()
  {
     if($GLOBALS['user_details']['user_type'] == 2){}
        else
          throw new PowerfulAPIException(401,'');

          $database = new Database();
          $db = $database->connect();

          $complaint = new Complaint($db);

          return $complaint->getComplaints();
  }

/**
 * @url GET /citizen/$id/complaint
 *
 */
  public function getComplaintsByCitizenID($id)
  {
      if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
        else if($GLOBALS['user_details']['user_type'] == 2){}
          else
            throw new PowerfulAPIException(401,'');

        $database = new Database();
        $db = $database->connect();

        $complaint = new Complaint($db);

        $complaint->citizen_id=Validate::input($id);

        return $complaint->getComplaintsById();
  }

/**
 * @url GET /complaint/$id
 * @noAuth
 */
  public function getComplaintsByID($id)
  {
      // if($GLOBALS['user_details']['user_type'] == 1){}
      //   else if($GLOBALS['user_details']['user_type'] == 2){}
      //     else
      //       throw new PowerfulAPIException(401,'');

            $database = new Database();
            $db = $database->connect();

            $complaint = new Complaint($db);

            $complaint->id=Validate::input($id);

            return $complaint->getComplaintsId();
  }


}
