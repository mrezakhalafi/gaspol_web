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

    if (isset($_GET['l_pin'])){

        $f_pin = $_GET['l_pin'];

        // CHECK IF TKT OR GASPOL CLUB 

        $type = 1;

        if ($_GET['type'] == 2){
            $type = 2;

            $query = $dbconn->prepare("SELECT * FROM TKT_MASYARAKAT LEFT JOIN PROVINCE ON TKT_MASYARAKAT.PROVINCE = PROVINCE.PROV_ID WHERE ID = '$f_pin'");
            $query->execute();
            $tktData = $query->get_result()->fetch_assoc();
            $query->close();

        }else{
            $type = 1;

            $query = $dbconn->prepare("SELECT * FROM TKT LEFT JOIN PROVINCE ON TKT.PROVINCE = PROVINCE.PROV_ID WHERE ID = '$f_pin'");
            $query->execute();
            $tktData = $query->get_result()->fetch_assoc();
            $query->close();

            $adminTKT = $tktData['F_PIN'];
        }

    }else{

        $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
        $query->execute();
        $userData = $query->get_result()->fetch_assoc();
        $query->close();

        die("Masukan L_PIN (TKT.ID) dan TYPE (1=TKT, 2=GASPOL");

    }
    

    // GET FOLLOWERS

    $query = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE TKT_ID = '$f_pin'");
    $query->execute();
    $followers = $query->get_result();
    $query->close();

    // GET MEMBER

    $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP WHERE CLUB_CHOICE = '$f_pin' AND STATUS = 1");
    $query->execute();
    $member = $query->get_result();
    $query->close();

    // GET POST

    $query = $dbconn->prepare("SELECT POST_SHARE.*, POST.*, POST.F_PIN AS POST_F_PIN, USER_LIST.*, POST.CREATED_DATE AS CA FROM POST_SHARE LEFT JOIN POST ON POST_SHARE.POST_ID = POST.POST_ID LEFT JOIN USER_LIST ON USER_LIST.F_PIN = POST.F_PIN WHERE POST.EC_DATE IS NULL AND CLUB_ID = '".$f_pin."'");
    $query->execute();
    $post = $query->get_result();
    $query->close();

    // // GET EVENT

    // $query = $dbconn->prepare("");
    // $query->execute();
    // $event = $query->get_result();
    // $query->close();

    // GET PROVINCE

    // $query = $dbconn->prepare("SELECT * FROM USER_LIST_EXTENDED_GASPOL LEFT JOIN PROVINCE ON USER_LIST_EXTENDED_GASPOL.PROVINCE = PROVINCE.PROV_ID WHERE F_PIN = '$f_pin'");
    // $query->execute();
    // $userDataExtend = $query->get_result()->fetch_assoc();
    // $query->close();

    // CHECK KTA

    $query = $dbconn->prepare("SELECT * FROM KTA LEFT JOIN REGISTRATION_PAYMENT ON KTA.NO_ANGGOTA =  REGISTRATION_PAYMENT.REF_ID WHERE KTA.F_PIN = '$f_pin'");
    $query->execute();
    $checkKTA = $query->get_result()->fetch_assoc();
    $query->close();

    if (isset($checkKTA)){

        if ($checkKTA['STATUS_ANGGOTA'] == 0){
        $statusKTA = 0;
        }else{
        $statusKTA = 1;
        }

    }

    // CHECK KIS

    $query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU =  REGISTRATION_PAYMENT.REF_ID WHERE KIS.F_PIN = '$f_pin'");
    $query->execute();
    $checkKIS = $query->get_result()->fetch_assoc();
    $query->close();

    // GET CLUB

    $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP LEFT JOIN TKT ON TKT.ID =  CLUB_MEMBERSHIP.CLUB_CHOICE WHERE CLUB_MEMBERSHIP.F_PIN = '$f_pin'");
    $query->execute();
    $getClub = $query->get_result()->fetch_assoc();
    $query->close();

    if (!isset($getClub)){
        $getClub = "-";
    }

    // FOR PADDING DIV BASIC ACCOUNT

    $padding = 0;

    if(!isset($checkKTA)){
        $padding = 1;
    }

    // CHECK IS BLOCKED

    $query = $dbconn->prepare("SELECT * FROM BLOCK_USER WHERE F_PIN = '".$_GET['f_pin']."' AND L_PIN = '".$f_pin."'");
    $query->execute();
    $isBlock = $query->get_result()->fetch_assoc();
    $query->close();

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
        font-weight: 600;
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

    </style>

  </head>

    <body>

    <div class="fixed-top m-3">
        <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">

        <?php if (isset($_GET['l_pin'])): ?>
            <img class="float-end" src="../assets/img/social/Property 1=vertical.svg" style="width: 30px; height: 30px" onclick="reportUserDot()">
        <?php endif; ?>
    </div>

    <div class="section-information">
        <div class="row">
            <div class="cover-image">

                <?php if ($tktData['PROFILE_IMAGE']): ?>
                    <img class="shadow-lg" style="height: 125px; width: 100%; background-color: grey; filter: blur(5px); object-position: center; object-fit: cover" src="../images/<?= $tktData['PROFILE_IMAGE'] ?>">
                <?php else: ?>
                    <div class="shadow-lg" style="height: 125px; width: 100%; background-color: grey; filter: blur(5px)"></div>
                <?php endif; ?>

            </div>
        </div>
        <div class="row" style="margin-top: -50px">
            <div class="profile-image text-center">

                <?php if ($tktData['PROFILE_IMAGE']): ?>
                    <img class="rounded-circle" style="object-fit: cover; border: 2px solid #ff6b00; width: 100px; height: 100px; filter: blur(0px)" src="../images/<?= $tktData['PROFILE_IMAGE'] ?>">
                <?php else: ?>
                    <img class="rounded-circle" style="object-fit: cover; border: 2px solid #ff6b00; width: 100px; height: 100px; filter: blur(0px)" src="../assets/img/tab5/no-avatar.jpg">
                <?php endif; ?>

            </div>
        </div>
        <div class="row mt-2">
            <div class="profile-name text-center">
                <img src="../assets/img/social/Property 1=on (copy 1).svg" style="width: 15px; height: 15px; margin-right: 5px">

                <?php 
                
                if ($type == 1){ ?>

                    <small style="font-size: 12px" class="text-secondary">IMI TKT</small><br />

                <?php 
                }else if($type == 2){ ?>

                    <small style="font-size: 12px" class="text-secondary">Gaspol Club</small><br />

                <?php
                }

                ?>

                <b><?= $tktData['CLUB_NAME'] ?></b>
            </div>
        </div>
        <div class="row mt-4 mb-4">

            <?php if (isset($_GET['l_pin'])): ?>

                <div class="col-4 text-center" onclick="followersOthers()">
                    <div style="font-size: 14px"><b id="followers_num"><?= mysqli_num_rows($followers) ?></b></div>
                    <div id="user_followers" style="font-size: 12px">Followers</div>
                </div>

            <?php else: ?>

                <div class="col-4 text-center" onclick="followers()">
                    <div style="font-size: 14px"><b id="followers_num"><?= mysqli_num_rows($followers) ?></b></div>
                    <div id="user_followers" style="font-size: 12px">Followers</div>
                </div>

            <?php endif; ?>

            <?php if (isset($_GET['l_pin'])): ?>

                <div class="col-4 text-center" onclick="memberOthers()">
                    <div style="font-size: 14px"><b id="member_num"><?= mysqli_num_rows($member) ?></b></div>
                    <div id="user_member" style="font-size: 12px">Member</div>
                </div>

            <?php else: ?>


                <div class="col-4 text-center" onclick="member()">
                    <div style="font-size: 14px"><b><?= mysqli_num_rows($member) ?></b></div>
                    <div id="user_member" style="font-size: 12px">Member</div>
                </div>

            <?php endif; ?>


            <div class="col-4 text-center">
                <div style="font-size: 14px"><b><?= mysqli_num_rows($post) ?></b></div>
                <div id="total_posts" style="font-size: 12px">Posts</div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 mb-3 text-center">
                <div class="row gx-0">

                <?php if (isset($_GET['l_pin'])):

                    if (!isset($isBlock)):

                        $a = $_GET['f_pin'];
                        $b = $f_pin;

                        // IS FOLLOW

                        $query = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE F_PIN = '".$a."' AND TKT_ID = '".$b."'");
                        $query->execute();
                        $isFollow = $query->get_result();
                        $query->close();

                        // IS JOIN

                        $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP WHERE STATUS != 3 AND F_PIN = '".$a."' AND CLUB_CHOICE = '".$b."'");
                        $query->execute();
                        $isJoin = $query->get_result();
                        $query->close();

                        if (mysqli_num_rows($isFollow) > 0): ?>

                            <div class="col-6">
                                <button id="btnUnfollow" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="unfollow()">Unfollow</button>
                                <button id="btnFollow" class="d-none" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="follow()">Follow</button>
                            </div>
                                                
                        <?php else: ?>

                            <div class="col-6">
                                <button id="btnFollow" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="follow()">Follow</button>
                                <button id="btnUnfollow" class="d-none" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="unfollow()">Unfollow</button>
                            </div>

                        <?php endif; ?>

                        <?php if (mysqli_num_rows($isJoin) > 0): ?>

                            <div class="col-6">
                                <button id="btnLeave" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="leaveClub()">Leave Club</button>
                                <button id="btnJoin" class="d-none" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="joinClub()">Join Club</button>
                            </div>

                        <?php else: ?>

                            <div class="col-6">
                                <button id="btnJoin" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="joinClub()">Join Club</button>
                                <button class="d-none" id="btnLeave" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="leaveClub()">Leave Club</button>
                            </div>

                    <?php endif; 
                    endif;
                    ?>

                <?php else: ?>
                    <button style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="changeProfile()">Edit Profile</button>
                <?php endif; ?>
                
                </div>
            </div>
        </div>

        <?php if (!isset($isBlock)): ?>

            <div class="row m-3">
                <div class="navbar">
                    <a id="navProfil" class="activeNav" onclick="changeProfil()">Profile</a>
                    <a id="navPostingan" onclick="changePostingan()">Posts</a>
                    <a id="navMedia" onclick="changeMedia()">Media</a>
                    <a id="navEvent" onclick="changeEvent()">Event</a>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <?php if (!isset($isBlock)): ?>

        <div class="section-profile m-4" style="font-size: 12px">

        <div class="row">
            <div class="mb-1 text-secondary">Bio</div>

            <?php if($tktData['CLUB_DESC']): ?>
                <p><b><?= $tktData['CLUB_DESC'] ?></b></p>
            <?php else: ?>
                <p style="font-weight: 700" class="not_updated_yet">Not updated yet</p>
            <?php endif; ?>

        </div>
        <div class="row">
            <div id="user_location" class="mb-1 text-secondary">Location</div>

            <?php if($tktData['PROV_NAME']): ?>
                <p><b><?= ucwords(strtolower($tktData['PROV_NAME'])) ?></b></p>
            <?php else: ?>
                <p style="font-weight: 700" class="not_updated_yet">Not updated yet</p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div id="user_category" class="mb-2 text-secondary">Categories</div>

            <?php if ($tktData['CONTENT_PREFERENCE']):

                $category = explode("|", $tktData['CONTENT_PREFERENCE']);

                foreach($category as $ct): 

                    $query = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE WHERE ID = '$ct'");
                    $query->execute();
                    $catName = $query->get_result()->fetch_assoc();
                    $query->close(); ?>

                    <div style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2"><img src="../assets/img/<?= $catName['ICON'] ?>" style="width: 15px; height: auto; margin-right: 10px"><?= $catName['CONTENT_CATEGORY'] ?></div>
                
                <?php endforeach ?>

            <?php else: ?>

                <p><b>-</b></p>

            <?php endif; ?>
            
        </div>

        <div class="row mt-3">
           <div id="user_exlink" class="mb-1 text-secondary"><img src="../assets/img/social/link.svg" style="width: 15px; height: 15px; margin-right: 5px">External Link</div>

            <?php if($tktData['CLUB_LINK']): ?>
                <p><b><?= $tktData['CLUB_LINK'] ?></b></p>
            <?php else: ?>
                <p><b>https://gaspol.co.id</b></p>
            <?php endif; ?>
        </div>

        </div>

    <?php endif; ?>

    <div class="section-postingan m-4 d-none" style="font-size: 12px">

    <?php if (mysqli_num_rows($post) > 0): ?>

        <?php foreach($post as $p): 
            
            $a = $_GET['f_pin'];
            $b = $p['POST_F_PIN'];
            $c = $p['POST_ID'];

            // CHECK IS FOLLOWED
            $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '$a' AND L_PIN = '$b'");
            $query->execute();
            $isFollowPost = $query->get_result()->fetch_assoc();
            $query->close(); 
            
            if (isset($isFollowPost)){
                $isFollowPost = 1;
            }else{
                $isFollowPost = 2;
            }

            // CHECK IS BOOKMARKED
            $query = $dbconn->prepare("SELECT * FROM POST_BOOKMARK WHERE F_PIN = '$a' AND POST_ID = '$c'");
            $query->execute();
            $isBookmarkPost = $query->get_result()->fetch_assoc();
            $query->close(); 
            
            if (isset($isBookmarkPost)){
                $isBookmarkPost = 1;
            }else{
                $isBookmarkPost = 2;
            }
            
            ?>

            <div class="product-row mt-3">
                <div>
                    <div class="timeline-post-header media">
                        <a class="d-flex pe-2" href="tab3-profile.php?l_pin=<?= $p['F_PIN'] ?>&amp;f_pin=<?= $f_pin ?>">

                            <?php if($p['IMAGE']): ?>

                                <img src="http://108.136.138.242/filepalio/image/<?= $p['IMAGE'] ?>" class="align-self-start rounded-circle mr-2 profile-pic">

                            <?php else: ?>

                                <img src="../assets/img/tab5/no-avatar.jpg" class="align-self-start rounded-circle mr-2 profile-pic">

                            <?php endif; ?>
                        </a>
                        <div class="media-body">
                            <h5 class="store-name"><?= $p['FIRST_NAME']." ".$p['LAST_NAME'] ?> <span class="text-secondary" style="font-weight: 500">di dalam</span> <?= $tktData['CLUB_NAME'] ?></h5>

                            <?php 

                                $created_date = $p["CA"];
                                $seconds = intval(intval($created_date) / 1000);
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

                            ?>

                            <p class="prod-timestamp"><?= $printed_date ?></p>
                        </div>
                        <div class="post-status d-none">
                            <img src="../assets/img/ic_public.png" height="20" width="20">
                        </div>
                        <div class="post-status d-none">
                            <img src="../assets/img/ic_user.png" height="20" width="20">
                        </div>
                        <div class="dropdown dropdown-edit">
                            <a class="post-status dropdown-toggle" data-bs-auto-close="true" id="edt-del">
                            <img src="../assets/img/social/Property 1=horizontal.svg" height="25" width="25" style="background-color:unset;" onclick="openPostContextMenu('<?= $p['POST_ID'] ?>','<?= $isFollowPost ?>','<?= $isBookmarkPost ?>','<?= $p['F_PIN'] ?>')">
                            </a>
                            <!-- <ul class="dropdown-menu" aria-labelledby="edt-del">
                            <li>
                                <a class="dropdown-item button_edit" onclick="editPost('')">Edit</a>
                            </li>
                            <li>
                                <a class="dropdown-item button_delete" onclick="deletePost('')">Delete</a>
                            </li>
                            </ul> -->
                        </div>
                        </div>
                    </div>
                    <div class="col-sm mt-3">
                        <span class="prod-desc"><?= $p['DESCRIPTION'] ?></span>
                    </div>
                    <div class="col-sm mt-2 timeline-image" onclick="postDetail('<?= $p['POST_ID'] ?>')">
                        <div id="carousel" class="carousel slide pointer-event" data-bs-touch="true" data-bs-interval="false" data-bs-ride="carousel">
                            <ol id="ci" class="carousel-indicators">

                                <?php 

                                $file_id = explode("|", $p['FILE_ID']);

                                foreach($file_id as $i => $fid): 
                                
                                    if ($i == 0): ?>

                                        <li data-bs-target="#carousel" data-bs-slide-to="<?= $i?>" class="active"></li>

                                    <?php else: ?>

                                        <li data-bs-target="#carousel" data-bs-slide-to="<?= $i?>"></li>

                                    <?php endif; ?>

                                <?php endforeach; 
                                
                                ?>

                            </ol>
                            <div class="carousel-inner">

                                <?php 


                                $file_id = explode("|", $p['FILE_ID']);

                                foreach($file_id as $i => $fid): 

                                    $ext = explode('.', $fid)[1];
                                            
                                    if ($ext == "mp4"){
                                        $type = 2;
                                    }else{
                                        $type = 1;
                                    }


                                    if ($i == 0): ?>

                                        <div class="carousel-item active">
                                            <div class="carousel-item-wrap">

                                                <?php if ($type == 1): ?>
                                                    <img src="../images/<?= $fid ?>" class="img-fluid rounded" loading="lazy">
                                                <?php elseif ($type == 2): ?>
                                                    <video autoplay muted loop class="img-fluid rounded" src="../images/<?= $fid ?>#t=0.5"></video>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    <?php else: ?>

                                        <div class="carousel-item">
                                            <div class="carousel-item-wrap">
                                                
                                                <?php if ($type == 1): ?>
                                                    <img src="../images/<?= $fid ?>" class="img-fluid rounded" loading="lazy">
                                                <?php elseif ($type == 2): ?>
                                                    <video autoplay muted loop class="img-fluid rounded" src="../images/<?= $fid ?>#t=0.5"></video>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    <?php endif; ?>

                                <?php endforeach; 

                                ?>

                            </div>
                        </div>
                    </div>

                    <?php 

                        // COUNT COMMENT
                        $query = $dbconn->prepare("SELECT * FROM POST_COMMENT WHERE POST_ID = '".$p['POST_ID']."'");
                        $query->execute();
                        $comment = $query->get_result();
                        $query->close(); 

                        // CHECK SELF LIKES
                        $query = $dbconn->prepare("SELECT * FROM POST_REACTION WHERE FLAG = 1 AND F_PIN = '".$_GET['f_pin']."' AND POST_ID = '".$p['POST_ID']."'");
                        $query->execute();
                        $isLikes = $query->get_result()->fetch_assoc();
                        $query->close(); 

                    ?>

                    <div class="col-sm mb-1 like-comment-container">
                        <div class="comment-button">
                        <a onclick="event.stopPropagation();openComment('<?= $p['POST_ID'] ?>')">
                            <img class="comment-icon" src="../assets/img/jim_comments.png" height="25" width="25">
                        </a>
                        <div class="like-comment-counter"><?= mysqli_num_rows($comment) ?></div>
                        </div>
                        
                        <?php if (isset($isLikes)): ?>

                            <div class="like-button" id="like-button-<?= $p['POST_ID'] ?>" onclick="event.stopPropagation(); likeProduct('<?= $p['POST_ID'] ?>',0)">
                            <img id="like-<?= $p['POST_ID'] ?>" src="../assets/img/jim_likes_red.png" height="25" width="25">
                            <div id="like-counter-<?= $p['POST_ID'] ?>" class="like-comment-counter"><?= $p['TOTAL_LIKES'] ?></div>

                        <?php else: ?>

                            <div class="like-button" id="like-button-<?= $p['POST_ID'] ?>" onclick="event.stopPropagation(); likeProduct('<?= $p['POST_ID'] ?>',1)">
                            <img id="like-<?= $p['POST_ID'] ?>" src="../assets/img/jim_likes.png" height="25" width="25">
                            <div id="like-counter-<?= $p['POST_ID'] ?>" class="like-comment-counter"><?= $p['TOTAL_LIKES'] ?></div>

                        <?php endif; ?>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <b class="text-secondary" id="no-post">No post available!</b>
                </div>
            </div>

        <?php endif; ?>
            
            </div>
        </div>

    <div class="section-media m-4 d-none" style="font-size: 12px">

        <div class="row mt-1">

        <?php if (mysqli_num_rows($post) > 0): ?>

            <?php foreach($post as $p): 
                
                $file_id = explode("|", $p['FILE_ID']);

                foreach($file_id as $fid):

                   $ext = explode('.', $fid)[1];
                                            
                    if ($ext == "mp4"){
                        $type = 2;
                    }else{
                        $type = 1;
                    }
                    
                    ?>

                <div class="col-4 gx-2 mt-2">

                    <?php if ($type == 1): ?>
                        
                        <img style="width: 100%; height: 120px; object-fit: cover; object-position: center; border-radius: 10px" src="../images/<?= $fid ?>" onclick="clickGrid('<?= $p['POST_ID'] ?>')">
                
                    <?php else: ?>

                        <video mute autoplay loop style="width: 100%; height: 120px; object-fit: cover; object-position: center; border-radius: 10px" src="../images/<?= $fid ?>#t=0.5" onclick="clickGrid('<?= $p['POST_ID'] ?>')">

                    <?php endif; ?>

                    </div>
                
                <?php 

                endforeach;
            endforeach; ?>

        <?php else: ?>

            <div class="col-12 mt-4 text-center">
                <b class="text-secondary" id="no-media">No media available!</b>
            </div>
        
        <?php endif; ?>

        </div>

    </div>

    <div class="section-event m-4 d-none" style="font-size: 12px">

        <div class="row mt-5">
            <div class="col-12 text-center">
                <b class="text-secondary" id="no-event">No event available!</b>
            </div>
        </div>

    </div>

    <!-- <div class="modal fade" id="modalPost" tabindex="-1" aria-labelledby="modalPostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
                </div>
            </div>
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="follow()">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="https://bprpedungan.com/wp-content/uploads/2017/08/Person-placeholder.jpg"> 
                        </div>
                        <div class="col-10">
                            <b style="font-size: 13px">Follow</b>
                        </div>
                    </div>
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="addSaved()">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="https://bprpedungan.com/wp-content/uploads/2017/08/Person-placeholder.jpg"> 
                        </div>
                        <div class="col-10">
                            <b style="font-size: 13px">Bookmark</b>
                        </div>
                    </div>
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="reportPost()">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="https://bprpedungan.com/wp-content/uploads/2017/08/Person-placeholder.jpg"> 
                        </div>
                        <div class="col-10">
                            <b style="font-size: 13px">Report Post</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- <div class="modal fade" id="modalEvent" tabindex="-1" aria-labelledby="modalEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
                </div>
            </div>
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="follow()">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="https://bprpedungan.com/wp-content/uploads/2017/08/Person-placeholder.jpg"> 
                        </div>
                        <div class="col-10">
                            <b style="font-size: 13px">Follow</b>
                        </div>
                    </div>
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="removeSaved()">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="https://bprpedungan.com/wp-content/uploads/2017/08/Person-placeholder.jpg"> 
                        </div>
                        <div class="col-10">
                            <b style="font-size: 13px">Remove Bookmark</b>
                        </div>
                    </div>
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="reportPost()">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="https://bprpedungan.com/wp-content/uploads/2017/08/Person-placeholder.jpg"> 
                        </div>
                        <div class="col-10">
                            <b style="font-size: 13px">Report Post</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <!-- <img src="../assets/img/success.png" style="width: 100px"> -->
                    <h1 class="mt-3">Join Club Pending!</h1>
                    <p class="mt-2">Your request need to be approval by club admin.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <button onclick="closeModalSuccess()" type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701; width: 30%">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDeleteSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalDeleteSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalDeleteSuccess">
                    <!-- <img src="../assets/img/success.png" style="width: 100px"> -->
                    <h1 class="mt-3">Leave Club Success!</h1>
                    <p class="mt-2">Your succesfully leave this club.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <button onclick="closeModalDelete()" type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701; width: 30%">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReport" tabindex="-1" aria-labelledby="modalReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
                </div>
            </div>
            <div class="modal-content" style="height: 220px">
                <div class="modal-body">
                    <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="reportUser('<?= $f_pin ?>')">
                        <div class="col-2 text-center">
                            <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/social/flag.svg"> 
                        </div>
                        <div class="col-10">
                            <b id="report-text" style="font-size: 13px">Report Club</b>
                        </div>
                    </div>

                    <?php if (!isset($isBlock)): ?>

                        <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="blockUserMenu()">
                            <div class="col-2 text-center">
                                <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/social/verboden.svg"> 
                            </div>
                            <div class="col-10">
                                <b id="block-text" style="font-size: 13px">Block Club</b>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="unblockUser()">
                            <div class="col-2 text-center">
                                <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/social/verboden.svg"> 
                            </div>
                            <div class="col-10">
                                <b id="unblock-text" style="font-size: 13px">Unblock Club</b>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content animate-bottom" style="height: 100%">
                <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

                    <div class="row gx-0">
                        <div class="col-12">
                            <div class="col-12 mb-3 text-center">
                                <h5>Why you want to report this content?</h5>
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
                                                <button class="btn btn-dark" type="button" onclick="reportContentSubmit()">Submit</button>
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
            <div class="modal-content animate-bottom" style="height: 100%">
                <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

                    <div class="row gx-0">
                        <div class="col-12">
                            <div class="col-12 mb-3 text-center">
                                <h5 id="why-report">Why you want to report this club?</h5>
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
                                                <label id="catext-<?= $c['ID'] ?>" class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY'] ?>
                                                </label>
                                            </div>


                                        <?php endforeach;

                                        ?>

                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button id="btnSubmit" class="btn btn-dark" style="background-color: darkorange; border: none" type="button" onclick="reportUserSubmit()">Submit</button>
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
            <div class="modal-content" style="height: 180px">
                <div class="modal-body p-4 text-center">
                    <p style="font-size: 16px" id="report-success">Report submited.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button id="btnClose" class="btn btn-dark" style="background-color: darkorange; border: none" type="button" onclick="reloadPages()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-block-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="height: 180px">
                <div class="modal-body p-4 text-center">
                    <p style="font-size: 16px" id="block-success">You blocked this club.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button id="btnClose2" class="btn btn-dark" style="background-color: darkorange; border: none" type="button" onclick="reloadPages()">Close</button>
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

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

    var F_PIN = "<?= $f_pin ?>";
    var switch_tab = localStorage.getItem('switch_tab');

    if (!switch_tab){
        switch_tab = 0;
    }

    if (switch_tab == 0){
        changeProfil();
    }
    else if(switch_tab == 1){
        changePostingan();
    }
    else if(switch_tab == 2){
        changeMedia();
    }
    else if(switch_tab == 3){
        changeEvent();
    }

    if(localStorage.getItem('lang') == 1) {
        $("#user_followers").text("Pengikut");
        $("#user_member").text("Anggota");
        $("#total_posts").text("Postingan");
        $("#btnFollow").text("Ikuti");
        $("#btnUnfollow").text("Berhenti Ikuti");
        $("#btnJoin").text("Bergabung Klub");
        $("#btnLeave").text("Keluar Klub");
        $("#navProfil").text("Profil");
        $('#navEvent').text("Acara");
        $("#navPostingan").text("Postingan");
        $("#user_location").text("Lokasi");
        $(".not_updated_yet").text("Belum diperbarui");
        $("#user_category").text("Kategori");
        $("#user_exlink").text("Tautan Eksternal");
        $("#no-post").text("Tidak ada postingan yang tersedia.");
        $("#no-media").text("Tidak ada media yang tersedia.");
        $("#no-event").text("Tidak ada acara yang tersedia.");
        $("#report-text").text("Laporkan Klub");
        $("#block-text").text("Blokir Klub");
        $("#unblock-text").text("Buka Blokir");
        $("#why-report").text("Kenapa anda ingin melaporkan klub ini?");
        $("#btnSubmit").text("Laporkan");
        $("#report-success").text("Laporan Berhasil.");
        $("#btnClose").text("Tutup");
        $("#btnClose2").text("Tutup");
        $("#block-success").text("Anda memblokir klub ini.");

        $('#catext-1').text('Penipuan');
        $('#catext-2').text('Ketelanjangan atau aktivitas seksual');
        $('#catext-3').text('Ujaran atau simbol kebencian');
        $('#catext-4').text('Penindasan atau pelecehan');
        $('#catext-5').text('Organisasi kekerasan atau berbahaya');
    }

    function changeProfil(){

        localStorage.setItem('switch_tab','0');

        $('.section-profile').removeClass('d-none');
        $('.section-postingan').addClass('d-none');
        $('.section-media').addClass('d-none');
        $('.section-event').addClass('d-none');

        $('#navProfil').addClass('activeNav');
        $('#navPostingan').removeClass('activeNav');
        $('#navMedia').removeClass('activeNav');
        $('#navEvent').removeClass('activeNav');

    }

    function changePostingan(){

        localStorage.setItem('switch_tab','1');
        
        $('.section-postingan').removeClass('d-none');
        $('.section-profile').addClass('d-none');
        $('.section-media').addClass('d-none');
        $('.section-event').addClass('d-none');

        $('#navPostingan').addClass('activeNav');
        $('#navProfil').removeClass('activeNav');
        $('#navMedia').removeClass('activeNav');
        $('#navEvent').removeClass('activeNav');

    }

    function changeMedia(){

        localStorage.setItem('switch_tab','2');
        
        $('.section-media').removeClass('d-none');
        $('.section-postingan').addClass('d-none');
        $('.section-profile').addClass('d-none');
        $('.section-event').addClass('d-none');

        $('#navMedia').addClass('activeNav');
        $('#navPostingan').removeClass('activeNav');
        $('#navProfil').removeClass('activeNav');
        $('#navEvent').removeClass('activeNav');

    }

    function changeEvent(){

        localStorage.setItem('switch_tab','3');

        $('.section-event').removeClass('d-none');
        $('.section-media').addClass('d-none');
        $('.section-postingan').addClass('d-none');
        $('.section-profile').addClass('d-none');

        $('#navEvent').addClass('activeNav');
        $('#navPostingan').removeClass('activeNav');
        $('#navProfil').removeClass('activeNav');
        $('#navMedia').removeClass('activeNav');
        
    }

    function member(){

        window.location.href = "gaspol_members?f_pin=".concat(F_PIN)

    }


    function memberOthers(){

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var l_pin = F_PIN;

        window.location.href = "gaspol_members?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    }

    function followers(){

        window.location.href = "gaspol_followers_tkt?f_pin=".concat(F_PIN)

    }

    function followersOthers(){

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var l_pin = F_PIN;

        window.location.href = "gaspol_followers_tkt?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    }

    function changeProfile(){

        window.location.href = "change-user?f_pin=".concat(F_PIN)

    }

    function likeProduct(code){

        var code = code;

        window.location.href = "comment.php?product_code="+code+"&f_pin=".concat(F_PIN)

    }

    function postDetail(code){

        var code = code;

        window.location.href = "comment.php?product_code="+code+"&f_pin=".concat(F_PIN)

    }

    function openComment(code){

        var code = code;

        window.location.href = "comment.php?product_code="+code+"&f_pin=".concat(F_PIN)

    }

    function clickGrid(code){

        var code = code;

        window.location.href = "comment.php?product_code="+code+"&f_pin=".concat(F_PIN)

    }

    // function openProfileSaved(l_pin){

    //     var f_pin = "<?= $_GET['f_pin'] ?>";
    //     var l_pin = l_pin;

    //     window.location.href = "gaspol_profile?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    // }

    function follow(checkIOS = false){

        if (window.Android) {
            if (!window.Android.checkProfile()) {
                return;
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: '',
                param2: 'follow_store'
            });
            return;

        }

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = "<?= $f_pin ?>"

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('l_pin', L_PIN);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#btnUnfollow').removeClass('d-none');
                    $('#btnFollow').addClass('d-none');

                    updateFollowers();

                }

            }
        }
        xmlHttp.open("post", "../logics/follow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    function unfollow(){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var id_unfollow = "<?= $f_pin ?>"

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('id_unfollow', id_unfollow);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#btnUnfollow').addClass('d-none');
                    $('#btnFollow').removeClass('d-none');

                    updateFollowers();

                }

            }
        }
        xmlHttp.open("post", "../logics/unfollow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    function updateFollowers(){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = "<?= $f_pin ?>"

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('l_pin', L_PIN);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                $('#followers_num').text(result);

            }
        }
        xmlHttp.open("post", "../logics/update_followers_tkt");
        xmlHttp.send(formData);

    }

    function addSaved(){



    }

    function removeSaved(){



    }

    function reportPost(){



    }

    function closeModalDelete(){

        $('#modalDeleteSuccess').modal('hide');

    }

    function closeModalSuccess(){

        $('#modalSuccess').modal('hide');

    }

    function reportUserDot(){

        $('#modalReport').modal('show');

    }

    $('#modal-category2').on('hidden.bs.modal', function (e) {
        $('html, body').css('overflow-y','');
    });

    function reportUser(l_pin, checkIOS = false){

        if (window.Android) {
            if (!window.Android.checkProfile()) {
                return;
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: l_pin,
                param2: 'follow_store'
            });
            return;

        }

        $('#modalReport').modal('hide');
        $('#modal-category2').modal('show');

        $('html, body').css('overflow-y','hidden');

    }

    function blockUserMenu(checkIOS = false) {

        if (window.Android) {
            if (window.Android.checkProfile()) {
                f_pin = window.Android.getFPin();
            } else {
                return;
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: l_pin,
                param2: 'block_user'
            });
            return;

        } else {
            f_pin = new URLSearchParams(window.location.search).get("f_pin");
        }

        var formData = new FormData();

        var f_pin = f_pin;
        var l_pin = "<?= $_GET['l_pin'] ?>"

        console.log("SSS", f_pin);

        formData.append('f_pin', f_pin);
        formData.append('l_pin', l_pin);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == "Berhasil") {

                    $('#modalReport').modal('hide');
                    $('#modal-block-success').modal('show');

                    if (window.Android) {

                        window.Android.blockUser(l_pin, true);

                    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

                        window.webkit.messageHandlers.blockUser.postMessage({
                            param1: l_pin,
                            param2: true
                        });
                        return;

                    }
                    // location.reload();
                } else {
                    alert("Block User Gagal");
                }
            }
        }

        xmlHttp.open("post", "../logics/block_user");
        xmlHttp.send(formData);
    };


    function reportUserSubmit() {

        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get("f_pin");
        }

        var formData = new FormData();

        var f_pin = f_pin;
        var f_pin_reported = "<?= $_GET['l_pin'] ?>"
        var report_category = $('input[name="report_category"]:checked').val();
        var count_report = 1 + 1;

        formData.append('f_pin', f_pin);
        formData.append('f_pin_reported', f_pin_reported);
        formData.append('report_category', report_category);
        formData.append('count_report', count_report);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == "Berhasil") {
                    $('#modal-category2').modal('hide');
                    $('#modal-report-success').modal('show');
                } else {
                    alert("Report User Gagal");
                }
            }
        }

        xmlHttp.open("post", "../logics/report_user");
        xmlHttp.send(formData);

    };

    function unblockUser() {

        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get("f_pin");
        }

        var formData = new FormData();

        var f_pin = f_pin;
        var l_pin = "<?= $_GET['l_pin'] ?>";

        console.log("SSS", f_pin);

        formData.append('f_pin', f_pin);
        formData.append('l_pin', l_pin);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == "Berhasil") {

                    $('#modalReport').modal('hide');

                    if (localStorage.lang == 0) {
                        $('#modal-block-success .modal-body>p').text('You unblocked this club.');
                    } else {
                        $('#modal-block-success .modal-body>p').text('Anda telah membuka blokir club ini.');
                    }
                    $('#modal-block-success').modal('show');

                    if (window.Android) {

                        window.Android.blockUser(l_pin, false);

                    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

                        window.webkit.messageHandlers.blockUser.postMessage({
                            param1: l_pin,
                            param2: false
                        });
                        return;

                    }
                    // location.reload();
                } else {
                    alert("Block User Failed");
                }
            }
        }

        xmlHttp.open("post", "../logics/unblock_user");
        xmlHttp.send(formData);
    }

    function reloadPages() {
        location.reload();
    }

    var ref_id_global;

    function joinClub(){

        if (window.Android) {
            if (!window.Android.checkProfile()) {
                return;
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: l_pin,
                param2: 'follow_store'
            });
            return;
        }

        var fd = new FormData();

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var date = new Date();
        var club_type = "<?= $tktData['CLUB_TYPE'] ?>";      
        var club_location = "<?= $tktData['PROVINCE'] ?>";
        var club_choice = F_PIN;
        ref_id_global = f_pin + date.getTime();

        fd.append("f_pin", f_pin);
        fd.append("club_type", club_type);
        fd.append("club_location", club_location);
        fd.append("club_choice", club_choice);
        fd.append("ref_id", ref_id_global);
        fd.append("is_android", 1);

        // Join 

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/join_club",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {

                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');

                $('#modalSuccess').modal('show');

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                $("#submit").prop("disabled", false);

                $('#btnJoin').addClass('d-none');
                $('#btnLeave').removeClass('d-none');

                updateMembers();
                sendMessage();
            },
            error: function (response) {
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });

    }

    function leaveClub(){

        var fd = new FormData();

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var club = F_PIN;

        fd.append("f_pin", f_pin);
        fd.append("club", club);

        var group = group;

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/delete_club",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#modalDeleteSuccess").modal('show');

                $('#btnJoin').removeClass('d-none');
                $('#btnLeave').addClass('d-none');

                updateMembers();
                exitGroup(group);
            },
            error: function (response) {
                alert("Failed To Delete Club");
            }
        });

    }

    function sendMessage(){

        var formData = new FormData();

        var originator = "<?= $_GET['f_pin'] ?>";
        var destination = "<?= $adminTKT  ?>";

        formData.append('originator', originator);
        formData.append('destination', destination);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            // if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                // const response = JSON.parse(xmlHttp.responseText);

                sendMessageAfter();
                
            // }
        }
        xmlHttp.open("post", "../logics/add_friend");
        xmlHttp.send(formData);

    }

    function sendMessageAfter(){

        var formData = new FormData();

        var message_id = ref_id_global;
        var originator = "<?= $_GET['f_pin'] ?>";
        var destination = "<?= $adminTKT  ?>";
        var reply_to = ref_id_global;

        var club_type = "<?= $tktData['CLUB_TYPE'] ?>";   

        if(club_type == 1){
            club_type = "Public";
        }else{
            club_type = "Private";
        }

        var club_location = $('#club_location').text();
        var club_choice = $('#club_choice').text();
        var content = {
            "form_id" : "105857",
            "form_title" : "Join+IMI+Club",
            "A01" : "",
            "club_type" : club_type,
            "province" : club_location,
            "club" : club_choice
        };

        var scope = 18;

        formData.append('message_id', message_id);
        formData.append('originator', originator);
        formData.append('destination', destination);
        formData.append('content', btoa(JSON.stringify(content)));
        formData.append('scope', scope);
        formData.append('reply_to', reply_to);
        formData.append('file_id', "105857");

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                const response = JSON.parse(xmlHttp.responseText);
                
            }
        }
        xmlHttp.open("post", "../logics/send_message");
        xmlHttp.send(formData);

    }

    function exitGroup(group){

        var formData = new FormData();

        var f_pin = F_PIN;
        var group_id = group;

        formData.append('f_pin', f_pin);
        formData.append('group_id', group_id);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                const response = xmlHttp.responseText;
                
            }
        }
        xmlHttp.open("post", "../logics/exit_group");
        xmlHttp.send(formData);

    }

    function updateMembers(){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = "<?= $f_pin ?>"

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('club_choice', L_PIN);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                $('#member_num').text(result);

            }
        }
        xmlHttp.open("post", "../logics/update_member");
        xmlHttp.send(formData);

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

        var f_pin = new URLSearchParams(window.location.search).get('f_pin');

        if (window.Android) {
            if (typeof window.Android.closeView === 'function') {
                window.Android.closeView();
            } else {
                window.history.back();
            }
        }
        else if (window.webkit && window.webkit.messageHandlers) {
            window.webkit.messageHandlers.closeProfile.postMessage({ param1: f_pin });
        }
        
    }

    function likeProduct($productCode, flag, checkIOS = false) {

        if (window.Android) {
            if (!window.Android.checkProfile()) {
            return;
            }
        }

        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
            param1: $productCode,
            param2: 'like'
            });
            return;
        }

        let f_pin = '';

        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get('f_pin');
        }

        var curTime = (new Date()).getTime();
        var formData = new FormData();

        formData.append('product_code', $productCode);
        formData.append('f_pin', f_pin);
        formData.append('last_update', curTime);
        formData.append('flag_like', flag);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);
                console.log($productCode);


                var count_like = $('#like-counter-'+$productCode).text();

                if(flag == 1){

                    var new_like = parseInt(count_like) + 1;

                    $('#like-'+$productCode).attr('src','../assets/img/jim_likes_red.png');

                    $('#like-button-'+$productCode).attr('onclick','likeProduct("'+$productCode+'",0)');

                }else if(flag == 0){

                    var new_num = parseInt(count_like);

                    if (new_num > 0){

                        var new_like = new_num - 1;
                    }

                    $('#like-'+$productCode).attr('src','../assets/img/jim_likes.png');

                    $('#like-button-'+$productCode).attr('onclick','likeProduct("'+$productCode+'",1)');

                }

                $('#like-counter-'+$productCode).text(new_like);

            }
        }

        xmlHttp.open("post", "/gaspol_web/logics/like_product");
        xmlHttp.send(formData);

    }


    function openPostContextMenu(postId, isFollowed, isBookmarked, f_pin_post) {
        let isFollow = isFollowed === '1';
        let isBookmark = isBookmarked === '1';
        console.log('postId', postId);
        console.log('isBookmarked', isBookmark);
        console.log('isFollowed', isFollow);

        let isSelfPost = f_pin_post == '<?= $_GET['f_pin'] ?>';

        console.log('isSelfPost', isSelfPost);

        if (window.Android) {
            if (window.Android.checkProfile()) {
                window.Android.openPostContextMenu(postId, isFollow, isBookmark, isSelfPost);
            }
        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.openPostContextMenu) {
            window.webkit.messageHandlers.openPostContextMenu.postMessage({
            post_id: postId,
            is_followed: isFollow,
            is_bookmarked: isBookmark,
            is_selfpost: isSelfPost
            });
        }
    }

    function editPost(code) {
        if (window.Android) {
            let f_pin = window.Android.getFPin();

            window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
        } else {
            let f_pin = new URLSearchParams(window.location.search).get("f_pin");

            window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
        }
    }

    function deletePost(post_id) {
        var xmlHttp = new XMLHttpRequest();

        let formData = new FormData();
        formData.append('post_id', post_id);
        formData.append('ec_date', new Date().getTime());
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == "Success") {
                console.log(post_id + ' deleted');
                if (localStorage.lang == 0) {
                $('#delete-post-info .modal-body').html('<h6>Post deleted.</h6>');
                $('#delete-post-close').text('Close');
                } else {
                $('#delete-post-info .modal-body').html('<h6>Postingan telah dihapus.</h6>');
                $('#delete-post-close').text('Tutup');
                }
                $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                console.log('row', $('.product-row').length);
                if ($('.product-row').length === 1) {
                    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
                    let activeCategories = localStorage.getItem('active_content_category');

                    window.location.href = 'tab1-main.php?f_pin=' + f_pin + '&filter=' + activeCategories;
                } else if ($('.product-row').length > 1) {
                    // let activeCategories = localStorage.getItem('active_content_category');
                    // let bTheme = '';
                    // if (activeCategories !== null && activeCategories.split('-').length === 1) {
                    //     bTheme = activeCategories.split('-')[0];
                    // }
                    window.location.reload();
                }
                });
                $('#delete-post-info').modal('toggle');
            } else {
                if (localStorage.lang == 0) {
                $('#delete-post-info .modal-body').html('<h6>An error occured while deleting post. Please refresh and try again.</h6>');
                $('#delete-post-close').text('Close');
                } else {
                $('#delete-post-info .modal-body').html('<h6>Error saat menghapus post. Silahkan muat ulang dan coba lagi.</h6>');
                $('#delete-post-close').text('Tutup');
                }
                // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
                //   window.location.reload();
                // });
                $('#delete-post-info').modal('toggle');
            }
            }
        }
        xmlHttp.open("POST", "/gaspol_web/logics/delete_post");
        xmlHttp.send(formData);
    }

</script>