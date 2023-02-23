<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['city'] != ''){
    $city = $_POST['city'];

    try {

        $query = $dbconn->prepare("SELECT * FROM DISTRICT WHERE CITY_ID = $city");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        // IF DATA EXIST RETURN DATA

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}

