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
 * @noAuth
 */
  public function fileComplaint($data)
  {
      // if($GLOBALS['user_details']['user_id'] == $id && $GLOBALS['user_details']['user_type'] == 1){}
      //   else if($GLOBALS['user_details']['user_type'] == 2){}
      //     else
      //       throw new PowerfulAPIException(401,'');

            $database = new Database();
            $db = $database->connect();

            $complaint = new Complaint($db);


            if(!isset($data->complaint) || !isset($data->citizen_id))
                throw new PowerfulAPIException(400,'');

            $complaint->id = Validate::input($id);
            $complaint->complaint = $data->complaint;


            return $complaint->fileComplaint();
  }
}
