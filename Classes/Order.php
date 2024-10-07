<?php
class Order extends Model {
    private $cart;

    public function __construct($cart)
    {
        $this->cart = $cart;
        echo "Order Added: " . "\n";
        echo json_encode($cart);
    }
}