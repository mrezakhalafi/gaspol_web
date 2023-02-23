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
    }

    // GET USER INFO

    $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
    $query->execute();
    $userData = $query->get_result()->fetch_assoc();
    $query->close();

    // GET FOLLOWERS

    $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE L_PIN = '$f_pin'");
    $query->execute();
    $followers = $query->get_result();
    $query->close();

    // GET FOLLOWING USER

    $following = [];

    $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '$f_pin'");
    $query->execute();
    $followingUser = $query->get_result();

    while ($row = $followingUser->fetch_array(MYSQLI_ASSOC))
    {
        $following[] = $row;
    }

    $query->close();

    // GET FOLLOWING TKT

    $query = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE F_PIN = '$f_pin'");
    $query->execute();
    $followingTKT = $query->get_result();

    while ($row = $followingTKT->fetch_array(MYSQLI_ASSOC))
    {
        $following[] = $row;
    }

    $query->close();

    // GET POST

    $query = $dbconn->prepare("SELECT POST.*, USER_LIST.FIRST_NAME, USER_LIST.LAST_NAME, USER_LIST.IMAGE FROM POST LEFT JOIN USER_LIST ON POST.F_PIN = USER_LIST.F_PIN WHERE POST.F_PIN = '$f_pin' AND POST.EC_DATE IS NULL");
    $query->execute();
    $post = $query->get_result();
    $query->close();

    // GET SAVED

    $query = $dbconn->prepare("SELECT POST_BOOKMARK.*, POST.*, USER_LIST.*, POST.CREATED_DATE AS CA FROM POST_BOOKMARK LEFT JOIN POST ON POST_BOOKMARK.POST_ID = POST.POST_ID LEFT JOIN USER_LIST ON POST.F_PIN = USER_LIST.F_PIN WHERE POST.F_PIN = '$f_pin'");
    $query->execute();
    $saved = $query->get_result();
    $query->close();

    // GET PROVINCE

    $query = $dbconn->prepare("SELECT * FROM USER_LIST_EXTENDED_GASPOL LEFT JOIN PROVINCE ON USER_LIST_EXTENDED_GASPOL.PROVINCE = PROVINCE.PROV_ID WHERE F_PIN = '$f_pin'");
    $query->execute();
    $userDataExtend = $query->get_result()->fetch_assoc();
    $query->close();

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

                <?php if ($userData['IMAGE']): ?>
                    <img class="shadow-lg" style="height: 125px; width: 100%; background-color: grey; filter: blur(5px); object-position: center; object-fit: cover" src="http://108.136.138.242/filepalio/image/<?= $userData['IMAGE'] ?>">
                <?php else: ?>
                    <div class="shadow-lg" style="height: 125px; width: 100%; background-color: grey; filter: blur(5px)"></div>
                <?php endif; ?>

            </div>
        </div>
        <div class="row" style="margin-top: -50px">
            <div class="profile-image text-center">

                <?php if ($userData['IMAGE']): ?>
                    <img class="rounded-circle" style="object-fit: cover; border: 2px solid #ff6b00; width: 100px; height: 100px; filter: blur(0px)" src="http://108.136.138.242/filepalio/image/<?= $userData['IMAGE'] ?>">
                <?php else: ?>
                    <img class="rounded-circle" style="object-fit: cover; border: 2px solid #ff6b00; width: 100px; height: 100px; filter: blur(0px)" src="../assets/img/tab5/no-avatar.jpg">
                <?php endif; ?>

            </div>
        </div>
        <div class="row mt-2">
            <div class="profile-name text-center">
                <b><?= $userData['FIRST_NAME']." ".$userData['LAST_NAME'] ?></b>
            </div>
        </div>
        <div class="row mt-3">

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

                <div class="col-4 text-center" onclick="followingOthers()">
                    <div style="font-size: 14px"><b id="followers_num"><?= count($following) ?></b></div>
                    <div id="user_following" style="font-size: 12px">Following</div>
                </div>

            <?php else: ?>


                <div class="col-4 text-center" onclick="following()">
                    <div style="font-size: 14px"><b><?= count($following) ?></b></div>
                    <div id="user_following"  style="font-size: 12px">Following</div>
                </div>

            <?php endif; ?>


            <div class="col-4 text-center">
                <div style="font-size: 14px"><b><?= mysqli_num_rows($post) ?></b></div>
                <div id="total_posts" style="font-size: 12px">Posts</div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">

                <?php if (isset($_GET['l_pin'])):

                    if (!isset($isBlock)):

                        $a = $_GET['f_pin'];
                        $b = $f_pin;

                        $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '".$a."' AND L_PIN = '".$b."'");
                        $query->execute();
                        $isFollow = $query->get_result();
                        $query->close();

                        if (mysqli_num_rows($isFollow) > 0): ?>

                            <button id="btnUnfollow" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="unfollow()">Unfollow</button>
                            <button id="btnFollow" class="d-none" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="follow()">Follow</button>
                        
                        <?php else: ?>

                            <button id="btnFollow" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="follow()">Follow</button>
                            <button id="btnUnfollow" class="d-none" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="unfollow()">Unfollow</button>

                        <?php endif; 
                    endif; ?>

                <?php else: ?>
                <button id="user_profile" style="font-size: 14px; background-color: white; border: 1px solid black; border-radius: 20px; width: 90%; padding: 5px" onclick="changeProfile()">Edit Profile</button>
            <?php endif; ?>
            
            </div>
        </div>

        <?php if (!isset($isBlock)): ?>

            <div class="row m-3">
                <div class="navbar">
                    <a id="navProfil" class="activeNav" onclick="changeProfil()">Profile</a>
                    <a id="navPostingan" onclick="changePostingan()">Posts</a>
                    <a id="navMedia" onclick="changeMedia()">Media</a>
                    <a id="navDisimpan" onclick="changeDisimpan()">Saved</a>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <?php if (!isset($isBlock)): ?>

        <div class="section-profile m-4" style="font-size: 12px">

        <div class="row">
            <div class="mb-1 text-secondary">Bio</div>

            <?php if($userData['QUOTE']): ?>
                <p><b><?= $userData['QUOTE'] ?></b></p>
            <?php else: ?>
                <p class="not_updated_yet" style="font-weight: 700">Not updated yet</p>
            <?php endif; ?>

        </div>
        <div class="row">
            <div id="user_location" class="mb-1 text-secondary">Location</div>

            <?php if($userDataExtend['PROV_NAME']): ?>
                <p><b><?= ucwords(strtolower($userDataExtend['PROV_NAME'])) ?></b></p>
            <?php else: ?>
                <p class="not_updated_yet" style="font-weight: 700">Not updated yet</p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div id="user_category" class="mb-2 text-secondary">Categories</div>

            <?php if ($userDataExtend['ID_CATEGORY']):

                $category = explode("|", $userDataExtend['ID_CATEGORY']);

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

        </div>

    <?php endif; ?>

    <div class="section-postingan m-4 d-none" style="font-size: 12px">

    <?php if (mysqli_num_rows($post) > 0): ?>

        <?php foreach($post as $p):

            $a = $_GET['f_pin'];
            $b = $p['F_PIN'];
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
                        <h5 class="store-name"><?= $p['FIRST_NAME']." ".$p['LAST_NAME'] ?></h5>

                        <?php 

                            $created_date = $p["CREATED_DATE"];
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

                                if ($i == 0): ?>

                                    <div class="carousel-item active">
                                        <div class="carousel-item-wrap">

                                            <?php $ext = explode('.', $fid)[1];
                                            
                                            if ($ext == "mp4"){
                                                $type = 2;
                                            }else{
                                                $type = 1;
                                            }
                                            
                                            ?>

                                            <?php if ($type == 1): ?>
                                                <img src="../images/<?= $fid ?>" class="img-fluid rounded" loading="lazy">
                                            <?php elseif ($type == 2): ?>
                                                <video autoplay muted loop class="img-fluid rounded" src="../images/<?= $fid ?>#t=0.5"></video>
                                            <?php endif; ?>

                                        </div>
                                    </div>

                                <?php else: ?>

                                    <?php $ext = explode('.', $fid)[1];
                                            
                                    if ($ext == "mp4"){
                                        $type = 2;
                                    }else{
                                        $type = 1;
                                    }
                                    
                                    ?>

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

                    <?php if (isset($isLikes) && $p['TOTAL_LIKES'] != 0): ?>

                        <div class="like-button" id="like-button-<?= $p['POST_ID'] ?>" onclick="event.stopPropagation(); likeProduct('<?= $p['POST_ID'] ?>',0)">
                            <img id="like-<?= $p['POST_ID'] ?>" src="../assets/img/jim_likes_red.png" height="25" width="25">
                            <div id="like-counter-<?= $p['POST_ID'] ?>" class="like-comment-counter"><?= $p['TOTAL_LIKES'] ?></div>
                        </div>

                    <?php else: ?>

                        <div class="like-button" id="like-button-<?= $p['POST_ID'] ?>" onclick="event.stopPropagation(); likeProduct('<?= $p['POST_ID'] ?>',1)">
                            <img id="like-<?= $p['POST_ID'] ?>" src="../assets/img/jim_likes.png" height="25" width="25">
                            <div id="like-counter-<?= $p['POST_ID'] ?>" class="like-comment-counter"><?= $p['TOTAL_LIKES'] ?></div>
                        </div>

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

    <div class="section-disimpan m-4 d-none" style="font-size: 12px">

    <?php if (mysqli_num_rows($saved) > 0): ?>

        <?php foreach($saved as $s): 
            
            $a = $_GET['f_pin'];
            $b = $s['F_PIN'];
            $c = $s['POST_ID'];

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
                    <a class="d-flex pe-2" href="tab3-profile.php?l_pin=<?= $s['F_PIN'] ?>&amp;f_pin=<?= $f_pin ?>">
                        
                    <?php if($s['IMAGE']): ?>

                        <img src="http://108.136.138.242/filepalio/image/<?= $s['IMAGE'] ?>" class="align-self-start rounded-circle mr-2 profile-pic">

                    <?php else: ?>

                        <img src="../assets/img/tab5/no-avatar.jpg" class="align-self-start rounded-circle mr-2 profile-pic">

                    <?php endif; ?>

                    </a>
                    <div class="media-body">
                        <h5 class="store-name"><?= $s['FIRST_NAME']." ".$s['LAST_NAME'] ?></h5>

                        <?php 

                            $created_date = $s["CA"];
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
                            <img src="../assets/img/social/Property 1=horizontal.svg" height="25" width="25" style="background-color:unset;" onclick="openPostContextMenu('<?= $s['POST_ID'] ?>','<?= $isFollowPost ?>','<?= $isBookmarkPost ?>','<?= $s['F_PIN'] ?>')">
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
                    <span class="prod-desc"><?= $s['DESCRIPTION'] ?></span>
                </div>
                <div class="col-sm mt-2 timeline-image" onclick="postDetail('<?= $s['POST_ID'] ?>')">
                    <div id="carousel" class="carousel slide pointer-event" data-bs-touch="true" data-bs-interval="false" data-bs-ride="carousel">
                        <ol id="ci" class="carousel-indicators">

                            <?php 

                            $file_id = explode("|", $s['FILE_ID']);

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


                            $file_id = explode("|", $s['FILE_ID']);

                            foreach($file_id as $i => $fid): 

                                if ($i == 0): 
                                
                                    $ext = explode('.', $fid)[1];
                                            
                                    if ($ext == "mp4"){
                                        $type = 2;
                                    }else{
                                        $type = 1;
                                    }
                                    
                                    ?>

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

                                    <?php $ext = explode('.', $fid)[1];
                                            
                                    if ($ext == "mp4"){
                                        $type = 2;
                                    }else{
                                        $type = 1;
                                    }
                                    
                                    ?>

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
                    $query = $dbconn->prepare("SELECT * FROM POST_COMMENT WHERE POST_ID = '".$s['POST_ID']."'");
                    $query->execute();
                    $comment = $query->get_result();
                    $query->close();
                    
                    // CHECK SELF LIKES
                    $query = $dbconn->prepare("SELECT * FROM POST_REACTION WHERE FLAG = 1 AND F_PIN = '".$_GET['f_pin']."' AND POST_ID = '".$s['POST_ID']."'");
                    $query->execute();
                    $isLikes = $query->get_result()->fetch_assoc();
                    $query->close(); 

                ?>

                <div class="col-sm mb-1 like-comment-container">
                    <div class="comment-button">
                    <a onclick="event.stopPropagation();openComment('<?= $s['POST_ID'] ?>')">
                        <img class="comment-icon" src="../assets/img/jim_comments.png" height="25" width="25">
                    </a>
                    <div class="like-comment-counter"><?= mysqli_num_rows($comment) ?></div>
                    </div>
                    
                    <?php if (isset($isLikes) && $s['TOTAL_LIKES'] != 0): ?>

                        <div class="like-button" id="like-button-<?= $s['POST_ID'] ?>-SAVED" onclick="event.stopPropagation(); likeProduct('<?= $s['POST_ID'] ?>',0)">
                        <img id="like-<?= $s['POST_ID'] ?>-SAVED" src="../assets/img/jim_likes_red.png" height="25" width="25">
                        <div id="like-counter-<?= $s['POST_ID'] ?>-SAVED" class="like-comment-counter"><?= $s['TOTAL_LIKES'] ?></div>

                    <?php else: ?>

                        <div class="like-button" id="like-button-<?= $s['POST_ID'] ?>-SAVED" onclick="event.stopPropagation(); likeProduct('<?= $s['POST_ID'] ?>',1)">
                        <img id="like-<?= $s['POST_ID'] ?>-SAVED" src="../assets/img/jim_likes.png" height="25" width="25">
                        <div id="like-counter-<?= $s['POST_ID'] ?>-SAVED" class="like-comment-counter"><?= $s['TOTAL_LIKES'] ?></div>

                    <?php endif; ?>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        <?php else: ?>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <b id="no-saved" class="text-secondary">No post saved!</b>
            </div>
        </div>

        <?php endif; ?>

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
    </div>

    <div class="modal fade" id="modalSaved" tabindex="-1" aria-labelledby="modalSavedLabel" aria-hidden="true">
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
                            <b id="report-user" style="font-size: 13px">Report User</b>
                        </div>
                    </div>

                    <?php if (!isset($isBlock)): ?>

                        <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="blockUserMenu()">
                            <div class="col-2 text-center">
                                <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/social/verboden.svg"> 
                            </div>
                            <div class="col-10">
                                <b id="block-user" style="font-size: 13px">Block User</b>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e7e7e7" onclick="unblockUser()">
                            <div class="col-2 text-center">
                                <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/social/verboden.svg"> 
                            </div>
                            <div class="col-10">
                                <b id="unblock-user" style="font-size: 13px">Unblock User</b>
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
                                <h5 id="why-report">Why you want to report this user?</h5>
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
                                                <button  id="btnSubmit" class="btn btn-dark" style="background-color: darkorange; border: none" type="button" onclick="reportUserSubmit()">Submit</button>
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
                    <p style="font-size: 16px" id="block-success">You blocked this person.</p>
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
        changeDisimpan();
    }

    if(localStorage.getItem('lang') == 1) {
        $("#user_profile").text("Ubah Profil");
        $("#user_followers").text("Pengikut");
        $('#user_following').text('Mengikuti');
        $("#total_posts").text("Postingan");
        $("#navProfil").text("Profil");
        $("#navPostingan").text("Postingan");
        $(".not_updated_yet").text("Belum diperbarui");
        $('#navDisimpan').text('Disimpan');
        $("#user_location").text("Lokasi");
        $("#user_category").text("Kategori");
        $('#no-post').text('Tidak ada postingan yang tersedia!');
        $('#no-media').text('Tidak ada media yang tersedia!');
        $('#no-saved').text('Tidak ada postingan yang disimpan!');
        $('#btnFollow').text('Ikuti');
        $('#btnUnfollow').text('Berhenti Ikuti');
        $('#report-user').text('Laporkan Pengguna');
        $('#block-user').text('Blokir Pengguna');
        $('#unblock-user').text('Buka Blokir');
        $('#btnSubmit').text('Laporkan');
        $('#btnClose').text('Tutup');
        $('#btnClose2').text('Tutup');
        $('#block-success').text('Anda memblokir pengguna ini.');
        $('#report-success').text('Laporan Berhasil.');
        $('#why-report').text('Kenapa anda ingin melaporkan pengguna ini?');

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
        $('.section-disimpan').addClass('d-none');

        $('#navProfil').addClass('activeNav');
        $('#navPostingan').removeClass('activeNav');
        $('#navMedia').removeClass('activeNav');
        $('#navDisimpan').removeClass('activeNav');

    }

    function changePostingan(){

        localStorage.setItem('switch_tab','1');
        
        $('.section-postingan').removeClass('d-none');
        $('.section-profile').addClass('d-none');
        $('.section-media').addClass('d-none');
        $('.section-disimpan').addClass('d-none');

        $('#navPostingan').addClass('activeNav');
        $('#navProfil').removeClass('activeNav');
        $('#navMedia').removeClass('activeNav');
        $('#navDisimpan').removeClass('activeNav');

    }

    function changeMedia(){

        localStorage.setItem('switch_tab','2');
        
        $('.section-media').removeClass('d-none');
        $('.section-postingan').addClass('d-none');
        $('.section-profile').addClass('d-none');
        $('.section-disimpan').addClass('d-none');

        $('#navMedia').addClass('activeNav');
        $('#navPostingan').removeClass('activeNav');
        $('#navProfil').removeClass('activeNav');
        $('#navDisimpan').removeClass('activeNav');

    }

    function changeDisimpan(){

        localStorage.setItem('switch_tab','3');

        $('.section-disimpan').removeClass('d-none');
        $('.section-media').addClass('d-none');
        $('.section-postingan').addClass('d-none');
        $('.section-profile').addClass('d-none');

        $('#navDisimpan').addClass('activeNav');
        $('#navPostingan').removeClass('activeNav');
        $('#navProfil').removeClass('activeNav');
        $('#navMedia').removeClass('activeNav');
        
    }

    function following(){

        window.location.href = "gaspol_following?f_pin=".concat(F_PIN)

    }


    function followingOthers(){

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var l_pin = F_PIN;
        window.location.href = "gaspol_following?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    }

    function followers(){

        window.location.href = "gaspol_followers?f_pin=".concat(F_PIN)

    }

    function followersOthers(){

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var l_pin = F_PIN;

        window.location.href = "gaspol_followers?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    }

    // function openProfileSaved(l_pin){

    //     var f_pin = "<?= $_GET['f_pin'] ?>";
    //     var l_pin = l_pin;

    //     window.location.href = "gaspol_profile?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    // }

    function changeProfile(){

        window.location.href = "change-user?f_pin=".concat(F_PIN)

    }

    // function likeProduct(code){

    //     var code = code;

    //     window.location.href = "comment.php?product_code="+code+"&f_pin=".concat(F_PIN)

    // }

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
                param2: 'report_user'
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
        var f_pin_reported = "<?= $_GET['l_pin'] ?>";
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
                        $('#modal-block-success .modal-body>p').text('You unblocked this user.');
                    } else {
                        $('#modal-block-success .modal-body>p').text('Anda telah membuka blokir user ini.');
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

    function follow(checkIOS = false){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = "<?= $f_pin ?>"

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
        xmlHttp.open("post", "../logics/follow_gaspol");
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
        xmlHttp.open("post", "../logics/unfollow_gaspol");
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
        xmlHttp.open("post", "../logics/update_followers");
        xmlHttp.send(formData);

    }

    function addSaved(){



    }

    function removeSaved(){



    }

    function reportPost(){



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

                /// -SAVED = FOR DIFFERENCE ID POST AND SAVED

                var count_like = $('#like-counter-'+$productCode).text();
                var count_like_saved = $('#like-counter-'+$productCode+'-SAVED').text();

                if(flag == 1){

                    var new_like = parseInt(count_like) + 1;
                    var new_like_saved = parseInt(count_like_saved) + 1;

                    $('#like-'+$productCode).attr('src','../assets/img/jim_likes_red.png');
                    $('#like-'+$productCode+'-SAVED').attr('src','../assets/img/jim_likes_red.png');

                    $('#like-button-'+$productCode).attr('onclick','likeProduct("'+$productCode+'",0)');
                    $('#like-button-'+$productCode+'-SAVED').attr('onclick','likeProduct("'+$productCode+'",0)');

                }else if(flag == 0){

                    var new_num = parseInt(count_like);
                    var new_num_saved = parseInt(count_like_saved);

                    if (new_num > 0){

                        var new_like = new_num - 1;
                    }

                    if (new_num_saved > 0){

                        var new_like_saved = new_num_saved-  1;

                    }

                    $('#like-'+$productCode).attr('src','../assets/img/jim_likes.png');
                    $('#like-'+$productCode+'-SAVED').attr('src','../assets/img/jim_likes.png');

                    $('#like-button-'+$productCode).attr('onclick','likeProduct("'+$productCode+'",1)');
                    $('#like-button-'+$productCode+'-SAVED').attr('onclick','likeProduct("'+$productCode+'",1)');

                }

                $('#like-counter-'+$productCode).text(new_like);
                $('#like-counter-'+$productCode+'-SAVED').text(new_like_saved);

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
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.openPostContextMenu) {
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