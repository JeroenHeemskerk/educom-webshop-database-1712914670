<?php 

function showCartContent () {
    echo '<h2>Winkelmandje</h2>';

    include_once('communication.php');
    $products = getCartProducts();

    $cartCounts = getCartCounts();
    $total = 0.0;
    foreach($products as $product) {
        $total += $cartCounts[$product["id"]] * $product["price"];
        echo '<a class="cart-list" href="index.php?page=shop&product=' . $product["id"] . '">';
        echo '<div>';
        echo '<img src=Images/' . $product["fname"] . '>';
        echo '<p>Aantal: ' . $cartCounts[$product["id"]] . '<br>';
        echo 'Prijs: &euro;' . $product["price"] . ',-<br>';
        echo '</p>';
        echo '</div></a>';
    }
    echo '<p id="total-cart">Totaal: &euro;' . $total . ',-</p>';
}

