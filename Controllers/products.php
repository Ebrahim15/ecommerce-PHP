<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require_once "../Classes/Dbh.php";

require_once "../Classes/Api.php";
require_once "../Classes/Model.php";
require_once "../Classes/Product.php";

$method = $_SERVER['REQUEST_METHOD'];
$tableName = "products";

// $api = new Api($method, $tableName);
// $api->getData();
// echo json_encode($api->getData());

$products = new Prodcut();
echo json_encode($products->getAllProducts());
