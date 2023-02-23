<?php 

$strImagePath = $_SERVER['DOCUMENT_ROOT'] ."/gaspol_web/assets/img/tkt_template.png";
$strTextPath = $_SERVER['DOCUMENT_ROOT'] .'/gaspol_web/assets/fonts/noto-sans/';
$typeBold = $strTextPath.'NotoSans-Bold.ttf';
$typeRegular = $strTextPath.'NotoSans-Regular.ttf';
header("Content-type: image/png");
setlocale(LC_ALL, 'id_ID.utf8');

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN
// var_dump($_POST);
// var_dump($_FILES);
session_start();
if(isset($_GET['uid'])){
  $uid = $_GET['uid'];
}

$sqlData = "SELECT CLUB_UID, EXPIRE_DATE, CLUB_CATEGORY, CLUB_NAME, PROVINCE, KETUA
  FROM TKT
  WHERE CLUB_UID = '$uid'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$resDATA = $queDATA->get_result()->fetch_assoc();
$name = $resDATA["CLUB_NAME"];
$exp_date = $resDATA["EXPIRE_DATE"];
$exp_date = date("j F Y",strtotime($exp_date));
$date_create = date_format(date_modify(date_create($exp_date),"-1 year"),"j F Y");
$barcode_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . strtoupper($uid) . "&amp;size=300x300";
// var_dump($barcode_url);
// var_dump($exp_date);
// var_dump($date_create);
$province = $resDATA["PROVINCE"];
$ketua = $resDATA["KETUA"];
$kategori = $resDATA["CLUB_CATEGORY"];
$kategori = str_replace(["1","2","3","|"], ["Olahraga", "Hobi", "Penyelenggara", ", "],$kategori);
$queDATA->close();

$imgPng = imageCreateFromPng($strImagePath);
// var_dump($imgPng);
$black = imagecolorallocate($imgPng, 0x00, 0x00, 0x00);
$font_size = 62;

$y_offset = 30;

imagettftext($imgPng, $font_size, 0, 264, 628+$y_offset, $black, $typeBold, $uid);
imagettftext($imgPng, $font_size, 0, 1573, 628+$y_offset, $black, $typeRegular, $exp_date);
imagettftext($imgPng, $font_size, 0, 568, 894+$y_offset, $black, $typeBold, $name);
imagettftext($imgPng, $font_size, 0, 568, 1026+$y_offset, $black, $typeBold, $kategori);
imagettftext($imgPng, $font_size, 0, 568, 1278+$y_offset, $black, $typeBold, $province);
imagettftext($imgPng, $font_size, 0, 568, 1686+$y_offset, $black, $typeBold, $date_create);
imagettftext($imgPng, $font_size, 0, 1864, 1838+$y_offset, $black, $typeRegular, $province);
imagettftext($imgPng, $font_size, 0, 1708, 2274+$y_offset, $black, $typeBold, $ketua);
$imageQr = imageCreateFromPng($barcode_url);
imagecopyresampled($imgPng, $imageQr, 1800, 1000, 0, 0, 400, 400, 300, 300);
$imgResized = imagescale($imgPng,1000);
imagepng($imgResized);