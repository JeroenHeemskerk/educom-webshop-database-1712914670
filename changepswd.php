<?php

function getChangePswdTitle() {
    return "Password Change";
}

function validateChangePswd() {
    $valid = false;
    $errors = array("pswdOld"=>"", "pswdNew"=>"", "pswdNew2"=>"", "general"=>""); 
    $values = array("pswdOld"=>"", "pswdNew"=>"", "pswdNew2"=>"", "email"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $values["pswdOld"] = getPostVar("pswdOld");
        $values["pswdNew"] =  getPostVar("pswdNew");
        $values["pswdNew2"] =  getPostVar("pswdNew2");
        include_once('session_manager.php');
        $values["email"] = getLoggedInEmail();

        try {
            include_once('communication.php');
            // caught in processPage() 
            $authResult = authenticateNewPswd($values);
            switch ($authResult['result']) {
                case RESULT_EMPTY_PSWD:
                    $errors["pswdOld"] = "Vul alsjeblieft je huidige wachtwoord in."; 
                    break;

                case RESULT_EMPTY_NEWPSWD:
                    $errors["pswdNew"] = "Herhaal alsjeblieft je nieuwe wachtwoord tweemaal."; 
                        break;
           
                case RESULT_WRONG_PSWD:
                    $errors["pswdOld"] = "Wachtwoord onjuist."; 
                    break;

                case RESULT_WRONG_NEWPSWD:
                    $errors["pswdNew"] = "Wachtwoorden komen niet overeen.";
                    break;

                case RESULT_NO_PSWDCHANGE:
                    $errors["pswdNew"] = "Vul alsjeblieft een <b>nieuw</b> wachtwoord in.";
                    break;
           
                case RESULT_OK:
                    $values['userName'] = $authResult['user']['name'];
                    $values['userId'] = $authResult['user']['id'];
                    break;
             }
        } 
        catch (Exception $e) {
            $errors["general"] = "Er is een technische storing, u kunt uw wachtwoord niet updaten. Probeer het later nogmaals.";
            logError('Authentication failed for user ' . $values['email'] . ', SQLError: ' . $e -> getMessage());
        }

        foreach($errors as $err_msg) {
            if (!empty($err_msg)) {
                return ['valid' => false, 'values' => $values, 'errors' => $errors];
            }
        }
        $valid = true;
    }

    return ['valid' => $valid, 'values' => $values, 'errors' => $errors];
}

function showChangePswd($data) {
    include_once('forms.php');
    showFormStart("Wijzig hier je wachtwoord:", "Wachtwoord Wijziging");
    showFormField("pswdOld", "Huidige wachtwoord", "password", $data, NULL, NULL, false);
    showFormField("pswdNew", "Nieuwe wachtwoord", "password", $data, NULL, NULL, false);
    showFormField("pswdNew2", "Herhaal wachtwoord", "password", $data, NULL, NULL, false);
    showFormEnd("changepswd", "Wijzig wachtwoord");
}