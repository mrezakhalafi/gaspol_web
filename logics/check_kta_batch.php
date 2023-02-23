<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$email = $_POST['email'];
$ektp = $_POST['ektp'];

try {

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE EMAIL = '$email'");
    $query->execute();
    $email = $query->get_result()->fetch_assoc();
    $query->close();

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE EKTP = '$ektp'");
    $query->execute();
    $ektp = $query->get_result()->fetch_assoc();
    $query->close();

    if (isset($email)){
        echo(1);    // Email Exist
    }else if(isset($ektp)){
        echo(2);    // EKTP Exist
    }else{
        echo(0);
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}


