<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

$f_pin = $_POST['f_pin'];
$club_type = $_POST['club_type'];
$club_location = $_POST['club_location'];
$club_choice = $_POST['club_choice'];
$ref_id = $_POST['ref_id'];

// UPDATE CLUB

$query = "INSERT INTO CLUB_MEMBERSHIP (REF_ID, F_PIN, CLUB_TYPE, CLUB_LOCATION, CLUB_CHOICE, STATUS) VALUES ('".$ref_id."','".$f_pin."',".$club_type.",".$club_location.",".$club_choice.",0)";

if (mysqli_query($dbconn, $query)){
    // echo($query);
    echo("Success");
}else{
    // echo($query);
    echo("Failed");
}

?>