// edit modal
class EditModal {

    constructor() {

        this.html =
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3 small-text">' +
            '       <div class="row my-3">' +
            `           <input name="collection-title" placeholder="Collection Title" required>` +
            '       </div>' +
            '       <div class="row my-3">' +
            `           <textarea rows="2" name="short-description" required>Short description (Optional)</textarea>` +
            '       </div>' +
            '       <div class="row">' +
            `           <button id="confirm-changes" class="py-1 px-3 m-0 my-1 fs-16">Save Changes</button>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-changes-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    question() {
        this.save_button = document.getElementById('confirm-changes');

        return new Promise((resolve, reject) => {
            this.save_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(true);
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
        text.innerHTML = this.html;
        window.appendChild(text);

        // Let's rock
        $('#modal-changes').modal('show');
    }

    _destroyModal() {
        $('#modal-changes').modal('hide');
    }
}

async function editCollection() {

    event.preventDefault();

    let edit = new EditModal();
    let response = await edit.question();

}

// get product data based on its code
function getProductDetail(product_code) {
    let formData = new FormData();
    formData.append("product_id", product_code);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/gaspol_web/logics/get_product_data");

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(JSON.parse(xhr.response));
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };

        xhr.send(formData);
    });
}

function getPostDetail(product_code) {
    let formData = new FormData();
    formData.append("product_id", product_code);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/gaspol_web/logics/get_post_data");

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(JSON.parse(xhr.response));
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };

        xhr.send(formData);
    });
}

function changeTab(id) {
    const desc = document.getElementById('description')
    const rat = document.getElementById('ratings')
    const desctab = document.getElementById('description-tab')
    const rattab = document.getElementById('ratings-tab')

    if (id == "description") {
        desc.classList.remove('d-none');
        rat.classList.add('d-none');
        rattab.classList.remove('tab-active');
        desctab.classList.add('tab-active');
    } else {
        rat.classList.remove('d-none');
        desc.classList.add('d-none');
        desctab.classList.remove('tab-active');
        rattab.classList.add('tab-active');
    }
}

function playModalVideo() {
    $('#modal-addtocart .addcart-img-container video').each(function () {
        $(this).off("play");
        $(this).on("play", function (e) {
            $(this).closest(".carousel").carousel("pause");
        })
        $(this).get(0).play();
        let $videoPlayButton = $(this).parent().find(".video-play");
        $videoPlayButton.addClass("d-none");
    })
}

let modalAddToCart = document.getElementById('modal-addtocart');

function toggleZoomVideoMute(code) {

}

modalAddToCart.addEventListener('shown.bs.modal', function () {
    console.log('shown');

    try {
        let videoWrap = this.querySelector('.carousel-item.active .video-wrap').id;
        toggleVideoMute(videoWrap);
    } catch (e) {

    }
    checkButtonPos();
    playModalVideo();

    let lastScrollTop = 0;
    $('#modal-addtocart .modal-content').scroll(function () {
        var currentScroll = $(this).scrollTop() + $(this).innerHeight(),
            maxScroll = this.scrollHeight;
        // console.log((currentScroll / maxScroll) * 100);
        
        if (currentScroll > lastScrollTop){
            // downscroll code
            console.log('up');
            if (window.Android) {
                window.Android.tabShowHide(false);
            }
         } else {
            // upscroll code
            console.log('down');
            if (window.Android) {
                window.Android.tabShowHide(true);
            }
         }
         lastScrollTop = currentScroll;
    });

});

modalAddToCart.addEventListener('hidden.bs.modal', function () {
    console.log('hidden');
    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
        // window.Android.setButtonTheme(''); // revert floating button

    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
        window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
            param1: false
        });
    }
    let activeCategories = localStorage.getItem('active_content_category');
    let bTheme = '';
    if (activeCategories !== null && activeCategories.split('-').length === 1) {
        bTheme = activeCategories.split('-')[0];
    }
    buttonTheme(bTheme);
    $('#modal-addtocart').removeClass('d-flex justify-content-center');
    let modalVideo = $('#modal-addtocart').find('video');
    if (modalVideo.length > 0) {
        $('#modal-addtocart .modal-body video').get(0).pause();
    }
})


function ext(url) {
    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."));
}

let $image_type_arr = ["jpg", "jpeg", "png", "webp"];
let $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
let ext_re = /(?:\.([^.]+))?$/;

function basename(url) {
    return url.split(/[\\/]/).pop();
}

function goToLink(link) {
    // console.log('broh');
    // localStorage.setItem('close_modal', 1);
    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
    }
    window.location.href = link;
}

// addtocart modal
class Addtocart {

    constructor(async_result) {

        let thumb_content = '';
        let thumb_id = async_result['THUMB_ID'].split('|');
        console.log(thumb_id);
        // let thumb_ext = ext(thumb_id).substr(1);
        // console.log(thumb_ext);
        let imageDivs = '';

        let domain = 'http://108.136.138.242';

        // if ($image_type_arr.includes(thumb_ext)) {
        //     thumb_content = `<img class="product-img" src="${ thumb_id.includes("http") ? thumb_id : domain + "/gaspol_web/images/" + thumb_id}">`;
        // } else if ($video_type_arr.includes(thumb_ext)) {
        // thumb_content = `
        // <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
        // <video class="myvid" muted playsinline>
        // <source src="${thumb_id.includes("http") ? thumb_id : domain + "/gaspol_web/images/" + thumb_id}">
        // </video>
        // <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
        // <img src="../assets/img/video_mute.png" />
        // </div>
        // <div class="video-play d-none">
        // '<img src="../assets/img/video_play.png" />
        // </div>
        // </div>
        // `;
        // }
        thumb_id.forEach((image, jIdx) => {
            var imgElem = '';
            var fileExt = ext_re.exec(image)[1];
            if ($image_type_arr.includes(fileExt)) {
                imgElem = `<img class="product-img" src="${ image.includes("http") ? image : domain + "/gaspol_web/images/" + image}">`;
            } else if ($video_type_arr.includes(fileExt)) {
                imgElem = `
              <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
              <video class="myvid" muted playsinline>
              <source src="${thumb_id.includes("http") ? image : domain + "/gaspol_web/images/" + image}">
              </video>
              <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
              <img src="../assets/img/video_mute.png" />
              </div>
              <div class="video-play d-none">
              '<img src="../assets/img/video_play.png" />
              </div>
              </div>
              `;
            }
            if (imgElem) {
                if (jIdx == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
                } else {
                    imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
                }
            }
        });

        let carouselControls = '';

        if (thumb_id.length > 1) {
            carouselControls = `
            <a class="carousel-control-prev" data-bs-target="#carousel-addtocart" data-bs-slide="prev" onclick="event.stopPropagation();"><span class="carousel-control-prev-icon"></span></a>
            <a class="carousel-control-next" data-bs-target="#carousel-addtocart" data-bs-slide="next" onclick="event.stopPropagation();"><span class="carousel-control-next-icon"></span></a>
            `;
        }

        console.log(thumb_content);



        let link = async_result.LINK;

        // if (async_result.LINK.substring(0,5) != "http") {
        //     link = "https://" + link;
        // }

        let link_thumb = imageDivs;

        let url_div = '';

        let f_pin = '';

        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get("f_pin");
        }

        let btn_text = "Click here";

        if (localStorage.lang == 1) {
            btn_text = "Klik di sini";
        }

        if (link != null && link != "") {
            if (link.substring(0, 4) != "http") {
                link = "https://" + link;
            }
            console.log(link);
            // link_thumb = '<a href="' + link + '">' + thumb_content + '</a>';
            url_div = `
            <a class="btn btn-dark" onclick="goToLink('${link}');" style="font-size:12px;">${btn_text}</a>
            `;
            if (async_result.CODE == '16467163130000246a901c4') {
                url_div = `
           <a class="btn btn-dark" onclick="goToLink('${link + "?f_pin=" + f_pin}');" style="font-size:12px;">Register membership</a>
            `;
            } else if (async_result.CODE == '16467252360000246a901c4') {
                url_div = `
           <a class="btn btn-dark" onclick="goToLink('${"/nexilis/pages/digipos?f_pin=" + f_pin}');" style="font-size:12px;">${btn_text}</a>
            `;
            }
        }

        let profpic = "";

        if (async_result.SHOP_THUMBNAIL != null && async_result.SHOP_THUMBNAIL.trim() != "") {
            profpic = "http://108.136.138.242/filepalio/image/" + async_result.SHOP_THUMBNAIL;
        } else {
            profpic = "/gaspol_web/assets/img/ic_person_boy.png";
        }

        // console.log(thumb_content);

        let is_paid = async_result.PRICING == 1;
        let post_price = async_result.POST_PRICE;

        let description = "";

        if (async_result.DESCRIPTION.includes("klik disini saja")) {
            console.log('ada cuy');
            description = async_result.DESCRIPTION.replace("klik disini saja", '<a href="' + link + '" style="text-decoration: underline; color:blue;">klik disini saja</a>');
        } else {
            description = async_result.DESCRIPTION;
        }

        // codes below wil only run after getProductDetail done executing
        this.html =
            `           
            <div class="container-fluid">
                <div class="col-12 px-0 mb-3">
                    <div class="addcart-img-container text-center">
                        <div id="carousel-addtocart" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-inner">
                                ${link_thumb}
                                ${carouselControls}
                            </div>
                        </div>
                        <img style="z-index: 9999" class="addcart-wishlist logo" src="${profpic}">
                        <img class="addcart-wishlist verif d-none" src="../assets/img/icons/Verified-(Black).png">
                        <span class="d-flex align-items-center addcart-wishlist name small-text">${async_result['SHOP_NAME']}</span>
                        <img class="addcart-wishlist star ${is_paid ? "" : "d-none"}" src="../assets/img/icons/wishlist-yellow.png">
                        <img style="z-index: 9999" class="addcart-wishlist more d-none" src="../assets/img/icons/More-white.png">
                    </div>
                </div>
            </div>

            <div class="container content-section">
                <div class="container-fluid">
                    <div class="row px-3">
                        <div class="col-9 d-flex align-items-center justify-content-start">
                            <div class="product-name m-0 fs-6 fw-bold">${async_result.PRODUCT_NAME}</div>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end ${is_paid ? "" : "d-none"}">
                            <img class="mx-1" src="../assets/img/icons/wishlist-yellow.png" width="20px"><div class="fs-6 fw-bold product-name">5.0</div>
                        </div>
                    </div>
                    <div class="row px-3 ${is_paid ? "" : "d-none"}">
                        <div class="col-8 d-flex align-items-center justify-content-start">
                            <h5 class="product-price fs-6 m-0">Rp ${async_result.PRICE.toLocaleString('en-US')}</h5>
                        </div>
                        <div class="col-4 d-flex align-items-center justify-content-end">
                            <h5 class="product-price small-text">1,1RB Terjual</h5>
                        </div>
                    </div>
                </div>

                <div class="container-fluid mt-2 bg-white small-text fw-bold" style="color: #bbb">
                    <div class="row">
                        <div onclick="changeTab('description');" id="description-tab" class="${post_price ? "col-6" : "col-12"} p-2 text-center font-medium tab-active">
                            Description
                        </div>
                        <div onclick="changeTab('ratings');" id="ratings-tab" class="col-6 p-2 text-center font-medium ${post_price ? "" : "d-none"}">
                            Ratings
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div id="description" class="m-3 prod-details">
                        <div class="col-12 small-text">
                        ${url_div != '' ? url_div + '<br><br>' : ''} 
                        ${description}
                        </div>
                    </div>

                    <div id="ratings" class="m-3 ratings d-none d-flex align-items-center">
                        <div class="col-12">
                            <div class="row my-4">
                                <ul class="list-group list-group-horizontal d-flex align-items-center justify-content-evenly">
                                    <li class="list-group-item">100+ Friendly Seller</li>
                                    <li class="list-group-item">100+ Quick Response</li>
                                    <li class="list-group-item">100+ Quick Delivery</li>
                                    <li class="list-group-item">100+ Great Packaging</li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col">
                                    All reviews
                                    <div class="overflow-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col prod-addtocart ${is_paid ? "" : "d-none"}">
                    <div class="container py-1">
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group counter">
                                    <button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeItemQuantity('modal-item-qty','sub')">-</button>
                                    <input id="modal-item-qty" type="text" pattern="\d*" maxlength="3" class="form-control text-center" placeholder="" value="1" min="1">
                                    <button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeItemQuantity('modal-item-qty','add')">+</button>
                                </div>
                            </div>
                            <div class="col-9">
                                <button id="add-to-cart" class="btn btn-addcart w-100" onclick="addToCartPost('${async_result['CODE']}');">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        this.parent = document.body;
        this.modal = document.getElementById('modal-add-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    static async build(product_code) {
        let async_result = await getProductDetail(product_code);
        return new Addtocart(async_result);
    }

    static async buildPost(post_code) {
        let async_result = await getPostDetail(post_code);
        return new Addtocart(async_result);
    }

    question() {
        this.save_button = document.getElementById('confirm-changes');

        return new Promise((resolve, reject) => {
            this.save_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(true);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Main text
        this.modal.innerHTML = this.html;

        // Let's rock
        $('#modal-addtocart').modal('show');

        // FOR CENTERED POPUP
        $('#modal-addtocart').addClass('d-flex justify-content-center');

        if (window.Android) {
            window.Android.setIsProductModalOpen(true);
        }
        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
            window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
                param1: true
            });
        }
    }

    _destroyModal() {
        $('#modal-addtocart').modal('hide');
        $('#modal-addtocart').removeClass('d-flex justify-content-center');
    }
}


function hideAddToCart() {
    console.log('hide')
    $('#modal-addtocart').modal('hide');
    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
        window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
            param1: false
        });
    }
}

async function showAddModal(product_code) {

    event.preventDefault();

    let add = await Addtocart.build(product_code);
    // let response = await add.question();

}

async function showAddModalPost(post_code) {
    event.preventDefault();

    let add = await Addtocart.buildPost(post_code);
}

function changeItemQuantity(id, mod) {
    if (mod == "add") {
        document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;
    } else {
        if (document.getElementById(id).value > 1) {
            document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
        }
    }
}

function saveToLocalStorage(product, quantity = 1) {

    if (localStorage.getItem("cart") != null) {
        var cart = JSON.parse(localStorage.getItem("cart"));
    } else {
        var cart = [];
    }

    // items
    var item_details = {};
    item_details.store_id = product.SHOP_CODE;
    item_details.itemName = product.PRODUCT_NAME;
    item_details.thumbnail = product.THUMB_ID.split('|')[0];
    item_details.itemPrice = product.PRICE;
    item_details.itemCode = product.CODE;
    item_details.itemQuantity = quantity;
    item_details.maxQty = product.QUANTITY;

    var merchant = cart.find(el => el.merchant_name == product.SHOP_NAME);

    // merchant
    if (merchant != undefined) { // check if the merchant already in the json
        var item = merchant.items.find(el => el.itemName == product.PRODUCT_NAME);

        if (item != undefined) {
            item.itemQuantity = parseInt(item.itemQuantity) + parseInt(item_details.itemQuantity);
        } else {
            merchant.items.push(item_details);
        }

    } else {
        var new_merchant = {};
        new_merchant.merchant_name = product.SHOP_NAME;
        new_merchant.items = [];
        new_merchant.items.push(item_details);

        cart.push(new_merchant);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    // console.log('added successfully');
};

function savePostToLocalStorage(product, quantity = 1) {

    if (localStorage.getItem("cart") != null) {
        var cart = JSON.parse(localStorage.getItem("cart"));
    } else {
        var cart = [];
    }

    // items
    var item_details = {};
    item_details.store_id = product.SHOP_CODE;
    item_details.itemName = product.PRODUCT_NAME;
    item_details.thumbnail = product.THUMB_ID.split('|')[0];
    item_details.itemPrice = product.PRICE;
    item_details.itemCode = product.CODE;
    item_details.itemQuantity = quantity;
    item_details.isPost = 1;

    var merchant = cart.find(el => el.merchant_name == product.SHOP_NAME);

    // merchant
    if (merchant != undefined) { // check if the merchant already in the json
        var item = merchant.items.find(el => el.itemName == product.PRODUCT_NAME);

        if (item != undefined) {
            item.itemQuantity = parseInt(item.itemQuantity) + parseInt(item_details.itemQuantity);
        } else {
            merchant.items.push(item_details);
        }

    } else {
        var new_merchant = {};
        new_merchant.merchant_name = product.SHOP_NAME;
        new_merchant.items = [];
        new_merchant.items.push(item_details);

        cart.push(new_merchant);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    // console.log('added successfully');
}

function addToCart(item) {
    console.log('tab5-collection');
    event.preventDefault();
    let quantity = document.getElementById('modal-item-qty').value;

    //Login-form input values
    let formData = new FormData();
    formData.append("product_id", item);

    // 1. Create a new XMLHttpRequest object
    if (quantity == 0) {
        alert('Please set the quantity!');
    } else {
        let xhr = new XMLHttpRequest();

        // 2. Configure it: GET-request for the URL /article/.../load
        xhr.open('POST', '/gaspol_web/logics/get_product_data');

        // 3. Send the request over the network
        xhr.send(formData);

        // 4. This will be called after the response is received
        xhr.onload = async function () {

            //Request error
            if (xhr.status != 200) { // analyze HTTP status of the response

                alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

                //Request success
            } else { // show the result

                // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
                let response = JSON.parse(xhr.response);
                console.log('response', response);
                saveToLocalStorage(response, quantity);
                if ($('#modal-addtocart').length > 0) {
                    $('#modal-addtocart').modal('hide');
                }
                if ($('#addtocart-success').length > 0) {
                    $('#addtocart-success').modal('show');
                }

            }
        };
    }
};

function addToCartPost(item) {
    console.log('tab5-collection');
    event.preventDefault();
    let quantity = document.getElementById('modal-item-qty').value;

    //Login-form input values
    let formData = new FormData();
    formData.append("product_id", item);

    // 1. Create a new XMLHttpRequest object
    if (quantity == 0) {
        alert('Please set the quantity!');
    } else {
        let xhr = new XMLHttpRequest();

        // 2. Configure it: GET-request for the URL /article/.../load
        xhr.open('POST', '/gaspol_web/logics/get_post_data');

        // 3. Send the request over the network
        xhr.send(formData);

        // 4. This will be called after the response is received
        xhr.onload = async function () {

            //Request error
            if (xhr.status != 200) { // analyze HTTP status of the response

                alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

                //Request success
            } else { // show the result

                // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
                let response = JSON.parse(xhr.response);
                savePostToLocalStorage(response, quantity);
                if ($('#modal-addtocart').length > 0) {
                    $('#modal-addtocart').modal('hide');
                }
                if ($('#addtocart-success').length > 0) {
                    $('#addtocart-success').modal('show');
                }

            }
        };
    }
}

function goBack() {
    if (window.Android) {
        window.Android.closeView();
    } else {
        window.history.back();
    }
}

// FUNCTION REPORT

$(document).on('click', '.dropdown-menu', function (e) {
    e.stopPropagation();
});

function reportContent(product_id, checkIOS = false) {
    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } 
    else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: product_id + '|0',
            param2: 'report_content'
        });
        return;
    }

    $('#modal-addtocart').modal('hide');
    $('#modal-category').modal('show');

    localStorage.setItem("report_post_id", product_id);
    // localStorage.setItem("report_count", report_count);

};

function reportContentSubmit() {

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var f_pin = f_pin;
    var post_id = localStorage.getItem("report_post_id");
    var report_category = $('input[name="report_category"]:checked').val();
    // var count_report = localStorage.getItem("report_count");

    var formData = new FormData();

    formData.append('f_pin', f_pin);
    formData.append('post_id', post_id);
    formData.append('report_category', report_category);
    // formData.append('count_report', count_report);


    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);

            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report Content Berhasil");
                $('#modal-category').modal('hide');
                $('#modal-report-success').modal('show');
                // location.reload();
            } else {
                alert("Report Content Gagal");
            }
        }

    }

    xmlHttp.open("post", "../logics/report_content");
    xmlHttp.send(formData);

};

function reportUser(f_pin_reported, checkIOS = false) {
    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: f_pin_reported,
            param2: 'report_user'
        });
        return;

    }

    $('#modal-addtocart').modal('hide');
    $('#modal-category2').modal('show');

    localStorage.setItem("f_pin_reported", f_pin_reported);

};

function reportUserSubmit() {

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var f_pin_reported = localStorage.getItem("f_pin_reported");;
    var report_category = $('input[name="report_category"]:checked').val();
    var count_report = 1 + 1;

    formData.append('f_pin', f_pin);
    formData.append('f_pin_reported', f_pin_reported);
    formData.append('report_category', report_category);
    formData.append('count_report', count_report);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-category2').modal('hide');
                $('#modal-report-success').modal('show');
                // location.reload();
            } else {
                alert("Report User Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/report_user");
    xmlHttp.send(formData);

};

function blockUser(l_pin, checkIOS = false) {
    if (window.Android) {
        if (window.Android.checkProfile()) {
            f_pin = window.Android.getFPin();
        } else {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: l_pin,
            param2: 'block_user'
        });
        return;

    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var l_pin = l_pin

    console.log("SSS", f_pin);

    formData.append('f_pin', f_pin);
    formData.append('l_pin', l_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');
                $('#modal-block-success').modal('show');

                if (window.Android) {

                    window.Android.blockUser(l_pin, true);

                } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

                    window.webkit.messageHandlers.blockUser.postMessage({
                        param1: l_pin,
                        param2: true
                    });
                    return;

                }
                // location.reload();
            } else {
                alert("Block User Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/block_user");
    xmlHttp.send(formData);
};

function unblockUser(l_pin) {
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var l_pin = l_pin

    console.log("SSS", f_pin);

    formData.append('f_pin', f_pin);
    formData.append('l_pin', l_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');

                if (localStorage.lang == 0) {
                    $('#modal-block-success .modal-body>p').text('You unblocked this user.');
                } else {
                    $('#modal-block-success .modal-body>p').text('Anda telah membuka blokir user ini.');
                }
                $('#modal-block-success').modal('show');

                if (window.Android) {

                    window.Android.blockUser(l_pin, false);

                } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

                    window.webkit.messageHandlers.blockUser.postMessage({
                        param1: l_pin,
                        param2: false
                    });
                    return;

                }
                // location.reload();
            } else {
                alert("Block User Failed");
            }
        }
    }

    xmlHttp.open("post", "../logics/unblock_user");
    xmlHttp.send(formData);
}

function reloadPages() {
    location.reload();
}