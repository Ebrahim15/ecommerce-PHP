<?php

class Prodcut extends Dbh{ 
    private $id;
    private $name;
    private $inStock;
    private $description;
    private $category;
    private $brand;
    private $typename;
    private $price;
    private $currency;
    private $gallery;
    private $attributes;

    public function __construct($id, $name, $inStock, $description, $category, $brand, $price, $currency, $gallery, $attributes, $typename = "Product" ) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->inStock = $inStock;
        $this->description = $description;
        $this->category = $category;
        $this->brand = $brand;
        $this->typename = $typename;
        $this->price = $price;
        $this->currency = $currency;
        $this->gallery = $gallery;
        $this->attributes = $attributes;
    }

    private function insertProduct() {
        $query = "INSERT INTO products VALUES 
                ('" . $this->id . "', 
                '" . $this->name . "', 
                '" . $this->inStock . "', 
                '" . $this->description . "',  
                '" . $this->category . "',  
                '" . $this->brand . "',  
                '" . $this->typename . "',  
                '" . $this->price . "',  
                '" . $this->currency . "',   
                '" . $this->gallery . "',  
                '" . $this->attributes . "');";
        return mysqli_multi_query(parent::connect(), $query);
    }

    public function postProduct() {
        try {
            $this->insertProduct();
            parent::closeConnection();
        }
        catch(mysqli_sql_exception) {
        }
    }
}