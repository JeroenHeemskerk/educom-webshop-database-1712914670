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
        $query = "INSERT INTO users (email, user, pswd) VALUES ('" . $email . "','" . $credentials["user"] . "','" . $credentials["pswd"] . "');"; 
  
        executeDataBaseQuery($query, $conn);
    } 
    finally {
        mysqli_close($conn);
    } 
} 

function getUserByEmail($email) {
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

function doesEmailExist($email) { 
    return !empty(getUserByEmail($email));
}

function authenticateUser($email, $pswd) {
    $conn = makeDataBaseConnection();

    try {
        $user = getUserByEmail($email);

        if (!empty($user) && $pswd == $user["pswd"]){
            return true;
        }
        return false;
    }
    finally {
        mysqli_close($conn);
    }
}



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