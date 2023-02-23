<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

// if (isset($_SESSION['user_f_pin'])){
//     $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
    $f_pin = $_POST['f_pin'];
// }

// FOR UID

function getRandomString($n) {
    $characters = '0123456789';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

$province = $_POST['province'];

// FILE PATH DOCUMENTS

$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/documents/';
$target_files = array();

// $uploadOk = 1;
$target_direct = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_file_loc = array();

// PP IMAGE
$image = strtolower(pathinfo($_FILES["fotoProfile"]["name"],PATHINFO_EXTENSION));
$ppimage = "PPIMICLUB-".$f_pin . time() . ".webp";
$target_file_loc['fotoProfile'] = $target_direct . $ppimage;


// END OF FILE PATH DOCUMENT

// POST

$club_name = $_POST['club_name'];
$content_preference = $_POST['cc_hidden'];
$club_type = $_POST['clubtype_radio'];
$category = $_POST['clubcategory_radio'];
$club_link = $_POST['exlink'];
$description = $_POST['desc'];

$address = $_POST['address'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];


$postcode = $_POST['postcode'];

// GET POSTCODE NAME BY ID
$query = $dbconn->prepare("SELECT * FROM POSTAL_CODE WHERE POSTAL_ID = '".$postcode."'");
$query->execute();
$postcodeName = $query->get_result()->fetch_assoc();
$query->close();

$postcode = $postcodeName['POSTAL_CODE'];

$city = $_POST['city'];
$district = $_POST['district'];
$subdistrict = $_POST['subdistrict'];

// END POST

$start_upload = true;

$start_upload = move_uploaded_file($_FILES["fotoProfile"]["tmp_name"], $target_file_loc["fotoProfile"]);

if($start_upload){


    if ($f_pin != "") {
        $postquery = "INSERT INTO TKT_MASYARAKAT (F_PIN, PROFILE_IMAGE, CLUB_NAME, CONTENT_PREFERENCE, CLUB_TYPE, CLUB_CATEGORY, CLUB_LINK, CLUB_DESCRIPTION, ADDRESS, RTRW, POSTCODE, PROVINCE, CITY, DISTRICT, SUBDISTRICT) VALUES ('".$f_pin."', '".$ppimage."', '".$club_name."', '".$content_preference."', 
        '".$club_type."', '".$category."', '".$club_link."', '".$description."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."')";
    }
    else {
        $postquery = "INSERT INTO TKT_MASYARAKAT (PROFILE_IMAGE, CLUB_NAME, CONTENT_PREFERENCE, CLUB_TYPE, CLUB_CATEGORY, CLUB_LINK, CLUB_DESCRIPTION, ADDRESS, RTRW, POSTCODE, PROVINCE, CITY, DISTRICT, SUBDISTRICT) VALUES ('".$ppimage."', '".$club_name."', '".$content_preference."', 
        '".$club_type."', '".$category."', '".$club_link."', '".$description."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."')";
    }

    if (mysqli_query($dbconn, $postquery)) {
        // echo("Koneksi Database Berhasil");
        // echo($postquery)

    }
    else {
        
        echo($postquery);
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }

}else{
    echo("Upload File gagal.");
}
// END QUERY
?>