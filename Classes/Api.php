<?php
class Api extends Dbh{
    private $method;
    private $tableName;
    private $data;

    public function __construct($method, $tableName)
    { 
        $this->method = $method;
        $this->tableName = $tableName;
        $this->handleRequest();
    }

    private function handleGetAll($tableName)
    {
        try {
            $query = "SELECT * FROM $tableName";
            $result = parent::connect()->query($query);
            $this->data = $result->fetch_all(MYSQLI_ASSOC);
            parent::closeConnection();           
        }
        catch(mysqli_sql_exception){
            echo "Error geting data.";
        }
    }

    private function handleRequest() {
        switch ($this->method) {
            case 'GET':
                $this->handleGetAll($this->tableName);
                break;
            case 'POST':
                $this->handleGetAll($this->tableName);
                break;
            default:
                echo "Invalid request method";
                break;
        }
    }

    public function getData() {
        return $this->data;
    }
}