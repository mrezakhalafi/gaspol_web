<?php

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();
session_start();

// ID SHOP GET

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (!isset($_GET['f_pin'])) {
  $id_shop = $_SESSION["f_pin"];
} else {
  $id_shop = $_GET['f_pin'];
  $_SESSION['f_pin'] = $id_shop;
}

// CHECK SHOP ID

if (!isset($id_shop)) {
  die("ID Shop Tidak Diset.");
}

// SELECT SHOP DATA ACTIVE

// $query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 1 
//                           AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
// $query->execute();
// $tagged_product = $query->get_result();
// $query->close();

// SELECT COLOR CONFIGURATION

$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
$query->execute();
$nexilis_configuration = $query->get_result()->fetch_assoc();
$query->close();

// GET SHOP CATEGORY

$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 5");
$query->execute();
$categoryData = $query->get_result();
$query->close();

$dbNewNus = newnus();

$bg_url = "";

if (isset($id_shop) && $id_shop != "") {
  $sqlBE = "SELECT be.COMPANY_ID
  FROM BUSINESS_ENTITY be
  LEFT JOIN USER_LIST ul ON ul.BE = be.ID
  WHERE ul.F_PIN = '$id_shop'";

  $queBE = $dbconn->prepare($sqlBE);
  $queBE->execute();
  $resBE = $queBE->get_result()->fetch_assoc();
  $c_id = $resBE["COMPANY_ID"];
  $queBE->close();

  // get BG
  $sqlBG = "SELECT APP_BG FROM WEBFORM WHERE COMPANY_ID = " . $c_id . " AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
  $queBG = $dbNewNus->prepare($sqlBG);
  $queBG->execute();
  $resBG = $queBG->get_result()->fetch_assoc();
  $bg_str = $resBG["APP_BG"];
  $queBG->close();

  // echo $bg_str;
  if ($bg_str != null && $bg_str != "") {
    $bg_arr = explode(",", $bg_str);

    // echo "<pre>";
    // print_r($bg_arr);
    // echo "</pre>";

    $rand_bg = $bg_arr[array_rand($bg_arr, 1)];

    $bg_url = "https://newuniverse.io/dashboardv2/uploads/background/" . $rand_bg;
  } else {
    $rand_bg = rand(1, 12) . ".png";
    $bg_url = "../assets/img/lbackground_" . $rand_bg;
  }
}

$ver_rand = time();


// get club
$sql = "SELECT tkt.*
FROM CLUB_MEMBERSHIP cm
LEFT JOIN TKT tkt ON cm.CLUB_CHOICE = tkt.ID
WHERE cm.STATUS = 1
AND cm.CLUB_CHOICE IN (SELECT ID FROM TKT)
AND cm.F_PIN = '$id_shop'";

$query = $dbconn->prepare($sql);
$query->execute();
$club_membership_raw = $query->get_result();
$query->close();

$club_membership = array();
while ($club = $club_membership_raw->fetch_assoc()) {
  $club_membership[] = $club;
}

// echo "<pre>";
//     print_r($club_membership);
//     echo "</pre>";
?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <title>Nexilis</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css?v=<?= $ver_rand ?>" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

  <style>
    /* FOR MODAL CAN BE SLIDER DOWN */

    body {
      min-height: fit-content;
      background-color: #f1f1f15e;
    }

    .container-fluid#header {
      padding: .5rem 1.25rem;
      background-color: white;
    }

    body.modal-open {
      position: inherit;
    }

    /* FOR FULL WIDTH MODAL */

    .modal-dialog {
      /* max-width: 100%;
      margin: 0;
      bottom: 0;
      left: 0;
      right: 0;
      height: 100vh;
      display: flex;
      margin-top: 10px; */
    }

    .modal-body {
      /* margin-top: -15px;
      padding-left: 0 !important;
      padding-right: 0 !important; */
    }

    .add-post-input {
      background: transparent;
      border-bottom: 1px solid lightgray;
      padding-top: 0;
      font-size: 14px;
      color: black;
    }

    .add-post-input:focus {
      border-bottom: 1px solid #ff6b00;
    }

    #upload-listing-dropdown {
      background-color: rgba(250, 250, 255, .8) !important;
    }

    div.section-post-description {
      padding-bottom: 5rem;
    }

    button#create-post:disabled {
      color: darkgray !important;
    }

    .form-check-input[type=checkbox] {
      border: 1px solid #8d8d8d !important;
    }

    .upload-listing-border {
      width: 125px;
      height: 125px;
    }

    .upload-listing-add {
      position: absolute;
      /* top: 42%; */
      /* left: 35%; */
      width: 30px;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }

    .navbar-back-black {
      width: 20px;
      height: auto;
    }

    .send-post-check {

      width: 25px;
      height: auto;
    }

    .upload-listing-image-slot {
      background-color: transparent;
      border: 0;
    }

    .nav-tabs {
      border: 0;
    }

    .attach-preview {
      object-fit: cover;
      width: 100%;
      height: 100%;
      border-radius: 10px;
      vertical-align:baseline;
    }

    .delete-listing-img {
      width: 30px;
      height: 30px;
      position: absolute;
      /* left: 0; */
      right: 5px;
      top: 5px;
    }

    .labels {
      font-size: 12px;
      color: gray;
    }

    .modalMenu .modal-dialog {
      position: fixed;
      top: auto;
      right: auto;
      left: auto;
      bottom: 0;
      margin: 0;
      padding: 0;
      width: inherit;
      margin-bottom: -10px;
    }

    .modalMenu .modal-content {
      height: 260px;
      padding-top: 20px;
      border: none;
    }

    #share-to {
      border-bottom: 1px solid lightgray;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <!-- <img src="<?php echo $bg_url; ?>" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999"> -->
  <!-- <div id="header"></div> -->

  <!-- NAVBAR -->

  <!-- <form method="POST"> -->
  <!-- <nav class="navbar navbar-light navbar-shop-manager"> -->
  <div class="container-fluid" id="header">
    <div class="row">
      <div class="col-6 d-flex align-items-center">
        <a onclick="goBack()">
          <img src="../assets/img/action_close.png" class="navbar-back-black">
        </a>
        <strong class="ms-3" id="title-newpost"></strong>
        <script>
          if (localStorage.lang == 0) {
            // $('#title-newpost').text('New Post');
            document.getElementById('title-newpost').innerHTML = 'New Post';
          } else {
            // $('#title-newpost').text('Buat Postingan');
            document.getElementById('title-newpost').innerHTML = 'Berita Baru';
          }
        </script>
      </div>
      <div class="col-6 text-end">
        <img src="../assets/img/check_line_disabled.png" class="send-post-check me-2">
        <button id="create-post" disabled class="btn" style="padding:.375rem 0;">
        </button>
        <script>
          if (localStorage.lang == 0) {
            // $('#title-newpost').text('New Post');
            document.getElementById('create-post').innerHTML = '<strong>Post</strong>';
          } else {
            // $('#title-newpost').text('Buat Postingan');
            document.getElementById('create-post').innerHTML = '<strong>Kirim</strong>';
          }
        </script>
      </div>
    </div>
  </div>
  <!-- </nav> -->

  <!-- SECTION NEW POST IMAGE -->

  <div class="section-new-post mt-3">
    <!-- <div class="row gx-0">

      <input type="file" id="addFile" class="d-none" onchange="loadFile(event)">
      <label for="addFile" id="addFile-label">
        <img src="../assets/img/tab5/Shop Manager/noimage-large.jpg" id="image-preview" class="new-post-image">
      </label>
      <img id="video-thumbnail" class="d-none">
    </div> -->
    <div class="row small-text gx-0 ps-3">
      <div class="container">
        <ul class="nav nav-tabs horizontal-slide gx-0">

          <!-- LOOP 10 SLOT FOR UPLOAD MEDIA -->

          <?php for ($i = 1; $i <= 5; $i++) { ?>

            <li class="nav-item <?= $i != 1 ? 'd-none' : '' ?>" id="attach-item-<?= $i ?>">
              <div class="upload-listing-image-slot d-flex justify-content-center">
                <div class="single-upload-cover-listing">
                  <div class="image-upload">
                    <label for="file-input-<?= $i ?>" class="row" style="--bs-gutter-x: none">
                      <img src="../assets/img/action_close_circle.png" data-delete-slot="<?= $i ?>" class="delete-listing-img" id="delete-listing-<?= $i ?>" style="z-index: 9999; display:none;">
                      <div id="image-preview-<?= $i ?>" class="upload-listing-border" style="border-radius:10px; border:1px solid black"></div>
                      <img src="../assets/img/tkt_add_follow.png" class="upload-listing-add" id="upload-listing-add-<?= $i ?>" style="<?php echo $i != 1 ? "display:none;" : "" ?>">
                    </label>
                    <input id="file-input-<?= $i ?>" type="file" name="listing_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                  </div>
                </div>
              </div>
            </li>

          <?php } ?>

          <!-- <p class="ms-3 mb-1" id="file-required" style="font-size: 11px;"></p> -->
        </ul>
        <script>
          // if (localStorage.lang == 0) {

          //   // $('#file-required').text('Please at least select 1 image/video.');
          //   document.getElementById('file-required').innerText = 'Please select one image/video'
          // } else {
          //   // $('#file-required').text('Mohon pilih setidaknya 1 gambar/video.');
          //   document.getElementById('file-required').innerText = 'Mohon pilih setidaknya 1 gambar/video'
          // }
        </script>
      </div>
    </div>
  </div>

  <!-- SECTION NEW POST FORM -->

  <div class="section-post-description mt-3">
    <div class="row gx-0 add-post d-none">

      <input type="text" class="add-post-input d-none" id="post_title" maxlength="64" name="post_title" placeholder="">
      <div class="d-none justify-content-end">
        <span id="counter-word-2" class="smallest-text text-grey">0</span><span class="smallest-text text-grey">/64</span>
      </div>
    </div>
    <div class="row gx-0 add-post">
      <label for="caption" id="caption-label" class="labels"></label>
      <textarea class="add-post-input mt-3" id="caption" maxlength="500" required name="caption" placeholder=""></textarea>
      <div class="d-flex justify-content-end">
        <span id="counter-word" class="smallest-text text-grey">0</span><span class="smallest-text text-grey">/500</span>
      </div>
    </div>
    <div class="row gx-0 add-post d-none">
      <input type="text" class="add-post-input" id="post_link" name="post_link" placeholder="URL">
    </div>
    <div class="row gx-0 add-post mt-2" style="padding-bottom: 0px">
      <label for="share-to" id="shareto-label" class="labels"></label>
      <button class="btn ps-0 mt-3" type="button" id="share-to" data-bs-toggle="modal" data-bs-target="#modalReport">
        <span class="listing-category-select" style="float:left;">Publik</span>
        <img src="../assets/img/chevron_down.png" style="width: 20px; height:auto; float:right;">
      </button>
    </div>
    <div class="row gx-0 d-none">
      <div class="col-12 col-md-12 col-lg-12 upload" style="padding-bottom: 5px; padding-top: 5px; padding-left: 20px; padding-right: 20px">
        <div class="form-check" style="padding-top: 5px; padding-bottom: 5px !important">
          <input class="form-check-input" name="post_check_old" type="checkbox" value="1" id="paid_post" onchange="changeProductMenu()">
          <label class="form-check-label small-text text-grey" for="paid_post">
            <span data-translate="tab5uploadlisting-14"></span>
          </label>
        </div>
      </div>
    </div>
    <div id="submenu_product" class="row gx-0 d-none" style="margin-bottom: 20px">
      <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-left add-post" style="padding-right: 5px">
        <div class="form-group">
          <input type="text" class="add-post-input" id="price" maxlength="64" name="price" placeholder="Price*">
        </div>
      </div>
      <div class="col-6 col-md-6 col-lg-6 add-post" style="padding-left: 5px">
        <div class="form-group">
          <input type="text" class="add-post-input" id="stock" maxlength="64" name="quantity" placeholder="Quantity*">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="filesize-warning" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="form-check" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-3">
          <!-- <h6>File size is too large. Please limit it to 2MB or less.</h6> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="form-posting" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-3">
          <!-- <h6>File size is too large. Please limit it to 2MB or less.</h6> -->
        </div>
        <div class="modal-footer">
          <!-- <button id="form-posting-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button> -->
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modalMenu" id="modalReport" tabindex="-1" aria-labelledby="modalReportLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="row">
        <div class="col-12 d-flex justify-content-center">
          <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
        </div>
      </div>
      <div class="modal-content" style="height: 220px">
        <div class="modal-body">
          <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="selectPostShare(0)">
            <div class="col-2 text-center">
              <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/globe-earth.png">
            </div>
            <div class="col-10">
              <b style="font-size: 13px">Publik</b>
            </div>
          </div>

          <?php foreach ($club_membership as $club) { ?>

            <div class="row pb-3 pt-3" id="tkt-<?= $club["ID"] ?>" style="border-bottom: 1px solid #e7e7e7" onclick='selectPostShare(<?= $club["ID"] ?>)'>
              <div class="col-2 text-center">
                <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/club.png">
              </div>
              <div class="col-10">
                <b style="font-size: 13px"><?= $club["CLUB_NAME"] ?></b>
              </div>
            </div>

          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" class="tagged_product" id="tagged_product" name="tagged_product" value="">
  <input type="hidden" class="post_photo" name="post_photo" value="">
  <!-- <input type="hidden" id="is_paid" name="is_paid" value="0"> -->
  <input type="hidden" name="post_check" id="post_check" value="0">

  <!-- Modal -->
  <!-- </div> -->
  <!-- </form> -->

  <input type="hidden" id="shop_id" value="<?= $id_shop ?>">

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.13.0-rc.2/jquery-ui.js" integrity="sha256-bLjSmbMs5XYwqLIj5ppZFblCo0/9jfdiG/WjPhg52/M=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script type="module" src="../assets/js/translate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.js"></script>
<script>

if (window.Android) {
      window.Android.tabShowHide(false);
    }
  // SCRIPT CHANGE LANGUAGE
  function goBack() {

    let isGrid = localStorage.getItem('is_grid');
    if (window.Android) {
      // window.Android.goBack();
      if (document.referrer != '' && document.referrer != null) {
        window.location = document.referrer;
      } else {
        // window.location = 'tab1-main.php?f_pin=' + window.Android.getFPin();
        if (isGrid != null) {
          if (isGrid == "1") {
            window.location = 'tab3-main.php?f_pin=' + window.Android.getFPin();
          } else {
            window.location = 'tab1-main.php?f_pin=' + window.Android.getFPin();
          }
        } else {
          window.location = document.referrer;
        }
      }
    } else {
      if (document.referrer != '' && document.referrer != null) {
        window.location = document.referrer;
      } else {
        // window.location = 'tab1-main.php?f_pin=<?php echo $id_shop; ?>';
        if (isGrid != null) {
          if (isGrid == "1") {
            window.location = 'tab3-main.php?f_pin=' + window.Android.getFPin();
          } else {
            window.location = 'tab1-main.php?f_pin=' + window.Android.getFPin();
          }
        } else {
          window.location = document.referrer;
        }
      }
    }
  }

  let club_id = 0;

  function selectPostShare(id) {
    club_id = id;
    $('#modalReport').modal('hide');
  }

  $(document).ready(function() {
    function changeLanguage() {

      var lang = localStorage.lang;
      try {
        change_lang(lang);
      } catch {}

    }

    if (localStorage.lang == 1) {
      // $('.navbar-title-2').css('margin-left', '30px');
      $('#post_title').attr('placeholder', 'Tulis Judul*');
      $('#caption').attr('placeholder', 'Apa yang Anda pikirkan...');
      $('#location').attr('placeholder', 'Tambahkan Lokasi');
      $('#tagged-text').attr('placeholder', 'Kaitkan Produk');
      $('#search-tagged').attr('placeholder', 'Pencarian');
      $('#paid-content').text('Konten Berbayar');
      $('#price').attr('placeholder', 'Harga*');
      $('#stock').attr('placeholder', 'Jumlah*');
      $('#caption-label').text('Posting Konten')
      $('#shareto-label').text('Bagikan ke')
    } else {
      $('#post_title').attr('placeholder', 'Write a Title*');
      $('#caption').attr('placeholder', 'What are you thinking...');
      $('#caption-label').text('Content Posting')
      $('#shareto-label').text('Share to')
    }

    changeLanguage();
    $('body').show();
  });

  // SCRIPT SLIDER TAGGED PRODUCT

  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));
  let init = parseInt(fixedPos.replace('px', ''));

  $('[data-bs-target="#taggedProductModal"]').click(function() {
    $('#taggedProductModal .modal-dialog').css('top', fixedPos);
    $('#taggedProductModal .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#taggedProductModal').draggable({
    handle: ".mod-header",
    axis: "y",
    drag: function(event, ui) {

      console.log('init: ' + init);
      if (ui.position.top < init) {
        ui.position.top = init;
      }

      let dialog = ui.position.top + window.innerHeight;
      if (dialog - window.innerHeight > 50) {
        $('#taggedProductModal').modal('hide');
      }
    }
  });

  function showModal() {
    $('body').css('height', '900px');
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 1));
    $('#taggedProductModal').modal('show');
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    $('.modal-dialog').css('margin-top', '50px');
    $('.modal-dialog').css('padding-bottom', '50px');
  }

  $('#taggedProductModal').on('hidden.bs.modal', function() {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    $('.modal').css('top', '0px');
    $('.modal').css('left', '0px');
    $('body').css('height', '100%');
    $('.modal-dialog').css('margin-top', '-50px');
    $('.modal-dialog').css('padding-bottom', '-50px');
  })

  // IS PAID

  $("#flexCheckDefaultPaid").change(function(event) {
    if (this.checked) {
      $('#is_paid').val(1);
      localStorage.setItem('is_paid', 1);
      $('#price').attr('disabled', false);
    } else {
      $('#is_paid').val(0);
      $('#price').val("");
      $('#price').attr('disabled', true);
    }
  });

  // CHECKBOX TAGGED PRODUCT

  $(document).on("change", ":checkbox", function(e) {

    // IF MODAL OPENED RUN THIS CHECKBOX EVENT

    if ($('#taggedProductModal').is(':visible')) {
      var array_tagged = $('.tagged_product').val();
      var tagged_name = $('.tagged-input').val();

      if (this.checked) {
        $('.tagged_product').val($(this).val());
        $('.tagged-input').val($(this).val());

        if (array_tagged.length > 0) {
          array_tagged = array_tagged + "|" + $(this).data("code-tagged");
          tagged_name = tagged_name + ", " + $(this).val();
        } else {
          array_tagged = $(this).data("code-tagged");
          tagged_name = $(this).val();
        }

        $('.tagged_product').val(array_tagged);
        $('.tagged-input').val(tagged_name);

        console.log(array_tagged);

        // SAVE TO LOCAL STORAGE

        localStorage.setItem('tagged_post', array_tagged);
        localStorage.setItem('tagged_post_name', $('.tagged-input').val());

      } else {

        var array_tagged = $('.tagged_product').val();
        var tagged_name = $('.tagged-input').val();

        if (array_tagged.includes("|")) {

          var a = array_tagged.replace("|" + $(this).data("code-tagged"), "");
          var b = tagged_name.replace(", " + $(this).val(), "");

          var c = a.replace($(this).data("code-tagged") + "|", "");
          var d = b.replace($(this).val() + ", ", "");

          $(".tagged_product").val(c);
          $(".tagged-input").val(d);

        } else {
          array_tagged = array_tagged.replace($(this).data("code-tagged"), "");
          tagged_name = tagged_name.replace($('.tagged-input').val(), "");

          $('.tagged_product').val(array_tagged);
          $('.tagged-input').val(tagged_name);
        }

        var array_tagged = $('.tagged_product').val();
        var tagged_name = $('.tagged-input').val();

        $('.tagged_product').val(array_tagged);
        $('.tagged-input').val(tagged_name);

        console.log(array_tagged);

        // SAVE TO LOCAL STORAGE

        localStorage.setItem('tagged_post', array_tagged);
        localStorage.setItem('tagged_post_name', $('.tagged-input').val());

      }
    }
  });

  for (var i = 2; i <= 25; i++) {
    $('#upload-listing-add-' + i).hide();
    $('#file-input-' + i).prop("type", "text");
    $('#delete-listing-' + (i - 1)).hide();
  }

  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];

  var loadFile = function(event, number) {
    if (event.target.files[0].size <= 33554432) { //32 MB
      var fileFormat = event.target.files[0].name.split('.')[1];
      var img, vid, canvas, ctx;
      var reader = new FileReader();
      reader.onload = createImage;
      reader.readAsDataURL(event.target.files[0]);

      function createImage() {
        if ($image_type_arr.includes(fileFormat)) {
          img = new Image();
          img.onload = imageLoaded;
          img.src = reader.result;
        } else if ($video_type_arr.includes(fileFormat)) {
          // setImage(reader.result);
          // checkValid();
          // vid = document.createElement('video');
          // vid.onloadedmetadata = videoLoaded;
          // vid.src = reader.result;
          setImage(reader.result, number);
          // vid.load();
        }
      }

      function imageLoaded() {
        let imgDataURL;

        canvas = document.createElement('canvas');
        ctx = canvas.getContext("2d");
        if (event.target.files[0].size > 204800) {
          // set size proportional to image
          canvas.height = canvas.width * (img.height / img.width);

          // step 1 - resize to 50%
          var oc = document.createElement('canvas'),
            octx = oc.getContext('2d');

          oc.width = img.width * 0.8;
          oc.height = img.height * 0.8;
          octx.drawImage(img, 0, 0, oc.width, oc.height);

          // step 2
          octx.drawImage(oc, 0, 0, oc.width, oc.height);

          // step 3, resize to final size
          ctx.drawImage(oc, 0, 0, oc.width, oc.height,
            0, 0, canvas.width, canvas.height);
        } else {
          canvas.width = img.naturalWidth;
          canvas.height = img.naturalHeight;
          ctx.drawImage(img, 0, 0);
        }
        imgDataURL = canvas.toDataURL("image/webp");
        setImage(imgDataURL, number);
        checkValid();
      }

      function videoLoaded() {
        let thumbnail;
        console.log('asda');
        canvas = document.createElement('canvas');
        ctx = canvas.getContext("2d");
        // ctx.drawImage(vid, 0, 0, vid.videoWidth, vid.videoHeight);
        // let thumbnail = canvas.toDataURL("image/webp");

        // seek to user defined timestamp (in seconds) if possible
        if (vid.duration < 1) {
          reject("video is too short.");
          return;
        }
        // delay seeking or else 'seeked' event won't fire on Safari
        vid.addEventListener('seeked', () => {
          console.log('video is now paused at 0.1');
          // define a canvas to have the same dimension as the video
          canvas.width = vid.videoWidth;
          canvas.height = vid.videoHeight;
          ctx.drawImage(vid, 0, 0, canvas.width, canvas.height);
          // return the canvas image as a blob
          thumbnail = canvas.toDataURL('image/webp', 0.8);
          setImage(reader.result, number, thumbnail);
          checkValid();
        });
        setTimeout(() => {
          vid.currentTime = 1;
        }, 1000);
        // extract video thumbnail once seeking is complete


      }
    } else {
      if (localStorage.lang == 0) {
        $('#filesize-warning .modal-body').html('<h6>File size is too large. Please limit it to 32MB or less.</h6>');
      } else {
        $('#filesize-warning .modal-body').html('<h6>Ukuran file terlalu besar. Mohon batasi menjadi 32MB atau kurang.</h6>');
      }
      $('#filesize-warning').modal('toggle');
    }
  }

  function pauseAll() {
    if ($('video#media').length > 0) {
      $('video#media').get(0).pause();
    }
  }

  function resumeAll() {
    if ($('video#media').length > 0) {
      $('video#media').get(0).play();
    }
  }

  var media_link;
  var video_thumb;
  var array_upload_photo = [];

  function setImage(link, number, thumbnail = "") {

    var strings = link.split("/");

    checkValid();

    // if (strings[0] == 'data:video') {
    //   $('label#addFile-label').html('<video src="' + link + '#t=0.5" id="media" autoplay muted loop style="display:block; width:100%; height:100%; object-fit: cover" type="video/mp4"></video>');
    //   $('img#video-thumbnail').attr('src', thumbnail);
    //   video_thumb = thumbnail;
    // } else {
    //   $('label#addFile-label').html('<img src="' + link + '" id="image-preview" class="new-post-image">');
    //   video_thumb = null;
    // }

    // media_link = link;

    if (strings[0] == 'data:video') {
      // $('#image-preview-' + number).replaceWith('<video src="' + link + '#t=0.5" id="video-preview-' + number + '" autoplay muted type="video/mp4" class="upload-listing-border">');

      $('#image-preview-' + number).html('<video src="' + link + '#t=0.5" id="video-' + number + '" autoplay muted type="video/mp4" class="attach-preview">');

      // IF DIV ALREADY VIDEO REPLACE AGAIN (CONFLICT : NO IMG REPLACED WITH VIDEO)

      // $('#video-preview-' + number).replaceWith('<video src="' + link + '#t=0.5" id="video-preview-' + number + '" autoplay muted type="video/mp4" class="upload-listing-border">');
    } else {
      $('#image-preview-' + number).html('<img src="' + link + '" id="image-' + number + '" class="attach-preview" style="vertical-align:baseline;">');

      // var output = document.getElementById('image-preview-' + number);
      // output.src = link;
    }

    $('#upload-listing-add-' + number).hide();
    $('#attach-item-' + (number + 1)).removeClass('d-none');
    $('#image-preview-' + number).css('border', '0')
    $('#upload-listing-add-' + (number + 1)).show();
    $('#file-input-' + (number + 1)).prop("type", "file");

    $('#delete-listing-' + number).show();

    // WHICH SLOT CHANGED IMAGE UPLOADED [EX = 0,4,5]

    if (!array_upload_photo.includes(number)) {

      array_upload_photo.push(number);
      $('#array_upload_photo').val(array_upload_photo);
    }

    console.log('array photo', array_upload_photo);
    console.log($('#array_upload_photo').val());

  }

  // DELETE DIV WHILE CLICK X

  var number_extend = 6;

  $('body').on("click", ".delete-listing-img", function() {



    $(this).parent().parent().parent().parent().parent().remove();

    // IF MATCH WITH SLOT DELETE THAT EX = DELETE 2 REMOVE 2 FROM ARRAY

    var delete_slot = $(this).data("delete-slot");
    array_upload_photo = array_upload_photo.filter(e => e !== delete_slot);

    $('#array_upload_photo').val(array_upload_photo);
    console.log($('#array_upload_photo').val());

    // ADD MORE SLOT WHILE USER DELETED SLOT

    /* FOR
        <li class="nav-item <?= $i != 1 ? 'd-none' : '' ?>" id="attach-item-<?= $i ?>">
              <div class="upload-listing-image-slot d-flex justify-content-center">
                <div class="single-upload-cover-listing">
                  <div class="image-upload">
                    <label for="file-input-<?= $i ?>" class="row" style="--bs-gutter-x: none">
                      <img src="../assets/img/action_close_circle.png" data-delete-slot="<?= $i ?>" class="delete-listing-img" id="delete-listing-<?= $i ?>" style="z-index: 9999; display:none;">
                      <div id="image-preview-<?= $i ?>" class="upload-listing-border" style="border-radius:10px; border:1px solid black"></div>
                      <img src="../assets/img/tkt_add_follow.png" class="upload-listing-add" id="upload-listing-add-<?= $i ?>" style="<?php echo $i != 1 ? "display:none;" : "" ?>">
                    </label>
                    <input id="file-input-<?= $i ?>" type="file" name="listing_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                  </div>
                </div>
              </div>
            </li>
    */

    var add_slot =
      `<li class="nav-item d-none" id="attach-item-${number_extend}">
        <div class="upload-listing-image-slot d-flex justify-content-center">
            <div class="single-upload-cover-listing">
                <div class="image-upload">
                    <label for="file-input-` + number_extend + `" class="row" style="--bs-gutter-x: none">
                        <img src="../assets/img/action_close_circle.png" data-delete-slot="` + number_extend + `" class="delete-listing-img" id="delete-listing-` + number_extend + `" style="z-index: 9999; display:none">
                        <div id="image-preview-${number_extend}" class="upload-listing-border" style="border-radius:10px; border:1px solid black"></div>
                        <img src="../assets/img/tkt_add_follow.png" class="upload-listing-add" id="upload-listing-add-` + number_extend + `">
                    </label>
                    <input id="file-input-` + number_extend + `" type="file" name="listing_thumbnail-` + number_extend + `" onchange="loadFile(event, ` + number_extend + `)" />
                </div>
            </div>
        </div>
    </li>`
    $('.horizontal-slide').append(add_slot);

    // if ($('.horizontal-slide li').length == 5 && $('#attach-item-' + number_extend).hasClass('d-none')) {
    //   $('#attach-item-' + number_extend).removeClass('d-none');
    //   $('#upload-listing-add-' + number_extend).removeClass('d-none');
    // }

    if ($('.attach-preview').length < 5) {
      let getAttach = $('.attach-preview').length;
      $('.horizontal-slide li:nth-child(' + (getAttach+1) + ')').removeClass('d-none');
    }

    number_extend++;
    checkValid();

    // IF LOCALSTORAGE DELETE EXIST

    window.localStorage.removeItem('get_listing_media_temp_1');

  });

  // SCRIPT SET IMAGE FROM ANDROID/IOS

  // function setImage(link) {

  //   if (window.Android) {
  //     link = Android.getDataImage();
  //   }

  //   var strings = link.split("/");

  //   if (strings[0] == 'data:video') {
  //     $('#image-preview').replaceWith('<video src="' + link + '#t=0.5" id="video-preview" autoplay muted style="object-fit: none" type="video/mp4" class="new-post-image">');

  //     // IF DIV ALREADY VIDEO REPLACE AGAIN (CONFLICT : NO IMG REPLACED WITH VIDEO)

  //     $('#video-preview').replaceWith('<video src="' + link + '#t=0.5" id="video-preview' + '" autoplay muted  style="object-fit: none" type="video/mp4" class="new-post-image">');
  //   } else {
  //     var output = document.getElementById('image-preview');
  //     output.src = link;
  //   }

  //   // SAVE TO LOCAL STORAGE

  //   localStorage.setItem('link', link);
  //   localStorage.setItem('media_post', link);
  // }

  // if (!window.Android) {
  // $('#image-preview').click(function() {
  //   $("#addFile").click();
  // })

  // $('#addFile').change(function(event) {
  //   var output = document.getElementById('image-preview');
  //   let link = URL.createObjectURL(event.target.files[0]);
  //   output.src = link;
  //   localStorage.setItem('link', link);
  //   localStorage.setItem('media_post', link);
  // })
  // // }

  // $("#addFile").change(function(e) {
  //   var file = e.target.files[0]; //FileList object
  //   var picReader = new FileReader();

  //   var fileSize = $(this).get(0).files[0].size;
  //   // // console.log("img size: " + fileSize);

  //   // if file > 2 MB
  //   if (fileSize > 2097152) {
  //     alert("Please limit your file size to 2 MB or less.");
  //     // $("#uploadLogo").attr('disabled', 'true');
  //     $(this).val(null);
  //   } else {

  //     // $("#uploadLogo").attr('disabled', 'false');
  //     //Only pics
  //     if (!file.type.match('image'))
  //       return;

  //     // base64 the image
  //     picReader.addEventListener("load", function(event) {
  //       var picFile = event.target;
  //       var base64result = picFile.result.split(",")[1]
  //       localStorage.setItem('link', picFile.result);
  //       localStorage.setItem('media_post', picFile.result);
  //     });

  //     // compress the image
  //     new Compressor(file, {
  //       quality: 0.9,
  //       maxWidth: 640,
  //       maxHeight: 480,
  //       success(result) {
  //         // see preview of the compressed image
  //         var imageUrl = URL.createObjectURL(result);
  //         document.querySelector("#image-preview").src = imageUrl;

  //         // //Read the image
  //         picReader.readAsDataURL(result);
  //       },
  //       error(err) {
  //         // console.log(err.message);
  //       },
  //     });
  //   }

  // });


  // FOR INPUT INSIDE DRAGGABLE CONTENT FOCUSED

  $(':input').bind('click', function() {
    $(this).focus();
  });

  // PREVENT INPUT WITH ENTER (DISABLE POPUP REQUIRED)

  $(':input').on('keypress', function(e) {
    return e.which !== 13;
  });

  // SAVE DATA FOR REFRESH

  $("#caption").blur(function() {
    var caption = $(this).val();
    // localStorage.setItem('caption_post', caption);
  })

  var get_caption = localStorage.getItem('caption_post');
  var get_media = localStorage.getItem('media_post');

  var get_category_name = localStorage.getItem('category_name');
  var get_category_id = localStorage.getItem('category_id');

  if (get_caption != null) {
    $('#caption').siblings('.palceholder').hide();
    // $("#caption").val(get_caption);
  }

  if (get_media != null) {
    // setImage(get_media);
  }

  if (get_category_id != null) {
    // $('.listing-category-select').text(get_category_name);
    // $('.category').val(get_category_id);
    // $('.category_name').val(get_category_name);
  }

  // CHANGE DROPDOWN AS NAME AS CLICK

  $('.dropdown-item').click(function() {
    console.log('click');
    $('.listing-category-select').text($(this).text());
    $('.category').val($(this).data('id'));
    $('.category_name').val($(this).text());

    // SAVE DROPDOWN TO LOCAL STORAGE

    var category_name = $(this).text();
    var category_id = $(this).data('id');
    localStorage.setItem('category_name', category_name);
    localStorage.setItem('category_id', category_id);

    checkValid();
  });

  // FUNCTION VOICE SEARCH

  function voiceSearch() {
    Android.toggleVoiceSearch();
  }

  function dataURLtoFile(dataurl, filename) {
    var arr = dataurl.split(','),
      mime = arr[0].match(/:(.*?);/)[1],
      bstr = atob(arr[1]),
      n = bstr.length,
      u8arr = new Uint8Array(n);

    while (n--) {
      u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, {
      type: mime
    });
  }

  function submitVoiceSearch(searchQuery) {
    $('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
  }

  function checkValid() {
    let caption = document.getElementById("caption");
    let title = document.getElementById("post_title");
    let postButton = document.getElementById('create-post');
    let category = document.getElementById("category");

    let price = $('#price').val();
    let stock = $('#stock').val();

    // let isFileValid = (media_link != undefined && media_link != "");
    console.log('array photo lengh', array_upload_photo)
    let isFileValid = array_upload_photo.length > 0;
    let isCaptionValid = caption.value.trim().length > 0;
    // let isTitleValid = title.value.trim().length > 0;
    // let isCategoryValid = category.value.trim() !== '';

    if (isFileValid && isCaptionValid) {
      // console.log('enabled');
      if ($("#paid_post").is(':checked')) {
        if (isFileValid && isCaptionValid) {
          postButton.disabled = false;
          $('.send-post-check').attr('src', '../assets/img/check_line.png')
        } else {
          postButton.disabled = true;
          $('.send-post-check').attr('src', '../assets/img/check_line_disabled.png')
        }
      } else {
        postButton.disabled = false;
        $('.send-post-check').attr('src', '../assets/img/check_line.png')
      }
    } else {
      // console.log('disabled');
      postButton.disabled = true;
      $('.send-post-check').attr('src', '../assets/img/check_line_disabled.png')
    }
  }

  // document.getElementById('caption').addEventListener('keyup', function(e) {
  //   checkValid();
  // })

  // document.getElementById('post_title').addEventListener('keyup', function(e) {
  //   checkValid();
  // })

  // document.getElementById('caption').addEventListener('paste', function(e) {
  //   checkValid();
  // })

  // document.getElementById('post_title').addEventListener('paste', function(e) {
  //   checkValid();
  // })

  $('#caption').on("change keyup paste input", function() {
    checkValid();
  })
  $('#post_title').on("change keyup paste input", function() {
    checkValid();
  })
  $('#price').on("change keyup paste input", function() {
    checkValid();
  })
  $('#stock').on("change keyup paste input", function() {
    checkValid();
  })

  function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) {
      return map[m];
    });
  }

  function sendData() {

    var formData = new FormData();

    // let file = localStorage.getItem('link');


    var caption = escapeHtml($('#caption').val());

    let title = caption.length > 256 ? caption.substr(0, 200) : caption;

    // let post_link = $('#post_link').val();

    // var file = media_link;
    // var format = file.split(";")[0].split('/')[1];

    // SORT JPEG

    // console.log('format', format);

    // var converted_link = dataURLtoFile(file, "." + format);

    var f_pin = "";

    if (window.Android) {
      f_pin = window.Android.getFPin();
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    // let url = post_link.replace(/^https?:\/\//, '');

    formData.append('title', title);
    formData.append('caption', caption);
    // formData.append('post_photo', converted_link);
    formData.append('array_upload_photo', array_upload_photo);
    formData.append('time', new Date().getTime());
    // formData.append('category', parseInt($('#category').val()));
    formData.append('category', 404);
    formData.append('f_pin', f_pin);
    formData.append('post_link', '');
    formData.append('post_check', $('#post_check').val());
    formData.append('price', $('#price').val());
    formData.append('stock', $('#stock').val());
    formData.append('club_id', club_id);

    console.log('arr up', array_upload_photo);
    for (let i = 0; i < array_upload_photo.length; i++) {

      console.log('arr idx', '#file-input-' + array_upload_photo[i]);
      formData.append('listing_thumbnail-' + array_upload_photo[i], $('#file-input-' + array_upload_photo[i])[0].files[0]);
    }

    // if ($video_type_arr.includes(format)) {
    //   let thumb = video_thumb;
    //   let thumbFormat = file.split(";")[0].split('/')[1];
    //   let converted_thumb = dataURLtoFile(thumb, "." + thumbFormat);
    //   formData.append('thumbnail', converted_thumb);
    // }

    for (var pair of formData.entries()) {
      console.log(pair[0] + ', ' + pair[1]);
    }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {

      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        console.log(xmlHttp.responseText);
        if (xmlHttp.responseText == "Berhasil") {
          if (localStorage.lang == 0) {
            // alert("Post submitted!");
          } else {
            // alert("An error occured.");
          }

          console.log('berhasil');

          let isGrid = localStorage.getItem("is_grid");
          if (isGrid != null) {
            if (isGrid == "1") {
              window.location.href = "/gaspol_web/pages/tab3-main?f_pin=" + f_pin;
            } else {
              window.location.href = "/gaspol_web/pages/tab1-main?f_pin=" + f_pin;
            }
          } else {
            window.location = document.referrer;
          }
        } else {

          if (localStorage.lang == 0) {
            $('#form-check .modal-body').html('<h6>An error occured while posting. Please try again.</h6>');
          } else {
            $('#form-check .modal-body').html('<h6>Terjadi kesalahan saat posting. Mohon coba lagi.</h6>');
          }
          $('#form-posting').modal('toggle');
          $('#form-check').modal('toggle');
          $("#create-post").prop("disabled", false);
          console.log("Gagal nih", xmlHttp.responseText);
        }
      }
    }
    xmlHttp.open("post", "../logics/tab5/insert_new_post");
    xmlHttp.send(formData);

  }

  let maxlength = 500;
  var inputEmail = document.querySelector('#caption');

  $('#caption').on('keyup change paste input', function(e) {

    if (inputEmail.value.length > maxlength) {
      inputEmail.value = inputEmail.value.substring(0, maxlength);
    }

    this.style.height = "auto";
    this.style.height = (this.scrollHeight) + "px";

  });

  let title_max = 64;
  var inputTitle = document.querySelector('#post_title');

  $('#post_title').on('keyup change paste input', function(e) {

    if (inputTitle.value.length > title_max) {
      inputTitle.value = inputTitle.value.substring(0, title_max);
    }

  });

  $('#create-post').click(function() {
    // let isFile = document.getElementById("addFile").files.length > 0;
    // let isCaption = document.getElementById("caption").value.trim().length > 0;
    // let isTitle = document.getElementById("post_title").value.trim().length > 0;
    // let isCategory = document.getElementById("category").value != "";
    // console.log("isFile", isFile);
    // console.log("isCaption", isCaption);
    // if (isFile && isCaption && isTitle && isCategory) {
    //   $(this).prop("disabled", true);

    if (localStorage.lang == 0) {
      $('#form-posting .modal-body').html('<h6>Upload in progress...</h6>');
    } else {
      $('#form-posting .modal-body').html('<h6>Sedang mengunggah...</h6>');
    }
    $('#form-posting').modal('toggle');
    //   sendData();
    // } else {
    //   if (localStorage.lang == 0) {

    //     $('#form-check .modal-body').html('<h6>Please fill image and caption before posting.</h6>');
    //   } else {
    //     $('#form-check .modal-body').html('<h6>Mohon isi gambar dan deskripsi sebelum melakukan posting.</h6>');
    //   }
    //   // $('#form-check').modal('toggle');
    // }

    sendData();
  })

  // WORD COUNTER SCRIPT

  var count = $('#caption').val().length;
  $('#counter-word').text(count);

  $('#caption').bind('input propertychange', function() {
    var count = $('#caption').val().length;
    $('#counter-word').text(count);
  });

  // WORD COUNTER TITLE SCRIPT

  var count = $('#post_title').val().length;
  $('#counter-word-2').text(count);

  $('#post_title').bind('input propertychange', function() {
    var count = $('#post_title').val().length;
    $('#counter-word-2').text(count);
  });

  // NEW FEATURE 

  function changeProductMenu() {

    checkValid();

    if ($("#paid_post").is(':checked')) {
      $('#submenu_product').removeClass('d-none');
      $('#post_check').val('1');
    } else {
      $('#submenu_product').addClass('d-none');
      $('#post_check').val('0');
    }
  }

  // CONVERT TO RUPIAH 

  var dengan_rupiah = document.getElementById('price');
  dengan_rupiah.addEventListener('keyup', function(e) {
    dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
  });

  var stock = document.getElementById('stock');
  stock.addEventListener('keyup', function(e) {
    stock.value = formatStock(this.value, 'Rp. ');
  });

  /* Fungsi */
  function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }

  function formatStock(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
  }
</script>

</html>