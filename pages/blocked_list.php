<?php

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();
session_start();

// GET USER FROM SESSION

if (isset($_GET['f_pin'])) {
  $id_user = $_GET['f_pin'];
  $_SESSION['user_f_pin'] = $id_user;
} else {
  $id_user = $_SESSION["user_f_pin"];
}

// CHECK USER

// if (!isset($id_user)) {
//   die("ID User Tidak Diset.");
// }

// SELECT USER FOLLOWING

// IF SEARCH IS ACTIVE

if (isset($_GET['query'])) {

  $search_query = $_GET['query'];

  $query = $dbconn->prepare("SELECT * FROM BLOCK_USER LEFT JOIN USER_LIST ON BLOCK_USER.F_PIN = 
                              USER_LIST.F_PIN WHERE BLOCK_USER.F_PIN = '$id_user' AND USER_LIST.FIRST_NAME LIKE '%$query%' 
                              GROUP BY BLOCK_USER.L_PIN ORDER BY (USER_LIST.FIRST_NAME || ' ' || USER_LIST.LAST_NAME)");
  $query->execute();
  $user_blocked = $query->get_result();
  $query->close();
} else {

  $query = $dbconn->prepare("SELECT * FROM BLOCK_USER LEFT JOIN USER_LIST ON BLOCK_USER.L_PIN = 
                              USER_LIST.F_PIN WHERE BLOCK_USER.F_PIN = '$id_user' GROUP BY BLOCK_USER.L_PIN 
                              ORDER BY (USER_LIST.FIRST_NAME || ' ' || USER_LIST.LAST_NAME)");
  $query->execute();
  $user_blocked = $query->get_result();
  $query->close();

  // print_r($user_blocked);

}

?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Gaspol Blocked</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href="../assets/css/tab5-collection-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
</head>

<body class="bg-white-background">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light bg-purple" style="background-color: #f66701">
    <div class="container">
      <a onclick="closeAndroid()">
        <img src="../assets/img/tab5/Back-(White).png" class="navbar-back-white">
      </a>
      <p class="navbar-title"></p>
      <script>
      </script>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="blocked_list" method="GET" style="width: 95%;">

          <?php
          $query = "";
          if (isset($_REQUEST['query'])) {
            $query = $_REQUEST['query'];
          }
          ?>

          <input id="query" placeholder="Search" type="text" class="search-query" name="query">
          <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill.png">
          <img id="voice-search" onclick="voiceSearch()" src="../assets/img/icons/Voice-Command.png">
        </form>
      </div>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <!-- <img src="../assets/img/tab5/Search-(White).png" class="search-white-right"> -->
      </div>
    </div>
  </nav>

  <!-- SECTION RECENT ACTIVITIES -->

  <div class="section-recent-activities">
    <div class="container recent-activities-title">
      <p class="text-purple small-text"></p>
    </div>
    <div class="container" id="block-list">

      <!-- IF USER HAVE FOLLOWERS -->

      <?php if (mysqli_num_rows($user_blocked) > 0) : ?>

        <?php foreach ($user_blocked as $block) : ?>

          <?php $images = explode('|', $block['IMAGE']); ?>

          <div class="row small-text one-followers" id="user-blocked-<?= $block['L_PIN'] ?>">
            <div class="col-1 col-md-1 col-lg-1" style="margin-right: 10px;">
              <a href="tab3-profile.php?store_id=<?= $block['L_PIN'] ?>&f_pin=<?= $id_user ?>">
                <?php if (empty($images[0])) : ?>
                  <img src="../assets/img/ic_person_boy.png" class="followers-ava" style="object-fit: cover; height: 30px; border-radius: 50%">
                <?php else : ?>
                  <img src="http://108.136.138.242/filepalio/image/<?= $images[0] ?>" class="followers-ava" style="object-fit: cover; height: 30px; border-radius: 50%">
                <?php endif; ?>
              </a>
            </div>
            <div class="col-7 col-md-7 col-lg-7" style="margin-right: 20px;">
              <a href="tab3-profile.php?store_id=<?= $block['L_PIN'] ?>&f_pin=<?= $id_user ?>">
                <div><?= $block['FIRST_NAME'] . " " . $block['LAST_NAME'] ?></div>
              </a>
              <!-- <div class="smallest-text text-grey"><?= date('d/m/y', $block['FOLLOW_DATE'] / 1000) ?></div> -->
            </div>
            <div class="col-3 col-md-3 col-lg-3">
              <input type="hidden" name="f_pin" value="<?= $id_user ?>">
              <input type="hidden" name="shop_id" value="<?= $block['L_PIN'] ?>">
              <button class="btn-follow" onclick="openModal1('<?= $block['FIRST_NAME'] ?>','<?= $block['L_PIN'] ?>')" type="button" style="padding-left:5px; padding-right:5px; border: 1px solid #f66701; color: #f66701" data-toggle="modal" data-target="#unfollowModal1"></button>
            </div>
          </div>

        <?php endforeach; ?>


      <?php else : ?>

        <p class="text-center small-text mt-5 no-blocked"></p>

      <?php endif; ?>

    </div>
  </div>
  </div>

  <!-- UNBLOCK CONFIRMATION MODAL -->

  <div class="modal fade" id="unfollowModal1" tabindex="-1" role="dialog" aria-labelledby="unfollowModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="height:100%">
        <div class="modal-body">
          <span style="font-size: 14px" class="are-u-sure"></span><span style="font-size: 14px" id="text-shop-name1"></span><span style="font-size: 14px">?</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()" style="font-size: 14px; color: #000000; border: 1px solid #f66701; background-color: #FFFFFF" data-dismiss="modal"></button>
          <!-- <form action="../logics/unblock_user_list" method="POST"> -->
          <input type="hidden" name="f_pin" value="<?= $id_user ?>">
          <input type="hidden" name="shop_id" id="shop_id" value="">
          <button type="button" class="btn btn-yes" style="font-size: 14px; color: #FFFFFF; background-color: #f66701" onclick="unblockUser();"></button>
          <!-- </form> -->
        </div>
      </div>
    </div>
  </div>


</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>
  //  SCRIPT CHANGE LANGUAGE

  // $(document).ready(function() {
  //   function changeLanguage() {

  //     var lang = localStorage.lang;
  //     change_lang(lang);

  //   }
  //   changeLanguage();
  //   $('body').show();
  // });




  function changeLanguage() {

    // var lang = localStorage.lang;
    // console.log('change', lang);
    // change_lang(lang);
    if (localStorage.lang == 0) {
      $('.navbar-title').text('Blocked List');
      $('.recent-activities-title > p').text('Recent Blocked User');
      $('.btn-follow').text('Unblock');

      // $('.modal-body > span').text('Pengguna yang diblokir baru ini');
      $('.btn-secondary').text("No");
      $('.btn-yes').text("Yes");
      $('.are-u-sure').text("Are you sure to unblock ");

      $('.no-blocked').text('You havent blocked anyone yet.');
    } else {
      $('.navbar-title').text('Daftar Blokir');
      $('.recent-activities-title > p').text('Pengguna yang diblokir baru ini');
      $('.btn-follow').text('Buka Blokir');

      // $('.modal-body > span').text('Pengguna yang diblokir baru ini');
      $('.btn-secondary').text("Tidak");
      $('.btn-yes').text("Iya");
      $('.are-u-sure').text("Apakah anda yakin untuk membuka blokir ");

      $('.no-blocked').text('Anda belum memblokir siapapun.');
    }


  }

  window.onload = function() {
    // console.log('pageload');
    // changeLanguage();
    changeLanguage();
    // $('body').show();
  }

  window.addEventListener('storage', function() {
    changeLanguage();
  })

  // OPEN & CLOSE MODAL

  function openModal1(name, code) {
    $('#unfollowModal1').modal('show');
    $('#text-shop-name1').text(name);
    $('#shop_id').val(code);
  }

  function closeModal() {
    $('#unfollowModal1').modal('hide');
  }

  // SCRIPT SEARCH

  $('#searchBar').attr('style', 'display:none !important');

  $(".search-white-right").click(function() {
    $('.navbar-title').hide();
    $('#searchBar').attr('style', 'display:block !important');
  });

  <?php
  if (isset($_GET['query'])) {
    echo ("
      $('.navbar-title').hide();
      $('#searchBar').attr('style','display:block !important');

      $('#query').val(localStorage.getItem('search_keyword'));
      $('#delete-query').removeClass('d-none');
      ");
  }
  ?>

  // FUNCTION SAVE SEARCH

  $('#query').on('change', function() {
    localStorage.setItem("search_keyword", this.value);
  });

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function() {
    $('#query').val('');
    // localStorage.setItem("search_keyword", "");
    // $('#delete-query').addClass('d-none');
    window.location = 'tab5-following.php';
  })

  $('#query').keyup(function() {

    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }

  })

  // FUNCTION VOICE SEARCH

  function voiceSearch() {
    Android.toggleVoiceSearch();
  }

  function submitVoiceSearch(searchQuery) {
    $('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
  }

  function closeAndroid() {

    if (window.Android) {

      window.Android.finishGaspolForm();

    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

      window.webkit.messageHandlers.finishGaspolForm.postMessage({
        param1: ""
      });
      return;

    } else {

      history.back();

    }
  }

  function unblockUser() {
    let l_pin = $('#shop_id').val();

    let f_pin = '';
    if (window.Android) {
      f_pin = window.Android.getFPin();
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    let formData = new FormData();
    formData.append('f_pin', f_pin);
    formData.append('shop_id', l_pin);

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        if (xmlHttp.responseText === "Unblock success") {
          if (window.Android) {

            window.Android.blockUser(l_pin, false);

          } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

            window.webkit.messageHandlers.blockUser.postMessage({
              param1: l_pin,
              param2: false
            });

          }
          $('#unfollowModal1').modal('hide');
          $('#user-blocked-' + l_pin).remove();

          if ($('.one-followers').length === 0) {
            let text = '';

            if (localStorage.lang == 0) {
              text = "You haven't blocked anyone yet.";
            } else {
              text = "Anda belum memblokir siapapun."
            }
            $('#block-list').html('<p class="text-center small-text mt-5 no-blocked">' + text + '</p>');
          }
        }
      }
    }
    xmlHttp.open("post", "/gaspol_web/logics/unblock_user_list");
    xmlHttp.send(formData);
  }
</script>

</html>