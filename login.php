<?php 

function getLoginTitle() {
    return "Login";
}

function validateLogin() {
    $valid = false;
    $errors = array("email"=>"", "pswd"=>"", "general"=>"");
    $values = array("email"=>"", "pswd"=>"", "userId"=>"", "userName"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Is deze include nodig
        include_once('index.php');
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["pswd"] =  getPostVar("pswd");

        try {
            include_once('communication.php'); 
            $authResult = authenticateUser($values["email"], $values["pswd"]);
            switch ($authResult['result']) {
                case RESULT_EMPTY_EMAIL:
                    $errors["email"] = "Vul alsjeblieft je emailadres in.";
                    break;

                case RESULT_UNKNOWN_USER: 
                    $errors["email"] = "Er is geen account bekend op deze website met dit emailadres."; 
                    break;

                case RESULT_EMPTY_PSWD:
                    $errors["pswd"] = "Vul alsjeblieft je wachtwoord in."; 
                    break;
           
                case RESULT_WRONG_PASSWORD:
                    $errors["pswd"] = "Wachtwoord onjuist."; 
                    break;
           
                case RESULT_OK:
                    $values['userName'] = $authResult['user']['name'];
                    $values['userId'] = $authResult['user']['id'];
                    break;
             }
        } 
        catch (Exception $e) {
            $errors["general"] = "Er is een technische storing, u kunt niet inloggen. Probeer het later nogmaals.";
            logError('Authentication failed for user ' . $values['email'] . ', SQLError: ' . $e -> getMessage());
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
