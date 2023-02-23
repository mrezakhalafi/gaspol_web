<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$product_code = $_POST['product_code'];
$flag_like = $_POST['flag_like'];
$last_update = $_POST['last_update'];
$f_pin = $_POST['f_pin'];
// $is_post = $_POST['is_post'];

try {
    // if ($is_post == 0) {
    //     $query = $dbconn->prepare("INSERT INTO LINK_REACTION (LINK_ID, F_PIN, FLAG, LAST_UPDATE) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE FLAG = ?, LAST_UPDATE = ?");
    //     $query->bind_param("ssssss", $product_code, $f_pin, $flag_like, $last_update, $flag_like, $last_update);
    //     $status = $query->execute();
    //     $query->close();

    //     // if($flag_like == '1'){
    //     //     $query = $dbconn->prepare("UPDATE PRODUCT SET TOTAL_LIKES=TOTAL_LIKES+1 WHERE CODE = ?");
    //     //     $query->bind_param("s", $product_code);
    //     // } else {
    //     //     $query = $dbconn->prepare("UPDATE PRODUCT SET TOTAL_LIKES=IF(TOTAL_LIKES<=0,0,TOTAL_LIKES-1) WHERE CODE = ?");
    //     //     $query->bind_param("s", $product_code);
    //     // }
    //     if ($flag_like == '1') {
    //         $query = $dbconn->prepare("UPDATE LINKS SET TOTAL_LIKES=TOTAL_LIKES+1 WHERE LINK_ID = ?");
    //         $query->bind_param("s", $product_code);
    //     } else {
    //         $query = $dbconn->prepare("UPDATE LINKS SET TOTAL_LIKES=IF(TOTAL_LIKES<=0,0,TOTAL_LIKES-1) WHERE LINK_ID = ?");
    //         $query->bind_param("s", $product_code);
    //     }
    //     $status = $query->execute();
    //     $query->close();
    // } else {
        $sql1 = "INSERT INTO POST_REACTION (POST_ID, F_PIN, FLAG, LAST_UPDATE) VALUES ('$product_code','$f_pin',$flag_like,$last_update) ON DUPLICATE KEY UPDATE FLAG = $flag_like, LAST_UPDATE = $last_update";
        $query = $dbconn->prepare($sql1);
        // $query->bind_param("ssssss", $product_code, $f_pin, $flag_like, $last_update, $flag_like, $last_update);
        $status = $query->execute();
        $query->close();

        if ($flag_like == '1') {
            $query = $dbconn->prepare("UPDATE POST SET TOTAL_LIKES=TOTAL_LIKES+1 WHERE POST_ID = ?");
            $query->bind_param("s", $product_code);
        } else {
            $query = $dbconn->prepare("UPDATE POST SET TOTAL_LIKES=IF(TOTAL_LIKES<=0,0,TOTAL_LIKES-1) WHERE POST_ID = ?");
            $query->bind_param("s", $product_code);
        }
        $status = $query->execute();
        $query->close();
    // }

    echo ' Success ';
} catch (\Throwable $th) {
    //throw $th;
    echo $th->getMessage();
}
