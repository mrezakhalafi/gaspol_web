<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$id = $_POST['product_id'];
$is_prod = $_POST['is_product'];
$dbconn = paliolite();

// get store products
if ($is_prod == 1) {
    $str = "SELECT p.CODE, p.THUMB_ID, p.NAME, p.DESCRIPTION FROM PRODUCT p WHERE p.CODE = '$id'";
} else {
    $str = "SELECT p.POST_ID AS CODE, p.FILE_ID AS THUMB_ID, p.TITLE AS NAME,p.LINK, p.DESCRIPTION FROM POST p WHERE p.POST_ID = '$id'";
}

$query = $dbconn->prepare($str);
$query->execute();
$product = $query->get_result()->fetch_assoc();
$query->close();

function utf8ize($d) {
    // if (is_array($d)) {
    //     foreach ($d as $k => $v) {
    //         $d[$k] = utf8ize($v);
    //     }
    // } else if (is_string ($d)) {
    //     return utf8_encode($d);
    // }
    return $d;
}

echo json_encode(utf8ize($product));