<?php

class Category extends Model{
    
    public function __construct()
    {
        parent::__construct("categories");
    }
    
    // private function insertCategory() {
    //     $query = "INSERT INTO categories VALUES
    //         ('" . $this->name . "', '" . $this->typename . "');";
    //     return mysqli_multi_query(parent::connect(), $query);
    // }

    // public function postCategory() {
    //     try{
    //         $this->insertCategory();
    //         parent::closeConnection();
    //     }
    //     catch(mysqli_sql_exception){
    //     }
    // }
}