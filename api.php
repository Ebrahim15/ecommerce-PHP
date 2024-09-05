<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require_once "Classes/Dbh.php";
require_once "Classes/Get.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $get = new Get;
        $get->handleGet();
        break;

    default:
        echo "Invalid request method";
        break;
}
