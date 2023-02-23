"use strict";
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");
var radioEktp = "File";

$.validator.addMethod("photoSize", function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File must be JPG, GIF or PNG, less than 3MB');
$.validator.addMethod("checkPhoto", function (value, element, param) {
    return this.optional(element) || /png|jpe?g|gif/g.test(element.files[0].name.toLowerCase().split('.').pop());
}, 'File must be JPG, GIF or PNG, less than 3MB');
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
    submitHandler: function(form,event) {
        // modalProgress.style.display = "block";
        event.preventDefault();
        // var myform = $("#kta-form")[0];
        // var fd = new FormData(myform);
        
        // fd.append("f_pin",F_PIN);
        palioPay();
        $("#submit").prop("disabled", true);
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
window.onclick = function(event) {
  if (event.target == modalSuccess) {
    modalSuccess.style.display = "none";
    window.open(
        '/gaspol_web/pages/card-kta.php?f_pin=' + F_PIN,
        '_blank' // <- This is what makes it open in a new window.
      );
    // document.location = 'card-kta.php?f_pin=' + F_PIN;
  }
}

function ktpOcr(data){
    // nik name address
    var d = JSON.parse(data)
    $('#ektp').val(d['nik'])
    $('#name').val(d['name'])
    $('#domisili').val(d['address'])
}

$("input[name=ektp_radio]:radio").on("click", function(){
    if($(this).val() == "File"){
        $('#fotoEktp').prop('required',true);
        $("#ektpLabelBtn").text("Choose File")
        $("#fotoEktp").prop('accept',"image/*,ocr_file/*")
        radioEktp = $(this).val();
    }
    else {
        $('#fotoEktp').prop('required',false);
        $("#ektpLabelBtn").text("Take Photo")
        $("#fotoEktp").prop('accept',"image/*,ocr_photo/*")
        radioEktp = $(this).val();
    }
});

$('#fotoEktp').change(function (e) { 
    e.preventDefault();
    $('#ektpFileName').text(this.files[0].name)
});