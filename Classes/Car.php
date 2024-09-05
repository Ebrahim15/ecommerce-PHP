<?php

class Car {
    // Properties / Fields
    private $brand;
    private $color;
    private $vehicleType = "car";

    // Constructor __construct() is a predefined function in php to work as a constructor
    public function __construct($brand, $color = "none") {
        $this->brand = $brand;
        $this->color = $color;
    }

    // Getter & Setter Methods
    public function getBrand() {
        return $this->brand;
    }

    public function setBrand($brand) {
        return $this->brand = $brand;
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $allowed_colors = [
            "red",
            "black",
            "blue",
            "green",
        ];
        if (in_array($color, $allowed_colors)) {
            return $this->color = $color;
        }
    }

    // Method
    public function getCarInfo() {
        return "Brand: " . $this->brand . ", Color: " . $this->color;
    }
}