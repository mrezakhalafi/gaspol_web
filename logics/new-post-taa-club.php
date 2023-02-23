<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

// if (isset($_SESSION['user_f_pin'])){
    // $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
    $f_pin = $_POST['f_pin'];
// }

// FILE PATH DOCUMENTS

$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/documents/';
$target_files = array();

$target_direct = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_file_loc = array();

// PP IMAGE
$image = strtolower(pathinfo($_FILES["fotoProfile"]["name"],PATHINFO_EXTENSION));
$ppimage = "PPTAACLUB-".$f_pin . time() . ".webp";
$target_file_loc['fotoProfile'] = $target_direct . $ppimage;

// DOCUMENT AD / ART
$adartdocs = strtolower(pathinfo($_FILES["docAdart"]["name"],PATHINFO_EXTENSION));
$dokadart = "EAD-ART-".$f_pin . time() . "." . $adartdocs;
$target_files['docAdart'] = $target_dir . $dokadart;

// CERTIFICATE
$certificate = strtolower(pathinfo($_FILES["docCertificate"]["name"],PATHINFO_EXTENSION));
$dokcertificate = "Crtfct-".$f_pin . time() . "." . $certificate;
$target_files['docCertificate'] = $target_dir . $dokcertificate;

// ADDITIONAL DOCUMENT
$additional = strtolower(pathinfo($_FILES["docAdditional"]["name"],PATHINFO_EXTENSION));
$dokAdditional = "Add-".$f_pin . time() . "." . $additional;
$target_files['docAdditional'] = $target_dir . $dokAdditional;

// END OF FILE PATH DOCUMENT

// POST
$ass_name = $_POST['ass_name'];
$category = $_POST['category'];
$description = $_POST['desc'];

// $adart = $_POST['docAdart'];
// $certificate = $_POST['docCertificate'];
// $additional = $_POST['docAdditional'];

$address = $_POST['address'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];
$postcode = $_POST['postcode'];
$province = $_POST['province'];
$city = $_POST['city'];
$district = $_POST['district'];
$subdistrict = $_POST['subdistrict'];

$bank = $_POST['bank-category'];
$acc_number = $_POST['acc-number'];
$acc_name = $_POST['acc-name'];

$president = $_POST['president'];
$secretary = $_POST['secretary'];
$clubadmin = $_POST['club-admin'];
$finance = $_POST['finance'];
$vicepresident = $_POST['vice-president'];
$hrd = $_POST['human-resource'];

$f_club = null;
$s_club = 0;
$t_club = 0;
$fth_club = 0;
$fvth_club = 0;

// $f_club = $_POST['club-category-1'];
// $s_club = $_POST['club-category-2'];
// $t_club = $_POST['club-category-3'];
// $fth_club = $_POST['club-category-4'];
// $fvth_club = $_POST['club-category-5'];

// COUNT MANY CLUB FROM FORM AND THEN LOOP INTO |

$count_club = $_POST['count_club'];

for($i=1; $i<($count_club+1); $i++){

    if($f_club == null){
        $f_club = $_POST['club-category-'.$i];
    }else{
        $f_club .= "|".$_POST['club-category-'.$i];
    }

}

// END POST

// TURN ON WHILE ACC

// if(isset($_POST['president_phone'])){
//     $president_phone = $_POST['president_phone'];
// }else{
//     $president_phone = "";
// }

// if(isset($_POST['president_ktp'])){
//     $president_ktp = $_POST['president_ktp'];
// }else{
//     $president_ktp = "";
// }

// if(isset($_POST['president_kta'])){
//     $president_kta = $_POST['president_kta'];
// }else{
//     $president_kta = "";
// }

// if(isset($_POST['secretary_phone'])){
//     $secretary_phone = $_POST['secretary_phone'];
// }else{
//     $secretary_phone = "";
// }

// if(isset($_POST['secretary_ktp'])){
//     $secretary_ktp = $_POST['secretary_ktp'];
// }else{
//     $secretary_ktp = "";
// }

// if(isset($_POST['secretary_kta'])){
//     $secretary_kta = $_POST['secretary_kta'];
// }else{
//     $secretary_kta = "";
// }

// if(isset($_POST['admin_phone'])){
//     $admin_phone = $_POST['admin_phone'];
// }else{
//     $admin_phone = "";
// }

// if(isset($_POST['admin_ktp'])){
//     $admin_ktp = $_POST['admin_ktp'];
// }else{
//     $admin_ktp = "";
// }

// if(isset($_POST['admin_kta'])){
//     $admin_kta = $_POST['admin_kta'];
// }else{
//     $admin_kta = "";
// }

// if(isset($_POST['finance_phone'])){
//     $finance_phone = $_POST['finance_phone'];
// }else{
//     $finance_phone = "";
// }

// if(isset($_POST['finance_ktp'])){
//     $finance_ktp = $_POST['finance_ktp'];
// }else{
//     $finance_ktp = "";
// }

// if(isset($_POST['finance_kta'])){
//     $finance_kta = $_POST['finance_kta'];
// }else{
//     $finance_kta = "";
// }

// if(isset($_POST['vice_president_phone'])){
//     $vice_president_phone = $_POST['vice_president_phone'];
// }else{
//     $vice_president_phone = "";
// }

// if(isset($_POST['vice_president_ktp'])){
//     $vice_president_ktp = $_POST['vice_president_ktp'];
// }else{
//     $vice_president_ktp = "";
// }

// if(isset($_POST['vice_president_kta'])){
//     $vice_president_kta = $_POST['vice_president_kta'];
// }else{
//     $vice_president_kta = "";
// }

// if(isset($_POST['human_resource_phone'])){
//     $human_resource_phone = $_POST['human_resource_phone'];
// }else{
//     $human_resource_phone = "";
// }

// if(isset($_POST['human_resource_ktp'])){
//     $human_resource_ktp = $_POST['human_resource_ktp'];
// }else{
//     $human_resource_ktp = "";
// }

// if(isset($_POST['human_resource_kta'])){
//     $human_resource_kta = $_POST['human_resource_kta'];
// }else{
//     $human_resource_kta = "";
// }

$president_phone = $_POST['president_phone'];
$president_ktp = $_POST['president_ktp'];
$president_kta = $_POST['president_kta'];

$secretary_phone = $_POST['secretary_phone'];
$secretary_ktp = $_POST['secretary_ktp'];
$secretary_kta = $_POST['secretary_kta'];

$admin_phone = $_POST['admin_phone'];
$admin_ktp = $_POST['admin_ktp'];
$admin_kta = $_POST['admin_kta'];

$finance_phone = $_POST['finance_phone'];
$finance_ktp = $_POST['finance_ktp'];
$finance_kta = $_POST['finance_kta'];

$vice_president_phone = $_POST['vice_president_phone'];
$vice_president_ktp = $_POST['vice_president_ktp'];
$vice_president_kta = $_POST['vice_president_kta'];

$human_resource_phone = $_POST['human_resource_phone'];
$human_resource_ktp = $_POST['human_resource_ktp'];
$human_resource_kta = $_POST['human_resource_kta'];
$is_android = $_POST['is_android'];

$start_upload = true;

$start_upload = move_uploaded_file($_FILES["fotoProfile"]["tmp_name"], $target_file_loc["fotoProfile"]);
$start_upload = move_uploaded_file($_FILES["docAdart"]["tmp_name"], $target_files["docAdart"]);

if($_FILES["docCertificate"]["tmp_name"]){
    $start_upload = move_uploaded_file($_FILES["docCertificate"]["tmp_name"], $target_files["docCertificate"]);
}else{
    $dokcertificate = "";
}

if($_FILES["docAdditional"]["tmp_name"]){
    $start_upload = move_uploaded_file($_FILES["docAdditional"]["tmp_name"], $target_files["docAdditional"]);
}else{
    $dokAdditional = "";
}

if($start_upload){
    // POST QUERY
    // $postquery = "INSERT INTO TAA (F_PIN, PROFILE_IMAGE, ASS_NAME, ASS_CATEGORY, ASS_DESC, DOCUMENT, CERTIFICATE, ADD_DOCS, ADDRESS, RTRW, POST_CODE, PROVINCE, CITY, DISTRICT, SUBDISTRICT, BANK, ACC_NUMBER, ACC_NAME, PRESIDENT, SECRETARY, CLUB_ADMIN, FINANCE, VICE_PRESIDENT, HUMAN_RESOURCE, CLUB_1, CLUB_2, CLUB_3, CLUB_4, CLUB_5) VALUES ('".$f_pin."', '".$ppimage."', '".$ass_name."', '".$category."', '".$description."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."', '".$secretary."', '".$clubadmin."', '".$finance."', '".$vicepresident."', '".$hrd."', '".$f_club."', '".$s_club."', '".$t_club."', '".$fth_club."', '".$fvth_club."')";

    if ($f_pin != "") {
        $postquery = "INSERT INTO TAA (F_PIN, PROFILE_IMAGE, ASS_NAME, ASS_CATEGORY, ASS_DESC, DOCUMENT, CERTIFICATE, ADD_DOCS, ADDRESS, RTRW, POST_CODE, PROVINCE, CITY, 
        DISTRICT, SUBDISTRICT, BANK, ACC_NUMBER, ACC_NAME, PRESIDENT, SECRETARY, CLUB_ADMIN, FINANCE, VICE_PRESIDENT, HUMAN_RESOURCE, CLUB_1, CLUB_2, CLUB_3, CLUB_4, CLUB_5,
        PRESIDENT_PHONE, PRESIDENT_KTP, PRESIDENT_KTA, SECRETARY_PHONE, SECRETARY_KTP, SECRETARY_KTA, ADMIN_PHONE, ADMIN_KTP, ADMIN_KTA, FINANCE_PHONE, FINANCE_KTP, 
        FINANCE_KTA, VICE_PRESIDENT_PHONE, VICE_PRESIDENT_KTP, VICE_PRESIDENT_KTA, HUMAN_RESOURCE_PHONE, HUMAN_RESOURCE_KTP, HUMAN_RESOURCE_KTA, IS_ANDROID) VALUES ('".$f_pin."', '".$ppimage."', '".$ass_name."', '".$category."', '".$description."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."', '".$address."', 
        '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."',
        '".$secretary."', '".$clubadmin."', '".$finance."', '".$vicepresident."', '".$hrd."', '".$f_club."', '".$s_club."', '".$t_club."', '".$fth_club."', '".$fvth_club."',
        '".$president_phone."','".$president_ktp."','".$president_kta."','".$secretary_phone."','".$secretary_ktp."','".$secretary_kta."','".$admin_phone."','".$admin_ktp."','".$admin_kta."',
        '".$finance_phone."','".$finance_ktp."','".$finance_kta."','".$vice_president_phone."','".$vice_president_ktp."','".$vice_president_kta."','".$human_resource_phone."','".$human_resource_ktp."','".$human_resource_kta."',".$is_android.")";
    }
    else {
        $postquery = "INSERT INTO TAA (PROFILE_IMAGE, ASS_NAME, ASS_CATEGORY, ASS_DESC, DOCUMENT, CERTIFICATE, ADD_DOCS, ADDRESS, RTRW, POST_CODE, PROVINCE, CITY, 
        DISTRICT, SUBDISTRICT, BANK, ACC_NUMBER, ACC_NAME, PRESIDENT, SECRETARY, CLUB_ADMIN, FINANCE, VICE_PRESIDENT, HUMAN_RESOURCE, CLUB_1, CLUB_2, CLUB_3, CLUB_4, CLUB_5,
        PRESIDENT_PHONE, PRESIDENT_KTP, PRESIDENT_KTA, SECRETARY_PHONE, SECRETARY_KTP, SECRETARY_KTA, ADMIN_PHONE, ADMIN_KTP, ADMIN_KTA, FINANCE_PHONE, FINANCE_KTP, 
        FINANCE_KTA, VICE_PRESIDENT_PHONE, VICE_PRESIDENT_KTP, VICE_PRESIDENT_KTA, HUMAN_RESOURCE_PHONE, HUMAN_RESOURCE_KTP, HUMAN_RESOURCE_KTA, IS_ANDROID) VALUES ('".$ppimage."', '".$ass_name."', '".$category."', '".$description."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."', '".$address."', 
        '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."',
        '".$secretary."', '".$clubadmin."', '".$finance."', '".$vicepresident."', '".$hrd."', '".$f_club."', '".$s_club."', '".$t_club."', '".$fth_club."', '".$fvth_club."',
        '".$president_phone."','".$president_ktp."','".$president_kta."','".$secretary_phone."','".$secretary_ktp."','".$secretary_kta."','".$admin_phone."','".$admin_ktp."','".$admin_kta."',
        '".$finance_phone."','".$finance_ktp."','".$finance_kta."','".$vice_president_phone."','".$vice_president_ktp."','".$vice_president_kta."','".$human_resource_phone."','".$human_resource_ktp."','".$human_resource_kta."',".$is_android.")";
    }

    if (mysqli_query($dbconn, $postquery)) {
        echo("Koneksi Database Berhasil");
    }
    else {

        echo("Koneksi Database Gagal");
        http_response_code(400);
    }

}else{
    echo("File upload gagal.");
}
// END QUERY
?>