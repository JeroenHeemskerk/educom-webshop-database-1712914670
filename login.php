<?php 

function getLoginTitle() {
    return "Login";
}

function validateLogin() {
    $valid = false;
    $errors = array("email"=>"", "pswd"=>"");
    $values = array("email"=>"", "pswd"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Is deze include nodig
        include_once('index.php');
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["pswd"] =  getPostVar("pswd");

        if (empty($values["email"])) {
            $errors["email"] = "Vul je alsjeblieft je emailadres in.";
        }

        if (empty($values["pswd"])) {
            $errors["pswd"] = "Vul je alsjeblieft je wachtwoord in.";
        }

        include_once('communication.php');
        if (!doesEmailExist($values["email"])) {
            $errors["email"] = "Er is geen account bekend op deze website met dit emailadres.";
        }
        else if (!authenticateUser($values["email"], $values["pswd"])) {
            $errors["pswd"] = "Wachtwoord onjuist.";
        }

        foreach($errors as $err_msg) {
            if (!empty($err_msg)) {
                $valid = false;
                return ['valid' => $valid, 'values' => $values, 'errors' => $errors];
            }
        }
        
        $valid = true;
    }

    return ['valid' => $valid, 'values' => $values, 'errors' => $errors];
}

function showLoginContent($data) {
    include_once('forms.php');
    showFormStart("Log hier in met je email en wachtwoord:");
    showFormField('email', 'Email', 'text', $data, NULL, NULL, false);
    showFormField('pswd', "Wachtwoord", 'password', $data, NULL, NULL, false);
    showFormEnd("login", "Login");
}
