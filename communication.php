<?php 

// dit voelt niet goed, 
// $servername = "localhost";
// $username = "florians_webshop_user";
// $password = "_9Cq>&djZFE>g5i";
// $dbname = "florians_webshop";

// // Create connection

// try {
//     $conn = mysqli_connect($servername, $username, $password, $dbname);
// }
// catch (Exception $e) {
//     echo 'MySQL connection error: ' . $e->getMessage() . PHP_EOL;
//     exit();
// }

// $sql = "SELECT * FROM users;";

// try {
//     $result = mysqli_query($conn, $sql);
// }
// catch (Exception $e) {
//     echo 'MySQL select error: ' . $e->getMessage() . PHP_EOL;
// }

// if (mysqli_num_rows($result) > 0) {
//     // output data of each row
//     while($row = mysqli_fetch_assoc($result)) {
//       echo "id: " . $row["id"]. " - Email: " . $row["email"] . " with username: " . $row["user"] . " and Password: " . $row["pswd"] . "<br>";
//     }
//   } else {
//     echo "0 results";
//   }
// mysqli_close($conn);


function addAccount($credentials) {
    // dit voelt niet goed
    $servername = "localhost";
    $username = "florians_webshop_user";
    $password = "_9Cq>&djZFE>g5i";
    $dbname = "florians_webshop";

    try {
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    }
    catch (Exception $e) {
        echo 'MySQL connection error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }

    $query = "INSERT INTO users (email, user, pswd) VALUES ('" . $credentials["email"] . "','" . $credentials["user"] . "','" . $credentials["pswd"] . "');";

    try {
        mysqli_query($conn, $query);
    }
    catch (Exception $e) {
        echo 'MySQL query error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }
}

function doesEmailExist($email) {
    // dit voelt niet goed
    $servername = "localhost";
    $username = "florians_webshop_user";
    $password = "_9Cq>&djZFE>g5i";
    $dbname = "florians_webshop";

    try {
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    }
    catch (Exception $e) {
        echo 'MySQL connection error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }

    $query = "SELECT email FROM users WHERE email='" . $email . "';";

    try {
        $result = mysqli_query($conn, $query);
    }
    catch (Exception $e) {
        echo 'MySQL query error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }
    $row = mysqli_fetch_assoc($result);

    if ($row == NULL) {
        return false;
    }

    return true;
}

function authenticateUser($email, $pswd) {
    // dit voelt niet goed
    $servername = "localhost";
    $username = "florians_webshop_user";
    $password = "_9Cq>&djZFE>g5i";
    $dbname = "florians_webshop";

    try {
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    }
    catch (Exception $e) {
        echo 'MySQL connection error: ' . $e->getMessage() . PHP_EOL;
        exit();
    }

    $query = 'SELECT email, pswd FROM users WHERE email="' . $email . '";';

    try {
        $result = mysqli_query($conn, $query);
    }
    catch (Exception $e) {
        echo 'MySQL query error: ' . $e->getMessage() . PHP_EOL;
    }
    $row = mysqli_fetch_assoc($result);

    if ($row == NULL) {
        return false;
    }

    if ($row["pswd"] == $pswd) {
        echo $row["email"];
        return true;
    }
    return false;

}

function getUserByEmail($email) {
       // dit voelt niet goed
       $servername = "localhost";
       $username = "florians_webshop_user";
       $password = "_9Cq>&djZFE>g5i";
       $dbname = "florians_webshop";
   
       try {
           $conn = mysqli_connect($servername, $username, $password, $dbname);
       }
       catch (Exception $e) {
           echo 'MySQL connection error: ' . $e->getMessage() . PHP_EOL;
           exit();
       }
   
       $query = "SELECT user FROM users WHERE email='" . $email . "';";
   
       try {
           $result = mysqli_query($conn, $query);
       }
       catch (Exception $e) {
           echo 'MySQL query error: ' . $e->getMessage() . PHP_EOL;
           exit();
       }
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
}

function doLogoutUser() {
    session_unset();
    session_destroy();
}

function getLoggedInUser() {
    return getUserByEmail(getSessionVar('email'));
}
