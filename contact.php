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

        if (empty($values["gender"])) {
            $errors["gender"] = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
        }
        else if (!array_key_exists($values['gender'], GENDERS)) {
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

        if ($values["comm"] == "email" && !filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Vul alsjeblieft een geldig emailadres in.";
        }

        if ($values["comm"] == "phone" && empty($values["phone"])) {
            $errors["phone"] = "Vul alsjeblieft een telefoonnummer in. ";
        }
        else if (!empty($values["phone"]) && !ctype_digit($values["phone"])) {
            $errors["phone"] = "Vul alsjeblieft een telefoonnummer in met alleen cijfers.";
        }

        $street_flag = empty($values["street"]);
        $housenumber_flag = empty($values["housenumber"]);
        $postalcode_flag = empty($values["postalcode"]);
        $municip_flag = empty($values["municip"]);
        if ($values["comm"] == "post" || !$street_flag || !$housenumber_flag || !$postalcode_flag  || !$municip_flag) {
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



function showContactContent ($data) {
    include_once('forms.php');
    showFormStart("Neem contact op:", "Het Contactformulier");
    showFormField('gender', 'Aanhef', 'select', $data, NULL, GENDERS);
    showFormField('name', 'Voor- en achternaam', 'text', $data, "Marie Jansen", NULL, false);
    showFormField('email', "Email", "text", $data, "voorbeeld@mail.com");
    showFormField('phone', "Telefoonnummer", "tel", $data, "0612345678");
    showFormField('street', 'Straatnaam', 'text', $data, "Lindeweg");
    showFormField("housenumber", "Huisnummer", "number", $data, "1");
    showFormField("additive", "Toevoeging", "text", $data, "A");
    showFormField("postalcode", "Postcode", "text", $data, "1234AB");
    showFormField("municip", "Gemeente", "text", $data, "Utrecht");
    showFormField('comm', 'Communicatie, via', 'radio', $data, NULL, COMM_PREFS);
    showFormField('msg', "Uw bericht", "textarea", $data, "Schrijf hier uw bericht...", ["rows" => 10, "cols" => 60]);
    showFormEnd("contact", "Verzenden");
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