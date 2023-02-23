"use strict";
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");

$.validator.addMethod("photoSize", function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File must be JPG, GIF or PNG, less than 3MB');
$.validator.addMethod("checkPhoto", function (value, element, param) {
    return this.optional(element) || /png|jpe?g|gif/g.test(element.files[0].name.split('.').pop());
}, 'File must be JPG, GIF or PNG, less than 3MB');
$('#kis-form').validate({
    rules: {
        fotoSim: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoPersetujuan: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoProfil: {
            photoSize: 4000000,
            checkPhoto: true
        }
    },
    submitHandler: function (form, event) {
        // modalProgress.style.display = "block";
        event.preventDefault();
        // $('#name').prop('disabled', false);
        // $('#domisili').prop('disabled', false);


        // VALIDATION


        var province = $('#province').val();
        var kateg = array_checked;
        var driving_status = document.querySelector('input[name="driving_status"]:checked').value;
        var payment = $("#dropdownMenuSelectMethod").val();

        if (driving_status == 2) {

            var kk = $('#kk').val();
            var fotoKK = $('#fotoKK').val();

            console.log("province = " + province);
            console.log("kategori = " + kateg);
            console.log("driving status = " + driving_status);
            console.log("no kk = " + kk);
            console.log("foto kk = " + fotoKK);

            if (province && kateg != "" && kk && fotoKK) {

                if ($('#flexCheckChecked').is(':checked')) {

                    if (!payment || payment == '') {
                        $('#validation-text').text("Please select payment method.");
                        $('#modal-validation').modal('show');
                    } else {

                        palioPay();
                    }

                } else {

                    $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                    $('#modal-validation').modal('show');

                }

            } else {

                $('#validation-text').text("Please fill all required form");
                $('#modal-validation').modal('show');

            }


        } else if (driving_status == 1) {

            var checkCar = $(".form-check-input-car").is(":checked");
            var checkMotor = $(".form-check-input-motor").is(":checked");

            if ($('#licenseCar').is(':checked') && $('#licenseBike').is(':checked')) {

                var simA = $('#sim-a').val();
                var fotoSIMA = $('#fotoSIMA').val();
                var simC = $('#sim-c').val();
                var fotoSIMC = $('#fotoSIMC').val();

                console.log("province = " + province);
                console.log("kategori = " + kateg);
                console.log("driving status = " + driving_status);
                console.log("no sim a = " + simA);
                console.log("file sim a = " + fotoSIMA);
                console.log("no sim c = " + simC);
                console.log("file sim c = " + fotoSIMC);

                if (province && kateg != "" && simA && fotoSIMA && simC && fotoSIMC) {

                    if (checkCar && checkMotor) {

                        if (simA != simC) {

                            if ($('#flexCheckChecked').is(':checked')) {

                                if (!payment || payment == '') {
                                    $('#validation-text').text("Please select payment method.");
                                    $('#modal-validation').modal('show');
                                } else {
                
                                    palioPay();
                                }

                            } else {

                                $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                                $('#modal-validation').modal('show');

                            }

                        }

                        else {

                            $('#validation-text').text("Your SIM A and SIM C Number are the same. Pls use another SIM Number.");
                            $('#modal-validation').modal('show');
        
                        }

                    } else {
                        $('#validation-text').text("Please match check the vehicle and the license type.");
                        $('#modal-validation').modal('show');
                    }

                } else {

                    $('#validation-text').text("Please fill all SIM A & SIM C identity");
                    $('#modal-validation').modal('show');

                }

            } else if ($('#licenseCar').is(':checked')) {

                var simA = $('#sim-a').val();
                var fotoSIMA = $('#fotoSIMA').val();

                console.log("province = " + province);
                console.log("kategori = " + kateg);
                console.log("driving status = " + driving_status);
                console.log("no sim a = " + simA);
                console.log("file sim a = " + fotoSIMA);

                if (province && kateg != "" && simA && fotoSIMA) {

                    if (checkCar && !checkMotor) {

                        if ($('#flexCheckChecked').is(':checked')) {

                            if (!payment || payment == '') {
                                $('#validation-text').text("Please select payment method.");
                                $('#modal-validation').modal('show');
                            } else {
            
                                palioPay();
                            }

                        } else {

                            $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                            $('#modal-validation').modal('show');

                        }

                    } else {
                        $('#validation-text').text("Please match check the vehicle and the license type.");
                        $('#modal-validation').modal('show');
                    }

                } else {

                    $('#validation-text').text("Please fill all SIM A identity");
                    $('#modal-validation').modal('show');

                }

            } else if ($('#licenseBike').is(':checked')) {

                var simC = $('#sim-c').val();
                var fotoSIMC = $('#fotoSIMC').val();

                console.log("province = " + province);
                console.log("kategori = " + kateg);
                console.log("driving status = " + driving_status);
                console.log("no sim c = " + simC);
                console.log("file sim c = " + fotoSIMC);

                if (province && kateg != "" && simC && fotoSIMC) {

                    if (checkMotor && !checkCar) {

                        if ($('#flexCheckChecked').is(':checked')) {

                            if (!payment || payment == '') {
                                $('#validation-text').text("Please select payment method.");
                                $('#modal-validation').modal('show');
                            } else {
            
                                palioPay();
                            }

                        } else {

                            $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                            $('#modal-validation').modal('show');

                        }

                    } else {
                        $('#validation-text').text("Please match check the vehicle and the license type.");
                        $('#modal-validation').modal('show');
                    }

                } else {

                    $('#validation-text').text("Please fill all SIM C identity");
                    $('#modal-validation').modal('show');

                }

            } else {
                $('#validation-text').text("Please fill all required form");
                $('#modal-validation').modal('show');
            }
        }


        // $("#submit").prop("disabled", true);
        // var myform = $("#kis-form")[0];
        // var fd = new FormData(myform);
        // fd.append("f_pin",F_PIN);
        // $.ajax({
        //     type: "POST",
        //     url: "/gaspol_web/logics/register_new_kis",
        //     data: fd,
        //     enctype: 'multipart/form-data',
        //     cache : false,
        //     processData: false,
        //     contentType: false,
        //     success: function (response) {
        //         modalProgress.style.display = "none";
        //         modalSuccess.style.display = "block";
        //         if (window.Android) {
        //             window.Android.finishGaspolForm()
        //         }
        //         $("#submit").prop("disabled", false);
        //     },
        //     error: function (response) {
        //         modalProgress.style.display = "none";
        //         alert("Failed");
        //         $("#submit").prop("disabled", false);
        //     }
        // });
    }
});


function fetchKtaData() {
    if (!F_PIN) {
        // document.getElementById('notfound').classList.remove('d-none');
    }
    var formData = new FormData();
    formData.append('f_pin', F_PIN);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var ktaData = JSON.parse(xmlHttp.responseText);
            if (ktaData.length > 0) {
                // document.getElementById('notfound').style.display = "none";
                $('#submit').prop('disabled', false);
                var ktaEntry = ktaData[0];
                $('#name').val(ktaEntry["NAME"]);
                $('#domisili').val(ktaEntry["ADDRESS"]);
                $('#name').prop('disabled', true);
                $('#domisili').prop('disabled', true);
                if (ktaEntry["PROFILE_IMAGE"]) {
                    var fotoElemen = document.getElementById('fotoProfilKta');
                    fotoElemen.classList.remove('d-none');
                    fotoElemen.src = "/gaspol_web/images/" + ktaEntry["PROFILE_IMAGE"]
                    $('#fotoKta').val(ktaEntry["PROFILE_IMAGE"]);
                }
                if (ktaEntry["FOTO_SIM"]) {
                    var fotoElemen = document.getElementById('fotoSimKtaImg');
                    fotoElemen.classList.remove('d-none');
                    fotoElemen.src = "/gaspol_web/images/" + ktaEntry["FOTO_SIM"]
                    // document.getElementById('fotoSim').style.display = "none";
                    // document.getElementById('fotoPersetujuan').style.display = "none";
                    $('#fotoSimKta').val(ktaEntry["FOTO_SIM"]);
                    $('#fotoSim').prop('disabled', true);
                    $('#fotoPersetujuan').prop('disabled', true);
                }

            } else {
                // document.getElementById('notfound').style.display = "";
                $('#submit').prop('disabled', true);
            }
        }
    }
    xmlHttp.open("post", "/gaspol_web/logics/fetch_kta_data");
    xmlHttp.send(formData);
}

function fetchKisCategory() {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var kategoriArray = JSON.parse(xmlHttp.responseText);
            if (kategoriArray.length > 0) {
                var kategoriSelect = document.getElementById('kategoriKis');
                for (var i = 0; i < kategoriArray.length; i++) {
                    var kategori = kategoriArray[i];
                    var opt = document.createElement("option");
                    opt.value = kategori['CODE'];
                    opt.innerHTML = kategori['CODE'] + ": " + kategori['NAME'];
                    kategoriSelect.appendChild(opt);
                }
            }
        }
    }
    xmlHttp.open("get", "/gaspol_web/logics/fetch_kis_category");
    xmlHttp.send();

}

$(function () {
    // fetchKisCategory();
    fetchKtaData();
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modalSuccess) {
        //   modalSuccess.style.display = "none";
        $('#modalSuccess').modal('hide');
        window.open(
            '/gaspol_web/pages/card-kis.php?f_pin=' + F_PIN,
            '_blank' // <- This is what makes it open in a new window.
        );
        // document.location = 'card-kta.php?f_pin=' + F_PIN;
    }
}

$('#fotoKK').change(function (e) {
    e.preventDefault();
    $('#kkFileName').text(this.files[0].name)
});

$('#fotoSIMA').change(function (e) {
    e.preventDefault();
    $('#simAFileName').text(this.files[0].name)
});

$('#fotoSIMC').change(function (e) {
    e.preventDefault();
    $('#simCFileName').text(this.files[0].name)
});