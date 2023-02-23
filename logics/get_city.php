<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['province'] != ''){
    $province = $_POST['province'];

    try {

        $query = $dbconn->prepare("SELECT * FROM CITY WHERE PROV_ID = $province");
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

