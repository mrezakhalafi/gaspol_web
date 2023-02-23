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
// session_start();
// if(isset($_SESSION['user_f_pin'])){
//   $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
  $f_pin = $_POST['f_pin'];
// }

$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_files = array();

$imageFileType = strtolower(pathinfo($_FILES["fotoProfile"]["name"],PATHINFO_EXTENSION));
$fotoProfile = "PROFILE-".$f_pin . time() . ".webp";
$target_files['fotoProfile'] = $target_dir . $fotoProfile;

function getRandomString($n) {
  $characters = '0123456789';
  $randomString = '';

  for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
  }

  return $randomString;
}

// GET FORM
$name = $_POST['name'];
$kategori = $_POST['kategori'];

$club_link = $_POST['club_link'];
$club_desc = $_POST['club_desc'];

$address = $_POST['address'];
$postcode = $_POST['postcode'];
$province = $_POST['province'];
$city = $_POST['city'];
$district = $_POST['district'];
$subdistrict = $_POST['subdistrict'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];

$sql = "SELECT PROV_ID FROM `PROVINCE` WHERE `PROV_ID` = '".$province."'";

$query = $dbconn->prepare($sql);
$query->execute();
$province_id = $query->get_result();
$province_id = $province_id->fetch_assoc()['PROV_ID'];
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

$start_upload = true;
$start_upload = move_uploaded_file($_FILES["fotoProfile"]["tmp_name"], $target_files["fotoProfile"]);

  if ($start_upload){
    $queryPost = "INSERT INTO TKT (F_PIN, CLUB_NAME, PROVINCE, CLUB_CATEGORY, CLUB_UID, EXPIRE_DATE, PROFILE_IMAGE, CLUB_LINK, CLUB_DESC, ADDRESS, RTRW, POSTCODE, CITY, DISTRICT, SUBDISTRICT) VALUES 
                        ('".$f_pin."','".$name."','".$province."','".$kategori."','".$uid."','".$expire_date."','".$fotoProfile."','".$club_link."','".$club_desc."','".$address."','".$rt."."/".".$rw."','".$postcode."','".$city."','".$district."','".$subdistrict."')";
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
