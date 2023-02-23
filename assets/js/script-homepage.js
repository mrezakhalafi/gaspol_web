var F_PIN = new URLSearchParams(window.location.search).get('f_pin');
var menu = "";

if (window.Android) {
    window.Android.tabShowHide(true);
  }

if (window.Android) {
    F_PIN = window.Android.getFPin();
}

// FOR AUTOLOAD MENU FROM PREV PAGE

if (menu == 1) {

    ktaMobility();

} else if (menu == 2) {

    ktaPro();

}

ktaMobility();

// PADDING FROM BASIC ACCOUNT

var padding = "0"

if (padding == 1) {

    $('#ktaMobility').css('margin-top', '245px');
    $('#ktaPro').css('margin-top', '245px');
    $('#kis').css('margin-top', '245px');

}

function ktaMobility() {

    $('#ktaMobility').removeClass('d-none');
    $('#ktaMobilityButton').removeClass('d-none');

    $('#ktaPro').addClass('d-none');
    $('#ktaProButton').addClass('d-none');

    $('#kis').addClass('d-none');
    $('#kisButton').addClass('d-none');

    $('#mob-off').addClass('d-none');
    $('#mob-on').removeClass('d-none');

    $('#pro-off').removeClass('d-none');
    $('#pro-on').addClass('d-none');
    $('#kis-off').removeClass('d-none');
    $('#kis-on').addClass('d-none');

}

function ktaPro() {

    $('#ktaMobility').addClass('d-none');
    $('#ktaMobilityButton').addClass('d-none');

    $('#ktaPro').removeClass('d-none');
    $('#ktaProButton').removeClass('d-none');

    $('#kis').addClass('d-none');
    $('#kisButton').addClass('d-none');

    $('#pro-off').addClass('d-none');
    $('#pro-on').removeClass('d-none');

    $('#mob-off').removeClass('d-none');
    $('#mob-on').addClass('d-none');
    $('#kis-off').removeClass('d-none');
    $('#kis-on').addClass('d-none');

}

function kis() {

    $('#ktaMobility').addClass('d-none');
    $('#ktaMobilityButton').addClass('d-none');

    $('#ktaPro').addClass('d-none');
    $('#ktaProButton').addClass('d-none');

    $('#kis').removeClass('d-none');
    $('#kisButton').removeClass('d-none');

    $('#kis-off').addClass('d-none');
    $('#kis-on').removeClass('d-none');

    $('#mob-off').removeClass('d-none');
    $('#mob-on').addClass('d-none');
    $('#pro-off').removeClass('d-none');
    $('#pro-on').addClass('d-none');

}

function upgradeMobility() {

    // window.location.href = "form-kta-mobility?f_pin=".concat(F_PIN)
    if (window.Android) {
        if (window.Android.checkProfile()) {
            window.location.href = "form-kta-mobility?f_pin=".concat(F_PIN)
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: F_PIN,
            param2: 'upgrade_mobility'
        });
        return;
    }

}

function newPro() {

    window.location.href = "form-kta-pronew?f_pin=".concat(F_PIN)

}

function upgradePro() {

    window.location.href = "form-kta-pronew?f_pin=".concat(F_PIN)

}

function registerKIS() {

    window.location.href = "form-kis-new?f_pin=".concat(F_PIN)

}

function viewCardMobility() {

    window.location.href = "card-kta-mobility?f_pin=".concat(F_PIN)

}

function viewCardPro() {

    window.location.href = "card-kta-pronew?f_pin=".concat(F_PIN)

}

function viewKIS() {

    window.location.href = "card-kis?f_pin=".concat(F_PIN)

}

function claimKTA() {

    // window.location.href = "".concat(F_PIN)
    if (window.Android) {
        if (window.Android.checkProfile()) {
            window.Android.openGaspolAccountRecovery();
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: "",
            param2: 'open_gaspol_account_recovery'
        });
        return;
    }

}

function closeAndroid() {

    if (window.Android) {

        // window.Android.finishGaspolForm();
        if (window.Android.finishGaspolForm) {
            window.Android.finishGaspolForm();
        } else {
            window.history.back();
        }

    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

        window.webkit.messageHandlers.finishGaspolForm.postMessage({
            param1: ""
        });
        return;

    } else {

        history.back();

    }
}

function goToPage(id) {
    switch (id) {
        case "imi-partners":
            if (window.Android) {
                window.Android.tabShowHide(false);
            }
            window.location.href = "imi-partner.php?f_pin=" + F_PIN;
            break;
        case "imi-directory":
            window.location.href = "imi_directory.php?f_pin=" + F_PIN;
            break;
        case "kta-benefits":

            if (window.Android) {
                window.Android.tabShowHide(false);
            }

            window.location.href = "imi_benefit.php?f_pin=" + F_PIN +"&m=0";
            break;
        case "era":
            let url = "imi-roadside-assistance.php?f_pin=" + F_PIN;
            if (window.Android) {
                if (window.Android.checkProfile()) {
                    window.location.href = url;
                }
            } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile) {
                window.webkit.messageHandlers.checkProfile.postMessage({
                    param1: url,
                    param2: 'homepage'
                });
                return;
            }
            break;
        case "insurance":
            // window.location.href = "imi_insurance.php?f_pin=" + F_PIN;
            let url_two = "imi_insurance.php?f_pin=" + F_PIN;
            if (window.Android) {
                if (window.Android.checkProfile()) {
                    window.location.href = url_two;
                }
            } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile) {
                window.webkit.messageHandlers.checkProfile.postMessage({
                    param1: url_two,
                    param2: 'homepage'
                });
                return;
            }
            break;
    }
}

function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = seconds / 31536000;

    if (interval > 1) {
        let timeInt = Math.floor(interval);
        let singular = "";
        let plural = "";
        if (localStorage.lang == 0) {
            singular = " year ago";
            plural = " years ago";
        } else {
            singular = " tahun lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " months ago" : timeInt + " month ago";
        if (localStorage.lang == 0) {
            singular = " month ago";
            plural = " months ago";
        } else {
            singular = " bulan lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 86400;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " days ago" : timeInt + " day ago";
        if (localStorage.lang == 0) {
            singular = " day ago";
            plural = " days ago";
        } else {
            singular = " hari lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 3600;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " hours ago" : timeInt + " hour ago";
        if (localStorage.lang == 0) {
            singular = " hour ago";
            plural = " hours ago";
        } else {
            singular = " jam lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 60;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " minutes ago" : timeInt + " minute ago";
        if (localStorage.lang == 0) {
            singular = " minute ago";
            plural = " minutes ago";
        } else {
            singular = " menit lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    let timeInt = Math.floor(seconds);
    if (localStorage.lang == 0) {
        singular = " second ago";
        plural = " seconds ago";
    } else {
        singular = " detik lalu";
        plural = singular;
    }
    let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
    // let timeStr = timeInt > 1 ? timeInt + " seconds ago" : timeInt + " second ago";
    return timeStr;
}

var offset = IS_HOMEPAGE == 1 ? 5 : 0;

let domain = "/gaspol_web/images/";

let activeCategory = "";

function getTotalNews() {

    let formData = new FormData();
    console.log('activeCategory', activeCategory);
    if (activeCategory !== "" || activeCategory !== "all") {
        formData.append('category', activeCategory);
    }

    return new Promise(function (resolve, reject) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                // // console.log(xmlHttp.responseText);
                resolve(xmlHttp.responseText);
            }
        }
        xmlHttp.open("post", "/gaspol_web/logics/get_total_news");
        xmlHttp.send(formData);
    });

}

function openNews(post_id) {
    window.location.href = "news_article.php?post_id=" + post_id;
}

async function getNews(offset = 0, params = "") {
    var formData = new FormData();

    // var index = offset;

    // formData.append('offset', index);
    // params = params + "&offset=" + offset;
    // let par = params + "&offset=" + offset;

    let par = "";
    if (params == "") {
        par = "?offset=" + offset;
    } else {
        par = params + "&offset=" + offset
    }

    // let params = "?offset=" + index;
    // if (activeCategory !== "") {
    //     params += "&category=" + activeCategory;
    // }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            var data = JSON.parse(xmlHttp.responseText);
            console.log(data);

            let news = '';

            if (data.length > 0) {
                $('#empty-news').addClass('d-none');
                data.forEach(d => {
                    let thumbnail = d.FILE_ID.split('|')[0];

                    let title = d.TITLE.length > 50 ? d.TITLE.substr(0, 50) + "..." : d.TITLE;
                    let desc = d.DESCRIPTION.length > 50 ? d.DESCRIPTION.substr(0, 50) + "..." : d.DESCRIPTION;
                    let time = timeSince(parseInt(d.CREATED_DATE));

                    news += `
                    <div class="row single-news my-3 gx-0" onclick="openNews('${d.POST_ID}')">
                        <div class="col-4 news-img-col">
                            <img class="news-img" src="${domain + thumbnail}">
                            <span class="category-tag">${d.CODE}</span>
                        </div>
                        <div class="col-8 p-2">
                            <div class="row">
                                <div class="col-12">
                                    <img src="../assets/img/action_clock.png" style="width: 14px; height: auto;">
                                    <span class="text-secondary small-text">${time}</span>
                                    <span class="small-text" style="margin: 0 3px;">•</span>
                                    <span class="text-secondary small-text">Admin</span>
                                </div>
                            </div>
                            <h6 class="news-title"><strong>${title}</strong></h6>
                            <p class="mb-0 text-secondary news-content">${desc} <a class="news-read-more">${localStorage.lang == 0 ? "Read more" : "Selengkapnya"}</a></p>
                        </div>
                    </div>
                    `;
                })
                $('#news-section').append(news);
                $('#section-load-more').removeClass('d-none')
            } else {

                if ($('.single-news').length == 0) {
                    $('#empty-news').removeClass('d-none');
                }
                $('#section-load-more').addClass('d-none')
            }



        }
    }
    xmlHttp.open("get", "../logics/get_news" + par);
    xmlHttp.send();
}

function loadNews() {
    $('#btn-loadmore').click(async function () {
        let maxNews = await getTotalNews();
        if (offset < maxNews) {
            offset = offset + 5;
            getNews(offset, window.location.search);
        }
    })
}

function selectNewsCategory() {
    $('.category').each(function (e) {
        $(this).click(async function (ev) {
            let fpin = "";
            let dest = window.location.href.split('?')[0];
            if (window.Android) {
                fpin = window.Android.getFPin();
            } else {
                fpin = new URLSearchParams(window.location.search).get('f_pin');
            }
            let params = "?f_pin=" + fpin;
            if ($(this).attr('id') === "all") {
                activeCategory = "all";
            } else {
                activeCategory = $(this).attr('id');
                params += "&category=" + activeCategory;
                dest += params;
            }
            console.log(activeCategory);
            $('#timeline-category .category:not(#' + activeCategory + ')').removeClass('active');
            $(this).addClass('active');
            $('#news-section').html('');
            console.log(params);
            await getNews(0, params);
            window.history.replaceState(null, "", dest);
        })
    })
}

function currentCategory() {
    let urlSearchParams = new URLSearchParams(window.location.search);
    let activeParam = urlSearchParams.get('category');

    if (activeParam == null) {
        activeCategory = "all";
    } else {
        activeCategory = activeParam;
    }

    if ($('#timeline-category .category').length > 0) {
        $('#timeline-category .category#' + activeCategory + '').addClass('active');
        $('#timeline-category .category:not(#' + activeCategory + ')').removeClass('active');
    }
}

window.onload = () => {
    // getNews(0, window.location.search);
    if (IS_HOMEPAGE == 0) {
        getNews(0, window.location.search);
    }
    loadNews();
    currentCategory();
    selectNewsCategory();
}