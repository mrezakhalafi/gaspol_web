let xReply = 1;
let xReffReply = 10;

function commentProduct($productCode, checkIOS = false) {
    // var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0,-9));
    // var isFollowed = false;
    // if (followedStore.includes($storeCode)) {
    //   followedStore = followedStore.filter(p => p !== $storeCode);
    //   $(".follow-icon-" + $storeCode).attr("src", "../assets/img/person_add.png");
    //   if (score > 0) {
    //     $('.follow-counter-store-' + $storeCode).text((score - 1)+" pengikut");
    //   }
    //   isFollowed = false;
    // } else {
    //   followedStore.push($storeCode);
    //   $(".follow-icon-" + $storeCode).attr("src", "../assets/img/ic_nuc_follow3_check.png");
    //   $('.follow-counter-store-' + $storeCode).text((score + 1)+" pengikut");
    //   isFollowed = true;
    // }

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: $productCode,
            param2: 'comment'
        });
        return;
    }

    if (document.getElementById("input").value.trim() != '') {
        $('input:text').click(
            function () {
                $(this).val('');
            });
    } else {
        showAlert("Isi Komentar...");
        return;
    }

    var f_pin = "";
    //TODO send like to backend
    if (window.Android) {
        f_pin = window.Android.getFPin();
        // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    var curTime = (new Date()).getTime();

    var formData = new FormData();

    var discussion_id = curTime.toString() + f_pin;

    formData.append('product_code', $productCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('comment', document.getElementById("input").value.trim());
    formData.append('discussion_id', discussion_id);

    xReply++;

    let is_post = new URLSearchParams(window.location.search).get('is_post');
    // formData.append('is_post', is_post);

    if (!document.getElementById("reply-div").classList.contains("d-none")) {
        var commentId = getCookie("commentId");
        formData.append('reply_id', commentId);
        xReffReply = xReffReply + 10;
    }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == 'Success Comment') {
                if (!document.getElementById("reply-div").classList.contains("d-none")) {
                    deleteAllCookies();
                    $("#reply-div").addClass('d-none');
                    document.getElementById("content-comment").style.marginBottom = "150px";
                }
                $('input#input').val('');
                // location.reload();
                appendComment(formData);
                window.scrollTo(0, document.body.scrollHeight);
                updateScore($productCode, 'comment');
            }
        }
    }
    xmlHttp.open("post", "/gaspol_web/logics/comment_product");
    xmlHttp.send(formData);
    // var formData = new FormData();
    // var curTime = (new Date()).getTime();

    // formData.append('product_code', $productCode);
    // formData.append('f_pin', "25Tefsg");
    // formData.append('last_update', curTime);
    // formData.append('comment', document.getElementById("input").value);

    // let xmlHttp = new XMLHttpRequest();
    // xmlHttp.onreadystatechange = function () {
    //     if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    //         console.log(xmlHttp.responseText);
    //         if(xmlHttp.responseText == 'Success Comment') {
    //             location.reload();
    //         }
    //     }
    // }
    // xmlHttp.open("post", "/gaspol_web/logics/comment_product.php");
    // xmlHttp.send(formData);
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

async function appendComment(formData) {
    let object = {};
    formData.forEach((value, key) => object[key] = value);

    // console.log("comment", object);   

    let date = new Date(parseInt(object.last_update));

    let currentHour = date.getHours();
    let currentMin = date.getMinutes();

    let hour = currentHour >= 10 ? currentHour : "0" + currentHour.toString();
    let minute = currentMin >= 10 ? currentMin : "0" + currentMin.toString();

    let time = hour + ":" + minute;

    let commentId = object.f_pin + object.last_update;

    let profpic = await getProfpic(object.f_pin);
    let username = await getUserName(object.f_pin);

    let balas = "Reply";

    if (localStorage.lang == 0) {
        balas = "Reply";
    } else if (localStorage.lang == 1) {
        balas = "Balas";
    }

    if (!object.hasOwnProperty('reply_id')) {

        let content_comment = document.getElementById('comment-section');

        let comment_html = `
        <div class="row mx-0 comments" id="${commentId}">
            <div class="commentId" style="display: none;">${commentId}</div>
            <div class="fPin" style="display: none;">${object.f_pin}</div>
            <div class="col-2">
            <img onclick="showProfile('${object.f_pin}')" id="user-thumb-new-${xReply}" alt="Profile Photo" class="rounded-circle my-3 profpic" src="${profpic}">
            </div>
            <div class="col-7 px-0 text-break">
                <div style="font-weight: bold; font-size:13px;" class="mt-3 mb-1 mr-3">
                    <span id="user-name-new-${xReply}">${username} </span>
                </div>
                <div class="my-1">                      
                <span style="font-weight: 300; font-size:13px;"> ${object.comment}</span>
                </div>
            </div>
            <div class="col-3 text-right">
                <div class="mt-3 mb-1" style=" color: grey; font-size:11px;">
                    ${time}
                </div>
                <div class="my-1">
                    <span style="font-weight: 300; " data-translate="comment-2" onclick="onReply(true,'user-name-new-${xReply}','${commentId}');">${balas}</span>
                </div>
            </div>
        </div>
        `;

        content_comment.innerHTML += comment_html;
    } else {

        let parent_id = object.reply_id;

        let comment_html = `
        <div class="row comments cmt-reply" id="${commentId}" style="width:100%;">
        <div class="commentId" style="display: none;">${commentId}</div>
        <div class="fPin" style="display: none;">${object.f_pin}</div>
        <div class="col-2">
          <img onclick="showProfile('${object.f_pin}')" id="user-thumb-reff-new-${xReffReply}" alt="Profile Photo" class="rounded-circle my-3 profpic"  src="${profpic}">
        </div>
        <div class="col-7 px-0 text-break">
                <div style="font-weight: bold; font-size:13px;" class="mt-3 mb-1 mr-3">
                    <span id="user-name-new-${xReffReply}">${username} </span>
                </div>
                <div class="my-1">                      
                <span style="font-weight: 300; font-size:13px;"> ${object.comment}</span>
                </div>
            </div>
            <div class="col-3 text-right">
                <div class="mt-3 mb-1" style="color: grey; font-size:11px;">
                    ${time}
                </div>
                <div class="my-1">
                    <span style="font-weight: 300;" data-translate="comment-2" onclick="onReply(true,'user-name-new-${xReffReply}','${commentId}');">${balas}</span>
                </div>
            </div>
      </div>
        `;

        console.log("parent", parent_id);
        console.log("this", commentId);

        let isLv1 = !$('.comments#' + parent_id).hasClass('cmt-reply');

        if (isLv1) {
            $('.comments#' + parent_id).append(comment_html);
        } else {
            $('.comments#' + parent_id).after(comment_html);
        }
        // if (isLv1) {
        //     $('.comments-tree#cmt-tree-' + parent_id).append(comment_html);
        // } else {
        //     let newTree = `
        //     <div class="comment-tree" id="cmt-tree-${commentId}">
        //     ${comment_html}
        //     </div>
        //     `;
        // }

    }


    enableDelete();
    // 
    if (!object.hasOwnProperty('reply_id')) {
        document.getElementById(commentId).scrollIntoView({
            behavior: "smooth"
        });
    } else {
        console.log("scrollto", commentId);
        var element = document.getElementById(commentId);
        var headerOffset = 45;
        var elementPosition = element.getBoundingClientRect().top;
        var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
    }
}

function openDelete(commentId) {
    return function curried_func(event) {
        event.preventDefault();
        event.stopPropagation();
        showSuccessModal(commentId, console.log(""));
    }
}

function enableDelete() {
    // document.querySelectorAll(".comments").forEach(item => {
    //     let commentId = item.querySelector(".commentId").innerText;
    //     let fPinContent = item.querySelector(".fPin").innerText;
    //     console.log('loop', commentId);
    //     var f_pin = '';
    //     try {
    //         if (window.Android) {
    //             f_pin = window.Android.getFPin();
    //         }
    //     } catch (err) {}
    //     var f_pin = "028a5119b2";
    //     if (fPinContent == f_pin) {
    //         // item.removeEventListener('contextmenu', openDelete);
    //         // item.addEventListener('contextmenu', openDelete(commentId));
    //         item.addEventListener('contextmenu', event => {
    //             // openDelete(event, commentId);
    //             console.log('target', commentId);
    //             // if (event.target.id == commentId) {
    //                 event.preventDefault();
    //                 event.stopPropagation();
    //                 showSuccessModal(commentId, console.log(""));
    //             // }
    //         }, false)
    //     } else {
    //         return;
    //     }
    // })
    $('.first-comment').each(function () {
        if (!$(this).hasClass('is-deleted')) {
            let commentId = $(this).siblings('.commentId').text();
            let fPinContent = $(this).siblings('.fPin').text();
            var f_pin = '';
            try {
                // if (window.Android) {
                //     f_pin = window.Android.getFPin();
                // } else {
                f_pin = new URLSearchParams(window.location.search).get('f_pin');
                // }
            } catch (err) {

            }
            // var f_pin = "028a5119b2";
            if (fPinContent == f_pin) {
                $(this).unbind('contextmenu');
                $(this).contextmenu(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showSuccessModal(commentId, console.log(""));
                })
            } else {
                return;
            }
        }
    })

    $('.comments').each(function () {
        if (!$(this).hasClass('is-deleted')) {
            let commentId = $(this).find('.commentId').text();
            let fPinContent = $(this).find('.fPin').text();
            var f_pin = '';
            try {
                // if (window.Android) {
                //     f_pin = window.Android.getFPin();
                // } else {
                f_pin = new URLSearchParams(window.location.search).get('f_pin');
                // }
            } catch (err) {

            }
            // var f_pin = "028a5119b2";
            if (fPinContent == f_pin) {
                $(this).unbind('contextmenu');
                $(this).contextmenu(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showSuccessModal(commentId, console.log(""));
                })
            } else {
                return;
            }
        }
    })


}

enableDelete();

// function enableDelete(fpin, commentId) {
//     // let item = document.querySelector(".comments#" + commentId);
//     var f_pin = '';
//     try {
//         if (window.Android) {
//             f_pin = window.Android.getFPin();
//         } else {
//             f_pin = new URLSearchParams(window.location.search).get("f_pin");
//         }
//     } catch (err) {}
//     // var f_pin = "02b3c7f2db";
//     if (fpin == f_pin) {
//         document.querySelector(".comments#" + commentId).addEventListener('contextmenu', event => {
//             event.preventDefault();
//             showSuccessModal(commentId, console.log(""));
//         }, false)
//     } else {
//         return;
//     }
// }

async function showSuccessModal(commentId, method) {
    event.preventDefault();

    $('body').css('overflow', 'hidden');
    this.myModal = new SuccessModal(commentId, method);

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

class SuccessModal {

    constructor(commentId, method) {
        if (localStorage.lang == 0) {
            this.modalTitle = "Are you sure you want to delete this comment?";
            this.acceptText = "Delete";
            this.cancelText = "Cancel";
        } else {
            this.modalTitle = "Yakin ingin menghapus komentar ini?";
            this.acceptText = "Hapus";
            this.cancelText = "Batal";
        }

        this.parent = document.body;
        this.commentId = commentId;
        this.method = method;

        this.modal = undefined;
        this.acceptButton = undefined;
        this.cancelButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.acceptButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.acceptButton.addEventListener("click", () => {
                var formData = new FormData();

                formData.append('comment_id', this.commentId);
                let is_post = new URLSearchParams(window.location.search).get('is_post');
                formData.append('is_post', is_post);
                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function () {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                        if (xmlHttp.responseText == 'Success Delete Comment') {
                            location.reload();
                        }
                    }
                }
                xmlHttp.open("post", "/gaspol_web/logics/delete_comment");
                xmlHttp.send(formData);
            });

            this.cancelButton.addEventListener("click", () => {
                this._destroyModal();
                $('body').css('overflow', 'auto');
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.setAttribute("style", "z-index: 1031;");
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.style.marginLeft = "5px";
        titleText.style.marginRight = "5px";
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // // Main text
        // const text = document.createElement('span');
        // text.setAttribute("id", "payment-form");
        // text.classList.add('simple-modal-text');
        // text.innerHTML = this.status;
        // window.appendChild(text);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.acceptButton = document.createElement('button');
        this.acceptButton.type = "button";
        this.acceptButton.classList.add('simple-modal-button-green');
        this.acceptButton.textContent = this.acceptText;
        buttonGroup.appendChild(this.acceptButton);

        // Cancel button
        this.cancelButton = document.createElement('button');
        this.cancelButton.type = "button";
        this.cancelButton.classList.add('simple-modal-button-red');
        this.cancelButton.textContent = this.cancelText;
        buttonGroup.appendChild(this.cancelButton);

        // Let's rock
        this.parent.appendChild(this.modal);
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

function onReply(condition, name, commentId) {
    if (condition) {
        $("#input").focus();
        finalName = document.getElementById(name).innerHTML;

        if (localStorage.lang == 0) {
            document.getElementById("content-reply").innerHTML = "Reply to " + finalName;
        } else if (localStorage.lang == 1) {
            document.getElementById("content-reply").innerHTML = "Balas ke " + finalName;
        }

        document.getElementById("content-comment").style.marginBottom = "200px";
        document.cookie = "commentId=" + commentId;
        $("#reply-div").removeClass('d-none');

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        sleep(500).then(() => {
            window.scrollTo(0, document.body.scrollHeight);
        });
    } else {
        deleteAllCookies();
        $("#reply-div").addClass('d-none');
        document.getElementById("content-comment").style.marginBottom = "200px";
    }
    window.scrollTo(0, document.body.scrollHeight);
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}

function goBack() {
    if (window.Android) {
        window.Android.closeView();
        // window.history.back();
    } else {
        window.history.back();
    }
}

function showAlert(word) {
    if (window.Android) {
        window.Android.showAlert(word);
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.showAlert) {
        window.webkit.messageHandlers.showAlert.postMessage({
            param1: word,
        });
        return;
    }
}

function hideProdDesc() {
    $(".prod-desc").each(function () {
        if ($(this).text().length > 50) {
            $(this).addClass("mb-3");
            $(this).toggleClass("truncate");
            let toggleText = document.createElement("span");
            toggleText.innerHTML = localStorage.lang == 1 ? "Selengkapnya..." : "Read more...";
            // toggleText.href = "#";
            toggleText.style.color = "#999999";
            toggleText.classList.add("truncate-read-more");
            $(this).parent().append(toggleText);
        }
    });
}

function toggleProdDesc() {
    $(".truncate-read-more").each(function () {
        $(this).click(function () {
            console.log("read more");
            $(this).parent().find(".prod-desc").toggleClass("truncate");
            if ($(this).text() == "Selengkapnya..." || $(this).text() == "Read more...") {
                $(this).text(localStorage.lang == 1 ? "Sembunyikan" : "Hide");
            } else {
                $(this).text(localStorage.lang == 1 ? "Selengkapnya..." : "Read more...");
            }
        });
    });
}

function onFocusInput() {
    if (window.Android) {
        try {
            window.Android.onFocusInput();
        } catch (e) {

        }
    }
}

function checkSendButton() {
    $('#input').keyup(function () {
        if ($(this).val().trim() == "") {
            $('#buttond_send').attr('src', '../assets/img/send.png');
        } else {
            $('#buttond_send').attr('src', '../assets/img/send_active.png');
        }
    })
}

function followStore($productCode) {
    var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0, -9));

    var isFollowed = $('#edt-del-' + $productCode).attr('data-isfollowed');

    //TODO send like to backend
    let f_pin = ''
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    var curTime = (new Date()).getTime();

    var formData = new FormData();

    formData.append('post_id', $productCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_follow', isFollowed);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            // updateScoreShop($storeCode);

            let state = xmlHttp.responseText.split('|')[0];
            let $storeCode = xmlHttp.responseText.split('|')[1];

            if (state == 'unfollow') {
                // followedStore = followedStore.filter(p => p !== $storeCode);
                // isFollowed = '0';
                $('#edt-del-' + $productCode).attr('data-isfollowed', '0')
            } else {
                // followedStore.push($storeCode);
                // isFollowed = true;
                $('#edt-del-' + $productCode).attr('data-isfollowed', '1')
            }
        }
    }
    xmlHttp.open("post", "/gaspol_web/logics/follow_store");
    xmlHttp.send(formData);
}

function bookmarkPost($productCode) {
    // var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0, -9));

    var isBookmarked = $('#edt-del-' + $productCode).attr('data-isbookmarked');

    //TODO send like to backend
    let f_pin = ''
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    var curTime = (new Date()).getTime();

    var formData = new FormData();

    formData.append('post_id', $productCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_bookmark', isBookmarked);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            // updateScoreShop($storeCode);

            let state = xmlHttp.responseText.split('|')[0];
            let $storeCode = xmlHttp.responseText.split('|')[1];

            if (state == 'unbookmark') {
                // followedStore = followedStore.filter(p => p !== $storeCode);
                // isFollowed = '0';
                $('#edt-del-' + $productCode).attr('data-isbookmarked', '0')
            } else {
                // followedStore.push($storeCode);
                // isFollowed = true;
                $('#edt-del-' + $productCode).attr('data-isbookmarked', '1')
            }
        }
    }
    xmlHttp.open("post", "/gaspol_web/logics/bookmark_post");
    xmlHttp.send(formData);
}

function reportContent(product_id, checkIOS = false) {
    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
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

function reloadPages() {
    location.reload();
}

function openPostContextMenu(postId, isFollowed, isBookmarked, isSelfPost = false) {
    let isFollow = isFollowed === '1';
    let isBookmark = isBookmarked === '1';
    console.log('postId', postId);
    console.log('isBookmarked', isBookmark);
    console.log('isFollowed', isFollow);

    if (window.Android) {
        if (window.Android.checkProfile()) {
            window.Android.openPostContextMenu(postId, isFollow, isBookmark, isSelfPost);
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.openPostContextMenu) {
        window.webkit.messageHandlers.openPostContextMenu.postMessage({
            post_id: postId,
            is_followed: isFollow,
            is_bookmarked: isBookmark,
            is_selfpost: isSelfPost
        });
    }
}

function enablePostContextMenu() {
    $('a.post-status').click(function (e) {
        e.stopPropagation();
        let postId = $(this).attr('data-postid');
        let isFollowed = $(this).attr('data-isfollowed');
        let isBookmarked = $(this).attr('data-isbookmarked');

        let f_pin = "";

        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get('f_pin');
        }

        let isSelfPost = $(this).attr('data-createdby') == f_pin;

        openPostContextMenu(postId, isFollowed, isBookmarked, isSelfPost);
    })
}

var likedPost = [];

function getLikedProducts() {
    var f_pin = ""
    if (window.Android) {
        f_pin = window.Android.getFPin();

    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    if (f_pin != "") {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                let likeData = JSON.parse(xmlHttp.responseText);
                likeData.forEach(product => {
                    var productCode = product.PRODUCT_CODE;
                    likedPost.push(productCode);
                    $("#like-" + productCode).attr("src", "../assets/img/jim_likes_red.png");
                });
                console.log('get likes', likedPost);
            }
        }
        xmlHttp.open("get", "/gaspol_web/logics/fetch_products_liked?f_pin=" + f_pin);
        xmlHttp.send();
    }
}

function likeProduct($productCode, checkIOS = false) {
    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: $productCode,
            param2: 'like'
        });
        return;
    }

    var score = parseInt($('#like-counter-' + $productCode).text());
    var isLiked = false;
    if (likedPost.includes($productCode)) {
        likedPost = likedPost.filter(p => p !== $productCode);
        $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.png");
        if (score > 0) {
            $('#like-counter-' + $productCode).text(score - 1);
        }
        isLiked = false;
    } else {
        likedPost.push($productCode);
        $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.png");
        $('#like-counter-' + $productCode).text(score + 1);
        isLiked = true;
    }

    let f_pin = '';

    //TODO send like to backend
    if (window.Android) {
        f_pin = window.Android.getFPin();


    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    var curTime = (new Date()).getTime();
    var formData = new FormData();

    formData.append('product_code', $productCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_like', (isLiked ? 1 : 0));

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            // // console.log(xmlHttp.responseText);
            // updateScore($productCode);

        }
    }
    xmlHttp.open("post", "/gaspol_web/logics/like_product");
    xmlHttp.send(formData);
}

let countVideoPlaying = 0;

let carouselIsPaused = true;

function videoReplayOnEnd() {
    $(".myvid").each(function (i, obj) {
        $(this).on('ended', function () {
            // // console.log("video ended");
            let $videoPlayButton = $(this).parent().find(".video-play");
            $videoPlayButton.removeClass("d-none");
        })
    })
}

function checkVideoViewport() {
    var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
    if (document.activeElement.className.match(pattern)) {
        return;
    }
    let videoWrapElements = document.querySelectorAll('.timeline-image .video-wrap>video, .timeline-image .carousel-item.active .video-wrap>video');
    let videoWrapArr = [].slice.call(videoWrapElements);
    let carouselElements = document.querySelectorAll('.timeline-image .carousel');
    let carouselArr = [].slice.call(carouselElements);

    let allElementsArr = videoWrapArr.concat(carouselArr);
    // console.log('ele', allElementsArr);
    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // console.log(entry.target);
            // console.log('ho', countVideoPlaying)
            if (entry.intersectionRatio >= 0.8 && $('#modal-addtocart').not('.show') && countVideoPlaying === 0) {
                playElement(entry.target);

            } else if (entry.intersectionRatio < 0.8) {
                pauseElement(entry.target);
            }
        });
    }, {
        threshold: 1
    });



    async function playElement(el) {

        if (el.id.includes('video') && el.paused) {
            let vidPromise = el.play();
            countVideoPlaying = 1;
        }
    }

    function pauseElement(el) {

        if (el.id.includes('video') && !el.paused) {
            el.pause();
            countVideoPlaying = 0;
        }
    }

    function pauseCarousel(cr) {
        cr.pause();
    }

    function startCarousel(cr) {
        cr.cycle();
    }

    allElementsArr.forEach((elements) => {
        observer.observe(elements);
    });

    // [].forEach.call(carouselElements, (carousel) => {
    //   // console.log('loop');

    //   observer.observe(carousel);
    // })
    videoReplayOnEnd();
    // playVid();
    // }
}

function pauseAll() {
    $('.carousel-item video, .timeline-image video').each(function () {
        $(this).get(0).pause();
    })
    visibleCarousel.clear();
    $('.carousel').each(function () {
        $(this).carousel('pause');
    })
    startPause = new Date().getTime();
    console.log(startPause);
    hideCategoryModal();
}

function resumeAll() {
    console.log('resume');
    countVideoPlaying = 0;
    let curTime = new Date().getTime();
    if (window.Android && typeof window.Android.checkFeatureAccessSilent === "function" && !window.Android.checkFeatureAccessSilent("new_post")) {
        $('#to-new-post').addClass('d-none');
    } else {
        $('#to-new-post').removeClass('d-none');
    }
    console.log('startpause', startPause);
    if (startPause > 0) {
        console.log('time', curTime - startPause);
        if (curTime - startPause >= 180000) {
            refreshClean();
        }
    }
    checkVideoViewport();
    checkVideoCarousel();
    // checkcarousel();
    // // updatecounter();
    fetchNotifCount();

}

function toggleVideoMute(code) {
    console.log(code);
    let videoWrap = document.getElementById(code);
    if (videoWrap) {
        let videoElement = videoWrap.querySelector('video');
        // console.log(videoElement);

        console.log('#' + code + ' .video-sound img');
        let muteIcon = document.querySelector('#' + code + ' .video-sound img');

        if (videoElement.muted) {
            videoElement.muted = false;
            muteIcon.src = "../assets/img/video_unmute.png";
        } else {
            videoElement.muted = true;
            muteIcon.src = "../assets/img/video_mute.png";
        }

        console.log(code + ' ' + videoElement.muted);
    }
}

function editPost(code) {
    if (window.Android) {
        let f_pin = window.Android.getFPin();

        window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
    } else {
        let f_pin = new URLSearchParams(window.location.search).get("f_pin");

        window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
    }
}

function deletePost(post_id) {
    var xmlHttp = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('post_id', post_id);
    formData.append('ec_date', new Date().getTime());
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == "Success") {
                console.log(post_id + ' deleted');
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>Post deleted.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Postingan telah dihapus.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                    console.log('row', $('.product-row').length);
                    if ($('.product-row').length === 1) {
                        let f_pin = new URLSearchParams(window.location.search).get('f_pin');
                        let activeCategories = localStorage.getItem('active_content_category');

                        window.location.href = 'tab1-main.php?f_pin=' + f_pin + '&filter=' + activeCategories;
                    } else if ($('.product-row').length > 1) {
                        // let activeCategories = localStorage.getItem('active_content_category');
                        // let bTheme = '';
                        // if (activeCategories !== null && activeCategories.split('-').length === 1) {
                        //     bTheme = activeCategories.split('-')[0];
                        // }
                        window.location.reload();
                    }
                });
                $('#delete-post-info').modal('toggle');
            } else {
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>An error occured while deleting post. Please refresh and try again.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Error saat menghapus post. Silahkan muat ulang dan coba lagi.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
                //   window.location.reload();
                // });
                $('#delete-post-info').modal('toggle');
            }
        }
    }
    xmlHttp.open("POST", "/gaspol_web/logics/delete_post");
    xmlHttp.send(formData);
}


$(function () {
    // hideProdDesc();
    checkVideoViewport();
    getLikedProducts();
    toggleProdDesc();
    checkSendButton();
    enablePostContextMenu();
    toggleVideoMute();
    // $(".prod-desc").readmore({
    //     moreLink: '<a href="#">Selengkapnya...</a>',
    //     lessLink: '<a href="#">Sembunyikan</a>',
    //     collapsedHeight: 22
    // });
})