<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$postcode = $_POST['postcode'];

if($postcode != 0){

    try {

        $query = $dbconn->prepare("SELECT * FROM POSTAL_CODE WHERE POSTAL_CODE LIKE '%$postcode%' GROUP BY POSTAL_CODE");
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
    
}else{
    $province = $_POST['province'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $subdistrict = $_POST['subdistrict'];

    try {

        $query = $dbconn->prepare("SELECT * FROM POSTAL_CODE WHERE PROV_ID = $province AND CITY_ID = $city AND DIS_ID = $district AND SUBDIS_ID = $subdistrict");
        $query->execute();
        $data = $query->get_result()->fetch_assoc();
        $query->close();

        // IF DATA EXIST RETURN DATA

        if ($data){
            echo(json_encode($data));
        }else{
            echo("");
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
}

