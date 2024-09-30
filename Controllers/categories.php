<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require_once "../Classes/Dbh.php";

require_once "../Classes/Api.php";

$method = $_SERVER['REQUEST_METHOD'];
$tableName = "categories";

$api = new Api($method, $tableName);
// $api->getData();
echo json_encode($api->getData());

