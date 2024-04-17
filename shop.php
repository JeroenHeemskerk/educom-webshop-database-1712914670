<?php

function getShopTitle() {
    return "Webshop";
}

function showShopContent($data) {
    include_once('communication.php');
    if (empty($data["productId"])) {
        showWebshopProducts($data);
    }
    else {
        showProductContent($data);
    }
}

function showWebshopProducts ($data) {
    $products = $data["products"];
    echo '<div class="product-list">';
    foreach($products as $id=>$product) {
        echo '<a class="product" href="index.php?page=shop&detail=' . $id . '"><div class="product">' . PHP_EOL;
        echo '<h3>' . $product["name"] . '</h3>' . PHP_EOL;
        echo '<img src="Images/' . $product["fname"] . '" alt="' . $product["description"] . '">';
        echo '<p>Prijs: &euro;' . $product["price"]  . ',-</p>';
        echo '</div></a>' . PHP_EOL;
    }
    echo '</div>';
}

function showProductContent($data) {
    include_once('communication.php');
    $id = $data["productId"];
    $product = $data["products"][$id];

    echo '<div class="detail">';
    echo '<h2>' . $product["name"] . '</h2>';
    echo '<img src="Images/' . $product["fname"] . '" alt="' . $product["description"] . '">';
    echo '<div class="detail-info">';
    echo '<h4>' . $product["name"] . '</h4>';
    echo '<p>' . $product["description"] . '<br><b>Prijs</b>: &euro;' . $product["price"] . '</p>';

    if (isUserLoggedIn()) {
        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">
        <input type="hidden" name="productId" value="' . $id . '">
        <input type="hidden" name="action" value="addToCart">
        <input type="hidden" name="page" value="cart">
        <input type="submit" value="Voeg toe aan CART">
    </form>';
    }
    echo '</div>';
    echo '</div>';
}
