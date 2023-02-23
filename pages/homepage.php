<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// CHECK KTA

$query = $dbconn->prepare("SELECT * FROM KTA LEFT JOIN REGISTRATION_PAYMENT ON KTA.NO_ANGGOTA =  REGISTRATION_PAYMENT.REF_ID WHERE KTA.F_PIN = '$f_pin' ORDER BY KTA.ID DESC");
$query->execute();
$checkKTA = $query->get_result()->fetch_assoc();
$query->close();

if (isset($checkKTA)) {

    if ($checkKTA['STATUS_ANGGOTA'] == 0) {
        $statusKTA = 0;
    } else {
        $statusKTA = 1;
    }
}

// CHECK KIS

$query = $dbconn->prepare("SELECT * FROM KIS INNER JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU =  REGISTRATION_PAYMENT.REF_ID WHERE KIS.F_PIN = '$f_pin' ORDER BY KIS.ID DESC");
$query->execute();
$checkKIS = $query->get_result()->fetch_assoc();
$query->close();

// GET CLUB

$query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP LEFT JOIN TKT ON TKT.ID =  CLUB_MEMBERSHIP.CLUB_CHOICE WHERE CLUB_MEMBERSHIP.F_PIN = '$f_pin' AND CLUB_MEMBERSHIP.STATUS = 1");
$query->execute();
$getClub = $query->get_result()->fetch_assoc();
$query->close();

if (!isset($getClub['CLUB_NAME'])) {
    $getClub['CLUB_NAME'] = "No Club";
}

// FOR PADDING DIV BASIC ACCOUNT

$padding = 0;

if (!isset($checkKTA)) {
    $padding = 1;
}

// get news_

$sql_news = "SELECT p.* , c.ID, c.CODE
FROM POST p
LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID
LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID
WHERE F_PIN = '024b7bb318' 
ORDER BY CREATED_DATE DESC 
LIMIT 5 
OFFSET 0";
$newsQuery = $dbconn->prepare($sql_news);
$newsQuery->execute();
$news_raw = $newsQuery->get_result();
$newsQuery->close();

$news = array();
while ($new = $news_raw->fetch_assoc()){
    $news[] = $new;
}

function timeSince($date) {

    // $seconds = floor((new Date() - $date) / 1000);

    $seconds = time() - floor($date / 1000);

    $interval = $seconds / 31536000;

    if ($interval > 1) {
        $timeInt = floor($interval);
        $singular = "";
        $plural = "";
        $singular = " year ago";
        $plural = " years ago";
        $timeStr = $timeInt > 1 ? $timeInt . $plural : $timeInt . $singular;
        return $timeStr;
    }
    $interval = $seconds / 2592000;
    if ($interval > 1) {
        $timeInt = floor($interval);
        // let timeStr = timeInt > 1 ? timeInt + " months ago" : timeInt + " month ago";
        $singular = " month ago";
        $plural = " months ago";
        $timeStr = $timeInt > 1 ? $timeInt . $plural : $timeInt . $singular;
        return $timeStr;
    }
    $interval = $seconds / 86400;
    if ($interval > 1) {
        $timeInt = floor($interval);
        // let timeStr = timeInt > 1 ? timeInt + " months ago" : timeInt + " month ago";
        $singular = " day ago";
        $plural = " days ago";
        $timeStr = $timeInt > 1 ? $timeInt . $plural : $timeInt . $singular;
        return $timeStr;
    }
    $interval = $seconds / 3600;
    if ($interval > 1) {
        $timeInt = floor($interval);
        // let timeStr = timeInt > 1 ? timeInt + " months ago" : timeInt + " month ago";
        $singular = " hour ago";
        $plural = " hours ago";
        $timeStr = $timeInt > 1 ? $timeInt . $plural : $timeInt . $singular;
        return $timeStr;
    }
    $interval = $seconds / 60;
    if ($interval > 1) {
        $timeInt = floor($interval);
        // let timeStr = timeInt > 1 ? timeInt + " months ago" : timeInt + " month ago";
        $singular = " minute ago";
        $plural = " minutes ago";
        $timeStr = $timeInt > 1 ? $timeInt . $plural : $timeInt . $singular;
        return $timeStr;
    }
    $timeInt = floor($seconds);
        $singular = " second ago";
        $plural = " seconds ago";
    $timeStr = $timeInt > 1 ? $timeInt . $plural : $timeInt . $singular;
    // let timeStr = timeInt > 1 ? timeInt + " seconds ago" : timeInt + " second ago";
    return $timeStr;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gaspol Home</title>
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet"> -->
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

        #main-div {
            border-radius: 1rem 1rem 0 0;
            background-color: #ffa500;
            margin-top: 60px;
        }

        .era-insurance {
            background-color: white;
            border-radius: .6rem;
        }

        .progress {
            background-color: grey;
            height: 10px;
        }

        .card {
            border-radius: 1rem;
            color: white !important;
        }

        .small-text {
            font-size: .55rem;
        }



        #story-container {
            margin: 0;
            padding: 0;
            width: 100%;
            /* background: white; */
            overflow-x: auto;
            box-sizing: border-box;
        }

        #story-container ul {
            list-style-type: none;
            user-select: none;
            display: flex;
            margin-bottom: 0;
            overflow-x: auto;
            padding-inline-start: 0;
            padding: 10px 0;
        }

        #story-container ul li {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3px;
            /* margin: 0 2px; */
        }

        #story-container ul li:first-child {
            padding-left: 10px
        }

        #story-container ul li:last-child {
            padding-right: 10px
        }

        #story-container ul li .story {
            
            width: 48px;
            height: 48px;
            padding: 2px;
            border-radius: 50%;
            /* background: rgba(255, 255, 255, 0); */
            position: relative;
            margin-bottom: 5px;
        }

        #story-container ul li .story img {
            padding: 1px;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* #story-container ul li.selected {
    background: white;
} */

        #story-container ul li.selected .story img {
            /* background: #cccf12; */
            box-shadow: inset 0 0 0 4px #cccf12;
        }

        #story-container ul li span {
            color: black;
            font-size: 8.5px;
            text-align: center;
            white-space: nowrap;
            width: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            /* margin-top: 5px; */
        }

        #story-container ul li span img {
            width: 9px;
            height: 9px;
            margin-right: 2px;
            vertical-align: middle;
        }

        .single-news {
            border-radius: 10px;
            background-color: white;
            margin: 6px 0;
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

        .news-img {
            border-radius: 10px 0px 0px 10px;
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .card-body {
            padding: 1rem .5rem;
        }
    </style>

</head>

<body style="visibility:hidden">

    <div class="container-fluid bg-white fixed-top" id="header">
        <div class="row pt-3 pb-2 px-2 align-items-center">
            <div class="col-10">
                <h5 class="mb-0" style="font-weight: 700;"><span id="hello">Hello</span>, <?= $userData["IS_CHANGED_PROFILE"] == 0 ? "Guest" : $userData["FIRST_NAME"] ?>!</h5>
            </div>
            <div class="col-2 text-end">
                <a href="imi-notification.php">
                    <img src="../assets/img/notification.png" alt="" style="width: 30px; height: 30px">
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0" id="main-div">
        <div class="py-3" id="row card-section">

            <?php if (isset($checkKTA)) : ?>

                <?php if (isset($checkKIS)) : ?>

                    <?php
                    $mil = $checkKIS['DATE'];
                    $seconds = $mil / 1000;
                    $oldData = date("d-m-Y", $seconds);
                    $date = date_create($oldData);
                    $newDate = date_format($date, "d M Y");

                    $expDate = strtotime('+1 year', strtotime($newDate));
                    // $expDate = date("d M Y", strtotime("+1 year", $oldData));

                    $now = time();
                    $your_date = $expDate;
                    $datediff = $your_date - $now;
                    $daysLeft = round($datediff / (60 * 60 * 24));

                    $percentage = $daysLeft / 366 * 100;
                    ?>


                    <div class="card mx-3 px-2 py-1" style="background-color: #0b0b35">
                        <div class="card-body">
                            <div class="row gx-0">
                                <div class="col-10">
                                    <h5 style="font-size: 17px; margin-bottom: 0px"><b>IKATAN MOTOR INDONESIA</b></h5>
                                    <p style="font-size: 13px; font-weight: 300" class="mb-2 mt-1">Kartu Tanda Anggota - KIS</p>
                                </div>
                                <div class="col-2 text-end">
                                    <img src="../assets/img/logo-imi.png" style="width: 100px; height: auto; width: 60px; height: auto; margin-top: -10px">
                                </div>
                            </div>
                            <h5 style="font-size: 17px; font-weight: bolder"><?= $checkKIS['NOMOR_KARTU'] ?></h5>
                            <p class="clubs" style="font-size: 13px; font-weight: 300" class="mb-2"><?= $getClub['CLUB_NAME'] ?></p>
                            <div class="row">
                                <div class="col-6">
                                    <div style="font-size: 13px"><b><?= date("d M Y", $expDate) ?></b></div>
                                    <small class="expiry-date" style="font-size: 12px; font-weight: 300"></small>
                                </div>
                                <div class="col-6 text-end">
                                    <button class="view-card" onclick="viewKIS()" style="margin-top: 5px; font-weight: bold; background-color:#ff6700; border-radius: 20px; border: none; color: white; padding: 7px; font-size: 13px; padding-left: 15px; padding-right: 15px"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($statusKTA == 0) : ?>

                    <?php
                    $mil = $checkKTA['DATE'];
                    $seconds = $mil / 1000;
                    $oldData = date("d-m-Y", $seconds);
                    $date = date_create($oldData);
                    $newDate = date_format($date, "d M Y");

                    $expDate = strtotime('+1 year', strtotime($newDate));

                    $now = time();
                    $your_date = $expDate;
                    $datediff = $your_date - $now;
                    $daysLeft = round($datediff / (60 * 60 * 24));

                    $percentage = $daysLeft / 366 * 100;
                    ?>

                    <div class="card mx-3 px-2 py-1" style="background-color: #0b0b35">
                        <div class="card-body">
                            <div class="row gx-0">
                                <div class="col-10">
                                    <h5 style="font-size: 17px; margin-bottom: 0px"><b>IKATAN MOTOR INDONESIA</b></h5>
                                    <p style="font-size: 13px; font-weight: 300" class="mb-2 mt-1">Kartu Tanda Anggota - KTA Mobility</p>
                                </div>
                                <div class="col-2 text-end">
                                    <img src="../assets/img/logo-imi.png" style="width: 100px; height: auto; width: 60px; height: auto; margin-top: -10px">
                                </div>
                            </div>
                            <h5 style="font-size: 17px; font-weight: bolder"><?= $checkKTA['NO_ANGGOTA'] ?></h5>
                            <p class="clubs" style="font-size: 13px; font-weight: 300" class="mb-2"><?= $getClub['CLUB_NAME'] ?></p>
                            <div class="row">
                                <div class="col-6">
                                    <div style="font-size: 13px"><b><?= date("d M Y", $expDate) ?></b></div>
                                    <small class="expiry-date" style="font-size: 12px; font-weight: 300"></small>
                                </div>
                                <div class="col-6 text-end">
                                    <button class="view-card" onclick="viewCardMobility()" style="margin-top: 5px; font-weight: bold; background-color:#ff6700; border-radius: 20px; border: none; color: white; padding: 7px; font-size: 13px; padding-left: 15px; padding-right: 15px" onclick="viewKIS()"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($statusKTA == 1) : ?>

                    <?php
                    $mil = $checkKTA['DATE'];
                    $seconds = $mil / 1000;
                    // $expDate = date("d M Y", strtotime('+1 year', $seconds);
                    $oldData = date("d-m-Y", $seconds);
                    $date = date_create($oldData);
                    $newDate = date_format($date, "d M Y");

                    $expDate = strtotime('+1 year', strtotime($newDate));

                    $now = time();
                    $your_date = $expDate;
                    $datediff = $your_date - $now;
                    $daysLeft = round($datediff / (60 * 60 * 24));

                    $percentage = $daysLeft / 366 * 100;
                    ?>

                    <div class="card mx-3 px-2 py-1" style="background-color: #0b0b35">
                        <div class="card-body">
                            <div class="row gx-0">
                                <div class="col-10">
                                    <h5 style="font-size: 17px; margin-bottom: 0px"><b>IKATAN MOTOR INDONESIA</b></h5>
                                    <p style="font-size: 13px; font-weight: 300" class="mb-2 mt-1">Kartu Tanda Anggota - KTA Pro</p>
                                </div>
                                <div class="col-2 text-end">
                                    <img src="../assets/img/logo-imi.png" style="width: 100px; height: auto; width: 60px; height: auto; margin-top: -10px">
                                </div>
                            </div>
                            <h5 style="font-size: 17px; font-weight: bolder"><?= $checkKTA['NO_ANGGOTA'] ?></h5>
                            <p class="clubs" style="font-size: 13px; font-weight: 300" class="mb-2"><?= $getClub['CLUB_NAME'] ?></p>
                            <div class="row">
                                <div class="col-6">
                                    <div style="font-size: 13px"><b><?= date("d M Y", $expDate) ?></b></div>
                                    <small class="expiry-date" style="font-size: 12px; font-weight: 300"></small>
                                </div>
                                <div class="col-6 text-end">
                                    <button class="view-card" onclick="viewCardPro()" style="margin-top: 5px; font-weight: bold; background-color:#ff6700; border-radius: 20px; border: none; color: white; padding: 7px; font-size: 13px; padding-left: 15px; padding-right: 15px"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            <?php else : ?>

                <?php
                // $mil = $checkKTA['DATE'];
                // $seconds = $mil / 1000;
                // $oldData = date("d-m-Y", $seconds);
                // $date = date_create($oldData);
                // $newDate = date_format($date, "d M Y");

                // $expDate = strtotime('+1 year', strtotime($newDate));

                // $now = time();
                // $your_date = $expDate;
                // $datediff = $your_date - $now;
                // $daysLeft = round($datediff / (60 * 60 * 24));

                // $percentage = $daysLeft / 366 * 100;
                ?>

                <div class="card mx-3" style="background-color: white">
                    <div class="card-body">
                        <div class="row gx-0">
                            <div class="col-12 text-center">
                                <h5 style="font-size: 17px; margin-bottom: 0px; color: black"><b>IKATAN MOTOR INDONESIA</b></h5>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <img src="../assets/img/logo-imi.png" style="height: auto; width: 35px; height: auto">
                                    <small style="color: grey; font-size: 11px" id="member-of">Member of</small>
                                    <img src="../assets/img/social/Property 1=fia-black.png" style="height: auto; width: 35px; height: auto">
                                    <img src="../assets/img/social/Property 1=fim-black.png" style="height: auto; width: 35px; height: auto">
                                </div>
                            </div>
                        </div>
                        <div class="row gx-0 mt-2">
                            <div class="col-6 text-center">
                                <button onclick="upgradeMobility()" style="width: 80%; margin-top: 5px; font-weight: 700; background-color:#ff6700; border-radius: 20px; border: none; color: white; padding: 7px; font-size: 13px; padding-left: 15px; padding-right: 15px" id="btn-reg-kta">Register KTA</button>
                            </div>
                            <div class="col-6 text-center">
                                <button onclick="claimKTA()" style="width: 80%; margin-top: 5px; font-weight: 700; background-color:white; border-radius: 20px; border: 1px solid black; color: black; padding: 7px; font-size: 13px; padding-left: 15px; padding-right: 15px" id="btn-claim-kta">Claim KTA</button>
                            </div>
                            <div class="row gx-0">
                                <div class="col-12 text-center mt-2">
                                    <small style="color: grey; font-size: 11px" id="become-imi">Become IMI member to receive extraordinary benefits.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <div class="row mb-4 justify-content-center align-items-center">
            <div class="col-5 me-1 pe-0  era-insurance" id="era" onclick="goToPage(this.id)">
                <div class="row">
                    <div class="col-3 ps-2 pe-0 d-flex align-items-center">
                        <img src="../assets/img/buoy.png" style="width: 100%; height: auto">
                    </div>
                    <div class="col-9 ps-1 pt-3 pb-2">
                        <h6 class="mb-0" style="font-size:15px"><strong>Gaspol RodA</strong></h6>
                        <span class="small-text">Roadside Assistance</span>
                    </div>
                </div>
            </div>
            <div class="col-5 ms-1 era-insurance" id="insurance" onclick="goToPage(this.id)">
                <div class="row">
                    <div class="col-3 ps-2 pe-0 d-flex align-items-center">
                        <img src="../assets/img/safe.png" style="width: 100%; height: auto">
                    </div>
                    <div class="col-9 ps-1 pe-0 pt-3 pb-2">
                        <h6 class="mb-0" style="font-size:15px"><strong id="insurance-title">Insurance</strong></h6>
                        <span class="small-text" id="insurance-subtitle">Travel and Life Insurance</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-0" style="background-color: #ff6b00; border-radius: 1rem 1rem 0 0;">
            <div class="row px-3 pt-3 pb-4" style="color:white">
                <div class="col-4 text-center" id="imi-partners" onclick="goToPage(this.id);" style="border-right: 1px solid #FF9900;">
                    <img src="../assets/img/social_official.png" style="width: 40px; height:auto;">
                    <p class="mb-0" style="font-size:.75rem" id="imi-partner-text">IMI Partner</p>
                </div>
                <div class="col-4 text-center" id="imi-directory" onclick="goToPage(this.id);" style="border-right: 1px solid #FF9900;">
                    <img src="../assets/img/social_imi.png" style="width: 40px; height:auto;">
                    <p class="mb-0" style="font-size:.75rem" id="imi-directory-text">IMI Directory</p>
                </div>
                <div class="col-4 text-center" id="kta-benefits" onclick="goToPage(this.id);">
                    <img src="../assets/img/social_member.png" style="width: 40px; height:auto;">
                    <p class="mb-0" style="font-size:.75rem" id="imi-benefit-text">KTA Benefits</p>
                </div>
            </div>

            <div class="container-fluid mx-0 bg-light" style="border-radius: 1rem 1rem 0 0;">
                <div class="row pt-3 pb-1 px-2">
                    <p class="mb-0" style="font-size: .8rem;"><strong id="official-partner">OFFICIAL PARTNER</strong></p>
                </div>
                <div class="row">
                    <div id="story-container">
                        <ul class="ps-3">

                            <?php

                            $sql = "SELECT * FROM IMI_PARTNERS";
                            $query = $dbconn->prepare($sql);
                            $query->execute();
                            $result = $query->get_result();
                            $query->close();

                            $partners = array();
                            while ($partner = $result->fetch_assoc()) {
                                $partners[] = $partner;
                            }

                            foreach ($partners as $part) {

                            ?>

                                <li id="partner-<?= $part['ID'] ?>" class="has-story">
                                    <div class="story" style="background:<?= $part["BG_COLOR"] ?>;">
                                        <img src="../images/<?= $part['IMAGE'] ?>">
                                    </div>
                                    <span class="user" id="partnername-<?= $part['ID'] ?>"><?= $part["NAME"] ?></span>
                                </li>

                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="row align-items-center mt-4 pb-1 px-2">
                    <div class="col-7">
                        <p class="mb-0" style="font-size: .8rem;"><strong id="news-update">NEWS UPDATE</strong></p>
                    </div>
                    <div class="col-5 text-end">
                        <a id="news-all" href="news_update.php?f_pin=<?= $f_pin ?>" style="font-size: .8rem; color:#ff6b00; text-decoration:none;">See all</a>
                    </div>
                </div>
                <div class="container-fluid px-0" id="news-section">
                    <?php foreach($news as $n) { 
                        
                        $domain = 'http://108.136.138.242/gaspol_web/images/';
                        $thumbnail = explode('|', $n['FILE_ID'])[0];
                        $time = timeSince($n['CREATED_DATE']);

                        $title = strlen($n['TITLE']) > 50 ? substr($n['TITLE'], 0, 50) . "..." : $n['TITLE'];
                        $desc = strlen($n['DESCRIPTION']) > 50 ? substr($n['DESCRIPTION'], 0, 50) . "..." : $n['DESCRIPTION'];
                    ?>

                        <div class="row single-news my-3 gx-0" onclick="openNews('<?= $n['POST_ID'] ?>')">
                        <div class="col-4 news-img-col">
                            <img class="news-img" src="<?= $domain . $thumbnail ?>">
                            <span class="category-tag"><?= $n['CODE'] ?></span>
                        </div>
                        <div class="col-8 p-2">
                            <div class="row">
                                <div class="col-12">
                                    <img src="../assets/img/action_clock.png" style="width: 14px; height: auto;">
                                    <span class="text-secondary small-text time-since"><?= $time ?></span>
                                    <span class="small-text" style="margin: 0 3px;">•</span>
                                    <span class="text-secondary small-text">Admin</span>
                                </div>
                            </div>
                            <h6 class="news-title"><strong><?= $title ?></strong></h6>
                            <p class="mb-0 text-secondary news-content"><?= $desc ?> <a class="news-read-more">Read more</a></p>
                        </div>
                    </div>

                    <?php } ?>
                    <!-- <div class="row single-news m-2 gx-0">
                        <div class="col-4">
                            <img class="news-img" src="https://akcdn.detik.net.id/community/media/visual/2018/08/15/a0b6df6d-6e4a-4fd7-bcca-e38c0eb518b1_169.jpeg?w=700&q=90">
                        </div>
                        <div class="col-8 p-2">
                            <span class="text-secondary small-text">5 min ago</span>
                            <span class="small-text" style="margin: 0 3px;">•</span>
                            <span class="text-secondary small-text">DetikOto</span>
                            <h6 class="news-title"><b>Mau Kredit Kawasaki Ninja H2 Seharga Rp 830 Juta?...</b></h6>
                            <p class="mb-0 text-secondary news-content">Jakarta - Kawasaki Ninja H2 merupakan motor hypersport... <a class="news-read-more">Read more</a></p>
                        </div>
                    </div>
                    <div class="row single-news m-2 gx-0">
                        <div class="col-4">
                            <img class="news-img" src="https://akcdn.detik.net.id/community/media/visual/2022/06/17/asap-hitam-knalpot-mobil_169.jpeg?w=700&q=90">
                        </div>
                        <div class="col-8 p-2">
                            <span class="text-secondary small-text">5 min ago</span>
                            <span class="small-text" style="margin: 0 3px;">•</span>
                            <span class="text-secondary small-text">DetikOto</span>
                            <h6 class="news-title"><b>Mobil Keluar Asap Hitam, Ini Penyebabnya</b></h6>
                            <p class="mb-0 text-secondary news-content">Sebagai pemilik mobil tentu kamu harus merawat... <a class="news-read-more">Read more</a></p>
                        </div>
                    </div>
                    <div class="row single-news m-2 gx-0">
                        <div class="col-4">
                            <img class="news-img" src="https://akcdn.detik.net.id/community/media/visual/2022/06/19/stefan-bradl_169.jpeg?w=700&q=90">
                        </div>
                        <div class="col-8 p-2">
                            <span class="text-secondary small-text">5 min ago</span>
                            <span class="small-text" style="margin: 0 3px;">•</span>
                            <span class="text-secondary small-text">DetikOto</span>
                            <h6 class="news-title"><b>Honda Keok di MotoGP Jerman, Sisa 1 Pebalap...</b></h6>
                            <p class="mb-0 text-secondary news-content">Stefan Bradl menjadi satu-satunya pebalap Honda yang mampu... <a class="news-read-more">Read more</a></p>
                        </div>
                    </div>
                    <div class="row single-news m-2 gx-0">
                        <div class="col-4">
                            <img class="news-img" src="https://akcdn.detik.net.id/community/media/visual/2021/06/19/fabio-quartararo.jpeg?w=700&q=90">
                        </div>
                        <div class="col-8 p-2">
                            <span class="text-secondary small-text">5 min ago</span>
                            <span class="small-text" style="margin: 0 3px;">•</span>
                            <span class="text-secondary small-text">DetikOto</span>
                            <h6 class="news-title"><b>MotoGP Jerman 2022: Quartararo Pemecah Rekor...</b></h6>
                            <p class="mb-0 text-secondary news-content">Rekor delapan kali juara berturut-turut di MotoGP Jerman yang... <a class="news-read-more">Read more</a></p>
                        </div>
                    </div> -->
                </div>
                <div class="container-fluid mt-3 pb-3" id="load-more-section">
                    <div class="row">
                        <div class="col-12 text-center">
                            <a>
                                <button class="btn btn-loadmore" id="btn-loadmore">
                                    <img src="../assets/img/action_docs.png" style="width:25px; height:auto">
                                    <span class="mb-0" style="font-size: 12px"><strong id="load-more">Load more</strong></span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var IS_HOMEPAGE = 1;
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../assets/js/script-homepage.js?v=<?= time() ?>"></script>

    <script>

        $(document).ready(function() {
            if(localStorage.lang == 0) {
                $('#hello').text("Hello");
                $('#news-update').text('NEWS UPDATE');
                $('#official-partner').text('OFFICIAL PARTNER');
                $('#load-more').text('Load more');
                $('#news-all').text('See all')
                $('#member-of').text('Member of');
                $('#btn-reg-kta').text('Register KTA');
                $('#btn-claim-kta').text('Claim KTA');
                $('#become-imi').text('Become IMI member to receive extraordinary benefits.')
                $('#insurance-title').text("Insurance");
                $('#insurance-subtitle').text("Travel and Life Insurance");
                $('#imi-partner-text').text("IMI Partner");
                $('#imi-directory-text').text("IMI Directory");
                $('#imi-benefit-text').text("KTA Benefits");
                $(".expiry-date").text("Expiry date");
                $(".view-card").text("View Card");
            } else {
                $('#hello').text("Halo");
                $('#news-update').text('INFORMASI TERBARU');
                $('#official-partner').text('MITRA RESMI');
                $('#load-more').text('Tampilkan Lebih Banyak');
                $('#news-all').text('Lihat Semua')
                $('#member-of').text('Anggota dari');
                $('#btn-reg-kta').text('Daftar KTA');
                $('#btn-claim-kta').text('Klaim KTA');
                $('#become-imi').text('Jadi anggota IMI untuk dapatkan beragam benefitnya.')
                $('#insurance-title').text("Asuransi");
                $('#insurance-subtitle').text("Perjalanan dan jiwa");
                $('#imi-partner-text').text("Rekan IMI");
                $('#imi-directory-text').text("Petunjuk IMI");
                $('#imi-benefit-text').text("Benefit KTA");
                $(".expiry-date").text("Tanggal kadaluarsa");
                $(".view-card").text("Lihat Kartu");

                $(".clubs").text("Tidak ada klub");

                $('.time-since').each(function() {
                    let timeSince = $(this).text();
                    let timeSinceTL = timeSince.replace("years ago", "tahun lalu").replace("year ago", "tahun lalu").replace("months ago", "bulan lalu").replace("month ago", "bulan lalu").replace("days ago", "hari lalu").replace("day ago", "hari lalu").replace("hours ago", "jam lalu").replace("hour ago", "jam lalu").replace("minutes ago", "menit lalu").replace("minute ago", "menit lalu").replace("seconds ago", "detik lalu").replace("second ago", "detik lalu");
                    $(this).text(timeSinceTL);
                })
                
            }

            $('body').css('visibility', 'visible')
        })
        
    </script>
</body>

</html>