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

$sqlData = "SELECT * FROM COUNTRIES";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$countries = $queDATA->get_result();
$queDATA->close();

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

$sqlData = "SELECT * FROM CITY ORDER BY CITY_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$birthplace = $queDATA->get_result();
$queDATA->close();

// POSTAL CODE

$sqlData = "SELECT * FROM POSTAL_CODE";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$postal = $queDATA->get_result();
$queDATA->close();

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

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '10'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

$upgradeFee = $price['REG_FEE'];
$adminFee = $price['ADMIN_FEE'];

// TAA CATEGORY
// $list_ctgry = $dbconn->prepare("SELECT * FROM TAA_CATEGORY");
// $list_ctgry->execute();
// $category = $list_ctgry->get_result();
// $list_ctgry->close();

// BANK CATEGORY
// $bank_category = $dbconn->prepare("SELECT * FROM TAA_BANK");
// $bank_category->execute();
// $show_bank = $bank_category->get_result();
// $bank_category->close();

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

// print_r($contentCategory);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TKT Masyarakat</title>

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

        .modal-footer {
            display: unset !important;
        }
    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" style="padding: 0px" id="tkt-masyarakat" enctype="multipart/form-data">
            
            <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b style="font-size: 14px">Gaspol Club - Registration</b>
                    </div>
                </div>
            </div>

            <div class="container mx-auto mt-3">
                <div data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClubInfo" aria-expanded="false" aria-controls="collapseClubInfo">
                    <b>Club Information</b>
                    <img id="collapse-img-1" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClubInfo">
                    <div class="row mt-3 mb-3 gx-0">
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
                            <!-- <br><p id="profileFileName" style="display: inline; margin-left: 14px">No file chosen</p> -->
                            <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)" />
                            <br><span id="fotoProfile-error" class="error" style="color: red; margin-left: 14px"></span>
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
                            <input type="text" name="content_preference" id="content_preference" placeholder="Content Preference" required data-bs-toggle="modal" data-bs-target="#modal-content-preference" required />
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

                    <p class="mt-3 mb-1"><b>Club Category</b></p>
                    <div class="row">
                        <div class="col-4">
                            <input type="radio" id="race_category" name="clubcategory_radio" class="radio" value="1" checked>
                            <label for="race_category">&nbsp;&nbsp;Race</label><br>
                        </div>
                        <div class="col-4">
                            <input type="radio" id="hobby_category" name="clubcategory_radio" class="radio" value="2">
                            <label for="hobby_category">&nbsp;&nbsp;Hobby</label>
                        </div>
                        <div class="col-4">
                            <input type="radio" id="open_category" name="clubcategory_radio" class="radio" value="3">
                            <label for="open_category">&nbsp;&nbsp;Open</label>
                        </div>
                    </div>

                    <!-- <input type="hidden" id="category" name="category"> -->

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

                    <div class="row gx-0 mt-2">
                        <div class="col-12">
                            <input type="text" name="exlink" id="exlink" placeholder="Club External Link" required />
                        </div>
                        <span class="exlink-star text-danger" style="position: absolute; margin-top: 9px; margin-left: 121px; width: 10px">*</span>
                    </div>

                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="desc" id="desc" placeholder="Description" required />
                        </div>
                        <span class="deskripsiklub text-danger" style="position: absolute; margin-top: 9px; margin-left: 81px; width: 10px">*</span>
                    </div>
                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                    <b>Club Location</b>
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
                <div class="form-check mb-4" style="margin-top: 50px">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                        <b>I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!</b>
                    </label>
                </div>
            </div>
            <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->
            <div style="width: 100%; height: 5px; margin-top: 25px; background-color: #e5e5e5"></div>
            <div style="background-color: #eee; padding-top: 25px">
                <div class="mt-5">
                    <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                        <button type="submit" class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 50%; height: 50px; color: white" onclick="selectizeValid()"><b>Register Now</b></button>
                    </div>
                </div>
            </div>
            <!-- <div style="width: 100%; height: 100px; background-color: #fff"></div> -->
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

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalMembership">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">TKT Masyarakat Registration Success!</h1>
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

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 10;
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
    <script src="../assets/js/form-tkt-masyarakat.js?v=<?php echo $ver; ?>"></script>

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

    var available_kta = 0;

    $(document).ready(function(e) {

        // $('#category').selectize();
        $('#postcode').selectize();
        $('#province').selectize();
        $('#city').selectize();
        $('#district').selectize();
        $('#subdistrict').selectize();

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

    });

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }


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
    $(".content-star").show();
    $(".kategoriklub").show();
    $(".lokasiklub").show();
    $(".exlink-star").show();
    $(".deskripsiklub").show();
    
    $(".alamatklub").show();
    $(".rtklub").show();
    $(".rwklub").show();
    $(".kodeposklub").show();
    $(".provinsiklub").show();
    $(".kotaklub").show();
    $(".distrikklub").show();
    $(".subdistrikklub").show();

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

    $("#exlink").bind("change paste keyup", function() {
        var exlink = $(this).val();

        if (exlink) {
            $(".exlink-star").hide();
        } else {
            $(".exlink-star").show();
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

    function selectizeValid() {

        storeValues();

        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        // var payment = $("#dropdownMenuSelectMethod").val();

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

        if (!fotoProfile) {
            $('#fotoProfile-error').text("This field is required.");
        } else {
            $('#fotoProfile-error').text("");
        }

        // if (!payment || payment == '') {
        //     $("#payment-error").text("This field is required.");
        // } else {
        //     $("#payment-error").text("");
        // }
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

</script>

<script>
    if (!window.Android) {
        $("#photo-method").hide();
    } else {
        $("#photo-method").show();
    }
</script>

<script>

    var cf = [];
    var cf_name = [];

    function saveCC(catID, catName) {

        var cc = document.getElementById("checkCategory"+catID);

        if (cc.checked == true) {

            $('.content-star').hide();

            cf.push(catID);
            cf_name.push(catName);

            console.log(cf);
            console.log(cf_name);
            
        }

        else {

            cf = cf.filter(function(item) {
                return item !== catID
            });

            cf_name = cf_name.filter(function(item) {
                return item !== catName
            });

            console.log(cf);
            console.log(cf_name);

        }

        $('#cc_hidden').val(cf.join("|"));
        $('#content_preference').val(cf_name.join(","));

    }

    function saveCbutton() {
        $("#modal-content-preference").modal('hide');
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

        setCookie("club_nameMAS", $('#club_name').val());
        setCookie("content_preferenceMAS", $('#content_preference').val());
        setCookie("cat_nameMAS", cf);
        setCookie("clubtype_radioMAS", document.querySelector('input[name="clubtype_radio"]:checked').value);
        setCookie("clubcategory_radioMAS", document.querySelector('input[name="clubcategory_radio"]:checked').value);
        setCookie("exlinkMAS", $('#exlink').val());
        setCookie("descMAS", $('#desc').val());

        setCookie("addressMAS", $('#address').val());
        setCookie("rtMAS", $('#rt').val());
        setCookie("rwMAS", $('#rw').val());
        setCookie("postcodeMAS", $('#postcode').val());
        setCookie("provinceMAS", $('#province').val());
        setCookie("cityMAS", $('#city').val());
        setCookie("districtMAS", $('#district').val());
        setCookie("subdistrictMAS", $('#subdistrict').val());

        // setCookie("paymentMAS", $('#dropdownMenuSelectMethod').val());
        return true;
        
    }

    function getDataCookie(){

        if (name = getCookie("club_nameMAS")){
            $('#club_name').val(decodeURIComponent(name));
            $('.namaklub').hide();

            is_takken = 1;
        }

        if (name = getCookie("content_preferenceMAS")){
            $('#content_preference').val(decodeURIComponent(name));
            $('.content-star').hide();

            var cat = decodeURIComponent(name.split("|"));

            var conCategory = cat.split(",");

            for(var i=0; i<conCategory.length; i++){

                cf_name.push(conCategory[i])

                $('#cc_hidden').val(cf_name.join("|"));
            }
        }

        if (conName = getCookie("cat_nameMAS")){

            var cat = decodeURIComponent(conName.split("|"));

            var conCategory = cat.split(",");

            for(var i=0; i<conCategory.length; i++){

                cf.push(conCategory[i]);

                $('#checkCategory'+conCategory[i]).attr('checked', true);
            }

        }

        if (clubtype_radio = getCookie("clubtype_radioMAS")){
            if (clubtype_radio==1){
                $('#public').prop('checked', true);
            }else{
                $('#private').prop('checked', true);
            }
        }

        if (clubcategory_radio = getCookie("clubcategory_radioMAS")){
            if (clubcategory_radio==1) {
                $('#race_category').prop('checked', true);
            }
            
            else if (clubcategory_radio==2) {
                $('#hobby_category').prop('checked', true);
            }

            else {
                $('#open_category').prop('checked', true);
            }
        }
        
        if (name = getCookie("exlinkMAS")){
            $('#exlink').val(decodeURIComponent(name));
            $('.exlink-star').hide();
        }

        if (desc = getCookie("descMAS")){
            $('#desc').val(decodeURIComponent(desc));
            $('.deskripsiklub').hide();
        }

        if (address = getCookie("addressMAS")){
            $('#address').val(decodeURIComponent(address));
            $('.alamatklub').hide();
        }

        if (rt = getCookie("rtMAS")){
            $('#rt').val(rt);
            $('.rtklub').hide();
        }

        if (rw = getCookie("rwMAS")){
            $('#rw').val(rw);
            $('.rwklub').hide();
        }

        if (postcode = getCookie("postcodeMAS")){
            var $select = $(document.getElementById('postcode'));
            var selectize = $select[0].selectize;
            selectize.setValue(postcode);

            $('.starpost').hide();
        }

        if (province = getCookie("provinceMAS")){
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

            if (city = getCookie("cityMAS")){
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
            if (district = getCookie("districtMAS")){
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

            if (subdistrict = getCookie("subdistrictMAS")){
                var $select = $(document.getElementById('subdistrict'));
                var selectize = $select[0].selectize;
                selectize.setValue(subdistrict);

                $('.starsubdist').hide();
            }

        }

    }

    function deleteAllCookie(){

        deleteCookie("club_nameMAS");
        deleteCookie("content_preferenceMAS");
        deleteCookie("cat_nameMAS");
        deleteCookie("clubtype_radioMAS");
        deleteCookie("clubcategory_radioMAS");
        deleteCookie("descMAS");
        deleteCookie("exlinkMAS");

        deleteCookie("addressMAS");
        deleteCookie("rtMAS");
        deleteCookie("rwMAS");
        deleteCookie("postcodeMAS");
        deleteCookie("provinceMAS");
        deleteCookie("cityMAS");
        deleteCookie("districtMAS");
        deleteCookie("subdistrictMAS");

        // deleteCookie("paymentMAS");
        
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