<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_POST['f_pin'];

try {

    $query = $dbconn->prepare("SELECT * FROM USER_LIST_EXTENDED WHERE F_PIN = '".$f_pin."'");
    $query->execute();
    $data = $query->get_result()->fetch_assoc();
    $query->close();

    $address = $data['ADDRESS'];

    // IF DATA EXIST RETURN DATA

    if ($address != null || $address != ""){
        echo("Valid");
    }else{
        echo("Not Valid");
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}


