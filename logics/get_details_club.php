<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['choice'] != ''){
    $choice = $_POST['choice'];

    try {

        $query = $dbconn->prepare("SELECT TKT.*, KTA.F_PIN AS L_PIN FROM TKT LEFT JOIN KTA ON TKT.ADMIN_KTA = KTA.NO_ANGGOTA WHERE TKT.ID = '".$choice."'");
        $query->execute();
        $data = $query->get_result()->fetch_assoc();
        $query->close();

        if (isset($data)){
            echo(base64_encode(json_encode($data)));
        }else{
            echo("");
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}

