<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['no_anggota'] != ''){
    $no_anggota = $_POST['no_anggota'];

    try {

        $query = $dbconn->prepare("SELECT * FROM KTA WHERE NO_ANGGOTA = '$no_anggota'");
        $query->execute();
        $data = $query->get_result()->fetch_assoc();
        $query->close();

        if (isset($data)){
            echo(json_encode($data));
        }else{
            echo(0);
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}

