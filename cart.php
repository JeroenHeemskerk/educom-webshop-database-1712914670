<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Is deze include nodig
    include_once('index.php');
    $id = getPostVar("productId");
    if (isset($id)) {
        include_once('communication.php');
        addToCart($id);
    }
}

function showCartContent () {
    include_once('communication.php');
    var_dump(getCart());
}

