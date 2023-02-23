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

// $uploadOk = 1;
// 32

// POST

$id_category = $_POST['idcat_val'];

// END POST

$postqueryUserList = "INSERT INTO USER_LIST_EXTENDED_GASPOL (F_PIN, ID_CATEGORY) VALUES ('".$f_pin."', '".$id_category."')";

if (mysqli_query($dbconn, $postqueryUserList)) {
    echo("Koneksi Database Berhasil");
}
else {
    echo($postqueryUserList);
    http_response_code(400);
    echo(mysqli_error($dbconn));
}

?>