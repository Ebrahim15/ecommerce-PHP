<?php
require_once "../src/Model.php";
require_once "../Classes/Product.php";
require_once "../Classes/Category.php";
require_once "../Classes/Price.php";
require_once "../Classes/Gallery.php";
require_once "../Classes/Currency.php";
require_once "../Classes/AttributeItem.php";
require_once "../Classes/AttributeSet.php";

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
            $category = new Category();
            $category->insertData($table);

        } else if($tableKey === "products"){
            // INSERT PRODUCTS DATA
            
            $products = [];
            $attribute_sets = [];
            $attributes = [];
            $currencies = [];
            $prices = [];
            $imageUrls = [];

            foreach ($table as $product) {
                // Remove any array datatype
                $modified_product = array_filter($product, function($field){
                    return (gettype($field) !== "array");
                });
                array_push($products, $modified_product);

                // Insert images urls
                foreach ($product['gallery'] as $url) {
                    array_push($imageUrls, ["productId" => $product['id'], "imageUrl" => $url]);
                };

                // Insert prices & currencies table data
                foreach ($product['prices'] as $price) {
                    $price['currencyId'] = $price['currency']['label'];
                    $price['productId'] = $product['id'];
                    $price['id'] = $price['productId'] . "_" . $price['currencyId'];

                    $currency = $price['currency'];
                    $currency['currencyId'] = $price['currency']['label'];
                    array_push($currencies, $currency);
                    array_push($prices, array_filter($price, function($field){
                        return (gettype($field) !== "array");
                    }));
                };

                // Insert attributeSets & attributes table data
                foreach ($product['attributes'] as $attribute_set) {
                    $modified_attribute_set = array_filter($attribute_set, function($field) {
                        return (gettype($field) !== "array");
                    });
                    $modified_attribute_set['productId'] = $product['id'];
                    $modified_attribute_set['attributeSetId'] = $attribute_set['id'] . "_" . $product['id'];
                    array_push($attribute_sets, $modified_attribute_set);

                    foreach($attribute_set['items'] as $attributeItem) {
                        $attributeItem['attributeSetId'] = $modified_attribute_set['attributeSetId'];
                        $attributeItem['attributeId'] = $attributeItem['attributeSetId'] . "_" . $attributeItem['value'];
                        array_push($attributes, $attributeItem);
                    }
                };
            }

            $product = new Prodcut();
            $product->insertData($products);

            $gallery = new Gallery();
            $gallery->insertData($imageUrls);

            $currency = new Currency();
            $currency->insertData($currencies);

            $price = new Price();
            $price->insertData($prices);

            $attribute_set = new AttributeSet();
            $attribute_set->insertData($attribute_sets);

            $attribute = new AttributeItem();
            $attribute->insertData($attributes);
        } 
    }
}
