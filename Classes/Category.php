<?php

class Category extends Dbh{
    private $name;
    private $typename;
    

    public function __construct($name, $typename = "Category")
    {
        $this->name = $name;
        $this->typename = $typename;
    }
    
    private function insertCategory() {
        $query = "INSERT INTO categories VALUES
            ('" . $this->name . "', '" . $this->typename . "');";
        return mysqli_multi_query(parent::connect(), $query);
    }

    public function postCategory() {
        try{
            $this->insertCategory();
            parent::closeConnection();
        }
        catch(mysqli_sql_exception){
        }
    }
}