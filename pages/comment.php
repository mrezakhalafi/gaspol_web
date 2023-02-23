<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();
$dbNewNus = newnus();

if (!isset($_GET['product_code'])) {
  die();
} else {
  $product_code = $_GET['product_code'];
}

// if (isset($_GET['is_post'])) {
//   $is_post = 0;
// } else {
//   $is_post = $_GET['is_post'];
// }

// get total comments
$sql = "SELECT COUNT(COMMENT_ID) AS CNT FROM POST_COMMENT WHERE POST_ID = '$product_code'";
$query = $dbconn->prepare($sql);
$query->execute();
$query_res = $query->get_result()->fetch_assoc();
$amt_comments = $query_res["CNT"];
$query->close();

$products_final = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_spesific_post.php');

$products_liked_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_liked_raw.php');
$products_liked = array();
foreach ($products_liked_raw as $product_liked) {
  $products_liked[] = $product_liked["PRODUCT_CODE"];
}

$stores_followed_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_followed_stores_raw.php');
$stores_followed = array();
foreach ($stores_followed_raw as $store_followed) {
  $stores_followed[] = $store_followed["L_PIN"];
}

$bookmarks_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_post_bookmarks.php');
$bookmarks = array();
foreach ($bookmarks_raw as $bookmark) {
  $bookmarks[] = $bookmark["POST_ID"];
}

// echo "<pre>";
// print_r($stores_followed);
// echo "</pre>";

// $stores_followed_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_stores_followed_raw.php');
// $stores_followed = array();
// foreach ($stores_followed_raw as $store_followed) {
//     $stores_followed[] = $store_followed["STORE_CODE"];
// }

$products_commented_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_commented_raw.php');
$products_commented = array();
foreach ($products_commented_raw as $product_commented) {
  $products_commented[] = $product_commented["PRODUCT_CODE"];
}

$purchases_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_purchases_raw.php');
$purchases = array();
foreach ($purchases_raw as $pc) {
  $purchases[] = $pc["POST_ID"];
}

$posts_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_reported_raw.php');
$posts_reported = array();
foreach ($posts_reported_raw as $post_reported) {
  $posts_reported[] = $post_reported["POST_ID"];
}

$total_comments_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_total_comments.php');

$total_likes_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_total_likes.php');

// echo 'reported by fpin';
// echo "<pre>";
// print_r($posts_reported);
// echo "</pre>";

$reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_reported_posts.php');

$blocked_users_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_blocked_users.php');
$blocked_users = array();
foreach ($blocked_users_raw as $blocked_user) {
  $blocked_users[] = $blocked_user["L_PIN"];
}

$users_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_users_reported_raw.php');
$users_reported = array();
foreach ($users_reported_raw as $user_reported) {
  $users_reported[] = $user_reported["F_PIN_REPORTED"];
}

$user_reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_reported_users.php');

$image_type_arr = array("jpg", "jpeg", "png", "webp");
$video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');
$shop_blacklist = array("17b0ae770cd", "239"); //isi manual 

// if (!(substr($shop_thumb_id, 0, 4) === "http")) {
//   $shop_thumb_id = "/gaspol_web/images/" . $shop_thumb_id;
// }

if (isset($_GET['f_pin'])) {

  $f_pin = $_GET['f_pin'];
}

$bg_url = "";
$rand_bg = rand(1, 12) . ".png";
$bg_url = "../assets/img/lbackground_" . $rand_bg;
// }


function time_elapsed_string($datetime, $full = false)
{
  // echo $datetime;
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) : 'just now';
}

$isFollowed = 0;
$isBookmarked = 0;
$created_by = $products_final[0]['CREATED_BY'];
if (in_array($created_by, $stores_followed)) {
  $isFollowed = 1;
}
if (in_array($product_code, $bookmarks)) {
  $isBookmarked = 1;
}

?>
<!doctype html>
<html lang="en">

<head>
  <title>Comment</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/css/roboto.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <!-- <link rel="stylesheet" href="../assets/css/tab1-style.css?random=<?= time(); ?>" /> -->
  <link rel="stylesheet" href="../assets/css/style-comment.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />
  <script src="../assets/js/script-data-comment.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script type="module" src="../assets/js/translate.js?random=<?= time(); ?>"></script>

  <style>
    body {
      background: white;
    }

    .cmt-reply {
      margin-left: 3rem !important;
      margin-right: 0 !important;
      margin-top: 0 !important;
      margin-bottom: 0 !important;
      width: 100%;
    }

    .product-row .timeline-image {
      padding: 5px 15px;
    }

    .timeline-image .carousel-indicators {
      margin-bottom: -2.5rem;
      margin-left: -65%;
    }

    .carousel-indicators {

      left: 48%;
      max-width: 200px;
    }

    .carousel-indicators [data-target] {
      background-color: black;
      opacity: .3;
      width: 6px;
      height: 6px;
      border-radius: 50%;
    }

    .carousel-indicators .active {

      width: 15px;
      border-radius: 5px !important;
      opacity: 1;
    }

    /* asdadasd */
  </style>
</head>

<body style="visibility:hidden">
  <div id="header-layout" class="sticky-top">
    <div id="header" class="row justify-content-between align-items-center">
      <div class="col-10">
        <!-- <i class="fas fa-arrow-left" style="color: white;" onclick="goBack()"></i> -->
        <img src="../assets/img/back_arrow.png" class="navbar-back-black" onclick="goBack()" style="height:28px; width: auto;">
        &ensp;
        <span id="header-title"><strong></strong></span>

        <script>
          // if (localStorage.lang == 0) {
          //   $('#header-title').text('Comment');
          // } else if (localStorage.lang == 1) {
          //   $('#header-title').text('Detail P');
          // }
        </script>
      </div>
      <div class="col-2">
        <a class="post-status" id="edt-del-<?= $product_code ?>" data-postid="<?= $product_code ?>" data-isfollowed="<?= $isFollowed ?>" data-isbookmarked="<?= $isBookmarked ?>" data-createdby="<?= $created_by ?>"><img src="../assets/img/3dot_vertical.png" height="25" width="25" style="background-color:unset;" /></a>
      </div>
    </div>
  </div>

  <div id="content-comment">
    <?php

    for ($i = 0; $i < count($products_final); $i++) {

      $code = $products_final[$i]["CODE"];
      $post_f_pin = $products_final[$i]["CREATED_BY"];
      // $changedProfile = $changedProfile['IS_CHANGED_PROFILE'];

      // if ($reports_arr[$code]['TOTAL_REPORTS'] >= 100 || in_array($code, $posts_reported)) {
      //   // echo 'bro';
      //   continue;
      // }
      if (in_array($post_f_pin, $blocked_users)) {
        continue;
      }

      // if ($user_reports_arr[$post_f_pin]['TOTAL_REPORTS'] >= 100 || in_array($post_f_pin, $users_reported)) {
      //   continue;
      // }

      // $name = $products_final[$i]["NAME"];
      $created_date = $products_final[$i]["CREATED_DATE"];
      $category = $products_final[$i]["CATEGORY"];
      // $classification = $products_final[$i]["CLASSIFICATION"];
      $seconds = intval(intval($created_date) / 1000);
      // // $printed_date = date("H:i", $seconds);

      $lazy = ' loading=lazy';

      // print date
      $date_diff = round((time() - $seconds) / (60 * 60 * 24));
      if ($date_diff == 0) {
        $printed_date = "Hari ini";
      } else if ($date_diff == 1) {
        $printed_date = "Kemarin";
      } else if ($date_diff == 2) {
        $printed_date = "2 hari lalu";
      } else if ($date_diff == 3) {
        $printed_date = "3 hari lalu";
      } else if ($date_diff == 4) {
        $printed_date = "4 hari lalu";
      } else if ($date_diff == 5) {
        $printed_date = "5 hari lalu";
      } else if ($date_diff == 6) {
        $printed_date = "6 hari lalu";
      } else if ($date_diff == 7) {
        $printed_date = "7 hari lalu";
      } else if ($date_diff > 7 && $date_diff < 365) {
        $printed_date = date("j M Y", $seconds);
      } else if ($date_diff >= 365) {
        $printed_date = date("j M Y", $seconds);
      }

      $store_id = $products_final[$i]["SHOP_CODE"];
      $desc = nl2br($products_final[$i]["DESCRIPTION"]);
      $thumb_id = $products_final[$i]["THUMB_ID"];
      $thumb_ids = explode("|", $thumb_id);
      $store_thumb_id = $products_final[$i]["STORE_THUMB_ID"];
      $store_name = $products_final[$i]["STORE_NAME"];
      $store_link = $products_final[$i]["STORE_LINK"];
      $total_likes = $products_final[$i]["TOTAL_LIKES"];
      $total_comment = $amt_comments;
      $is_product = $products_final[$i]["IS_PRODUCT"];
      $tagged_product = $products_final[$i]["TAGGED_PRODUCT"];
      $is_paid = $products_final[$i]["PRICING"];
      $report = $products_final[$i]["REPORT"];

      if (in_array($store_id, $shop_blacklist)) {
        continue;
      }

      $domain = 'http://108.136.138.242';

      $imgs = explode('|', $store_thumb_id);
      if (substr($imgs[0], 0, 4) !== "https") {
        if ($imgs[0] == "") {
          $thumb = "../assets/img/ic_person_boy.png";
        } else {
          // $thumb = $imgs[0];
          // $thumb = "/gaspol_web/images/" . $imgs[0];
          $thumb = "http://108.136.138.242/filepalio/image/" . $imgs[0];
        }
      } else {
        $thumb = $imgs[0];
      }

      // if ($i > 0) {
      // }
      if ($category == "4") {
        echo '<div class="product-row pt-3 pb-1" id="product-' . $code . '" data-iscontent="true" onclick="event.stopPropagation();openComment(\'' . $code . '\')">';
      } else {
        echo '<div class="product-row pt-3 pb-1" id="product-' . $code . '" onclick="event.stopPropagation();openComment(\'' . $code . '\')">';
      }
      echo '<div class="col-sm">';
      echo '<div class="timeline-post-header media">';
      echo '<a class="d-flex pr-3" href="tab3-profile.php?l_pin=' . $store_id . '&f_pin=' . $f_pin . '">';
      // if ($is_shop >= 1) {
      //     echo '<a class="d-flex pr-3" href="tab3-profile.php?store_id=' . $store_id . '&f_pin=' . $f_pin . '">';
      // } else {
      //     echo '<a class="d-flex pr-3" href="tab3-profile-user.php?f_pin=' . $f_pin . '&store_id=' . $store_id . '">';
      // }
      echo '<img src="' . $thumb . '" class="profile-pic align-self-start rounded-circle mr-2">';
      echo '</a>';
      echo '<div class="media-body">';
      // if ($is_verified > 0) {
      //   echo '<h5 class="store-name"><img src="/gaspol_web/assets/img/icons/Verified-(Black).png"/>' . $store_name . '</h5>';
      // } else {
      echo '<h5 class="store-name">' . $store_name . '</h5>';
      // }
      echo '<p class="prod-timestamp">' . $printed_date . '</p>';
      echo '</div>';
      echo '<div class="post-status d-none">';
      echo '<img src="../assets/img/ic_public.png" height="20" width="20"/>';
      echo '</div>';
      echo '<div class="post-status d-none">';
      echo '<img src="../assets/img/ic_user.png" height="20" width="20"/>';
      echo '</div>';
      echo '<div class="dropdown dropdown-edit edit-menu-' . $post_f_pin . '">';
      $isFollowed = 0;
      $isBookmarked = 0;
      if (in_array($store_id, $stores_followed)) {
        $isFollowed = 1;
      }

      if (in_array($code, $bookmarks)) {
        $isBookmarked = 1;
      }
      echo '<ul class="dropdown-menu" aria-labelledby="edt-del-' . $code . '">';
      echo '<li><a class="dropdown-item button_edit" onclick="editPost(\'' . $code . '\')">Edit</a></li>';
      echo '<li><a class="dropdown-item button_delete" onclick="deletePost(\'' . $code . '\')">Delete</a></li>';
      echo '</ul>';
      // echo '<img src="../assets/img/icons/More.png" height="25" width="25"/>';
      echo '</div>';
      echo '</div>';
      echo '</div>';

      echo '<div class="col-sm mt-3">';
      if ($code != '16467163130000246a901c4') {
        // echo '<span class="prod-name"><img class="verified-icon-prod" src="../assets/img/icons/Verified-(Black).png">' . $store_name . '</span>&emsp;';
        if ($is_paid == 0) {
          echo '<span class="prod-desc mb-2">' . strip_tags($desc) . '</span>';
        } else {
          if (in_array($code, $purchases)) {
            echo '<span class="prod-desc mb-2">' . strip_tags($desc) . '</span>';
          } else {
            echo '<div class="row my-3">';
            echo '<div class="col-sm-12">';
            echo '<h5 class="text-center">Purchase to see content</h5>';
            echo '</div>';
            echo '</div>';
          }
        }
      } else {
        echo '<div class="row">';
        echo '<div class="col-8">';
        // echo '<span class="prod-name"><img class="verified-icon-prod" src="../assets/img/icons/Verified-(Black).png">' . $store_name . '</span>&emsp;';
        if ($is_paid == 0) {
          echo '<span class="prod-desc mb-2">' . strip_tags($desc) . '</span>';
        } else {
          if (in_array($code, $purchases)) {
            echo '<span class="prod-desc mb-2">' . strip_tags($desc) . '</span>';
          } else {
            echo '<div class="row my-3">';
            echo '<div class="col-sm-12">';
            echo '<h5 class="text-center">Purchase to see content</h5>';
            echo '</div>';
            echo '</div>';
          }
        }
        echo '</div>';
        echo '<div class="col-4 d-flex align-items-center justify-content-end">';
        echo '<a class="btn btn-dark click-membership" onclick="event.stopPropagation();goToURL(\'/gaspol_web/pages/menu_membership?f_pin=' . $f_pin . '\');" style="font-size:12px; color:white;"></a>';
        echo '</div>';
        echo '</div>';
      }
      echo '</div>';
      if ($code == '16467163130000246a901c4') {

        echo '<div class="col-sm timeline-image" onclick="event.stopPropagation();goToURL(\'/gaspol_web/pages/menu_membership?f_pin=' . $f_pin . '\');">';
      } else {
        echo '<div class="col-sm timeline-image">';
      }
      // echo '<a class="timeline-main" onclick="openStore(\'' . $store_id . '\',\'' . $store_link . '\');">';
      // if ($is_paid != 0) {
      //     echo '<a class="timeline-main" id="detail-product-' . $code . '" onclick="showAddModalPost(\'' . $code . '\');">';
      // } else {
      //     echo '<a class="timeline-main" id="detail-product-' . $code . '">';
      // }
      if (count($thumb_ids) == 1) {

        // echo '<img class="single-image img-fluid rounded" src="' . $thumb_id . '">';
        $thumb_ext = trim(pathinfo($thumb_ids[0], PATHINFO_EXTENSION));
        $image_name = str_replace($thumb_ext, "", $thumb_ids[0]);
        // echo 'ext ' .$thumb_ext;
        // if ($code == '164429392600002d4a953a0') {
        //     echo 'count thumb ' . count($thumb_ids);
        //     echo '<br>';
        //     echo $thumb_ids[0];
        // }
        if (in_array($thumb_ext, $image_type_arr)) {
          echo '<img src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[0]) . '" class="img-fluid rounded"' . $lazy . '>';
          if ($tagged_product != null) {
            echo '<div class="timeline-product-tag">';
            echo '<img src="../assets/img/icons/Tagged-Product.png" />';
            echo '</div>';
          }
        } else if (in_array($thumb_ext, $video_type_arr)) {
          echo '<div class="video-wrap" id="videowrap-' . $code . '-0">';
          echo '<video muted playsinline loop src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[0]) . '#t=0.5" id="video-' . $code . '-0" class="myvid rounded" preload="" poster="' . $image_name . 'webp">';
          // echo '<source src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[0]) . '#t=0.5" type="video/' . $thumb_ext . '">';
          echo '</video>';
          if ($tagged_product != null) {
            echo '<div class="timeline-product-tag-video">';
            echo '<img src="../assets/img/icons/Tagged-Product.png" />';
            echo '</div>';
          }
          echo '<div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute(\'videowrap-' . $code . '-0\');">';
          echo '<img src="../assets/img/video_mute.png" />';
          echo '</div>';
          // echo '<div class="video-fullscreen">';
          // echo '<img src="../assets/img/fullscreen.png" />';
          // echo '</div>';
          echo '<div class="video-play d-none">';
          echo '<img src="../assets/img/video_play.png" />';
          echo '</div></div>';
        }
      } else {
        $count_thumb_id = count($thumb_ids);
        echo '<div id="carousel-' . $code . '" class="carousel slide pointer-event" data-touch="true" data-interval="false" data-ride="carousel" data-wrap="false">';
        echo '<ol id="ci-' . $code . '" class=' . '"carousel-indicators">';
        for ($j = 0; $j < $count_thumb_id; $j++) {
          if ($j == 0) {
            echo '<li data-target="#carousel-' . $code . '" data-slide-to="' . $j . '" class="active"></li>';
          } else {
            echo '<li data-target="#carousel-' . $code . '" data-slide-to="' . $j . '"></li>';
          }
        }
        echo '</ol>';
        echo '<div class="carousel-inner">';
        for ($j = 0; $j < count($thumb_ids); $j++) {
          if ($j == 0) {
            echo '<div class="carousel-item active">';
          } else {
            echo '<div class="carousel-item">';
          }
          echo '<div class="carousel-item-wrap">';
          $thumb_ext = pathinfo($thumb_ids[$j], PATHINFO_EXTENSION);
          $image_name = str_replace($thumb_ext, "", $thumb_ids[$j]);
          if (in_array($thumb_ext, $image_type_arr)) {
            echo '<img src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[$j]) . '" class="img-fluid rounded"' . $lazy . '>';
            if ($tagged_product != null) {
              echo '<div class="timeline-product-tag">';
              echo '<img src="../assets/img/icons/Tagged-Product.png" />';
              echo '</div>';
            }
          } else if (in_array($thumb_ext, $video_type_arr)) {
            echo '<div class="video-wrap" id="videowrap-' . $code . '-' . $j . '">';
            echo '<video playsinline muted loop src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[$j]) . '#t=0.5" id="video-' . $code . '-' . $j . '" class="myvid rounded" preload="" poster="' . $image_name . 'webp">';
            // echo '<source src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[$j]) . '#t=0.5" type="video/' . $thumb_ext . '">';
            echo '</video>';
            if ($tagged_product != null) {
              echo '<div class="timeline-product-tag-video">';
              echo '<img src="../assets/img/icons/Tagged-Product.png" />';
              echo '</div>';
            }
            echo '<div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute(\'videowrap-' . $code . '-' . $j . '\');">';
            echo '<img src="../assets/img/video_mute.png" />';
            echo '</div>';
            // echo '<div class="video-fullscreen">';
            // echo '<img src="../assets/img/fullscreen.png" />';
            // echo '</div>';
            echo '<div class="video-play d-none">';
            echo '<img src="../assets/img/video_play.png" />';
            echo '</div></div>';
          }

          echo '</div></div>';
        }
        echo '</div>';
        // echo '<a class="carousel-control-prev" data-target="#carousel-' . $code . '" data-slide="prev" onclick="event.stopPropagation(); buttonTheme(\'' . $category . '\')">';
        // echo '<span class="carousel-control-prev-icon"></span>';
        // echo '</a>';
        // echo '<a class="carousel-control-next" data-target="#carousel-' . $code . '" data-slide="next" onclick="event.stopPropagation(); buttonTheme(\'' . $category . '\')">';
        // echo '<span class="carousel-control-next-icon"></span>';
        // echo '</a>';
        echo '</div>';
      }
      echo '</a>';
      echo '</div>';
      echo '<div class="col-sm like-comment-container">';
      echo '<div class="comment-button">';
      // echo '<a href="comment?product_code=' . $code . '&f_pin='.$f_pin.'">';
      echo '<a onclick="event.stopPropagation();openComment(\'' . $code . '\')">';
      if (in_array($code, $products_commented)) {
        echo '<img class="comment-icon-' . $code . '" src="../assets/img/jim_comments_blue.png?v=2" height="25" width="25"/>';
      } else {
        echo '<img class="comment-icon-' . $code . '" src="../assets/img/jim_comments.png?v=2" height="25" width="25"/>';
      }
      echo '</a>';
      echo '<div class="like-comment-counter">';
      echo $total_comment;
      echo '</div>';
      echo '</div>';
      echo '<div class="like-button" onClick="event.stopPropagation();likeProduct(\'' . $code . '\')">';
      if (in_array($code, $products_liked)) {
        echo '<img id=like-' . $code . ' src="../assets/img/jim_likes_red.png" height="25" width="25"/>';
      } else {
        echo '<img id=like-' . $code . ' src="../assets/img/jim_likes.png?v=2" height="25" width="25"/>';
      }
      echo '<div id=like-counter-' . $code . ' class="like-comment-counter">';
      echo $total_likes;
      echo '</div>';
      echo '</div>';

      // if ($post_f_pin != $f_pin) {
      //     echo '<img src="../assets/img/warning.png?v=2" style="width: 25px; height: 25px" id="dropdownMenuSelectLanguage" class="dropdownMenuSelectLanguage" data-toggle="dropdown" aria-expanded="false"></img>';
      //     echo '<ul class="dropdown-menu shadow-lg" style="min-width: auto !important; position: absolute; border: 1px solid black; z-index: 1000" aria-labelledby="dropdownMenuLanguage">';

      //     echo '<li id="report_content" onclick="reportContent(\'' . $code . '\',' . $report . ')"><a class="dropdown-item report_content_text" data-translate="tab5listing-10">Report this Content</a></li>';
      //     echo '<li id="report_user" onclick="reportUser(\'' . $post_f_pin . '\')" ><a class="dropdown-item report_user_text" data-translate="tab5listing-10">Report this User</a></li>';
      //     echo '<li id="block_user" onclick="blockUser(\'' . $post_f_pin . '\')"><button type="submit" style="color:brown" class="dropdown-item block_user_text" data-translate="tab5listing-11">Block this User</button></li>';

      //     echo '</ul>';
      // }

      // echo '<div class="follower-button" onClick="addWishlist(\'' . $code . '\',this)">';
      // if ($is_paid == 1) {
      //     if (in_array($code, $wishlists)) {
      //         echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist-fill.png" height="25" width="25"/>';
      //     } else {
      //         echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist.png" height="25" width="25"/>';
      //     }
      // }
      // echo '<div id=follow-counter-post-' . $code . ' class="d-none like-comment-counter follow-counter-store-' . $store_id . '">';
      // echo $total_follower . ' pengikut';
      // echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '<hr class="my-0 mb-0" style="background-color: #f1f1f1; height: 7px;">';
    }

    ?>
    <div class="container-fluid mt-2" id="comment-section" style="padding-bottom:105px;">
      <h5 class="px-3"><strong><span id="comment-amt"><?= $amt_comments ?></span> <span id="comment-unit"></span></strong></h5>
      <?php


      $comments = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');

      function getReplies($reffs, $sub)
      {
        ${"j" . $sub} = 0;
        foreach ($reffs as $reff) {
          ${"comment_id_reff" . $sub} = $reff["COMMENT_ID"];
          ${"f_pin_reff" . $sub} = $reff["F_PIN"];
          ${"comment_text_reff" . $sub} = $reff["COMMENT"];
          ${"created_date_reff" . $sub} = $reff["CREATED_DATE"];

          ${"seconds_reff" . $sub} = intval(intval(${"created_date_reff" . $sub}) / 1000);
          // $date_diff = round((time() - ${"seconds_reff" . $sub}) / (60 * 60 * 24));

          ${"printed_date_reff" . $sub} = time_elapsed_string('@' . strval(${"seconds_reff" . $sub}));
          // if ($date_diff == 0) {
          //   ${"printed_date_reff" . $sub} = "Hari ini";
          // } else if ($date_diff == 1) {
          //   ${"printed_date_reff" . $sub} = "Kemarin";
          // } else if ($date_diff == 2) {
          //   ${"printed_date_reff" . $sub} = "2 hari lalu";
          // } else if ($date_diff == 3) {
          //   ${"printed_date_reff" . $sub} = "3 hari lalu";
          // } else if ($date_diff == 4) {
          //   ${"printed_date_reff" . $sub} = "4 hari lalu";
          // } else if ($date_diff == 5) {
          //   ${"printed_date_reff" . $sub} = "5 hari lalu";
          // } else if ($date_diff == 6) {
          //   ${"printed_date_reff" . $sub} = "6 hari lalu";
          // } else if ($date_diff == 7) {
          //   ${"printed_date_reff" . $sub} = "7 hari lalu";
          // } else if ($date_diff > 7 && $date_diff < 365) {
          //   ${"printed_date_reff" . $sub} = date("j M Y", ${"seconds_reff" . $sub});
          // } else if ($date_diff >= 365) {
          //   ${"printed_date_reff" . $sub} = date("j M Y", ${"seconds_reff" . $sub});
          // }
          // ${"printed_date_reff" . $sub} = date("H:i", ${"seconds_reff" . $sub});
          // ${"date_explode_reff" . $sub} = explode(":", ${"printed_date_reff" . $sub});
          // ${"hours_reff" . $sub} = (int)${"date_explode_reff" . $sub}[0] + 7;
          // if (${"hours_reff" . $sub} >= 24) {
          //   ${"hours_reff" . $sub} = ${"hours_reff" . $sub} - 24;
          //   ${"hours_reff" . $sub} = "{" . ${"hours_reff" . $sub} . "}";
          //   if (strlen(${"hours_reff" . $sub}) == 1) {
          //     ${"hours_reff" . $sub} = "0" . ${"hours_reff" . $sub};
          //   }
          // }
          // ${"printed_date_reff" . $sub} = ${"hours_reff" . $sub} . ":" . ${"date_explode_reff" . $sub}[1];
          ${"parameter_reply_reff" . $sub} = "true," . "'user-name-reff-" . $sub . ${"j" . $sub} . "'," . "'" . ${'comment_id_reff' . $sub} . "'";
          ${"parameter_profile_reff" . $sub} = "'" . ${"f_pin_reff" . $sub} . "'";
          $is_delete = $reff["IS_DELETE"];

          $displayPic = $reff['IMAGE'];
          $displayName = $reff['USERNAME'];

          if ($displayPic == "") {
            $displayPic = "../assets/img/ic_person_boy.png";
          } else {
            $displayPic = "http://108.136.138.242/filepalio/image/" . $displayPic;
          }
          if ($is_delete == 0) {
            echo '<div class="row comments cmt-reply" id="' . ${'comment_id_reff' . $sub} . '">';
          } else {
            echo '<div class="row comments cmt-reply is-delete" id="' . ${'comment_id_reff' . $sub} . '">';
          }
          echo '<div class="commentId" style="display: none;">' . ${'comment_id_reff' . $sub} . '</div>';
          echo '<div class="fPin" style="display: none;">' . ${"f_pin_reff" . $sub} . '</div>';
          echo '<div class="col-2">';
          echo '<img onclick="showProfile(' . ${"parameter_profile_reff" . $sub} . ')" id="user-thumb-reff-' . $sub . ${"j" . $sub} . '" alt="Profile Photo" class="rounded-circle my-3 profpic" id="display-pic" src="' . $displayPic . '">';
          echo '</div>';
          echo '<div class="col-7 px-0 text-break">';
          if ($is_delete == 0) {
            echo '<div style="font-weight: bold; font-size:13px" class="mt-3 mb-1 mr-3"><span id="user-name-reff-' . $sub . ${"j" . $sub} . '">' . $displayName . '</span></div>';
            echo '<div style="font-weight: bold font-size:13px class="mr-3"><span style="font-weight: 300;"> ' . ${"comment_text_reff" . $sub} . '</span></div>';
            // echo '<div style="font-weight: 100; color: grey;" class="my-1">' . ${"printed_date_reff" . $sub} . '&emsp;<span class="text-replied" data-translate="comment-2" style="font-weight: 300;" onclick="onReply(' . ${"parameter_reply_reff" . $sub} . ');"></span></div>';
          } else {
            echo '<div style="font-weight: bold;" class="mt-3 mb-1 mr-3"><span style="font-weight: 300;">Comment deleted.</h4></div>';
          }
      ?>

          <script>
            if (localStorage.lang == 0) {
              $('.text-replied').text('Reply');
            } else if (localStorage.lang == 1) {
              $('.text-replied').text('Balas');
            }
          </script>

          <?php
          echo '</div>';
          echo '<div class="col-3 text-right">';
          echo '<div style="font-weight: 100; color: grey; font-size:11px;" class="mt-3 mb-1">' . ${"printed_date_reff" . $sub} . '</div>';
          echo '<div style="font-weight: 100; font-size:11px;" class="my-1"><span class="text-replied" data-translate="comment-2" style="font-weight: 300;" onclick="onReply(' . ${"parameter_reply_reff" . $sub} . ');"></span></div>';
          echo '</div>';
          echo '</div>';


          ?>
          <script>
            if (localStorage.lang == 0) {
              $('.text-replied').text('Reply');
            } else if (localStorage.lang == 1) {
              $('.text-replied').text('Balas');
            }
          </script>
        <?php

          // echo ('<script>getDisplayNameReff("' . ${"f_pin_reff" . $sub} . '","' . $sub . '","' . ${"j" . $sub} . '")</script>');
          // echo ('<script>getThumbIdReff("' . ${"f_pin_reff" . $sub} . '","' . $sub . '","' . ${"j" . $sub} . '")</script>');
          ${"reffs" . $sub} = include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');
          if (count(${"reffs" . $sub}) > 0) {
            getReplies(${"reffs" . $sub}, $sub + 1);
          }
          ${"j" . $sub}++;
        }
      }

      $i = 0;

      foreach ($comments as $comment) {
        $comment_id = $comment["COMMENT_ID"];
        // if ($is_post == 0) {
        //   $product_code = $comment["LINK_ID"];
        // } else {
        $product_code = $comment["POST_ID"];
        // }
        $f_pin = $comment["F_PIN"];
        $comment_text = $comment["COMMENT"];
        $created_date = $comment["CREATED_DATE"];
        $displayPic = $comment['IMAGE'];
        $displayName = $comment['USERNAME'];
        $is_delete = $comment['IS_DELETE'];

        if ($displayPic == "") {
          $displayPic = "../assets/img/ic_person_boy.png";
        } else {
          $displayPic = "http://108.136.138.242/filepalio/image/" . $displayPic;
        }

        $seconds = intval(intval($created_date) / 1000);
        // $date_diff = round((time() - $seconds) / (60 * 60 * 24));
        // if ($date_diff == 0) {
        //   $printed_date = "Hari ini";
        // } else if ($date_diff == 1) {
        //   $printed_date = "Kemarin";
        // } else if ($date_diff == 2) {
        //   $printed_date = "2 hari lalu";
        // } else if ($date_diff == 3) {
        //   $printed_date = "3 hari lalu";
        // } else if ($date_diff == 4) {
        //   $printed_date = "4 hari lalu";
        // } else if ($date_diff == 5) {
        //   $printed_date = "5 hari lalu";
        // } else if ($date_diff == 6) {
        //   $printed_date = "6 hari lalu";
        // } else if ($date_diff == 7) {
        //   $printed_date = "7 hari lalu";
        // } else if ($date_diff > 7 && $date_diff < 365) {
        //   $printed_date = date("j M Y", $seconds);
        // } else if ($date_diff >= 365) {
        //   $printed_date = date("j M Y", $seconds);
        // }
        $date_diff = time_elapsed_string('@' . strval($seconds));
        // $printed_date = date("H:i", $seconds);
        // $date_explode = explode(":", $printed_date);
        // $hours = (int)$date_explode[0] + 7;
        // if ($hours >= 24) {
        //   $hours = $hours - 24;
        //   $hours = "{$hours}";
        //   if (strlen($hours) == 1) {
        //     $hours = "0" . $hours;
        //   }
        // }
        // $printed_date = $hours . ":" . $date_explode[1];
        $parameter_reply = "true," . "'user-name-" . $i . "'," . "'$comment_id'";
        $parameter_profile = "'" . $f_pin . "'";
        if ($is_delete == 0) {
          echo '<div class="row mx-0 comments" id="' . $comment_id . '">';
        } else {
          echo '<div class="row mx-0 comments is-deleted" id="' . $comment_id . '">';
        }
        echo '<div class="commentId" style="display: none;">' . $comment_id . '</div>';
        echo '<div class="fPin" style="display: none;">' . $f_pin . '</div>';
        echo '<div class="col-2">';
        echo '<img onclick="showProfile(' . $parameter_profile . ')" id="user-thumb-' . $i . '" alt="Profile Photo" class="rounded-circle my-3 profpic" id="display-pic" src="' . $displayPic . '">';
        echo '</div>';

        if ($is_delete == 0) {
          echo '<div class="col-7 px-0 text-break first-comment">';
          echo '<div style="font-weight: bold; font-size:13px" class="mt-3 mb-1 mr-3"><span id="user-name-' . $i . '">' . $displayName . '</span></div>';
          echo '<div style="font-weight: 100; font-size:13px" class="my-1">' . $comment_text . '&emsp;</div>';
          // echo '<div style="font-weight: 100; color: grey;" class="my-1">' . $printed_date . '&emsp;<span class="text-replied" style="font-weight: 300;" data-translate="comment-2" onclick="onReply(' . $parameter_reply . ');"></span></div>';
        } else {
          echo '<div class="col-10 text-break first-comment is-deleted">';
          echo '<div style="font-weight: bold;" class="mt-3 mb-1 mr-3"><span style="font-weight: 300;">Comment deleted.</h4></div>';
        }
        ?>

        <script>
          if (localStorage.lang == 0) {
            $('.text-replied').text('Reply');
          } else if (localStorage.lang == 1) {
            $('.text-replied').text('Balas');
          }
        </script>

      <?php
        echo '</div>';

        echo '<div class="col-3 text-right">';
        echo '<div style="font-weight: 100; color: grey; font-size:11px;" class="mt-3 mb-1">' . $date_diff . '</div>';
        echo '<div style="font-weight: 100; font-size:11px;" class="my-1" onclick="onReply(' . $parameter_reply . ');"><span class="text-replied" style="font-weight: 300;" data-translate="comment-2" onclick="onReply(' . $parameter_reply . ');"></span></div>';
        echo '</div>';
        $reffs = include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');
        if (count($reffs) > 0) {
          getReplies($reffs, 1);
        }
        echo '</div>';

        // echo ('<script>getDisplayName("' . $f_pin . '","' . $i . '")</script>');
        // echo ('<script>getThumbId("' . $f_pin . '","' . $i . '")</script>');
        // $reffs = include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');
        // if (count($reffs) > 0) {
        //   getReplies($reffs, 1);
        // }
        $i++;
      }
      ?>
      <script>
        if (localStorage.lang == 0) {
          $('.text-replied').text('Reply');
        } else if (localStorage.lang == 1) {
          $('.text-replied').text('Balas');
        }
      </script>
    </div>

    <div class="row fixed-bottom" style="background-color: white; border-bottom: 1px solid lightgray;">
      <div style="width: 100%; height: 40px; background: #b0bec6;" class="d-none row mb-2 pt-2" id="reply-div">
        <div class="col-12" style="color: grey; font-weight: 300; padding-left: 40px;" id="content-reply">
        </div>
        <div class="col-2 text-right">
          <i class="fas fa-times" style="color: white;" onclick="onReply(false);"></i>
        </div>
      </div>
      <div class="col-12 px-4 pt-1 mb-2" style="display:flex; flex-direction:row;">
        <div class="d-flex w-100" style=" border-bottom: 1px solid lightgray">
          <input type="text" name="message" id="input" placeholder="Tulis Komentar" data-translate-placeholder="comment-3" onclick="onFocusInput()" class="border-0 px-0 py-2">
          <img class="ml-auto" id="buttond_send" src="../assets/img/send.png" onclick="commentProduct('<?php echo $product_code; ?>')">
        </div>
      </div>
      <!-- <div class="col-1 px-0 mx-0 d-flex align-items-center"> -->
      <!-- <div id="buttond_send" class="px-3 py-2" onclick="commentProduct('<?php echo $product_code; ?>')"> -->
      <!-- </div> -->
      <!-- </div> -->
    </div>

    <div class="modal fade" id="delete-post-info" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <!-- <h6>Product added to cart!</h6> -->
          </div>
          <div class="modal-footer">
            <button id="delete-post-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content animate-bottom">
          <div class="modal-body p-4" id="modal-add-body" style="position: relative;">
            <div class="row gx-0">
              <div class="col-12">
                <div class="col-12 mb-3 text-center">
                  <h5 id="header-report-content">Why you want to report this content?</h5>
                </div>
                <div class="col-12" style="float: left; font-size: 16px">
                  <ul>
                    <form action="/action_page.php">

                      <?php

                      $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                      $query->execute();
                      $category = $query->get_result();
                      $query->close();

                      foreach ($category as $c) : ?>

                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                          <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                            <?= $c['CATEGORY'] ?>
                          </label>
                        </div>


                      <?php endforeach;

                      ?>

                      <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                          <button class="submit-report btn btn-dark" type="button" onclick="reportContentSubmit()">Submit</button>
                        </div>
                      </div>
                    </form>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-report-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-4 text-center">
            <p id="report-submitted" style="font-size: 16px">Report submited.</p>
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                <button class="button-close btn btn-dark" type="button" onclick="reloadPages()">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script> -->
    <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
    <script src="../assets/js/script-comment.js?random=<?= time(); ?>"></script>

    <script>

      if (window.Android) {
        window.Android.tabShowHide(false);
      }

      $(document).ready(function() {
        let commentAmt = parseInt($('#comment-amt').text());

        if (localStorage.lang == 0) {
          // $('#header-title').text('Comment');
          $('#input').attr('placeholder', 'Comment here');
          $('a.click-membership').text('Click Here');
          $('#header-title strong').text('Post Detail');
          if (commentAmt == 1) {
            $('#comment-unit').text('Comment');
          } else {
            $('#comment-unit').text('Comments');
          }
        } else if (localStorage.lang == 1) {
          // $('#header-title').text('Komentar');
          $('#input').attr('placeholder', 'Tulis Komentar');
          $('a.click-membership').text('Klik Disini');
          $('#header-title strong').text('Detail Postingan');
          $('#comment-unit').text('Komentar');
        }

        $('body').css('visibility', 'visible');
      })
    </script>
</body>

</html>