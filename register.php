<?php 

function getRegisterTitle() {
    return "Registration";
}

function showRegisterStart() {
    echo '<p class="content">Registreer je door het volgende formulier in te vullen:</p>';
    echo '<form method="POST" action="'; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '">';
}

function showRegisterField($fieldName, $label, $type, $vald_vals_errs, $placeholder=NULL) {
        $values = $vald_vals_errs["values"];
        $errors = $vald_vals_errs["errors"];
    
        echo '<div>';
        echo '<label for="' . $fieldName . '">' . $label . ': </label>';
    
        switch ($type) {
            case "text":
            case "password":
                echo '<input type="' . $type . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '" placeholder="' . $placeholder . '">';
                echo '<span class="error"> ' . $errors[$fieldName] . '</span>';
                break;
            }
        echo '</div>';
}

function showRegisterEnd() {
    echo '<input type="hidden" id="page" name="page" value="register">';
    echo '<input type="submit" value="Registreer">';
    echo '</form>';
}

function showRegisterContent($data) {
    showRegisterStart();
    showRegisterField('username', 'Gebruikersnaam', 'text', $data);
    showRegisterField('email', 'Email', 'text', $data);
    showRegisterField('pswd', 'Wachtwoord', 'password', $data);
    showRegisterField('pswd2', 'Herhaal wachtwoord', 'password', $data);
    showRegisterEnd();

}

function validateRegister() {
    $valid = false;
    $errors = array("username"=>"", "email"=>"", "pswd"=>"", 
    "pswd2"=>"", );

    $values = array("username"=>"", "email"=>"", "pswd"=>"", "pswd2"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $values["username"] = getPostVar("username");
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["pswd"] =  getPostVar("pswd");
        $values["pswd2"] =  getPostVar("pswd2");

        if (empty($values["username"])) {
            $errors["username"] = "Vul alsjeblieft een gebruikersnaam in.";
        }

        if (empty($values["email"])) {
            $errors["email"] = "Vul alsjeblieft je emailadres in.";
        }

        include_once('communication.php');
        if (doesEmailExist($values["email"])) {
            $errors["email"] = "Dit emailadres heeft al een account op deze website.";
        }

        if (!filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Vul alsjeblieft een geldig emailadres in.";
        }

        if (empty($values["pswd"])) {
            $errors["pswd"] = "Vul een wachtwoord in ter registratie.";
        }

        if (empty($values["pswd2"])) {
            $errors["pswd2"] = "Herhaal je gekozen wachtwoord ter verificatie.";
        }

        if ($values["pswd"] != $values["pswd2"]) {
            $errors["pswd2"] = "Wachtwoorden komen niet overeen.";
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
