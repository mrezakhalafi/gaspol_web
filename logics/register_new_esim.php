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

$imageFileType = strtolower(pathinfo($_FILES["pasFoto"]["name"],PATHINFO_EXTENSION));
$pasFoto = "PF-".$f_pin . time() . "." . $imageFileType;
$target_files['pasFoto'] = $target_dir . $pasFoto;

$imageFileType = strtolower(pathinfo($_FILES["fotoTtd"]["name"],PATHINFO_EXTENSION));
$fotoTtd = "TTD-".$f_pin . time() . "." . $imageFileType;
$target_files['fotoTtd'] = $target_dir . $fotoTtd;

// GET OPEN STORE FORM
$simRequest = $_POST['simRequest'];
$ektp = $_POST['ektp'];
$type = $_POST['confirm_type'];
$address = $_POST['address'];
$occupation = $_POST['occupation'];
if($simRequest == "1"){
    $name = $_POST['name'];
    $placeOfBirth = $_POST['placeOfBirth'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $bloodType = $_POST['bloodType'];
    $gender = $_POST['gender'];
}
else {
    $noSim = $_POST['noSim'];
    $query = $dbconn->prepare("SELECT * FROM SIM WHERE NIK = '$noSim'");
    $query->execute();
    $res = $query->get_result()->fetch_assoc();
    $id = $res["ID"];
    $query->close();
}

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
}
else {
    $start_upload = true;
    if($simRequest == "2"){
        $start_upload = move_uploaded_file($_FILES["fotoSim"]["tmp_name"], $target_files["fotoSim"]);
    }
    $start_upload = move_uploaded_file($_FILES["fotoEktp"]["tmp_name"], $target_files["fotoEktp"]);
    $start_upload = move_uploaded_file($_FILES["fotoTtd"]["tmp_name"], $target_files["fotoTtd"]);
    $start_upload = move_uploaded_file($_FILES["pasFoto"]["tmp_name"], $target_files["pasFoto"]);

  if ($start_upload){
    if($simRequest == "1"){
        $queryPost = "INSERT INTO SIM (F_PIN, TYPE, EKTP, NAME, PLACE_OF_BIRTH, DATE_OF_BIRTH, 
                        BLOOD_TYPE, GENDER, ADDRESS, OCCUPATION, FOTO_KTP, FOTO_TTD, PAS_FOTO) VALUES 
                        ('".$f_pin."','".$type."','".$ektp."','".$name."','".$placeOfBirth."','".
                        $dateOfBirth."','".$bloodType."','".$gender."','".$address."','".$occupation."','".$fotoEktp."','".$fotoTtd."','".$pasFoto."')";
    }
    else {
        $queryPost = "UPDATE SIM SET OCCUPATION = '".$occupation."' AND EKTP = '"
        .$ektp."' AND ADDRESS = '".$address."' AND FOTO_KTP = '".$fotoEktp."' AND FOTO_SIM = '".$fotoSim.
        "' AND FOTO_TTD = '".$fotoTtd."' AND PAS_FOTO = '".$pasFoto."' WHERE ID = '".$id."'";
    }
    if (mysqli_query($dbconn, $queryPost)){
      echo("Berhasil");
    }else{
      echo("Data failed to add. ".$sql.mysqli_error($dbconn));
    }

  }else{
    echo("The file is suitable but not uploaded successfully.");
  }
}

?>