<?php

include_once '../apiv1/config/Database.php';
include_once 'Profession.php';

class Validate
{


  public static function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public static function NIC($data) {
    $pattern = "/^([0-9]{9}[x|X|v|V]|[0-9]{12})$/m";
    return preg_match($pattern, $data);
  }

  public static function Name($data) {
    $pattern = "/^[\p{L} ,.'-]+$/u";
    return preg_match($pattern, $data);
  }

  public static function Date($data) {
    $pattern = "/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/";
    return preg_match($pattern, $data);
  }

  public static function Latitude($data) {
    $pattern = "/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/";
    return preg_match($pattern, $data);
  }

  public static function Longitude($data) {
    $pattern = "/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/";
    return preg_match($pattern, $data);
  }

  public static function Email($data) {
    $pattern = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
    return preg_match($pattern, $data);
  }

  public static function Password($data) {
    $pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
    return preg_match($pattern, $data);
  }

  public static function Mobile($data) {
    $pattern = "/^(?:7|0|(?:\+94))[0-9]{9,10}$/";
    return preg_match($pattern, $data);
  }


  public static function Profession($data) {
    $pattern = "/^[0-9]*$/";
    if(!preg_match($pattern, $data))
    return false;

    $database = new Database();
    $db = $database->connect();

    $Profession = new Profession($db);
    $Profession->id=$data;

    return $Profession->isProfValid();
  }

}





 ?>
