<?php

/*
 * Hold the cart item data
 */
class cart_item {

    public $id;

    public $product_id;

    public $image;

    public $name;

    public $price;

    public function __construct() {

    }
}

/*
 * Hold the cart items that user would like to buy
 */
class cart {

    public $cart_items = array();

    public function __construct() {

    }

    public function add_to_cart($id, $product_id, $image, $name, $price) {
        $cart_item = new cart_item();
        $cart_item->id = $id;
        $cart_item->product_id = $product_id;
        $cart_item->image = $image;
        $cart_item->name = $name;
        $cart_item->price = $price;
        array_push($this->cart_items, $cart_item);
    }
}

/*
 * Hold the summary for the paypal transcation 
 */
class cart_summary {
    
    public $is_error = false;
    
    public $is_complete = false;

    public $error_message;
    
    public $complete_message;
    
    public function __construct() {
        
    }
}

?>