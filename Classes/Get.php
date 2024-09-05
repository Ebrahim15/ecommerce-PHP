<?php

class Get extends Dbh
{
    public function handleGet()
    {
        try {
            $query = "SELECT * FROM products";
            $result = parent::connect()->query($query);
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            parent::closeConnection();
        }
        catch(mysqli_sql_exception){
            echo "Error geting data.";
        }
    }
}
