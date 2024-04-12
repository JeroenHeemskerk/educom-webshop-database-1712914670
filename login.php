<?php 

function getLoginTitle() {
    return "Login";
}

function showLoginStart() {
    echo '<p class="content">Log hier in met je email en wachtwoord:</p>';
    echo '<form method=POST action="'; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '">';
}

function showLoginField($fieldName, $label, $type, $data, $placeholder=NULL) {
    $values = $data["values"];
    $errors = $data["errors"];

    echo '<div>';
    echo '<label for="' . $fieldName . '">' . $label . ': </label>';

    switch ($type) {
        case "text":
            echo '<input type="' . $type . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '" placeholder="' . $placeholder . '">';
            echo '<span class="error"> ' . $errors[$fieldName] . '</span>';
            break;

        case "password":
            echo '<input type="' . $type . '" id="' . $fieldName . '" name="' . $fieldName . '" placeholder="' . $placeholder . '">';
            echo '<span class="error"> ' . $errors[$fieldName] . '</span>';
    }
    echo '</div>';
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

function showLoginEnd() {
    echo '<input type="hidden" id="page" name="page" value="login">';
    echo '<input type="submit" value="Login">';
    echo '</form>';
}

function showLoginContent($data) {
    showLoginStart();
    showLoginField('email', 'Email', 'text', $data);
    showLoginField('pswd', "Wachtwoord", 'password', $data);
    showLoginEnd();
}
