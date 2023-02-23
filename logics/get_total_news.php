<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = "024b7bb318";

$sql_where = "";

if (isset($_GET['category'])) {
    $sql_where = "AND c.ID = " . $_GET['category'];
}

try {

    $sql = "
    SELECT p.* , c.ID, c.CODE
    FROM POST p
    LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID
    LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID
    WHERE F_PIN = '$f_pin' 
    ".$sql_where."
    ORDER BY CREATED_DATE DESC";
    $query = $dbconn->prepare($sql);
    $query->execute();
    $data = $query->get_result();
    $query->close();

    // IF DATA EXIST RETURN DATA

    if (isset($data)){
        echo(mysqli_num_rows($data));
    }else{
        echo("");
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}
    
