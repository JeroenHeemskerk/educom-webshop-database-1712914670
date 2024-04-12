<?php 

function getContactTitle() {
    return "Contact";
}

include_once('index.php');
define("GENDERS", array(""=> "--", "mevr"=>"Mevr.", "dhr"=>"Dhr.", "dhr_mevr" => "Dhr. / Mevr.", "mevr_dhr" => "Mevr. / Dhr.", "unspecified" => "Zeg ik liever niet."));
define("COMM_PREFS", array("email" => "Email", "phone" => "Telefoon", "post" => "Post"));

function validateContact() {
    $valid = false;
    $errors = array("gender"=>"", "name"=>"", "msg"=>"", 
    "comm"=>"", "email"=>"", "phone"=>"", "street"=>"", "housenumber"=>"", "postalcode"=>"", "municip"=>"");
    $values = array("gender"=>"--", "name"=>"", "email"=>"", "phone"=>"", "street"=>"", "housenumber"=>"", "additive"=>"", "postalcode"=>"", "municip"=>"", "msg"=>"", "comm"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $values["gender"] = getPostVar("gender");
        $values["name"] =  getPostVar("name");
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["phone"] =  getPostVar("phone");
        $values["street"] =  getPostVar("street");
        $values["housenumber"] =  getPostVar("housenumber");
        $values["additive"] =  getPostVar("additive");
        $values["postalcode"] =  getPostVar("postalcode");
        $values["municip"] =  getPostVar("municip");
        $values["msg"] =  getPostVar("msg");

        if ($values["gender"] == "--") {
            $errors["gender"] = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
        }
        else if (array_key_exists($values['gender'], GENDERS)) {
            $errors["gender"] = "Selecteer alsjeblieft een van de aanhefvoorkeuren.";
        }

        if (empty($values["name"])) {
            $errors["name"] = "Vul alsjeblieft je volledige naam in.";
        }
        else if (!preg_match('/[a-zA-Z]/', $values["name"])) {  
            $errors["name"] = "Vul alsjeblieft een naam in met minstens 1 letter.";
        }

        if (empty($values["msg"])) {
            $errors["msg"] = "Vul alsjeblieft een bericht in.";
        }
        
        if (empty(getPostVar("comm"))) {
            $errors["comm"] = "Vul alsjeblieft je communicatievoorkeur in.";
        }
        else {
            $values["comm"] = getPostVar("comm");
        }

        if ($values["comm"] == "Email" && !filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Vul alsjeblieft een geldig emailadres in.";
        }

        if ($values["comm"] == "Telefoon" && empty($values["phone"])) {
            $errors["phone"] = "Vul alsjeblieft een telefoonnummer in. ";
        }
        else if (!empty($values["phone"]) && !ctype_digit($values["phone"])) {
            $errors["phone"] = "Vul alsjeblieft een telefoonnummer in met alleen cijfers.";
        }

        $street_flag = empty($values["street"]);
        $housenumber_flag = empty($values["housenumber"]);
        $postalcode_flag = empty($values["postalcode"]);
        $municip_flag = empty($values["municip"]);
        if ($values["comm"] == "Post" || !$street_flag || !$housenumber_flag || !$postalcode_flag  || !$municip_flag) {
            if ($street_flag) {
                $errors["street"] = "Vul alsjeblieft je straatnaam in.";
            }

            if ($housenumber_flag) {
                $errors["housenumber"] = "Vul alsjeblieft je huisnummer in.";
            }

            if ($postalcode_flag) {
                $errors["postalcode"] = "Vul alsjeblieft je postcode in.";
            }
            else if (!preg_match('/^[0-9]{4}[A-Z]{2}$/', $values["postalcode"])) {
                $errors["postalcode"] = "Vul alsjeblieft een geldige Nederlands postcode in."; 
            }

            if ($municip_flag) {
                $errors["municip"] = "Vul alsjeblieft je gemeente in.";
            }
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

function showContactStart() {
    echo "<h2>Het Contactformulier</h2>";
    echo '<form method="POST" action="'; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '">
    <p>Neem contact op:</p>';
}

function showContactEnd() {
    echo '<input type="hidden" id="page" name="page" value="contact">';
    echo '<input type="submit" value="Verzenden">';
    echo '</form>';
}

function showContactField($fieldName, $label, $type, $data, $placeholder=NULL, $options=NULL, $optional=true) {
    $values = $data["values"];
    $errors = $data["errors"];

    echo '<div>';
    echo '<label for="' . $fieldName . '">' . $label . ': </label>';

    switch ($type) {
        case "text":
        case "tel":
        case "number":
            echo '<input type="' . $type . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '" placeholder="' . $placeholder . '">';
            echo '<span class="error">';
            if (!$optional) {echo " * ";}
            if (!empty($errors[$fieldName])) {
                if ($optional) {echo " * ";} 
                echo  $errors[$fieldName];
            }
            echo '</span>';
            break;


        case "textarea":
            echo '<' . $type . ' id="' . $fieldName . '" name="' . $fieldName . '" ';
            foreach($options as $key => $option) {
                // bit hacky, used for cols and rows
                echo $key . '="' . $option . '" ';
            }
            echo 'placeholder="' . $placeholder . '">';
            echo $values[$fieldName] . '</' . $type . '>';
            echo '<span class="error"> * ' . $errors[$fieldName] . '</span>';
            break;

        case "radio":
            foreach($options as $key => $option) {
                echo '<input type="' . $type . '"';
                echo 'id="' . $key . '" name="' . $fieldName . '" value="' . $option . '" ';
                if (isset($values[$fieldName]) && $values[$fieldName] == $option) { echo "checked";}
                echo '><label class="radio" for="' . $key . '">' . $option . '</label>';
            }
            echo '<span class="error"> * '  . $errors[$fieldName] . '</span>';
            break;

        case "select":
            echo '<' . $type . ' id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '">';
            foreach($options as $option) {
                echo '<option value="' . $option . '"';
                if ($values[$fieldName] == $option) {echo "selected";}
                echo '>' . $option . '</option>';
            }
            echo '</select>';
            echo '<span class="error"> * ' . $errors[$fieldName] . '</span>';
            break;
    }
    echo '</div>';
}

function showContactContent ($data) {
    showContactStart();
    showContactField('gender', 'Aanhef', 'select', $data, NULL, GENDERS);
    showContactField('name', 'Voor- en achternaam', 'text', $data, "Marie Jansen", NULL, false);
    showContactField('email', "Email", "text", $data, "voorbeeld@mail.com");
    showContactField('phone', "Telefoonnummer", "tel", $data, "0612345678");
    showContactField('street', 'Straatnaam', 'text', $data, "Lindeweg");
    showContactField("housenumber", "Huisnummer", "number", $data, "1");
    showContactField("additive", "Toevoeging", "text", $data, "A");
    showContactField("postalcode", "Postcode", "text", $data, "1234AB");
    showContactField("municip", "Gemeente", "text", $data, "Utrecht");
    showContactField('comm', 'Communicatie, via', 'radio', $data, NULL, COMM_PREFS);
    showContactField('msg', "Uw bericht", "textarea", $data, "Schrijf hier uw bericht...", ["rows" => 10, "cols" => 60]);
    showContactEnd();
} 
        
function showContactThanks($data) {
    $values = $data["values"];

    echo '<p>Bedankt, ' . $values["name"] . ', voor je reactie:</p>
    <div>Aanhef: ' . $values["gender"] . '</div>
    <div>Naam: ' . $values["name"] . '</div>';
    if (!empty($values["phone"])) { 
        echo '<div>Tel: ' . $values["phone"] . '</div>';
    } 
    if (!empty($values["email"])) { 
        echo '<div>Email:  '. $values["email"] . '</div>';
    } 
    
    // At this point, either all are filled in, or none. So only one check required.
    if (!empty($values["street"])) { 
        echo '<div>Adres: ' . $values["street"] . ' ' . $values["housenumber"] . $values["additive"] . '</div>
        <div>Woonplaats: ' . $values["postalcode"] . ', ' . $values["municip"] . '</div>
        <div>Communicatievoorkeur: ' . $values["comm"] . '</div>';
    }
}