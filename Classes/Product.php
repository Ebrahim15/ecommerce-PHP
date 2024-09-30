<?php

class Prodcut extends Model
{
    private $id;
    private $name;
    private $inStock;
    private $description;
    private $category;
    private $brand;
    private $typename;


    // public function __construct($id, $name, $inStock, $description, $category, $brand, $typename = "Product")
    // {
    //     $this->id = $id;
    //     $this->name = $name;
    //     $this->inStock = $inStock;
    //     $this->description = $description;
    //     $this->category = $category;
    //     $this->brand = $brand;
    //     $this->typename = $typename;
    // }
    
    public function __construct()
    {
        parent::__construct("products");
    }

    public function getAllProducts(){
        return parent::getAllData();
    }

    // private function insertProduct()
    // {
    //     $query = "INSERT INTO products VALUES 
    //             ('" . $this->id . "', 
    //             '" . $this->name . "', 
    //             '" . $this->inStock . "', 
    //             '" . $this->description . "',  
    //             '" . $this->category . "',  
    //             '" . $this->brand . "',  
    //             '" . $this->typename . "');";
    //     return mysqli_multi_query(parent::connect(), $query);
    // }

    // public function postProduct()
    // {
    //     try {
    //         $this->insertProduct();
    //         parent::closeConnection();
    //     } catch (mysqli_sql_exception) {

    //     }
    // }
}
