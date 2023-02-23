<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// CHANGE THIS IP ACCORDING TO NEEDS

// $ip_address = "http://192.168.0.56/gaspol_web/images/";
// $ip_address = "http://202.158.33.26/gaspol_web/images/";
$ip_address = "";

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];
$f_pin = $_SESSION['user_f_pin'];

// GET NEW LISTING FORM

$product_title = $_POST['product_title'];
$product_description = $_POST['product_description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
$variation = $_POST['variation'];
$weight = $_POST['weight'];
$post_check = $_POST['post_check'];
// $is_paid = $_POST['is_paid'];

// DECLARE VARIABLE FOR IMAGE LOOP

$array_upload_photo = $_POST['array_upload_photo'];
$number = 1;
$listing_thumbnail = "";

// START BIG FOR IMAGE

$new_array_upload_photo = explode(',', $array_upload_photo);

for ($number=0; $number<count($new_array_upload_photo); $number++){

  // SET IMAGE DIRECTORY

  $array_loop = $new_array_upload_photo[$number];

  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
  $imageFileType = strtolower(pathinfo($_FILES["listing_thumbnail-$array_loop"]["name"],PATHINFO_EXTENSION));
  $target_file = $target_dir . $f_pin . time() . $array_loop . "." . $imageFileType;
  $uploadOk = 1;

  // CHECK IF REAL IMAGE

  if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["listing_thumbnail-$array_loop"]["tmp_name"]);

    if ($check !== false) {
      echo("File is an image - " . $check["mime"] . ".");
      $uploadOk = 1;
    }else{
      echo("File is not an image.");
      $uploadOk = 0;
    }
  }

  // CHECK IF IMAGE EXIST

  if (file_exists($target_file)){
    echo("Sorry, file already exists.");
    $uploadOk = 0;
  }

  // CHECK IMAGE SIZE

  if ($_FILES["listing_thumbnail-$array_loop"]["size"] > 5000000){
    echo("Your file size is too large.");
    $uploadOk = 0;
  }

  // CHECK IMAGE FORMAT

  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"){
    echo "Only JPG, JPEG, PNG & GIF photo formats and MP4 video formats are allowed. Now :".$_FILES["listing_thumbnail-$array_loop"]["name"];
    
    $uploadOk = 0;
  }

  // CHECK IMAGE VALIDATION AND UPLOAD IT

  if ($uploadOk == 0) {
    echo("Your file does not match.");
  }else{

    if (move_uploaded_file($_FILES["listing_thumbnail-$array_loop"]["tmp_name"], $target_file)) {

      $allImagesUploaded = 1;

    }else{
      echo("The file is suitable but not uploaded successfully.".$_FILES["listing_thumbnail-$array_loop"]["error"]);
    }
  }
  
  // INSERT MULTIPLE FILE INTO DATABASE TEXT

  if ($listing_thumbnail!== ""){
    $listing_thumbnail .= "|" . $ip_address . $f_pin . time() . $array_loop . "." . $imageFileType;
  }else{
    $listing_thumbnail .=  $ip_address . $f_pin . time() . $array_loop . "." . $imageFileType; 
  }

// END BIG FOR IMAGE

}

if ($allImagesUploaded==1){

  // IF THERE IS | IN CENTER

  while (strpos($listing_thumbnail, '||') !== false){
    $listing_thumbnail = str_replace("||","|",$listing_thumbnail);
  }

  // IF THERE IS | IN BEGINNING

  while ($listing_thumbnail[0] == "|"){
    $listing_thumbnail = substr($listing_thumbnail, 1);
  }

  // IF THERE IS | IN LAST

  while (substr($listing_thumbnail, -1) == "|"){
    $listing_thumbnail = substr($listing_thumbnail, 0, -1);
  }

  // INSERT INTO LISTING/PRODUCT AND SHIPMENT DETAILS

  $bytes = random_bytes(8);
  $hexbytes = strtoupper(bin2hex($bytes));
  $code = substr($hexbytes, 0, 15);

  $query ="INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
            DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
            REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('".$code."','1','".$product_title."','".(time()*1000)."',
            '".$id_shop."','".$product_description."','".$listing_thumbnail."','".$category."','1',
            '1','".$price."','1','0','0','".$stock."','".$variation."','".$post_check."')";

  $queryDetails = "INSERT INTO PRODUCT_SHIPMENT_DETAIL (PRODUCT_CODE, LENGTH, WIDTH, HEIGHT, 
                    IS_FRAGILE, WEIGHT) VALUES ('".$code."','1','1','1','1','".$weight."')";

  if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $queryDetails)){
    header("Location: ../../pages/tab3-main.php?f_pin=".$f_pin);
  } else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
  }

}else{
  echo("Failed to insert database in the product & shipment query (Not File)");
}

?>