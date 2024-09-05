<?php
require_once "Classes/Dbh.php";
require_once "Classes/Product.php";
require_once "Classes/Category.php";

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "scandiweb-ecommerce";
$conn = "";

$filename = "data.json";

$data = file_get_contents($filename);

$array = json_decode($data, true);

foreach ($array as $tablesKey => $tables) {
    foreach ($tables as $tableKey => $table) {
        
        if ($tableKey === "categories") {
            // INSERT CATEGORIES DATA
            foreach ($table as $table_data) {
                $category = new Category($table_data['name']);
                $category->postCategory();
            };
        } else if($tableKey === "products"){
            // INSERT PRODUCTS DATA
            foreach ($table as $table_data) {
                $gallery = json_encode($table_data['gallery']);
                $attributes = json_encode($table_data['attributes']);
                $product = new Prodcut(
                    $table_data['id'], 
                    $table_data["name"], 
                    $table_data["inStock"], 
                    $table_data["description"],  
                    $table_data['category'],  
                    $table_data['brand'],  
                    $table_data['prices'][0]['amount'],  
                    $table_data['prices'][0]['currency']['label'],
                    $gallery,  
                    $attributes
                );

                $product->postProduct();
            }
        }
    }
}
