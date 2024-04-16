<?php 

function showCartContent () {
    include_once('communication.php');
    $products = getCartProducts();

    $cartCounts = getCartCounts();
    foreach($products as $product) {
        echo '<div class="cart-list">';
        echo '<img src=Images/' . $product["fname"] . '>';
        echo '<p>Aantal: ' . $cartCounts[$product["id"]] . '<br>';
        echo 'Prijs: ' . $product["price"] . '<br>';
        echo '</p>';
        echo '</div>';
    }
}

