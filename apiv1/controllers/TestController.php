<?php

class TestController
{

  function authorize()
  {
      if ($_SERVER["Authorization"]=="111222")
        return true;
      else
        return false;
  }

    /**
     * @url GET /
     * @noAuth
     */
    public function Home()
    {
        handleError(523, "fdf");
        return "SLBFE API HOME";
    }

    /**
     * @url POST /prashan/$no
     */
    public function prashan($no)
    {
        $para = $_REQUEST['email'];
        return "Prashan = ".$no.". Email =".$para;
    }

    /**
     * @url GET /prashan/$no
     */
    public function prashan123($no)
    {
      $cars = array (4=>15);
        return $cars;
    }

    /**
     * @url GET /banana/
     */
    public function banana()
    {
        return "banana";

    }

    /**
     * @url POST /banana/bh
     */
    public function bh()
    {

          return "KKL";

    }


    /**
     * @url GET /users/$id
     * @url GET /users/current
     */
    public function getUser($id = null)
    {
        return array("id" => $id, "name" => null);
    }
}

?>
