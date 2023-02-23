<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $f_pin = $_GET['f_pin'];

session_start();

if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
else if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}

$dbconn = paliolite();

$ver = time();

// $sqlData = "SELECT COUNT(*) as exist
//   FROM KTA
//   WHERE F_PIN = '$f_pin'";

// //   echo $sqlData;

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $resDATA = $queDATA->get_result()->fetch_assoc();
// $exist = $resDATA["exist"];
// $queDATA->close();

// if ($exist > 0) {
//     header("Location: /gaspol_web/pages/card-kta-mobility?f_pin=$f_pin");
//     die();
// }

// NATIONALITY

// $sqlData = "SELECT * FROM COUNTRIES";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $countries = $queDATA->get_result();
// $queDATA->close();

// HOBBIES

// $sqlData = "SELECT * FROM KTA_HOBBY";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $hobby = $queDATA->get_result();
// $queDATA->close();

// HOBBIES LAINNYA

// $queDATAS = $dbconn->prepare("SELECT * FROM KTA_HOBBY");
// $queDATAS->execute();
// $hobbies = $queDATAS->get_result()->fetch_assoc();
// $hobby_id = $hobbies["ID"];
// $queDATAS->close();

// BIRTHPLACE

// $sqlData = "SELECT * FROM CITY ORDER BY CITY_NAME ASC";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $birthplace = $queDATA->get_result();
// $queDATA->close();

// POSTAL CODE

// $sqlData = "SELECT * FROM POSTAL_CODE";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $postal = $queDATA->get_result();
// $queDATA->close();

// GET USER DATA

$sqlData = "SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$userData = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

// PROVINCE

$sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$province = $queDATA->get_result();
$queDATA->close();

// PRICE

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '6'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

$upgradeFee = $price['REG_FEE'];
$adminFee = $price['ADMIN_FEE'];

// TAA CATEGORY
$list_ctgry = $dbconn->prepare("SELECT * FROM TAA_CATEGORY");
$list_ctgry->execute();
$category = $list_ctgry->get_result();
$list_ctgry->close();

// BANK CATEGORY
$bank_category = $dbconn->prepare("SELECT * FROM TAA_BANK");
$bank_category->execute();
$show_bank = $bank_category->get_result();
$bank_category->close();

// CLUB CHOICE
$club_category = $dbconn->prepare("SELECT * FROM TKT");
$club_category->execute();
$show_club = $club_category->get_result();
$club_category->close();

// KTA F_PIN
$ktaData = $dbconn->prepare("SELECT * FROM KTA WHERE F_PIN = '$f_pin'");
$ktaData->execute();
$ktaName = $ktaData->get_result()->fetch_assoc();
$ktaData->close();

// CONTENT CATEGORY
$content = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE");
$content->execute();
$contentCategory = $content->get_result();
$content->close();

// CONTENT CATEGORY 2
$contain = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE");
$contain->execute();
$containCategory = $contain->get_result()->fetch_assoc();
$contain->close();

// print_r ($containCategory);

// print_r($ktaName);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TKT IMI Club</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content {
            margin: 0;
            width: 100%;
        }

        .form-submit {
            margin-top: 10px;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        input[type="radio"] {
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }

        .collapse {
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        }

    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" style="padding: 0px" id="imiclub-form" enctype="multipart/form-data">
            
            <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b style="font-size: 14px">TKT IMI Club - Registration</b>
                    </div>
                </div>
            </div>

            <div class="container mx-auto mt-3">
                <div data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClubInfo" aria-expanded="false" aria-controls="collapseClubInfo">
                    <b>Club Information</b>
                    <img id="collapse-img-1" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClubInfo">
                    <div class="row gx-0 mt-3 mb-3">
                        <b class="mb-2">Club Photo</b>
                        <div id="photo-method" class="row mt-3" style="margin-bottom: 5px">
                            <!-- <span class="fotoprofil text-danger" style="position: absolute; margin-top: 8px; margin-left: 140px; z-index: 999">*</span> -->
                            <div class="col-6">
                                <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                                <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                            </div>
                            <div class="col-6">
                                <input style="margin-left: 12px" type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                                <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                            </div>
                        </div>
                        <div class="col-5">
                            <img id="club_image" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center" src="../assets/img/tab5/create-post-black.png?v=2">
                        </div>

                        <!-- <span class="profileimagestar text-danger" style="position: absolute; margin-top: 27px; margin-left: 249px; z-index: 999">*</span> -->

                        <div class="col-7 mt-3">
                            <label for="fotoProfile" id="profileLabelBtn" style="font-size: 15px; color: black; width: 80%; border-radius: 20px; background-color: white; border: 1px solid black; font-size: 14px; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Set Club Photo</label><br />
                            <small class="text-secondary" style="font-weight: 500; font-size: 8.5px">* Profile photo will be used for club profile</small>
                            <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)" />
                            <br><span id="fotoProfile-error" class="error" style="color: red"></span>
                        </div>
                    </div>
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="club_name" id="club_name" placeholder="Club Name" required />
                        </div>
                        <span class="namaklub text-danger" style="position: absolute; margin-top: 9px; margin-left: 82px; width: 10px">*</span>

                        <label id="username-exist" class="text-danger"></label>
                        <label id="username-not-exist" class="text-success"></label>
                    </div>

                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="content_preference" id="content_preference" placeholder="Content Preference" required data-bs-toggle="modal" data-bs-target="#modal-content-preference" required readonly/>
                        </div>
                        <span class="content-star text-danger" style="position: absolute; margin-top: 9px; margin-left: 133px; width: 10px">*</span>
                    </div>

                    <input type="hidden" id="cc_hidden" name="cc_hidden">

                    <p class="mt-3 mb-1"><b>Club Type</b></p>
                    <div class="row">
                        <div class="col-6">
                            <input type="radio" id="public" name="clubtype_radio" class="radio" value="1" checked>
                            <label for="public">&nbsp;&nbsp;Public</label><br>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="private" name="clubtype_radio" class="radio" value="2">
                            <label for="private">&nbsp;&nbsp;Private</label>
                        </div>
                    </div>

                    <div class="mt-3 mb-3">
                        <b>Club Category</b><br>

                        <span class="clubcategorystar text-danger" style="position: absolute; margin-top: -26px; margin-left: 107px">*</span>

                        <?php foreach ($category as $c) : ?>

                            <input type="checkbox" class="check mt-2" id="cat<?= $c['ID'] ?>" name="cat<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" onchange="changeCategory('<?= $c['ID'] ?>')">
                            <label for="cat<?= $c['ID'] ?>">&nbsp;&nbsp;&nbsp;<?= $c['CATEGORY'] ?></label><br>

                            

                        <?php endforeach; ?>

                        <span id="category-error" class="error" style="color: red"></span>
                    </div>

                    <input type="hidden" id="category" name="category">

                    <!-- <select class="mt-3 mb-2" style="margin-left: -5px" id="category" name="category" aria-label="" style="font-size: 16px" >
                        <option value="" selected>Choose Club Category</option>

                        <?php
                        foreach ($category as $ct) {
                        ?>
                                <option value="<?= $ct['ID'] ?>"><?= ucwords(strtolower($ct['CATEGORY'])) ?></option>
                                <?php
                            }
                                ?>

                    </select> -->

                    <!-- <span class="kategoriklub text-danger" style="position: absolute; margin-top: -45px; margin-left: 162px; z-index: 999">*</span> -->
                    <!-- <span id="category-error" class="error" style="color: red"></span> -->

                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="club_location" id="club_location" placeholder="Club Location" required />
                        </div>
                        <span class="lokasiklub text-danger" style="position: absolute; margin-top: 9px; margin-left: 98px; width: 10px">*</span>
                    </div>

                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="desc" id="desc" placeholder="Description" required />
                        </div>
                        <span class="deskripsiklub text-danger" style="position: absolute; margin-top: 9px; margin-left: 81px; width: 10px">*</span>
                    </div>
                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                    <b>Address</b>
                    <img id="collapse-img-3" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseAddress">
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="address" id="address" placeholder="Full Address" required />
                        </div>
                        <span class="alamatklub text-danger" style="position: absolute; margin-top: 9px; margin-left: 86px; width: 10px">*</span>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="rt" id="rt" placeholder="RT" onKeyPress="if (this.value.length == 3) return false;" required />
                        </div>
                        <span class="rtklub text-danger" style="position: absolute; margin-top: 10px; margin-left: 21px; width: 10px">*</span>

                        <div class="col-6">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="rw" id="rw" placeholder="RW" onKeyPress="if (this.value.length == 3) return false;" required />
                        </div>
                        <span class="rwklub text-danger" style="position: absolute; margin-top: 9px; margin-left: 53%; width: 10px">*</span>
                    </div>

                    <select class="mt-3 mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px">
                        <option value="" selected>Postcode</option>

                        <!-- <?php foreach ($postal as $p) : ?>
                            <option value="<?= $p['POSTAL_ID'] ?>"><?= $p['POSTAL_CODE'] ?></option>
                        <?php endforeach; ?> -->

                    </select>

                    <span class="kodeposklub text-danger" style="position: absolute; margin-top: -48px; margin-left: 81px; z-index: 999">*</span>
                    <span id="postcode-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                        <option value="" selected>Province</option>

                        <?php foreach ($province as $p) : ?>
                            <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span class="provinsiklub text-danger" style="position: absolute; margin-top: -48px; margin-left: 76px; z-index: 999">*</span>
                    <span id="province-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="city" name="city" aria-label="" style="font-size: 16px">
                        <option value="" selected>City</option>
                    </select>

                    <span class="kotaklub text-danger" style="position: absolute; margin-top: -48px; margin-left: 45px; z-index: 999">*</span>
                    <span id="city-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="district" name="district" aria-label="" style="font-size: 16px">
                        <option value="" selected>District</option>
                    </select>

                    <span class="distrikklub text-danger" style="position: absolute; margin-top: -48px; margin-left: 65px; z-index: 999">*</span>
                    <span id="district-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px">
                        <option value="" selected>District Word</option>
                    </select>

                    <span class="subdistrikklub text-danger" style="position: absolute; margin-top: -48px; margin-left: 106px; z-index: 999">*</span>
                    <span id="subdistrict-error" class="error" style="color: red"></span>

                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseBank" aria-expanded="false" aria-controls="collapseBank">
                    <b>Bank Information</b>
                    <img id="collapse-img-4" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseBank">
                    <select class="mt-3 mb-2" style="margin-left: -5px" id="bank-category" name="bank-category" aria-label="" style="font-size: 16px">
                        <option value="" selected>Select Bank</option>

                        <?php
                        foreach ($show_bank as $sb) {
                        ?>
                            <option value="<?= $sb['ID'] ?>"><?= $sb['BANK'] ?></option>
                        <?php
                        }
                        ?>

                    </select>
                    <span class="bankklub text-danger" style="position: absolute; margin-top: -48px; margin-left: 96px; z-index: 999">*</span>
                    <span id="bank-error" class="error" style="color: red"></span>

                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="acc-number" id="acc-number" placeholder="Account Number" required />
                        </div>
                        <span class="norekening text-danger" style="position: absolute; margin-top: 9px; margin-left: 122px; z-index: 999; width: 10px">*</span>
                    </div>

                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="acc-name" id="acc-name" placeholder="Account Name" required />
                        </div>
                        <span class="namanasabah text-danger" style="position: absolute; margin-top: 8px; margin-left: 107px; z-index: 999; width: 10px">*</span>
                    </div>

                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClubManagement" aria-expanded="false" aria-controls="collapseClubManagement">
                    <b>Club Management</b>
                    <img id="collapse-img-5" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClubManagement">

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="" id="checkRolesMe" name="checkRolesMe" onchange="assignRole()">
                        <label class="form-check-label" for="checkRolesMe">
                            <b>Assign all roles to me</b>
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="president" id="president" placeholder="President" required data-bs-toggle="modal" data-bs-target="#modal-president" />
                            <input type="hidden" name="president_phone" id="president_phone">
                            <input type="hidden" name="president_ktp" id="president_ktp">
                            <input type="hidden" name="president_kta" id="president_kta">
                        </div>
                        <span class="presiden text-danger" style="position: absolute; margin-top: 9px; margin-left: 69px; z-index: 999; width: 10px">*</span>
                        <span id="presiden-kta-error" class="error" style="color: red"></span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="secretary" id="secretary" placeholder="Secretary" required data-bs-toggle="modal" data-bs-target="#modal-secretary" />
                            <input type="hidden" name="secretary_phone" id="secretary_phone">
                            <input type="hidden" name="secretary_ktp" id="secretary_ktp">
                            <input type="hidden" name="secretary_kta" id="secretary_kta">
                        </div>
                        <span class="sekretaris text-danger" style="position: absolute; margin-top: 9px; margin-left: 71px; z-index: 999; width: 10px">*</span>
                        <span id="secretary-kta-error" class="error" style="color: red"></span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <?php
                            if ($ktaName) {
                            ?>
                                <input type="text" value="<?= $ktaName['NAME'] ?>" name="club-admin" id="club-admin" placeholder="Club Admin" required data-bs-toggle="modal" data-bs-target="#modal-admin" />
                            <?php
                            } else {
                            ?>
                                <input type="text" name="club-admin" id="club-admin" placeholder="Club Admin" required data-bs-toggle="modal" data-bs-target="#modal-admin" />
                            <?php
                            }
                            ?>
                            <input type="hidden" name="admin_phone" id="admin_phone">

                            <?php
                            if ($ktaName) {
                            ?>
                                <input type="hidden" name="admin_ktp" id="admin_ktp" value="<?= $ktaName['EKTP'] ?>">
                            <?php
                            } else {
                            ?>
                                <input type="hidden" name="admin_ktp" id="admin_ktp">
                            <?php
                            }
                            ?>

                            <?php
                            if ($ktaName) {
                            ?>
                                <input type="hidden" value="<?= $ktaName['NO_ANGGOTA'] ?>" name="admin_kta" id="admin_kta" required>
                            <?php
                            } else {
                            ?>
                                <input type="hidden" name="admin_kta" id="admin_kta" required>
                            <?php
                            }
                            ?>
                        </div>
                        <span id="admin-star" class="admin text-danger" style="position: absolute; margin-top: 9px; margin-left: 85px; z-index: 999; width: 10px">*</span>
                        <span id="admin-kta-error" class="error" style="color: red"></span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="finance" id="finance" placeholder="Finance" required data-bs-toggle="modal" data-bs-target="#modal-finance" />
                            <input type="hidden" name="finance_phone" id="finance_phone">
                            <input type="hidden" name="finance_ktp" id="finance_ktp">
                            <input type="hidden" name="finance_kta" id="finance_kta">
                        </div>
                        <span class="keuangan text-danger" style="position: absolute; margin-top: 9px; margin-left: 59px; z-index: 999; width: 10px">*</span>
                        <span id="finance-kta-error" class="error" style="color: red"></span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="vice-president" id="vice-president" placeholder="Vice President" data-bs-toggle="modal" data-bs-target="#modal-vice-president" />
                            <input type="hidden" name="vice_president_phone" id="vice_president_phone">
                            <input type="hidden" name="vice_president_ktp" id="vice_president_ktp">
                            <input type="hidden" name="vice_president_kta" id="vice_president_kta">
                        </div>
                        <!-- <span class="wakil text-danger" style="position: absolute; margin-top: 9px; margin-left: 102px; z-index: 999; width: 10px">*</span> -->
                        <span id="vice-kta-error" class="error" style="color: red"></span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="human-resource" id="human-resource" placeholder="Human Resource" data-bs-toggle="modal" data-bs-target="#modal-human-resource" />
                            <input type="hidden" name="human_resource_phone" id="human_resource_phone">
                            <input type="hidden" name="human_resource_ktp" id="human_resource_ktp">
                            <input type="hidden" name="human_resource_kta" id="human_resource_kta">
                        </div>
                        <!-- <span class="hrd text-danger" style="position: absolute; margin-top: 9px; margin-left: 123px; z-index: 999; width: 10px">*</span> -->
                        <span id="hrd-kta-error" class="error" style="color: red"></span>
                    </div>
                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentification" aria-expanded="false" aria-controls="collapseIdentification">
                    <b>Documents</b>
                    <img id="collapse-img-2" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseIdentification">

                    <!-- AD / ART DOCUMENT -->
                    <p class="mb-2">AD / ART Document</p>

                    <label for="docAdart" id="adartBtn" style="font-size: 15px; color: black; width: 37%; border-radius: 20px; background-color: white; border: 1px solid black; font-size: 14px; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                    <p id="adartFileName" style="display: inline;">No file chosen</p>
                    <input type="file" style="display:none;" accept=".pdf" name="docAdart" id="docAdart" class="photo" placeholder="File Dokumen AD / ART" />
                    <span class="adart text-danger" style="position: absolute; margin-top: -33px; margin-left: -82px; z-index: 999">*</span>
                    <span id="docAdart-error" class="error" style="color: red"></span>

                    <!-- END AD / ART DOCUMENT -->

                    <!-- CERTIFICATE DOCUMENT -->
                    <p class="mt-3 mb-2">Certificate Document</p>

                    <label for="docCertificate" id="certificateBtn" style="font-size: 15px; color: black; width: 37%; border-radius: 20px; background-color: white; border: 1px solid black; font-size: 14px; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                    <p id="certificateFileName" style="display: inline;">No file chosen</p>
                    <input type="file" style="display:none;" accept=".pdf" name="docCertificate" id="docCertificate" class="photo" placeholder="Foto Dokumen Sertifikat" />
                    <span class="sertifikat text-danger" style="position: absolute; margin-top: -33px; margin-left: -68px; z-index: 999">*</span>
                    <span id="docCertificate-error" class="error" style="color: red"></span>

                    <!-- END OF CERTIFICATE DOCUMENT -->

                    <!-- ADDITIONAL DOCUMENT -->
                    <p class="mt-3 mb-2">Additional Document</p>

                    <label for="docAdditional" id="additionalBtn" style="font-size: 15px; color: black; width: 37%; border-radius: 20px; background-color: white; border: 1px solid black; font-size: 14px; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                    <p id="additionalFileName" style="display: inline;">No file chosen</p>
                    <input type="file" style="display:none;" accept=".pdf" name="docAdditional" id="docAdditional" class="photo" placeholder="Foto Dokumen Tambahan" />
                    <span class="tambahan text-danger" style="position: absolute; margin-top: -33px; margin-left: -68px; z-index: 999">*</span>
                    <span id="docAdditional-error" class="error" style="color: red"></span>

                    <!-- END OF ADDITIONAL DOCUMENT -->

                </div>

                <div class="form-check mb-4" style="margin-top: 50px">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                        <b>I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!</b>
                    </label>
                </div>
            </div>

            <div style="width: 100%; height: 5px; background-color: #e5e5e5"></div>
            <div style="background-color: #eee; padding-top: 25px">
                <div class="container mx-auto" style="background-color: transparent">
                    <div class="form-group-2 mt-4 mb-4">
                        <div class="row mb-3">
                            <div class="col-6" style="color: #626262">
                                <b>Club Registration Fee</b>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <b style="font-size: 20px">Rp. <?= number_format($upgradeFee, 0, '', '.') ?></b>
                            </div>
                        </div>
                        <div class="row">
                            <select name="dropdownMenuSelectMethod" id="dropdownMenuSelectMethod" style="border: 1px solid #d7d7d7" onchange="selectMethod(this.value);">
                                <option value="" selected>Select Payment Method</option>
                                <option value="CARD">CARD</option>
                                <!-- <option value="OVO">OVO</option>
                                <option value="DANA">DANA</option>
                                <option value="LINKAJA">LINKAJA</option>
                                <option value="SHOPEEPAY">SHOPEEPAY</option> -->
                                <option value="QRIS">QRIS</option>
                            </select>
                            <span id="payment-error" class="error" style="color: red"></span>
                        </div>
                        <!-- <div class="row">
                            <div class="col-6" style="color: #626262">
                                Administration Fee
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="color: #626262">
                                Tax(10%)
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <b>Rp.
                                <?php

                                $tax = $upgradeFee * 10 / 100;
                                $total_tax = number_format($tax, 0, '', '.');
                                echo $total_tax;


                                ?></b>
                            </div>
                        </div> -->
                        <!-- <div class="row mt-2">
                            <div class="col-6" style="color: #626262">
                                Total Payment
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <b style="font-size: 20px">Rp. <?= number_format($upgradeFee + $adminFee + $tax, 0, '', '.') ?></b>
                            </div>
                        </div> -->
                    </div>
                </div>

                <input type="hidden" name="president_f_pin" id="president_f_pin">
                <input type="hidden" name="secretary_f_pin" id="secretary_f_pin">
                <input type="hidden" name="finance_f_pin" id="finance_f_pin">
                <input type="hidden" name="vice_president_f_pin" id="vice_president_f_pin">
                <input type="hidden" name="hrd_f_pin" id="hrd_f_pin">

                <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                    <button type="submit" class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 50%; height: 50px; color: white" onclick="selectizeValid()"><b>Pay Now</b></button>
                </div>
                <!-- <div style="width: 100%; height: 100px; background-color: #fff"></div> -->
            </div>
        </form>

        <!-- The Modal -->
        <!-- <div id="modalProgress" class="modal"> -->

        <!-- Modal content -->
        <!-- <div class="modal-content">
                <p>Upload in progress...</p>
            </div>

        </div> -->

        <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0" id="modalProgress">
                        <p>Upload in progress...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div id="modalSuccess" class="modal"> -->

        <!-- Modal content -->
        <!-- <div class="modal-content">
                <p>Successfully upload data</p>
            </div>

        </div> -->

        <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center" id="modalSuccess">
                        <img src="../assets/img/success.png" style="width: 100px">
                        <h1 class="mt-3">TKT IMI Club Registration Success!</h1>
                        <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center">
                                <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Main Menu</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 90vh">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-error-body">
                    <p id="error-modal-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p id="validation-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-president" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">President</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <p>Insert the member info for the role</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkPresident">
                        <label class="form-check-label" for="checkPresident">
                            Assign this role to me
                        </label>
                    </div> -->
                    <input type="text" pattern="[0-9]*" maxlength="20" name="president_kta_modal" id="president_kta_modal" placeholder="KTA Number" required />
                    <p id="check-kta-number-president" class="text-danger">This field is required</p>
                    <p id="kta-found-president" class="text-success">KTA data found.</p>
                    <p id="kta-not-found-president" class="text-danger">KTA data not found.</p>
                    <input type="text" name="president_name" id="president_name" placeholder="Full Name" required />
                    <div class="row">
                        <div class="col-2">
                            <input type="text" pattern="[0-9]*" maxlength="20" placeholder="+62" readonly />
                        </div>
                        <div class="col-10">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="president_phone_modal" id="president_phone_modal" placeholder="Phone Number" required />
                        </div>
                    </div>
                    <input type="text" pattern="[0-9]*" maxlength="20" name="president_ktp_modal" id="president_ktp_modal" placeholder="Identification Number" required />
                    <!-- <input type="text" pattern="[0-9]*" maxlength="20" name="president_kta_modal" id="president_kta_modal" placeholder="KTA Number" required/> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="savePresident()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-secretary" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Secretary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" pattern="[0-9]*" maxlength="20" name="secretary_kta_modal" id="secretary_kta_modal" placeholder="KTA Number" required />
                    <p id="check-kta-number-secretary" class="text-danger">This field is required</p>
                    <p id="kta-found-secretary" class="text-success">KTA data found.</p>
                    <p id="kta-not-found-secretary" class="text-danger">KTA data not found.</p>
                    <input type="text" name="secretary_name" id="secretary_name" placeholder="Full Name" required />
                    <div class="row">
                        <div class="col-2">
                            <input type="text" pattern="[0-9]*" maxlength="20" placeholder="+62" readonly />
                        </div>
                        <div class="col-10">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="secretary_phone_modal" id="secretary_phone_modal" placeholder="Phone Number" required />
                        </div>
                    </div>
                    <input type="text" pattern="[0-9]*" maxlength="20" name="secretary_ktp_modal" id="secretary_ktp_modal" placeholder="Identification Number" required />
                    <!-- <input type="text" pattern="[0-9]*" maxlength="20" name="secretary_kta_modal" id="secretary_kta_modal" placeholder="KTA Number" required/> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveSecretary()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-admin" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if ($ktaName) {
                    ?>
                        <input type="text" pattern="[0-9]*" maxlength="20" value="<?= $ktaName['NO_ANGGOTA'] ?>" name="admin_kta_modal" readonly id="admin_kta_modal" placeholder="KTA Number" required />
                    <?php
                    } else {
                    ?>
                        <input type="text" pattern="[0-9]*" maxlength="20" name="admin_kta_modal" id="admin_kta_modal" placeholder="KTA Number" required />
                    <?php
                    }
                    ?>
                    <p id="check-kta-number" class="text-danger">This field is required</p>
                    <p id="kta-found" class="text-success">KTA data found.</p>
                    <p id="kta-not-found" class="text-danger">KTA data not found.</p>
                    <?php
                    if ($ktaName) {
                    ?>
                        <input type="text" value="<?= $ktaName['NAME'] ?>" name="admin_name" id="admin_name" placeholder="Full Name" required readonly />
                    <?php
                    } else {
                    ?>
                        <input type="text" name="admin_name" id="admin_name" placeholder="Full Name" required />
                    <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-2">
                            <input type="text" pattern="[0-9]*" maxlength="20" placeholder="+62" readonly />
                        </div>
                        <div class="col-10">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="admin_phone_modal" id="admin_phone_modal" placeholder="Phone Number" required />
                        </div>
                    </div>
                    <?php
                    if ($ktaName) {
                    ?>
                        <input type="text" pattern="[0-9]*" maxlength="20" name="admin_ktp_modal" id="admin_ktp_modal" placeholder="Identification Number" value="<?= $ktaName['EKTP'] ?>" required />
                    <?php
                    } else {
                    ?>
                        <input type="text" pattern="[0-9]*" maxlength="20" name="admin_ktp_modal" id="admin_ktp_modal" placeholder="Identification Number" required />
                    <?php
                    }
                    ?>

                    <!-- <input type="text" pattern="[0-9]*" maxlength="20" name="admin_kta_modal" id="admin_kta_modal" placeholder="KTA Number" required/> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveAdmin()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-finance" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Finance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" pattern="[0-9]*" maxlength="20" name="finance_kta_modal" id="finance_kta_modal" placeholder="KTA Number" required />
                    <p id="check-kta-number-finance" class="text-danger">This field is required</p>
                    <p id="kta-found-finance" class="text-success">KTA data found.</p>
                    <p id="kta-not-found-finance" class="text-danger">KTA data not found.</p>
                    <input type="text" name="finance_name" id="finance_name" placeholder="Full Name" required />
                    <div class="row">
                        <div class="col-2">
                            <input type="text" pattern="[0-9]*" maxlength="20" placeholder="+62" readonly />
                        </div>
                        <div class="col-10">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="finance_phone_modal" id="finance_phone_modal" placeholder="Phone Number" required />
                        </div>
                    </div>
                    <input type="text" pattern="[0-9]*" maxlength="20" name="finance_ktp_modal" id="finance_ktp_modal" placeholder="Identification Number" required />
                    <!-- <input type="text" pattern="[0-9]*" maxlength="20" name="finance_kta_modal" id="finance_kta_modal" placeholder="KTA Number" required/> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveFinance()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-vice-president" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vice President</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" pattern="[0-9]*" maxlength="20" name="vice_president_kta_modal" id="vice_president_kta_modal" placeholder="KTA Number" required />
                    <p id="check-kta-number-vice" class="text-danger">This field is required</p>
                    <p id="kta-found-vice" class="text-success">KTA data found.</p>
                    <p id="kta-not-found-vice" class="text-danger">KTA data not found.</p>
                    <input type="text" name="vice_president_name" id="vice_president_name" placeholder="Full Name" required />
                    <div class="row">
                        <div class="col-2">
                            <input type="text" pattern="[0-9]*" maxlength="20" placeholder="+62" readonly />
                        </div>
                        <div class="col-10">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="vice_president_phone_modal" id="vice_president_phone_modal" placeholder="Phone Number" required />
                        </div>
                    </div>
                    <input type="text" pattern="[0-9]*" maxlength="20" name="vice_president_ktp_modal" id="vice_president_ktp_modal" placeholder="Identification Number" required />
                    <!-- <input type="text" pattern="[0-9]*" maxlength="20" name="vice_president_kta_modal" id="vice_president_kta_modal" placeholder="KTA Number" required/> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveVicePresident()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-human-resource" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Human Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" pattern="[0-9]*" maxlength="20" name="human_resource_kta_modal" id="human_resource_kta_modal" placeholder="KTA Number" required />
                    <p id="check-kta-number-hrd" class="text-danger">This field is required</p>
                    <p id="kta-found-hrd" class="text-success">KTA data found.</p>
                    <p id="kta-not-found-hrd" class="text-danger">KTA data not found.</p>
                    <input type="text" name="human_resource_name" id="human_resource_name" placeholder="Full Name" required />
                    <div class="row">
                        <div class="col-2">
                            <input type="text" pattern="[0-9]*" maxlength="20" placeholder="+62" readonly />
                        </div>
                        <div class="col-10">
                            <input type="text" pattern="[0-9]*" maxlength="20" name="human_resource_phone_modal" id="human_resource_phone_modal" placeholder="Phone Number" required />
                        </div>
                    </div>
                    <input type="text" pattern="[0-9]*" maxlength="20" name="human_resource_ktp_modal" id="human_resource_ktp_modal" placeholder="Identification Number" required />
                    <!-- <input type="text" pattern="[0-9]*" maxlength="20" name="human_resource_kta_modal" id="human_resource_kta_modal" placeholder="KTA Number" required/> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveHumanResource()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalMembership">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">TKT IMI Club Registration Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-dark mt-3" id="modalMembership-mainMenu" style="background-color: #f66701; border: 1px solid #f66701">Main Menu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-content-preference" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Content Preference</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You can choose more than 3 categories</p>
                    <?php 
                    foreach ($contentCategory as $ckey => $cc) {
                        // print_r ($cc['CONTENT_CATEGORY']);
                        ?>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <input class="form-check-input" type="checkbox" value="<?= $cc['ID'] ?>" id="checkCategory<?= $ckey ?>" name="checkCategory" onclick="saveCC('<?= $ckey ?>', '<?= $cc['CONTENT_CATEGORY'] ?>')">
                                <label class="ms-2 form-check-label" for="checkCategory">
                                    <?= $cc['CONTENT_CATEGORY'] ?>
                                </label>
                            </div>
                        </div>
                        <?php
                    } 
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 45%">Close</button>
                    <button type="button" class="btn btn-dark" style="width: 45%; background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveCbutton()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 6;
        var is_takken = 0;
        localStorage.setItem('grand-total', <?= $upgradeFee + $adminFee + $tax ?>);
    </script>

    <script>
        var title = 'Club Registration Fee';
        var price = '<?= number_format($upgradeFee, 0, '', '.') ?>';
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_tax = '<?= number_format($upgradeFee * 10 / 100, 0, '', '.'); ?>';
        var total_price = '<?= number_format($upgradeFee + $adminFee + $tax, 0, '', '.') ?>';
    </script>

    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-imiclub.js?v=<?php echo $ver; ?>"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <!-- Javascript -->
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>
    $('#check-kta-number').hide();
    $('#kta-found').hide();
    $('#kta-not-found').hide();

    $('#check-kta-number-president').hide();
    $('#kta-found-president').hide();
    $('#kta-not-found-president').hide();

    $('#check-kta-number-secretary').hide();
    $('#kta-found-secretary').hide();
    $('#kta-not-found-secretary').hide();

    $('#check-kta-number-finance').hide();
    $('#kta-found-finance').hide();
    $('#kta-not-found-finance').hide();

    $('#check-kta-number-vice').hide();
    $('#kta-found-vice').hide();
    $('#kta-not-found-vice').hide();

    $('#check-kta-number-hrd').hide();
    $('#kta-found-hrd').hide();
    $('#kta-not-found-hrd').hide();

    var available_kta = 0;

    $(document).ready(function(e) {

        // $('#category').selectize();
        $('#postcode').selectize();
        $('#province').selectize();
        $('#city').selectize();
        $('#district').selectize();
        $('#subdistrict').selectize();
        $('#bank-category').selectize();
        $('#club-category').selectize();
        $('#club-category-2').selectize();
        $('#club-category-3').selectize();
        $('#club-category-4').selectize();
        $('#club-category-5').selectize();
        $('#club-category-6').selectize();

        $('#dropdownMenuSelectMethod').selectize();

        $('#dropdownMenuSelectMethod-selectized').attr('readonly', true);

        getDataCookie();

        $("#postcode-selectized").bind("change paste keyup", function() {

            var $select = $(document.getElementById('postcode'));
            var selectize = $select[0].selectize;

            var postcode = $(this).val();
            var formData = new FormData();

            formData.append('postcode', postcode);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                    // // console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);

                    Object.keys(obj).forEach(function(item) {

                        // // console.log(obj[item]['POSTAL_CODE']);                    
                        selectize.addOption({
                            value: obj[item]['POSTAL_ID'],
                            text: obj[item]['POSTAL_CODE']
                        });

                    });

                }
            }
            xmlHttp.open("post", "../logics/get_postcode");
            xmlHttp.send(formData);

            $('#modalSuccess').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $("#postcode").bind("change", function() {

            var postcode = $(this).val();
            var formData = new FormData();

            formData.append('postcode', postcode);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                    // console.log(xmlHttp.responseText);

                    const address = JSON.parse(xmlHttp.responseText);

                    var city_id = address.CITY_ID;
                    var subdis_id = address.SUBDIS_ID;
                    var district_id = address.DIS_ID;
                    var province_id = address.PROV_ID;

                    var city_name = capitalize(address.CITY_NAME);
                    var subdis_name = capitalize(address.SUBDIS_NAME);
                    var district_name = capitalize(address.DIS_NAME);
                    var province_name = capitalize(address.PROV_NAME);

                    var $select = $(document.getElementById('province'));
                    var selectize = $select[0].selectize;
                    selectize.addOption({
                        value: province_id,
                        text: province_name
                    });
                    selectize.setValue(province_id);

                    var $select2 = $(document.getElementById('city'));
                    var selectize2 = $select2[0].selectize;
                    selectize2.addOption({
                        value: city_id,
                        text: city_name
                    });
                    selectize2.setValue(city_id);

                    var $select3 = $(document.getElementById('district'));
                    var selectize3 = $select3[0].selectize;
                    selectize3.addOption({
                        value: district_id,
                        text: district_name
                    });
                    selectize3.setValue(district_id);

                    var $select4 = $(document.getElementById('subdistrict'));
                    var selectize4 = $select4[0].selectize;
                    selectize4.addOption({
                        value: subdis_id,
                        text: subdis_name
                    });
                    selectize4.setValue(subdis_id);

                    // // console.log(city_id);
                    // // console.log(subdis_id);
                    // // console.log(district_id);
                    // // console.log(province_id);
                    // // console.log(city_name);
                    // // console.log(subdis_name);
                    // // console.log(district_name);
                    // // console.log(province_name);

                    // $('#city').val(capitalize(city));
                    // $('#province').val(capitalize(province));
                    // $('#district').val(capitalize(district));
                    // $('#district_word').val(capitalize(subdis));

                }
            }
            xmlHttp.open("post", "../logics/get_full_address");
            xmlHttp.send(formData);
        });

        // FOR RED DOT IN SELECTIZE

        $("#postcode-selectized").bind("change paste keyup", function() {

            if ($('#postcode-selectized').val()) {
                $('.kodeposklub').hide();
            } else {
                $('.kodeposklub').show();
            }
        });

        $("#province-selectized").bind("change paste keyup", function() {

            if ($('#province-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $("#city-selectized").bind("change paste keyup", function() {

            if ($('#city-selectized').val()) {
                $('.kotaklub').hide();
            } else {
                $('.kotaklub').show();
            }
        });

        $("#district-selectized").bind("change paste keyup", function() {

            if ($('#district-selectized').val()) {
                $('.distrikklub').hide();
            } else {
                $('.distrikklub').show();
            }
        });

        $("#subdistrict-selectized").bind("change paste keyup", function() {

            if ($('#subdistrict-selectized').val()) {
                $('.subdistrikklub').hide();
            } else {
                $('.subdistrikklub').show();
            }
        });

        $("#bank-category-selectized").bind("change paste keyup", function() {

            if ($('#bank-category-selectized').val()) {
                $('.bankklub').hide();
            } else {
                $('.bankklub').show();
            }
        });

    });

    // FOR VALIDATE KTA PRESIDENT

    $("#president_kta_modal").bind("change paste keyup", function() {

        var no_anggota = $('#president_kta_modal').val();

        var formData = new FormData();

        formData.append('no_anggota', no_anggota);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0) {

                    $('#check-kta-number-president').hide();
                    $('#kta-found-president').hide();
                    $('#kta-not-found-president').show();

                    $('#president_name').val("");
                    $('#president_ktp_modal').val("");

                    $('#president').val("");
                    $('#president_ktp').val("");

                    available_kta = 0;

                } else {

                    var name = response.NAME;
                    var ktp = response.EKTP;
                    var f_pin = response.F_PIN;

                    $('#president_name').val(name);
                    $('#president_ktp_modal').val(ktp);
                    $('#president_f_pin').val(f_pin);

                    $('#president').val(name);
                    $('#president_ktp').val(ktp);


                    $('#check-kta-number-president').hide();
                    $('#kta-found-president').show();
                    $('#kta-not-found-president').hide();

                    available_kta = 1;

                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta");
        xmlHttp.send(formData);

    });

    // FOR VALIDATE KTA SECRETARY

    $("#secretary_kta_modal").bind("change paste keyup", function() {

        var no_anggota = $('#secretary_kta_modal').val();

        var formData = new FormData();

        formData.append('no_anggota', no_anggota);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0) {

                    $('#check-kta-number-secretary').hide();
                    $('#kta-found-secretary').hide();
                    $('#kta-not-found-secretary').show();

                    $('#secretary_name').val("");
                    $('#secretary_ktp_modal').val("");

                    $('#secretary').val("");
                    $('#secretary_ktp').val("");

                    available_kta = 0;

                } else {

                    var name = response.NAME;
                    var ktp = response.EKTP;
                    var f_pin = response.F_PIN;

                    $('#secretary_name').val(name);
                    $('#secretary_ktp_modal').val(ktp);
                    $('#secretary_f_pin').val(f_pin);

                    $('#secretary').val(name);
                    $('#secretary_ktp').val(ktp);

                    $('#check-kta-number-secretary').hide();
                    $('#kta-found-secretary').show();
                    $('#kta-not-found-secretary').hide();

                    available_kta = 1;

                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta");
        xmlHttp.send(formData);

    });

    // FOR VALIDATE KTA ADMIN

    $("#admin_kta_modal").bind("change paste keyup", function() {

        var no_anggota = $('#admin_kta_modal').val();

        var formData = new FormData();

        formData.append('no_anggota', no_anggota);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0) {

                    $('#check-kta-number').hide();
                    $('#kta-found').hide();
                    $('#kta-not-found').show();

                    $('#admin_name').val("");
                    $('#admin_ktp_modal').val("");

                    $('#admin').val("");
                    $('#admin_ktp').val("");

                    available_kta = 0;

                } else {

                    var name = response.NAME;
                    var ktp = response.EKTP;

                    $('#admin_name').val(name);
                    $('#admin_ktp_modal').val(ktp);

                    $('#admin').val(name);
                    $('#admin_ktp').val(ktp);

                    $('#check-kta-number').hide();
                    $('#kta-found').show();
                    $('#kta-not-found').hide();

                    available_kta = 1;

                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta");
        xmlHttp.send(formData);

    });

    // FOR VALIDATE KTA FINANCE

    $("#finance_kta_modal").bind("change paste keyup", function() {

        var no_anggota = $('#finance_kta_modal').val();

        var formData = new FormData();

        formData.append('no_anggota', no_anggota);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0) {

                    $('#check-kta-number-finance').hide();
                    $('#kta-found-finance').hide();
                    $('#kta-not-found-finance').show();

                    $('#finance_name').val("");
                    $('#finance_ktp_modal').val("");

                    $('#finance').val("");
                    $('#finance_ktp').val("");

                    available_kta = 0;

                } else {

                    var name = response.NAME;
                    var ktp = response.EKTP;
                    var f_pin = response.F_PIN;

                    $('#finance_name').val(name);
                    $('#finance_ktp_modal').val(ktp);
                    $('#finance_f_pin').val(f_pin);

                    $('#finance').val(name);
                    $('#finance_ktp').val(ktp);

                    $('#check-kta-number-finance').hide();
                    $('#kta-found-finance').show();
                    $('#kta-not-found-finance').hide();

                    available_kta = 1;

                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta");
        xmlHttp.send(formData);

    });

    // FOR VALIDATE KTA VICE PRESIDENT

    $("#vice_president_kta_modal").bind("change paste keyup", function() {

        var no_anggota = $('#vice_president_kta_modal').val();

        var formData = new FormData();

        formData.append('no_anggota', no_anggota);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0) {

                    $('#check-kta-number-vice').hide();
                    $('#kta-found-vice').hide();
                    $('#kta-not-found-vice').show();

                    $('#vice_president_name').val("");
                    $('#vice_president_ktp_modal').val("");

                    $('#vice_president').val("");
                    $('#vice_president_ktp').val("");

                    available_kta = 0;

                } else {

                    var name = response.NAME;
                    var ktp = response.EKTP;
                    var f_pin = response.F_PIN;

                    $('#vice_president_name').val(name);
                    $('#vice_president_ktp_modal').val(ktp);
                    $('#vice_president_f_pin').val(f_pin);

                    $('#vice_president').val(name);
                    $('#vice_president_ktp').val(ktp);

                    $('#check-kta-number-vice').hide();
                    $('#kta-found-vice').show();
                    $('#kta-not-found-vice').hide();

                    available_kta = 1;

                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta");
        xmlHttp.send(formData);

    });

    // FOR VALIDATE KTA HRD

    $("#human_resource_kta_modal").bind("change paste keyup", function() {

        var no_anggota = $('#human_resource_kta_modal').val();

        var formData = new FormData();

        formData.append('no_anggota', no_anggota);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0) {

                    $('#check-kta-number-hrd').hide();
                    $('#kta-found-hrd').hide();
                    $('#kta-not-found-hrd').show();

                    $('#human_resource_name').val("");
                    $('#human_resource_ktp_modal').val("");

                    $('#human_resource').val("");
                    $('#human_resource_ktp').val("");

                    available_kta = 0;

                } else {

                    var name = response.NAME;
                    var ktp = response.EKTP;
                    var f_pin = response.F_PIN;

                    $('#human_resource_name').val(name);
                    $('#human_resource_ktp_modal').val(ktp);
                    $('#hrd_f_pin').val(f_pin);

                    $('#human_resource').val(name);
                    $('#human_resource_ktp').val(ktp);

                    $('#check-kta-number-hrd').hide();
                    $('#kta-found-hrd').show();
                    $('#kta-not-found-hrd').hide();

                    available_kta = 1;

                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta");
        xmlHttp.send(formData);

    });

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    $(".add-hobby").hide();

    $("#hobby").change(function() {
        var value = $(this).val();

        if (value == 6) {
            $(".add-hobby").show();
        } else {
            $(".add-hobby").hide();
        }
    });

    // FOR ARROW COLLAPSE

    $('#collapseClubInfo').on('shown.bs.collapse', function() {
        $('#collapse-img-1').attr('src', '../assets/img/arrow-up.png');
    });

    $('#collapseClubInfo').on('hidden.bs.collapse', function() {
        $('#collapse-img-1').attr('src', '../assets/img/arrow-down.png');
    });

    $('#collapseIdentification').on('shown.bs.collapse', function() {
        $('#collapse-img-2').attr('src', '../assets/img/arrow-up.png');
    });

    $('#collapseIdentification').on('hidden.bs.collapse', function() {
        $('#collapse-img-2').attr('src', '../assets/img/arrow-down.png');
    });

    $('#collapseAddress').on('shown.bs.collapse', function() {
        $('#collapse-img-3').attr('src', '../assets/img/arrow-up.png');
    });

    $('#collapseAddress').on('hidden.bs.collapse', function() {
        $('#collapse-img-3').attr('src', '../assets/img/arrow-down.png');
    });

    $('#collapseBank').on('shown.bs.collapse', function() {
        $('#collapse-img-4').attr('src', '../assets/img/arrow-up.png');
    });

    $('#collapseBank').on('hidden.bs.collapse', function() {
        $('#collapse-img-4').attr('src', '../assets/img/arrow-down.png');
    });

    $('#collapseClubManagement').on('shown.bs.collapse', function() {
        $('#collapse-img-5').attr('src', '../assets/img/arrow-up.png');
    });

    $('#collapseClubManagement').on('hidden.bs.collapse', function() {
        $('#collapse-img-5').attr('src', '../assets/img/arrow-down.png');
    });

    $('#collapseCL').on('shown.bs.collapse', function() {
        $('#collapse-img-6').attr('src', '../assets/img/arrow-up.png');
    });

    $('#collapseCL').on('hidden.bs.collapse', function() {
        $('#collapse-img-6').attr('src', '../assets/img/arrow-down.png');
    });

    // select[0].selectize.close();

    // PROVINCE TO CITY

    $("#province").bind("change", function() {

        var $select = $(document.getElementById('city'));
        var selectize = $select[0].selectize;

        // CLEAR CITY
        selectize.clear();
        selectize.clearOptions();

        // CLEAR DISTRICT
        var $select2 = $(document.getElementById('district'));
        var selectize2 = $select2[0].selectize;
        selectize2.clearOptions();
        selectize2.clear();

        // CLEAR SUBDISTRICT
        var $select3 = $(document.getElementById('subdistrict'));
        var selectize3 = $select3[0].selectize;
        selectize3.clearOptions();
        selectize3.clear();

        var province = $(this).val();
        localStorage.setItem("province", province);

        var formData = new FormData();

        formData.append('province', province);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(xmlHttp.responseText);

                Object.keys(obj).forEach(function(item) {

                    // // console.log(obj[item]['POSTAL_CODE']);

                    selectize.addOption({
                        value: obj[item]['CITY_ID'],
                        text: capitalize(obj[item]['CITY_NAME'])
                    });

                });
            }
        }
        xmlHttp.open("post", "../logics/get_city");
        xmlHttp.send(formData);
    });

    // CITY TO DISTRICT

    $("#city").bind("change", function() {

        var $select = $(document.getElementById('district'));
        var selectize = $select[0].selectize;

        // CLEAR CITY
        selectize.clearOptions();
        selectize.clear();

        // CLEAR DISTRICT
        var $select2 = $(document.getElementById('district'));
        var selectize2 = $select2[0].selectize;
        selectize2.clearOptions();
        selectize2.clear();

        // CLEAR SUBDISTRICT
        var $select3 = $(document.getElementById('subdistrict'));
        var selectize3 = $select3[0].selectize;
        selectize3.clearOptions();
        selectize3.clear();

        var city = $(this).val();
        localStorage.setItem("city", city);

        var formData = new FormData();

        formData.append('city', city);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(xmlHttp.responseText);

                Object.keys(obj).forEach(function(item) {

                    // // console.log(obj[item]['POSTAL_CODE']);

                    selectize.addOption({
                        value: obj[item]['DIS_ID'],
                        text: capitalize(obj[item]['DIS_NAME'])
                    });

                });
            }
        }
        xmlHttp.open("post", "../logics/get_district");
        xmlHttp.send(formData);
    });

    // DISTRICT TO SUBDIS

    $("#district").bind("change", function() {

        var $select = $(document.getElementById('subdistrict'));
        var selectize = $select[0].selectize;

        // CLEAR DISTRICT
        selectize.clearOptions();
        selectize.clear();

        // CLEAR SUBDISTRICT
        var $select3 = $(document.getElementById('subdistrict'));
        var selectize3 = $select3[0].selectize;
        selectize3.clearOptions();
        selectize3.clear();

        var district = $(this).val();
        localStorage.setItem("district", district);

        var formData = new FormData();

        formData.append('district', district);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(xmlHttp.responseText);

                Object.keys(obj).forEach(function(item) {

                    // // console.log(obj[item]['POSTAL_CODE']);

                    selectize.addOption({
                        value: obj[item]['SUBDIS_ID'],
                        text: capitalize(obj[item]['SUBDIS_NAME'])
                    });

                });
            }
        }
        xmlHttp.open("post", "../logics/get_subdistrict");
        xmlHttp.send(formData);
    });

    // SUBDIS TO ALL ADDRESS

    $("#subdistrict").bind("change", function() {

        var province = localStorage.getItem("province");
        var city = localStorage.getItem("city");
        var district = localStorage.getItem("district");
        var subdistrict = $(this).val();

        // console.log("Province :"+province);
        // console.log("City :"+city);
        // console.log("District :"+district);
        // console.log("Subdis :"+subdistrict);

        var formData = new FormData();

        formData.append('province', province);
        formData.append('city', city);
        formData.append('district', district);
        formData.append('subdistrict', subdistrict);
        formData.append('postcode', 0);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // // console.log(">>>"+xmlHttp.responseText);
                const postcode = JSON.parse(xmlHttp.responseText);

                var $select = $(document.getElementById('postcode'));
                var selectize = $select[0].selectize;

                // CLEAR POSTAL CODE
                if ($('#postcode').val().trim() === '') {
                    selectize.clearOptions();
                    selectize.clear();

                    selectize.addOption({
                        value: postcode.POSTAL_ID,
                        text: postcode.POSTAL_CODE
                    });
                    selectize.setValue(postcode.POSTAL_ID);
                }

            }
        }
        xmlHttp.open("post", "../logics/get_postcode");
        xmlHttp.send(formData);
    });
</script>

<script>
    var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
    var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
    var loadFile = function(event) {
        //   var reader = new FileReader();
        //   reader.onload = function() {

        //     $('#fotoProfile-error').text("");
        //     $('#club_image').attr('src', reader.result);

        //     }
        //     reader.readAsDataURL(event.target.files[0]);
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
                setImage(reader.result, number);
                // vid.load();
            }
        }

        var dataURLToBlob = function(dataURL) {
            var BASE64_MARKER = ';base64,';
            if (dataURL.indexOf(BASE64_MARKER) == -1) {
                var parts = dataURL.split(',');
                var contentType = parts[0].split(':')[1];
                var raw = parts[1];

                return new Blob([raw], {
                    type: contentType
                });
            }

            var parts = dataURL.split(BASE64_MARKER);
            var contentType = parts[0].split(':')[1];
            var raw = window.atob(parts[1]);
            var rawLength = raw.length;

            var uInt8Array = new Uint8Array(rawLength);

            for (var i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }

            return new Blob([uInt8Array], {
                type: contentType
            });
        }

        function imageLoaded() {
            let imgDataURL, container, fileInput;

            canvas = document.createElement('canvas');
            ctx = canvas.getContext("2d");
            if (img.width > 480) {
                // var canvas = document.createElement('canvas'),
                var max_size = 480, // TODO : pull max size from a site config
                    width = img.width,
                    height = img.height;
                if (width > height) {
                    if (width > max_size) {
                        height *= max_size / width;
                        width = max_size;
                    }
                } else {
                    if (height > max_size) {
                        width *= max_size / height;
                        height = max_size;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(img, 0, 0, width, height);
                imgDataURL = canvas.toDataURL('image/webp');
            } else {
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                ctx.drawImage(img, 0, 0);
                imgDataURL = canvas.toDataURL("image/webp");
            }
            let blobData = dataURLToBlob(imgDataURL);
            container = new DataTransfer();
            file = new File([blobData], "tkt_pfp.webp",{type:"image/webp", lastModified:new Date().getTime()});
            container.items.add(file);
            let fileInputElement = document.getElementById('fotoProfile');
            fileInputElement.files = container.files;
            console.log(fileInputElement.files);
            // $("#fotoProfile").val(file);
            $('#fotoProfile-error').text("");
            $('#club_image').attr('src', imgDataURL);
            // checkValid();
        }
    };

    var $input = $('#acc-number')
    $input.keyup(function(e) {
        var max = 18;
        if ($input.val().length > max) {
            $input.val($input.val().substr(0, max));
        }
    });
</script>

<script>
    $(".fotoprofil").show();
    $(".namaklub").show();
    $(".kategoriklub").show();
    $(".lokasiklub").show();
    $(".deskripsiklub").show();
    $(".alamatklub").show();
    $(".rtklub").show();
    $(".rwklub").show();
    $(".kodeposklub").show();
    $(".provinsiklub").show();
    $(".kotaklub").show();
    $(".distrikklub").show();
    $(".subdistrikklub").show();
    $(".bankklub").show();
    $(".norekening").show();
    $(".namanasabah").show();
    $(".presiden").show();
    $(".sekretaris").show();
    $(".admin").show();
    $(".keuangan").show();
    $(".wakil").show();
    $(".hrd").show();
    $(".adart").show();
    $(".sertifikat").show();
    $(".tambahan").show();

    $("#fotoProfile").change(function() {
        var valimage = $(this).val();

        if (valimage) {
            $(".fotoprofil").hide();
        } else {
            $(".fotoprofil").show();
        }
    });

    $("#club_name").bind("change paste keyup", function() {
        var valname = $(this).val();

        if (valname) {
            $(".namaklub").hide();
        } else {
            $(".namaklub").show();
        }
    });

    $("#category").change(function() {
        var category = $(this).val();

        if (category) {
            $(".kategoriklub").hide();
        } else {
            $(".kategoriklub").show();
        }
    });

    $("#club_location").bind("change paste keyup", function() {
        var clublocation = $(this).val();

        if (clublocation) {
            $(".lokasiklub").hide();
        } else {
            $(".lokasiklub").show();
        }
    });

    $("#desc").bind("change paste keyup", function() {
        var clubdesc = $(this).val();

        if (clubdesc) {
            $(".deskripsiklub").hide();
        } else {
            $(".deskripsiklub").show();
        }
    });

    $("#address").bind("change paste keyup", function() {
        var address = $(this).val();

        if (address) {
            $(".alamatklub").hide();
        } else {
            $(".alamatklub").show();
        }
    });

    $("#rt").bind("change paste keyup", function() {
        var rt = $(this).val();

        if (rt) {
            $(".rtklub").hide();
        } else {
            $(".rtklub").show();
        }
    });

    $("#rw").bind("change paste keyup", function() {
        var rw = $(this).val();

        if (rw) {
            $(".rwklub").hide();
        } else {
            $(".rwklub").show();
        }
    });

    $("#postcode").change(function() {
        var kodepos = $(this).val();

        if (kodepos) {
            $(".kodeposklub").hide();
        } else {
            $(".kodeposklub").show();
        }
    });

    $("#province").change(function() {
        var provinsi = $(this).val();

        if (provinsi) {
            $(".provinsiklub").hide();
        } else {
            $(".provinsiklub").show();
        }
    });

    $("#city").change(function() {
        var kota = $(this).val();

        if (kota) {
            $(".kotaklub").hide();
        } else {
            $(".kotaklub").show();
        }
    });

    $("#district").change(function() {
        var distrik = $(this).val();

        if (distrik) {
            $(".distrikklub").hide();
        } else {
            $(".distrikklub").show();
        }
    });

    $("#subdistrict").change(function() {
        var subdistrik = $(this).val();

        if (subdistrik) {
            $(".subdistrikklub").hide();
        } else {
            $(".subdistrikklub").show();
        }
    });

    $("#bank-category").change(function() {
        var kategoribank = $(this).val();

        if (kategoribank) {
            $(".bankklub").hide();
        } else {
            $(".bankklub").show();
        }
    });

    $("#acc-number").bind("change paste keyup", function() {
        var norekening = $(this).val();

        if (norekening) {
            $(".norekening").hide();
        } else {
            $(".norekening").show();
        }
    });

    $("#acc-name").bind("change paste keyup", function() {
        var namanasabah = $(this).val();

        if (namanasabah) {
            $(".namanasabah").hide();
        } else {
            $(".namanasabah").show();
        }
    });

    $("#president").bind("change paste keyup", function() {
        var presiden = $(this).val();

        if (presiden) {
            $(".presiden").hide();
        } else {
            $(".presiden").show();
        }
    });

    $("#secretary").bind("change paste keyup", function() {
        var sekretaris = $(this).val();

        if (sekretaris) {
            $(".sekretaris").hide();
        } else {
            $(".sekretaris").show();
        }
    });

    $("#club-admin").bind("change paste keyup", function() {
        var admin = $(this).val();

        if (admin) {
            $(".admin").hide();
        } else {
            $(".admin").show();
        }
    });

    $("#finance").bind("change paste keyup", function() {
        var finance = $(this).val();

        if (finance) {
            $(".keuangan").hide();
        } else {
            $(".keuangan").show();
        }
    });

    $("#vice-president").bind("change paste keyup", function() {
        var wakil = $(this).val();

        if (wakil) {
            $(".wakil").hide();
        } else {
            $(".wakil").show();
        }
    });

    $("#human-resource").bind("change paste keyup", function() {
        var hrd = $(this).val();

        if (hrd) {
            $(".hrd").hide();
        } else {
            $(".hrd").show();
        }
    });

    $("#docAdart").change(function() {
        var adart = $(this).val();

        if (adart) {
            $(".adart").hide();
        } else {
            $(".adart").show();
        }
    });

    $("#docCertificate").change(function() {
        var sertifikat = $(this).val();

        if (sertifikat) {
            $(".sertifikat").hide();
        } else {
            $(".sertifikat").show();
        }
    });

    $("#docAdditional").change(function() {
        var additional = $(this).val();

        if (additional) {
            $(".tambahan").hide();
        } else {
            $(".tambahan").show();
        }
    });

    var category = []; 
    var cat_name = [];

    var cf = [];
    var cf_name = [];
    

    function changeCategory(number) {

        var value = $('#cat' + number).val();

        if ($('#cat' + number).is(':checked')) {

            category.push(value);
            cat_name.push("cat"+number);

        } else {

            category = category.filter(function(item) {
                return item !== value
            })

            cat_name = cat_name.filter(function(item) {
                return item !== "cat"+number
            })

        }

        if (category != "") {

            $('.clubcategorystar').hide();
            $('#category-error').text("");

        } else {

            $('.clubcategorystar').show();
            $('#category-error').text("This field is required.");

        }

        // console.log(category.join("|"));
        $('#category').val(category.join("|"));

    }

    var rolesChecked = 0;

    function assignRole() {

        if ($('#checkRolesMe').is(':checked')){
            rolesChecked = 1;
        }else{
            rolesChecked = 0;
        }

    }

    function saveCC(number, values_input) {

        var value = $('#checkCategory' + number).val();

        var checkCat = document.getElementById("checkCategory"+number);
        // var idCheckCat = number;

        if (checkCat.checked == true) {

            $(".content-star").hide();

            cf.push(number);
            cf_name.push(values_input);
            
            console.log(cf);
            console.log(cf_name);

        } 
        
        else {

            $(".content-star").show();

            cf = cf.filter(function(item) {
                return item !== number;
            });

            cf_name = cf_name.filter(function(item) {
                return item !== values_input;
            });

            
            //     return item !== "checkCategory"+number;
            // });

            console.log(cf);
            console.log(cf_name);

        }

        // if (cf != "") {

        //     $('.clubcategorystar').hide();
        //     $('#category-error').text("");

        // } else {

        //     $('.clubcategorystar').show();
        //     $('#category-error').text("This field is required.");

        // }

        // console.log(category.join("|"));
        $('#cc_hidden').val(cf.join("|"));
        $("#content_preference").val(cf_name.join(","));

    };

    function saveCbutton() {
        $("#modal-content-preference").modal('hide');
    }



</script>

<script>
    // FOR SELECTIZED VALIDATION

    $("#postcode").change(function() {
        $('#postcode-error').text("");
    });

    $("#province").change(function() {
        $('#province-error').text("");
    });

    $("#city").change(function() {
        $('#city-error').text("");
    });

    $("#district").change(function() {
        $('#district-error').text("");
    });

    $("#subdistrict").change(function() {
        $('#subdistrict-error').text("");
    });

    $("#category").change(function() {
        $('#category-error').text("");
    });

    $("#bank-category").change(function() {
        $('#bank-error').text("");
    });

    $("#docAdart").change(function() {
        $('#docAdart-error').text("");
    });

    $("#docCertificate").change(function() {
        $('#docCertificate-error').text("");
    });

    $("#docAdditional").change(function() {
        $('#docAdditional-error').text("");
    });

    function selectizeValid() {

        storeValues();

        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();
        var category = $('#category').val();
        var bank = $('#bank-category').val();

        var fotoProfile = $('#fotoProfile').val();
        var docAdart = $('#docAdart').val();
        var docCertificate = $('#docCertificate').val();
        var docAdditional = $('#docAdditional').val();

        var admin_kta = $('#admin_kta').val();

        var payment = $("#dropdownMenuSelectMethod").val();

        if (!admin_kta) {
            $('#admincat_name-kta-error').text("Admin data is required.");
        } else {
            $('#admin-kta-error').text("");
        }

        if (!postcode) {
            $('#postcode-error').text("This field is required.");
        } else {
            $('#postcode-error').text("");
        }

        if (!province) {
            $('#province-error').text("This field is required.");
        } else {
            $('#province-error').text("");
        }

        if (!city) {
            $('#city-error').text("This field is required.");
        } else {
            $('#city-error').text("");
        }

        if (!district) {
            $('#district-error').text("This field is required.");
        } else {
            $('#district-error').text("");
        }

        if (!subdistrict) {
            $('#subdistrict-error').text("This field is required.");
        } else {
            $('#subdistrict-error').text("");
        }

        if (!bank) {
            $('#bank-error').text("This field is required.");
        } else {
            $('#bank-error').text("");
        }

        if (!category) {
            $('#category-error').text("This field is required.");
        } else {
            $('#category-error').text("");
        }

        if (!fotoProfile) {
            $('#fotoProfile-error').text("This field is required.");
        } else {
            $('#fotoProfile-error').text("");
        }

        if (!docAdart) {
            $('#docAdart-error').text("This field is required.");
        } else {
            $('#docAdart-error').text("");
        }


        if (!docCertificate) {
            $('#docCertificate-error').text("This field is required.");
        } else {
            $('#docCertificate-error').text("");
        }


        if (!docAdditional) {
            $('#docAdditional-error').text("This field is required.");
        } else {
            $('#docAdditional-error').text("");
        }

        if (!category) {
            $('#category-error').text("This field is required.");
        } else {
            $('#category-error').text("");
        }

        if (!payment || payment == '') {
            $("#payment-error").text("This field is required.");
        } else {
            $("#payment-error").text("");
        }
    }
</script>

<!-- ASSIGN ALL ROLES -->

<script>
    $('#checkRolesMe').click(function() {
        if ($(this).is(':checked')) {

            var check = '<?= $userData['FIRST_NAME'] ?>';

            if (check) {

                $('#president').val('<?= $ktaName['NAME'] ?>');
                $('#secretary').val('<?= $ktaName['NAME'] ?>');
                // $('#club-admin').val('<?= $ktaName['NAME'] ?>');
                $('#finance').val('<?= $ktaName['NAME'] ?>');
                $('#vice-president').val('<?= $ktaName['NAME'] ?>');
                $('#human-resource').val('<?= $ktaName['NAME'] ?>');

                $('#president_name').val('<?= $ktaName['NAME'] ?>');
                $('#secretary_name').val('<?= $ktaName['NAME'] ?>');
                // $('#admin_name').val('<?= $ktaName['NAME'] ?>');
                $('#finance_name').val('<?= $ktaName['NAME'] ?>');
                $('#vice_president_name').val('<?= $ktaName['NAME'] ?>');
                $('#human_resource_name').val('<?= $ktaName['NAME'] ?>');

                $('#president_kta_modal').val('<?= $ktaName['NO_ANGGOTA'] ?>');
                $('#president_kta').val('<?= $ktaName['NO_ANGGOTA'] ?>');

                $('#secretary_kta_modal').val('<?= $ktaName['NO_ANGGOTA'] ?>');
                $('#secretary_kta').val('<?= $ktaName['NO_ANGGOTA'] ?>');

                $('#finance_kta_modal').val('<?= $ktaName['NO_ANGGOTA'] ?>');
                $('#finance_kta').val('<?= $ktaName['NO_ANGGOTA'] ?>');

                $('#vice_president_kta_modal').val('<?= $ktaName['NO_ANGGOTA'] ?>');
                $('#vice_president_kta').val('<?= $ktaName['NO_ANGGOTA'] ?>');

                $('#human_resource_kta_modal').val('<?= $ktaName['NO_ANGGOTA'] ?>');
                $('#human_resource_kta').val('<?= $ktaName['NO_ANGGOTA'] ?>');

                $('#president_ktp_modal').val('<?= $ktaName['EKTP'] ?>');
                $('#president_ktp').val('<?= $ktaName['EKTP'] ?>');

                $('#secretary_ktp_modal').val('<?= $ktaName['EKTP'] ?>');
                $('#secretary_ktp').val('<?= $ktaName['EKTP'] ?>');

                $('#finance_ktp_modal').val('<?= $ktaName['EKTP'] ?>');
                $('#finance_ktp').val('<?= $ktaName['EKTP'] ?>');

                $('#vice_president_ktp_modal').val('<?= $ktaName['EKTP'] ?>');
                $('#vice_president_ktp').val('<?= $ktaName['EKTP'] ?>');

                $('#human_resource_ktp_modal').val('<?= $ktaName['EKTP'] ?>');
                $('#human_resource_ktp').val('<?= $ktaName['EKTP'] ?>');

                $('.presiden').hide();
                $('.sekretaris').hide();
                // $('.admin').hide();
                $('.keuangan').hide();
                $('.wakil').hide();
                $('.hrd').hide();

                $('#president-error').text("");
                $('#secretary-error').text("");

                $('#president_f_pin').val('<?= $ktaName['F_PIN'] ?>');
                $('#secretary_f_pin').val('<?= $ktaName['F_PIN'] ?>');
                $('#finance_f_pin').val('<?= $ktaName['F_PIN'] ?>');
                $('#vice_president_f_pin').val('<?= $ktaName['F_PIN'] ?>');
                $('#hrd_f_pin').val('<?= $ktaName['F_PIN'] ?>');

                // PRESIDEN
                // if (available_kta == 1){
                //     $('#presiden-kta-error').text("");
                // }else{
                //     $('#club-president-error').text("");
                //     $('#presiden-kta-error').text("President data is required.");
                // }

                // SECRETARY
                // if (available_kta == 1){
                //     $('#secretary-kta-error').text("");
                // }else{
                //     $('#club-secretary-error').text("");
                //     $('#secretary-kta-error').text("Secretary data is required.");
                // }

                // ADMIN
                // if (available_kta == 1){
                //     $('#admin-kta-error').text("");
                // }else{
                //     $('#club-admin-error').text("");
                //     $('#admin-kta-error').text("Admin data is required.");
                // }

                // $('#finance-error').text("");
                // $('#vice-president-error').text("");
                // $('#human-resource-error').text("");

                // FINANCE
                // if (available_kta == 1){
                //     $('#finance-kta-error').text("");
                // }else{
                //     $('#club-finance-error').text("");
                //     $('#finance-kta-error').text("Finance data is required.");
                // }

                // VICE PRESIDENT
                // if (available_kta == 1){
                //     $('#vice-kta-error').text("");
                // }else{
                //     $('#club-vice-error').text("");
                //     $('#vice-kta-error').text("Vice President data is required.");
                // }

                // HUMAN RESOURCE
                // if (available_kta == 1){
                //     $('#hrd-kta-error').text("");
                // }else{
                //     $('#club-hrd-error').text("");
                //     $('#hrd-kta-error').text("Human Resource data is required.");
                // }

            } else {
                // alert("No User (F_PIN) detected.");

                $('#error-modal-text').text("You are not signed in yet. Please download Gaspol! Apps for use this feature.");
                $('#modal-error').modal('show');
                $('#checkRolesMe').attr('checked', false);
            }

        } else {

            // INPUT HIDDEN
            $('#president_kta').val("");
            $('#president').val("");
            $('#president_ktp').val("");

            $('#secretary_kta').val("");
            $('#secretary').val("");
            $('#secretary_ktp').val("");

            $('#finance_kta').val("");
            $('#finance').val("");
            $('#finance_ktp').val("");

            $('#vice_president_kta').val("");
            $('#vice-president').val("");
            $('#vice_president_ktp').val("");

            $('#human_resource_kta').val("");
            $('#human-resource').val("");
            $('#human_resource_ktp').val("");
            // END

            // INPUT
            $('#president_kta_modal').val('');
            $('#president_name').val('');
            $('#president_ktp_modal').val('');

            $('#secretary_kta_modal').val("");
            $('#secretary_name').val('');
            $('#secretary_ktp_modal').val("");

            // $('#admin_name').val('');

            $('#finance_kta_modal').val("");
            $('#finance_name').val('');
            $('#finance_ktp_modal').val("");

            $('#vice_president_kta_modal').val("");
            $('#vice_president_name').val('');
            $('#vice_president_ktp_modal').val("");

            $('#human_resource_kta_modal').val("");
            $('#human_resource_name').val('');
            $('#human_resource_ktp_modal').val("");
            // END

            $('.presiden').show();
            $('.sekretaris').show();
            // $('.admin').show();
            $('.keuangan').show();
            $('.wakil').show();
            $('.hrd').show();
        }
    });
</script>

<!-- POP UP MODAL PRESIDENT ETC -->

<script>
    function savePresident() {

        var name = $('#president_name').val();
        var phone = $('#president_phone_modal').val();
        var ktp = $('#president_ktp_modal').val();
        var kta = $('#president_kta_modal').val();

        $('#president').val(name);
        $('#president_phone').val(phone);
        $('#president_ktp').val(ktp);
        $('#president_kta').val(kta);

        if (available_kta == 1) {
            $('#modal-president').modal('hide');
            $('#presiden-kta-error').text("");
        } else if (available_kta == 0 && !kta) {
            $('#check-kta-number-president').show();
        }

        if ($('#president').val() && $('#president_kta').val()) {
            $('.presiden').hide();
            $('#club-president-error').text("")
        }

    }

    function saveSecretary() {
        var name = $('#secretary_name').val();
        var phone = $('#secretary_phone_modal').val();
        var ktp = $('#secretary_ktp_modal').val();
        var kta = $('#secretary_kta_modal').val();

        $('#secretary').val(name);
        $('#secretary_phone').val(phone);
        $('#secretary_ktp').val(ktp);
        $('#secretary_kta').val(kta);

        if (available_kta == 1) {
            $('#modal-secretary').modal('hide');
            $('#secretary-kta-error').text("");
        } else if (available_kta == 0 && !kta) {
            $('#check-kta-number-secretary').show();
        }

        if ($('#secretary').val() && $('#secretary_kta').val()) {
            $('.sekretaris').hide();
            $('#club-secretary-error').text("")
        }

    }

    function saveAdmin() {

        // CEK INPUT DATA MODAL ADMIN
        var name = $('#admin_name').val();
        var phone = $('#admin_phone_modal').val();
        var ktp = $('#admin_ktp_modal').val();
        var kta = $('#admin_kta_modal').val();

        // CEK INPUT DATA MODAL ADMIN YANG HIDDEN
        $('#club-admin').val(name);
        $('#admin_phone').val(phone);
        $('#admin_ktp').val(ktp);
        $('#admin_kta').val(kta);

        // VALIDASI DATA TIAP INPUT MODAL ADMIN
        if (available_kta == 1) {
            $('#modal-admin').modal('hide');
            $('#admin-kta-error').text("");
        } else if (available_kta == 0 && !kta) {
            $('#check-kta-number').show();
        }

        if ($('#club-admin').val() && $('#admin_kta').val()) {
            $('.presiden').hide();
            $('#club-admin-error').text("")
        }

    }

    function saveFinance() {
        var name = $('#finance_name').val();
        var phone = $('#finance_phone_modal').val();
        var ktp = $('#finance_ktp_modal').val();
        var kta = $('#finance_kta_modal').val();

        $('#finance').val(name);
        $('#finance_phone').val(phone);
        $('#finance_ktp').val(ktp);
        $('#finance_kta').val(kta);

        if (available_kta == 1) {
            $('#modal-finance').modal('hide');
            $('#finance-kta-error').text("");
        } else if (available_kta == 0 && !kta) {
            $('#check-kta-number-finance').show();
        }

        if ($('#finance').val() && $('#finance_kta').val()) {
            $('.keuangan').hide();
            $('#club-finance-error').text("")
        }

    }

    function saveVicePresident() {
        var name = $('#vice_president_name').val();
        var phone = $('#vice_president_phone_modal').val();
        var ktp = $('#vice_president_ktp_modal').val();
        var kta = $('#vice_president_kta_modal').val();

        $('#vice-president').val(name);
        $('#vice_president_phone').val(phone);
        $('#vice_president_ktp').val(ktp);
        $('#vice_president_kta').val(kta);

        if (available_kta == 1) {
            $('#modal-vice-president').modal('hide');
            $('#vice-kta-error').text("");
        } else if (available_kta == 0 && !kta) {
            $('#check-kta-number-vice').show();
        }

        if ($('#vice-president').val() && $('#vice_president_kta').val()) {
            // $('.keuangan').hide();
            $('#club-vice-error').text("")
        }
    }

    function saveHumanResource() {
        var name = $('#human_resource_name').val();
        var phone = $('#human_resource_phone_modal').val();
        var ktp = $('#human_resource_ktp_modal').val();
        var kta = $('#human_resource_kta_modal').val();

        $('#human-resource').val(name);
        $('#human_resource_phone').val(phone);
        $('#human_resource_ktp').val(ktp);
        $('#human_resource_kta').val(kta);

        // $('#modal-human-resource').modal('hide');

        // if ($('#human-resource').val()){
        //     $('.hrd').hide();
        //     $('#human-resource-error').text("")
        // }

        if (available_kta == 1) {
            $('#modal-human-resource').modal('hide');
            $('#hrd-kta-error').text("");
        } else if (available_kta == 0 && !kta) {
            $('#check-kta-number-hrd').show();
        }

        if ($('#human-resource').val() && $('#human_resource_kta').val()) {
            // $('.keuangan').hide();
            $('#club-hrd-error').text("")
        }
    }
</script>

<script>
    // CHECK CLUB USERNAME ALREADY TAKEN

    $("#club_name").bind("change paste keyup", function() {
        var name = $(this).val();

        // // console.log(name);

        var formData = new FormData();

        formData.append('name', name);

        if (name != "") {

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                    // // console.log(xmlHttp.responseText);

                    var result = xmlHttp.responseText;

                    if (result == "Ada") {
                        // console.log("Username Ada");
                        $('#username-not-exist').text("");
                        $('#username-exist').text("That club name is taken, try another.");

                        is_takken = 0;
                    } else if (result == "Tidak ada") {
                        // console.log("Username Tidak Ada");
                        $('#username-not-exist').text("That club name is available.");
                        $('#username-exist').text("");

                        is_takken = 1;
                    }

                }
            }
            xmlHttp.open("post", "../logics/check_tkt_username");
            xmlHttp.send(formData);

        } else {
            $('#username-not-exist').text("");
            $('#username-exist').text("");
        }
    });

    // SINGLE ROLE FOR ME

    // $("#checkPresident").click(function() {
    //     if ($(this).is(":checked")) {
    //         $('#president_name').val('<?= $userData['FIRST_NAME'] . " " . $userData['LAST_NAME'] ?>');
    //     }
    // });

    // LIMIT MODAL KTP LENGTH

    var $input_president = $('#president_ktp');
    var $input_secretary = $('#secretary_ktp');
    var $input_admin = $('#admin_ktp');
    var $input_finance = $('#finance_ktp');
    var $input_vice_president = $('#vice_president_ktp');
    var $input_human_resource = $('#human_resource_ktp');

    var max = 16;

    $input_president.keyup(function(e) {
        var max = 16;
        if ($input_president.val().length > max) {
            $input_president.val($input_president.val().substr(0, max));
        }
    });

    $input_secretary.keyup(function(e) {
        var max = 16;
        if ($input_secretary.val().length > max) {
            $input_secretary.val($input_secretary.val().substr(0, max));
        }
    });

    $input_admin.keyup(function(e) {
        var max = 16;
        if ($input_admin.val().length > max) {
            $input_admin.val($input_admin.val().substr(0, max));
        }
    });

    $input_finance.keyup(function(e) {
        var max = 16;
        if ($input_finance.val().length > max) {
            $input_finance.val($input_finance.val().substr(0, max));
        }
    });

    $input_vice_president.keyup(function(e) {
        var max = 16;
        if ($input_vice_president.val().length > max) {
            $input_vice_president.val($input_vice_president.val().substr(0, max));
        }
    });

    $input_human_resource.keyup(function(e) {
        var max = 16;
        if ($input_human_resource.val().length > max) {
            $input_human_resource.val($input_human_resource.val().substr(0, max));
        }
    });
</script>

<script>
    if (!window.Android) {
        $("#photo-method").hide();
    } else {
        $("#photo-method").show();
    }
</script>

<script>
    var name = $('#club-admin').val();

    // console.log(name);

    if (name != "") {
        $('#admin-star').hide();
        // console.log('A');
    } else {
        $('#admin-star').show();
        // console.log('B');
    }
</script>

<script type="text/javascript">

    var today = new Date();
    var expiry = new Date(today.getTime() + 30 * 24 * 3600 * 1000); // plus 30 days

    function setCookie(name, value)
    {
        document.cookie=name + "=" + escape(value) + "; path=/; expires=" + expiry.toGMTString();
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function deleteCookie(name){
        document.cookie=name + "=; path=/; expires=" + expiry.toGMTString();
    }

    function storeValues()  
    {

        setCookie("club_nameTKT", $('#club_name').val());
        setCookie("content_preferenceTKT", $('#content_preference').val());
        setCookie("clubtype_radioTKT", document.querySelector('input[name="clubtype_radio"]:checked').value);
        setCookie("club_locationTKT", $('#club_location').val());
        setCookie("descTKT", $('#desc').val());
        setCookie("addressTKT", $('#address').val());
        setCookie("rtTKT", $('#rt').val());
        setCookie("rwTKT", $('#rw').val());
        setCookie("postcodeTKT", $('#postcode').val());
        setCookie("provinceTKT", $('#province').val());
        setCookie("cityTKT", $('#city').val());
        setCookie("districtTKT", $('#district').val());
        setCookie("subdistrictTKT", $('#subdistrict').val());
        setCookie("bank-categoryTKT", $('#bank-category').val());
        setCookie("acc-numberTKT", $('#acc-number').val());
        setCookie("acc-nameTKT", $('#acc-name').val());

        setCookie("president_phoneTKT", $('#president_phone').val());
        setCookie("president_ktpTKT", $('#president_ktp').val());
        setCookie("president_ktaTKT", $('#president_kta').val());

        setCookie("secretary_phoneTKT", $('#secretary_phone').val());
        setCookie("secretary_ktpTKT", $('#secretary_ktp').val());
        setCookie("secretary_ktaTKT", $('#secretary_kta').val());

        setCookie("finance_phoneTKT", $('#finance_phone').val());
        setCookie("finance_ktpTKT", $('#finance_ktp').val());
        setCookie("finance_ktaTKT", $('#finance_kta').val());

        setCookie("vice_president_phoneTKT", $('#vice_president_phone').val());
        setCookie("vice_president_ktpTKT", $('#vice_president_ktp').val());
        setCookie("vice_president_ktaTKT", $('#vice_president_kta').val());

        setCookie("human_resource_phoneTKT", $('#human_resource_phone').val());
        setCookie("human_resource_ktpTKT", $('#human_resource_ktp').val());
        setCookie("human_resource_ktaTKT", $('#human_resource_kta').val());

        setCookie("presidentTKT", $('#president').val());
        setCookie("secretaryTKT", $('#secretary').val());
        setCookie("club-adminTKT", $('#club-admin').val());
        setCookie("financeTKT", $('#finance').val());
        setCookie("vice-presidentTKT", $('#vice-president').val());
        setCookie("human-resourceTKT", $('#human-resource').val());

        setCookie("checkRolesMeTKT", rolesChecked);
        setCookie("cat_nameTKT", cf);
        

        setCookie("paymentTKT", $('#dropdownMenuSelectMethod').val());
        return true;
    }

    function getDataCookie(){

        if (name = getCookie("club_nameTKT")){
            $('#club_name').val(decodeURIComponent(name));
            $('.namaklub').hide();

            is_takken = 1;
        }

        if (clubtype_radio = getCookie("clubtype_radioTKT")){
            if (clubtype_radio==1){
                $('#public').prop('checked', true);
            }else{
                $('#private').prop('checked', true);
            }
        }

        if (name = getCookie("content_preferenceTKT")){
            $('#content_preference').val(decodeURIComponent(name));
            $('.content-star').hide();

            var cat = decodeURIComponent(name.split("|"));

            var conCategory = cat.split(",");

            for(var i=0; i<conCategory.length; i++){

                cf_name.push(conCategory[i])

                $('#cc_hidden').val(cf_name.join("|"));
            }
        }

        if (conName = getCookie("cat_nameTKT")){

            var cat = decodeURIComponent(conName.split("|"));

            var conCategory = cat.split(",");

            for(var i=0; i<conCategory.length; i++){

                cf.push(conCategory[i]);

                $('#checkCategory'+conCategory[i]).attr('checked', true);
            }

        }

        if (club_location = getCookie("club_locationTKT")){
            $('#club_location').val(decodeURIComponent(club_location));
            $('.lokasiklub').hide();
        }

        if (desc = getCookie("descTKT")){
            $('#desc').val(decodeURIComponent(desc));
            $('.deskripsiklub').hide();
        }

        if (address = getCookie("addressTKT")){
            $('#address').val(decodeURIComponent(address));
            $('.alamatklub').hide();
        }

        if (rt = getCookie("rtTKT")){
            $('#rt').val(rt);
            $('.rtklub').hide();
        }

        if (rw = getCookie("rwTKT")){
            $('#rw').val(rw);
            $('.rwklub').hide();
        }

        if (postcode = getCookie("postcodeTKT")){
            var $select = $(document.getElementById('postcode'));
            var selectize = $select[0].selectize;
            selectize.setValue(postcode);

            $('.starpost').hide();
        }

        if (province = getCookie("provinceTKT")){
            var $select = $(document.getElementById('province'));
            var selectize = $select[0].selectize;
            selectize.setValue(province);

            $('.starprovince').hide();

            var $selectCity = $(document.getElementById('city'));
            var selectizeCity = $selectCity[0].selectize;

            // CLEAR CITY
            selectizeCity.clear(); 
            selectizeCity.clearOptions();

            var province = province;
            localStorage.setItem("province", province);

            var formData = new FormData();

            formData.append('province', province);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                        
                        Object.keys(obj).forEach(function (item){

                            // console.log(obj[item]['POSTAL_CODE']);
                            
                            selectizeCity.addOption({value: obj[item]['CITY_ID'], text: capitalize(obj[item]['CITY_NAME'])});

                        });

                        setCity();
                }
            }
            xmlHttp.open("post", "../logics/get_city");
            xmlHttp.send(formData);
        }

        function setCity(){

            if (city = getCookie("cityTKT")){
                var $select = $(document.getElementById('city'));
                var selectize = $select[0].selectize;
                selectize.setValue(city);

                $('.starcity').hide();

                var $selectDis = $(document.getElementById('district'));
                var selectizeDis = $selectDis[0].selectize;

                // CLEAR CITY
                selectizeDis.clearOptions();
                selectizeDis.clear(); 

                var city = city;
                localStorage.setItem("city", city);

                var formData = new FormData();

                formData.append('city', city);

                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function(){
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        
                        console.log(xmlHttp.responseText);

                        var obj = JSON.parse(xmlHttp.responseText);
                            
                            Object.keys(obj).forEach(function (item){

                                // console.log(obj[item]['POSTAL_CODE']);
                            
                                selectizeDis.addOption({value: obj[item]['DIS_ID'], text: capitalize(obj[item]['DIS_NAME'])});

                            });

                        setDis();
                    }
                }
                xmlHttp.open("post", "../logics/get_district");
                xmlHttp.send(formData);
            }
        }

        function setDis(){
            if (district = getCookie("districtTKT")){
                var $select = $(document.getElementById('district'));
                var selectize = $select[0].selectize;
                selectize.setValue(district);

                $('.stardist').hide();

                var $select = $(document.getElementById('subdistrict'));
                var selectize = $select[0].selectize;

                // CLEAR DISTRICT
                selectize.clearOptions();
                selectize.clear(); 

                var district = district;
                localStorage.setItem("district", district);

                var formData = new FormData();

                formData.append('district', district);

                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function(){
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        
                        console.log(xmlHttp.responseText);

                        var obj = JSON.parse(xmlHttp.responseText);
                            
                            Object.keys(obj).forEach(function (item){

                                // console.log(obj[item]['POSTAL_CODE']);

                                selectize.addOption({value: obj[item]['SUBDIS_ID'], text: capitalize(obj[item]['SUBDIS_NAME'])});

                            });

                            setSubDis();
                    }
                }
                xmlHttp.open("post", "../logics/get_subdistrict");
                xmlHttp.send(formData);
            }
        }

        function setSubDis(){

            if (subdistrict = getCookie("subdistrictTKT")){
                var $select = $(document.getElementById('subdistrict'));
                var selectize = $select[0].selectize;
                selectize.setValue(subdistrict);

                $('.starsubdist').hide();
            }

        }

        if (bankcategory = getCookie("bank-categoryTKT")){
            var $select = $(document.getElementById('bank-category'));
            var selectize = $select[0].selectize;
            selectize.setValue(bankcategory);
        }

        if (accnumber = getCookie("acc-numberTKT")){
            $('#acc-number').val(accnumber);
            $('.norekening').hide();
        }

        if (accname = getCookie("acc-nameTKT")){
            $('#acc-name').val(decodeURIComponent(accname));
            $('.namanasabah').hide();
        }

        if (president = getCookie("presidentTKT")){
            $('#president').val(decodeURIComponent(president));
            $('.presiden').hide();
        }

        if (secretary = getCookie("secretaryTKT")){
            $('#secretary').val(decodeURIComponent(secretary));
            $('.sekretaris').hide();
        }

        if (clubadmin = getCookie("club-adminTKT")){
            $('#club-admin').val(decodeURIComponent(clubadmin));
            $('.admin-star').hide();
        }

        if (finance = getCookie("financeTKT")){
            $('#finance').val(decodeURIComponent(finance));
            $('.keuangan').hide();
        }

        if (vice = getCookie("vice-presidentTKT")){
            $('#vice-president').val(decodeURIComponent(vice));
        }

        if (hrd = getCookie("human-resourceTKT")){
            $('#human-resource').val(decodeURIComponent(hrd));
        }

        if (payment = getCookie("paymentTKT")){
            $('#dropdownMenuSelectMethod').val(payment);
        }

        if (rolesChecked = getCookie("checkRolesMeTKT")){

            if (rolesChecked == 1){
                $('#checkRolesMe').attr('checked', true);
            }else{
                $('#checkRolesMe').attr('checked', false);
            }

        }

    }

    function deleteAllCookie(){

        deleteCookie("club_nameTKT");
        deleteCookie("content_preferenceTKT");
        deleteCookie("clubtype_radioTKT");
        deleteCookie("club_locationTKT");
        deleteCookie("descTKT");
        deleteCookie("addressTKT");
        deleteCookie("rtTKT");
        deleteCookie("rwTKT");

        deleteCookie("postcodeTKT");
        deleteCookie("provinceTKT");
        deleteCookie("cityTKT");
        deleteCookie("districtTKT");
        deleteCookie("subdistrictTKT");

        deleteCookie("bank-categoryTKT");
        deleteCookie("acc-numberTKT");
        deleteCookie("acc-nameTKT");

        deleteCookie("presidentTKT");
        deleteCookie("secretaryTKT");
        deleteCookie("club-adminTKT");
        deleteCookie("financeTKT");
        deleteCookie("vice-presidentTKT");
        deleteCookie("human-resourceTKT");

        deleteCookie("president_phoneTKT");
        deleteCookie("president_ktpTKT");
        deleteCookie("president_ktaTKT");

        deleteCookie("secretary_phoneTKT");
        deleteCookie("secretary_ktpTKT");
        deleteCookie("secretary_ktaTKT");

        deleteCookie("finance_phoneTKT");
        deleteCookie("finance_ktpTKT");
        deleteCookie("finance_ktaTKT");

        deleteCookie("vice_president_phoneTKT");
        deleteCookie("vice_president_ktpTKT");
        deleteCookie("vice_president_ktaTKT");

        deleteCookie("human_resource_phoneTKT");
        deleteCookie("human_resource_ktpTKT");
        deleteCookie("human_resource_ktaTKT");

        deleteCookie("cat_nameTKT");
        deleteCookie("cont_nameTKT");

        deleteCookie("checkRolesMeTKT");

        deleteCookie("paymentTKT");
        
    }

    function closeAndroid(){

        if (window.Android){

        window.Android.finishGaspolForm();

        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

        window.webkit.messageHandlers.finishGaspolForm.postMessage({
            param1: ""
        });
        return;

        }else{

        history.back();

        }
    }

</script>