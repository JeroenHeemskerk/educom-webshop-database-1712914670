<?php 

define('RESULT_OK', 0);
define('RESULT_UNKNOWN_USER', -1);
define('RESULT_WRONG_PSWD', -2); 
define('RESULT_EMPTY_EMAIL', -3);
define('RESULT_EMPTY_PSWD', -4);
define('RESULT_EMPTY_NEWPSWD', -5);
define('RESULT_WRONG_NEWPSWD', -6);
define('RESULT_NO_PSWDCHANGE', -7);

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
        $pswd = password_hash($credentials["pswd"], PASSWORD_DEFAULT, [14]);
        $query = "INSERT INTO users (email, name, pswd) VALUES ('" . $email . "','" . $credentials["user"] . "','" . $pswd . "');"; 
  
        executeDataBaseQuery($query, $conn);
    } 
    finally {
        mysqli_close($conn);
    } 
} 

function authenticateNewPswd($values) {
    if (empty($values["pswdOld"])) {
        return ["result" => RESULT_EMPTY_PSWD];
    }

    if (empty($values["pswdNew"]) || empty($values["pswdNew2"])) {
        return ["result" => RESULT_EMPTY_NEWPSWD];
    }

    if ($values["pswdNew"] != $values["pswdNew2"]) {
        return ["result" => RESULT_WRONG_NEWPSWD];
    }

    // caught in validateChangePswd()
    $user = getUserDataByEmail($values["email"]);
    if (!password_verify($values["pswdOld"], $user["pswd"])) {
        return ["result" => RESULT_WRONG_PSWD];
    }

    if (password_verify($values["pswdNew"], $user["pswd"])) {
        return ["result" => RESULT_NO_PSWDCHANGE];
    }

    try {
        $conn = makeDataBaseConnection();
        
        $query = "UPDATE users SET pswd = '" . password_hash($values["pswdNew"], PASSWORD_DEFAULT, [14]) . "' WHERE id = " . $user["id"] . ";";

        executeDataBaseQuery($query, $conn);
        return ['result' => RESULT_OK, 'user' => $user];

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


function authenticateUser($email, $pswd) { 
    if (empty($email)) {
        return ['result' => RESULT_EMPTY_EMAIL];
    }

    if(empty($pswd)) {
        return ['result' => RESULT_EMPTY_PSWD];
    }

    // caught in validateLogin()
    $user = getUserDataByEmail($email);
    if (empty($user)) {
        return ['result' => RESULT_UNKNOWN_USER]; 
    } 

    if (!password_verify($pswd, $user["pswd"])) { 
        return ['result' => RESULT_WRONG_PSWD]; 
    } 
    
    return ['result' => RESULT_OK, 'user' => $user]; 
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
        return ["products"=>$products];
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

function addOrder() {
    $conn = makeDataBaseConnection();

    include_once('session_manager.php');
    try {
        // default of date col is current date
        $query = "INSERT INTO orders (user_id) VALUES (" . getLoggedInUserId() . ");";

        executeDataBaseQuery($query, $conn);

        $cart = getCart();
        $query = "INSERT INTO ordersproducts (order_id, product_id, count) VALUES ";
        foreach($cart as $productId=>$count) {
            // werkt mysql_insert_id als er heel veel aanvragen tegelijkertijd binnenkomen?
            $query .= "('" . mysqli_insert_id($conn) . "','" . $productId . "','" . $count . "'),";

        }
        // replace , with ;
        $query[strlen($query)-1] = ";";
        executeDataBaseQuery($query, $conn);

    }
    finally {
        mysqli_close($conn);
    }
}

function getTopKProducts($k) {
    $conn = makeDataBaseConnection();

    try {
        $query = "SELECT p.*, SUM(op.count) as total_sold FROM ordersproducts as op
        RIGHT JOIN products as p ON op.product_id = p.id
        LEFT JOIN orders as o ON op.order_id = o.id
        WHERE o.order_date > DATE_SUB(date(CURRENT_DATE), INTERVAL 7 DAY) or o.order_date IS NULL
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT " . $k . ";";

        $result = executeDataBaseQuery($query, $conn);

        while($row = mysqli_fetch_assoc($result)) {
            $products[$row["id"]] = array("id"=> $row["id"], "name"=>$row["name"], "description"=>$row["description"], "price"=>$row["price"], "fname"=>$row["fname"]);
        }
    
        return ["products" => $products];
    }
    finally {
        mysqli_close($conn);
    }


}