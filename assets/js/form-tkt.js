"use strict";
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");

function kategori(){
    var k = [];
    var i = 0;
    if ($('#cat1').is(':checked')){
        k[i] = $('#cat1').val();
        i++;
    }
    if ($('#cat2').is(':checked')){
        k[i] = $('#cat2').val();
        i++;
    }
    if ($('#cat3').is(':checked')){
        k[i] = $('#cat3').val();
    }
    var joined = k.join("|")
    return joined
}

$.validator.addMethod("checkPdf", function (value, element, param) {
    return this.optional(element) || /pdf/g.test(element.files[0].name.toLowerCase().split('.').pop());
}, 'File must be in PDF format');

$('#tkt-form').validate({
    rules: {
        cat1: {
            require_from_group: [1, ".check"]
        },
        cat2: {
            require_from_group: [1, ".check"]
        },
        cat3: {
            require_from_group: [1, ".check"]
        },
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
        modalProgress.style.display = "block";
        event.preventDefault();
        palioPay();
        $("#submit").prop("disabled", true);
        // var myform = $("#tkt-form")[0];
        // var fd = new FormData(myform);
        // fd.append("f_pin",F_PIN);
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
window.onclick = function(event) {
  if (event.target == modalSuccess) {
    modalSuccess.style.display = "none";
    window.open(
        // '/gaspol_web/pages/card-tkt.php?uid=' + UID,
        '/gaspol_web/pages/form-tkt.php?f_pin=' + F_PIN,
        '_blank' // <- This is what makes it open in a new window.
      );
    // document.location = 'card-kta.php?f_pin=' + F_PIN;
  }
}