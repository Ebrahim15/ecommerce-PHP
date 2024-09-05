<?php

class Dbh {
    private $db_server = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "scandiweb-ecommerce";
    private $conn;

    protected function connect() {
        try {
            $this->conn = mysqli_connect($this->db_server, $this->db_user, $this->db_pass, $this->db_name);

            return $this->conn;
        } catch (mysqli_sql_exception) {
            echo "Couldn't connect";
        }
    }

    protected function closeConnection() {
        $this->conn->close();
    }
}