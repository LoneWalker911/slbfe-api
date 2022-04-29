<?php
//error_reporting(E_ALL & ~E_NOTICE);

require 'PowerfulAPI.php';
require 'controllers/TestController.php';
require 'controllers/Citizen.php';
require 'controllers/Login.php';
include_once 'config/Database.php';
include_once '../models/Login.php';
include_once '../models/Validate.php';
include_once '../models/Citizen.php';
include_once '../models/Profession.php';


$server = new PowerfulAPI('production');

$server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass('LoginCtrl');
$server->addClass('CitizenCtrl');
$server->handle();
?>
