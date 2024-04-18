<?php 


function makeDataBaseConnection() {
    $servername = getenv("MYSQL_FLORIAN_WEBSHOP_HOST"); 
    $dbname = getenv("MYSQL_FLORIAN_WEBSHOP_DATABASE"); 
    $username = getenv("MYSQL_FLORIAN_WEBSHOP_USER"); 
    $password = getenv("MYSQL_FLORIAN_WEBSHOP_PASSWORD");

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        throw new Exception("Er ging iets mis tijdens het verbinden met de database.\n");
    }

    return $conn;
}

function executeDataBaseQuery($query, $conn) {
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Er ging iets mis tijdens het uitvoeren van de query.\n");
    }

    return $result;
}

function addAccount($credentials) { 
    $conn = makeDataBaseConnection(); 
    try {
        $email = mysqli_real_escape_string($conn, $credentials["email"]);
        $query = "INSERT INtO users (email, user, pswd) VALUES ('" . $email . "','" . $credentials["user"] . "','" . $credentials["pswd"] . "');"; 
  
        executeDataBaseQuery($query, $conn);
    } 
    finally {
        mysqli_close($conn);
    } 
} 

function getUserDataByEmail($email) {
    $conn = makeDataBaseConnection();

    try {
        $email = mysqli_real_escape_string($conn, $email);
        $query = "SELECT * FROM users WHERE email='" . $email . "';";
    
        $result = executeDataBaseQuery($query, $conn);
        $row = mysqli_fetch_assoc($result);
    
        return $row;
    }
    finally {
        mysqli_close($conn);
    }
}

// getest met foute sql query
function doesEmailExist($email) { 
    return !empty(getUserDataByEmail($email));
}

function getUserByEmail($email) {
    $user = getUserDataByEmail($email);
     
    if (empty($user)) {
        return NULL; 
    } 
 
    return $user["user"]; 
}

// getest met foute sql query
define('RESULT_OK', 0);
define('RESULT_UNKNOWN_USER', -1);
define('RESULT_WRONG_PASSWORD', -2); 
define('RESULT_EMPTY_EMAIL', -3);
define('RESULT_EMPTY_PSWD', -4);
function authenticateUser($email, $pswd) { 
    if (empty($email)) {
        return ['result' => RESULT_EMPTY_EMAIL];
    }

    // deze wordt gecatched in validateLogin()
    $user = getUserDataByEmail($email);
    if (empty($user)) {
        return ['result' => RESULT_UNKNOWN_USER]; 
    } 
    
    if(empty($pswd)) {
        return ['result' => RESULT_EMPTY_PSWD];
    }
 
    if ($user["pswd"] != $pswd) { /* JH: Ik heb deze 'if' omgedraaid omdat het beter leest om eerst alle fouten af te handelen */
        return ['result' => RESULT_WRONG_PASSWORD]; 
    } 
    
    return ['result' => RESULT_OK, 'user' => $user]; 
} 

// getest met foute sql query
function getProductsByIDs($ids) {
    $conn = makeDataBaseConnection();

    try {
        $query = 'SELECT * FROM products WHERE id IN (' . implode(',', $ids) . ')';
        $result = executeDataBaseQuery($query, $conn);
    
        $products = array();
        while($row = mysqli_fetch_assoc($result)) {
            $products[$row["id"]] = array("id"=> $row["id"], "name"=>$row["name"], "description"=>$row["description"], "price"=>$row["price"], "fname"=>$row["fname"]);
        }
        return $products;
    }
    finally {
        mysqli_close($conn);
    }

}

// getest met foute sql query
function getProducts() {
    $conn = makeDataBaseConnection();

    try {
        $query = "SELECT * FROM products";

        $result = executeDataBaseQuery($query, $conn);
    
        $products = array();
        while($row = mysqli_fetch_assoc($result)) {
            $products[$row["id"]] = array("id"=> $row["id"], "name"=>$row["name"], "description"=>$row["description"], "price"=>$row["price"], "fname"=>$row["fname"]);
        }
    
        return ["products" => $products];
    }
    finally {
        mysqli_close($conn);
    }

}

function addPurchase() {
    $conn = makeDataBaseConnection();

    // eerst order toevoegen aan orders tabel

    

    // $products = getCartProducts();
    // $cartCounts = getCartCounts();

    // $query = "INSERT INTO ordersProducts (order_id, product_id, count) VALUES ('" . $credentials["email"] . "','" . $credentials["user"] . "','" . $credentials["pswd"] . "');";";
}