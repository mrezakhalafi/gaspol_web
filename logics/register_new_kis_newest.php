<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

// GET USER PIN
// var_dump($_POST);
// var_dump($_FILES);
// session_start();

$f_pin = $_POST['f_pin'];

// $f_pin = "02ba89b7c7";

$unique_number = $_SESSION['ref_id'];
$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_files = array();

if($_FILES["fotoSIMC"]["size"] > 0){
    $imageFileType = strtolower(pathinfo($_FILES["fotoSIMC"]["name"],PATHINFO_EXTENSION));
    $fotoSIMC = "SIM-C-".$unique_number . ".webp";
    $target_files['fotoSIMC'] = $target_dir . $fotoSIMC;
}
if($_FILES["fotoSIMA"]["size"] > 0){
  $imageFileType = strtolower(pathinfo($_FILES["fotoSIMA"]["name"],PATHINFO_EXTENSION));
  $fotoSIMA = "SIM-A-".$unique_number . ".webp";
  $target_files['fotoSIMA'] = $target_dir . $fotoSIMA;
}
if($_FILES["fotoKK"]["size"] > 0){
    $imageFileType = strtolower(pathinfo($_FILES["fotoKK"]["name"],PATHINFO_EXTENSION));
    $fotoKK = "POTW-".$unique_number . ".webp";
    $target_files['fotoKK'] = $target_dir . $fotoKK;
}

// GET OPEN STORE FORM
$name = $_POST['name'];
$domisili = $_POST['domisili'];
$pasFoto = $_POST['fotoKta'];
$kategoriKis = $_POST['kategoriKis'];

$simA = $_POST['sim-a'];
$simC = $_POST['sim-c'];

$province = $_POST['province'];
$kk = $_POST['kk'];
$is_android = $_POST['is_android'];

$no_kta = $_POST['no_kta'];

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
}
else {
    $start_upload = true;
    if(array_key_exists("fotoSIMC",$target_files)){
       $start_upload = move_uploaded_file($_FILES["fotoSIMC"]["tmp_name"], $target_files["fotoSIMC"]);
    }
    if(array_key_exists("fotoSIMA",$target_files)){
      $start_upload = move_uploaded_file($_FILES["fotoSIMA"]["tmp_name"], $target_files["fotoSIMA"]);
   }
    if(array_key_exists("fotoKK",$target_files)){
      $start_upload = move_uploaded_file($_FILES["fotoKK"]["tmp_name"], $target_files["fotoKK"]);
    }

  if ($start_upload){
    $queryPost = "INSERT INTO KIS (F_PIN, NOMOR_KARTU, FOTO_PROFIL, `NAME`, DOMISILI, FOTO_SIM_C, FOTO_PERSETUJUAN, KATEGORI, `EXPIRY_DATE`, NO_SIM_C, FOTO_SIM_A, NO_SIM_A, NO_KK, PROVINCE, IS_ANDROID, NO_KTA) VALUES 
                        ('".$f_pin."','".'0'.$unique_number."','".$pasFoto."','".$name."','".$domisili."','".$fotoSIMC."','".$fotoKK."','".$kategoriKis."', (NOW() + INTERVAL 1 YEAR),'".$simC."','".$fotoSIMA."','".$simA."','".$kk."','".$province."',".$is_android.",'".$no_kta."')";
    if (mysqli_query($dbconn, $queryPost)){
      echo("Berhasil");
    }else{
      echo("Data failed to add. ".$sql.mysqli_error($dbconn));
    }

  }else{
    echo("The file is suitable but not uploaded successfully.");
  }
}
