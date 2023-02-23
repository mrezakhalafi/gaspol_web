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

$sqlData = "SELECT * FROM USER_LIST_EXTENDED_GASPOL WHERE F_PIN = '$f_pin'";

$queEXDATA = $dbconn->prepare($sqlData);
$queEXDATA->execute();
$userEXDataGaspol = $queEXDATA->get_result()->fetch_assoc();
$queEXDATA->close();

$province = $_POST['province'];

// $uploadOk = 1;
// 32
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';

// OPERASIONAL

// $target_file_loc = array();

// PP IMAGE
$image = strtolower(pathinfo($_FILES["fotoProfile"]["name"],PATHINFO_EXTENSION));
$ppimage = "PROFILEUSER-".$f_pin . time() . ".webp";
$target_file_loc = $target_dir . $ppimage;

// POST

$club_name = $_POST['user_name'];

$user_gender = $_POST['gender_radio'];

if ($user_gender == 0) {
    $user_gender = 1;
}

$user_email = $_POST['email_user'];
$birthdate = $_POST['date_birth'];

$bio = $_POST['bio'];
$id_category = $_POST['id_category'];

// END POST

// $connection = ssh2_connect('202.158.33.26', 2309);
// ssh2_auth_password($connection, 'easysoft', '*347e^!VU4y+#hAP');

$start_upload = true;

$start_upload = move_uploaded_file($_FILES["fotoProfile"]["tmp_name"], $target_file_loc);

if($start_upload){

    copy($target_file_loc, '/apps/lcs/gaspol/server/image/' . $ppimage);

    // $ssh_local_file = '/var/www/html/qmera/gaspol_web/images/' . $ppimage;

    // move file to cu directory
    // copy($uploaded_file, 'http://202.158.33.27:2809/' . $originator . '-' . $hex . '.' . $fileType);

    // ssh2_scp_send($connection, $ssh_local_file, '/apps/lcs/paliolite/server/image/' . $ppimage, 0777);

    // if dia gak punya f_pin yg terdaftar di table USER_LIST_EXTENDED_GASPOL

    if (isset($userEXDataGaspol)) {
        $postqueryUserList = "UPDATE USER_LIST SET FIRST_NAME = '$club_name', IMAGE = '$ppimage', QUOTE = '$bio', EMAIL = '$user_email' WHERE F_PIN = '$f_pin'";
        
        
        if ($birthdate != ""){
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = '$birthdate', GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }else{
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = NULL, GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }

        $postqueryUListExGaspol = "UPDATE USER_LIST_EXTENDED_GASPOL SET PROVINCE = '$province', ID_CATEGORY = '$id_category' WHERE F_PIN = '$f_pin'";
    }
    else {
        $postqueryUserList = "UPDATE USER_LIST SET FIRST_NAME = '$club_name', IMAGE = '$ppimage', QUOTE = '$bio', EMAIL = '$user_email' WHERE F_PIN = '$f_pin'";
        
        if ($birthdate != ""){
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = '$birthdate', GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }else{
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = NULL, GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }

        $postqueryUListExGaspol = "INSERT INTO USER_LIST_EXTENDED_GASPOL (F_PIN, PROVINCE, ID_CATEGORY) VALUES ('".$f_pin."', '".$province."', '".$id_category."')";
    }

    if (mysqli_query($dbconn, $postqueryUserList) && mysqli_query($dbconn, $postqueryUsListEx) && mysqli_query($dbconn, $postqueryUListExGaspol)) {
        echo("Koneksi Database Berhasil");
    }
    else {
        // echo($postquery);
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }

}else{

    if (isset($userEXDataGaspol)) {
        $postqueryUserList = "UPDATE USER_LIST SET FIRST_NAME = '$club_name', QUOTE = '$bio', EMAIL = '$user_email' WHERE F_PIN = '$f_pin'";
        
        if ($birthdate != ""){
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = '$birthdate', GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }else{
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = NULL, GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }

        $postqueryUListExGaspol = "UPDATE USER_LIST_EXTENDED_GASPOL SET PROVINCE = '$province', ID_CATEGORY = '$id_category' WHERE F_PIN = '$f_pin'";
        echo("1");
    }
    else {
        $postqueryUserList = "UPDATE USER_LIST SET FIRST_NAME = '$club_name', QUOTE = '$bio', EMAIL = '$user_email' WHERE F_PIN = '$f_pin'";
        
        if ($birthdate != ""){
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = '$birthdate', GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }else{
            $postqueryUsListEx = "UPDATE USER_LIST_EXTENDED SET BIRTHDATE = NULL, GENDER = '$user_gender' WHERE F_PIN = '$f_pin'";
        }

        $postqueryUListExGaspol = "INSERT INTO USER_LIST_EXTENDED_GASPOL (F_PIN, PROVINCE, ID_CATEGORY) VALUES ('".$f_pin."', '".$province."', '".$id_category."')";
        echo("2");
    }

    if (mysqli_query($dbconn, $postqueryUserList) && mysqli_query($dbconn, $postqueryUsListEx) && mysqli_query($dbconn, $postqueryUListExGaspol)) {
        echo("Koneksi Database Berhasil");
    }
    else {
        // echo($postquery);
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }
    
}

?>