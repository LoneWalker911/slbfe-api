<?php

class HomeCtrl
{

  function authorize()
  {
        return true;
  }

    /**
     * @url GET /
     * @noAuth
     */
    public function Home()
    {

        include 'index.php';
    }


}

?>
