<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN

session_start();

if (!isset($f_pin) || $f_pin == null) {
  $f_pin = $_POST['f_pin'];
}

// GET OPEN STORE FORM

$caption = $_POST['caption'];
$title = $_POST['title'];
// $time = $_POST['time'];
$time = floor(microtime(true) * 1000);
$post_link = $_POST['post_link'];
$category = $_POST['category'];

// GET NEW LISTING FORM

$old_price = $_POST['price'];
$price = preg_replace('/[^0-9]/', '', $old_price);  


$old_stock = $_POST['stock'];
$stock = preg_replace('/[^0-9]/', '', $old_stock);  

// SEPARATE

$post_check = $_POST['post_check'];
$club_id = $_POST['club_id'];


// get score vars
$query = $dbconn->prepare("SELECT * FROM POST_SCORE_PARAMETER");
$query->execute();
$score_var = $query->get_result();
$query->close();

$score = array();

while($sc = $score_var->fetch_assoc()) {
  $score[$sc["PARAM"]] = $sc["VALUE"];
}

$starting_score = $score["TEMP"] + $time;

// SET IMAGE DIRECTORY

// $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
// $imageFileType = strtolower(pathinfo($_FILES["post_photo"]["name"],PATHINFO_EXTENSION));
// $uploadOk = 1;
// $target_file = $target_dir . $filename . "." . $imageFileType;

// $uploadThumbnail = true;

// if (isset($_FILES['thumbnail'])) {
//   $thumbnailFileType = strtolower(pathinfo($_FILES["thumbnail"]["name"],PATHINFO_EXTENSION));
//   $thumbnailTarget = $target_dir . $filename . ".webp";
//   $uploadThumbnail = move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnailTarget);
// }

// // CHECK IF REAL IMAGE

// if (isset($_POST["submit"])){
//   $check = getimagesize($_FILES["post_photo"]["tmp_name"]);
//   if ($check !== false){
//     echo "File is an image - " . $check["mime"] . ".";
//     $uploadOk = 1;
//   } else {
//     echo "File is not an image.";
//     $uploadOk = 0;
//   }
// }

// // CHECK IF IMAGE EXIST

// if (file_exists($target_file)) {
//   echo "Sorry, file already exists.";
//   $uploadOk = 0;
// }

// // CHECK IMAGE SIZE

// if ($_FILES["post_photo"]["size"] > 33554432) {
//   echo "Your file size is too large.";
//   $uploadOk = 0;
// }

// // CHECK IMAGE FORMAT

// if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//   && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"){
//   echo "Only JPG, JPEG, PNG & GIF photo formats and MP4 video formats are allowed. Now :";
//   $uploadOk = 0;
// }

// CHECK IMAGE VALIDATION

// $thumb_id = $f_pin . time() . "." . $imageFileType;

$array_upload_photo = $_POST['array_upload_photo'];
$number = 1;
$listing_thumbnail = "";

$filename = $f_pin . time();

// START BIG FOR IMAGE

$new_array_upload_photo = explode(',', $array_upload_photo);

for ($number=0; $number<count($new_array_upload_photo); $number++){

  // SET IMAGE DIRECTORY

  $array_loop = $new_array_upload_photo[$number];

  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
  $imageFileType = strtolower(pathinfo($_FILES["listing_thumbnail-$array_loop"]["name"],PATHINFO_EXTENSION));
  $target_file = $target_dir . $filename . "_" . $array_loop . "." . $imageFileType;
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

  if ($_FILES["listing_thumbnail-$array_loop"]["size"] > 32000000){
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
    $listing_thumbnail .= "|" . $ip_address . $filename . "_" . $array_loop . "." . $imageFileType;
  }else{
    $listing_thumbnail .=  $ip_address . $filename . "_" . $array_loop . "." . $imageFileType; 
  }

// END BIG FOR IMAGE

}

if (isset($post_link) && $post_link != "" && substr($post_link,0,4)!="http") {
  $post_link = "https://" . $post_link;
}

// if ($uploadOk == 0) {
//   echo("File anda belum sesuai.");
// }else{

  if ($allImagesUploaded==1){

    // INSERT INTO POST

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
  

    $bytes = random_bytes(8);
    $hexbytes = strtoupper(bin2hex($bytes));
    // $notif_id = substr($hexbytes, 0, 15);
    $notif_id = $f_pin . $time;

    // FOR FILE TYPE

    if ($imageFileType == "mp4"){
      $file_type = 2;
    }else{
      $file_type = 1;
    }

    // $title = substr($caption,0,32);

    // INSERT POST ONLY (QUERY 1)

    if ($post_check == 0){

      $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
                    PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, SCORE, LINK) VALUES 
                    ('".$notif_id."','".$f_pin."','".$title."','".$caption."','2','".
                    $time."','3','".$file_type."','','".$listing_thumbnail."',
                    '".$time."',$starting_score,'".$post_link."')";

      $queryCategory = "INSERT INTO CONTENT_CATEGORY(`POST_ID`, `CATEGORY`) VALUES ('$notif_id', $category)";

      

      if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryCategory)){
        // echo("Berhasil");
        if ($club_id != 0) {
          $queryShareTo = "INSERT INTO POST_SHARE(`F_PIN`, `POST_ID`, `CLUB_TYPE`, `CLUB_ID`) VALUES ('$f_pin', '$notif_id', 1, $club_id)";
          if (mysqli_query($dbconn, $queryShareTo)) {
            echo("Berhasil");
          }else{
            echo("Data failed to add. $sql. " . mysqli_error($dbconn));
          }
        } else {
          echo("Berhasil");
        }
      }else{
        echo("Data failed to add. $sql. " . mysqli_error($dbconn));
      }
    
    }else if($post_check == 1){

      // INSERT POST AND PRODUCT (QUERY 2)

      $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
                    PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, SCORE, LINK) VALUES 
                    ('".$notif_id."','".$f_pin."','".$title."','".$caption."','2','".
                    $time."','3','".$file_type."','','".$listing_thumbnail."',
                    '".$time."',$starting_score,'".$post_link."')";

      $queryCategory = "INSERT INTO CONTENT_CATEGORY(`POST_ID`, `CATEGORY`) VALUES ('$notif_id', $category)";

      $queryProduct =  "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
                        DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
                        REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('".$notif_id."','0','".$title."','".$time."',
                        '".$f_pin."','".$caption."','".$listing_thumbnail."','0','0',
                        '0','".$price."','1','".$file_type."','0','".$stock."','Regular','".$post_check."')";

      if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct) && mysqli_query($dbconn, $queryCategory)){
        echo("Berhasil");
      }else{
        echo("Data failed to add. $sql. " . mysqli_error($dbconn));
      }

    }

  }else{
    echo("The file is suitable but not uploaded successfully.".$_FILES["post_photo"]["error"]);
  }
// }

?>