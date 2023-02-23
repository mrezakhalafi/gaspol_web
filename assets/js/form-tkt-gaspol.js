"use strict";
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");

// function kategori(){
//     var k = [];
//     var i = 0;
//     if ($('#cat1').is(':checked')){
//         k[i] = $('#cat1').val();
//         i++;
//     }
//     if ($('#cat2').is(':checked')){
//         k[i] = $('#cat2').val();
//         i++;
//     }
//     if ($('#cat3').is(':checked')){
//         k[i] = $('#cat3').val();
//     }
//     if ($('#cat4').is(':checked')){
//         k[i] = $('#cat4').val();
//     }
//     if ($('#cat5').is(':checked')){
//         k[i] = $('#cat5').val();
//     }
//     var joined = k.join("|")
//     return joined
// }

$.validator.addMethod("checkPdf", function (value, element, param) {
    return this.optional(element) || /pdf/g.test(element.files[0].name.toLowerCase().split('.').pop());
}, 'File must be in PDF format');

$('#tkt-form').validate({
    rules: {
        adArt : {
            checkPdf: true
        },
        aktaPP : {
            checkPdf: true
        }
    },
    message : {
        spam : "Please select at least one type of category."
    },
    submitHandler: function(form,event) {
        // modalProgress.style.display = "block";
        event.preventDefault();

        // VALIDATION

        var fotoProfile = $('#fotoProfile').val();
        var name = $('#name').val();
        var kategori = $('#category').val();
        var club_link = $('#club_link').val();
        var club_desc = $('#club_desc').val();

        var address = $('#address').val();
        var rt = $('#rt').val();
        var rw = $('#rw').val();
        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        console.log("foto profile = "+fotoProfile);
        console.log("name = "+name);
        console.log("kategori = "+kategori);
        console.log("club link = "+club_link);
        console.log("club desc = "+club_desc);
        console.log("address = "+address);
        console.log("rt = "+rt);
        console.log("rw = "+rw);
        console.log("postcode = "+postcode);
        console.log("province = "+province);
        console.log("city = "+city);
        console.log("district = "+district);
        console.log("subdistrict = "+subdistrict);

        if (fotoProfile && name && kategori && club_link && club_desc && address && rt && rw && postcode && province && city && district && subdistrict){

            if ($('#flexCheckChecked').is(':checked')) {

                palioPay();

            }else{

                $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
                $('#modal-validation').modal('show');

            }

        }else{

            $('#validation-text').text("Please fill all required form");
            $('#modal-validation').modal('show');

        }
        
        // $("#submit").prop("disabled", true);
        // var myform = $("#tkt-form")[0];
        // var fd = new FormData(myform);
        // // fd.append("f_pin",F_PIN);
        // var kategoriStr = kategori();
        // fd.append("kategori", kategoriStr);
        // fd.delete("cat1");
        // fd.delete("cat2");
        // fd.delete("cat3");
        // $.ajax({
        //     type: "POST",
        //     url: "/gaspol_web/logics/register_new_tkt",
        //     data: fd,
        //     enctype: 'multipart/form-data',
        //     cache : false,
        //     processData: false,
        //     contentType: false,
        //     success: function (response) {
        //         UID = response;
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
//         // '/gaspol_web/pages/card-tkt.php?uid=' + UID,
//         '/gaspol_web/pages/form-tkt.php?f_pin=' + F_PIN,
//         '_blank' // <- This is what makes it open in a new window.
//       );
//     // document.location = 'card-kta.php?f_pin=' + F_PIN;
//   }
// }

$('#fotoProfile').change(function (e) { 
    e.preventDefault();
    $('#profileFileName').text(this.files[0].name)
});