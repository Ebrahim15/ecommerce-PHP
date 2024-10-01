<?php
// header("Content-Type: application/json");
// header("Access-Control-Allow-Origin: *");
// require_once "../Classes/Dbh.php";

// require_once "../Classes/Api.php";
require_once "../src/Model.php";
require_once "../src/Controller.php";
require_once "../Classes/Product.php";
require_once "../Controllers/Api.php";

$method = $_SERVER['REQUEST_METHOD'];

$products = new Api($method, $product = new Prodcut());
echo json_encode($products->getData());



// $tableName = "products";

// // $api = new Api($method, $tableName);
// // $api->getData();
// // echo json_encode($api->getData());

// $products = new Prodcut();
// echo json_encode($products->getAllProducts());


