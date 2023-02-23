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
$('#tkt-masyarakat').validate({
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
    submitHandler: function(form,event) {
        // modalProgress.style.display = "block";
        event.preventDefault();
        // var myform = $("#kta-form")[0];
        // var fd = new FormData(myform);
        
        // fd.append("f_pin",F_PIN);

        // VALIDATION

        var fotoprofile = $('#fotoProfile').val();
        var clubname = $('#club_name').val();
        var content_category = $('#content_preference').val();
        var clubtype = document.querySelector('input[name="clubtype_radio"]:checked').value;
        var clubcategory = document.querySelector('input[name="clubcategory_radio"]:checked').value;
        var exlink = $('#exlink').val();
        var description = $('#desc').val();
        // var payment = $("#dropdownMenuSelectMethod").val();

        var address = $('#address').val();
        var rt = $('#rt').val();
        var rw = $('#rw').val();
        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        console.log("fotoProfile = "+fotoprofile);
        console.log("club_name = "+clubname);
        console.log("clubtype_radio = "+clubtype);
        console.log("clubcategory_radio = "+clubcategory);
        console.log("exlink = "+exlink);
        console.log("desc = "+description);

        // console.log("nationality = "+nationality);
        // console.log("hobby = "+hobby);

        console.log("address = "+address);
        console.log("rt = "+rt);
        console.log("rw = "+rw);
        console.log("postcode = "+postcode);
        console.log("province = "+province);
        console.log("city = "+city);
        console.log("district = "+district);
        console.log("subdistrict = "+subdistrict);

        // console.log("fotoProfile = "+fotoProfile);
        // console.log("fotoEktp = "+fotoEktp);
        // console.log("ektp = "+ektp);

        if (fotoprofile && clubname && content_category && clubtype && clubcategory && exlink && address && rt && rw && postcode && province && city && district && subdistrict){

            if ($('#flexCheckChecked').is(':checked')) {

                // if (!payment || payment == '') {
                //     $('#validation-text').text("Please select payment method.");
                //     $('#modal-validation').modal('show');
                // } else {

                //     palioPay();
                    submitForm('10');
                // }

            }else{

                $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                $('#modal-validation').modal('show');

            }

        }else{

            $('#validation-text').text("Please fill all required form");
            $('#modal-validation').modal('show');

        }
        
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
    }
});

// When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//   if (event.target == modalSuccess) {
//     modalSuccess.style.display = "none";
//     window.open(
//         '/gaspol_web/pages/card-kta-mobility.php?f_pin=' + F_PIN,
//         '_blank' // <- This is what makes it open in a new window.
//       );
//     // document.location = 'card-kta.php?f_pin=' + F_PIN;
//   }
// }

function taaDocs(data){
    // nik name address
    var d = JSON.parse(data)

    // if (($('#ektp').val() == null || $('#ektp').val() == "") && (d['nik'] != null && d['nik'] != "")){
    //     $('#ektp').val(d['nik'])
    // }

    if (($('#name').val() == null || $('#name').val() == "") && (d['name'] != null && d['name'] != "")){
        $('#name').val(d['name'])
    }

    if (($('#address').val() == null || $('#address').val() == "") && (d['address'] != null && d['address'] != "")){
        $('#address').val(d['address'])
    }
}

// $("input[name=ektp_radio]:radio").on("click", function(){
//     if($(this).val() == "File"){
//         $('#fotoEktp').prop('required',true);
//         $("#ektpLabelBtn").text("Choose File")
//         $("#fotoEktp").prop('accept',"image/*,ocr_file/*")
//         radioEktp = $(this).val();
//     }
//     else {
//         $('#fotoEktp').prop('required',false);
//         $("#ektpLabelBtn").text("Take Photo")
//         $("#fotoEktp").prop('accept',"image/*,ocr_photo/*")
//         radioEktp = $(this).val();
//     }
// });

// $('#fotoEktp').change(function (e) { 
//     e.preventDefault();
//     $('#ektpFileName').text(this.files[0].name)
// });

// CLUB PHOTO

$("input[name=profile_radio]:radio").on("click", function(){
    if($(this).val() == "File"){
        $('#fotoProfile').prop('required',true);
        $("#profileLabelBtn").text("Choose File")
        $("#fotoProfile").prop('accept',"image/*,profile_file/*")
        radioProfile = $(this).val();

        $('#club_image').attr('src','../assets/img/tab5/create-post-black.png');
        $('#profileFileName').text("No file chosen");
    }
    else {
        $('#fotoProfile').prop('required',false);
        $("#profileLabelBtn").text("Take Photo")
        $("#fotoProfile").prop('accept',"image/*,profile_photo/*")
        radioProfile = $(this).val();

        $('#club_image').attr('src','../assets/img/tab5/create-post-black.png');
        $('#profileFileName').text("No file chosen");
    }
});

$('#fotoProfile').change(function (e) { 
    e.preventDefault();
    $('#profileFileName').text(this.files[0].name)
});

$('#docAdart').change(function (e) { 
    e.preventDefault();
    $('#adartFileName').text(this.files[0].name)
});

// CERTIFICATE DOCUMENT
$('#docCertificate').change(function (e) { 
    e.preventDefault();
    $('#certificateFileName').text(this.files[0].name)
});

// ADDITIONAL CERTIFICATE DOCUMENT
$('#docAdditional').change(function (e) { 
    e.preventDefault();
    $('#additionalFileName').text(this.files[0].name)
});