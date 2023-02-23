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
$unique_number = "".$f_pin . time();
$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_files = array();
if(!empty($_POST['fotoSimKta'])){
  $fotoSim = $_POST['fotoSimKta'];
}
else if($_FILES["fotoSim"]["size"] > 0){
    $imageFileType = strtolower(pathinfo($_FILES["fotoSim"]["name"],PATHINFO_EXTENSION));
    $fotoSim = "SIM-".$unique_number . "." . $imageFileType;
    $target_files['fotoSim'] = $target_dir . $fotoSim;
}
if($_FILES["fotoPersetujuan"]["size"] > 0){
    $imageFileType = strtolower(pathinfo($_FILES["fotoPersetujuan"]["name"],PATHINFO_EXTENSION));
    $fotoPersetujuan = "POTW-".$unique_number . "." . $imageFileType;
    $target_files['fotoPersetujuan'] = $target_dir . $fotoPersetujuan;
}

// GET OPEN STORE FORM
$name = $_POST['name'];
$domisili = $_POST['domisili'];
$pasFoto = $_POST['fotoKta'];
$kategoriKis = $_POST['kategoriKis'];

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
}
else {
    $start_upload = true;
    if(array_key_exists("fotoSim",$target_files)){
       $start_upload = move_uploaded_file($_FILES["fotoSim"]["tmp_name"], $target_files["fotoSim"]);
    }
    if(array_key_exists("fotoPersetujuan",$target_files)){
      $start_upload = move_uploaded_file($_FILES["fotoPersetujuan"]["tmp_name"], $target_files["fotoPersetujuan"]);
    }

  if ($start_upload){
    $queryPost = "INSERT INTO KIS (F_PIN, NOMOR_KARTU, FOTO_PROFIL, `NAME`, DOMISILI, FOTO_SIM, FOTO_PERSETUJUAN, KATEGORI, `EXPIRY_DATE`) VALUES 
                        ('".$f_pin."','".$unique_number."','".$pasFoto."','".$name."','".$domisili."','".$fotoSim."','".$fotoPersetujuan."','".$kategoriKis."', (NOW() + INTERVAL 1 YEAR))";
    if (mysqli_query($dbconn, $queryPost)){
      echo("Berhasil");
    }else{
      echo("Data failed to add. ".$sql.mysqli_error($dbconn));
    }

  }else{
    echo("The file is suitable but not uploaded successfully.");
  }
}
