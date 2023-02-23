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

// GET FROM POST FORM

$caption = $_POST['caption'];
$title = $_POST['title'];
$time = $_POST['time'];
$post_link = $_POST['post_link'];
$post_id = $_POST['post_id'];

// GET FROM PRODUCT FORM

$old_price = $_POST['price'];
$price = preg_replace('/[^0-9]/', '', $old_price);

$old_quantity = $_POST['stock'];
$quantity = preg_replace('/[^0-9]/', '', $old_quantity);

// SEPARATE

$post_check = $_POST['post_check'];
$old_thumb_id = $_POST['old_thumb_id'];
$array_changed_photo = $_POST['array_changed_photo'];
$deleted_thumb_id = $_POST['deleted_thumb_id'];
// $title = substr($caption,0,32);

// FOR OLD COPY FROM POST TO PRODUCT

$query = $dbconn->prepare("SELECT * FROM POST WHERE POST_ID = '$post_id'");
$query->execute();
$edit_data = $query->get_result()->fetch_assoc();
$query->close();

// CHECK IS THIS NEW PRODUCT OR EDITING PRODUCT

$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE CODE = '$post_id'");
$query->execute();
$edit_data_product = $query->get_result()->fetch_assoc();
$query->close();

if (isset($post_link) && $post_link != "" && substr($post_link, 0, 4) != "http") {
    $post_link = "https://" . $post_link;
}

// SET IMAGE DIRECTORY

$filename = $f_pin .time();

// $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
// $imageFileType = strtolower(pathinfo($_FILES["post_photo"]["name"], PATHINFO_EXTENSION));
// $uploadOk = 1;
// $target_file = $target_dir . $filename . "." . $imageFileType;

// $uploadThumbnail = true;

// if (isset($_FILES['thumbnail'])) {
//     $thumbnailFileType = strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION));
//     $thumbnailTarget = $target_dir . $filename . ".webp";
//     $uploadThumbnail = move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnailTarget);
// }

// CHECK IF REAL IMAGE

// if (isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["post_photo"]["tmp_name"]);
//     if ($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }

// CHECK IF IMAGE EXIST
if ($array_changed_photo != "") {

    // CHANGED PHOTO

    $number = 0;
    $listing_thumbnail = "";

    // START BIG FOR IMAGE

    $new_array_changed_photo = explode(',', $array_changed_photo);

    // COUNT AS CHANGED SLOT NUMBER

    for ($number = 0; $number < count($new_array_changed_photo); $number++) {

        // SET IMAGE DIRECTORY

        $array_loop = $new_array_changed_photo[$number];

        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
        $imageFileType = strtolower(pathinfo($_FILES["listing_thumbnail-$array_loop"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $filename . "_" . $array_loop . "." . $imageFileType;
        $uploadOk = 1;

        // CHECK IF REAL IMAGE

        if (isset($_POST["submit"])) {

            $check = getimagesize($_FILES["listing_thumbnail-$array_loop"]["tmp_name"]);

            if ($check !== false) {
                echo ("File is an image - " . $check["mime"] . ".");
                $uploadOk = 1;
            } else {
                echo ("File is not an image.");
                $uploadOk = 0;
            }
        }

        // CHECK IF IMAGE EXIST

        if (file_exists($target_file)) {
            echo ("Sorry, file already exists.");
            $uploadOk = 0;
        }

        // CHECK IMAGE SIZE

        if ($_FILES["listing_thumbnail-$array_loop"]["size"] > 5000000) {
            echo ("Your file size is too large.");
            $uploadOk = 0;
        }

        // CHECK IMAGE FORMAT

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"
        ) {
            echo ("Only JPG, JPEG, PNG, WEBP & GIF format photos and MP4 video formats are allowed. Now : " . $_FILES["listing_thumbnail-$array_loop"]);

            $uploadOk = 0;
        }

        // CHECK IMAGE VALIDATION AND UPLOAD IT

        if ($uploadOk == 0) {
            echo ("Your file doesn't match.");
        } else {

            if (move_uploaded_file($_FILES["listing_thumbnail-$array_loop"]["tmp_name"], $target_file)) {

                $allImagesUploaded = 1;
            } else {
                echo ("File match but can't upload." . $_FILES["listing_thumbnail-$array_loop"]["error"]);
            }
        }

        // DATABASE THUMB_ID FILLED WITH OLD THUMB_ID AND NEW NAME

        $new_listing_thumbnail = $old_thumb_id . "|" . $ip_address . $filename . "_" . $array_loop . "." . $imageFileType;
        $old_thumb_id = $new_listing_thumbnail;

        // END BIG FOR IMAGE

    }

    // IF USER UPLOAD NEW IMAGE AND DELETE IMAGE TOO

    if ($deleted_thumb_id != null) {

        $new_listing_thumbnail = str_replace($deleted_thumb_id . "|", "", $old_thumb_id);
        $new_listing_thumbnail = str_replace("|" . $deleted_thumb_id, "", $old_thumb_id);
        // $new_listing_thumbnail = str_replace($deleted_thumb_id."|","",$new_listing_thumbnail);

        // IF NOTHING CHANGES BECAUSE RANDOM DELETE (EX = 1 AND 4)

        if ($new_listing_thumbnail == $old_thumb_id) {

            $delete_explode = explode('|', $deleted_thumb_id);

            foreach ($delete_explode as $explode) {

                $new_listing_thumbnail = str_replace($explode, "", $old_thumb_id);
                $old_thumb_id = $new_listing_thumbnail;
            }
        }

        // IF THERE IS | IN CENTER

        while (strpos($new_listing_thumbnail, '||') !== false) {
            $new_listing_thumbnail = str_replace("||", "|", $new_listing_thumbnail);
        }

        // IF THERE IS | IN BEGINNING

        while ($new_listing_thumbnail[0] == "|") {
            $new_listing_thumbnail = substr($new_listing_thumbnail, 1);
        }

        // IF THERE IS | IN LAST

        while (substr($new_listing_thumbnail, -1) == "|") {
            $new_listing_thumbnail = substr($new_listing_thumbnail, 0, -1);
        }
    }

    // DELETE IMAGE FROM DATABASE

    $delete_explode = explode('|', $deleted_thumb_id);
    chmod($target_dir . $explode, 777);

    foreach ($delete_explode as $explode) {

        unlink($target_dir . $explode);
        // print_r($target_dir . $explode);

    }

    // AFTER ALL SUITABLE THEN UPLOAD FILE AND UPDATE PRODUCT & PRODUCT SHIPMENT DETAIL

    if ($allImagesUploaded == 1) {

        // EDIT POST ONLY WITH IMAGE (QUERY 5)

        if (!$edit_data_product && $post_check == 0) {

            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

            if (mysqli_query($dbconn, $queryPost)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }

            // EDIT POST AND PRODUCT WITH IMAGE (QUERY 6)

        } else if ($edit_data_product && $post_check == 1) {

            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }

            // EDIT POST AND NEW PRODUCT WITH IMAGE (QUERY 7)

        } else if (!$edit_data_product && $post_check == 1) {

            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

            $queryProduct =     "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
                                    DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
                                    REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('" . $post_id . "','0','" . $title . "','" . (time() * 1000) . "',
                                    '" . $f_pin . "','" . $caption . "','" . $new_listing_thumbnail . "','0','0',
                                    '0','" . $price . "','1','" . $file_type . "','0','" . $quantity . "','Regular','" . $post_check . "')";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }

            // EDIT POST AND DELETE PRODUCT WITH IMAGE (QUERY 8)

        } else if ($edit_data_product && $post_check == 0) {

            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET IS_DELETED = '1' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    } else {
        echo ("The file is suitable but not uploaded successfully." . $_FILES["post_photo"]["error"]);
    }
} else if ($deleted_thumb_id != null) {

    $new_listing_deleted = str_replace($deleted_thumb_id . "|", "", $old_thumb_id);
    $new_listing_deleted = str_replace("|" . $deleted_thumb_id, "", $old_thumb_id);
    // $new_listing_deleted = str_replace($deleted_thumb_id."|","",$new_listing_deleted);

    // IF NOTHING CHANGES BECAUSE RANDOM DELETE (EX = 1 AND 4)

    if ($new_listing_deleted == $old_thumb_id) {

        $delete_explode = explode('|', $deleted_thumb_id);

        foreach ($delete_explode as $explode) {

            $new_listing_deleted = str_replace($explode, "", $old_thumb_id);
            $old_thumb_id = $new_listing_deleted;
        }
    }

    // IF THERE IS | IN CENTER

    while (strpos($new_listing_deleted, '||') !== false) {
        $new_listing_deleted = str_replace("||", "|", $new_listing_deleted);
    }

    // IF THERE IS | IN BEGINNING

    while ($new_listing_deleted[0] == "|") {
        $new_listing_deleted = substr($new_listing_deleted, 1);
    }

    // IF THERE IS | IN LAST

    while (substr($new_listing_deleted, -1) == "|") {
        $new_listing_deleted = substr($new_listing_deleted, 0, -1);
    }

    // DELETE IMAGE FROM DATABASE

    $delete_explode = explode('|', $deleted_thumb_id);
    chmod($target_dir . $explode, 777);

    foreach ($delete_explode as $explode) {

        unlink($target_dir . $explode);
        // print_r($target_dir . $explode);

    }

    // AFTER COMPLETE UPDATE DATABASE

    $query = "UPDATE POST SET FILE_ID = '$new_listing_deleted', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

    // $query = "UPDATE PRODUCT SET NAME = '$product_title', SHOP_CODE = '$id_shop', DESCRIPTION =
    //               '$product_description', THUMB_ID = '$new_listing_deleted', CATEGORY = '$category', 
    //               PRICE = '$price', QUANTITY = '$stock', VARIATION = '$variation', IS_POST = '$post_check' 
    //               WHERE CODE = '" . $id_product . "'";

    // $queryDetails = "UPDATE PRODUCT_SHIPMENT_DETAIL SET WEIGHT = '$weight' WHERE PRODUCT_CODE = '" . $id_product . "'";

    // if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $queryDetails)) {
    if (mysqli_query($dbconn, $query)) {
        echo 'Berhasil';
    } else {
        echo ("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
    }
} else {

    // EDIT POST ONLY WITHOUT IMAGE (QUERY 1)

    if (!$edit_data_product && $post_check == 0) {

        $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

        if (mysqli_query($dbconn, $queryPost)) {
            echo ("Berhasil");
        } else {
            echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }

        // EDIT POST AND PRODUCT WITHOUT IMAGE (QUERY 2)

    } else if ($edit_data_product && $post_check == 1) {

        $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

        $queryProduct = "UPDATE PRODUCT SET NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0' WHERE CODE = '$post_id'";

        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
            echo ("Berhasil");
        } else {
            echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }

        // EDIT POST AND INSERT PRODUCT WITHOUT IMAGE (QUERY 3)
    } else if (!$edit_data_product && $post_check == 1) {

        $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

        $queryProduct =     "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
                            DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
                            REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('" . $post_id . "','0','" . $title . "','" . (time() * 1000) . "',
                            '" . $f_pin . "','" . $caption . "','" . $edit_data['FILE_ID'] . "','0','0',
                            '0','" . $price . "','1','" . $edit_data['FILE_TYPE'] . "','0','" . $quantity . "','Regular','" . $post_check . "')";

        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
            echo ("Berhasil");
        } else {
            echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }

        // EDIT POST AND DELETE PRODUCT WITHOUT IMAGE (QUERY 4)
    } else if ($edit_data_product && $post_check == 0) {

        $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time WHERE POST_ID = '$post_id'";

        $queryProduct = "UPDATE PRODUCT SET NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '1' WHERE CODE = '$post_id'";

        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
            echo ("Berhasil");
        } else {
            echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
    }
}
