<?php 


function makeDataBaseConnection() {
    $servername = getenv("MYSQL_FLORIAN_WEBSHOP_HOST"); 
    $dbname = getenv("MYSQL_FLORIAN_WEBSHOP_DATABASE"); 
    $username = getenv("MYSQL_FLORIAN_WEBSHOP_USER"); 
    $password = getenv("MYSQL_FLORIAN_WEBSHOP_PASSWORD");

    try {
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    }
    catch (Exception $e) {
        echo 'MySQL connection error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }
    return $conn;
}

function executeDataBaseQuery($query, $conn) {
    try {
        $result = mysqli_query($conn, $query);
    }
    catch (Exception $e) {
        echo 'MySQL query error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }

    return $result;
}

function addAccount($credentials) {
    $conn = makeDataBaseConnection();

    $query = "INSERT INTO users (email, user, pswd) VALUES ('" . $credentials["email"] . "','" . $credentials["user"] . "','" . $credentials["pswd"] . "');";

    executeDataBaseQuery($query, $conn);
}

function doesEmailExist($email) {
    $conn = makeDataBaseConnection();

    $query = "SELECT email FROM users WHERE email='" . $email . "';";

    $result = executeDataBaseQuery($query, $conn);

    $row = mysqli_fetch_assoc($result);

    if ($row == NULL) {
        return false;
    }

    return true;
}

function authenticateUser($email, $pswd) {
    $conn = makeDataBaseConnection();

    $query = 'SELECT email, pswd FROM users WHERE email="' . $email . '";';

    $result = executeDataBaseQuery($query, $conn);
    $row = mysqli_fetch_assoc($result);

    if ($row == NULL) {
        return false;
    }

    if ($row["pswd"] == $pswd) {
        return true;
    }

    return false;
}

function getUserByEmail($email) {
    $conn = makeDataBaseConnection();

    $query = "SELECT user FROM users WHERE email='" . $email . "';";

    $result = executeDataBaseQuery($query, $conn);
    $row = mysqli_fetch_assoc($result);

    if ($row == NULL) {
        // wat moet ik dan hier returnen? een default instellen?
        return false;
    }

    return $row["user"];
}

function getSessionVar($key, $default="") {
    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }
    
    return $default;
}

function isUserLoggedIn() {
    return getSessionVar('login', false);
}

function doLoginUser($values) {
    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    $_SESSION["email"] = $values["email"];
    $_SESSION["login"] = true;
    $_SESSION["cart"] = array();
}

function doLogoutUser() {
    session_unset();
    session_destroy();
}

function getLoggedInUser() {
    return getUserByEmail(getSessionVar('email'));
}

function addToCart($id) {
    if (empty($_SESSION["cart"][$id])) {
        $_SESSION["cart"][$id] = 1;
    }
    else {
        $_SESSION["cart"][$id]++;
    }
}

function getProductsByIDs($ids) {
    $conn = makeDataBaseConnection();

    $query = 'SELECT * FROM products WHERE id IN (' . implode(',', $ids) . ')';
    $result = executeDataBaseQuery($query, $conn);

    $products = array();
    while($row = mysqli_fetch_assoc($result)) {
        $products[$row["id"]] = array("id"=> $row["id"], "name"=>$row["name"], "description"=>$row["description"], "price"=>$row["price"], "fname"=>$row["fname"]);
    }

    return $products;
}

function getProducts() {
    $conn = makeDataBaseConnection();

    $query = "SELECT * FROM products";

    $result = executeDataBaseQuery($query, $conn);

    $products = array();
    while($row = mysqli_fetch_assoc($result)) {
        $products[$row["id"]] = array("id"=> $row["id"], "name"=>$row["name"], "description"=>$row["description"], "price"=>$row["price"], "fname"=>$row["fname"]);
    }

    return ["products" => $products];
}

function getCart() {
    return getSessionVar("cart", array());
}

function emptyCart() {
    $_SESSION["cart"] = array();
}

function addPurchase() {
    $conn = makeDataBaseConnection();

    // $products = getCartProducts();
    // $cartCounts = getCartCounts();

    // $query = "INSERT INTO ordersProducts (order_id, product_id, count) VALUES ('" . $credentials["email"] . "','" . $credentials["user"] . "','" . $credentials["pswd"] . "');";";
}