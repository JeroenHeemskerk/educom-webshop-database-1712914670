<?php 

function getCartTitle() {
    return "Cart";
}

function getCartProducts() {
    include_once('session_manager.php');
    $cart = getCart();
    $cartIds = array_keys($cart);
    $total = 0.0;
    if (empty($cartIds)) {
        return array("products"=>array(), "total"=>$total);
    }

    include_once('communication.php');
    $products = getProductsByIDs($cartIds);
    $cartProducts = array();
    foreach($cart as $productId => $count) {
        // ik filter twee keer op id, maar simultaan loopen is te veel gedoe
        $product = $products[$productId];
        $cartProducts[$productId] = array("id"=>$productId, "count"=>$count, "name"=>$product['name'], "description"=>$product['description'], "fname"=>$product['fname'], "price"=>$product['price']);
        
        $subtotal = $product["price"]*$count;
        $cartProducts[$productId]["subtotal"] = $subtotal;
        $total += $subtotal;
    }

    return ["products" => $cartProducts, "total"=>$total];
}

function handleCartAction($action, $id) {
    // returns the page to redirect to
    include_once('session_manager.php');
    include_once('communication.php');
    $errors = array();
    switch ($action) {
        case "addToCart":
            addToCart($id);
            try {
                $products = getProducts()["products"];
            }
            catch (Exception $e) {
                $errors["general"] = "Er is een technische storing, de winkelmand werkt momenteel niet. Probeer het later nogmaals.";
                logError('Cart action failed for ' . getLoggedInEmail() . ', SQLError: ' . $e -> getMessage());
                $products = array();
            }
            return ["products"=>$products, "page"=>"shop", "productId"=>$id, "errors"=>$errors];

        case "purchase":
            try {
                addOrder();
                emptyCart();
                return ["products"=>array(), "total"=>0.0, "page"=>"cart"];
            }
            catch (Exception $e) {
                $errors = array();
                $errors["general"] = "Er is een technische storing, u kunt momenteel niet afrekenen. Probeer het later nogmaals.";
                logError('Purchase failed for ' . getLoggedInEmail() . ', SQLError: ' . $e -> getMessage());
                return ["products"=>array(), "total"=>0.0, "page"=>"cart", "errors"=>$errors];
            }
    }
}

function showActionButton($action, $page, $buttonId, $buttonText, $productId=NULL) {
    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">
    <input type="hidden" name="productId" value="' . $productId . '">
    <input type="hidden" name="action" value="' . $action . '">';
    echo '<input type="hidden" name="page" value="' . $page . '">';
    echo '<input id="' . $buttonId . '" type="submit" value="' . $buttonText . '">
</form>';
}


function showCartContent($data) {
    echo '<h2>Winkelmandje</h2>';

    include_once('communication.php');

    $products = $data["products"];
    foreach($products as $product) {
        echo '<a class="cart-list" href="index.php?page=shop&detail=' . $product["id"] . '">';
        echo '<div>';
        echo '<img src=Images/' . $product["fname"] . '>';
        echo '<p>Aantal: ' . $product["count"] . '<br>';
        echo 'Prijs: &euro;' . $product["subtotal"] . ',-<br>';
        echo '</p>';
        echo '</div></a>';
    }
    echo '<p id="total-cart">Totaal: &euro;' . $data["total"] . ',-</p>';
    showActionButton("purchase", "cart", "purchaseButton", "Afrekenen");
}


