<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];
$uid = "";
$dbconn = paliolite();

$sql = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

$query = $dbconn->prepare($sql);
$query->execute();
$province = $query->get_result();
$query->close();
$sqlData = "SELECT CLUB_UID
  FROM TKT
  WHERE F_PIN = '$f_pin'";
$query = $dbconn->prepare($sqlData);
$query->execute();
$d = $query->get_result()->fetch_assoc();
if (!is_null($d)) {
    $uid = $d['CLUB_UID'];
}
$query->close();
$ver = time();

// PRICE

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '4'";

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Gaspol</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <script src="../assets/js/xendit.min.js"></script>

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
    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

            <form method="POST" style="padding: 0px" class="main-form" id="tkt-form" action="/gaspol_web/logics/register_new_tkt" enctype="multipart/form-data">
                    
                <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid; background-image: url(../assets/img/lbackground_2.png)">
                    <div class="col-1 d-flex justify-content-start">
                        <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                    </div>
                    <div class="col-11 d-flex justify-content-center">
                        <h2 style="margin-bottom: 0px">GasPol Club Registration</h2>
                    </div>
                </div>

                <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

                <div class="container pt-4">
                    <h2 class="text-center"><span style="font-size: 22px">Keanggotaan <span style="color: #f66701">Gaspol Club</span></span><br><span style="font-size: 16px; color: #626262">(Tanda Klub Terdaftar)</span></h2>
                </div>

                <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>

                <div class="form-group-2 mt-3">
                    <div class="container">
                    
                    <div data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseInformation" aria-expanded="false" aria-controls="collapseInformation">
                        <b>Club Information<b>
                        <img id="collapse-img-1" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                    </div>

                    <div class="collapse show mt-2" id="collapseInformation">
                        <div class="row mt-3 mb-3">
                            <b class="mb-2">Club Photo</b>
                            <div class="col-5 d-flex justify-content-center">
                                <img id="club_image" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; " src="../assets/img/tab5/create-post-black.png">
                            </div>

                            <!-- <span class="profileimagestar text-danger" style="position: absolute; margin-top: 27px; margin-left: 249px; z-index: 999">*</span> -->

                            <div class="col-7">
                                <div class="row mt-3" style="margin-bottom: 5px">
                                    <span class="profileimagestar text-danger" style="position: absolute; margin-top: 8px; margin-left: 140px; z-index: 999">*</span>
                                    <!-- <div class="col-6">
                                        <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                                        <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                                        <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                                    </div> -->
                                </div>
                                <label for="fotoProfile" id="profileLabelBtn" style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px; margin-left: 10px" class="btn">Choose File</label>
                                <br><p id="profileFileName" style="display: inline; margin-left: 14px">No file chosen</p>
                                <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)"/>
                                <br><span id="fotoProfile-error" class="error" style="color: red; margin-left: 14px"></span>
                            </div>
                        </div>

                        <div class="row gx-0">
                            <div class="col-12">
                                <input type="text" name="name" id="name" placeholder="Club Name" required />
                            </div>
                            <span class="clubnamestar text-danger" style="position: absolute; margin-top: 8px; margin-left: 83px; width: 10px">*</span>
                        </div>

                        <div class="mt-3 mb-3">
                        <b>Club Category</b><br>

                            <span class="clubcategorystar text-danger" style="position: absolute; margin-top: -26px; margin-left: 108px">*</span>

                            <!-- <input type="checkbox" class="check mt-2" id="cat1" name="cat1" value="1">
                            <label for="cat1">&nbsp;&nbsp;&nbsp;Olahraga</label><br>
                            <input type="checkbox" class="check" id="cat2" name="cat2" value="2">
                            <label for="cat2">&nbsp;&nbsp;&nbsp;Hobi</label><br>
                            <input type="checkbox" class="check" id="cat3" name="cat3" value="3">
                            <label for="cat3">&nbsp;&nbsp;&nbsp;Penyelenggara</label><br> -->

                            <?php foreach($category as $c): ?>

                                <input type="checkbox" class="check mt-2" id="cat<?= $c['ID'] ?>" name="cat<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" onchange="changeCategory('<?= $c['ID'] ?>')">
                                <label for="cat<?= $c['ID'] ?>">&nbsp;&nbsp;&nbsp;<?= $c['CATEGORY'] ?></label><br>

                            <?php endforeach; ?>
                            
                            <span id="category-error" class="error" style="color: red"></span>
                        </div>

                        <input type="hidden" id="category" name="category">

                        <div class="row gx-0">
                            <div class="col-12">
                                <input type="text" name="club_link" id="club_link" placeholder="Club External Link" required />
                            </div>
                            <span class="linkstar text-danger" style="position: absolute; margin-top: 8px; margin-left: 123px; width: 8px">*</span>
                        </div>

                        <div class="row gx-0">
                            <div class="col-12">
                                <input type="text" name="club_desc" id="club_desc" placeholder="Club Description" required />
                            </div>
                            <span class="descstar text-danger" style="position: absolute; margin-top: 8px; margin-left: 118px; width: 8px">*</span>
                        </div>
                    </div>
                </div>


                <div class="container mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                    <b>Address<b>
                    <img id="collapse-img-2" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="container collapse show mt-2" id="collapseAddress">
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="address" id="address" placeholder="Full Address" required />
                        </div>
                        <span class="addressstar text-danger" style="position: absolute; margin-top: 8px; margin-left: 86px; width: 10px">*</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="number" name="rt" id="rt" placeholder="RT" onKeyPress="if (this.value.length == 3) return false;" required />
                        </div>
                        <span class="rtstar text-danger" style="position: absolute; margin-top: 8px; margin-left: 22px; width: 10px">*</span>

                        <div class="col-6">
                            <input type="number" name="rw" id="rw" placeholder="RW" onKeyPress="if (this.value.length == 3) return false;" required />                            
                        </div>
                        <span class="rwstar text-danger" style="position: absolute; margin-top: 8px; margin-left: 53%; width: 10px">*</span>
                    </div>

                    <select class="mt-3 mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px">
                        <option value="" selected>Postcode</option>

                        <!-- <?php foreach($postal as $p): ?>
                            <option value="<?= $p['POSTAL_ID'] ?>"><?= $p['POSTAL_CODE'] ?></option>
                        <?php endforeach; ?> -->
                    
                    </select>

                    <span class="postcodestar text-danger" style="position: absolute; margin-top: -48px; margin-left: 79px; z-index: 999">*</span>
                    <span id="postcode-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                        <option value="" selected>Province</option>

                        <?php foreach($province as $p): ?>
                            <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span class="provincestar text-danger" style="position: absolute; margin-top: -48px; margin-left: 77px; z-index: 999">*</span>
                    <span id="province-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="city" name="city" aria-label="" style="font-size: 16px">
                        <option value="" selected>City</option>
                    </select>

                    <span class="citystar text-danger" style="position: absolute; margin-top: -48px; margin-left: 45px; z-index: 999">*</span>
                    <span id="city-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="district" name="district" aria-label="" style="font-size: 16px">
                        <option value="" selected>District</option>
                    </select>

                    <span class="districtstar text-danger" style="position: absolute; margin-top: -47px; margin-left: 66px; z-index: 999">*</span>
                    <span id="district-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px">
                        <option value="" selected>District Word</option>
                    </select>

                    <span class="subdistrictstar text-danger" style="position: absolute; margin-top: -48px; margin-left: 105px; z-index: 999">*</span>
                    <span id="subdistrict-error" class="error" style="color: red"></span>
                        
                </div>
                        
                    <!-- <div class="form-group-2">
                        <p><b>Biaya Pembayaran : Rp. 200,000<b></p>
                    </div> -->
                    <div class="form-check mb-4 container" style="margin-top: 50px">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                        <label class="form-check-label" for="flexCheckChecked">
                            I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!
                        </label>
                    </div>
                    <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5" class="mt-4"></div>
                    <div class="container">
                        <div class="form-group-2 mt-4 mb-4">
                            <div class="row">
                                <div class="col-6" style="color: #626262">
                                    Registration Fee
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <b>Rp. <?= number_format(0, 0, '', '.') ?></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6" style="color: #626262">
                                    Administration Fee
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <b>Rp. <?= number_format(0, 0, '', '.') ?></b>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6" style="color: #626262">
                                    Total Payment
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <b style="font-size: 20px">Rp. <?= number_format(0, 0, '', '.') ?></b>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
                <div class="form-submit d-flex justify-content-center pb-5" style="padding-top: 20px; background-image: url(../assets/img/lbackground_2.png)">
                    <input type="submit" style="width: 40%; font-size: 16px; padding: 10px; background-color: #f66701; color: #FFFFFF" name="submit" id="submit" class="submit" value="SUBMIT" onclick="selectizeValid()"/>
                </div>
                <!-- <div style="width: 100%; height: 100px; background-color: #fff"></div> -->
            </div>
        </form>
    </div>

        <!-- The Modal -->
        <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0" id="modalProgress">
                    <p>Upload in progress...</p>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">TKT Gaspol Club Registration Success!</h1>
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
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-payment-body">
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

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var UID = "<?php echo $uid; ?>";
        var REG_TYPE = 7;
        localStorage.setItem('grand-total', '10000');
    </script>
    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-tkt-gaspol.js?v=<?php echo $ver; ?>"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>

$(document).ready(function(e) {

$('#birthplace').selectize();
$('#nationality').selectize();
$('#bloodtype').selectize();
$('#hobby').selectize();
$('#postcode').selectize();

$('#province').selectize();
$('#city').selectize();
$('#district').selectize();
$('#subdistrict').selectize();

$("#postcode-selectized").bind("change paste keyup", function() {
   
    var $select = $(document.getElementById('postcode'));
    var selectize = $select[0].selectize;

    var postcode = $(this).val();
    var formData = new FormData();

    formData.append('postcode', postcode);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            // console.log(xmlHttp.responseText);

            var obj = JSON.parse(xmlHttp.responseText);
            
            Object.keys(obj).forEach(function (item){

                // console.log(obj[item]['POSTAL_CODE']);                    
                selectize.addOption({value: obj[item]['POSTAL_ID'], text: obj[item]['POSTAL_CODE']});

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

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

$("#postcode").bind("change", function() {

    var postcode = $(this).val();
    var formData = new FormData();

    formData.append('postcode', postcode);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);

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
            selectize.addOption({value: province_name, text: province_name});
            selectize.setValue(province_name);

            var $select2 = $(document.getElementById('city'));
            var selectize2 = $select2[0].selectize;
            selectize2.addOption({value: city_name, text: city_name});
            selectize2.setValue(city_name);

            var $select3 = $(document.getElementById('district'));
            var selectize3 = $select3[0].selectize;
            selectize3.addOption({value: district_name, text: district_name});
            selectize3.setValue(district_name);

            var $select4 = $(document.getElementById('subdistrict'));
            var selectize4 = $select4[0].selectize;
            selectize4.addOption({value: subdis_name, text: subdis_name});
            selectize4.setValue(subdis_name);

            // console.log(city_id);
            // console.log(subdis_id);
            // console.log(district_id);
            // console.log(province_id);
            // console.log(city_name);
            // console.log(subdis_name);
            // console.log(district_name);
            // console.log(province_name);

            // $('#city').val(capitalize(city));
            // $('#province').val(capitalize(province));
            // $('#district').val(capitalize(district));
            // $('#district_word').val(capitalize(subdis));

        }
    }
    xmlHttp.open("post", "../logics/get_full_address");
    xmlHttp.send(formData);
});

// FOR ARROW COLLAPSE

$('#collapseInformation').on('shown.bs.collapse', function () {
    $('#collapse-img-1').attr('src','../assets/img/arrow-up.png');
});

$('#collapseInformation').on('hidden.bs.collapse', function () {
    $('#collapse-img-1').attr('src','../assets/img/arrow-down.png');
});

$('#collapseAddress').on('shown.bs.collapse', function () {
    $('#collapse-img-2').attr('src','../assets/img/arrow-up.png');
});

$('#collapseAddress').on('hidden.bs.collapse', function () {
    $('#collapse-img-2').attr('src','../assets/img/arrow-down.png');
});

$('#collapseIdentification').on('shown.bs.collapse', function () {
    $('#collapse-img-3').attr('src','../assets/img/arrow-up.png');
});

$('#collapseIdentification').on('hidden.bs.collapse', function () {
    $('#collapse-img-3').attr('src','../assets/img/arrow-down.png');
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
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);

            var obj = JSON.parse(xmlHttp.responseText);
                
                Object.keys(obj).forEach(function (item){

                    // console.log(obj[item]['POSTAL_CODE']);
                    
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
            
            console.log(xmlHttp.responseText);

            var obj = JSON.parse(xmlHttp.responseText);
                
                Object.keys(obj).forEach(function (item){

                    // console.log(obj[item]['POSTAL_CODE']);
                
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
            
            console.log(xmlHttp.responseText);

            var obj = JSON.parse(xmlHttp.responseText);
                
                Object.keys(obj).forEach(function (item){

                    // console.log(obj[item]['POSTAL_CODE']);

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

    console.log("Province :"+province);
    console.log("City :"+city);
    console.log("District :"+district);
    console.log("Subdis :"+subdistrict);

    var formData = new FormData();

    formData.append('province', province);
    formData.append('city', city);
    formData.append('district', district);
    formData.append('subdistrict', subdistrict);
    formData.append('postcode', 0);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            // console.log(">>>"+xmlHttp.responseText);
            const postcode = JSON.parse(xmlHttp.responseText);

            var $select = $(document.getElementById('postcode'));
            var selectize = $select[0].selectize;

            // CLEAR POSTAL CODE
            selectize.clearOptions();
            selectize.clear(); 

            selectize.addOption({value: postcode.POSTAL_ID, text: postcode.POSTAL_CODE});
            selectize.setValue(postcode.POSTAL_ID);            
            
        }
    }
    xmlHttp.open("post", "../logics/get_postcode");
    xmlHttp.send(formData);
});

    // FOR RED DOT IN SELECTIZE

    $("#postcode-selectized").bind("change paste keyup", function() {

        if($('#postcode-selectized').val()){
            $('.postcodestar').hide();
        }else{
            $('.postcodestar').show();
        }
    });

    $("#province-selectized").bind("change paste keyup", function() {

        if($('#province-selectized').val()){
            $('.provincestar').hide();
        }else{
            $('.provincestar').show();
        }
    });

    $("#city-selectized").bind("change paste keyup", function() {

        if($('#city-selectized').val()){
            $('.citystar').hide();
        }else{
            $('.citystar').show();
        }
    });

    $("#district-selectized").bind("change paste keyup", function() {

        if($('#district-selectized').val()){
            $('.districtstar').hide();
        }else{
            $('.districtstar').show();
        }
    });

    $("#subdistrict-selectized").bind("change paste keyup", function() {

        if($('#subdistrict-selectized').val()){
            $('.subdistrictstar').hide();
        }else{
            $('.subdistrictstar').show();
        }
    });

});

</script>

<script>
    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function() {
        
        $('#fotoProfile-error').text("");
        $('#club_image').attr('src', reader.result);

        }
        reader.readAsDataURL(event.target.files[0]);
    };
</script>

<script>
    $(".profileimagestar").show();
    $(".clubnamestar").show();
    $(".clubcategorystar").show();
    $(".linkstar").show();
    $(".descstar").show();
    $(".addressstar").show();
    $(".rtstar").show();
    $(".rwstar").show();

    $("#fotoProfile").change(function() {
        var valimage = $(this).val();

        if (valimage) {
            $(".profileimagestar").hide();
        }

        else {
            $(".profileimagestar").show();
        }
    });

    $("#name").bind("change paste keyup", function() {
        var valname= $(this).val();

        if (valname) {
            $(".clubnamestar").hide();
        }

        else {
            $(".clubnamestar").show();
        }
    });

    var category = [];

    function changeCategory(number){

        var value = $('#cat'+number).val();

        if ($('#cat'+number).is(':checked')) {

            category.push(value);

        }else{

            category = category.filter(function(item) {
                return item !== value
            })

        }

        if(category != ""){

            $('.clubcategorystar').hide();
            $('#category-error').text("");

        }else{

            $('.clubcategorystar').show();
            $('#category-error').text("This field is required.");

        }

        console.log(category.join("|"));
        $('#category').val(category.join("|"));

    }

    $("#club_link").bind("change paste keyup", function() {
        var vallink= $(this).val();

        if (vallink) {
            $(".linkstar").hide();
        }

        else {
            $(".linkstar").show();
        }
    });

    $("#club_desc").bind("change paste keyup", function() {
        var valdesc= $(this).val();

        if (valdesc) {
            $(".descstar").hide();
        }

        else {
            $(".descstar").show();
        }
    });

    $("#address").bind("change paste keyup", function() {
        var valaddress= $(this).val();

        if (valaddress) {
            $(".addressstar").hide();
        }

        else {
            $(".addressstar").show();
        }
    });

    $("#rt").bind("change paste keyup", function() {
        var valrt= $(this).val();

        if (valrt) {
            $(".rtstar").hide();
        }

        else {
            $(".rtstar").show();
        }
    });

    $("#rw").bind("change paste keyup", function() {
        var valrw= $(this).val();

        if (valrw) {
            $(".rwstar").hide();
        }

        else {
            $(".rwstar").show();
        }
    });

    $("#postcode").change(function() {
        var valpostcode = $(this).val();

        if (valpostcode) {
            $(".postcodestar").hide();
        }

        else {
            $(".postcodestar").show();
        }
    });

    $("#province").change(function() {
        var valprovince = $(this).val();

        if (valprovince) {
            $(".provincestar").hide();
        }

        else {
            $(".provincestar").show();
        }
    });

    $("#city").change(function() {
        var valcity = $(this).val();

        if (valcity) {
            $(".citystar").hide();
        }

        else {
            $(".citystar").show();
        }
    });

    $("#district").change(function() {
        var valdistrict = $(this).val();

        if (valdistrict) {
            $(".districtstar").hide();
        }

        else {
            $(".districtstar").show();
        }
    });

    $("#subdistrict").change(function() {
        var valsubdistrict = $(this).val();

        if (valsubdistrict) {
            $(".subdistrictstar").hide();
        }

        else {
            $(".subdistrictstar").show();
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

function selectizeValid(){

    var province = $('#province').val();
    var city = $('#city').val();
    var district = $('#district').val();
    var subdistrict = $('#subdistrict').val();
    var postcode = $('#postcode').val();
    var category = $('#category').val();

    var fotoProfile = $('#fotoProfile').val();
  
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

    if(!fotoProfile){
        $('#fotoProfile-error').text("This field is required.");
    }else{
        $('#fotoProfile-error').text("");
    }

    if(!category){
        $('#category-error').text("This field is required.");
    }else{
        $('#category-error').text("");
    }
}

</script>