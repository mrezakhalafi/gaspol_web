var cardModalHtml =
    '<div id="three-ds-container" style="display: none;">' +
    '   <iframe id="sample-inline-frame" name="sample-inline-frame" width="100%" height="400"> </iframe>' +
    '</div>' +
    '<form id="credit-card-form" name="creditCardForm" method="post">' +
    '<fieldset id="fieldset-card">' +
    '<div class="col p-3">' +
    '  <div class="row">' +
    '    Credit Card Number' +
    '  </div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" required class="form-control" id="credit-card-number" placeholder="e.g: 4000000000000002" name="creditCardNumber">' +
    '  </div>' +
    '  <div class="row mb-4">' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    Month' +
    '  </div>' +
    '      <div class="row">' +
    '        <select required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-month" placeholder="MM" style="border-color: #608CA5" name="creditCardExpMonth">' +
    '          <option>01</option>' +
    '          <option>02</option>' +
    '          <option>03</option>' +
    '          <option>04</option>' +
    '          <option>05</option>' +
    '          <option>06</option>' +
    '          <option>07</option>' +
    '          <option>08</option>' +
    '          <option>09</option>' +
    '          <option>10</option>' +
    '          <option>11</option>' +
    '          <option>12</option>' +
    '        </select>' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-5 mx-1">' +
    '  <div class="row">' +
    '    Year' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="4" size="4" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-year" placeholder="YYYY" style="border-color: #608CA5" name="creditCardExpYear">' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    CVV' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="3" size="3" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-cvv" placeholder="123" style="border-color: #608CA5" name="creditCardCvv">' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '<div class="row">' +
    '  <input class="pay-button" onclick="return toSubmit();" type="submit" id="pay-with-credit-card" value="Pay" name="payWithCreditCard">' +
    '</div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';


async function palioPay() {
    this.myModal = new SimpleModal();

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

class SimpleModal {

    constructor(modalTitle) {
        this.modalTitle = "title";
        this.parent = document.body;

        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = "";

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            this.closeButton.addEventListener("click", () => {
                resolve(null);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");

        // let payment_method = document.getElementById('dropdownMenuSelectMethod').innerHTML;
        let payment_method = localStorage.getItem('payment-method');
        if (payment_method == "CARD &gt;") {
            text.innerHTML = cardModalHtml;
        } else if (payment_method == "OVO &gt;") {
            text.innerHTML = ovoModalHtml;
        } else if (payment_method == "DANA &gt;") {
            text.innerHTML = danaModalHtml;
        } else if (payment_method == "LINKAJA &gt;") {
            text.innerHTML = linkajaModalHtml;
        } else {
            text.innerHTML = cardModalHtml;
        }

        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

function xenditResponseHandler(err, creditCardCharge) {
    if (err) {
        console.log(err);
        return displayError(err);
        // console.log(err);
    }

    console.log(creditCardCharge);

    if (creditCardCharge.status === 'APPROVED' || creditCardCharge.status === 'VERIFIED') {
        console.log("success");
        displaySuccess(creditCardCharge);
    } else if (creditCardCharge.status === 'IN_REVIEW') {
        window.open(creditCardCharge.payer_authentication_url, 'sample-inline-frame');
        $('.overlay').show();
        $('#three-ds-container').show();
    } else if (creditCardCharge.status === 'FRAUD') {
        displayError(creditCardCharge);
    } else if (creditCardCharge.status === 'FAILED') {
        displayError(creditCardCharge);
    }
}

function toSubmit() {
    event.preventDefault();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.setAttribute('disabled', 'disabled');

    // document.getElementById("credit-card-form").classList.add('d-none');

    //dev
    Xendit.setPublishableKey('xnd_public_development_qcfW9OvrvG3U0ph6Dc01xNMhKhhW2On4a0l7ZMUS696BBWR8vNbkSKyRZGlOLQ');
    //prod
    // Xendit.setPublishableKey('xnd_public_production_qoec6uRBSVSb4n0WwIijVZgDJevwSZ5xKuxaTRh4YBix0nMSsKgxi226yxtTd7');

    var tokenData = getTokenData();

    Xendit.card.createToken(tokenData, xenditResponseHandler);
}

function displayError(err) {
    alert('Request Credit Card Charge Failed');
    $('#three-ds-container').hide();
    $('.overlay').hide();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.removeAttribute('disabled');
    // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");
};

function displaySuccess(creditCardCharge) {
    var $form = $('#credit-card-form');
    $('#three-ds-container').hide();
    $('.overlay').hide();

    var js = {
        token_id: creditCardCharge.id,
        amount: localStorage.getItem('grand-total'),
        cvv: $form.find('#credit-card-cvv').val()
    };
    // var items = JSON.stringify(cart);
    // var base64items = btoa(items);
    // var fpin = getFpin();

    // let purchased_cart = [];
    // cart.forEach(merchant => {
    //     merchant.items.forEach(product => {
    //         if (product.selected == 'checked') {
    //             let p = {};
    //             p.p_code = product.itemCode;
    //             p.price = product.itemPrice;
    //             p.amount = product.itemQuantity;
    //             p.isPost = product.isPost;
    //             purchased_cart.push(p);
    //         }
    //     })
    // })

    // if (userAgent) {
    //     var fpin = getFpin();
    // } else {
    //     var fpin = "test";
    // }

    $.post("../logics/paliobutton/php/paliopay",
        js,
        function (data, status) {
            try {
                if (data.status == "CAPTURED") {
                    // clearCart();
                    postForm("../logics/insert_membership_payment", {
                        fpin: F_PIN,
                        method: "card",
                        status: 1,
                        price: parseInt(localStorage.getItem("grand-total")),
                        reg_type: REG_TYPE,
                        date: new Date().getTime()
                    });
                } else {
                    alert("Credit card transaction failed");
                    let fieldset = document.getElementById('fieldset-card');
                    fieldset.removeAttribute('disabled');
                }
            } catch (err) {
                console.log(err);
                alert("Error occured");
                let fieldset = document.getElementById('fieldset-card');
                fieldset.removeAttribute('disabled');
            }
        }, 'json'
    );
}

function postForm(path, params, method) {
    method = method || 'post';

    var form = document.createElement('form');
    form.id = 'my-form';
    form.setAttribute('method', method);
    form.setAttribute('action', path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', key);
            hiddenField.setAttribute('value', params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);

    let myform =$('#my-form')[0];
    let formData = new FormData(myform);

    $.ajax({
        type: "POST",
        url: "/gaspol_web/logics/insert_membership_payment",
        data: formData,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        contentType: false,
        success: function(response) {
            submitForm(REG_TYPE);
        },
        error: function(response) {
            alert(response.responseText);
        }
    })

    // let xmlHttp = new XMLHttpRequest();
    // xmlHttp.onreadystatechange = function () {
    //   if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    //     // // console.log(xmlHttp.responseText);
    //     // updateScore($productCode);
    //     submitForm(REG_TYPE);
    //   }
    // }
    // xmlHttp.open("post", "/gaspol_web/logics/insert_membership_payment");
    // xmlHttp.send(formData);
}

function getTokenData() {
    var $form = $('#credit-card-form');
    return {
        // amount: $form.find('#credit-card-amount').val(),
        amount: localStorage.getItem('grand-total'),
        card_number: $form.find('#credit-card-number').val(),
        card_exp_month: $form.find('#credit-card-exp-month').val(),
        card_exp_year: $form.find('#credit-card-exp-year').val(),
        card_cvn: $form.find('#credit-card-cvv').val(),
        is_multiple_use: false,
        should_authenticate: true
    };
}

function submitForm(type) {
    if (type == 2) { // new_kta
        var myform = $("#kta-form")[0];
        var fd = new FormData(myform);
        fd.append("f_pin", F_PIN);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_kta",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                modalSuccess.style.display = "block";
                if (window.Android) {
                    window.Android.finishGaspolForm()
                }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                alert(response.responseText);
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 1) {
        var myform = $("#kis-form")[0];
        var fd = new FormData(myform);
        fd.append("f_pin",F_PIN);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_kis",
            data: fd,
            enctype: 'multipart/form-data',
            cache : false,
            processData: false,
            contentType: false,
            success: function (response) {
                modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                modalSuccess.style.display = "block";
                if (window.Android) {
                    window.Android.finishGaspolForm()
                }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                alert("Failed");
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 4) {
        var myform = $("#tkt-form")[0];
        var fd = new FormData(myform);
        fd.append("f_pin",F_PIN);
        var kategoriStr = kategori();
        fd.append("kategori", kategoriStr);
        fd.delete("cat1");
        fd.delete("cat2");
        fd.delete("cat3");
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_tkt",
            data: fd,
            enctype: 'multipart/form-data',
            cache : false,
            processData: false,
            contentType: false,
            success: function (response) {
                UID = response;
                modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                modalSuccess.style.display = "block";
                if (window.Android) {
                    window.Android.finishGaspolForm()
                }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                alert(response.responseText);
                $("#submit").prop("disabled", false);
            }
        });
    }
}