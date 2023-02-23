<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

session_start();

if (isset($_GET['f_pin'])) {
  $f_pin = $_GET['f_pin'];
  $_SESSION['user_f_pin'] = $f_pin;
} else if (isset($_SESSION['user_f_pin'])) {
  $f_pin = $_SESSION['user_f_pin'];
}

// GET USER INFO

$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
$query->execute();
$userData = $query->get_result()->fetch_assoc();
$query->close();

// get categories
$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 8 ORDER BY ID DESC");
$query->execute();
$categoryResult = $query->get_result();
$query->close();

$categoryList = array();
while ($category = $categoryResult->fetch_assoc()) {
  $categoryList[] = $category;
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>News Update</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Font Icon -->
  <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

  <style>
    /* FOR HTML NOT OFFSIDE */

    html,
    body {
      max-width: 100%;
      overflow-x: hidden;
      font-family: 'Poppins';
    }

    .news-title {
      font-size: 15px;
    }

    .news-content {
      font-size: 11px;
    }

    a.news-read-more {
      color: #ff6b00;
      text-decoration: none;
    }

    .small-text {
      font-size: 11px;
    }

    .nav-fill .nav-item .nav-link,
    .nav-justified .nav-item .nav-link {
      width: 100%;
      color: gray;
      font-weight: 600;
      background-color: white;
      font-size: 14px;
      padding: .75rem 1rem;
    }

    .nav-fill .nav-item .nav-link.active,
    .nav-justified .nav-item .nav-link.active {
      color: black;
    }

    .nav-item {
      color: black;
    }

    .nav-pills .nav-item.active:after {
      content: '';
      width: 60px;
      height: 2px;
      background: #ff6b00;
      position: absolute;
      bottom: 0;
      margin: auto;
      right: 0;
      left: 0;
    }

    #timeline-category .nav {
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    #timeline-category .nav-item {
      margin: 0 .3rem;
    }

    #timeline-category .nav-pills .nav-link {
      border-radius: 1rem;
      padding: .25rem .75rem;
      background-color: #e7e7e7;
      color: #777777;
      white-space: nowrap;
      border: 1px solid #e7e7e7;
    }

    #timeline-category .nav-pills .nav-link.active {
      border-radius: 1rem;
      background-color: #ff6b0010;
      color: #ff6b00;
      border: 1px solid #ff6b00;
    }

    .nav-item {
      font-size: 14px !important;
    }

    .small-text {
      font-size: 10px;
    }

    

    .single-news {
      border-radius: 10px;
      background-color: white;
      margin: 6px 0;
    }

    .news-img {
      border-radius: 10px 0px 0px 10px;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .news-img-col {
      position: relative;
    }

    .category-tag {
      font-size: 9px;
      background-color: #27099D;
      color: white;
      padding: 3px 8px;
      border-radius: 15px;
      position: absolute;
      left: 7px;
      top: 7px;
    }

    .news-title {
      font-size: .9rem;
      margin-top: .25rem;
    }

    .news-content {
      font-size: .7rem;
    }

    a.news-read-more {
      color: #ff6b00;
      text-decoration: none;
    }

    .btn-loadmore {
      border-radius: 20px;
      border: 1px solid black;
    }

    .empty-logo {
      width: 40%;
      height: auto;
      margin-bottom: 2rem;
    }

    .empty-title {
      color: gray;
    }

    .empty-subtitle {
      font-size: 12px;
      color: gray;
    }
  </style>

</head>

<body style="background-color: #f1f1f1">

  <div class="p-3 shadow-sm fixed-top" style="border-bottom: 1px solid #e4e4e4; background-color: white">
    <div class="row">
      <div class="col-2 text-center">
        <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
      </div>
      <div class="col-10 ps-0 pt-1">
        <b id="news-update" style="font-size: 14px">News Update</b>
      </div>
    </div>
  </div>

  <div class="timeline-category" id="timeline-category" style="margin-top: 90px">
    <ul class="nav nav-pills px-3 mb-1">
      <li class="nav-item">
        <a class="nav-link category" id="all">
          All
        </a>
      </li>
      <?php foreach ($categoryList as $category) { ?>

        <li class="nav-item">
          <a class="nav-link category" aria-current="page" id="<?= $category["ID"] ?>">
            <?= $category["CODE"] ?>
          </a>
        </li>

      <?php } ?>
      <!-- <li class="nav-item">
        <a class="nav-link" id="user">
          Automotive
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="club">
          News
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="most-comment">
          Event
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="most-comment">
          Entertainment
        </a>
      </li> -->
    </ul>
  </div>

  <div class="container-fluid" id="news-section">


  </div>

  <div class="row mt-5 d-none" id="empty-news">
    <div class="col-8 mx-auto text-center">
      <img class="empty-logo" src="../assets/img/empty-state.png">
      <h6 class="empty-title"><strong>No news at the moment</strong></h6>
      <p class="empty-subtitle">Please come back later.</p>
    </div>
  </div>

  <div class="row mt-4 d-none" id="section-load-more">
    <div class="col-12 text-center">
      <a>
        <button class="btn btn-loadmore" id="btn-loadmore">
          <img src="../assets/img/action_docs.png" style="width:25px; height:auto">
          <span id="load-more" class="mb-0" style="font-size: 12px"><strong></strong></span>
        </button>
      </a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script>var IS_HOMEPAGE = 0; </script>
  <script src="../assets/js/script-homepage.js?v=<?= time() ?>"></script>
</body>

</html>

<script>
  if (window.Android) {
    window.Android.tabShowHide(false);
  }

  function closeAndroid() {

    // if (window.Android) {

    //   window.Android.finishGaspolForm();

    // } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

    //   window.webkit.messageHandlers.finishGaspolForm.postMessage({
    //     param1: ""
    //   });
    //   return;

    // } else {

      history.back();

    // }
  }
</script>

<script>
  $(document).ready(function() {

    if(localStorage.lang == 0) {
      $("#news-update").text("News Update");
      $("#load-more").text("Load more");
    } else {
      $("#news-update").text("Berita Terbaru");
      $("#load-more").text("Tampilkan lebih banyak");

    }

  });
</script>