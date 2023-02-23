"use strict";
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");
var radioEktp = "File";
var radioProfile = "File";

$.validator.addMethod("photoSize", function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File must be JPG, GIF or PNG, less than 3MB');
$.validator.addMethod("checkPhoto", function (value, element, param) {
    return this.optional(element) || /png|jpe?g|gif/g.test(element.files[0].name.toLowerCase().split('.').pop());
}, 'File must be JPG, GIF or PNG, less than 3MB');

if (localStorage.lang == 1) {
    var nameError = "Kolom ini wajib diisi.";    
    var emailError = "Harap masukkan alamat email dengan benar.";
}
else {
    var nameError = "This field is required.";    
    var emailError = "Please enter a valid email address.";
}

$('#kta-form').validate({
    rules: {
        ektp: {
            number: true
        },
        fotoEktp: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoSim: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoProfil: {
            photoSize: 4000000,
            checkPhoto: true
        }
    },
    messages: {
        'ektp': {
            required: nameError
        },
        'name': {
            required: nameError
        },
        'email': {
            required: nameError,
            email: emailError
        },
        'address': {
            required: nameError
        },
        'rt': {
            required: nameError
        },
        'rw': {
            required: nameError
        },
        'hobby_desc': {
            required: nameError
        }
    },
    submitHandler: function (form, event) {
        // modalProgress.style.display = "block";
        event.preventDefault();
        // var myform = $("#kta-form")[0];
        // var fd = new FormData(myform);

        // fd.append("f_pin",F_PIN);

        // VALIDATION

        var name = $('#name').val();
        var email = $('#email').val();
        var birthplace = $('#birthplace').val();
        var date_birth = $('#date_birth').val();
        var gender_radio = document.querySelector('input[name="gender_radio"]:checked').value;
        var bloodtype = $('#bloodtype').val();
        var nationality = $('#nationality').val();
        var hobby = $('#hobby').val();

        var address = $('#address').val();
        var rt = $('#rt').val();
        var rw = $('#rw').val();
        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        var payment = $('#dropdownMenuSelectMethod').val();

        var fotoProfile = $('#fotoProfile').val();
        var fotoEktp = $('#fotoEktp').val();
        var ektp = $('#ektp').val();

        // console.log("name = "+name);
        // console.log("email = "+email);
        // console.log("birthplace = "+birthplace);
        // console.log("date_birth = "+date_birth);
        // console.log("gender_radio = "+gender_radio);
        // console.log("bloodtype = "+bloodtype);
        // console.log("nationality = "+nationality);
        // console.log("hobby = "+hobby);
        // console.log("address = "+address);
        // console.log("rt = "+rt);
        // console.log("rw = "+rw);
        // console.log("postcode = "+postcode);
        // console.log("province = "+province);
        // console.log("city = "+city);
        // console.log("district = "+district);
        // console.log("subdistrict = "+subdistrict);
        // console.log("fotoProfile = "+fotoProfile);
        // console.log("fotoEktp = "+fotoEktp);
        // console.log("ektp = "+ektp);

        if (name && email && birthplace && date_birth && gender_radio && bloodtype && nationality && hobby &&
            address && rt && rw && postcode && province && city && district && subdistrict && payment &&
            fotoProfile && fotoEktp && ektp && is_takken == 1 && is_takken_ktp == 1) {

            if ($('#flexCheckChecked').is(':checked')) {

                validateEmail();
                // palioPay();

            } else {

                $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                $('#modal-validation').modal('show');
                $("html").attr("style", "overflow-y: hidden");

            }

        } else {

            if (localStorage.lang == 1) {
                $('#validation-text').text("Harap isi semua form");    
            }
            else {
                $('#validation-text').text("Please fill all required form");
            }
            $('#modal-validation').modal('show');
            $("html").attr("style", "overflow-y: hidden");

        }

        $("#modal-validation").on('hidden.bs.modal', function(e) {
            $("html").attr("style", "");
        });


        // $("#submit").prop("disabled", true);

        // $.ajax({
        //     type: "POST",
        //     url: "/gaspol_web/logics/register_new_kta",
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
        //         alert(response.responseText);
        //         $("#submit").prop("disabled", false);
        //     }
        // });
        // $.post('/gaspol_web/logics/register_new_esim',fd,function(){
        //     alert( "success" );
        //   },"multipart/form-data").done(function() {
        //       alert( "second success" );
        //     }).fail(function() {
        //       alert( "error" );
        //     }).always(function() {
        //       alert( "finished" );
        //     });

        // if (localStorage.lang == 1) {
        //     alert();
        //     $(".error").text("Kolom ini wajib diisi.");
        // }
    }
});

function generateRandomNumber() {
    var minm = 100000;
    var maxm = 999999;
    return Math.floor(Math
        .random() * (maxm - minm + 1)) + minm;
}

function validateEmail() {

    $('#modal-otp').modal('show');

    var formData = new FormData();

    var email = $('#email').val();
    var otp = generateRandomNumber();
    localStorage.setItem('otp', btoa(otp));

    $('#email-place-otp').text(email);

    formData.append('email', email);
    formData.append('otp', btoa(otp));

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = JSON.parse(xmlHttp.responseText);

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "../logics/send_email_gmail");
    xmlHttp.send(formData);

}

function checkOTP() {

    var input = $('#input-otp').val();
    var otp = atob(localStorage.getItem('otp'));

    if (input == otp) {

        $('#modal-otp').modal('hide');
        palioPay();

    } else {
        $('#otp-not-correct').removeClass('d-none');
    }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modalSuccess) {
        modalSuccess.style.display = "none";
        window.open(
            '/gaspol_web/pages/card-kta-mobility.php?f_pin=' + F_PIN,
            '_blank' // <- This is what makes it open in a new window.
        );
        // document.location = 'card-kta.php?f_pin=' + F_PIN;
    }
}

function ktpOcr(data) {
    // nik name address
    var d = JSON.parse(data)

    if (($('#ektp').val() == null || $('#ektp').val() == "") && (d['nik'] != null && d['nik'] != "")) {

        $('#ektp').val(d['nik'])
        $('#ektp-error').text("");
        $('.starnoktp').hide();

        var formData = new FormData();
        formData.append('ektp', d['nik']);


        if (d['nik'].length < 16) {
            $('#ktp-not-exist').text("Image detection might be imperfect. Please correct accordingly.");
            $('#ktp-exist').text("");
        } else {
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                    // // console.log(xmlHttp.responseText);

                    var result = xmlHttp.responseText;

                    if (result == 1) {
                        // console.log("KTP Ada");
                        $('#ktp-not-exist').text("");
                        $('#ktp-exist').text("That KTP Number is taken, try another.");

                        is_takken_ktp = 0;

                    } else if (result == 0) {
                        // console.log("KTP Tidak Ada");
                        $('#ktp-not-exist').text("That KTP Number is available.");
                        $('#ktp-exist').text("");

                        is_takken_ktp = 1;
                    }

                }
            }
            xmlHttp.open("post", "../logics/check_ktp");
            xmlHttp.send(formData);
        }
    }

    if (($('#name').val() == null || $('#name').val() == "") && (d['name'] != null && d['name'] != "")) {
        $('#name').val(d['name'])

        // $('#ektp-error').text("");
        $('.fullname').hide();

    }

    if (($('#address').val() == null || $('#address').val() == "") && (d['address'] != null && d['address'] != "")) {
        $('#address').val(d['address'])

        // $('#ektp-error').text("");
        $('.staraddress').hide();

    }
}

$("input[name=ektp_radio]:radio").on("click", function () {
    if ($(this).val() == "File") {
        $('#fotoEktp').prop('required', true);
        // $("#ektpLabelBtn").text("Choose File")
        $("#fotoEktp").prop('accept', "image/*,ocr_file/*")
        radioEktp = $(this).val();

        $('#imageKTP').attr('src', '../assets/img/ktp.svg');
        $('#ektpFileName').text("No file chosen");
    } else {
        $('#fotoEktp').prop('required', false);
        // $("#ektpLabelBtn").text("Take Photo")
        $("#fotoEktp").prop('accept', "image/*,ocr_photo/*")
        radioEktp = $(this).val();

        $('#imageKTP').attr('src', '../assets/img/ktp.svg');
        $('#ektpFileName').text("No file chosen");
    }
});

function uploadOCRWeb(file) {
    let formData = new FormData();
    formData.append('ektp', file);
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            console.log(xmlHttp.responseText);

            var result = xmlHttp.responseText;

            ktpOcr(result);

        }
    }
    xmlHttp.open("post", "../logics/ocr_ktp_web");
    xmlHttp.send(formData);
}

$('#fotoEktp').change(function (e) {
    e.preventDefault();
    $('#ektpFileName').text(this.files[0].name)
    // upload for OCR web
    if (!window.Android) {
        uploadOCRWeb(this.files[0]);
    }
});

// NEW PROFILE PIC

$("input[name=profile_radio]:radio").on("click", function () {
    if ($(this).val() == "File") {
        $('#fotoProfile').prop('required', true);
        $("#profileLabelBtn").text("Choose File")
        $("#fotoProfile").prop('accept', "image/*,profile_file/*")
        radioProfile = $(this).val();

        $('#imageProfile').attr('src', '../assets/img/avatar.svg');
        $('#profileFileName').text("No file chosen");
    } else {
        $('#fotoProfile').prop('required', false);
        $("#profileLabelBtn").text("Take Photo")
        $("#fotoProfile").prop('accept', "image/*,profile_photo/*")
        radioProfile = $(this).val();

        $('#imageProfile').attr('src', '../assets/img/avatar.svg');
        $('#profileFileName').text("No file chosen");
    }
});

$('#fotoProfile').change(function (e) {
    e.preventDefault();
    $('#profileFileName').text(this.files[0].name)
});