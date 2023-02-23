<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// if (isset($_SESSION['user_f_pin'])){
//     $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
    $f_pin = $_POST['f_pin'];
// }

// GET USER DATA EXTENDED GASPOL

$vehicle_island = $_POST['vehicle-island'];

// $uploadOk = 1;
// 32
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';

// OPERASIONAL

// $target_file_loc = array();

// PP IMAGE
$image = strtolower(pathinfo($_FILES["photo-name"]["name"],PATHINFO_EXTENSION));
$ppimage = "PP-ROADSIDE-ASSISTANCE-".$f_pin . time() . ".webp";
$target_file_loc = $target_dir . $ppimage;

// POST

$vehicle_brand = $_POST['vehicle-brand'];
$vehicle_type = $_POST['vehicle-type'];
$vehicle_year = $_POST['vehicle-year'];
$vehicle_license = $_POST['vehicle-license'];
$vehicle_category = $_POST['vehicle_category'];

// END POST

$start_upload = true;
$start_upload = move_uploaded_file($_FILES["photo-name"]["tmp_name"], $target_file_loc);
if($start_upload){
    
    $postquery = "INSERT INTO ROADSIDE_ASSISTANCE (F_PIN, VEHICLE_CATEGORY, ISLAND, VEHICLE_PHOTO, VEHICLE_BRAND, TYPE, YEAR, LICENSE_PLATE) VALUES ('".$f_pin."', '".$vehicle_category."', '".$vehicle_island."', '".$ppimage."', '".$vehicle_brand."', '".$vehicle_type."', '".$vehicle_year."', '".$vehicle_license."')";
        
    if (mysqli_query($dbconn, $postquery)) {
        echo("Koneksi Database Berhasil");
    }
    else {
        echo($postquery);
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }

}else{

    $postquery = "INSERT INTO ROADSIDE_ASSISTANCE (F_PIN, VEHICLE_CATEGORY, ISLAND, VEHICLE_PHOTO, VEHICLE_BRAND, TYPE, YEAR, LICENSE_PLATE) VALUES ('".$f_pin."', '".$vehicle_category."', '".$vehicle_island."', '".$vehicle_brand."', '".$vehicle_type."', '".$vehicle_year."', '".$vehicle_license."')";
        
    if (mysqli_query($dbconn, $postquery)) {
        echo("Koneksi Database Berhasil");
    }
    else {
        echo("Upload Failed.");
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }
    
}

?>