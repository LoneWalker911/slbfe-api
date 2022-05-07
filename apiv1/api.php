<?php
//error_reporting(E_ALL & ~E_NOTICE);
// header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
// header('Access-Control-Allow-Credentials: true');
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

//Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

   }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

require 'PowerfulAPI.php';
require 'controllers/Citizen.php';
require 'controllers/Officer.php';
require 'controllers/Login.php';
include_once 'config/Database.php';
include_once '../models/Login.php';
include_once '../models/Validate.php';
include_once '../models/Citizen.php';
include_once '../models/CitizenQualification.php';
include_once '../models/Profession.php';
include_once '../models/Officer.php';


$server = new PowerfulAPI('production');

$server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass('LoginCtrl');
$server->addClass('CitizenCtrl');
$server->addClass('OfficerCtrl');
$server->handle();
?>
