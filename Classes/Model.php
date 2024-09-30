<?php

class Model
{
    // Connect to the database
    private $db_server = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "scandiweb-ecommerce";
    private $conn;

    private $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    protected function connect()
    {
        try {
            $this->conn = mysqli_connect($this->db_server, $this->db_user, $this->db_pass, $this->db_name);

            return $this->conn;
        } catch (mysqli_sql_exception) {
            echo "Couldn't connect";
        }
    }

    // Close the connection to the database
    protected function closeConnection()
    {
        $this->conn->close();
    }

    // Insert the data from the json file into the database(MySql)
    private function handleInsertData($fields, $values)
    {
        try {
            $query = "INSERT INTO " . $this->tableName . " (" . implode(",", $fields) . ") VALUES
                ('" . implode("','", $values) . "')";
    
            return mysqli_multi_query($this->connect(), $query);
        }
        catch(mysqli_sql_exception){
            if($this->conn->errno === 1062) {
                
            }
            else {
                echo "Error: " . $this->conn->error . "<br>";
            }
        }
    }

    public function insertData($data)
    {
        if($this->tableName === ""){
            throw new Exception("Attribute _table is empty string!");
        }

        foreach ($data as $object) {
            $fields = array_keys($object);
            $values = array_values($object);

            $this->handleInsertData($fields, $values);
        }
    }

    // Get the data from the database
}
