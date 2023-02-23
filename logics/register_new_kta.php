<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN
// var_dump($_POST);
// var_dump($_FILES);
session_start();
if(isset($_SESSION['user_f_pin'])){
  $f_pin = $_SESSION['user_f_pin'];
}
else if(isset($_POST['f_pin'])){
  $f_pin = $_POST['f_pin'];
}

// $f_pin = "02ba89b7c7";
$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_files = array();
if($_FILES["fotoSim"]["size"] > 0){
    $imageFileType = strtolower(pathinfo($_FILES["fotoSim"]["name"],PATHINFO_EXTENSION));
    $fotoSim = "SIM-".$f_pin . time() . "." . $imageFileType;
    $target_files['fotoSim'] = $target_dir . $fotoSim;
}
$imageFileType = strtolower(pathinfo($_FILES["fotoEktp"]["name"],PATHINFO_EXTENSION));
$fotoEktp = "EKTP-".$f_pin . time() . "." . $imageFileType;
$target_files['fotoEktp'] = $target_dir . $fotoEktp;

$imageFileType = strtolower(pathinfo($_FILES["fotoProfil"]["name"],PATHINFO_EXTENSION));
$pasFoto = "FP-".$f_pin . time() . "." . $imageFileType;
$target_files['fotoProfil'] = $target_dir . $pasFoto;

// GET OPEN STORE FORM
$ektp = $_POST['ektp'];
$name = $_POST['name'];
$domisili = $_POST['domisili'];

$sql = "SELECT * FROM `KTA` WHERE `EKTP` = '".$uid."'";
$query = $dbconn->prepare($sql);
$query->execute();
$sql_uid = $query->get_result()->fetch_assoc();
if(!is_null($sql_uid)){
  echo("EKTP sudah terdaftar di KTA yang berbeda.");
  http_response_code(409);
  exit();
}

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
  http_response_code(400);
}
else {
    $start_upload = true;
    $start_upload = move_uploaded_file($_FILES["fotoSim"]["tmp_name"], $target_files["fotoSim"]);
    $start_upload = move_uploaded_file($_FILES["fotoEktp"]["tmp_name"], $target_files["fotoEktp"]);
    $start_upload = move_uploaded_file($_FILES["fotoProfil"]["tmp_name"], $target_files["fotoProfil"]);

  if ($start_upload){
    $queryPost = "INSERT INTO KTA (F_PIN, FOTO_PROFIL, `NAME`, EKTP, FOTO_KTP, DOMISILI, FOTO_SIM, STATUS_ANGGOTA) VALUES 
                        ('".$f_pin."','".$pasFoto."','".$name."','".$ektp."','".$fotoEktp."','".$domisili."','".$fotoSim."',0)";
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
