<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// GET USER PIN
// var_dump($_POST);
// var_dump($_FILES);

session_start();
// if (isset($_SESSION['user_f_pin'])){
//   $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
  $f_pin = $_POST['f_pin'];
// }

// $f_pin = "02ba89b7c7";

$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_files = array();

// if($_FILES["fotoSim"]["size"] > 0){
//     $imageFileType = strtolower(pathinfo($_FILES["fotoSim"]["name"],PATHINFO_EXTENSION));
//     $fotoSim = "SIM-".$f_pin . time() . "." . $imageFileType;
//     $target_files['fotoSim'] = $target_dir . $fotoSim;
// }

$imageFileType = strtolower(pathinfo($_FILES["fotoEktp"]["name"],PATHINFO_EXTENSION));
$fotoEktp = "EKTP-".$f_pin . time() . ".webp";
$target_files['fotoEktp'] = $target_dir . $fotoEktp;

$imageFileType = strtolower(pathinfo($_FILES["fotoProfile"]["name"],PATHINFO_EXTENSION));
$pasFoto = "FP-".$f_pin . time() . ".webp";
$target_files['fotoProfile'] = $target_dir . $pasFoto;

// GET FROM POST 

$name = $_POST['name'];
$email = $_POST['email'];
$birthplace = $_POST['birthplace'];
$date_birth = $_POST['date_birth'];
$gender_radio = $_POST['gender_radio'];
$bloodtype = $_POST['bloodtype'];
$nationality = $_POST['nationality'];
$hobby = $_POST['hobby'];
$hobby_desc = $_POST['hobby_desc'];
$is_android = $_POST['is_android'];

$address = $_POST['address'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];
$province = $_POST['province'];
$city = $_POST['city'];
$district = $_POST['district'];
$district_word = $_POST['subdistrict'];
$postcode = $_POST['postcode'];

// $club_type = $_POST['club_type'];
// $club_location = $_POST['club_location'];
// $club_choice = $_POST['club_choice'];
$status_anggota = $_POST['status_anggota'];

// $domisili = $_POST['domisili'];
$ektp = $_POST['ektp'];

// if ($club_choice != null || $club_choice != ""){
//   $club_choice_new = $club_choice;
// }else{
//   $club_choice_new = "0";
// }

// NEW REGISTRATION

if ($status_anggota == 0){

    // CHECK DUPLICATE KTP AND EMAIL

    $sql = "SELECT * FROM `KTA` WHERE `EKTP` = ".$ektp;
    $query = $dbconn->prepare($sql);
    $query->execute();
    $sql_uid = $query->get_result()->fetch_assoc();

    if (!is_null($sql_uid)){
        echo("Nomer E-KTP sudah terdaftar pada KTA yang berbeda.");
        http_response_code(409);
        exit();
    }

    $sql2 = "SELECT * FROM `KTA` WHERE `EMAIL` = '".$email."'";
    $query2 = $dbconn->prepare($sql2);
    $query2->execute();
    $checkEmail = $query2->get_result()->fetch_assoc();

    if (!is_null($checkEmail)){
        echo("Alamat Email sudah terdaftar pada KTA yang berbeda.");
        http_response_code(409);
        exit();
    }

    if ($uploadOk == 0) {
        echo("File anda belum sesuai.");
        http_response_code(400);
    }
    else {
        $start_upload = true;
        // $start_upload = move_uploaded_file($_FILES["fotoSim"]["tmp_name"], $target_files["fotoSim"]);
        $start_upload = move_uploaded_file($_FILES["fotoEktp"]["tmp_name"], $target_files["fotoEktp"]);
        $start_upload = move_uploaded_file($_FILES["fotoProfile"]["tmp_name"], $target_files["fotoProfile"]);

        if ($start_upload){
            // $queryPost = "INSERT INTO KTA (F_PIN, FOTO_PROFIL, `NAME`, EKTP, FOTO_KTP, DOMISILI, FOTO_SIM, STATUS_ANGGOTA) VALUES 
            //                     ('".$f_pin."','".$pasFoto."','".$name."','".$ektp."','".$fotoEktp."','".$domisili."','".$fotoSim."',0)";

            $id_anggota = $_SESSION['ref_id'];

            if ($f_pin != "") {
                $queryPost = "INSERT INTO KTA (F_PIN, NAME, EMAIL, BIRTHPLACE, DATEBIRTH, GENDER, BLOODTYPE, NATIONALITY, HOBBY, ADDRESS, RTRW,
                                PROVINCE, CITY, DISTRICT, DISTRICT_WORD, POSTCODE, EKTP, PROFILE_IMAGE, EKTP_IMAGE, STATUS_ANGGOTA, NO_ANGGOTA, HOBBY_DESC, IS_ANDROID) VALUES ('".$f_pin."','".$name."',
                                '".$email."','".$birthplace."','".$date_birth."','".$gender_radio."','".$bloodtype."','".$nationality."','".$hobby."','".$address."','".$rt. "/" .$rw."','".$province."',
                                '".$city."','".$district."','".$district_word."','".$postcode."','".$ektp."','".$pasFoto."','".$fotoEktp."','1','".'0'.$id_anggota."','".$hobby_desc."',".$is_android.")";
            }

            else {
                $queryPost = "INSERT INTO KTA (NAME, EMAIL, BIRTHPLACE, DATEBIRTH, GENDER, BLOODTYPE, NATIONALITY, HOBBY, ADDRESS, RTRW,
                                PROVINCE, CITY, DISTRICT, DISTRICT_WORD, POSTCODE, EKTP, PROFILE_IMAGE, EKTP_IMAGE, STATUS_ANGGOTA, NO_ANGGOTA, HOBBY_DESC, IS_ANDROID) VALUES ('".$name."',
                                '".$email."','".$birthplace."','".$date_birth."','".$gender_radio."','".$bloodtype."','".$nationality."','".$hobby."','".$address."','".$rt. "/" .$rw."','".$province."',
                                '".$city."','".$district."','".$district_word."','".$postcode."','".$ektp."','".$pasFoto."','".$fotoEktp."','1','".'0'.$id_anggota."','".$hobby_desc."',".$is_android.")";
            }

            if (mysqli_query($dbconn, $queryPost)){
                echo("Berhasil");
            }else{
                echo("Data failed to add. ".$sql.mysqli_error($dbconn));
                http_response_code(400);
            }

        }else{
            echo("The file is suitable but not uploaded successfully.");
            http_response_code(500);
        }
    }
}else if ($status_anggota == 1){

    // UPGRADE KTA

    // $queryPost = "UPDATE KTA SET NAME = '$name', EMAIL = '$email', BIRTHPLACE = '$birthplace', GENDER = '$gender_radio', BLOODTYPE = '$bloodtype', NATIONALITY = '$nationality', 
    //                 HOBBY = '$hobby', ADDRESS = '$address', RTRW = '$rtrw', PROVINCE = '$province', CITY = '$city', DISTRICT = '$district', DISTRICT_WORD = '$district_word', 
    //                 POSTCODE = '$postcode', EKTP = '$ektp', PROFILE_IMAGE = '$pasFoto', EKTP_IMAGE = '$fotoEktp', '1'";

    $queryPost = "UPDATE KTA SET STATUS_ANGGOTA = '1' WHERE F_PIN = '$f_pin'";

    if (mysqli_query($dbconn, $queryPost)){
        echo("Berhasil");
    }else{
        echo("Data failed to upgraded. ".$sql.mysqli_error($dbconn));
        http_response_code(400);
    }

}
