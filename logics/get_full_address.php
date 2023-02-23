<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['postcode'] != ''){
    $postcode = $_POST['postcode'];

    try {

        $query = $dbconn->prepare("SELECT * FROM PROVINCE LEFT JOIN CITY ON PROVINCE.PROV_ID = CITY.PROV_ID LEFT JOIN DISTRICT ON DISTRICT.CITY_ID = CITY.CITY_ID
                                    LEFT JOIN SUBDISTRICT ON SUBDISTRICT.DIS_ID = DISTRICT.DIS_ID LEFT JOIN POSTAL_CODE ON POSTAL_CODE.SUBDIS_ID = SUBDISTRICT.SUBDIS_ID WHERE POSTAL_CODE.POSTAL_ID LIKE '%$postcode%'");
        $query->execute();
        $data = $query->get_result()->fetch_assoc();
        $query->close();
        // IF DATA EXIST RETURN DATA

        if (isset($data)){
            echo(json_encode($data));
        }else{
            echo("");
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}

