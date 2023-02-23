<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

$f_pin = $_GET['f_pin'];
$_SESSION['user_f_pin'] = $f_pin;

$dbconn = paliolite();

$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

$sqlGIF = "SELECT BE_ID, COUNT(BE_ID) AS COUNT_BE FROM XPORA_GIF WHERE BE_ID = 0 OR BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
$queGIF = $dbconn->prepare($sqlGIF);
$queGIF->execute();
$resGIF = $queGIF->get_result()->fetch_assoc();
$queGIF->close();

$countGIF = $resGIF["COUNT_BE"];
$be_id = $resGIF["BE_ID"];

$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 5");
$query->execute();
$category_raw = $query->get_result();
$query->close();

$category_arr = array();
while ($cat = $category_raw->fetch_assoc()) {
  $category_arr[] = $cat;
};
?>

<!doctype html>
<html lang="en">

<head>
  <title>Timeline</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/clean-switch.css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../assets/css/tab1-style.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="../assets/css/tab3-style.css?v=<?php echo time(); ?>" />
  <!-- <link rel="stylesheet" href="../assets/css/roboto.css" /> -->
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />

  <script src="../assets/js/xendit.min.js"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jQueryRotate.js"></script>
  <script src="../assets/js/jquery.validate.js"></script>
  <script src="../assets/js/isInViewport.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
  <script src="../assets/js/jquery.ba-throttle-debounce.js"></script>
  <?php
  $rand_bg = rand(1, 12) . ".png";
  ?>

  <style>
    body {
      background: #f1f1f1;
    }

    #header-layout {
      background: white;
      z-index: 99;
      transition: top 0.4s ease-in-out;
    }

    #header-layout #header {
      padding-top: 0px;
    }

    #story-container {
      background: transparent;
    }

    form#searchFilterForm-a {
      border: 1px solid #c9c9c9;
      background-color: rgba(255, 255, 255, .55);
      width: 100%;
    }

    #searchFilter-a {
      margin-left: 0;
    }

    input#query {
      background-color: rgba(255, 255, 255, 0);
    }

    #modal-addtocart .modal-dialog {
      top: 0;
    }

    .float {
      position: fixed;
      width: 60px;
      height: 60px;
      bottom: 75px;
      right: 20px;
      background-color: white;
      color: #FFF;
      border-radius: 50px;
      text-align: center;
      box-shadow: 0px 6px 15px #00000029;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      z-index: 99;
    }

    a#to-new-post:hover {
      color: white;
    }

    .post-status.dropdown-toggle::after {
      display: none;
    }

    .dropdown ul li {
      /* padding: .25rem .75rem; */
    }

    #pbr-timeline {
      max-width: 100%;
      overflow-x: hidden;
      
      margin-top: 120px;
    }

    #timeline-category {
      margin-top: 150px;
    }

    .timeline-image .img-fluid {
      width: 100%;
      height: auto;
    }

    <?php

    $rand_pos = rand(0, 1);

    ?>#gif-container {
      position: fixed;
      z-index: 9999;
    }

    #gif-container.left {
      bottom: 150px;
      left: 20px;
    }

    #gif-container.right {
      bottom: 150px;
      right: 20px;
    }

    .gifs img {
      height: 200px;
      width: auto;
    }

    .modal#modal-addtocart {
      z-index: 99999;
    }

    .timeline-image .carousel-inner,
    .timeline-image .carousel-item.active,
    .timeline-image .carousel-item-wrap,
    .timeline-image .video-wrap {
      overflow: visible !important;
    }

    #categoryFilter-body ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #categoryFilter-body input[type="checkbox"] {
      margin: 5px;
    }

    /* .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
      color: black;
      border-bottom: 2px solid #000000;
      background-color: transparent;
      font-weight: bold;
      border-radius: 0%;
    } */

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

    .carousel-indicators [data-bs-target] {
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

    #timeline-category .nav {
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    #timeline-category .nav-item {
      margin: 0 .3rem;
    }

    #timeline-category .nav-pills .nav-link {
      border-radius:1rem;
      padding: .25rem .75rem;
      background-color:white;
      color:gray;
      white-space:nowrap;
    }

    #timeline-category .nav-pills .nav-link.active {
      border-radius:1rem;
      background-color:#ff6b00;
      color:white;
    }

    .category-check {
      display:none;
    }

    .category-check.active {
      display:inline-block;
    }

    #tkt-follow-list .card {
      margin: 0 5px;
      word-wrap: normal;
      min-height: 160px;
      min-width: 140px;
      max-width: 140px;
      text-align:center;
      
      white-space: nowrap;
      overflow: hidden;
      text-overflow:ellipsis;
      border: 0;
      border-radius: .5rem;
    }

    #tkt-follow-list .card-body {
      padding: .75rem .75rem;
      display: flex;
      justify-content: center;
      align-items: end;
    }

    #tkt-follow-list .card-body .tkt-add-follow {
      text-align:right;
      width: 25px;
      height:auto;
    position: absolute;
    right: 5px;
    top: 5px;
    }

    #tkt-follow-list .card-title {
      /* text-transform: uppercase; */
      /* text-overflow: ellipsis; */
      white-space: nowrap;
      overflow: hidden;
      text-overflow:ellipsis;
    }

    #tkt-follow-list .card-text {
      color:gray;
    }

    #tkt-follow-list .tkt-member,
    #tkt-follow-list .tkt-follower {
      width:15px;
      height:auto;
    }

    /* .carousel,
    .carousel-inner,
    .carousel-inner>.item {
      overflow: hidden;
    }

    .carousel-inner:before {
      position: absolute;
      top: 0;
      bottom: 0;
      right: 82%;
      left: 0;
      content: "";
      display: block;
      background-color: #fff;
      z-index: 2;
    }

    .carousel-inner:after {
      position: absolute;
      top: 0;
      bottom: 0;
      right: 0;
      left: 82%;
      content: "";
      display: block;
      background-color: #fff;
      z-index: 2;
    } */    

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

<body style="visibility:hidden;">
  <!-- <img
    class="demo-bg"
    alt=""
  > -->
  <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
  <div class="container-fluid">


    <div id="header-layout" class="sticky-top">
      <div class="row my-4 px-3">
        <div class="col">
          <img src="../assets/img/logo_gaspol.png" style="width: 28px; height:auto;">
        </div>
        <div class="col text-end">
          <img src="../assets/img/action_chat.png" style="width:30px; height:auto; margin-right:10px;" onclick="openSearch()">
          <a href="imi-notification.php"><img src="../assets/img/notification.png" style="width:30px; height:auto;"></a>
        </div>
        <!-- <div class="col-1">
        </div> -->
      </div>
      <div class="row mb-1">
        <ul class="tab1-tabs nav nav-pills nav-fill px-0">
          <li class="nav-item" id="timeline-tabitem" style="position:relative;">
            <a id="timeline-tab" class="nav-link active" href="#" onclick="changeProfileTab('timeline');">Timeline</a>
          </li>
          <li class="nav-item" id="following-tabitem" style="position:relative;">
            <a id="following-tab" class="nav-link" href="#" onclick="changeProfileTab('following');">Following</a>
          </li>
        </ul>
        <script>
          if (localStorage.lang == 0) {
            $('#timeline-tab').text('Timeline');
          } else {
            $('#timeline-tab').text('Linimasa');
          }
        </script>
      </div>
    </div>
    <div class="timeline-category d-none" id="timeline-category">
      <ul class="nav nav-pills px-3 mb-1">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" id="all">
            Semua
            <img class="ms-1 category-check active" src="../assets/img/check.png" style="width:20px; height: auto;"/>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="user">
            Gaspolers
            <img class="ms-1 category-check" src="../assets/img/check.png" style="width:20px; height: auto;"/>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="club">
            Klub
            <img class="ms-1 category-check" src="../assets/img/check.png" style="width:20px; height: auto;"/>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" id="asosiasi">
            Asosiasi
            <img class="ms-1 category-check" src="../assets/img/check.png" style="width:20px; height: auto;"/>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" id="most-comment">
            Komentar terbanyak
            <img class="ms-1 category-check" src="../assets/img/check.png" style="width:20px; height: auto;"/>
          </a>
        </li>
      </ul>
    </div>
    <div class="timeline" id="pbr-timeline">
      <?php //require('timeline_products.php'); 
      ?>
    </div>
    <div id="loader_message"></div>

    <a id="to-new-post" class="float">
      <img src="../assets/img/newpost.png" style="width:40px; height:auto;">
    </a>
    <script>
      // if (window.Android && typeof window.Android.checkFeatureAccessSilent === "function" && !window.Android.checkFeatureAccessSilent("new_post")) {
      // //   let test = false;
      // // if(!test) {
      //   $('#to-new-post').addClass('d-none');
      // } else {
      //   $('#to-new-post').removeClass('d-none');
      // }
      // if (window.Android) {
      //   if (typeof window.Android.checkFeatureAccessSilent === "function" && !window.Android.checkFeatureAccessSilent("new_post")) {
      //     $('#to-new-post').addClass('d-none');
      //   } else {
      //     $('#to-new-post').removeClass('d-none');
      //   }
      // }
    </script>
  </div>

  <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
    <div class="modal-dialog" role="document">


      <div class="modal-content animate-bottom">
        <div class="modal-back" id="modal-back">
          <img src="../assets/img/icons/Back-(White) - Copy.png" />
        </div>
        <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
        </div>
      </div>
    </div>
  </div>

  <!-- show product modal -->
  <div class="modal fade" id="modal-product" tabindex="-1" aria-labelledby="modal-product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
        </div>
      </div>
    </div>
  </div>
  <!-- show product modal -->

  <!-- add to cart success modal -->
  <div class="modal fade" id="addtocart-success" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <h6>Product added to cart!</h6>
        </div>
        <div class="modal-footer">
          <button id="addtocart-success-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- add to cart success modal -->

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

  <div class="modal fade" id="modal-category2" tabindex="-1" role="dialog" aria-labelledby="modal-category2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content animate-bottom">
        <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

          <div class="row gx-0">
            <div class="col-12">
              <div class="col-12 mb-3 text-center">
                <h5 id="header-report-user">Why you want to report this user?</h5>
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

                    <!-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category1" value="0">
                                        <label class="form-check-label" for="report_category1">
                                        It's a scam
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category2" value="1">
                                        <label class="form-check-label" for="report_category2">
                                        Nudity or sexual activity
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="2">
                                        <label class="form-check-label" for="report_category3">
                                        Hate speech or symbols
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="3">
                                        <label class="form-check-label" for="report_category3">
                                        Bullying or harassment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category4" value="4">
                                        <label class="form-check-label" for="report_category4">
                                        Violence or dangerous organization
                                        </label>
                                    </div> -->

                    <div class="row mt-3">
                      <div class="col-12 d-flex justify-content-center">
                        <button class="submit-report btn btn-dark" type="button" onclick="reportUserSubmit()">Submit</button>
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

  <div class="modal fade" id="modal-categoryFilter" tabindex="-1" role="dialog" aria-labelledby="modal-categoryFilter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header" style="font-size:1rem;">
          Select category
        </div>
        <div class="modal-body" id="categoryFilter-body" style="position: relative;">
          <ul>
            <?php foreach ($category_arr as $category) { ?>
              <li id="item-<?= $category['ID'] ?>">
                <input type="checkbox" id="<?= $category['ID'] ?>" name="item-<?= $category['ID'] ?>" />
                <label for="<?= $category['ID'] ?>"><?= $category['CODE'] ?></label>
              </li>
            <?php } ?>
          </ul>
        </div>
        <div class="modal-footer">
          <button class="btn btn-dark" type="button" id="submitCategory">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-block-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body p-4 text-center">
          <p id="block-user" style="font-size: 16px">You blocked this user.</p>
          <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
              <button class="button-close btn btn-dark" type="button" onclick="reloadPages()">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- delete post -->
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

  <?php if ($countGIF > 0) { ?>
    <div id="gif-container" class="<?php echo $rand_pos == 1 ? "right" : "left" ?>">

    </div>

  <?php } ?>

  <script>
    var BE_ID = <?php echo $be_id != null ? $be_id : "null" ?>;
  </script>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  <script src="../assets/js/tab5-collection.js?r=<?= time(); ?>"></script>
  <script src="../assets/js/script-filter.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/profile-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/seamless-scroll-polyfill@latest/lib/bundle.min.js"></script>
  <script src="../assets/js/script-timeline.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>

  <script src="../assets/js/update_counter.js?r=<?= time(); ?>"></script>
  <script>
    // $('#addtocart-success').on('hidden.bs.modal', function() {
    //   location.reload();
    // });

    if (window.Android) {
      window.Android.tabShowHide(true);
    }

    if (localStorage.lang !== 1) {
      // $('input#query').attr('placeholder', 'Search');
      // $('#story-all-posts').text("All Posts");
    } else {
      $('input#query').attr('placeholder', 'Pencarian');
      $('#story-all-posts').text("Semua Post");
      $('#header-report-content').text("Mengapa anda ingin melaporkan konten ini?");
      $('#header-report-user').text("Mengapa anda ingin melaporkan user ini?");
      $('.submit-report').text("Kirim");
      $('#report-submitted').text("Laporan telah dikirim.");
      $('#block-user').text("Anda telah berhasil memblokir user ini.");
      $('.button-close').text("Tutup");
    }

    function openNewPost(checkIOS = false) {
      if (window.Android) {
        // if (typeof window.Android.checkFeatureAccess === "function" && window.Android.checkFeatureAccess("new_post") && window.Android.checkProfile()) {
        //   window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
        // }
        if (window.Android.checkProfile()) {
          window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
        }
      } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
          param1: '',
          param2: 'newpost'
        });
        return;
      } else {
        let fpin = new URLSearchParams(window.location.search).get("f_pin");
        window.location = "tab5-new-post?f_pin=" + fpin;
      }
      localStorage.setItem("is_grid", "0");
    }

    function openSearch(){

      let fpin = new URLSearchParams(window.location.search).get("f_pin");

      window.location.href = "gaspol_search?f_pin=".concat(fpin);

    }

    $(document).ready(function() {
      $('#to-new-post').click(function() {
        openNewPost();

      })


    })
  </script>

  <script>
    // if (localStorage.lang == 1) {
    //   $('#header-report-content').text("Mengapa anda ingin melaporkan konten ini?");
    // }
  </script>



  <!-- <script src="../assets/js/paliopay-dictionary.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/paliopay.js?random=<?= time(); ?>"></script> -->
</body>

</html>