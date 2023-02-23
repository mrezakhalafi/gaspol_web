var cardModalHtml =
    '<div id="three-ds-container" style="display: none;">' +
    '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>' + 
    '   <iframe id="sample-inline-frame" name="sample-inline-frame" width="100%" height="400"> </iframe>' +
    '</div>' +
    '<form id="credit-card-form" name="creditCardForm" method="post">' +
    '<fieldset id="fieldset-card">' +
    '<div class="col">' +
    '<div class="">' +
    '<div id="grey-price" style="background-color: #f1f1f1; border: 1px solid #c3c3c3" class="p-2 form-group-2 mt-4 mb-4">' +
    '<div class="row">' +
    '<div class="col-6" style="color: #626262">' +
    title +
    '</div>' +
    '<div class="col-6 d-flex justify-content-end">' +
    '<b id="price-second">Rp. ' + price + '</b>' +
    '</div>' +
    '</div>' +
    '<div class="row">' +
    '<div class="col-6" style="color: #626262">' +
    'Administration Fee' +
    '</div>' +
    '<div class="col-6 d-flex justify-content-end">' +
    '<b>Rp. ' + price_fee + '</b>' +
    '</div>' +
    '</div>' +
    '<div id="total-price" class="row mt-2">' +
    '<div class="col-6" style="color: #626262">' +
    'Total Payment' +
    '</div>' +
    '<div class="col-6 d-flex justify-content-end">' +
    '<b id="total-slot" style="font-size: 20px">Rp. ' + total_price + '</b>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '  <div class="row">' +
    '    Credit Card Number' +
    '  </div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" pattern="[0-9]*" required class="form-control" id="credit-card-number" placeholder="e.g: 4000000000000002" name="creditCardNumber">' +
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
    '        <input maxlength="4" size="4" type="text" pattern="[0-9]*" required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-year" placeholder="YYYY" style="border-color: #608CA5" name="creditCardExpYear">' +    
    '      </div>' +
    '    </div>' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    CVV' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="3" size="3" type="text" pattern="[0-9]*" required class="form-control form-control fs-16 fontRobReg" id="credit-card-cvv" placeholder="123" style="border-color: #608CA5" name="creditCardCvv">' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '<div class="row">' +
    '  <input class="pay-button" onclick="return toSubmit();" type="submit" style="background-color: #f66701" id="pay-with-credit-card" value="Pay" name="payWithCreditCard">' +
    '</div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';

function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// FOR NATIVE ACCEPT JOIN IMI

var ref_id_global;

// ovo payment template
var ovoModalHtml =
    '<form id="ovo-form" name="ovoForm" method="post">' +
    '<fieldset id="fieldset-ovo">' +
    '<div class="col p-3">' +
    '  <div class="row">Phone Number</div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" required id="phone-number" placeholder="e.g: +6282111234567" name="phoneNumber">' +
    '  </div>' +
    '  <div class="row">' +
    '       <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitOVO();" type="submit" id="pay-with-ovo" value="Pay" name="payWithOVO">' +
    '  </div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';

// dana payment template
var danaModalHtml =
    '<form id="dana-form" name="danaForm" method="post">' +
    '<fieldset id="fieldset-dana">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitDANA();" type="submit" id="pay-with-dana" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithDANA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// linkaja payment template
var linkajaModalHtml =
    '<form id="linkaja-form" name="linkajaForm" method="post">' +
    '<fieldset id="fieldset-linkaja">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitLINKAJA();" type="submit" id="pay-with-linkaja" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithLINKAJA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// shopeepay template
var shopeepayModalHtml =
    '<form id="shopeepay-form" name="shopeepayForm" method="post">' +
    '<fieldset id="fieldset-shopeepay">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitSHOPEE();" type="submit" id="pay-with-shopeepay" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithSHOPEEPAY">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// QRIS template
var qrisModalHtml =
    '<form id="qris-form" name="qrisForm" method="post">' +
    '<fieldset id="fieldset-qris">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <div id="qrcode"></div>' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitQRIS();" type="submit" id="pay-with-qris" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Generate QR Code" name="payWithQRIS">' +
    '           <br><button type="button" style="background-color: #f06270" class="pay-button mt-3 d-none" id="simulate-qris-payment">Pay QRIS</button>' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';


var payment_method = "";

function selectMethod(e) {

    // document.getElementById('dropdownMenuSelectMethod').innerHTML = `${e} >`;
    // localStorage.setItem('payment-method', e.innerHTML);
    payment_method = e;
    localStorage.setItem('payment-method', payment_method);
    // console.log('select', payment_method);
}

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
        window.classList.add('mx-auto');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");

        // let payment_method = document.getElementById('dropdownMenuSelectMethod').innerHTML;
        let payment_method = localStorage.getItem('payment-method');
        if (payment_method.includes("CARD")) {
            text.innerHTML = cardModalHtml;
        } else if (payment_method.includes("OVO")) {
            text.innerHTML = ovoModalHtml;
        } else if (payment_method.includes("DANA")) {
            text.innerHTML = danaModalHtml;
        } else if (payment_method.includes("LINKAJA")) {
            text.innerHTML = linkajaModalHtml;
        } else if (payment_method.includes("SHOPEEPAY")) {
            text.innerHTML = shopeepayModalHtml;
        } else if (payment_method.includes("QRIS")) {
            text.innerHTML = qrisModalHtml;
        } else {
            text.innerHTML = cardModalHtml;
        }

        window.appendChild(text);

        // SCRIPT ONLY FOR IMI (PRICE IN MODAL PAYMENT)

        if (typeof total_tax !== 'undefined') {
            if (parseInt(total_tax) > 0) {
                $("#total-price").hide();

                var html = '<div class="row">' +
                    '<div class="col-6" style="color: #626262">' +
                    'Tax (10%)' +
                    '</div>' +
                    '<div class="col-6 d-flex justify-content-end">' +
                    '<b>Rp. ' + total_tax + '</b>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row mt-2">' +
                    '<div class="col-6" style="color: #626262">' +
                    'Total Payment' +
                    '</div>' +
                    '<div id="total-price" class="col-6 d-flex justify-content-end">' +
                    '<b style="font-size: 20px">Rp. ' + total_price + '</b>' +
                    '</div>' +
                    '</div>';
                $('#grey-price').append(html);
            }
        }

        // SCRIPT FOR KIS (0) AND ERA (1) (PRICE IN MODAL PAYMENT)

        var text_kis = $('#price-second').text();

        if (text_kis == "Rp. 0") {
            $('#price-second').text("Rp. " + numberWithDots(total_price));
            $('#total-slot').text("Rp. " + numberWithDots(localStorage.getItem('grand-total')));
        }else if(text_kis == "Rp. 1"){
            $('#price-second').text("Rp. " + numberWithDots(total_price));
            $('#total-slot').text("Rp. " + numberWithDots(total_price));
        }

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

    // console.log(creditCardCharge);

    $('#ccLoader').addClass('d-none');

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

    $('#ccLoader').removeClass('d-none');

    var cc = $('#credit-card-number').val();
    var yy = $('#credit-card-exp-year').val();
    var cvv = $('#credit-card-cvv').val();

    if (cc && yy && cvv) {
        let fieldset = document.getElementById('fieldset-card');
        fieldset.setAttribute('disabled', 'disabled');

        // document.getElementById("credit-card-form").classList.add('d-none');

        //dev
        Xendit.setPublishableKey('xnd_public_development_qcfW9OvrvG3U0ph6Dc01xNMhKhhW2On4a0l7ZMUS696BBWR8vNbkSKyRZGlOLQ');
        //prod
        // Xendit.setPublishableKey('xnd_public_production_qoec6uRBSVSb4n0WwIijVZgDJevwSZ5xKuxaTRh4YBix0nMSsKgxi226yxtTd7');

        var tokenData = getTokenData();

        Xendit.card.createToken(tokenData, xenditResponseHandler);
    } else {
        $('#validation-text').text('Please fill all credit card information');
        $('#modal-validation').modal('show');
    }
}
// event.preventDefault();


function displayError(err) {
    // alert('Request Credit Card Charge Failed');
    // $('#validation-text').text(err);
    if (typeof err === 'object') {
        $('#validation-text').text(err.message);
    } else {
        $('#validation-text').text(err);
    }
    $('#modal-validation').modal('show');
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

    // postForm("../logics/insert_membership_payment_mobility", {
    //     fpin: F_PIN,
    //     method: "card",
    //     status: 1,
    //     price: parseInt(parseInt(localStorage.getItem("grand-total"))),
    //     reg_type: REG_TYPE,
    //     date: new Date().getTime()
    // });

    $.post("../logics/paliobutton/php/paliopay",
        js,
        function (data, status) {
            try {
                if (data.status == "CAPTURED") {
                    // clearCart();
                    postForm("../logics/insert_membership_payment_mobility", {
                        fpin: btoa(F_PIN),
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

// payment with ovo
function toSubmitOVO() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));

    var js = {
        phone_number: $('#phone-number').val(),
        amount: amt,
    };

    // var callbackURL = this.callbackURL;
    // var amount = this.price;

    $.post("../logics/paliobutton/php/paliopay_ovo",
        js,
        function (data, status) {
            try {
                if (data == "SUCCEEDED") {
                    postForm("../logics/insert_membership_payment_mobility", {
                        fpin: F_PIN,
                        method: "OVO",
                        status: 1,
                        price: parseInt(localStorage.getItem("grand-total")),
                        reg_type: REG_TYPE,
                        date: new Date().getTime()
                    });
                } else {
                    // alert("Credit card transaction failed");
                    $('#validation-text').text('An error occured');
                    $('#modal-validation').modal('show');
                    $('#three-ds-container').hide();
                    $('.overlay').hide();
                    // showSuccessModal(dictionary.checkout.notice.failed[defaultLang], "OVO");
                }
            } catch (err) {
                console.log(err);
                // alert("Error occured");
                // $('#modal-payment').modal('toggle');
                // $('#modal-payment-status-body').text("Payment failed");
                // $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
                $('#validation-text').text('An error occured');
                $('#modal-validation').modal('show');
                $('#three-ds-container').hide();
                $('.overlay').hide();
            }
        }
    );

    // alert("Please finish your payment.");
}

// payment with dana
function toSubmitDANA() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));
    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    var js = {
        // callback: this.callbackURL,
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_dana",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url);
                window.location.href = response.actions.desktop_web_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment shopeepay
function toSubmitSHOPEE() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));
    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_shopee",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url, "_blank");
                // window.open(response.actions.mobile_deeplink_checkout_url);
                window.location.href = response.actions.mobile_deeplink_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

function checkQRISStatus(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/qris_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            // console.log('checkqris', responseObj);

            if (responseObj.status == "COMPLETED") {
                // alert(`Payment received!`); // response is the server response

                // HIT API
                // ganti vbot_ sesuai pilihan
                // let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                // digipos_cart.method = responseObj.payment_detail.source;
                // digipos_cart.last_update = new Date().getTime();

                // vbotAPI(digipos_cart);
                postForm("../logics/insert_membership_payment_mobility", {
                    fpin: btoa(F_PIN),
                    method: "TEST_QRIS",
                    status: 1,
                    price: parseInt(localStorage.getItem("grand-total")),
                    reg_type: REG_TYPE,
                    date: new Date().getTime()
                });

            } else {
                checkQRISStatus(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

function simulateQRISPayment(ext_id) {

    let amt = parseInt(localStorage.getItem("grand-total"));

    var js = {
        amount: amt,
        external_id: ext_id
    };

    $.post("../logics/paliobutton/php/qris_check",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                let responseObj = JSON.parse(data);
                // console.log('simulateqris', responseObj);

                if (responseObj.status == "COMPLETED") {
                    // alert(`Payment received!`); // response is the server response
                    var method = responseObj.payment_details.source ? responseObj.payment_details.source : "TEST_QRIS";

                    // HIT API
                    // ganti vbot_ sesuai pilihan
                    // let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                    // digipos_cart.method = method;
                    // digipos_cart.last_update = new Date().getTime();

                    // vbotAPI(digipos_cart);

                    postForm("../logics/insert_membership_payment_mobility", {
                        fpin: btoa(F_PIN),
                        method: method,
                        status: 1,
                        price: parseInt(localStorage.getItem("grand-total")),
                        reg_type: REG_TYPE,
                        date: new Date().getTime()
                    });
                } else {
                    checkQRISStatus(id);
                }
            } catch (err) {
                console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

function runSimulateQRIS(ext_id) {
    $('#simulate-qris-payment').off('click');

    $('#simulate-qris-payment').removeClass('d-none');

    $('#simulate-qris-payment').click(function (e) {
        e.preventDefault();

        simulateQRISPayment(ext_id);
    })
}

function toSubmitQRIS() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        // callback: window.location.origin + "/nexilis/pages/payment.php?f_pin=" + getFpin(),
        callback: window.location.href,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_qris",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                // console.log(response);

                new QRCode(document.getElementById('qrcode'), response.qr_string);

                runSimulateQRIS(response.external_id);

            } catch (err) {
                console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment with linkaja
function toSubmitLINKAJA() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));

    let f_pin = new URLSearchParams(window.location.search).get('f_pin');

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_linkaja",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                // console.log(response);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url);
                window.location.href = response.actions.desktop_web_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "LINKAJA");
            }
        }
    );
}

// check ewallet payment status
function checkEwallet(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/ewallet_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            console.log(`Error ${xhr.status}: ${xhr.statusText}`);
            $('#modal-payment').modal('toggle');
            $('#modal-payment-status-body').text("Payment failed");
            $('#modal-payment-status').modal('toggle');
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            // console.log(responseObj);

            if (responseObj.status == "SUCCEEDED" || responseObj.status == "COMPLETED") {
                // alert(`Payment received!`); // response is the server response
                localStorage.removeItem('ewallet_id');
                if (responseObj.channel_code == "ID_DANA") {
                    var method = "DANA";
                } else if (responseObj.channel_code == "ID_LINKAJA") {
                    var method = "LINKAJA";
                } else if (responseObj.channel_code == "ID_SHOPEEPAY") {
                    var method = "SHOPEEPAY";
                }

                // HIT API
                // ganti vbot_ sesuai pilihan
                let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                digipos_cart.method = method;
                digipos_cart.last_update = new Date().getTime();

                vbotAPI(digipos_cart);

            } else {
                checkEwallet(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
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

    let myform = $('#my-form')[0];
    let formData = new FormData(myform);

$.ajax({
        type: "POST",
        url: "/gaspol_web/logics/insert_membership_payment_mobility",
        data: formData,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            submitForm(REG_TYPE);
        },
        error: function (response) {
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
    // xmlHttp.open("post", "/gaspol_web/logics/insert_membership_payment_mobility");
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

    var f_pin = new URLSearchParams(window.location.search).get('f_pin');
    var env = new URLSearchParams(window.location.search).get('env');

    if (type == 2) { // New KTA
        var myform = $("#kta-form")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }
        

        fd.append("f_pin", F_PIN);
        fd.append("is_android", is_android);
        fd.append("postcode", $('#postcode').text());
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_kta_mobility",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";                

                submitForm(11);

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);

                deleteAllCookie();
                sendInstruction();

                if (window.Android) {
                    // $('#modalSuccess').modal('show');
                    window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=1';
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    // $('#modalSuccess').modal('show');
                    window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=1';
                }
                else {
                    $('#modalMembership').modal('show');
                    // window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=1';
                }

            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 3) { // Upgrade KTA Pro New
        var myform = $("#kta-form")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("f_pin", F_PIN);
        fd.append("is_android", is_android);
        fd.append("postcode", $('#postcode').text());
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/upgrade_kta",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.disdisplaySuccess('2423423424234');play = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";

                submitForm(11);

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);

                deleteAllCookie();
                sendInstruction();
                
                if (window.Android) {
                    // $('#modalSuccess').modal('show');
                    window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=2';
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    // $('#modalSuccess').modal('show');
                    window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=2';
                }
                else {
                    $('#modalMembership').modal('show');
                    // window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=2';
                }

            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 1) {
        var myform = $("#kis-form")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("f_pin", F_PIN);
        fd.append("is_android", is_android);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_kis",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";
                
                if (window.Android) {
                    $('#modalSuccess').modal('show');
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    $('#modalSuccess').modal('show');
                }
                else {
                    $('#modalMembership').modal('show');
                }

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                if (window.Android) {
                    window.Android.finishGaspolForm()
                }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                alert("Failed");
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 4) {
        var myform = $("#tkt-form")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("f_pin", F_PIN);
        var kategoriStr = kategori();
        fd.append("kategori", kategoriStr);
        fd.delete("cat1");
        fd.delete("cat2");
        fd.delete("cat3");
        fd.append("is_android", is_android);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_tkt",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                UID = response;
                // modalProgress.style.display = "none";
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";
                $('#modalProgress').modal('hide');
                $('#modalSuccess').modal('show');

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                if (window.Android) {
                    window.Android.finishGaspolForm()
                }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 5) { // New KTA
        var myform = $("#taa-form")[0];
        var fd = new FormData(myform);
        fd.append("f_pin", F_PIN);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("postcode", $('#postcode').text());
        fd.append("is_android", is_android);

        var category = $('#category').val();
        fd.append("category", category);

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/new-post-taa-club",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";
                
                if (window.Android) {
                    $('#modalSuccess').modal('show');
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    $('#modalSuccess').modal('show');
                }
                else {
                    $('#modalMembership').modal('show');
                }

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);

                deleteAllCookie();
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 6) { // TKT IMI CLUB
        var myform = $("#imiclub-form")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("f_pin", F_PIN);
        fd.append("postcode", $('#postcode').text());
        fd.append("is_android", is_android);

        var date = new Date();
        ref_id_global = F_PIN + date.getTime();
        fd.append("ref_id", ref_id_global);

        var category = $('#category').val();
        fd.append("category", category);

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register-imi-club",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";

                if (window.Android) {
                    $('#modalSuccess').modal('show');
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    $('#modalSuccess').modal('show');
                }
                else {
                    $('#modalMembership').modal('show');
                }

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }

                makeGroup();
                // joinSelf();

                $("#submit").prop("disabled", false);

                deleteAllCookie();
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 7) { // New GASPOL Club
        var myform = $("#tkt-form")[0];
        var fd = new FormData(myform);
        fd.append("f_pin", F_PIN);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("postcode", $('#postcode').text());

        var category = $('#category').val();

        fd.append("kategori", category);
        fd.append("is_android", is_android);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/new_taa_gaspol",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";
                $('#modalSuccess').modal('show');

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 8) { // New KIS
        var myform = $("#kis-form")[0];
        var fd = new FormData(myform);
        fd.append("f_pin", F_PIN);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        fd.append("is_android", is_android);

        var kategori;

        for (var i = 0; i < array_checked.length; i++) {

            if (kategori != null && kategori != "") {
                kategori = kategori + "|" + array_checked[i];
            } else {
                kategori = array_checked[i];
            }
        }

        fd.append('kategoriKis', kategori);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_kis_newest",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";
                // $('#modalSuccess').modal('show');

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);

                deleteAllCookie();

                if (window.Android) {
                    // $('#modalSuccess').modal('show');
                    window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=3';
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    // $('#modalSuccess').modal('show');
                    window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=3';
                }
                else {
                    $('#modalMembership').modal('show');
                    // window.location.href = '/gaspol_web/pages/kta-mobility-checkout?f_pin='+f_pin+'&env=3';
                }

                // submitForm(11);

                
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 9) { // JOIN IMI CLUB

        console.log('abc');
        var myform = $("#kta-form")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        var date = new Date();
        ref_id_global = F_PIN + date.getTime();

        fd.append("f_pin", F_PIN);
        fd.append("ref_id", ref_id_global);
        fd.append("is_android", is_android);

        // join 

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/join_club",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";

                if (window.Android) {
                    $('#modalSuccess').modal('show');
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    $('#modalSuccess').modal('show');
                }
                else {
                    $('#modalMembership').modal('show');
                }

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);

                deleteAllCookie();
                sendMessage();
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    } else if (type == 10) { // TKT MASYARAKAT

        console.log('abc');
        var myform = $("#tkt-masyarakat")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        var date = new Date();
        ref_id_global = F_PIN + date.getTime();

        fd.append("f_pin", F_PIN);
        fd.append("ref_id", ref_id_global);
        fd.append("is_android", is_android);

        // join 

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register-tkt-masyarakat",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // modalSuccess.style.display = "block";

                if (window.Android) {
                    $('#modalSuccess').modal('show');
                }
                else if (window.webkit && window.webkit.messageHandlers) {
                    $('#modalSuccess').modal('show');
                }
                else {
                    $('#modalMembership').modal('show');
                }

                if (window.Android) {
                    window.Android.checkFeatureAccess();
                }

                // if (window.Android) {
                //     window.Android.finishGaspolForm()
                // }
                $("#submit").prop("disabled", false);

                deleteAllCookie();
            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    }

    else if (type == 11) { // ROADSIDE ASSISTANCE

        console.log('abc');
        var myform = $("#checkout")[0];
        var fd = new FormData(myform);

        var is_android = 0;

        if (window.Android) {
            is_android = 1;
        }

        var date = new Date();

        for(var i=0; i<cars.length; i++){

            fd.append("f_pin", F_PIN);
            fd.append("vehicle_category", cars[i].category);
            fd.append("vehicle-island", cars[i].island);
            fd.append("photo-name", cars[i].photo);
            fd.append("vehicle-brand", cars[i].brand);
            fd.append("vehicle-type", cars[i].type);
            fd.append("vehicle-year", cars[i].year);
            fd.append("vehicle-license", cars[i].license);

            // join 

            $.ajax({
                type: "POST",
                url: "/gaspol_web/logics/register-roadside-assistance",
                data: fd,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
                contentType: false,
                success: function (response) {
                    // modalProgress.style.display = "none";
                    $('#modalProgress').modal('hide');
                    $('#modal-payment').modal('hide');
                    // modalSuccess.style.display = "block";

                    if (window.Android) {
                        $('#modalSuccess').modal('show');
                    }
                    else if (window.webkit && window.webkit.messageHandlers) {
                        $('#modalSuccess').modal('show');
                    }
                    else {
                        $('#modalMembership').modal('show');
                    }

                    if (window.Android) {
                        window.Android.checkFeatureAccess();
                    }

                    // if (window.Android) {
                    //     window.Android.finishGaspolForm()
                    // }
                    $("#submit").prop("disabled", false);
                    
                },
                error: function (response) {
                    // modalProgress.style.display = "none";
                    $('#modalProgress').modal('hide');
                    $('#modal-payment').modal('hide');
                    // alert(response.responseText);

                    $('#error-modal-text').text(response.responseText);
                    $('#modal-error').modal('show');
                    $("#submit").prop("disabled", false);
                }
            });
        }
    }
}

function sendInstruction() {

    var formData = new FormData();

    var email = $('#email').val();
    var f_pin = F_PIN;

    formData.append('email', email);
    formData.append('f_pin', f_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = JSON.parse(xmlHttp.responseText);

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "../logics/send_email_gmail_2");
    xmlHttp.send(formData);

}

function sendMessage(){

    var formData = new FormData();

    var originator = F_PIN;
    var destination = $('#destination').val();

    formData.append('originator', originator);
    formData.append('destination', destination);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        // if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            // const response = JSON.parse(xmlHttp.responseText);

            sendMessageAfter();
            
        // }
    }
    xmlHttp.open("post", "../logics/add_friend");
    xmlHttp.send(formData);
    
}

function sendMessageAfter(){

    var formData = new FormData();

    var message_id = ref_id_global;
    var originator = F_PIN;
    var destination = $('#destination').val();
    var reply_to = ref_id_global;

    var club_type = document.querySelector('input[name="club_type"]:checked').value;

    if(club_type == 1){
        club_type = "Public";
    }else{
        club_type = "Private";
    }

    var club_location = $('#club_location').text();
    var club_choice = $('#club_choice').text();
    var content = {
        "form_id" : "105857",
        "form_title" : "Join+IMI+Club",
        "A01" : "",
        "club_type" : club_type,
        "province" : club_location,
        "club" : club_choice
    };

    var scope = 18;

    formData.append('message_id', message_id);
    formData.append('originator', originator);
    formData.append('destination', destination);
    formData.append('content', btoa(JSON.stringify(content)));
    formData.append('scope', scope);
    formData.append('reply_to', reply_to);
    formData.append('file_id', "105857");

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = JSON.parse(xmlHttp.responseText);
            
        }
    }
    xmlHttp.open("post", "../logics/send_message");
    xmlHttp.send(formData);
   
}

function makeGroup(){

    var formData = new FormData();

    var f_pin = F_PIN;
    var club_name = $('#club_name').val();
    var base64_image = $('#club_image').attr('src').split(',')[1];
    let desc = $('#desc').val();

    formData.append('f_pin', f_pin);
    formData.append('group_name', club_name);
    formData.append('base64_image', base64_image);
    formData.append('description', desc)

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = xmlHttp.responseText;
            console.log("1",response);

            // var name_group = club_name;

            var id_group = response.split("\n")[1];
            // console.log("2",id_group);

            updateGroup(club_name,id_group);
            
        }
    }
    xmlHttp.open("post", "../logics/make_group_private");
    xmlHttp.send(formData);
}

function updateGroup(club_name,id_group){

    var formData = new FormData();

    var name_group = club_name;
    var id_group = id_group;

    formData.append('name_group', name_group);
    formData.append('id_group', id_group);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = xmlHttp.responseText;
            console.log(response);

        }
    }
    xmlHttp.open("post", "../logics/update_group");
    xmlHttp.send(formData);
}

function exitGroup(group){

    var formData = new FormData();

    var f_pin = F_PIN;
    var group_id = group;
    
    formData.append('f_pin', f_pin);
    formData.append('group_id', group_id);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = xmlHttp.responseText;
            
        }
    }
    xmlHttp.open("post", "../logics/exit_group");
    xmlHttp.send(formData);
}

$('#modalMembership-mainMenu').click(function() {
    if(!window.Android) {
        localStorage.removeItem('otp');
        window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/membership";
    }
})