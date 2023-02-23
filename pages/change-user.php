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

// GET USER DATA

$sqlData = "SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$userData = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

// GET USER DATA

$sqlData = "SELECT * FROM USER_LIST_EXTENDED WHERE F_PIN = '$f_pin'";

$queEXDATA = $dbconn->prepare($sqlData);
$queEXDATA->execute();
$userEXData = $queEXDATA->get_result()->fetch_assoc();
$queEXDATA->close();

// GET USER DATA EXTENDED GASPOL

$sqlData = "SELECT * FROM USER_LIST_EXTENDED_GASPOL WHERE F_PIN = '$f_pin'";

$queEXDATA = $dbconn->prepare($sqlData);
$queEXDATA->execute();
$userEXDataGaspol = $queEXDATA->get_result()->fetch_assoc();
$queEXDATA->close();

// PROVINCE

$sqlData = "SELECT * FROM PROVINCE";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$province = $queDATA->get_result();
// $provinceData = $province['PROV_ID'];
$queDATA->close();

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

// CONTENT CATEGORY JOIN
$contentJ = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE LEFT JOIN USER_LIST_EXTENDED_GASPOL ON CONTENT_PREFERENCE.ID = USER_LIST_EXTENDED_GASPOL.ID_CATEGORY WHERE USER_LIST_EXTENDED_GASPOL.F_PIN = '$f_pin'");
$contentJ->execute();
$contentCategoryJoin = $contentJ->get_result()->fetch_assoc();
$conCatJoin = explode("|", $contentCategoryJoin['ID_CATEGORY']);
$contentJ->close();

// GET CATEGORY NAME

$arrayName = array();

foreach ($conCatJoin as $ct):

    $content = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE WHERE ID = '".$ct."'");
    $content->execute();
    $categoryName = $content->get_result()->fetch_assoc();

    if($arrayName){

        $arrayName .= "|".$categoryName['CONTENT_CATEGORY'];

    }else{

        $arrayName = $categoryName['CONTENT_CATEGORY'];

    }
    $content->close();

endforeach;

print_r (mysqli_num_rows($userEXDataGaspol));

// print_r($userData['IMAGE']);

// GET BIRTHDATE

$date = $userEXData['BIRTHDATE']; 
$sec = strtotime($date);  
$newdate = date ("m/d/Y", $sec);
$formatedDate = date ("Y-m-d", $sec);

// print_r($date);

if ($userEXData['GENDER'] == 0) {
    $userEXData['GENDER'] = 1;
}

// print_r($userEXData['GENDER']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Profile</title>

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
            border-radius: 20px;
            padding: 20px;
        }

        .modal-footer {
            display: unset !important;
        }

        .selectize-input {
            border: none !important;
            border-bottom: 1px solid #ccc !important;
            padding-left: 0px !important;
        }

    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" style="padding: 0px" id="change-user" enctype="multipart/form-data">
            <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid">
                <!-- <div class="col-1 d-flex justify-content-start">
                    <a onclick="history.back()"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                </div>
                <div class="col-11 d-flex justify-content-center">
                    <h2 style="margin-bottom: 0px">IMI Club Registration</h2>
                </div> -->
                <div class="col-8">
                    <a id="back-button" onclick="backAndroidIOS()" class="ms-2" style="position: absolute"><img src="../assets/img/membership-back.png" alt="" style="height: 36px"></a>
                    <p class="text-center" style="margin-bottom: 0px; margin-top: 7px; font-size: 14px; font-weight: 700">Edit Account Info</p>
                </div>
                <div class="col-4 text-center">
                    <img src="../assets/img/checked-icon.svg" alt="" style="height: 14px">
                    <button class="btn text-center" style="margin-bottom: 0px; color: #777777; font-weight: 700" value="Save" onclick="saveData()">Save</button>
                </div>
            </div>

            <div class="container mx-auto mt-3">

                <div id="popup-success" class="alert alert-success" role="alert">
                    You've successfully changed your profile.
                </div>

                <div style="font-size: 18px" aria-expanded="false" aria-controls="collapseClubInfo">
                    <b>Personal Information<b>
                </div>

                <div class="collapse show mt-2" id="collapseClubInfo">
                    <div class="row mt-3 mb-3">
                        <!-- <b class="mb-2">Profile Picture</b> -->
                        <!-- <div id="photo-method" class="row mt-3" style="margin-bottom: 5px">
                            <span class="fotoprofil text-danger" style="position: absolute; margin-top: 8px; margin-left: 140px; z-index: 999">*</span>
                            <div class="col-6">
                                <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                                <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                            </div>
                            <div class="col-6">
                                <input style="margin-left: 12px" type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                                <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                            </div>
                        </div> -->
                        <div class="col-6">
                            <?php
                            // print_r($userData['IMAGE']);
                                if ($userData['IMAGE']) {
                                    ?>
                                    <img id="club_image" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center" src="/filepalio/image/<?=$userData['IMAGE']?>">
                                    <?php
                                }
                                else {
                                    ?>
                                    <img id="club_image" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center" src="../assets/img/tab5/create-post-black.png?v=2">
                                    <?php
                                }
                            ?>
                        </div>

                        <!-- <span class="profileimagestar text-danger" style="position: absolute; margin-top: 27px; margin-left: 249px; z-index: 999">*</span> -->

                        <div class="col-6 mt-3">
                            <label for="fotoProfile" id="profileLabelBtn" style="color: black; background-color: transparent; margin-right: 10px; margin-bottom: 10px; margin-left: 10px; border-radius: 35px; border: 1px solid black; font-weight: 600" class="btn">Change</label>
                            <!-- <br><p id="profileFileName" style="display: inline; margin-left: 14px">No file chosen</p> -->
                            <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)" />
                            <br><span id="fotoProfile-error" class="error" style="color: red; margin-left: 14px"></span>
                        </div>
                    </div>

                    <p class="mt-3 mb-1" style="color: #555555; font-weight: normal">Full Name</p>
                    <div class="row gx-0">
                        <div class="col-12">
                            <input value="<?= $userData['FIRST_NAME'] ?>" type="text" name="user_name" id="user_name" placeholder="Full Name" required />
                        </div>
                        <label id="username-exist" class="text-danger"></label>
                        <label id="username-not-exist" class="text-success"></label>
                    </div>

                    <p class="mt-3 mb-1" style="color: #555555; font-weight: normal">Email Address</p>
                    <div class="row gx-0">
                        <div class="col-12">
                            <input value="<?= $userData['EMAIL'] ?>" type="text" name="email_user" id="email_user" placeholder="Email Address" required />
                        </div>
                        <label id="username-exist" class="text-danger"></label>
                        <label id="username-not-exist" class="text-success"></label>
                    </div>

                    <p class="mt-3 mb-1" style="color: #555555; font-weight: normal">Gender</p>
                    <div class="row">
                        <div class="col-6">
                            <input type="radio" id="genderMale" name="gender_radio" class="radio" value="1" <?php if($userEXData['GENDER'] == 1): ?> checked <?php endif; ?>>
                            <label for="genderMale" style="font-weight: normal">&nbsp;&nbsp;Male</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="genderFemale" name="gender_radio" class="radio" value="2" <?php if($userEXData['GENDER'] == 2): ?> checked <?php endif; ?>>
                            <label for="genderFemale" style="font-weight: normal">&nbsp;&nbsp;Female</label><br>
                        </div>
                    </div>

                    <div class="row gx-0 mt-3">
                        <div class="col-12">
                            <input <?php if ($date): ?> value="<?= $formatedDate ?>" <?php else: ?> placeholder="Date of Birth" <?php endif; ?> type="text" name="date_birth" id="date_birth" min="1932-01-01" max="2016-12-31" required style="background-color: white" onfocus="(this.type='date'); (this.value='<?= $formatedDate ?>'); (this.placeholder='<?= $newdate ?>')"/>
                        </div>
                    </div>

                    <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                        <option value="" selected>Select Province</option>

                        <?php foreach($province as $p): ?>
                            <option <?php if($userEXDataGaspol['PROVINCE'] == $p['PROV_ID']): ?> selected <?php endif; ?>value="<?= $p['PROV_ID'] ?>"><?= $p['PROV_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="row gx-0 mt-3">
                        <div class="col-12">
                            <input class="mb-0" value="<?= $userData['QUOTE'] ?>" type="text" name="bio" id="bio" placeholder="Write your bio" required />
                            <span id="total-length" style="font-size: 10px; font-weight: normal; float: right; color: #777777"><span id="char-length">0</span>/500</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4" style="width: 100%; height: 10px; background-color: #e5e5e5"></div>

            <!-- <div class="row p-5">
                <div class="col-6 d-flex justify-content-start">
                    <p>Favourite Content</p>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="#" id="changetomodal" style="color: #f66701" onclick="modalCategory()">Change</a>
                </div>
            </div> -->

            <div class="row p-5" id="row_category">
                <!-- <php  
                    foreach ($conCatJoin as $cj) {
                        foreach ($contentCategory as $ct) {
                            if ($cj == $ct['ID']) {
                            ?>
                            <div class="col-6 text-center" id="col_category">
                                <div style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2">
                                    <img src="../assets/img/<= $ct['ICON'] ?>" style="width: 15px; height: auto; margin-right: 10px"><= $ct['CONTENT_CATEGORY'] ?>
                                </div>
                            </div>
                            <php
                            }
                        }
                    }
                    
                ?> -->
                <span style="font-weight: 700; font-size: 18xpx">Connected Account</span>
                <div class="row gx-0">
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button class="btn btn-white" style="border: 2px solid black; width: 280px; border-radius: 20px">
                            <div class="row">
                                <div class="col-2">
                                    <img src="../assets/img/facebook-icon.svg" alt="" style="width: 24px; height: 24px">
                                </div>
                                <div class="col-10 text-start">
                                    <span style="font-weight: 700">Connect Facebook</span>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button class="btn btn-white" style="border: 2px solid black; width: 280px; border-radius: 20px">
                            <div class="row">
                                <div class="col-2">
                                    <img src="../assets/img/twitter-icon.svg" alt="" style="width: 24px; height: 24px">
                                </div>
                                <div class="col-10 text-start">
                                    <span style="font-weight: 700">Connect Twitter</span>
                                </div>
                            </div>  
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4" style="width: 100%; height: 200px; background-color: white"></div>

            <input type="hidden" id="id_category" name="id_category" value="<?= $contentCategoryJoin['ID_CATEGORY'] ?>">
            <input type="hidden" id="name_category" name="name_category" value="<?= $arrayName ?>">

        </form>

        <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0" id="modalProgress">
                        <p>Upload in progress...</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-error-body">
                    <p id="error-modal-text">Please fill the required form.</p>
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
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Content Preference</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnClose()"></button>
                </div>
                <div class="modal-body">
                    <p>You can choose more than 3 categories</p>
                    <?php 
                    foreach ($contentCategory as $cc) {
                        // print_r($cc['ID']);
                        ?>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <input <?php if (in_array($cc['ID'], $conCatJoin)): ?> checked <?php endif; ?>class="form-check-input" type="checkbox" value="" id="checkCategory<?= $cc['ID'] ?>" name="checkCategory" onclick="saveCC('<?= $cc['ID'] ?>', '<?= $cc['CONTENT_CATEGORY'] ?>')">
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
    </script>

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

    $(document).ready(function(e) {
        
        $('#province').selectize();

        $("#province-selectized").bind("change paste keyup", function() {

            if ($('#province-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        var inputBio = document.querySelector('#bio');

        $('#bio').on('keyup change paste input', function(e) {
            if (inputBio.value.length > 500) {
                inputBio.value = inputBio.value.substring(0, 500);
            }
        });

        $('#bio').bind('input propertychange', function() {
            var count = $('#bio').val().length;
            $('#char-length').text(count);
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

    $("#province").change(function() {
        var provinsi = $(this).val();

        if (provinsi) {
            $(".provinsiklub").hide();
        } else {
            $(".provinsiklub").show();
        }
    });

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

    var category_id = [];
    var category_name = [] ;

    function modalCategory() {
        $("#modal-content-preference").modal('show');
        $("html").attr("style", "overflow-y: hidden");
    }

    function saveCbutton() {
        $("#modal-content-preference").modal('hide');
        // $("html").attr("style", "");
    }

    var modalCC = $("#modal-content-preference").css("display");

    $('#modal-content-preference').on('hidden.bs.modal', function (e) {
        $("html").attr("style", "");
    });

    function saveCC(cat_id, cat_name) {

        var checkCat = document.getElementById("checkCategory"+cat_id);

        if (checkCat.checked == true) {

            category_id.push(cat_id);
            category_name.push(cat_name);

            console.log(category_id);
            console.log(category_name);

            $('#row_category').html("");

            for (var i=0; i<category_id.length; i++){
                   
                var html = `<div class="col-6 text-center" id="col_category">
                                <div id="div_category`+category_id[i]+`" style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2">
                                    <img src="https://cdn-icons-png.flaticon.com/512/214/214351.png" style="width: 15px; height: auto; margin-right: 10px">`+category_name[i]+`
                                </div>
                            </div>`;

                $('#row_category').append(html);

            }

            $("#id_category").val(category_id.join("|"));
            
        }

        else {

            category_id = category_id.filter(function(item) {
                return item !== cat_id;
            });

            category_name = category_name.filter(function(item) {
                return item !== cat_name;
            });

            console.log(category_id);
            console.log(category_name);

            $('#row_category').html("");

            for (var i=0; i<category_id.length; i++){
                   
                var html = `<div class="col-6 text-center" id="col_category">
                                <div id="div_category`+category_id[i]+`" style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2">
                                    <img src="https://cdn-icons-png.flaticon.com/512/214/214351.png" style="width: 15px; height: auto; margin-right: 10px">`+category_name[i]+`
                                </div>
                            </div>`;

                $('#row_category').append(html);

            }

            $("#id_category").val(category_id.join("|"));
            
        }

    }

    var id_category = $('#id_category').val();
    var name_category = $('#name_category').val();

    if(id_category){


        var single_id = id_category.split("|");
        var single_name = name_category.split("|");

        for(var i=0; i<single_id.length; i++){
            console.log(single_id[i]);

            category_id.push(single_id[i]);
            category_name.push(single_name[i]);
        }

    }

    $("#popup-success").hide();

    function saveData(){

        var myform = $("#change-user")[0];
        var fd = new FormData(myform);

        fd.append("f_pin", F_PIN);

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register-user",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                
                $("#popup-success").show();
                setInterval(hideMsg, 8000);
                console.log("masuk sukses");

            },
            error: function (response) {
                
                alert("Uplod Gagal.");
                // $("#popup-success").show();
                // setInterval(hideMsg, 8000);
                // console.log("masuk ke yang gagal");

            }
        });
    }

    function hideMsg() {

        $("#popup-success").hide();

    }

</script>

<script>

    $("input[name=profile_radio]:radio").on("click", function () {
        if ($(this).val() == "File") {
            $('#fotoProfile').prop('required', true);
            $("#profileLabelBtn").text("Change")
            $("#fotoProfile").prop('accept', "image/*,profile_file/*")
            radioProfile = $(this).val();

            $('#club_image').attr('src', '../assets/img/tab5/create-post-black.png');
            $('#profileFileName').text("No file chosen");
        } else {
            $('#fotoProfile').prop('required', false);
            $("#profileLabelBtn").text("Take Photo")
            $("#fotoProfile").prop('accept', "image/*,profile_photo/*")
            radioProfile = $(this).val();

            $('#club_image').attr('src', '../assets/img/tab5/create-post-black.png');
            $('#profileFileName').text("No file chosen");
        }
    });

</script>

 <script>

    function backAndroidIOS(){

        var f_pin = new URLSearchParams(window.location.search).get('f_pin');

        if (window.Android) {
            window.history.back();
        }
        else if (window.webkit && window.webkit.messageHandlers) {
            window.location.href = 'tab3-profile?f_pin='+f_pin+'&env=1';
        }

    }

 </script>

