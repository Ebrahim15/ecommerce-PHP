<?php

class Gallery extends Model{
    private $product_id;
    private $image_url;

    // public function __construct($product_id, $image_url) {
    //     $this->product_id = $product_id;
    //     $this->image_url = $image_url;
    // }

    public function __construct()
    {
        parent::__construct("gallery");
    }
}