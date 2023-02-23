<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $f_pin = $_GET['f_pin'];

session_start();

// if(isset($_SESSION['f_pin'])){
//     $f_pin = $_SESSION['f_pin'];
// }
// else if(isset($_GET['f_pin'])){
//     $f_pin = $_GET['f_pin'];
// }

if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
else if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}

$dbconn = paliolite();

$ver = time();

$sqlData = "SELECT COUNT(*) as exist
  FROM KTA
  WHERE F_PIN = '$f_pin'";

//   echo $sqlData;

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $resDATA = $queDATA->get_result()->fetch_assoc();
// $exist = $resDATA["exist"];
// $queDATA->close();

// if ($exist > 0) {
//     header("Location: /gaspol_web/pages/card-kta?f_pin=$f_pin");
//     die();
// }

// NATIONALITY

$sqlData = "SELECT * FROM COUNTRIES";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$countries = $queDATA->get_result();
$queDATA->close();

// HOBBIES

$sqlData = "SELECT * FROM KTA_HOBBY";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$hobby = $queDATA->get_result();
$queDATA->close();

// BIRTHPLACE / CITY

$sqlData = "SELECT * FROM CITY ORDER BY CITY_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$birthplace = $queDATA->get_result();
$queDATA->close();

// PROVINCES

$sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$provinces = $queDATA->get_result();
$queDATA->close();

// CLUB

$sqlData = "SELECT * FROM TKT ORDER BY CLUB_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$club = $queDATA->get_result();
$queDATA->close();

// LOAD DATA FROM KTA MOBILITY

$sqlData = "SELECT KTA.*, CITIES.CITY_NAME AS CNAME FROM KTA LEFT JOIN CITIES ON KTA.BIRTHPLACE = CITIES.CITY_ID WHERE F_PIN = '$f_pin'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$ktaData = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

if ($ktaData['STATUS_ANGGOTA'] == 1) {
    header("Location: /gaspol_web/pages/card-kta-pronew?f_pin=$f_pin");
    die();
}

// PRICE NEW KTA PRO

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '3'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

// PRICE UPGRADE KTA PRO

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '7'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$priceUp = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

if($ktaData){
    $upgradeFee = $priceUp['REG_FEE'];
}else{
    $upgradeFee = $price['REG_FEE'];
}
$adminFee = $price['ADMIN_FEE'];

$queryVehicleBrand = $dbconn->prepare("SELECT * FROM VEHICLE_BRAND ORDER BY BRAND ASC");
$queryVehicleBrand->execute();
$vehicleBrand = $queryVehicleBrand->get_result();
$queryVehicleBrand->close();

$queryVehicleType = $dbconn->prepare("SELECT * FROM VEHICLE_TYPE ORDER BY VEHICLE_TYPE ASC");
$queryVehicleType->execute();
$vehicleType = $queryVehicleType->get_result();
$queryVehicleType->close();

$queryRoadsideAssistance = $dbconn->prepare("SELECT * FROM ROADSIDE_ASSISTANCE LEFT JOIN VEHICLE_BRAND ON ROADSIDE_ASSISTANCE.VEHICLE_BRAND = VEHICLE_BRAND.ID LEFT JOIN VEHICLE_TYPE ON ROADSIDE_ASSISTANCE.TYPE = VEHICLE_TYPE.ID WHERE F_PIN = '$f_pin'");
$queryRoadsideAssistance->execute();
$roadsideAssistance = $queryRoadsideAssistance->get_result();
$queryRoadsideAssistance->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form KTA Pro New</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content{
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

        input[type="radio"]{
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }

        .collapse{
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        }

         /* .selectize-input{
            border: none !important;
            margin-bottom: -4px;
            margin-left: -10px;
        } */

        /* .selectize-control{
            border-bottom: 2px solid #ebebeb !important;
        } */

        .selectize-input.focus {
            box-shadow: none !important;
            /* border: none !important; */
            /* border: 1px solid #ccc !important; */
        }

        .selectize-control.single .selectize-input, .selectize-control.single .selectize-input input {
            border: none;
            border-bottom: 2px solid #ebebeb;
            border-radius: 0px;
        }

        input, select {
            padding-left: 12px;
        }

        .selectize-input>* {
            font-size: 12px;
        }

        .selectize-dropdown-content {
            font-size: 12px;
        }

        #vehicle-island-selectized {
            font-size: 12px;
        }

        #vehicle-brand-selectized {
            font-size: 12px;
        }

        #vehicle-type-selectized {
            font-size: 12px;
        }

    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" id="kta-form" style="padding: 0px" action="/gaspol_web/logics/register_new_kta" enctype="multipart/form-data">
        
            <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b style="font-size: 14px">KTA Pro - Registration</b>
                    </div>
                </div>
            </div>
            
            <div class="container mx-auto mt-3">

                <?php if($ktaData): ?>
                    <b><p class="text-success mt-3">You upgrading from KTA Mobility.</p></b>
                    <p class="mb-3" style="color: grey">Most of your data will be generated from your KTA.</p>
                <?php else: ?>
                    <!-- <b><p class="text-danger mt-3 mb-2">KTA Data not found.</p></b> -->
                <?php endif; ?>

                <div class="mt-4 mx-auto" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentificationXXX" aria-expanded="false" aria-controls="collapseIdentification">
                    <div style="background-color: darkorange;border-radius: 200px;width: 20px;height: 20px;font-size: 12px;color: white;text-align: center;display: inline-block;">1</div>
                    <b style="margin-left: 10px; font-size: 14px">Identification</b>
                    <!-- <img id="collapse-img-3" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px"> -->
                </div>

                <div class="mt-3" id="collapseIdentification">

                    <p class="mb-2"><b>KTA Photo</b> &nbsp;<span class="starppimg text-danger" style="position: absolute">*</span> </p>
                    <!-- <input type="file" accept="image/*,photo/*,ocr/*" name="fotoProfil" id="fotoProfil" class="photo" placeholder="Foto Profil" required /> -->
                    <div id="photo-method" class="row" style="margin-bottom: 5px">
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                            <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                        </div>
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                            <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <?php if ($ktaData): ?>
                                <img id="imageProfile" src="../images/<?= $ktaData['PROFILE_IMAGE'] ?>" style="width: 100px; height: 100px; border-radius: 10px; object-fit: cover; object-position: center">
                            <?php else: ?>
                                <img id="imageProfile" src="../assets/img/avatar.svg" style="width: 100px; height: 100px; border-radius: 10px; object-fit: cover; object-position: center">
                            <?php endif; ?>
                        </div>
                        <div class="col-7">
                            <div class="row mt-3">
                                <?php if ($ktaData): ?>
                                    <label for="fotoProfile" id="profileLabelBtn" style="font-size: 15px; color: white; width: 80%; border-radius: 20px; background-color: grey; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Upload Photo</label>
                                <?php else: ?>
                                    <label for="fotoProfile" id="profileLabelBtn" style="font-size: 15px; color: black; width: 80%; border-radius: 20px; background-color: white; border: 1px solid black; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Upload Photo</label>
                            <small class="text-secondary" style="font-weight: 500; font-size: 8.5px">* Profile photo will be used for KTA card</small>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <!-- <?php if ($ktaData): ?>
                                    <p id="profileFileName" style="display: inline;"><?= $ktaData['PROFILE_IMAGE'] ?></p>
                                <?php else: ?>
                                    <p id="profileFileName" style="display: inline;">No file chosen</p>
                                <?php endif; ?> -->

                                <?php if($ktaData): ?>
                                    <input type="text" style="display:none;" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" value="<?= $ktaData['PROFILE_IMAGE'] ?>"/>
                                <?php else: ?>
                                    <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" onchange="loadFile(event)"/>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!$ktaData): ?>
                        <span id="fotoProfile-error" class="error" style="color: red"></span>
                    <?php endif; ?>

                    <div class="row gx-0 mt-3">
                        <div class="col-12">
                            <input type="text" name="name" id="name" placeholder="Full Name" value="<?= $ktaData['NAME'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="fullname text-danger" style="position: absolute; margin-top: 9px; margin-left: 84px; width: 10px">*</span>
                    </div>
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="email" name="email" id="email" placeholder="Email Address" value="<?= $ktaData['EMAIL'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="starmail text-danger" style="position: absolute; margin-top: 10px; margin-left: 110px; width: 10px">*</span>
                        <label id="username-exist" class="text-danger"></label>
                        <label id="username-not-exist" class="text-success"></label>
                    </div>
                    <div class="row gx-0">
                        <div class="col-1" style="margin-top: 11px">
                            <span><b>NIK</b></span>
                        </div>
                        <div class="col-11" style="padding-left: 15px">
                            <input type="text" pattern="[0-9]*" maxlength="16" name="ektp" id="ektp" placeholder="Identification Number" <?php if($ktaData): ?> disabled <?php endif; ?> value="<?= $ktaData['EKTP'] ?>" required/>
                        </div>
                        <span class="starnoktp text-danger" style="position: absolute; margin-top: 11px; margin-left: 207px; width: 10px">*</span>
                        <label id="ktp-exist" class="text-danger"></label>
                        <label id="ktp-not-exist" class="text-success"></label>
                        <label id="ktp-16" class="text-danger"></label>
                    </div>

                    <p class="mt-3 mb-2"><b>ID Card</b> &nbsp;<span class="starktp text-danger" style="position: absolute">*</span> </p>
                    <div id="id-photo-method" class="row" style="margin-bottom:5px">
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioEktpFile" name="ektp_radio" class="radio" value="File" checked>
                            <label for="radioEktpFile">&nbsp;&nbsp;From File</label>
                        </div>
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioEktpOcr" name="ektp_radio" class="radio" value="OCR">
                            <label for="radioEktpOcr">&nbsp;&nbsp;Take Photo</label><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php if ($ktaData): ?>
                                <img src="../images/<?= $ktaData['EKTP_IMAGE'] ?>" style="border-radius: 10px">
                            <?php else: ?>  
                                <label for="fotoEktp" id="ektpLabelBtn"> 
                                    <img id="imageKTP" src="../assets/img/ktp.svg">
                                </label>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <div class="row w-75 mt-3">
                                <?php if ($ktaData) {
                                    ?>
                                    <!-- <label for="fotoEktp" id="ektpLabelBtn" style="color: #FFFFFF; background-color: grey; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label> -->
                                    <?php
                                }
                                else {
                                    ?>
                                    <!-- <label for="fotoEktp" id="ektpLabelBtn" style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label> -->
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="row">
                                <!-- <?php if ($ktaData): ?>
                                    <p id="ektpFileName" style="display: inline;"><?= $ktaData['EKTP_IMAGE'] ?></p>
                                <?php else: ?>
                                    <p id="ektpFileName" style="display: inline;">No file chosen</p>
                                <?php endif; ?> -->

                                <?php if($ktaData): ?>
                                    <input type="text" style="display:none;" accept="image/*,ocr_file/*" name="fotoEktp" id="fotoEktp" class="photo" placeholder="Foto Fisik E-KTP" value="<?= $ktaData['EKTP_IMAGE'] ?>"/>
                                <?php else: ?>
                                    <input type="file" style="display:none;" accept="image/*,ocr_file/*" name="fotoEktp" id="fotoEktp" class="photo" placeholder="Foto Fisik E-KTP" required onchange="loadFile2(event)"/>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!$ktaData): ?>
                        <span id="fotoEktp-error" class="error" style="color: red"></span>
                    <?php endif; ?>
                </div>

                <!-- <div data-bs-toggle="collapse" class="mt-3" style="font-size: 18px" data-bs-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
                    <b>Personal Information</b>
                    <img id="collapse-img-1" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div> -->

                <div class="mt-2" id="collapsePersonal">
                    <div class="row gx-0">
                        <div class="col-12">
                            <select class="mt-1 mb-2" id="birthplace" name="birthplace" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                            
                                <?php if ($ktaData): ?>
                                    <option value="<?= $ktaData['BIRTHPLACE'] ?>" selected><?= $ktaData['CNAME'] ?></option>
                                <?php else: ?>
                                    <option value="" selected>Birthplace</option>

                                    <?php foreach($birthplace as $b): ?>
                                        
                                        <option value="<?= $b['CITY_ID'] ?>"><?= ucwords(strtolower($b['CITY_NAME'])) ?></option>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </select>
                            <span class="starbp text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 87px">*</span>
                            <span id="birthplace-error" class="error" style="color: red"></span>
                        </div>
                    </div>

                    <p class="mt-3 mb-1"><b>Date of Birth</b> <span class="bdstar text-danger" style="position: absolute; margin-left: 8px; margin-top: -5px">*</span> </p>
                    <input type="date" name="date_birth" id="date_birth" placeholder="Date Of Birth" <?php if($ktaData): ?> disabled <?php else: ?> value="1970-01-01" min="1932-01-01" max="2016-12-31"<?php endif; ?> style="background-color: white" value="<?= $ktaData['DATEBIRTH'] ?>" required/>

                    <p class="mt-3 mb-1"><b>Gender</b></p>

                    <?php if ($ktaData): ?>

                        <div class="row">
                            <div class="col-6">
                                <input type="radio" id="genderMale" <?php if($ktaData): ?> disabled <?php endif; ?> name="gender_radio" class="radio" value="1" <?php if($ktaData['GENDER'] == 1): ?> checked <?php endif; ?> >
                                <label for="genderMale">&nbsp;&nbsp;Male</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" id="genderFemale" <?php if($ktaData): ?> disabled <?php endif; ?> name="gender_radio" class="radio" value="2" <?php if($ktaData['GENDER'] == 2): ?> checked <?php endif; ?>>
                                <label for="genderFemale">&nbsp;&nbsp;Female</label><br>
                            </div>
                        </div>
                    
                    <?php else: ?>

                        <div class="row">
                            <div class="col-6">
                                <input type="radio" id="genderMale" name="gender_radio" class="radio" value="1" checked>
                                <label for="genderMale">&nbsp;&nbsp;Male</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" id="genderFemale" name="gender_radio" class="radio" value="2">
                                <label for="genderFemale">&nbsp;&nbsp;Female</label><br>
                            </div>
                        </div>

                    <?php endif; ?>
                    <select class="mt-3 mb-2" id="bloodtype" name="bloodtype" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                        <option value="" >Blood Type</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "A"): ?> selected <?php endif; ?> value="A">A</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "B"): ?> selected <?php endif; ?> value="B">B</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "AB"): ?> selected <?php endif; ?> value="AB">AB</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "O"): ?> selected <?php endif; ?> value="O">O</option>
                    </select>
                    <span class="starbt text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 92px">*</span>
                    <span id="bloodtype-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="nationality" name="nationality" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                        <option value="" selected>Nationality</option>

                        <?php foreach($countries as $c): ?>
                            <option value="<?= $c['ID'] ?>" <?php if($ktaData['NATIONALITY'] == $c['ID']): ?> selected <?php endif; ?>><?= $c['COUNTRY_NAME'] ?></option>
                        <?php endforeach; ?>

                    </select>
                    <span class="starnation text-danger" style="position: absolute; z-index: 999; margin-top: -47px; margin-left: 91px">*</span>
                    <span id="nationality-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="hobby" name="hobby" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                        
                        <option value="" selected>Hobby</option>

                        <?php foreach($hobby as $h): ?>
                            <option value="<?= $h['ID'] ?>" <?php if($ktaData['HOBBY'] == $h['ID']): ?> selected <?php endif; ?>><?= $h['NAME'] ?></option>
                        <?php endforeach; ?>

                    </select>
                    <span class="starhobby text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 62px">*</span>
                    <span id="hobby-error" class="error" style="color: red"></span>

                    <div class="add-hobby row gx-0">
                        <div class="col-12">
                            <input type="text" name="hobby_desc" id="hobby_desc" placeholder="Hobby" required/>
                        </div>
                        <span class="staraddhobby text-danger" style="position: absolute; margin-top: 10px; margin-left: 60px; width: 10px">*</span>
                    </div> 

                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseAddressXXX" aria-expanded="false" aria-controls="collapseAddress">
                    <div style="background-color: darkorange;border-radius: 200px;width: 20px;height: 20px;font-size: 12px;color: white;text-align: center;display: inline-block;">2</div>
                    <b style="margin-left: 10px; font-size: 14px">Address</b>
                    <!-- <img id="collapse-img-2" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px"> -->
                </div>

                <div class="mt-2" id="collapseAddress">
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="address" id="address" placeholder="Full Address" value="<?= $ktaData['ADDRESS'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="staraddress text-danger" style="position: absolute; margin-top: 10px; margin-left: 98px; width: 10px">*</span>
                    </div>
                    
                    <?php 

                    if ($ktaData):

                    $newRTRW = explode("/", $ktaData['RTRW']);

                    $rt = $newRTRW[0];
                    $rw = $newRTRW[1];

                    endif;

                    ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <input type="text" pattern="[0-9]*" maxlength="3" name="rt" id="rt" placeholder="RT" onKeyPress="if (this.value.length == 3) return false;" value="<?= $rt ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="starrt text-danger" style="position: absolute; margin-top: 8px; margin-left: 36px; width: 10px">*</span>
                        <div class="col-6">
                            <input type="text" pattern="[0-9]*" maxlength="3" name="rw" id="rw" placeholder="RW" onKeyPress="if (this.value.length == 3) return false;" value="<?= $rw ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                            <span class="starrw text-danger" style="position: absolute; margin-top: -44px; margin-left: 42px; width: 10px">*</span>
                        </div>
                    </div>          
                    
                    <?php if (!$ktaData): ?>

                    <select class="mt-3 mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px">
                        <option value="" selected>Postcode</option>
                    </select>
                    <span class="starpost text-danger" style="position: absolute; z-index: 999; margin-top: -47px; margin-left: 80px">*</span>
                    <span id="postcode-error" class="error" style="color: red"></span>

                    <?php else: ?>

                    <select class="mt-3 mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px" disabled>
                        <option value="<?= $ktaData['POSTCODE'] ?>" selected><?= $ktaData['POSTCODE'] ?></option>
                    </select>

                    <?php endif; ?>

                    <!-- <input type="text" name="province" id="province" placeholder="Province" value="<?= $ktaData['PROVINCE'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/>
                    <input type="text" name="city" id="city" placeholder="City" value="<?= $ktaData['CITY'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/>
                    <input type="text" name="district" id="district" placeholder="District" value="<?= $ktaData['DISTRICT'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/>
                    <input type="text" name="district_word" id="district_word" placeholder="District Word" value="<?= $ktaData['DISTRICT_WORD'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/> -->
                    <!-- <input type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" name="postcode" id="postcode" placeholder="Postcode" value="<?= $ktaData['POSTCODE'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> /> -->

                    <?php if (!$ktaData): ?>

                        <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                            <option value="" selected>Province</option>

                            <?php foreach($provinces as $p): ?>
                                <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <span class="starprovince text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 77px">*</span>
                        <span id="province-error" class="error" style="color: red"></span>

                        <select class="mt-3 mb-2" id="city" name="city" aria-label="" style="font-size: 16px">
                            <option value="" selected>City</option>
                        </select>

                        <span class="starcity text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 47px">*</span>
                        <span id="city-error" class="error" style="color: red"></span>

                        <select class="mt-3 mb-2" id="district" name="district" aria-label="" style="font-size: 16px">
                            <option value="" selected>District</option>
                        </select>

                        <span class="stardist text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 66px">*</span>
                        <span id="district-error" class="error" style="color: red"></span>

                        <select class="mt-3 mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px">
                            <option value="" selected>District Word</option>
                        </select>

                        <span class="starsubdist text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 105px">*</span>
                        <span id="subdistrict-error" class="error" style="color: red"></span>

                        <?php else: 
                        
                        $sqlData = "SELECT * FROM PROVINCE WHERE PROV_ID = '".$ktaData['PROVINCE']."'";

                        $queDATA = $dbconn->prepare($sqlData);
                        $queDATA->execute();
                        $provinceGet = $queDATA->get_result()->fetch_assoc();
                        $queDATA->close();

                        $sqlData = "SELECT * FROM CITIES WHERE CITY_ID = '".$ktaData['CITY']."'";

                        $queDATA = $dbconn->prepare($sqlData);
                        $queDATA->execute();
                        $cityeGet = $queDATA->get_result()->fetch_assoc();
                        $queDATA->close();

                        $sqlData = "SELECT * FROM DISTRICT WHERE DIS_ID = '".$ktaData['DISTRICT']."'";

                        $queDATA = $dbconn->prepare($sqlData);
                        $queDATA->execute();
                        $districtGet = $queDATA->get_result()->fetch_assoc();
                        $queDATA->close();

                        $sqlData = "SELECT * FROM SUBDISTRICT WHERE DIS_ID = '".$ktaData['DISTRICT']."'";

                        $queDATA = $dbconn->prepare($sqlData);
                        $queDATA->execute();
                        $subDistrictGet = $queDATA->get_result()->fetch_assoc();
                        $queDATA->close();

                        ?>

                            <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['PROVINCE'] ?>" selected><?= $provinceGet['PROV_NAME'] ?></option>
                            </select>

                            <select class="mt-3 mb-2" id="city" name="city" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['CITY'] ?>" selected><?= $cityeGet['CITY_NAME'] ?></option>
                            </select>

                            <select class="mt-3 mb-2" id="district" name="district" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['DISTRICT'] ?>" selected><?= $districtGet['DIS_NAME'] ?></option>
                            </select>

                            <select class="mt-3 mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['DISTRICT_WORD'] ?>" selected><?= $subDistrictGet['SUBDIS_NAME'] ?></option>
                            </select>

                        <?php endif; ?>


                </div>

                <!-- <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClub" aria-expanded="false" aria-controls="collapseClub">
                    <b>Club<b>
                    <img id="collapse-img-3" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClub">
                    
                    <p class="mt-3 mb-1">Club Type</p>
                    <div class="row">
                        <div class="col-6">
                            <input type="radio" id="clubIMI" name="club_type" class="radio" value="1" checked>
                            <label for="clubIMI">&nbsp;&nbsp;IMI Club</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="clubPrivate" name="club_type" class="radio" value="2">
                            <label for="clubPrivate">&nbsp;&nbsp;Private</label><br>
                        </div>
                    </div>
                    <select class="mt-3 mb-2" id="club_location" name="club_location" aria-label="" style="font-size: 16px">
                        <option value=""  selected>Club Location</option>

                        <?php foreach($provinces as $p): ?>
                            <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                        <?php endforeach; ?>

                    </select>

                    <span class="starclubl text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 115px">*</span>
                    <span id="clublocation-error" class="error" style="color: red"></span>
                    
                    <div class="club-bungkus">
                        <select class="mt-3 mb-2" id="club_choice" name="club_choice" aria-label="" style="font-size: 16px">
                            <option value=""  selected>Club Choice</option>

                            <?php foreach($club as $c): ?>
                                <option value="<?= $c['ID'] ?>"><?= ucwords(strtolower($c['CLUB_NAME'])) ?></option>
                            <?php endforeach; ?>

                        </select>

                        <span class="starclubc text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 106px">*</span>
                        <span id="clubchoice-error" class="error" style="color: red"></span>
                    </div>

                </div> -->

                <div class="form-check" style="margin-top: 50px">
                    <input class="form-check-input" type="checkbox" value="" id="checkERA" onclick="functionERA()">
                    <label class="form-check-label" for="checkERA" style="font-weight: 600">
                        Add-on <span style="color: #f66701">Emergency Roadside Assistance</span>
                    </label>
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
                            <?php if($ktaData): ?>
                                <div class="col-6" style="color: #626262">
                                    <b>Mobility Upgrade Fee</b>
                                </div>
                            <?php else: ?>
                                <div class="col-6" style="color: #626262">
                                    <b>KTA Pro Fee</b>
                                </div>
                            <?php endif; ?>
                            <div class="col-6 d-flex justify-content-end">
                                <b style="font-size: 20px">Rp. <?= number_format($upgradeFee, 0, '', '.') ?></b>
                            </div>

                            <div class="col-8" style="color: #626262">
                                <b id="insurance-payment">Emergency Roadside Assistance</b>
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <b id="insurance-payment-price" style="font-size: 20px"></b>
                            </div>

                            <div class="col-6" style="color: #626262">
                                <b id="summary-payment">Total Price</b>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <b id="summary-payment-price" style="font-size: 20px"></b>
                            </div>

                        </div>
                        <div class="row">
                            <select id="dropdownMenuSelectMethod" style="border: 1px solid #d7d7d7" onchange="selectMethod(this.value);">
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
                                <b>Rp. <?= number_format($adminFee, 0, '', '.') ?></b>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6" style="color: #626262">
                                Total Payment
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <b style="font-size: 20px">Rp. <?= number_format($upgradeFee+$adminFee, 0, '', '.') ?></b>
                            </div>
                        </div> -->
                    </div>
                </div>

                <?php if ($ktaData): ?>
                    <input type="hidden" id="status_anggota" name="status_anggota" value="1">
                <?php else: ?>
                    <input type="hidden" id="status_anggota" name="status_anggota" value="0">
                <?php endif; ?>

                <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                    <button type="submit" class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 50%; height: 50px; color: white" onclick="selectizeValid()"><b>Pay Now</b></button>
                </div>
            </div>
            <!-- <div style="width: 100%; height: 100px; background-color: #fff"></div> -->
        </form>
    
    </div>

    <section id="page-roadside-assistance" style="display:none;">

        <!-- ROADSIDE ASSISTANCE -->
        <section id="imi-roadside-assistance">

            <div class="mainForm" style="padding: 0px">

                <form method="POST" class="mainform" style="padding: 0px" id="roadside-assistance" enctype="multipart/form-data">
                    <div class="row gx-0 p-3 fixed-top" style="border-bottom: 2px #e5e5e5 solid; background-color: white">
                        <div class="col-2">
                            <a class="ms-2" onclick="backRa()" style="position: absolute"><img src="../assets/img/membership-back.png" alt="" style="height: 22px"></a>
                        </div>
                        <div class="col-10">
                            <p style="margin-bottom: 0px; font-size: 14px; font-weight: 700">Gaspol RodA</p>
                        </div>
                    </div>

                    <div class="container mx-auto" style="margin-top: 80px">

                        <!-- <div id="popup-success" class="alert alert-success" role="alert">
                            You've successfully changed your profile.
                        </div> -->

                        <div aria-expanded="false" aria-controls="collapseClubInfo">
                            <p style="font-size: 13px; font-weight: 600">Emergency</p>
                            <b style="font-size: 19px; font-weight: 700">Roadside Assistance<b>
                        </div>

                        <div class="collapse show mt-3" id="collapseClubInfo">
                            
                            <p style="color: #555555; font-size: 13px; font-weight: normal">Specialized in Vehicle Logistics for all types of vehicles, we cover Sumatra, Java, and Bali. Fast delivery with 24/7 customer service.</p>
                            
                            <div class="row mt-3">
                                <div class="col-2 d-flex justify-content-center">
                                    <img src="../assets/img/path.svg" alt="" style="width: 23px; height: 23px">
                                </div>
                                <div class="col-10">
                                    <p style="font-size: 13px">Value for Money</p>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-10">
                                    <p style="font-size: 13px; color: #777777">Get 1x towing for your vehicle yearly.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-2 d-flex justify-content-center">
                                    <img src="../assets/img/thunder.svg" alt="" style="width: 23px; height: 23px">
                                </div>
                                <div class="col-10">
                                    <p style="font-size: 13px">Quick Response</p>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-10">
                                    <p style="font-size: 13px; color: #777777">Ready for you 24/7, responds to your calls below 30 sec with attendance max. 1 hour.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-2 d-flex justify-content-center">
                                    <img src="../assets/img/paper.svg" alt="" style="width: 23px; height: 23px">
                                </div>
                                <div class="col-10">
                                    <p style="font-size: 13px">Wide Coverage Tow</p>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-10">
                                    <p style="font-size: 13px; color: #777777">With distance up to 50Km, we are sure to help.</p>
                                </div>
                            </div>

                            <section id="contact">

                                <div class="row mt-3">
                                    <div class="col-12">

                                        <p style="font-size: 13px; color: #777777">For more information:</p>

                                        <div class="row mt-3">
                                            <div class="col-2 d-flex justify-content-center">
                                                <img src="../assets/img/question.svg" alt="" style="width: 18px; height: 23px">
                                            </div>
                                            <div class="col-10">
                                                <p style="font-size: 13px">(021) 2356 7888 / 5050 7888</p>
                                            </div>
                                            <div class="col-2">
                                                
                                            </div>
                                            <div class="col-10">
                                                <p style="font-size: 13px; color: #777777">Customer care</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-2 d-flex justify-content-center">
                                                <img src="../assets/img/whatsapp.svg" alt="" style="width: 18px; height: 23px">
                                            </div>
                                            <div class="col-10">
                                                <p style="font-size: 13px">021 8060 0691</p>
                                            </div>
                                            <div class="col-2">
                                                
                                            </div>
                                            <div class="col-10">
                                                <p style="font-size: 13px; color: #777777">Whatsapp</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                
                            </section>

                            <section id="vehicle" class="mt-3" style="display: none">
                                <b style="font-size: 19px; font-weight: 700">Vehicle Information<b>
                                <p style="font-size: 13px; font-weight: 600; color: #555555;">You can add more than one vehicle.</p>

                                <?php
                                foreach ($roadsideAssistance as $ra) {
                                    ?>
                                    <div class="row mt-3 single-car" id="vehicle-<?= $ra['ID'] ?>">
                                        <div class="col-2 d-flex justify-content-center">
                                            <img src="../assets/img/accept.svg" alt="" style="width: 23px; height: 23px">
                                        </div>
                                        <div class="col-8">
                                            <p style="font-size: 13px"><span id="vehicle-name"><?= $ra['BRAND'] ?></span>, <span id="vehicle-series"><?= $ra['VEHICLE_TYPE'] ?></span>, <span id="vehicle-production"><?= $ra['YEAR'] ?></span></p>
                                        </div>
                                        <div class="col-2 d-flex justify-content-center">
                                            <img src="../assets/img/Back-(White).png" alt="" style="width: 23px; height: 23px; transform: rotate(180deg)">
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="row mt-3" id="add-vehicle">
                                    <div class="col-2 d-flex justify-content-center">
                                        <img src="../assets/img/additional.svg" alt="" style="width: 16px; height: 16px" class="mt-1">
                                    </div>
                                    <div class="col-8">
                                        <p style="font-size: 13px">Add vehicle</p>
                                    </div>
                                    <div class="col-2 d-flex justify-content-center">
                                        <img src="../assets/img/Back-(White).png" alt="" style="width: 23px; height: 23px; transform: rotate(180deg)">
                                    </div>
                                </div>
                            </section>

                        </div> 
                    </div>

                    <div class="row p-4 bg-light fixed-bottom" style="border-top: 1px solid #e9e9e9">
                        <div class="row gx-0" id="price">
                            <div class="col-6 text-start">
                                <p class="ms-3" style="font-size: 14px; color: #000000">Price</p>
                            </div>
                            <div class="col-6 text-end">
                                <p id="total-price" class="me-3" style="font-size: 14px; font-weight: 600"></p>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" id="submit-orange" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #ff6b00; font-weight: 700; border: none; color: white" onclick="purchaseCategory()">Purchase</button>
                            <button type="button" id="buy-add-on" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #ff6b00; font-weight: 700; border: none; color: white" onclick="buyCategory()">Buy Add-on</button>
                        </div>
                    </div>

                    <!-- <div class="mt-4" style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

                    <!-- <div class="row p-5">
                        <div class="col-6 d-flex justify-content-start">
                            <p>Favourite Content</p>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="#" id="changetomodal" style="color: #f66701" onclick="modalCategory()">Change</a>
                        </div>
                    </div> -->

                    <div class="row px-5" id="row_category">
                        <?php  
                            // foreach ($conCatJoin as $cj) {
                            //     foreach ($contentCategory as $ct) {
                            //         if ($cj == $ct['ID']) {
                            //         ?>
                                        <!-- <div class="col-6 text-center" id="col_category">
                                            <div style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2">
                                                <img src="../assets/img/<?= $ct['ICON'] ?>" style="width: 15px; height: auto; margin-right: 10px"><?= $ct['CONTENT_CATEGORY'] ?>
                                            </div>
                                        </div> -->
                                        <?php
                            //         }
                            //     }
                            // }
                        ?>
                    </div>

                    <div class="mt-4" style="width: 100%; height: 250px; background-color: white"></div>

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

        </section>
        <!-- END OF ROADSIDE ASSISTANCE -->

    </section>

    <div id="modal-vehicle" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-vehicle">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="modal-title" style="font-weight: 600">Add Vehicle</h5>
                        </div>
                        <div class="col-12">
                            <p style="font-size: 12px; color: #555555">Insert vehicle information for the E.R.A. add-on.</p>
                        </div>
                    </div>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">

                    <!-- <p style="font-size: 12px">Vehicle Category</p> -->
                    <div class="row">
                        <div class="col-12">
                            <p style="font-size: 12px">Vehicle Category</p>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="automobile" name="vehicle_category" class="radio" value="1" checked>
                            <label for="automobile" style="font-size: 12px">&nbsp;&nbsp;Automobile</label><br>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="motorcycle" name="vehicle_category" class="radio" value="2">
                            <label for="motorcycle" style="font-size: 12px">&nbsp;&nbsp;Motorcycle</label>
                        </div>
                    </div>

                    <select class="mt-3 mb-2" id="vehicle-island" name="vehicle-island" aria-label="" style="font-size: 16px">
                        <option value="" selected>Select Island</option>

                        <?php foreach($provinces as $p): ?>
                            <option style="font-size: 12px" value="<?= $p['PROV_ID'] ?>"><?= $p['PROV_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span id="island-error" style="font-size: 12px; color: red">This field is required.</span>

                    <!-- <div class="mb-3">
                        <input type="file" onchange="loadFile3(event)" class="form-control d-none" id="vehicle-photo-hidden" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px">

                        <label for="vehicle-photo-hidden" class="form-label" style="font-size: 12px">Upload Vehicle Photo</label>
                        <input class="form-control" type="text" id="photo-name" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px" readonly>
                    </div> -->

                    <div class="mb-3">
                        <input type="file" onchange="loadFile3(event)" class="form-control d-none" id="vehicle-photo-hidden" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px">

                        <div class="row gx-0">
                            <div class="col-10">
                                <input class="form-control" type="text" id="photo-name" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px; background-color: white" placeholder="Upload Vehicle Photo" readonly>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <label for="vehicle-photo-hidden" class="form-label">
                                    <img id="choose-button" src="../assets/img/choose-image.svg" alt="" style="width: 25px; height: 25px; margin-left: -10px">
                                </label>
                                <img id="revert-button" src="../assets/img/switch-icon.svg" alt="" style="width: 25px; height: 25px; margin-left: -10px" onclick="revertButton()">
                            </div>
                        </div>
                    </div>

                    <span id="photo-error" style="font-size: 12px; color: red">This field is required.</span>


                    <!-- PAIR OF QUERY -->
                    <select class="mt-3 mb-2" id="vehicle-brand" name="vehicle-brand" aria-label="" style="font-size: 16px">
                        <option value="" selected>Vehicle Brand</option>

                        <?php foreach($vehicleBrand as $vb): ?>
                            <option value="<?= $vb['ID'] ?>"><?= $vb['BRAND'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span id="brand-error" style="font-size: 12px; color: red">This field is required.</span>

                    <select class="mt-3 mb-2" id="vehicle-type" name="vehicle-type" aria-label="" style="font-size: 16px">
                        <option value="" selected>Type</option>
                    </select>

                    <span id="type-error" style="font-size: 12px; color: red">This field is required.</span>
                    <!-- END -->

                    <input id="vehicle-year" type="text" style="font-size: 12px" placeholder="Year">
                    <span id="year-error" style="font-size: 12px; color: red">This field is required.</span>

                    <input id="vehicle-license" type="text" style="font-size: 12px" placeholder="License Plate">
                    <span id="license-error" style="font-size: 12px; color: red">This field is required.</span>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 45%">Close</button> -->
                    <button type="button" class="btn btn-dark" style="width: 100%; background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701; border-radius: 20px" onclick="submitButton()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <!-- <div id="modalProgress" class="modal"> -->

        <!-- Modal content -->
        <!-- <div class="modal-content"> -->
            <!-- <p>Upload in progress...</p> -->
        <!-- </div> -->

    <!-- </div> -->

    <!-- <div id="modalSuccess" class="modal"> -->

        <!-- Modal content -->
        <!-- <div class="modal-content">
            <p>Successfully upload data</p>
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

    <div class="modal fade" id="modalSuccess" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KTA Pro Registration Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="card-kta-pronew.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">View Card</button></a>
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

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p style="font-size: 12px" id="validation-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-error-body">
                    <p id="error-modal-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-otp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 50px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p style="font-size: 12px">Kode OTP telah dikirim ke email <span id="email-place-otp" class="text-success"></span>, silahkan buka email anda untuk mendapatkan kode OTP.</p>
                    <div class="input-group mb-3 mt-3">
                        <input type="text" maxlength="6" pattern="[0-9]*" id="input-otp" class="form-control" placeholder="Kode OTP" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <small style="font-size: 12px" id="otp-not-correct" class="text-danger d-none">Kode OTP tidak sesuai.</small>
                        </div>
                    </div>
                    <div class="btn btn-success" onclick="checkOTP()">Submit</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalMembership">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KTA Pro Registration Success!</h1>
                    <p class="mt-2">Please download Gaspol Apps to see your KTA card. And check your email to get login information in Gaspol Apps!</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                        <button type="button" class="btn btn-dark mt-3" id="modalMembership-mainMenu" style="background-color: #f66701; border: 1px solid #f66701">Main Menu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="is_kta" name="is_kta" value="<?= $ktaData['ID'] ?>">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 3;
        localStorage.setItem('grand-total', <?= $upgradeFee+$adminFee ?>);
    </script>

    <script>

        var price = '<?= number_format($upgradeFee, 0, '', '.') ?>';
        
        if ($('#is_kta').val() != ""){
            var title = 'Mobility Upgrade Fee';
        }else{
            var title = 'KTA Pro Fee';
        }
        
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';

        var is_takken;
        var is_takken_ktp;

    </script>

    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-kta-pronew.js?v=<?php echo $ver; ?>"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

<script>

// $('input[type=radio][name=club_type]').change(function() {
//     if (this.value == '1') {
//         $('#club_choice').attr('disabled',false);
//         $('.club-bungkus').show();
//     }
//     else if (this.value == '2') {
//         $('#club_choice').attr('disabled',true);
//         $('.club-bungkus').hide();
//     }
// });

</script>

</html>

<script>

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

$(document).ready(function(e) {

    if ($('#is_kta').val() != ""){
        $('.fullname').hide();
        $(".starmail").hide();
        $(".starbp").hide();
        $(".bdstar").hide();
        $(".starbt").hide();
        $(".starnation").hide();
        $(".starhobby").hide();
        $(".staraddhobby").hide();
        $(".staraddress").hide();
        $(".starrt").hide();
        $(".starrw").hide();
        $(".starpost").hide();
        $(".starprovince").hide();
        $(".starcity").hide();
        $(".stardist").hide();
        $(".starsubdist").hide();
        // $(".starclubl").hide();
        // $(".starclubc").hide();
        $(".starppimg").hide();
        $(".starnoktp").hide();
        $(".starktp").hide();

        is_takken = 1;
        is_takken_ktp = 1;
    }

    $('#nationality').selectize();
    $('#bloodtype').selectize();
    $('#hobby').selectize();
    // $('#club_location').selectize();
    // $('#club_choice').selectize();
    $('#birthplace').selectize();
    $('#postcode').selectize();

    $('#province').selectize();
    $('#city').selectize();
    $('#district').selectize();
    $('#subdistrict').selectize();

    $('#dropdownMenuSelectMethod').selectize();

    $('#dropdownMenuSelectMethod-selectized').attr('readonly', true);

    var $select = $(document.getElementById('postcode'));
    var selectize = $select[0].selectize;

    <?php if($ktaData['HOBBY'] == 6): ?>
        $("#hobby_desc").val('<?= $ktaData['HOBBY_DESC'] ?>');
        $(".add-hobby").show();
        $("#hobby_desc").attr('disabled',true);
    <?php else: ?>
        $(".add-hobby").hide();
    <?php endif; ?>
    
    getDataCookie();

    $("#postcode-selectized").bind("change paste keyup", function() {
       
        var postcode = $(this).val();
        var formData = new FormData();

        formData.append('postcode', postcode);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // // console.log(xmlHttp.responseText);

                var obj = JSON.parse(xmlHttp.responseText);
                
                Object.keys(obj).forEach(function (item){

                    // // console.log(obj[item]['POSTAL_CODE']);                    
                    selectize.addOption({value: obj[item]['POSTAL_ID'], text: obj[item]['POSTAL_CODE']});

                });

            }
        }
        xmlHttp.open("post", "../logics/get_postcode");
        xmlHttp.send(formData);
    });

    $("#postcode").bind("change", function() {

        var postcode = $(this).val();
        var formData = new FormData();

        formData.append('postcode', postcode);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
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
                selectize.addOption({value: province_id, text: province_name});
                selectize.setValue(province_id);

                var $select2 = $(document.getElementById('city'));
                var selectize2 = $select2[0].selectize;
                selectize2.addOption({value: city_id, text: city_name});
                selectize2.setValue(city_id);

                var $select3 = $(document.getElementById('district'));
                var selectize3 = $select3[0].selectize;
                selectize3.addOption({value: district_id, text: district_name});
                selectize3.setValue(district_id);

                var $select4 = $(document.getElementById('subdistrict'));
                var selectize4 = $select4[0].selectize;
                selectize4.addOption({value: subdis_id, text: subdis_name});
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

    $("#hobby").change(function() {
        var value = $(this).val();

        if (value == 6) {
            $(".add-hobby").show();
        }
        else {
            $(".add-hobby").hide();
        }
    });

    // FOR ARROW COLLAPSE

    $('#collapsePersonal').on('shown.bs.collapse', function () {
        $('#collapse-img-1').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapsePersonal').on('hidden.bs.collapse', function () {
        $('#collapse-img-1').attr('src','../assets/img/arrow-down.png');
    });

    $('#collapseAddress').on('shown.bs.collapse', function () {
        $('#collapse-img-2').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapseAddress').on('hidden.bs.collapse', function () {
        $('#collapse-img-2').attr('src','../assets/img/arrow-down.png');
    });

    $('#collapseClub').on('shown.bs.collapse', function () {
        $('#collapse-img-3').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapseClub').on('hidden.bs.collapse', function () {
        $('#collapse-img-3').attr('src','../assets/img/arrow-down.png');
    });

    $('#collapseIdentification').on('shown.bs.collapse', function () {
        $('#collapse-img-4').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapseIdentification').on('hidden.bs.collapse', function () {
        $('#collapse-img-4').attr('src','../assets/img/arrow-down.png');
    });


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
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            // console.log(xmlHttp.responseText);

            var obj = JSON.parse(xmlHttp.responseText);
                
                Object.keys(obj).forEach(function (item){

                    // // console.log(obj[item]['POSTAL_CODE']);
                    
                    selectize.addOption({value: obj[item]['CITY_ID'], text: capitalize(obj[item]['CITY_NAME'])});

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
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(xmlHttp.responseText);
                    
                    Object.keys(obj).forEach(function (item){

                        // // console.log(obj[item]['POSTAL_CODE']);
                    
                        selectize.addOption({value: obj[item]['DIS_ID'], text: capitalize(obj[item]['DIS_NAME'])});

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
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(xmlHttp.responseText);
                    
                    Object.keys(obj).forEach(function (item){

                        // // console.log(obj[item]['POSTAL_CODE']);

                        selectize.addOption({value: obj[item]['SUBDIS_ID'], text: capitalize(obj[item]['SUBDIS_NAME'])});

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
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // // console.log(">>>"+xmlHttp.responseText);
                const postcode = JSON.parse(xmlHttp.responseText);

                var $select = $(document.getElementById('postcode'));
                var selectize = $select[0].selectize;

                // CLEAR POSTAL CODE
                if ($('#postcode').val().trim() === '') { // IF POSTCODE EMPTY FILL - IF NOT NOT FILL
                    selectize.clearOptions();
                    selectize.clear(); 

                    selectize.addOption({value: postcode.POSTAL_ID, text: postcode.POSTAL_CODE});
                    selectize.setValue(postcode.POSTAL_ID);  
                }     
                
            }
        }
        xmlHttp.open("post", "../logics/get_postcode");
        xmlHttp.send(formData);
    });

    // FOR RED DOT IN SELECTIZE

    $("#birthplace-selectized").bind("change paste keyup", function() {

        if($('#birthplace-selectized').val()){
            $('.starbp').hide();
        }else{
            $('.starbp').show();
        }
    });

    $("#bloodtype-selectized").bind("change paste keyup", function() {

        if($('#bloodtype-selectized').val()){
            $('.starbt').hide();
        }else{
            $('.starbt').show();
    }
    });

    $("#nationality-selectized").bind("change paste keyup", function() {

        if($('#nationality-selectized').val()){
            $('.starnation').hide();
        }else{
            $('.starnation').show();
        }
    });

    $("#postcode-selectized").bind("change paste keyup", function() {

        if($('#postcode-selectized').val()){
            $('.starpost').hide();
        }else{
            $('.starpost').show();
        }
    });

    $("#province-selectized").bind("change paste keyup", function() {

        if($('#province-selectized').val()){
            $('.starprovince').hide();
        }else{
            $('.starprovince').show();
        }
    });

    $("#city-selectized").bind("change paste keyup", function() {

        if($('#city-selectized').val()){
            $('.starcity').hide();
        }else{
            $('.starcity').show();
        }
    });

    $("#district-selectized").bind("change paste keyup", function() {

        if($('#district-selectized').val()){
            $('.stardist').hide();
        }else{
            $('.stardist').show();
        }
    });

    $("#subdistrict-selectized").bind("change paste keyup", function() {

        if($('#subdistrict-selectized').val()){
            $('.starsubdist').hide();
        }else{
            $('.starsubdist').show();
        }
    });

    $("#hobby-selectized").bind("change paste keyup", function() {

        if($('#hobby-selectized').val()){
            $('.starhobby').hide();
        }else{
            $('.starhobby').show();
        }
    });

    // $("#club_location-selectized").bind("change paste keyup", function() {

    //     if($('#club_location-selectized').val()){
    //         $('.starclubl').hide();
    //     }else{
    //         $('.starclubl').show();
    //     }
    // });

    // $("#club_choice-selectized").bind("change paste keyup", function() {

    //     if($('#club_choice-selectized').val()){
    //         $('.starclubc').hide();
    //     }else{
    //         $('.starclubc').show();
    //     }
    // });

});

</script>

<script>

    // $('#imageProfile').hide();
    // $('#imageKTP').hide();

    // var loadFile = function(event) {
    //   var reader = new FileReader();
    //   reader.onload = function() {
        
    //     // $('#fotoProfile-error').text("");
    //     // $('#imageProfile').attr('src', reader.result);
    //     // // $('#imageProfile').show();

    //     // }
    //     // reader.readAsDataURL(event.target.files[0]);
    // };

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
            file = new File([blobData], "kta-P_pfp.webp",{type:"image/webp", lastModified:new Date().getTime()});
            container.items.add(file);
            let fileInputElement = document.getElementById('fotoProfile');
            fileInputElement.files = container.files;
            console.log(fileInputElement.files);
            // $("#fotoProfile").val(file);
            // $('#fotoProfile-error').text("");
            $('#imageProfile').attr('src', imgDataURL);
            // checkValid();
        }
    };

    var loadFile2 = function(event) {
      var reader = new FileReader();
      reader.onload = function() {
        
        $('#fotoEktp-error').text("");
        $('#imageKTP').attr('src', reader.result);
        // $('#imageKTP').show();

        }
        reader.readAsDataURL(event.target.files[0]);
    };

    var $input_address = $('#address')
    $input_address.keyup(function(e) {
        var max = 60;
        if ($input_address.val().length > max) {
            $input_address.val($input_address.val().substr(0, max));
        }
    });

    var $input = $('#ektp')
    $input.keyup(function(e) {
        var max = 18;
        if ($input.val().length > max) {
            $input.val($input.val().substr(0, max));
        }
    });

</script>

<script>

    $(".fullname").show();
    $(".starmail").show();
    $(".starbp").show();
    $(".bdstar").show();
    $(".starbt").show();
    $(".starnation").show();
    $(".starhobby").show();
    $(".staraddhobby").show();
    $(".staraddress").show();
    $(".starrt").show();
    $(".starrw").show();
    $(".starpost").show();
    $(".starprovince").show();
    $(".starcity").show();
    $(".stardist").show();
    $(".starsubdist").show();
    // $(".starclubl").show();
    // $(".starclubc").show();
    $(".starppimg").show();
    $(".starnoktp").show();
    $(".starktp").show();
    
    $("#name").bind("change paste keyup", function() {
        var namevalue = $(this).val();

        if (namevalue) {
            $(".fullname").hide();
        }

        else {
            $(".fullname").show();
        }
    });

    $("#email").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".starmail").hide();
        }

        else {
            $(".starmail").show();
        }
    });

    $("#birthplace").change(function() {
        var bpvalue = $(this).val();

        if (bpvalue) {
            $(".starbp").hide();
        }

        else {
            $(".starbp").show();
        }
    });

    $("#date_birth").bind("change paste keyup", function() {
        var bdvalue = $(this).val();

        if (bdvalue) {
            $(".bdstar").hide();
        }

        else {
            $(".bdstar").show();
        }

    });

    $("#bloodtype").change(function() {
        var btvalue = $(this).val();

        if (btvalue) {
            $(".starbt").hide();
        }

        else {
            $(".starbt").show();
        }
    });

    $("#nationality").change(function() {
        var nationalvalue = $(this).val();

        if (nationalvalue) {
            $(".starnation").hide();
        }
        
        else {
            $(".starnation").show();
        }
    });

    $("#hobby").change(function() {
        var hobbyvalue = $(this).val();

        if (hobbyvalue) {
            $(".starhobby").hide();
        }

        else {
            $(".starhobby").show();
        }
    });

    $("#hobby_desc").bind("change paste keyup", function() {
        var addhobbyvalue = $(this).val();

        if (addhobbyvalue) {
            $(".staraddhobby").hide();
        }

        else {
            $(".staraddhobby").show();
        }

    });

    $("#address").bind("change paste keyup", function() {
        var addressvalue = $(this).val();

        if (addressvalue) {
            $(".staraddress").hide();
        }

        else {
            $(".staraddress").show();
        }

    });

    $("#rt").bind("change paste keyup", function() {
        var rtvalue = $(this).val();

        if (rtvalue) {
            $(".starrt").hide();
        }

        else {
            $(".starrt").show();
        }

    });



    $("#rw").bind("change paste keyup", function() {
        var rwvalue = $(this).val();

        if (rwvalue) {
            $(".starrw").hide();
        }

        else {
            $(".starrw").show();
        }

    });

    $("#postcode").change(function() {
        var pcvalue = $(this).val();

        if (pcvalue) {
            $(".starpost").hide();
        }

        else {
            $(".starpost").show();
        }
    });

    $("#province").change(function() {
        var provincevalue = $(this).val();

        if (provincevalue) {
            $(".starprovince").hide();
        }

        else {
            $(".starprovince").show();
        }
    });

    $("#city").change(function() {
        var cityvalue = $(this).val();

        if (cityvalue) {
            $(".starcity").hide();
        }

        else {
            $(".starcity").show();
        }
    });

    $("#district").change(function() {
        var distvalue = $(this).val();

        if (distvalue) {
            $(".stardist").hide();
        }

        else {
            $(".stardist").show();
        }
    });

    $("#subdistrict").change(function() {
        var subdistvalue = $(this).val();

        if (subdistvalue) {
            $(".starsubdist").hide();
        }

        else {
            $(".starsubdist").show();
        }
    });

    // $("#club_location").change(function() {
    //     var clublvalue = $(this).val();

    //     if (clublvalue) {
    //         $(".starclubl").hide();
    //     }

    //     else {
    //         $(".starclubl").show();
    //     }
    // });

    // $("#club_choice").change(function() {
    //     var clubcvalue = $(this).val();

    //     if (clubcvalue) {
    //         $(".starclubc").hide();
    //     }

    //     else {
    //         $(".starclubc").show();
    //     }
    // });

    $("#fotoProfile").change(function() {
        var ppimg = $(this).val();

        if (ppimg) {
            $(".starppimg").hide();
        }

        else {
            $(".starppimg").show();
        }
    });

    $("#ektp").bind("change paste keyup", function() {
        var ppimg = $(this).val();

        if (ppimg) {
            $(".starnoktp").hide();
        }

        else {
            $(".starnoktp").show();
        }
    });

    $("#fotoEktp").change(function() {
        var ppktp = $(this).val();

        if (ppktp) {
            $(".starktp").hide();
        }

        else {
            $(".starktp").show();
        }
    });
    
</script>

<script>

// FOR SELECTIZED VALIDATION

$("#birthplace").change(function() {
    $('#birthplace-error').text("");
});

$("#bloodtype").change(function() {
    $('#bloodtype-error').text("");
});

$("#nationality").change(function() {
    $('#nationality-error').text("");
});

$("#hobby").change(function() {
    $('#hobby-error').text("");
});

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

$("#dropdownMenuSelectMethod").change(function() {
    $('#payment-error').text("");
});

// $("#club_location").change(function() {
//     $('#clublocation-error').text("");
// });

// $("#club_choice").change(function() {
//     $('#clubchoice-error').text("");
// });

function selectizeValid(){

    storeValues();

    var birthplace = $('#birthplace').val();
    var bloodtype = $('#bloodtype').val();
    var nationality = $('#nationality').val();
    var hobby = $('#hobby').val();
    var postcode = $('#postcode').val();
    var province = $('#province').val();
    var city = $('#city').val();
    var district = $('#district').val();
    var subdistrict = $('#subdistrict').val();

    // var clublocation = $('#club_location').val();
    // var clubchoice = $('#club_choice').val();

    var fotoProfile = $('#fotoProfile').val();
    var fotoEktp = $('#fotoEktp').val();

    var payment = $("#dropdownMenuSelectMethod").val();

    if(!birthplace){
        $('#birthplace-error').text("This field is required.");
    }else{
        $('#birthplace-error').text("");
    }

    if(!bloodtype){
        $('#bloodtype-error').text("This field is required.");
    }else{
        $('#bloodtype-error').text("");
    }

    if(!nationality){
        $('#nationality-error').text("This field is required.");
    }else{
        $('#nationality-error').text("");
    }

    if(!hobby){
        $('#hobby-error').text("This field is required.");
    }else{
        $('#hobby-error').text("");
    }

    if(!postcode){
        $('#postcode-error').text("This field is required.");
    }else{
        $('#postcode-error').text("");
    }

    if(!province){
        $('#province-error').text("This field is required.");
    }else{
        $('#province-error').text("");
    }

    if(!city){
        $('#city-error').text("This field is required.");
    }else{
        $('#city-error').text("");
    }

    if(!district){
        $('#district-error').text("This field is required.");
    }else{
        $('#district-error').text("");
    }

    if(!subdistrict){
        $('#subdistrict-error').text("This field is required.");
    }else{
        $('#subdistrict-error').text("");
    }

    if(!fotoEktp){
        $('#fotoEktp-error').text("This field is required.");
    }else{
        $('#fotoEktp-error').text("");
    }

    if(!fotoProfile){
        $('#fotoProfile-error').text("This field is required.");
    }else{
        $('#fotoProfile-error').text("");
    }

    if (!payment) {
        $("#payment-error").text("This field is required.");
    }
    else {
        $("#payment-error").text("");
    }

    // if(!clubchoice){
    //     $('#clubchoice-error').text("This field is required.");
    // }else{
    //     $('#clubchoice-error').text("");
    // }

    // if(!clublocation){
    //     $('#clublocation-error').text("This field is required.");
    // }else{
    //     $('#clublocation-error').text("");
    // }
}

// CHECK EMAIL ALREADY TAKEN

$("#email").bind("change paste keyup", function() {
    var email = $(this).val();

    // // console.log(name);

    var formData = new FormData();

    formData.append('email', email);

    if (email != ""){

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // // console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText;

                if (result == "Ada"){
                    // console.log("Username Ada");
                    $('#username-not-exist').text("");
                    $('#username-exist').text("That email is taken, try another.");

                    is_takken = 0;
                }else if(result == "Tidak ada"){
                    // console.log("Username Tidak Ada");
                    // $('#username-not-exist').text("That email is available.");
                    $('#username-exist').text("");

                    is_takken = 1;
                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta_email");
        xmlHttp.send(formData);

    }else{
        $('#username-not-exist').text("");
        $('#username-exist').text("");
    }
});

// CHECK KTP

$("#ektp").bind("change paste keyup", function() {
    var ektp = $(this).val();

    // // console.log(name);

    var formData = new FormData();

    formData.append('ektp', ektp);

    if (ektp != ""){

        if(ektp.length < 16){
            $('#ktp-16').text("KTP Number must be 16 digits.");
            $('#ktp-exist').text("");
        } else {
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // // console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText;

                if (result == 1){
                    // console.log("KTP Ada");
                    $('#ktp-not-exist').text("");
                    $('#ktp-16').text("");
                    $('#ktp-exist').text("That KTP Number is taken, try another.");

                    is_takken_ktp = 0;
                }else if(result == 0){
                    // console.log("KTP Tidak Ada");
                    $('#ktp-not-exist').text("That KTP Number is available.");
                    $('#ktp-16').text("");
                    $('#ktp-exist').text("");

                    is_takken_ktp = 1;
                }

            }
        }
        xmlHttp.open("post", "../logics/check_ktp");
        xmlHttp.send(formData);
        }
    }else{
        $('#ktp-not-exist').text("");
        $('#ktp-exist').text("");
    }
});

</script>

<script>

    $("#page-roadside-assistance").hide();

    function functionERA() {

        checkIsDB = "<?= mysqli_num_rows($roadsideAssistance) ?>";

        var checkInsurance = document.getElementById("checkERA");
        
        if (checkInsurance.checked == true) {

            $(".main-form").hide();
            $("#page-roadside-assistance").show();

            if (checkIsDB.length == 0) {
                $("#contact").show();
                $("#vehicle").hide();
                $("#submit-orange").show();
                $("#buy-add-on").hide();
            }
            else {

                $("#contact").hide();
                $("#vehicle").show();
                $("#submit-orange").hide();
                $("#buy-add-on").show();

            }

        }

        else {

            $(".main-form").hide();
            $("#page-roadside-assistance").show();

            if (cars.length == 0) {
                $("#contact").show();
                $("#vehicle").hide();
                $("#submit-orange").show();
                $("#buy-add-on").hide();
            }
            else {
                $("#contact").hide();
                $("#vehicle").show();
                $("#submit-orange").hide();
                $("#buy-add-on").show();
            }

        }

    }

</script>

<script>
    if (!window.Android) {
        $("#photo-method").hide();
        $("#id-photo-method").hide();
    }
    else {
        $("#photo-method").show();
        $("#id-photo-method").show();
    }
</script>

<script>

    $("#contact").show();
    $("#vehicle").hide();
    $("#price").hide();

    $(document).ready(function(e) {
        
        $('#vehicle-island').selectize();

        $("#vehicle-island-selectized").bind("change paste keyup", function() {

            if ($('#vehicle-island-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $('#vehicle-brand').selectize();

        $("#vehicle-brand-selectized").bind("change paste keyup", function() {

            if ($('#vehicle-brand-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $('#vehicle-type').selectize();

        $("#vehicle-type-selectized").bind("change paste keyup", function() {

            if ($('#vehicle-type-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $("#vehicle-brand").bind("change", function() {

            var $select = $(document.getElementById('vehicle-type'));
            var selectize = $select[0].selectize;

            // CLEAR CITY
            selectize.clear(); 
            selectize.clearOptions();

            var vehicleBrand = $(this).val();
            localStorage.setItem("vehicle-brand", vehicleBrand);

            var formData = new FormData();

            formData.append('vehicle-brand', vehicleBrand);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                        
                        Object.keys(obj).forEach(function (item){

                            // console.log(obj[item]['POSTAL_CODE']);
                            
                            selectize.addOption({value: obj[item]['ID'], text: obj[item]['VEHICLE_TYPE']});

                        });
                }
            }
            xmlHttp.open("post", "../logics/get_vehicle_type");
            xmlHttp.send(formData);
            
        });

    });

</script>

<script>

    $("#revert-button").hide();

    var file;

    var loadFile3 = function(event) {

        var reader = new FileReader();
        reader.onload = function() {

        console.log(reader.result);
        $("#photo-error").hide();
        $("#choose-button").hide();
        $("#revert-button").show();
        file = reader.result;

        event.target.value = '';

        }
        reader.readAsDataURL(event.target.files[0]);

    }

</script>

<script>

    $("#insurance-payment").hide();
    $("#summary-payment").hide();

    $('#vehicle-photo-hidden').change(function (e) {
        e.preventDefault();
        $('#photo-name').val(this.files[0].name)
    });

    function revertButton() {
        $("#photo-name").val("");
        $("#choose-button").show();
        $("#revert-button").hide();
    }

    $("#buy-add-on").hide();

    function purchaseCategory() {
        $("#modal-vehicle").modal('show');
        $("html").attr("style", "overflow-y: hidden");
    }

    $('#modal-vehicle').on('hidden.bs.modal', function (e) {
        $("html").attr("style", "");
    });

    var cars = [];
    var cars_temp;
    var number = 0;

    $("#island-error").hide();
    $("#photo-error").hide();
    $("#brand-error").hide();
    $("#type-error").hide();
    $("#year-error").hide();
    $("#license-error").hide();

    function submitButton() {

        var category = document.querySelector('input[name="vehicle_category"]:checked').value;
        var island = $('#vehicle-island').val();
        var photo = file;
        var brand = $('#vehicle-brand').val();
        var type = $('#vehicle-type').val();
        var year = $('#vehicle-year').val();
        var license = $('#vehicle-license').val();
        var photo_name = $("#photo-name").val();
        var brand_name = $('#vehicle-brand').text();
        var type_name = $('#vehicle-type').text();

        if (!island) {
            $("#island-error").show();
        }

        if (!photo) {
            $("#photo-error").show();
        }
        else {
            $("#photo-error").hide();
        }

        if (!photo_name) {
            $("#photo-error").show();
        }
        else {
            $("#photo-error").hide();
        }

        if (!brand) {
            $("#brand-error").show();
        }

        if (!type) {
            $("#type-error").show();
        }

        if (!year) {
            $("#year-error").show();
        }

        if (!license) {
            $("#license-error").show();
        }

        $("#vehicle-island").change(function() {
            $("#island-error").hide();
        });

        $("#vehicle-brand").change(function() {
            $("#brand-error").hide();
        });

        $("#vehicle-type").change(function() {
            $("#type-error").hide();
        });

        $("#vehicle-year").bind("change paste keyup", function() {
            $("#year-error").hide();
        });

        $("#vehicle-license").bind("change paste keyup", function() {
            $("#license-error").hide();
        });

        var $select = $(document.getElementById('vehicle-island'));
        var selectize = $select[0].selectize;
        selectize.clear(); 

        var $select = $(document.getElementById('vehicle-brand'));
        var selectize = $select[0].selectize;
        selectize.clear(); 

        var $select = $(document.getElementById('vehicle-type'));
        var selectize = $select[0].selectize;
        selectize.clear(); 
        selectize.clearOptions();

        $('#vehicle-photo').val("");
        $('#photo-name').val("");
        $('#vehicle-year').val("");
        $('#vehicle-license').val("");

        var converted_link = dataURLtoFile(photo, ".webp");

        if (island && photo && brand && type && year && license) {

            $("#vehicle").show();
            $("#contact").hide();

            var html = `<div class="row mt-3 single-car" id="vehicle-`+number+`">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/accept.svg" alt="" style="width: 23px; height: 23px">
                            </div>
                            <div class="col-8">
                                <p style="font-size: 13px"><span id="vehicle-name">`+brand_name+`</span>, <span id="vehicle-series">`+type_name+`</span>, <span id="vehicle-production">`+year+`</span></p>
                            </div>
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/Back-(White).png" alt="" style="width: 23px; height: 23px; transform: rotate(180deg)">
                            </div>
                        </div>`;

            $('#vehicle').append(html);

            $('#add-vehicle').insertAfter($('#vehicle-'+number));
            number = number + 1;

            if (cars.length > 0){

                cars_temp = [

                    {
                        "category" : category,
                        "island" : island,
                        "photo" : converted_link,
                        "brand" : brand,
                        "type" : type,
                        "year" : year,
                        "license" : license 
                    }

                ];

                cars = cars.concat(cars_temp);

            }else{

                cars = [

                    {
                        "category" : category,
                        "island" : island,
                        "photo" : converted_link,
                        "brand" : brand,
                        "type" : type,
                        "year" : year,
                        "license" : license 
                    }

                ];

            }

            console.log(cars);

            totalPrice = (cars.length)*30000;
            summaryPayment = (parseInt(price)*1000)+totalPrice;

            const format = totalPrice.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            const rupiah = 'Rp. ' + convert.join('.').split('').reverse().join('');

            const summaryFormat = summaryPayment.toString().split('').reverse().join('');
            const summaryConvert = summaryFormat.match(/\d{1,3}/g);
            const summaryRupiah = 'Rp. ' + summaryConvert.join('.').split('').reverse().join('');

            $("#total-price").text(rupiah);
            $("#total-payment-price").text(rupiah);
            $("#insurance-payment-price").text(rupiah);
            $("#summary-payment-price").text(summaryRupiah);
            $("#insurance-payment-price").show();
            $("#summary-payment-price").show();

        }
        else {

            $("#vehicle").hide();
            $("#contact").show();

        }

        if (cars.length > 0) {
            $("#price").show();
            $("#buy-add-on").show();
            $("#submit-orange").hide();

            $("#checkERA").attr("checked", true);

        }

        if (island && photo && brand && type && year && license) {
            $("#modal-vehicle").modal('hide');
        }

    }

    $("#add-vehicle").on('click', function() {
        $("#modal-vehicle").modal('show');
    });

    // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
    $("#imi-checkout").hide();
    // $("#imi-roadside-assistance").show();

    function buyCategory() {

        $(".main-form").show();
        $("#page-roadside-assistance").hide();
        $("#insurance-payment").show();
        $("#summary-payment").show();

    }

    function backRa() {

        if (cars.length > 0) {

            vehicleWarning();

            $("#checkERA").attr("checked", true);
            $("#insurance-payment").hide();
            $("#insurance-payment-price").hide();
            $("#summary-payment").hide();
            $("#summary-payment-price").hide();
            $("#price").hide();
            
        }
        else {

            $(".main-form").show();
            $("#page-roadside-assistance").hide();
            $("#checkERA").attr("checked", false);
            $("#insurance-payment").hide();
            $("#insurance-payment-price").hide();
            $("#summary-payment").hide();
            $("#summary-payment-price").hide();
            $("#price").hide();
            
        }

    }

    var brand_name = $('#vehicle-brand').text();
    var type_name = $('#vehicle-type').text();

    // SCRIPT CONVERT BASE64 TO OBJECT

    function dataURLtoFile(dataurl, filename){
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);

        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], filename, {type:mime});
    }

</script>

<script>

    $("#other-method").hide();
    $("#add-on-container").hide();

</script>

<script>

    function vehicleWarning() {

        Swal.fire({
        title: 'Are you sure you want to leave this site?',
        text: "Progress you made may not be saved.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                
                cars.length = 0;
                $(".single-car").html("");
                $(".main-form").show();
                $("#page-roadside-assistance").hide();

            }
        })

    }

</script>

<!-- FOR COOKIE AUTOFILL -->

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
    setCookie("ektpKTAPRO", $('#ektp').val());
    setCookie("nameKTAPRO", $('#name').val());
    setCookie("emailKTAPRO", $('#email').val());
    setCookie("birthplaceKTAPRO", $('#birthplace').val());
    setCookie("date_birthKTAPRO", $('#date_birth').val());
    setCookie("gender_radioKTAPRO", document.querySelector('input[name="gender_radio"]:checked').value);
    setCookie("bloodtypeKTAPRO", $('#bloodtype').val());
    setCookie("nationalityKTAPRO", $('#nationality').val());
    setCookie("hobbyKTAPRO", $('#hobby').val());
    setCookie("hobby_descKTAPRO", $('#hobby_desc').val());

    setCookie("addressKTAPRO", $('#address').val());
    setCookie("rtKTAPRO", $('#rt').val());
    setCookie("rwKTAPRO", $('#rw').val());
    setCookie("postcodeKTAPRO", $('#postcode').val());
    setCookie("provinceKTAPRO", $('#province').val());
    setCookie("cityKTAPRO", $('#city').val());
    setCookie("districtKTAPRO", $('#district').val());
    setCookie("subdistrictKTAPRO", $('#subdistrict').val());

    setCookie("paymentKTAPRO", $('#dropdownMenuSelectMethod').val());
    return true;
  }

  function getDataCookie(){

    if (name = getCookie("nameKTAPRO")){
        $('#name').val(decodeURIComponent(name));
        $('.fullname').hide();
    }

    if (email = getCookie("emailKTAPRO")){
        $('#email').val(email);
        $('.starmail').hide();

        is_takken = 1;
    }

    if (ektp = getCookie("ektpKTAPRO")){
        $('#ektp').val(ektp);
        $('.starnoktp').hide();

        is_takken_ktp = 1;
    }

    if (birthplace = getCookie("birthplaceKTAPRO")){
        var $select = $(document.getElementById('birthplace'));
        var selectize = $select[0].selectize;
        selectize.setValue(birthplace);
    }

    if (date_birth = getCookie("date_birthKTAPRO")){
        $('#date_birth').val(date_birth);
        $('.bdstar').hide();
    }

    if (bloodtype = getCookie("bloodtypeKTAPRO")){
        var $select = $(document.getElementById('bloodtype'));
        var selectize = $select[0].selectize;
        selectize.setValue(bloodtype);
    }

    if (nationality = getCookie("nationalityKTAPRO")){
        var $select = $(document.getElementById('nationality'));
        var selectize = $select[0].selectize;
        selectize.setValue(nationality);
    }

    if (hobby = getCookie("hobbyKTAPRO")){
        var $select = $(document.getElementById('hobby'));
        var selectize = $select[0].selectize;
        selectize.setValue(hobby);

        if (hobby=='6'){
            $(".add-hobby").show();
            $('.staraddhobby').hide();
            $('#hobby_desc').val(decodeURIComponent(getCookie("hobby_descKTAPRO")));
        }
    }

    if (gender_radio = getCookie("gender_radioKTAPRO")){
        if (gender_radio==1){
            $('#genderMale').prop('checked', true);
        }else{
            $('#genderFemale').prop('checked', true);
        }
    }

    if (address = getCookie("addressKTAPRO")){
        $('#address').val(decodeURIComponent(address));
        $('.staraddress').hide();
    }

    if (rt = getCookie("rtKTAPRO")){
        $('#rt').val(rt);
        $('.starrt').hide();
    }

    if (rw = getCookie("rwKTAPRO")){
        $('#rw').val(rw);
        $('.starrw').hide();
    }

    if (postcode = getCookie("postcodeKTAPRO")){
        var $select = $(document.getElementById('postcode'));
        var selectize = $select[0].selectize;
        selectize.setValue(postcode);

        $('.starpost').hide();
    }

    if (province = getCookie("provinceKTAPRO")){
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

        if (city = getCookie("cityKTAPRO")){
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
        if (district = getCookie("districtKTAPRO")){
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

        if (subdistrict = getCookie("subdistrictKTAPRO")){
            var $select = $(document.getElementById('subdistrict'));
            var selectize = $select[0].selectize;
            selectize.setValue(subdistrict);

            $('.starsubdist').hide();
        }

    }
   

    if (payment = getCookie("paymentKTAPRO")){
        $('#dropdownMenuSelectMethod').val(payment);
    }

}

function deleteAllCookie(){
    deleteCookie("ektpKTAPRO");
    deleteCookie("nameKTAPRO");
    deleteCookie("emailKTAPRO");
    deleteCookie("birthplaceKTAPRO");
    deleteCookie("date_birthKTAPRO");
    deleteCookie("gender_radioKTAPRO");
    deleteCookie("bloodtypeKTAPRO");
    deleteCookie("nationalityKTAPRO");
    deleteCookie("hobbyKTAPRO");
    deleteCookie("hobby_descKTAPRO");

    deleteCookie("addressKTAPRO");
    deleteCookie("rtKTAPRO");
    deleteCookie("rwKTAPRO");
    deleteCookie("postcodeKTAPRO");
    deleteCookie("provinceKTAPRO");
    deleteCookie("cityKTAPRO");
    deleteCookie("districtKTAPRO");
    deleteCookie("subdistrictKTAPRO");

    deleteCookie("paymentKTAPRO");
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