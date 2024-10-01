<?php
class Api extends Controller {
    private $method;
    private $class;
    
    public function __construct($method, $class)
    {
        $this->method = $method;
        $this->class = $class;
    }

    private function handleRequest() {
        switch ($this->method) {
            case 'POST':
                return $this->class->getAllData();
                break;
            case 'GET':
                return $this->class->getAllData();
                break;
            default:
                echo "Invalid request method";
                break;
        }
    }

    public function getData() {
        return $this->handleRequest();
    }
}