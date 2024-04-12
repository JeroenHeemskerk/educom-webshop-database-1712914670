<?php 

function addAccount($credentials) {
    // TODO: wat is "or" keyword
    $users = fopen("users.txt", "a") or die("Unable to open file!");
    
    $registration = [$credentials["email"], $credentials["username"] , $credentials["pswd"]];
    fwrite($users, implode("|", $registration) . PHP_EOL);
    fclose($users);
}

function doesEmailExist($email) {
    // TODO: wat is "or" keyword
    $users = fopen("users.txt", "r") or die("Unable to open file!");

    while (!feof($users)) {
        $current_credentials = explode("|", fgets($users));
        $current_email = $current_credentials[0];
        if ($current_email == $email) {
            return true;
        }
    }
    fclose($users);
    return false;
}

function authenticateUser($email, $pswd) {
    $users = fopen('users.txt', "r") or die("Unable to open file!");

    while (($line = fgets($users)) !== false) {
        // Skip empty lines
        if (trim($line) == '') {
            continue;
        }

        $current_credentials = explode("|", $line);
        $current_email = $current_credentials[0];
        $current_pswd = $current_credentials[2];

        if ($current_email == $email && $current_pswd == $pswd . PHP_EOL) {
            return true;
        }
    }
    
    fclose($users);
    return false;
}

function getUserByEmail($email) {
    $users = fopen("users.txt", "r") or die("Unable to open file!");

    while (!feof($users)) {
        $current_credentials = explode("|", fgets($users));
        $current_email = $current_credentials[0];
        if ($current_email == $email) {
            return $current_credentials[1];
        }
    }
    fclose($users);
    return false;
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