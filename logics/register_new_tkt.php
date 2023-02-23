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
if(isset($_SESSION['user_f_pin'])){
  $f_pin = $_SESSION['user_f_pin'];
}
else if(isset($_POST['f_pin'])){
  $f_pin = $_POST['f_pin'];
}

function getRandomString($n) {
  $characters = '0123456789';
  $randomString = '';

  for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
  }

  return $randomString;
}

// $f_pin = "02ba89b7c7";
$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/documents/';
$target_files = array();
$aktaPPFilename = "";
$adArtFilename = "";

if($_FILES["aktaPP"]["size"] > 0){
    $aktaPPFilename = "AKTAPP-".$f_pin . time() . ".pdf";
    $target_files['aktaPP'] = $target_dir . $aktaPPFilename;
}
$adArtFilename = "ADART-".$f_pin . time() . ".pdf";
$target_files['adArt'] = $target_dir . $adArtFilename;

// GET FORM
$name = $_POST['name'];
$province = $_POST['province'];
$ketua = $_POST['ketua'];
$kategori = $_POST['kategori'];
$ketua = $_POST['ketua'];
$wakil = $_POST['wakil'];
$sekretaris = $_POST['sekretaris'];
$bendahara = $_POST['bendahara'];
$admin = $_POST['admin'];
$hrd = $_POST['hrd'];
$sql = "SELECT ID FROM `PROVINCE` WHERE `PROVINCE`.`PROVINCE` = '".$province."'";

$query = $dbconn->prepare($sql);
$query->execute();
$province_id = $query->get_result();
$province_id = $province_id->fetch_assoc()['ID'];
$query->close();
$unq = false;
do{
  $uid = str_pad($province_id, 2, "0", STR_PAD_LEFT).getRandomString(14);
  $sql = "SELECT * FROM `TKT` WHERE `CLUB_UID` = '".$uid."'";
  $query = $dbconn->prepare($sql);
  $query->execute();
  $sql_uid = $query->get_result()->fetch_assoc();
  if(is_null($sql_uid)){
    $unq = true;
  }
} while(!$unq);

$d = new DateTime();
$d->modify('+1 year');
$expire_date = $d->format('Y-m-d');

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
  http_response_code(400);
}
else {
    $start_upload = true;
    if($_FILES["aktaPP"]["size"] > 0)
      $start_upload = move_uploaded_file($_FILES["aktaPP"]["tmp_name"], $target_files["aktaPP"]);
    $start_upload = move_uploaded_file($_FILES["adArt"]["tmp_name"], $target_files["adArt"]);

  if ($start_upload){
    $queryPost = "INSERT INTO TKT (F_PIN, CLUB_NAME, PROVINCE, CLUB_CATEGORY, KETUA, WAKIL, SEKRETARIS, BENDAHARA, `ADMIN`, HRD, AD_ART, AKTA_PP, CLUB_UID, EXPIRE_DATE) VALUES 
                        ('".$f_pin."','".$name."','".$province."','".$kategori."','".$ketua."','".$wakil."','".$sekretaris."','".$bendahara."','".$admin."','".$hrd."','".$adArtFilename."','".$aktaPPFilename."','".$uid."','".$expire_date."')";
    if (mysqli_query($dbconn, $queryPost)){
      echo($uid);
    }else{
      echo("Data failed to add. ".$sql.mysqli_error($dbconn));
      http_response_code(400);
    }

  }else{
    echo("The file is suitable but not uploaded successfully.");
    http_response_code(500);
  }
}
