<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Citizen.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
  case 'PUT':
    do_something_with_put($request);
    break;
  case 'POST':
    citizenRegister($request);
    break;
  case 'GET':
    read($request);  
    break;
  default:
    handle_error($request);
    break;

  }

 ?>
