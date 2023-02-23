<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $dbconn = paliolite();

    session_start();

    if(isset($_GET['f_pin'])){
        $f_pin = $_GET['f_pin'];
        $_SESSION['user_f_pin'] = $f_pin;
    }
    else if(isset($_SESSION['user_f_pin'])){
        $f_pin = $_SESSION['user_f_pin'];
    }

    // GET USER INFO

    $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
    $query->execute();
    $userData = $query->get_result()->fetch_assoc();
    $query->close();

    // GET SEARCH

    $querySearch = $_GET['query'];

    if ($querySearch == ""){

        $querySearch = null;

    }

    $searchData;
    $type = $_GET['type'];

    $sql = "SELECT USER_LIST.*, USER_LIST.F_PIN AS USER_F_PIN FROM USER_LIST WHERE FIRST_NAME LIKE '%$querySearch%' AND BE = 282 OR LAST_NAME LIKE '%$querySearch%' AND BE = 282";

    $query = $dbconn->prepare($sql);
    $query->execute();
    $searchDataUser = $query->get_result();

    $sql = "SELECT * FROM TKT WHERE CLUB_NAME LIKE '%$querySearch%'";

    $query = $dbconn->prepare($sql);
    $query->execute();
    $searchDataTKT = $query->get_result();  

    if ($type != 2){

        while ($row = $searchDataUser->fetch_array(MYSQLI_ASSOC))
        {
            $searchData[] = $row;
        }

        $query->close();    
    }

    if ($type != 1){

        while ($row = $searchDataTKT->fetch_array(MYSQLI_ASSOC))
        {
            $searchData[] = $row;
        }

        $query->close();  
    }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gaspol Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/tab1-style.css?random=<?= time(); ?>" />
    <link rel="stylesheet" href="../assets/css/tab3-style.css?v=<?php echo time(); ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <style>

      /* FOR HTML NOT OFFSIDE */

      html,
      body {
        max-width: 100%;
        overflow-x: hidden;
        font-family: 'Poppins' !important;
      }

      a:not([href]):not([class]), a:not([href]):not([class]):hover {
        color: grey;
      }

      .navbar a{
          color: grey;
          text-decoration: none;
          font-size: 14px;
          padding-bottom: 8px;
      }

      .activeNav{
        color: black !important;
        border-bottom: 3px solid darkorange !important;
      }

      .dropdown-toggle::after{
          display: none !important;
      }

      .modal-dialog {
        position:fixed;
        top:auto;
        right:auto;
        left:auto;
        bottom:0;
        margin: 0;
        padding: 0;
        width: inherit;
        margin-bottom: -10px;
    }
        
    .modal-content{
        height: 260px;
        padding-top: 20px;
        border: none;
    }

    .form-control:focus {
        box-shadow: none;
    }

    </style>

  </head>

    <body>

    <div class="fixed-top p-3" style="background-color: white; z-index: 1040">
        <div class="row">
            <div class="col-2">
                <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
            </div>
            <div class="col-9" style="border-bottom: 1px solid lightgrey">
                <div class="input-group rounded">

                    <img src="../assets/img/social/search.svg" style="width: 27px; height: 27px; opacity: 0.6">

                    <?php if (isset($querySearch)): ?>
                        <input id="search" type="search" class="form-control" style="padding-left: 25px; border: none; border-radius: 0px !important" placeholder="Search" aria-label="Search" aria-describedby="search-addon" value="<?= $querySearch ?>" onkeydown="saveQuery()"/>
                    <?php else: ?>
                        <input id="search" type="search" class="form-control" style="padding-left: 25px; border: none; border-radius: 0px !important" placeholder="Search" aria-label="Search" aria-describedby="search-addon" onkeydown="saveQuery()"/>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <?php if (!isset($querySearch)): ?>

        <div class="section-title m-3 row" style="margin-top: 80px !important">
            <div class="row gx-0">
                <div class="col-6">
                    <p><b id="previous-search" style="font-size: 14px">Previous Search</b></p>
                </div>
                <div class="col-6 text-end" onclick="clearAll()">
                    <div class="text-danger"><p style="font-size: 14px" id="clear-all">Clear all</p></div>
                </div>
            </div>

        <!-- LOOP SEARCH HISTORY -->

        <div id="section-history" class="mt-1">
            <p id="no-history" class="text-secondary">No previous search</p>
        </div>

    </div>

    <?php else: ?>

        <!-- LOOP QUERY BY SEARCH IF EXIST -->

        <div class="row p-2 fixed-top" style="padding-top: 70px !important; background-color: white">
            <div class="navbar">
                <div class="col-6 text-center">
                    <a id="navAccount" class="activeNav" onclick="changeAccount()"><b id="account-text" style="font-size: 16px">Account</b></a>
                </div>
                <div class="col-6 text-center">
                    <a id="navProduct" onclick="changeProduct()"><b id="product-text" style="font-size: 16px">Product</b></a>
                </div>
            </div>
            <div class="row pt-4 pb-2" id="switch_result" >
                <div class="container">

                    <?php if ($_GET['type'] == 1): ?>

                        <span id="user-text" onclick="filterUser(1)" style="margin-left: 10px; font-size: 14px; background-color: #ff6b00; color: white; 
                        border-radius: 20px; width:auto; padding: 10px; padding-left: 15px; padding-right: 15px; margin-right: 10px">
                        User (<?= mysqli_num_rows($searchDataUser) ?>)<img src="../assets/img/social/Property 1=line.svg" class="rounded-circle" 
                        style="height: 15px; width: 15px; margin-left: 10px; background-color: white; padding: 2px"></span>

                    <?php else: ?>

                        <span id="user-text" onclick="filterUser(0)" style="margin-left: 10px; font-size: 14px; background-color: #dedede; border-radius: 20px; 
                        width:auto; padding: 10px; padding-left: 15px; padding-right: 15px; margin-right: 10px">
                        User (<?= mysqli_num_rows($searchDataUser) ?>)</span>
                    
                    <?php endif; ?>

                    <?php if ($_GET['type'] == 2): ?>

                        <span id="club-text" onclick="filterClub(1)" style="font-size: 14px; color: white; background-color: #ff6b00; border-radius: 20px; 
                        width:auto; padding: 10px; padding-left: 15px; padding-right: 15px; margin-right: 10px">
                        Club (<?= mysqli_num_rows($searchDataTKT) ?>)<img src="../assets/img/social/Property 1=line.svg" class="rounded-circle" 
                        style="height: 15px; width: 15px; margin-left: 10px; background-color: white; padding: 2px"></span>
                    
                    <?php else: ?>

                        <span id="club-text" onclick="filterClub(0)" style="font-size: 14px; background-color: #dedede; border-radius: 20px; width:auto; 
                        padding: 10px; padding-left: 15px; padding-right: 15px; margin-right: 10px">
                        Club (<?= mysqli_num_rows($searchDataTKT) ?>)</span>
                    
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="section-account m-3" style="margin-top: 200px !important">

            <?php if (count($searchData) > 0): ?>

                <?php foreach($searchData as $s): ?>


                    <?php if (isset($s['CLUB_NAME'])): ?>

                        <div class="row mt-3 pb-3" style="border-bottom: 1px solid #e7e7e7" onclick="visitClub('<?= $s['ID'] ?>')">
                            <div class="col-3">

                                <?php if ($s['PROFILE_IMAGE']): ?>
                                    <img class="rounded-circle" style="border: 2px solid #ff6b00; height: 65px; width: 65px; object-position: center; object-fit: cover" src="../images/<?= $s['PROFILE_IMAGE'] ?>">
                                <?php else: ?>
                                    <img class="rounded-circle" style="border: 2px solid #ff6b00; height: 65px; width: 65px; object-position: center; object-fit: cover" src="../assets/img/tab5/no-avatar.jpg">
                                <?php endif; ?>
                                    
                            </div>
                            <div class="col-9">
                                <div style="font-size: 12px; color: grey">IMI TKT</div>
                                <div class="mt-1" style="font-size: 13px"><b><?= $s['CLUB_NAME'] ?></b></div>
                                <div class="row gx-0 mt-2">

                                    <?php 

                                        $l_pin = $s['ID'];
                                    
                                        // COUNT MEMBERS
                                        $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP WHERE STATUS = 1 AND CLUB_CHOICE = '".$l_pin."'");
                                        $query->execute();
                                        $members = $query->get_result();
                                        $query->close();

                                        // COUNT FOLLOWERS
                                        $query = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE TKT_ID = '".$l_pin."'");
                                        $query->execute();
                                        $followers = $query->get_result();
                                        $query->close();

                                    ?>

                                    <div class="col-4" style="font-size: 12px; color: grey">
                                        <img src="../assets/img/social/member.svg" style="object-fit: cover; width: 17px; height: 17px; margin-right: 5px; opacity: 0.8">
                                        <?= mysqli_num_rows($members) ?> Members
                                    </div>
                                    <div class="col-1 p-0 text-secondary" style="margin-top: -3px">•</div>
                                    <div class="col-5" style="font-size: 12px; color: grey">
                                        <img src="../assets/img/social/follower.svg" style="width: 17px; height: 17px; margin-right: 5px; opacity: 0.8">
                                        <?= mysqli_num_rows($followers) ?> Followers
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="row mt-3 pb-3" style="border-bottom: 1px solid #e7e7e7" onclick="visitProfile('<?= $s['F_PIN'] ?>')">
                            <div class="col-3">

                                <?php if ($s['IMAGE']): ?>
                                    <img class="rounded-circle" style="border: 2px solid #ff6b00; height: 65px; width: 65px; object-position: center; object-fit: cover" src="http://108.136.138.242/filepalio/image/<?= $s['IMAGE'] ?>">
                                <?php else: ?>
                                    <img class="rounded-circle" style="border: 2px solid #ff6b00; height: 65px; width: 65px; object-position: center; object-fit: cover" src="../assets/img/tab5/no-avatar.jpg">
                                <?php endif; ?>
                                    
                            </div>
                            <div class="col-9">

                                <?php

                                    // CHECK KTA
                                    $query = $dbconn->prepare("SELECT * FROM KTA WHERE F_PIN = '".$l_pin."'");
                                    $query->execute();
                                    $kta = $query->get_result()->fetch_assoc();
                                    $query->close();  
                                        
                                ?>
                                    
                                <?php if ($kta['STATUS_ANGGOTA'] == 1): ?>

                                    <div style="font-size: 12px; color: grey">KTA Pro Member</div>

                                <?php elseif ($kta['STATUS_ANGGOTA'] == 0): ?>

                                    <div style="font-size: 12px; color: grey">KTA Mobility Member</div>

                                <?php else: ?>

                                    <div style="font-size: 12px; color: grey">-</div>

                                <?php endif; ?>

                                <div class="mt-1" style="font-size: 13px"><b><?= $s['FIRST_NAME']." ".$s['LAST_NAME'] ?></b></div>
                                <div class="row gx-0 mt-2">

                                    <?php 

                                        $l_pin = $s['USER_F_PIN'];
                                    
                                        // COUNT POST
                                        $query = $dbconn->prepare("SELECT * FROM POST WHERE F_PIN = '".$l_pin."'");
                                        $query->execute();
                                        $post = $query->get_result();
                                        $query->close();

                                        // COUNT FOLLOWERS
                                        $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE L_PIN = '".$l_pin."'");
                                        $query->execute();
                                        $followers = $query->get_result();
                                        $query->close();

                                    ?>

                                    <div class="col-4" style="font-size: 12px; color: grey">
                                        <img src="../assets/img/social/image.svg" style="object-fit: cover; width: 17px; height: 17px; margin-right: 5px; opacity: 0.8">
                                        <?=  mysqli_num_rows($post) ?> Posts
                                    </div>
                                    <div class="col-1 p-0 text-secondary" style="margin-top: -3px">•</div>
                                    <div class="col-5" style="font-size: 12px; color: grey">
                                        <img src="../assets/img/social/follower.svg" style="width: 17px; height: 17px; margin-right: 5px; opacity: 0.8">
                                        <?= mysqli_num_rows($followers) ?> Followers
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                    
                <?php endforeach; ?>

            <?php else: ?>

                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <h5 class="text-secondary" id="no-result" style="font-size: 15px !important">No result available</h5>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <div class="section-product d-none" style="margin-top: 160px">
            
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <h5 class="text-secondary" id="coming-soon" style="font-size: 15px !important">Coming Soon</h5>
                </div>
            </div>

        </div>

    <?php endif;?>

    <div class="modal fade" id="modalClearAll" tabindex="-1" aria-labelledby="modalClearAllLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
                </div>
            </div>
            <div class="modal-content" style="height: 300px">
                <div class="modal-body p-4">
                    <h5 style="font-weight: 700; font-size: 18px" id="clear-title">Clear Search History</h5>
                    <p class="text-secondary" id="clear-desc">You are going to remove all of the search history. Tap the Clear Now button to proceed.</p>
                    <button class="mt-3" style="width: 100%; border-radius: 20px; color: white; background-color:#ff6b00; border:none; padding: 10px; font-size: 14px" onclick="confirmClearAll()" id="clear-button"><b>Clear Now</b></button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

    if(localStorage.lang == 1){

        $('#previous-search').text('Pencarian Sebelumnya');
        $('#no-history').text('Tidak ada riwayat pencarian.');
        $('#clear-all').text('Hapus Semua');
        $('#clear-title').text('Hapus Riwayat Pencarian');
        $('#clear-desc').text('Anda ingin menghapus semua riwayat pencarian. Pencet Hapus Sekarang untuk memprosesnya.');
        $('#clear-button').text('Hapus Sekarang');
        $('#search').attr('placeholder','Pencarian');
        $('#account-text').text('Akun');
        $('#product-text').text('Produk');
        $('#no-result').text('Pencarian tidak ditemukan');
        $('#coming-soon').text('Segera Hadir');

        <?= mysqli_num_rows($searchDataUser) ?>

        $('#user-text').text('Pengguna (<?= mysqli_num_rows($searchDataUser) ?>)');
        $('#club-text').text('Klub (<?= mysqli_num_rows($searchDataTKT) ?>)');


    }

    var history_search = []; 
    var old_history = localStorage.getItem('array_search_gaspol');

    if (old_history){

        history_search = old_history.split(",");

    }

    var F_PIN = "<?= $_GET['f_pin'] ?>";

    if (history_search.length != 0){

        $('#no-history').hide();

        for (let i = 0; i < history_search.length; i++) {

            var html = `<div class="row gx-0 mt-2">
                            <div class="col-2" onclick="goToPage('`+history_search[i]+`')">
                                <img src="../assets/img/social/reload.svg" style="width: 23px; height: 23px; opacity: 0.5">
                            </div>
                            <div class="col-8" onclick="goToPage('`+history_search[i]+`')">
                                <p style="font-size: 15px">`+history_search[i]+`</p> 
                            </div>
                            <div class="col-2 text-end" onclick="deleteSearch('`+history_search[i]+`')">
                                <img src="../assets/img/social/trash.svg" style="width: 27px; height: 27px">
                            </div>
                        </div>`;
 
            $('#section-history').append(html);
        
        }

    }

    function goToPage(link){

        var keyword = link;

        window.location.href = "gaspol_search?f_pin=".concat(F_PIN)+"&query="+link;

    }

    function saveQuery(){

        var keyword = $('#search').val();

        if(event.key === 'Enter') {
            
            if (keyword != ""){

                history_search.push(keyword);
                localStorage.setItem('array_search_gaspol', history_search);

            }

            window.location.href = "gaspol_search?f_pin=".concat(F_PIN)+"&query="+keyword;
        }


    }

    function changeAccount(){

        $('.section-account').removeClass('d-none');
        $('.section-product').addClass('d-none');

        $('#navAccount').addClass('activeNav');
        $('#navProduct').removeClass('activeNav');

        $('#switch_result').show();

    }

    function changeProduct(){

        $('.section-account').addClass('d-none');
        $('.section-product').removeClass('d-none');

        $('#navAccount').removeClass('activeNav');
        $('#navProduct').addClass('activeNav');

        $('#switch_result').hide();

    }

    function deleteSearch(query){

        var keyword = query;

        history_search = history_search.filter(e => e !== keyword);
        localStorage.setItem('array_search_gaspol', history_search);

        window.location.href = "gaspol_search?f_pin=".concat(F_PIN);

    }

    function clearAll(){

        $('#modalClearAll').modal('show');
        
    }

    function confirmClearAll(){

        history_search = [];
        localStorage.removeItem('array_search_gaspol');

        window.location.href = "gaspol_search?f_pin=".concat(F_PIN);
    }

    function visitProfile(l_pin){

        window.location.href = "tab3-profile?f_pin=".concat(F_PIN)+"&l_pin="+l_pin;

    }
    
    function visitClub(tkt_id){

        window.location.href = "gaspol_club?f_pin=".concat(F_PIN)+"&l_pin="+tkt_id;

    }

    function filterUser(is_active){

        var query = $('#search').val();

        if (is_active == 1){
            window.location.href = "gaspol_search?f_pin=".concat(F_PIN)+"&query="+query;
        }else{
            window.location.href = "gaspol_search?f_pin=".concat(F_PIN)+"&query="+query+"&type=1";
        }

    }

    function filterClub(is_active){

        var query = $('#search').val();

        if (is_active == 1){
            window.location.href = "gaspol_search?f_pin=".concat(F_PIN)+"&query="+query;
        }else{
            window.location.href = "gaspol_search?f_pin=".concat(F_PIN)+"&query="+query+"&type=2";
        }

    }

    function closeAndroid(){

        // if (window.Android){

        // window.Android.finishGaspolForm();

        // }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

        // window.webkit.messageHandlers.finishGaspolForm.postMessage({
        //     param1: ""
        // });
        // return;

        // }else{

        // history.back();

        // }

        window.location.href = "tab1-main?f_pin=".concat(F_PIN);
    }

</script>