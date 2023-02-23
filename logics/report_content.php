<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

// GET FROM COLLECTION JS

$f_pin = $_POST['f_pin'];
$post_id = $_POST['post_id'];
$report_category = $_POST['report_category'];
// $count_report = $_POST['count_report'];
$count_report = 0;
// get report count
$reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_reported_posts.php');
if (in_array($post_id, $reports_arr)) {
    // echo 'bro';
    // continue;
    $count_report = $reports_arr[$post_id]["TOTAL_REPORTS"];
}

$created_at = time() * 1000;

$new_report = $count_report + 1;

// INSERT REPORT

$query = "INSERT INTO REPORT_POST (F_PIN, POST_ID, REPORT_CATEGORY, CREATED_AT) VALUES ('".$f_pin."','".$post_id."','".$report_category."','".$created_at."')";

$query2 = "UPDATE POST SET REPORT = '".$new_report."' WHERE POST_ID = '".$post_id."'";

if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $query2)){
    echo ("Berhasil");
}else{
    echo("Gagal dari Query");
}

?>