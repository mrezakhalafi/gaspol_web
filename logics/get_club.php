<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['location'] != ''){
    $location = $_POST['location'];
    $f_pin = $_POST['f_pin'];
    $type = $_POST['type'];

    try {

        if ($type == 1){

            $query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = '$location' AND CLUB_TYPE = 1 AND TKT.ID NOT IN (SELECT CLUB_CHOICE FROM CLUB_MEMBERSHIP WHERE F_PIN = '".$f_pin."')"); //1 = Public
            $query->execute();
            $data = $query->get_result();
            $query->close();

        }else if($type == 2){

            $query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = '$location' AND CLUB_TYPE = 2 AND TKT.ID NOT IN (SELECT CLUB_CHOICE FROM CLUB_MEMBERSHIP WHERE F_PIN = '".$f_pin."')"); //1 = Public
            $query->execute();
            $data = $query->get_result();
            $query->close();

        }

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        // IF DATA EXIST RETURN DATA

        if (isset($rows)){
            echo(base64_encode(json_encode($rows)));
        }else{
            echo("");
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}

