<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['ektp'] != ''){
    $ektp = $_POST['ektp'];

    try {

        $query = $dbconn->prepare("SELECT * FROM KTA WHERE EKTP = '$ektp'");
        $query->execute();
        $data = $query->get_result()->fetch_assoc();
        $query->close();

        if (isset($data)){
            echo(1);
        }else{
            echo(0);
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}

