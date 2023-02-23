var data = [];
var dataFiltered = [];

let defaultCategory = '';

let limit = 21;
let offset = 0;
let busy = false;
let r = Math.floor(Math.random() * (2 - 1 + 1)) + 1; // grid random kiri atau kanan

var grid_stack = GridStack.init({
  float: false,
  disableOneColumnMode: true,
  column: 3,
  margin: 2.5,
  animate: false,
});

// var ua = window.navigator.userAgent;
// var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
// var webkit = !!ua.match(/WebKit/i);
// var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
// var palioBrowser = !!ua.match(/PalioBrowser/i);
// var isChrome = !!ua.match(/Chrome/i);

var big_list = new Map();

function isBig($position) {
  var div = Math.floor($position / 9);
  if (big_list.has(div)) {
    return (big_list.get(div) == $position);
  } else {
    var pos = (div * 9) + Math.floor(Math.random() * 8);
    big_list.set(div, pos);
    return (pos == $position);
  }
}

var STORE_ID = "";

function checkStory(checkIOS = false) {
  let f_pin = window.Android ? window.Android.getFPin() : new URLSearchParams(window.location.search).get('f_pin');
  let openMenu = 'menu_membership?f_pin=' + f_pin;
  if (window.Android) {
      if (window.Android.checkProfile()) {
          window.location.href = openMenu;
      }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
      window.webkit.messageHandlers.checkProfile.postMessage({
          param1: openMenu,
          param2: 'gif'
      });
      return;
  } else {
      window.location.href = openMenu;
  }
}

function onClickHasStory() {
  $(".has-story").click(function (e) {
    e.preventDefault();
    busy = true;
    if (this.id == "all-store") {
      STORE_ID = "";
      let activeCategories = localStorage.getItem('active_content_category');
      let bTheme = '';
      if (activeCategories !== null && activeCategories.split('-').length === 1) {
        bTheme = activeCategories.split('-')[0];
      }
      buttonTheme(bTheme);
      // searchFilter();
    } else if (this.id == 'store-0246a901c4') {

      checkStory();
    } else {
      let prev_STORE_ID = STORE_ID;
      STORE_ID = this.id.split("-")[1];
      // buttonTheme(STORE_ID);
      // fetchProductCount(STORE_ID, prev_STORE_ID);
    }
    searchFilter();
  });
}

// gapi.load("client");

// to randomized array js
function shuffle(array) {
  var currentIndex = array.length,
    randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    // And swap it with the current element.
    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex], array[currentIndex]
    ];
  }

  return array;
}

var currentSort = 'popular';

// to get merchants that have products
function nonEmptyMerchants() {
  let xhr = new XMLHttpRequest();
  xhr.open('GET', '/gaspol_web/logics/non_empty_merchants.php');
  xhr.responseType = 'json';
  xhr.send();
  xhr.onload = function () {
    if (xhr.status != 200) { // analyze HTTP status of the response
      console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

    } else { // show the result
      let responseObj = xhr.response; // array
      localStorage.setItem("non_empty_merchant", JSON.stringify(responseObj));
      // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
    }
  };

  xhr.onerror = function () {
    // // console.log("Request failed");
  };
}
nonEmptyMerchants();

// to shuffle product order in tab 1
function shuffleMerchants(sort_by) {
  let finalArr = [];
  if (sort_by == 'popular') {
    let all_merchants = dataFiltered; // array of all merchant
    let non_empty_merchants = JSON.parse(localStorage.getItem('non_empty_merchant')); // array of merchant code (that has products)

    let non_empty = [];
    let empty = [];
    all_merchants.forEach(merchant => {
      if (non_empty_merchants.includes(merchant.CODE)) {
        non_empty.push(merchant);
      } else {
        empty.push(merchant);
      }
    });

    finalArr = shuffle(non_empty).concat(shuffle(empty));
  } else if (sort_by == 'date') {
    let all_merchants = dataFiltered; // array of all merchant


    finalArr = all_merchants.sort((a, b) => (a.CREATED_DATE > b.CREATED_DATE) ? -1 : ((b.CREATED_DATE > a.CREATED_DATE) ? 1 : 0));
  } else if (sort_by == 'follower') {
    let all_merchants = dataFiltered; // array of all merchant

    finalArr = all_merchants.sort((a, b) => (a.TOTAL_FOLLOWER > b.TOTAL_FOLLOWER) ? -1 : ((b.TOTAL_FOLLOWER > a.TOTAL_FOLLOWER) ? 1 : 0))
  }

  // to make the non empty appear based on score, remove shuffle from non-empty
  // return non_empty.concat(shuffle(empty)) // shuffling only merchant with no products
  // return finalArr;
  return new Promise(function (resolve, reject) {
    resolve(finalArr);
  });
}

function gridCheck(arr, id) {
  const found = arr.some(el => el.id === id);
  return found;
}

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

// function ext(url) {
//   return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."));
// }

function ext(url) {
  return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
}

var enableFollow = 0;
var showLinkless = 2;
var f_pin = '';
var gridElements = [];
var carouselIntervalId = 0;
let defaultSort = 'popular';
let currentShuffle = [];
var fillGridStack = async function ($grid, sort_by, lim, off) {

  // // console.log('fillgridstack');

  gridElements = [];
  big_list.clear();
  var baseDelay = 5000; //(Math.max(5, dataFiltered.length) * 1000) / 2;

  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
  var $shop_blacklist = ["17b0ae770cd"]; //isi manual
  var ext_re = /(?:\.([^.]+))?$/;

  let f_pin = "";

  if (window.Android) {
    try {
      f_pin = window.Android.getFPin();
    } catch (err) {
      console.log(err);
    }
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  // dataFiltered = shuffleArray(dataFiltered);



  dataFiltered.slice(off, lim + 1).forEach((element, idx) => {
    // // console.log(idx);
    // // console.log(element.CODE);
    var size;
    if (r == 1) {
      size = ((idx % 12 == 0 || idx % 12 == 7) ? 2 : 1);
    } else {
      size = ((idx % 12 == 1 || idx % 12 == 6) ? 2 : 1);
    }

    // // console.log(idx + ', ' + size);


    var imageDivs = '';
    var imageArray = productImageMap.get(element.CODE);
    // var delay = Math.floor(Math.random() * (baseDelay)) + 5000;

    var merchantWebURL = ''
    if (element.LINK == null || element.LINK == '' || element.LINK == undefined || element.BE_ID != null) {
      // merchantWebURL = '/gaspol_web/pages/profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin;
      merchantWebURL = 'tab3-profile.php?store_id=' + element.F_PIN + '&f_pin=' + f_pin;
    } else if (!element.LINK.startsWith("http")) {
      merchantWebURL = "https://" + element.LINK;
    } else {
      merchantWebURL = element.LINK;
    }

    let thumb_id = element.THUMB;

    let domain = '';

    if (imageArray) {
      //       console.log('here');
      let contents = '';
      if (imageArray.length > 1) {
        imageArray.forEach((image, jIdx) => {
          var imgElem = '';
          var fileExt = ext_re.exec(image)[1].trim();
          if ($image_type_arr.includes(fileExt)) {
            imgElem = '<img class="content-image" src="' + domain + '/gaspol_web/images/' + image.trim() + '"/>'
          } else if ($video_type_arr.includes(fileExt)) {
            var isAutoplay = size == 2 ? 'autoplay' : '';
            imgElem = '<video muted playsinline loop class="content-image" id="video-' + element.CODE + '"><source src="' + domain + '/gaspol_web/images/' + image.trim() + '#t=1" type="video/' + fileExt + '"></video>';
          }
          if (imgElem) {
            if (jIdx == 0) {
              imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
            } else {
              imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
            }
          }
        });
        contents = '<div id="store-carousel-' + element.CODE + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
          //  : (''))
          '<div class="carousel-inner">' +
          imageDivs +
          '</div>' +
          '</div>';
      } else {
        var imgElem = '';
        var fileExt = ext_re.exec(imageArray[0])[1].trim();
        if ($image_type_arr.includes(fileExt)) {
          imgElem = '<img class="content-image" src="' + domain + '/gaspol_web/images/' + imageArray[0].trim() + '"/>'
        } else if ($video_type_arr.includes(fileExt)) {
          var isAutoplay = size == 2 ? 'autoplay' : '';
          imgElem = '<video muted playsinline loop class="content-image" id="video-' + element.CODE + '"><source src="' + domain + '/gaspol_web/images/' + imageArray[0].trim() + '#t=1" type="video/' + fileExt + '"></video>';
        }
        contents = imgElem;
      }
      var interval = size == 2 ? '3000' : 'false';
      var isBig = size == 2 ? 'big-grid' : 'small-grid';
      var computed =
        // '<div class="grid-stack-item">'+
        // '<div class="grid-stack-item-content">' +
        // '<div class="inner" onclick="openStore(\'' + element.CODE + '\',\'' + merchantWebURL + '\');" ' +
        // ' oncontextmenu="openStoreMenu(\'' + element.CODE + '\',\'' + element.NAME + '\')"' +
        // '>' +
        // '<a href="javascript:openStore(\'' + element.CODE + '\',\'' + merchantWebURL + '\');">' +
        '<a href="' + merchantWebURL + '" id="' + element.CODE + '">' +
        '<div id="grid-' + element.CODE + '" class="inner ' + isBig + '"' +
        ((element.LINK == null || element.LINK == '' || element.LINK == undefined || element.BE_ID != null) ? "" :
          ' oncontextmenu="openStoreMenu(\'' + element.CODE + '\',\'' + element.NAME + '\')"') +
        '>' +
        // '<img id="store-image-' + element.CODE + '" class="content-image" src="' + element.THUMB_ID + '" />' +
        contents +
        // '<div class="icon-merchant"><img src="/gaspol_web/assets/img/icons/Verified-(Black).png" ' + (element.BE_ID != null ? '' : ' class="d-none"') + '/><div class="merchant-name">' + element.NAME + '</div></div>' +

        '<div class="viewer-count" id="visitor-' + element.CODE + '">' +
        '<img src="/nexilis/assets/img/jim_likes_red.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
        '<span class="visitor-amt" style="font-size:11px; color:white">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.TOTAL_LIKES) +
        '</span>' +
        '<img src="/nexilis/assets/img/jim_comments_blue.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
        '<span class="follower-amt" style="font-size:11px; color:white">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.TOTAL_COMMENTS) +
        '</span>' +
        '</div>' +
        '</div>' +
        '</a>'
      //  +
      // '</div></div>' 
      ;
      // grid_stack.addWidget({
      //   w: size,
      //   h: size,
      //   content: computed
      // });
      if (!gridCheck(gridElements, element.CODE)) {
        gridElements.push({
          id: element.CODE,
          minW: size,
          minH: size,
          maxW: size,
          maxH: size,
          content: computed
        });
      }
      if (size == 2 && imageArray.length > 1) {
        //   $('#store-carousel-' + element.CODE).carousel({
        //     ride: 'carousel',
        //     interval: delay
        //   });
        carouselList.push('#store-carousel-' + element.CODE + '');
      }
    }
  });


  // grid_stack.batchUpdate();

  // console.log(gridElements);

  grid_stack.removeAll(true);
  grid_stack.load(gridElements, true);
  // grid_stack.commit();
  if (dataFiltered.length == 0) {
    $('#no-stores').removeClass('d-none');
  } else {
    $('#no-stores').addClass('d-none');
  }
  $('.carousel').each(function () {
    $(this).carousel();
    // setTimeout(() => {
    //   $(this).carousel('next');
    // }, Math.floor(Math.random() * (1000)) + 1000);
  });
  $('#stack-top').css('display', 'none');
  $('.overlay').addClass('d-none');
  checkVideoViewport();
  // checkVideoCarousel();
  checkCarousel();
  correctVideoCrop();
  correctImageCrop();
  addToCartModal();
  attachLongPress();
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  // carouselIntervalId = setInterval(function () {
  //   carouselNext();
  // }, 3000);

  $('#loading').addClass('d-none');
};

function fillGridWidgets(grid, sort_by, lim, off) {
  let start = off;
  let end = off + lim;

  // console.log('next page');

  var baseDelay = 5000; //(Math.max(5, dataFiltered.length) * 1000) / 2;
  // console.table(dataFiltered);
  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
  var $shop_blacklist = ["17b0ae770cd"]; //isi manual
  var ext_re = /(?:\.([^.]+))?$/;

  let f_pin = "";
  if (window.Android) {
    try {
      f_pin = window.Android.getFPin();
    } catch (err) {
      // // console.log(err);
    }
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  let batch = dataFiltered.slice(start, end);

  batch.forEach((element, idx) => {
    if ($shop_blacklist.includes(element.CODE)) {
      return;
    }

    var size;
    if (r == 0) {
      size = ((idx % 12 == 0 || idx % 12 == 7) ? 2 : 1);
    } else {
      size = ((idx % 12 == 1 || idx % 12 == 6) ? 2 : 1);
    }

    // // console.log(idx + ', '  + size);

    var imageDivs = '';
    var imageArray = productImageMap.get(element.CODE);
    // var delay = Math.floor(Math.random() * (baseDelay)) + 5000;

    if (!imageArray || imageArray.length == 0) {
      imageArray = [];
      let shop_thumb = element.THUMB_ID;
      if (!shop_thumb.startsWith("http")) {
        let shoppic = "/gaspol_web/images/" + shop_thumb;
        imageArray.push(shoppic);
      } else {
        imageArray.push(shop_thumb);
      }
    }

    var merchantWebURL = ''
    if (element.LINK == null || element.LINK == '' || element.LINK == undefined || element.BE_ID != null) {
      // merchantWebURL = '/gaspol_web/pages/profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin;
      merchantWebURL = 'tab3-profile.php?store_id=' + element.F_PIN + '&f_pin=' + f_pin;
    } else if (!element.LINK.startsWith("http")) {
      merchantWebURL = "https://" + element.LINK;
    } else {
      merchantWebURL = element.LINK;
    }

    let domain = '';

    if (imageArray) {
      if (imageArray.length > 1) {
        imageArray.forEach((image, jIdx) => {
          var imgElem = '';
          var fileExt = ext_re.exec(image)[1].trim();
          if ($image_type_arr.includes(fileExt)) {
            imgElem = '<img class="content-image" src="' + domain + '/gaspol_web/images/' + image.trim() + '"/>'
          } else if ($video_type_arr.includes(fileExt)) {
            var isAutoplay = size == 2 ? 'autoplay' : '';
            imgElem = '<video muted playsinline loop class="content-image" id="video-' + element.CODE + '"><source src="' + domain + '/gaspol_web/images/' + image.trim() + '#t=1" type="video/' + fileExt + '"></video>';
          }
          if (imgElem) {
            if (jIdx == 0) {
              imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
            } else {
              imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
            }
          }
        });
        contents = '<div id="store-carousel-' + element.CODE + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
          //  : (''))
          '<div class="carousel-inner">' +
          imageDivs +
          '</div>' +
          '</div>';
      } else {
        var imgElem = '';
        var fileExt = ext_re.exec(imageArray[0])[1].trim();
        if ($image_type_arr.includes(fileExt)) {
          imgElem = '<img class="content-image" src="' + domain + '/gaspol_web/images/' + imageArray[0].trim() + '"/>'
        } else if ($video_type_arr.includes(fileExt)) {
          var isAutoplay = size == 2 ? 'autoplay' : '';
          imgElem = '<video muted playsinline loop class="content-image" id="video-' + element.CODE + '"><source src="' + domain + '/gaspol_web/images/' + imageArray[0].trim() + '#t=1" type="video/' + fileExt + '"></video>';
        }
        contents = imgElem;
      }
      var interval = size == 2 ? '3000' : 'false';
      var isBig = size == 2 ? 'big-grid' : 'small-grid';
      var computed =
        // '<div class="inner" onclick="openStore(\'' + element.CODE + '\',\'' + merchantWebURL + '\');" ' +
        // ' oncontextmenu="openStoreMenu(\'' + element.CODE + '\',\'' + element.NAME + '\')"' +
        // '>' +
        // '<a href="javascript:openStore(\'' + element.CODE + '\',\'' + merchantWebURL + '\');">' +
        '<a href="' + merchantWebURL + '" id="' + element.CODE + '">' +
        '<div id="grid-' + element.CODE + '" class="inner ' + isBig + '"' +
        ((element.LINK == null || element.LINK == '' || element.LINK == undefined || element.BE_ID != null) ? "" :
          ' oncontextmenu="openStoreMenu(\'' + element.CODE + '\',\'' + element.NAME + '\')"') +
        '>' +
        contents +
        // '<div class="icon-merchant"><img src="/gaspol_web/assets/img/icons/Verified-(Black).png" ' + (element.BE_ID != null ? '' : ' class="d-none"') + '/><div class="merchant-name">' + element.NAME + '</div></div>' +
        '<div class="viewer-count" id="visitor-' + element.CODE + '">' +
        '<img src="/nexilis/assets/img/jim_likes_red.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
        '<span class="visitor-amt" style="font-size:11px; color:white">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.TOTAL_LIKES) +
        '</span>' +
        '<img src="/nexilis/assets/img/jim_comments_blue.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
        '<span class="follower-amt" style="font-size:11px; color:white">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.TOTAL_COMMENTS) +
        '</span>' +
        '</div>' +
        '</div>' +
        '</a>';
      if (!gridCheck(gridElements, element.CODE)) {
        gridElements.push({
          id: element.CODE,
          minW: size,
          minH: size,
          maxW: size,
          maxH: size,
          content: computed
        });
        grid_stack.addWidget({
          id: element.CODE,
          minW: size,
          minH: size,
          maxW: size,
          maxH: size,
          content: computed
        });
      }
      if (size == 2 && imageArray.length > 1) {
        carouselList.push('#store-carousel-' + element.CODE + '');
      }
    }
  });

  grid_stack.compact();

  if (dataFiltered.length == 0) {
    $('#no-stores').removeClass('d-none');
  } else {
    $('#no-stores').addClass('d-none');
  }
  $('.carousel').each(function () {
    $(this).carousel();
  });
  $('#stack-top').css('display', 'none');
  $('.overlay').addClass('d-none');
  checkVideoViewport();
  // checkVideoCarousel();
  checkCarousel();
  correctVideoCrop();
  correctImageCrop();
  addToCartModal();
  attachLongPress();
  busy = false;

  // if (carouselIntervalId) {
  //   clearInterval(carouselIntervalId);
  // }
  // carouselIntervalId = setInterval(function () {
  //   carouselNext();
  // }, 3000);
}

var nextCarouselIdx = 0;
var carouselList = [];

function carouselNext() {
  if (carouselList.length <= 0) return;
  let prevIdx = nextCarouselIdx;
  while (!$(carouselList[nextCarouselIdx]).is(":in-viewport")) {
    nextCarouselIdx = nextCarouselIdx + 1;
    if (nextCarouselIdx >= carouselList.length) {
      nextCarouselIdx = 0;
    }
    if (nextCarouselIdx == prevIdx) break;
  }
  $(carouselList[nextCarouselIdx]).carousel('next');
  nextCarouselIdx = nextCarouselIdx + 1;
  if (nextCarouselIdx >= carouselList.length) {
    nextCarouselIdx = 0;
  }
}



function correctVideoCrop() {
  var videos = document.querySelectorAll("video.content-image");
  videos.forEach(function (elem) {
    elem.addEventListener("loadedmetadata", function () {
      if (elem.videoWidth > elem.videoHeight) {
        elem.classList.add("landscape");
      }
    })
  })
}

var onlongtouch;
var timer;
var touchduration = 500;

function pauseAllVideo() {
  $('.grid-stack-item .carousel-item video, .grid-stack-item .carousel-item.active video').each(function () {
    let isPaused = $(this).get(0).paused;
    $(this).off("stop pause ended");
    // $(this).on("stop pause ended", function (e) {
    //   $(this).closest(".carousel").carousel();
    // });
    if (!isPaused) {
      $(this).get(0).pause();
    }
  });
}

function playAllVideo() {
  $('.grid-stack-item .carousel-item video, .grid-stack-item .carousel-item.active video').each(function () {
    // pause carousel when video is playing
    // $(this).off("play");
    // $(this).on("play", function (e) {
    //   $(this).closest(".carousel").carousel("pause");
    // })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  });
}

let popUpModal = document.getElementById('modal-product');

popUpModal.addEventListener('show.bs.modal', function () {
  pauseAllVideo();
})

popUpModal.addEventListener('hidden.bs.modal', function () {
  // playAllVideo();
  console.log('hidden');
  $('#modal-product .modal-body').html('');
  if (window.Android) {
    window.Android.setIsProductModalOpen(false);
  }
  if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
    window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
      param1: false
    });
  }
  modalIsOpen = false;
  checkVideoViewport();
})

function attachLongPress() {
  let gridItem = Array.from(document.querySelectorAll('div.grid-stack-item-content a'));
  gridItem.forEach(function (element) {
    element.addEventListener('contextmenu', (e) => {
      e.preventDefault();
      e.stopPropagation();
    })
    let dragging = false;
    element.addEventListener("touchstart", function (event) {
      // event.preventDefault();
      console.log('touch', element.id)
      event.stopPropagation();
      if (!timer) {
        timer = setTimeout(function () {
          // timer = null;
          // console.log('drag', dragging);
          if (!dragging) {
            showProductModal(element.id);
          }
          console.log('touch', element.id)
        }, touchduration);
      }
    }, false);
    element.addEventListener('touchmove', function (evt) {
      dragging = true;
      // console.log('drag', dragging);
    })
    element.addEventListener("touchend", function () {
      dragging = false;
      // console.log('drag', dragging);
      if (timer) {
        if (modalIsOpen) {
          // if (window.Android) {
          //   window.Android.setIsProductModalOpen(false);
          // }
          // if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
          //   window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
          //     param1: false
          //   });
          // }
          // $('#modal-product .modal-body').html('');
          $('#modal-product').modal('hide');
        }
        clearTimeout(timer);
        timer = null;
      }
    }, false);
  });
}

function hideModal() {
  $('#modal-product').modal('hide');
}

function correctImageCrop() {
  var images = document.querySelectorAll("img.content-image");
  images.forEach(function (elem) {
    elem.addEventListener("load", function () {
      if (elem.width > elem.height) {
        elem.classList.add("landscape");
      }
    })
  })
}

function openStore($store_code, $store_link) {
  if (window.Android) {
    if (storeMap.has($store_code)) {
      var storeOpen = storeMap.get($store_code);

      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
          if (xmlHttp.status == 200) {
            let dataStore = JSON.parse(xmlHttp.responseText);
            storeData = JSON.stringify(dataStore[0]);
          }
          window.Android.openStore(storeOpen);
        }
      }
      xmlHttp.open("get", "/gaspol_web/logics/fetch_stores_specific?store_id=" + $store_code);
      xmlHttp.send();
    }
  } else {
    window.location.href = $store_link;
  }
}

function openStoreMenu($storeCode, $storeName) {
  if (window.Android) {
    if (storeMap.has($storeCode)) {
      var storeOpen = storeMap.get($storeCode);
      window.Android.openStoreMenu(storeOpen);
    }
  }
}

function fetchRewardPoints() {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let resp = JSON.parse(xmlHttp.responseText);
      // // // console.log(resp);

      if (resp.length > 0) {
        resp.forEach(abc => {
          let storeIndex = dataFiltered.findIndex(dt => dt.CODE == abc.STORE_CODE);
          dataFiltered[storeIndex].REWARD_PTS = abc.AMOUNT;
          // // // console.log(storeIndex);
        });
      }
    }
  };

  if (window.Android) {
    var f_pin = window.Android.getFPin();
    // var f_pin = "0282aa57c9";
    // var fpin_lokal = "0282aa57c9";
    if (f_pin) {
      xmlHttp.open("get", "/gaspol_web/logics/fetch_stores_reward_user_raw?f_pin=" + f_pin);
    } else {
      xmlHttp.open("get", "/gaspol_web/logics/fetch_stores_reward_user_raw");
    }
  } else {
    xmlHttp.open("get", "/gaspol_web/logics/fetch_stores_reward_user_raw");
    // var f_pin = "0282aa57c9";
    // xmlHttp.open("get", "/gaspol_web/logics/fetch_stores_reward_user_raw?f_pin=" + f_pin);
  }

  xmlHttp.send();
}

let countVideoPlaying = 0;
var visibleCarousel = new Set();

function checkVideoViewport() {

  let videoWrapElements = document.querySelectorAll('.big-grid');
  let videoWrapArr = [].slice.call(videoWrapElements);
  // let carouselElements = document.querySelectorAll('.big-grid .carousel');
  // let carouselArr = [].slice.call(carouselElements);

  // let allElementsArr = videoWrapArr.concat(carouselArr);
  let allElementsArr = videoWrapArr.reverse();
  let observer = new IntersectionObserver((entries) => {

    entries.forEach(entry => {
      if (entry.intersectionRatio >= 1 && $('#modal-product').not('.show') && countVideoPlaying === 0) {
        playElement(entry.target, entry.intersectionRatio);
      } else if (entry.intersectionRatio < 1) {
        pauseElement(entry.target, entry.intersectionRatio);
      }
    });
  }, {
    threshold: 1
  });

  function playElement(el, ir) {
    let video = el.querySelector('video');
    let carousel = el.querySelector('.carousel');
    if (video != null && video.paused) {
      video.play();
      console.log(video.id, 'play');
      countVideoPlaying = 1;
    }
    // else if (carousel != null && !visibleCarousel.has(carousel.id)) {
    //   visibleCarousel.add(carousel.id);
    //   // $('#' + carousel.id).carousel({
    //   //   interval: 3000
    //   // });
    //   $('#' + carousel.id).carousel('cycle');

    // }
  }

  function pauseElement(el, ir) {
    let video = el.querySelector('video');
    let carousel = el.querySelector('.carousel');
    // console.log('video', video);
    // console.log('carousel', carousel);
    if (video != null && !video.paused) {
      video.pause();
      countVideoPlaying = 0;
    }
    // else if (carousel != null && visibleCarousel.has(carousel.id)) {
    //   visibleCarousel.delete(carousel.id);
    //   $('#' + carousel.id).carousel('pause');
    // }

  }

  allElementsArr.forEach((elements) => {
    observer.observe(elements);
  });
}

function checkVideoCarousel() {
  // play video when active in carousel
  // $(".carousel").on("slid.bs.carousel", function (e) {
  //   if (palioBrowser && isChrome) {
  //     if ($(this).find("video").length) {
  //       if ($(this).find(".carousel-item").hasClass("active")) {
  //         $(this).find("video").get(0).play();
  //       } else {
  //         $(this).find("video").get(0).pause();
  //       }
  //     }
  //   }
  // });
}

var visibleCarousel = new Set();

function checkCarousel() {
  // $('.carousel').each(function () {
  //   if ($(this).is(":in-viewport")) {
  //     if (!visibleCarousel.has($(this).attr('id'))) {
  //       visibleCarousel.add($(this).attr('id'));
  //       $(this).carousel('cycle');
  //     }
  //   } else {
  //     if (visibleCarousel.has($(this).attr('id'))) {
  //       visibleCarousel.delete($(this).attr('id'));
  //       $(this).carousel('pause');
  //     }
  //   }
  // });
}

// start periodic when window in focus
$(window).focus(function () {
  //do something
  refreshId = setInterval(function () {
    // updateStoreViewer();
  }, 10000);
  // if (carouselIntervalId) {
  //   clearInterval(carouselIntervalId);
  // }
  // carouselIntervalId = setInterval(function () {
  //   carouselNext();
  // }, 3000);
});

// stop periodic when window out of focus
$(window).blur(function () {
  //do something
  clearInterval(refreshId);
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
    carouselIntervalId = 0;
  }

});
onClickHasStory();
var refreshId = 0;
$(function () {
  // fillGridStack('#content-grid');
  // registerPulldown();
  $(window).scroll(function () {
    scrollFunction();
    didScroll = true;

    // play video when is in view
    checkVideoViewport();
    checkVideoCarousel();
    checkCarousel();
  });
  // if (localStorage.getItem("store_data") !== null && localStorage.getItem("store_pics_data") !== null) {
  //   prefetchStores();
  // }
  fillFilter();
  // horizontalScrollPos();
  // getFollowSetting();
  getShowLinklessSetting();
  getLikedProducts();
  getCommentedProducts();
  activeCategoryTab();
  // fetchStores();
  // updateStoreViewer();
});

var storeMap = new Map();

function prefetchStores() {
  data = JSON.parse(localStorage.getItem("store_data"));
  filterStoreData(filter, search, false);
  dataFiltered.forEach(storeEntry => {
    storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
  });
  dataFiltered = [];
  dataFiltered = dataFiltered.concat(data);

  var productData = JSON.parse(localStorage.getItem("store_pics_data"));
  productData.forEach(storeEntry => {
    $thumb_ids = storeEntry.THUMB_ID.split("|");
    $thumb_ids.forEach(function (thumbid, index) {
      if (!thumbid.startsWith("http")) {
        var root = 'http://' + location.host;
        var profPic = "";

        if (thumbid == null || thumbid == "") {
          profPic = "/gaspol_web/assets/img/palio.png";
        } else {
          // profpic = root + ":2809/file/image/" + storeEntry.THUMB_ID;
          profPic = "/gaspol_web/images/" + thumbid;
        }
        $thumb_ids[index] = profPic;
      }
    });
    if (!productImageMap.has(storeEntry.STORE_CODE)) {
      productImageMap.set(storeEntry.STORE_CODE, $thumb_ids);
    } else if (productImageMap.get(storeEntry.STORE_CODE).length < 3) {
      productImageMap.set(storeEntry.STORE_CODE, productImageMap.get(storeEntry.STORE_CODE).concat($thumb_ids));
    }
  });
  // fillGridStack('#content-grid', currentSort, limit, offset);
}

function getFollowSetting() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      dataFollowSetting = JSON.parse(xhr.responseText);

      // // // console.log(data);
      enableFollow = dataFollowSetting;
    }
  };
  xhr.open("get", "/gaspol_web/logics/fetch_stores_settings?param=stats");
  xhr.send();
}

function getShowLinklessSetting() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      dataShowLinklessSetting = JSON.parse(xhr.responseText);

      // // // console.log(data);
      showLinkless = dataShowLinklessSetting;
    }
  };
  xhr.open("get", "/gaspol_web/logics/fetch_stores_settings?param=show_linkless");
  xhr.send();
}

// function fetchStores() {
//   // var formData = new FormData();
//   // formData.append('f_pin', localStorage.F_PIN);

//   var xmlHttp = new XMLHttpRequest();
//   xmlHttp.onreadystatechange = function () {
//     if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
//       data = JSON.parse(xmlHttp.responseText);
//       filterStoreData(filter, search, false);
//       dataFiltered.forEach(storeEntry => {
//         storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
//       });
//       // dataFiltered = [];
//       // dataFiltered = dataFiltered.concat(data);
//       localStorage.setItem("store_data", xmlHttp.responseText);
//       fetchProductPics();

//     }
//   }

//   if (window.Android) {
//     var f_pin = window.Android.getFPin();
//     if (f_pin) {
//       xmlHttp.open("get", "/gaspol_web/logics/fetch_stores?f_pin=" + f_pin);
//     } else {
//       xmlHttp.open("get", "/gaspol_web/logics/fetch_stores");
//     }
//   } else {
//     xmlHttp.open("get", "/gaspol_web/logics/fetch_stores");
//     // var f_pin = "0282aa57c9";
//     // xmlHttp.open("get", "/gaspol_web/logics/fetch_stores?f_pin=" + f_pin);
//   }

//   xmlHttp.send();
// }

function fetchLinks() {
  // console.log(searchSettings);
  let f_pin = "";
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = async function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      data = JSON.parse(xmlHttp.responseText);
      // console.log('data', data);
      if (data.length == 0) {
        // loadClient();
      } else {
        // pullShopFromCrawler(searchSettings[4].exactTerms, data, false);
        // console.log(currentShuffle);
        // fetchProductPics(currentShuffle, false);
        let store_id = new URLSearchParams(window.location.search).get('store_id');
        if (store_id != null) {
          STORE_ID = store_id.toString();
        }
        // filterStoreData("", "", STORE_ID, false);
        console.log(activeFilter);
        filterStoreData(activeFilter, query, STORE_ID, false);

      }

      localStorage.setItem("store_data", xmlHttp.responseText);

      // fetchProductPics();

    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_links?f_pin=" + f_pin);

  xmlHttp.send();
}

function updateStoreViewer() {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let dataStoreViewer = JSON.parse(xmlHttp.responseText);
      dataStoreViewer.forEach(storeEntry => {
        if (storeEntry.IS_LIVE_STREAMING > 0) {
          $('#live-' + storeEntry.CODE).removeClass('d-none');
        } else {
          $('#live-' + storeEntry.CODE).addClass('d-none');
        }
        $('#visitor-' + storeEntry.CODE + ' span.visitor-amt').html('' + new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(storeEntry.TOTAL_VISITOR));
        $('#visitor-' + storeEntry.CODE + ' span.follower-amt').html('' + new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(storeEntry.TOTAL_FOLLOWER));
      });
    }
  }

  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      xmlHttp.open("get", "/gaspol_web/logics/fetch_stores?f_pin=" + f_pin);
    } else {
      xmlHttp.open("get", "/gaspol_web/logics/fetch_stores");
    }
  } else {
    xmlHttp.open("get", "/gaspol_web/logics/fetch_stores");
  }

  xmlHttp.send();
}
let modalIsOpen = false;

var productImageMap = new Map();
var productImageCountMap = new Map();

function fetchProductPics(arr, isGoogle) {
  // console.log("array");

  let addLinks = [];

  arr.forEach(storeEntry => {

    $thumb_ids = storeEntry.THUMB;

    if (productImageCountMap.has(storeEntry.CODE)) {
      return;
    } else {
      let thumb_arr = $thumb_ids.split('|');
      let new_arr = [];
      thumb_arr.forEach((tid, idx) => {
        if (!tid.startsWith("http")) {
          var root = 'http://' + location.host;
          // var profPic = "";

          // if (thumbid == null || thumbid == "") {
          //   profPic = "/gaspol_web/assets/img/palio.png";
          // } else {
          //   // profpic = root + ":2809/file/image/" + storeEntry.THUMB_ID;
          //   profPic = "/gaspol_web/images/" + thumbid;
          // }
          // $thumb_ids[index] = profPic;

          if (tid != null && tid != "") {
            let profPic = tid;
            // $thumb_ids[index] = profPic;
            new_arr.push(profPic);
          }
        }
      })
      productImageMap.set(storeEntry.CODE, new_arr);
    }

    if (!productImageCountMap.has(storeEntry.CODE)) {
      productImageCountMap.set(storeEntry.CODE, 1);
    } else {
      productImageCountMap.set(storeEntry.CODE, productImageCountMap.get(storeEntry.CODE) + 1);
    }

    if (storeEntry.ID === undefined) {
      addLinks.push(storeEntry);
    }
  });

  if (addLinks.length > 0) {
    saveLinks(addLinks);
  }

  // if (isGoogle) {

  //   saveLinks(arr);
  // }
  localStorage.setItem("store_data", JSON.stringify(arr));
  fillGridStack('#content-grid', currentSort, limit, offset, isGoogle);
}

function toggleVideoMute(code) {
  console.log(code);
  let videoWrap = document.getElementById(code);
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

function playVid(code) {
  let videoWrap = document.querySelector('#videowrap-modal-' + code);
  let playButton = video.querySelector('.video-play');
  let video = video.querySelector('video');

  playButton.addEventListener('click', (e) => {
    if (video.paused) {
      video.play();
      playButton.classList.add('d-none');
    }
  });
}
var hiddenStores = [];

function filterStoreData($filterCategory, $filterSearch, $storeId, isSearching) {
  if (window.Android) {
    try {
      hiddenStores = window.Android.getHiddenStores().split(",");
    } catch (error) {

    }
  }

  dataFiltered = [];
  data.forEach(storeEntry => {
    // if (showLinkless == 2 || (showLinkless == 1 && !storeEntry.LINK) || (showLinkless == 0 && storeEntry.LINK)) {
    var isMatchCategory = false;

    if ($filterCategory) {

      // var categoryArray = $filterCategory;
      // isMatchCategory = storeEntry.CATEGORY == $filterCategory;
      var categoryArray = $filterCategory.split("-");
      isMatchCategory = categoryArray.indexOf(storeEntry.CATEGORY + "") > -1;
      console.log(isMatchCategory);
      // console.log('fc', categoryArray.indexOf(storeEntry.CATEGORY + ""));
    } else {
      isMatchCategory = true;
    }

    var isMatchStoreId = false;
    if ($storeId) {
      isMatchStoreId = storeEntry.F_PIN == $storeId;
    } else {
      isMatchStoreId = true;
    }

    var isMatchSearch = false;
    // console.log("filterSearch", $filterSearch);
    if ($filterSearch) {
      isMatchSearch = isMatchSearch || storeEntry.TITLE.toLowerCase().includes($filterSearch.toLowerCase());
      isMatchSearch = isMatchSearch || storeEntry.DESC.toLowerCase().includes($filterSearch.toLowerCase());
      // isMatchSearch = isMatchSearch || storeEntry.LINK.toLowerCase().includes($filterSearch.toLowerCase());
      isMatchSearch = isMatchSearch || storeEntry.USERNAME.toLowerCase().includes($filterSearch.toLowerCase());
    } else {
      isMatchSearch = true;
    }
    if (isMatchCategory && isMatchSearch && isMatchStoreId) {
      // console.log('here');
      dataFiltered.push(storeEntry);
    }
  });
  if (isSearching) {

  }
  fetchProductPics(dataFiltered, isSearching);
}



function visitStore($store_code, $f_pin, $is_entering) {
  var formData = new FormData();

  formData.append('store_code', $store_code);
  formData.append('f_pin', $f_pin);
  formData.append('flag_visit', ($is_entering ? 1 : 0));

  if ($store_code && $f_pin) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // // console.log(xmlHttp.responseText);
        let dataVisitStore = JSON.parse(xmlHttp.responseText);
        $('#visitor-' + $store_code + ' span').html('' + new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(dataVisitStore[0].TOTAL_VISITOR));
      }
    }
    xmlHttp.open("post", "/gaspol_web/logics/visit_store");
    xmlHttp.send(formData);
  }
}

var mouseY = 0;
var startMouseY = 0;

// function registerPulldown() {
//   PullToRefresh.init({
//     mainElement: '#content-grid',
//     onRefresh: function () {
//       window.location.reload();
//     }
//   });
// }

var didScroll;
var isSearchHidden = true;
var lastScrollTop = 0;
var delta = 1;
var navbarHeight = $('#header-layout').outerHeight();
var topPosition = 0;


function headerOut() {
  $('#searchFilter').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

$('#header').click(function () {
  $(document).scrollTop(0);
  if ($('#searchFilter').hasClass('d-none')) {
    $('#searchFilter').removeClass('d-none');
    isSearchHidden = false;
  } else {
    $('#searchFilter').addClass('d-none');
    isSearchHidden = true;
    const query = $('#query').val();

    // if (!isFilterCheckedAny()) {
    //   resetFilter();
    // } else
    if (query != "") {
      searchFilter();
    } else
    if (query == "") {
      var url_string = window.location.href;
      var url = new URL(url_string);
      var paramValue = url.searchParams.get("query");
      if (paramValue != null) {
        searchFilter();
      }
    }
  }
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  $('#gear').rotate({
    angle: 0,
    animateTo: 180
  });
});

let headerHeight = $('#header-layout').outerHeight();

function hasScrolled() {
  var st = $(this).scrollTop();

  // Make sure they scroll more than delta
  if (Math.abs(lastScrollTop - st) <= delta)
    return;

  // If they scrolled down and are past the navbar, add class .nav-up.
  // This is necessary so you never see what is "behind" the navbar.
  if (st > lastScrollTop && st > navbarHeight) {
    // Scroll Down
    $('#header-layout').css('top', -headerHeight + 'px');
  } else {
    // Scroll Up
    if (st + $(window).height() < $(document).height()) {
      $('#header-layout').css('top', '0px');
    }
  }

  lastScrollTop = st;
}

setInterval(function () {
  if (didScroll) {
    hasScrolled();
    didScroll = false;
  }
}, 10);

$(function () {
  $(window).scroll(function () {
    scrollFunction();
    didScroll = true;
  });
});

function scrollFunction() {
  if ($(document).scrollTop() > navbarHeight) {
    // if (!isSearchHidden)
    // headerOut();
    $("#scroll-top").css('display', 'block');
  } else {
    $("#scroll-top").css('display', 'none');
  }
}

function topFunction(animate) {
  if (animate) {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  } else {
    window.scrollTo({
      top: 0
    });
  }
}

$('#switchAll').click(function () {
  setFilterCheckedAll($('#switchAll').is(':checked'));
});

function checkSwitch(checked) {
  $('#switchAll').prop('checked', checked);
}

$('.checkbox-filter-cat').click(function () {
  if (!$(this).is(':checked')) {
    checkSwitch(false);
  } else if (isFilterCheckedAll()) {
    checkSwitch(true);
  }
});

function fillFilter() {
  var url_string = window.location.href;
  var url = new URL(url_string);
  var searchValue = url.searchParams.get("query");
  if (searchValue != null) {
    $('#query').val(searchValue);
  }
  var filterValue = url.searchParams.get("filter");
  if (filterValue != null) {
    filterArr = filterValue.split("-");
    filterArr.forEach(filterId => {
      $('#checkboxFilter-' + filterId).prop('checked', true);
    });
  }
}

function resetFilter() {
  var needRefresh = false;
  if ($('#query').val() || !$("#switchAll").is(':checked')) {
    needRefresh = true;
  }
  $("#mic").attr("src", "../assets/img/action_mic.png");
  $('#query').val('');
  $('#switchAll').prop('checked', true);
  setFilterCheckedAll(true);
  if (!isSearchHidden) {
    // headerOut();
  }
  if (needRefresh) {
    searchFilter();
  }
}

function changeBg(category) {
  let imgBg = document.querySelector('.demo-bg');
  // // console.log(category);

  if (category == '313') { //soccer
    imgBg.src = "../assets/img/nxsport_bg/bg3.png";
  } else if (category == '314') { //basketball
    imgBg.src = "../assets/img/nxsport_bg/bg2.png";
  } else if (category == '315') { //boxing
    imgBg.src = "../assets/img/nxsport_bg/bg4.png";
  } else if (category == '316') { //tennis
    imgBg.src = "../assets/img/nxsport_bg/bg5.png";
  } else if (category == '317') { // racing
    imgBg.src = "../assets/img/nxsport_bg/bg6.png";
  } else {
    imgBg.src = "../assets/img/nxsport_bg/bg1.png";
  }
}

function hasStoreId() {
  var tmp = "";
  var params = location.search
    .substr(1)
    .split("&");
  var id = "#all-store";
  var filter = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('store_id=')) {
      tmp = params[i].split("=")[1];
      STORE_ID = tmp;
    }
    if (params[i].includes('filter=')) {
      tmp = params[i].split("=")[1];
      FILTERS = tmp;
    }
  }
  highlightStore();
  const scrollLeft = $(id).position()['left'];
  $("#story-container ul").scrollLeft(scrollLeft);
  if (location.href.includes('#product')) {
    var product_id = '#' + location.href.split('#')[1]
    $(product_id)[0].scrollIntoView();
  }
}

let activeFilter = '';
let query = $('input#query').val();

function searchFilter() {
  // $('#loading').removeClass('d-none');
  setTimeout(function () {
    dataFiltered = [];
    // // // console.log("here");
    var dest = "";
    var horizontal = 'timeline_story_container_grid.php';
    query = $('#query').val();
    console.log('af', activeFilter);
    console.log('store id', STORE_ID)
    var filter = activeFilter;
    if (activeFilter !== '') {
      let activeFilterArr = activeFilter.split('-');
      $('#categoryFilter-body ul li input').each(function () {
        if (activeFilterArr.includes($(this).attr('id'))) {
          $(this).prop('checked', true);
        } else {
          $(this).prop('checked', false);
        }
      })
    }
    localStorage.setItem('activeQuery', query);
    localStorage.setItem('activeFilter', filter);
    localStorage.setItem('activeStoreId', STORE_ID);
    // if (STORE_ID != "") {
    //   dest = dest + "?store_id=" + STORE_ID;
    // }
    if (window.Android) {
      var f_pin = window.Android.getFPin();
      // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
      if (f_pin) {
        dest = dest + "?f_pin=" + f_pin;
      }
    } else {
      var f_pin = new URLSearchParams(window.location.search).get('f_pin');
      if (f_pin) {
        dest = dest + "?f_pin=" + f_pin;
      }
    }
    if (STORE_ID != "") {
      dest = dest + "&store_id=" + STORE_ID;
    }
    if (query != "") {
      let urlEncodedQuery = encodeURIComponent(query);
      dest = dest + "&query=" + urlEncodedQuery;
    }
    if (filter != "") {
      let urlEncodedFilter = encodeURIComponent(filter);
      dest = dest + "&filter=" + urlEncodedFilter;
    }
    horizontal = horizontal + dest;
    // window.location.href = dest;
    // if (!dest) dest = "?"
    history.pushState({
      'search': query,
      'filter': filter
    }, "Palio Browser", dest);
    offset = 0;
    r = Math.floor(Math.random() * (2 - 1 + 1)) + 1;
    console.log(horizontal);
    $.get(horizontal, function (data) {
      $('#story-container').html(data);
      // highlightStore();
      hasStoreId();
      onClickHasStory();
    });
    filterStoreData(filter, query, STORE_ID, true);
    changeLayout();
  }, 500);
}

function selectCategoryFilter() {
  let selected = [];
  $('#categoryFilter-body input:checked').each(function () {
    selected.push($(this).attr('id'));
  });

  if (selected.length > 0) {
    activeFilter = selected.join('-');
  } else {
    activeFilter = '';
  }

  $('#modal-categoryFilter').modal('toggle');

  if (window.Android) {
    try {
      window.Android.selectCategory(activeFilter);
    } catch (e) {
      console.log('select cat', e);
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.selectCategory) {
    window.webkit.messageHandlers.selectCategory.postMessage({
      param1: activeFilter
    });
  }
  STORE_ID = "";
  searchFilter();
}

// function activeCategoryTab() {
//   let urlSearchParams = new URLSearchParams(window.location.search);
//   let activeParam = urlSearchParams.get('filter');
//   activeFilter = activeParam;

//   if (activeParam == null) {
//     activeParam = 1;
//     activeFilter = 0;
//   }

//   // console.log("active filter", activeFilter);

//   $('#categoryFilter-' + activeParam).addClass('active');
//   $('#category-tabs .nav-link:not(#categoryFilter-' + activeParam + ')').removeClass('active');
// }

function checkCategoryCheckbox() {
  $('#modal-categoryFilter input').on('change', function () {
    var len = $('#modal-categoryFilter input:checked').length;
    if (len === 0) {
      $(this).prop('checked', true);
      console.log('You must select at least 1 checkbox');
    }
  });
}

function activeCategoryTab() {
  let urlSearchParams = new URLSearchParams(window.location.search);
  let activeParam = urlSearchParams.get('filter');

  console.log(activeParam);

  if (activeParam == null) {
    activeParam = "all";
    activeFilter = "";
  } else {
    activeFilter = activeParam;
  }
  console.log(activeFilter);
  // $('#categoryFilter-' + activeParam).addClass('active');
  // $('#category-tabs .nav-link:not(#categoryFilter-' + activeParam + ')').removeClass('active');
  if (activeParam !== "all") {
    let filters = activeParam.split('-');

    filters.forEach(fi => {
      $('#modal-categoryFilter input#' + fi).prop('checked', true);
    })
  } else {
    console.log('all active')
    $('#modal-categoryFilter ul li input').each(function () {
      $(this).prop('checked', true);
    })
  }
}

function voiceSearch() {
  if (window.Android) {
    $isVoice = window.Android.toggleVoiceSearch();
    toggleVoiceButton($isVoice);
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.toggleVoiceSearch) {
    window.webkit.messageHandlers.toggleVoiceSearch.postMessage({
      param1: ""
    });
  }
}



function submitVoiceSearch($searchQuery) {
  // // // console.log("submitVoiceSearch " + $searchQuery);
  $('#query').val($searchQuery);
  $('#delete-query').removeClass('d-none');
  searchFilter();

}

function toggleVoiceButton($isActive) {
  if ($isActive) {
    $("#mic").attr("src", "../assets/img/action_mic_blue.png");
  } else {
    $("#mic").attr("src", "../assets/img/action_mic.png");
  }
}

function pauseAll() {
  $('video').each(function () {
    $(this).get(0).pause();
  })
  visibleCarousel.clear();
  $('.carousel').each(function () {
    $(this).carousel('pause');
  })
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
    carouselIntervalId = 0;
  }
  startPause = new Date().getTime();
  console.log(startPause);
  hideCategoryModal();
  $('#modal-product').modal('hide');
}

function resumeAll() {
  // checkVideoCarousel();
  checkCarousel();
  // updatecounter();
  // fetchNotifCount();
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  if (modalIsOpen) {
    if ($('#modal-product .video-wrap').length > 0) {
      $('#modal-product .video-wrap video').get(0).play(0);
    }
  } else {
    checkVideoViewport();
  }
  // carouselIntervalId = setInterval(function () {
  //   // carouselNext();
  // }, 3000);
  let curTime = new Date().getTime();
  console.log('startpause', startPause);
  if (startPause > 0) {
    console.log('time', curTime - startPause);
    if (curTime - startPause >= 30000) {
      refreshClean();
    }
  }
}

$('#searchFilterForm-a').validate({
  rules: {
    // 'category[]': {
    //   required: true
    // }
  },
  messages: {
    // 'category[]': {
    //   required: '<div class="alert alert-danger" role="alert">Pilih minimal salah satu filter di atas</div>',
    // },
  },
  submitHandler: function (form) {
    searchFilter();
  },
  errorClass: 'help-block',
  errorPlacement: function (error, element) {
    // if (element.attr('name') == 'category[]') {
    //   error.insertAfter('#checkboxGroup');
    // }
  }

});

function hideSortDropdown() {
  $('#stack-top').css('display', 'none');
  $('#grid-overlay').addClass('d-none');
}

async function changeSort(sort) {
  currentSort = sort;
  currentShuffle = await shuffleMerchants(sort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
}

$('#sort-store-popular').click(async function () {
  currentSort = 'popular';
  currentShuffle = await shuffleMerchants(currentSort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
  $('#sort-store-popular .check-mark').removeClass('d-none');
  $('#sort-store-date .check-mark').addClass('d-none');
  $('#sort-store-follower .check-mark').addClass('d-none');
})

$('#sort-store-date').click(async function () {
  currentSort = 'date';
  currentShuffle = await shuffleMerchants(currentSort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
  $('#sort-store-popular .check-mark').addClass('d-none');
  $('#sort-store-date .check-mark').removeClass('d-none');
  $('#sort-store-follower .check-mark').addClass('d-none');
})

$('#sort-store-follower').click(async function () {
  currentSort = 'follower';
  currentShuffle = await shuffleMerchants(currentSort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
  $('#sort-store-popular .check-mark').addClass('d-none');
  $('#sort-store-date .check-mark').addClass('d-none');
  $('#sort-store-follower .check-mark').removeClass('d-none');
})

function eraseQuery() {

  if ($('#searchFilterForm-a input#query').val() != '') {
    $('#delete-query').removeClass('d-none');
  }

  $("#delete-query").click(function () {
    $('#searchFilterForm-a input#query').val('');
    $('#delete-query').addClass('d-none');
    searchFilter();
  })

  $('#searchFilterForm-a input#query').keyup(function () {
    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })
}

function resetSearch() {
  document.getElementById('query').value = '';
}

function checkDupes() {
  let nodes = document.querySelectorAll('#content-grid>div[gs-id]');
  let ids = {};
  let totalNodes = nodes.length;

  console.log('total', totalNodes);

  for (let i = 0; i < totalNodes; i++) {
    let currentId = nodes[i].gridstackNode.id ? nodes[i].gridstackNode.id : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }

  // let dupes = Object.keys(ids).find(key => object[key] === value);;

  console.table(ids);
}

function checkDupesDataFiltered() {
  let nodes = dataFiltered;
  let ids = {};
  let totalNodes = nodes.length;

  for (let i = 0; i < totalNodes; i++) {
    let currentId = nodes[i].CODE ? nodes[i].CODE : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }

  // let dupes = Object.keys(ids).find(key => object[key] === value);;

  console.table(ids);
}

function updateCartCounter() {
  let counter_badge = 0;
  if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
  } else {
    var cart = [];
  }
  cart.forEach(item => {
    item.items.forEach(item => {
      counter_badge += parseInt(item.itemQuantity);
    })
  })
  if (counter_badge != 0) {
    $('#cart-counter').removeClass('d-none');
    $('#cart-counter').html(counter_badge);
  } else {
    $('#cart-counter').addClass('d-none');
  }
}

function onFocusSearch() {
  if (window.Android) {
    try {
      window.Android.onFocusSearch();
    } catch (e) {

    }
  }
}

$(function () {
  gapi.load("client");
  //updateCounter();

  let urlParams = new URLSearchParams(window.location.search);
  let activeCat = urlParams.get('filter');

  if (activeCat != null) {
    $('#categoryFilter-' + activeCat).addClass('active');
    $('.nav-link:not(#categoryFilter-' + activeCat + ')').removeClass('active');
  } else {
    $('#categoryFilter-all').addClass('active');
    $('.nav-link:not(#categoryFilter-all)').removeClass('active');
  }

  let sortMenu = document.getElementById("stack-top");
  $('#grid-overlay').click(function () {
    if (sortMenu.style.display == "block") {
      sortMenu.style.display = 'none';
      $('#grid-overlay').addClass('d-none');
    }
  });

  eraseQuery();

  $('form#searchFilterForm-a').get(0).reset();
  $('#delete-query').addClass('d-none');
})

function getShopFromCrawler(result, existingArr, isSearching) {
  console.log(result);

  let resultArr = JSON.parse(result);

  resultArr.forEach(res => {
    let isExist = existingArr.some(el => el.CODE == res.CODE);
    if (isExist == false) {
      res.TOTAL_LIKES = 0;
      res.TOTAL_COMMENTS = 0;
      res.DESC = res.TITLE;
      res.CATEGORY = "5";
      existingArr.push(res);
    }
  });
  console.log(existingArr);
  filterStoreData(activeFilter, isSearching ? url : search, isSearching);
}

function pullShopFromCrawler(url, arr, isSearching) {
  console.log(url);
  let words = url.split(" ");

  let terms = "";
  if (words.length > 1) {
    terms = words.join("+");
  } else {
    terms = words[0];
  }

  let searchUrl = "https://www.google.com/search?tbm=shop&q=" + terms;

  if (window.Android) {
    window.Android.pullShopFromCrawler(searchUrl, isSearching);
  } else {

    console.log(searchUrl);

    $.get(searchUrl, function (data) {
      let doc = new DOMParser().parseFromString(data, "text/html");
      let elements = doc.getElementsByClassName("shntl sh-np__click-target");
      // let tempArr = [];

      Array.from(elements).forEach(ele => {
        let img = ele.getElementsByClassName("SirUVb sh-img__image")[0].querySelector("img").src;
        let title = ele.getElementsByClassName("sh-np__product-title")[0].textContent;
        // console.log(ele.querySelector("a"));
        let context_link = "https://www.google.com" + ele.getAttribute("href");
        if (img.startsWith("http")) {
          let link_id = new URLSearchParams(new URL(img).search).get("q").split(":")[1];
          let obj = {
            "LINK_ID": link_id,
            "TITLE": title,
            "DESC": title,
            "THUMB": img,
            "CATEGORY": "5",
            "CONTEXT_LINK": context_link
          }
          let isExist = arr.some(el => el.CODE == link_id);
          if (isExist == false) {
            obj.TOTAL_LIKES = 0;
            obj.TOTAL_COMMENTS = 0;
            arr.push(obj);
          }
        }
      });
      console.log(arr);
      filterStoreData(activeFilter, isSearching ? url : search, isSearching);
    });
  }
}

function highlightStore() {

  if (STORE_ID != "") {
    selected_id = "#store-" + STORE_ID;
    // todo: kalo store ga ada
  } else {
    selected_id = '#all-store';
  }
  $('.has-story').removeClass('selected');
  $(selected_id).addClass("selected");
  horizontalScrollPos(STORE_ID);
}

function horizontalScrollPos(selected) {
  let selectedPos = 0;
  try {
    selectedPos = document.querySelector('.has-story#store-' + selected).offsetLeft;
  } catch (e) {

  }

  $('#story-container ul').animate({
    scrollLeft: selectedPos
  })
}

function checkButtonPos() {
  let elem = document.querySelector('.prod-addtocart');
  let bounding = elem.getBoundingClientRect();

  if (bounding.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
    console.log('out')
    elem.style.bottom = elem.offsetHeight + 20 + 'px';
  } else {
    elem.style.bottom = '25px';
  }
}

function pullRefresh() {
  if (window.Android && $(window).scrollTop() == 0) {
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
  }
}

function pauseAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    $(this).off("stop pause ended");
    $(this).on("stop pause ended", function (e) {
      $(this).closest(".carousel").carousel();
    });
    $(this).get(0).pause();
  });
}

function playAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    // pause carousel when video is playing
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  });
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

var commentedProducts = [];

function getCommentedProducts() {
  var f_pin = ""
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  if (f_pin != "") {
    //   // console.log("GETCOMMENTED");
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        let likeData = JSON.parse(xmlHttp.responseText);
        likeData.forEach(product => {
          var productCode = product.POST_ID;
          commentedProducts.push(productCode);
          $(".comment-icon-" + productCode).attr("src", "../assets/img/jim_comments_blue.png");
        });
        console.log('get commented', commentedProducts);
      }
    }
    xmlHttp.open("get", "/gaspol_web/logics/fetch_products_commented?f_pin=" + f_pin);
    xmlHttp.send();
  }

}

function addToCartModal() {
  /* start handle detail product popup */
  const initPos = parseInt($('#header-layout').offset().top + $('#header-layout').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));

  // let product_id = "";

  let init = parseInt(fixedPos.replace('px', ''));

  // var ua = window.navigator.userAgent;
  // var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
  // var webkit = !!ua.match(/WebKit/i);
  // var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);

  $('#modal-addtocart').on('shown.bs.modal', function () {
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    checkButtonPos();
    pullRefresh();
    pauseAllVideo();
    playModalVideo();

    if (window.Android) {
      window.Android.setIsProductModalOpen(true);
    }
  })

  $('.grid-stack-item-content a').click(function () {
    console.log('init: ' + init);
    $('#modal-addtocart .modal-dialog').css('top', '55px');
    $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#modal-addtocart').on('hidden.bs.modal', function () {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    let modalVideo = $('#modal-addtocart').find('video');
    if (modalVideo.length > 0) {
      $('#modal-addtocart .modal-body video').get(0).pause();
    }
    pullRefresh();
    checkVideoViewport();

    if (window.Android) {
      window.Android.setIsProductModalOpen(false);
    }
  })

  /* end handle detail product popup */
}

var likedPost = [];

function likeProduct($productCode, $is_post) {
  var score = parseInt($('#like-counter-' + $productCode).text());
  var isLiked = false;

  if (window.Android) {
    if (window.Android.checkProfile()) {

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
    }
  } else {
    console.log('liked', likedPost);
    if (likedPost.includes($productCode)) {
      console.log('exists', $productCode);
      likedPost = likedPost.filter(p => p !== $productCode);
      $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.png");
      if (score > 0) {
        $('#like-counter-' + $productCode).text(score - 1);
      }
      isLiked = false;
    } else {
      console.log('not exist', $productCode);
      likedPost.push($productCode);
      $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.png");
      $('#like-counter-' + $productCode).text(score + 1);
      isLiked = true;
    }
  }

  //TODO send like to backend
  // var f_pin = "02b46dfe44";
  var f_pin = "";
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
  formData.append('is_post', $is_post);

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      // // console.log(xmlHttp.responseText);
      // updateScore($productCode);
    }
  }

  if (window.Android) {
    if (window.Android.checkProfile()) {
      xmlHttp.open("post", "/gaspol_web/logics/like_product");
      xmlHttp.send(formData);
    }
  } else {
    xmlHttp.open("post", "/gaspol_web/logics/like_product");
    xmlHttp.send(formData);
  }
}

function openComment(code, isPost, f_pin_link) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = "comment.php?product_code=" + code + "&is_post=" + isPost + "&f_pin=" + f_pin;
    }
  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = "comment.php?product_code=" + code + "&is_post=" + isPost + "&f_pin=" + f_pin;
  }
}

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

function changeLayout() {
  console.log('2131')
  let pickGrid = document.getElementById('to-grid-layout');
  let pickList = document.getElementById('to-list-layout');

  let f_pin = "";

  let urlParams = new URLSearchParams(window.location.search);

  let query = localStorage.getItem('activeQuery') !== '' && localStorage.getItem('activeQuery') !== null ? "&query=" + localStorage.getItem('activeQuery') : "";
  let filter = localStorage.getItem('activeFilter') !== '' && localStorage.getItem('activeFilter') !== null ? "&filter=" + localStorage.getItem('activeFilter') : "";
  let store_id = localStorage.getItem('activeStoreId') !== '' && localStorage.getItem('activeStoreId') !== null ? "&store_id=" + localStorage.getItem('activeStoreId') : "";

  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = urlParams.get('f_pin');
  }

  function clickList() {
    if (window.Android) {
      // window.Android.isGrid("0");
    }
    console.log('query', query);
    console.log('filter', filter);
    console.log('store_id', store_id);
    localStorage.setItem("is_grid", "0");
    window.location = 'tab1-main.php?f_pin=' + f_pin + query + filter + store_id;
  }

  function clickGrid() {
    if (window.Android) {
      // window.Android.isGrid("1");
    }
    console.log('query', query);
    console.log('filter', filter);
    console.log('store_id', store_id);
    localStorage.setItem("is_grid", "1");
    window.location = 'tab3-main.php?f_pin=' + f_pin + query + filter + store_id;
  }

  if (pickGrid && pickList) {
    $('#to-grid-layout').off('click');
    $('#to-list-layout').off('click');
    $('#to-grid-layout').click(clickGrid);
    $('#to-list-layout').click(clickList);
  }
}


let gif_arr = [];
let gif_pos = [0, 1];

function getGIFs() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      gif_arr = JSON.parse(xmlHttp.responseText);
      let new_arr = [gif_arr[0], gif_arr[2], gif_arr[1], gif_arr[3]];
      console.log('all', new_arr);
      // let noPPOB = gif_arr.filter(gif => gif.BE_ID !== 0);
      // console.log('no ppob', noPPOB);
      drawGIFs(new_arr);
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_gifs?f_pin=" + f_pin);
  xmlHttp.send();

}

function drawGIFs(arr) {
  let lastAd = parseInt(localStorage.getItem('last_ad'));

  if (lastAd == null) {
    lastAd = 0;
  }

  let currentAd = 0;
  if (lastAd + 1 <= arr.length - 1) {
    currentAd = lastAd + 1;
  } else {
    currentAd = 0;
  }

  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  localStorage.setItem('last_ad', currentAd);
  let pickGif = arr[currentAd];
  console.log(currentAd);
  let url = '';
  if (currentAd == 0 || currentAd == 2) {
    url = pickGif.URL + '&f_pin=' + f_pin + '&origin=2';
  } else {
    url = pickGif.URL + '?f_pin=' + f_pin + '&origin=2';
  }
  let div = `
      <div id="gifs-${currentAd}" class="gifs">
      <a onclick="event.preventDefault(); goToURL('${url}');">
          <img src="/gaspol_web/assets/img/gif/${pickGif.FILENAME}">
        </a>
      </div>
    `;
  document.getElementById('gif-container').innerHTML = div;
  if (document.getElementById('gifs-2') != null) {
    // console.log('sini anjing');
    // document.getElementById('gif-container').style.removeProperty('bottom');
    // document.getElementById('gif-container').style.top = '30px';
  }
  // randomAd(arr);
  animateAd(currentAd);
}

function goToURL(url, checkIOS = false) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = url;
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.checkProfile.postMessage({
      param1: url,
      param2: 'gif'
    });
    return;

  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = url;
  }
}

function animateAd(which) {
  console.log('which', which);
  if (which === 3 || which === 0) { // move horizontal
    if ($('#gif-container').hasClass('left')) {
      var windowHeight = $(window).width();
      var lineHeight = $('#gifs-' + which).width();
      var desiredBottom = 20;
      var newPosition = windowHeight - (lineHeight + desiredBottom);
      $('#gif-container').animate({
        left: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          right: desiredBottom + 'px',
          left: 'auto'
        });
        $('#gif-container').fadeOut();
      });
    } else if ($('#gif-container').hasClass('right')) {
      $('#gif-container').animate({
        left: '20px',
      }, 5000, function () {
        $('#gif-container').css({
          right: 'auto',
          left: '20px'
        });
        $('#gif-container').fadeOut();
      });
    }
  } else if (which === 2 || which === 1) {

    $('#gif-container').animate({
      top: '30px',
    }, 5000, function () {
      $('#gif-container').css({
        bottom: 'auto',
        top: '30px'
      });
      $('#gif-container').fadeOut();
    });
  }
}

let category_arr = [];

let categoryTree;


function fetchDefaultCategory() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let webform = JSON.parse(xmlHttp.responseText);
      console.log(category_arr);

      if (webform.APP_URL === '1' || webform.APP_URL === '2') {
        if (webform.APP_URL_DEFAULT !== null && webform.APP_URL_DEFAULT !== '') {
          defaultCategory = webform.APP_URL_DEFAULT;
        }
      } else if (webform.CONTENT_TAB_LAYOUT === '1' || webform.CONTENT_TAB_LAYOUT === '2') {
        if (webform.CONTENT_TAB_DEFAULT !== null && webform.CONTENT_TAB_DEFAULT !== '') {
          defaultCategory = webform.CONTENT_TAB_DEFAULT;
        }
      }

      fetchLinks();
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_default_category?f_pin=" + f_pin);
  xmlHttp.send();
}

function fetchCategory() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      category_arr = JSON.parse(xmlHttp.responseText);
      console.log(category_arr);

      if (category_arr.length > 0) {
        categoryTree = unflatten(category_arr);
        console.log(categoryTree);

        let objTree = {
          CATEGORY_ID: "0",
          NAME: "root",
          CHILDREN: categoryTree
        }

        console.log(objTree);

        createCategoryCheckbox($('#modal-categoryFilter #categoryFilter-body ul'), objTree);
      }
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_posts_category?f_pin=" + f_pin);
  xmlHttp.send();
}

const unflatten = data => {
  const tree = data.map(e => ({
    ...e
  })).reduce((a, e) => {
    a[e.CATEGORY_ID] = a[e.CATEGORY_ID] || e;
    a[e.PARENT] = a[e.PARENT] || {};
    const parent = a[e.PARENT];
    parent.CHILDREN = parent.CHILDREN || [];
    parent.CHILDREN.push(e);
    return a;
  }, {});
  return Object.values(tree)
    .find(e => e.CATEGORY_ID === undefined).CHILDREN;
};

function createCategoryCheckbox(parentUL, branch) {
  console.log(branch);
  for (var key in branch.CHILDREN) {
    if (branch.CHILDREN != null) {
      var item = branch.CHILDREN[key];
      $item = $('<li>', {
        id: "item-" + item.CATEGORY_ID
      });
      $item.append($('<input>', {
        type: "checkbox",
        id: item.CATEGORY_ID,
        name: "item-" + item.CATEGORY_ID
      }));
      $item.append($('<label>', {
        for: item.CATEGORY_ID,
        text: item.NAME
      }));
      parentUL.append($item);
      if (item.CHILDREN) {
        var $ul = $('<ul>').appendTo($item);
        createCategoryCheckbox($ul, item);
      }
    }
  }
  checkboxBehavior();
  activeCategoryTab();
}

function checkboxBehavior() {
  $('#categoryFilter-body li :checkbox').on('click', function () {
    console.log('asdmas');
    var isChecked = $(this).is(":checked");

    //down
    $(this).closest('ul').find("ul li input:checkbox").prop("checked", isChecked);
  });
}

function checkArray() {
  var selected = [];
  $('#categoryFilter-body input:checked').each(function () {
    selected.push($(this).attr('id'));
  });
  console.log('checked', selected);
}

$(window).on('load', async function () {

  // fetchCategory();
  if (document.getElementById('gif-container') != null) {
    getGIFs();
  }
  $('#toggle-filter').click(function () {
    $('#modal-categoryFilter').modal('toggle');
  })
  activeCategoryTab();
  $('#submitCategory').click(function () {
    selectCategoryFilter();
  })
  // loadClient();
  // await getSearchSettings();
  // fetchDefaultCategory();
  fetchLinks();
  onClickHasStory();
  horizontalScrollPos();
  changeLayout();
  $(window).scroll(function () {
    // make sure u give the container id of the data to be loaded in.
    // // console.log(Math.ceil($(window).scrollTop() + $(window).height()) >= $("#content-grid").height());
    // console.log(busy);
    if ((Math.ceil($(window).scrollTop() + ($(window).height() * 1.5)) >= $("#content-grid").height()) && !busy) {
      // // console.log('scroll here');
      busy = true;
      offset = limit + offset;
      // displayRecords(limit, offset);
      fillGridWidgets('#content-grid', currentSort, limit, offset);
    }
  });
})

function hideCategoryModal() {
  $('#modal-categoryFilter').modal('hide');
}

let video_arr = ['webm', 'mp4'];
let img_arr = ['png', 'jpg', 'webp', 'gif', 'jpeg'];

class ShowProduct {

  constructor(async_result) {

    console.log(async_result);

    let thumbs = async_result.thumb_id.split('|');
    let name = async_result.name;
    let description = async_result.description;

    console.log(thumbs);

    let content = '';
    let domain = '';

    if (thumbs.length == 1) {
      let type = ext(thumbs[0]);
      console.log(type);
      if (video_arr.includes(type)) {
        // content = `
        //     <video muted autoplay loop class="d-block w-100">
        //     <source src="../images/${thumbs[0]}#t=0.5" type="video/${type}">
        //     </video>
        // `;
        content += `
                <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                <video class="myvid" autoplay muted playsinline>
                <source src="${thumbs[0].includes("http") ? thumbs[0] : domain + "/gaspol_web/images/" + thumbs[0]}">
                </video>
                <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                <img src="../assets/img/video_mute.png" />
                </div>
                <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                '<img src="../assets/img/video_play.png" />
                </div>
                </div>
                `;
      } else if (img_arr.includes(type)) {
        content += `
                    <img src="${thumbs[0].substr(0,4) == "http" ? th : domain+'/gaspol_web/images/' + thumbs[0]}" class="d-block w-100">
                `;
      }
    } else {
      content = `
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
            `;

      let filteredThumbs = thumbs.filter(thumb => thumb.trim() != '' || thumb.length > 0);
      filteredThumbs.forEach((th, idx) => {
        if (th.trim() != '') {
          content += `<div class="carousel-item${idx == 0 ? ' active' : ''}">`;

          let type = ext(th);
          console.log('type', type);
          if (video_arr.includes(type)) {
            //     content += `
            //     <video autoplay muted class="d-block w-100">
            //     <source src="${th.substr(0,4) == "http" ? th : 'https://qmera.io/gaspol_web/images/' + th}#t=0.5" type="video/${type}">
            //     </video>
            // `;
            content += `
                        <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                        <video class="myvid" autoplay muted playsinline>
                        <source src="${th.includes("http") ? th : domain + "/gaspol_web/images/" + th}">
                        </video>
                        <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                        <img src="../assets/img/video_mute.png" />
                        </div>
                        <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                        '<img src="../assets/img/video_play.png" />
                        </div>
                        </div>
                        `;
          } else if (img_arr.includes(type)) {
            content += `
                        <img src="${th.substr(0,4) == "http" ? th : domain + '/gaspol_web/images/' + th}" class="d-block w-100">
                    `;
          }
          console.log(content);
          content += `</div>`;
        }
      })



      if (filteredThumbs.length > 1) {
        content += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            `;
      }
    }

    let link = '';
    let url_div = '';

    if (async_result.LINK != null && async_result.LINK != undefined && async_result.LINK.trim() != "") {
      link = async_result.LINK;
      if (link.substring(0, 4) != "http") {
        link = "https://" + link;
      }
      console.log(link);
      // link_thumb = '<a href="' + link + '">' + thumb_content + '</a>';
      url_div = `
           <a href="${link}" style="text-decoration:underline; color:#0d6efd";>Link</a>
            `;
      if (async_result.CODE == '16467163130000246a901c4') {
        url_div = `
           <a href="${link + "?f_pin=" + f_pin}" style="text-decoration:underline; color:#0d6efd";>Register membership</a>
            `;
      }
    }

    let f_pin = '';
    if (window.Android) {
      f_pin = window.Android.getFPin();
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    console.log(content)
    // codes below wil only run after getProductThumbs done executing
    this.html_body = content;
    let profpic = dataFiltered.find(df => df.CODE == async_result.CODE).PROFPIC;
    let uname = dataFiltered.find(df => df.CODE == async_result.CODE).USERNAME;
    let poster = dataFiltered.find(df => df.CODE == async_result.CODE).F_PIN;
    this.html_header = `
        <div class="d-flex align-items-center">
          <a href="tab3-profile?f_pin=${f_pin}&store_id=${poster}">
            <img src="http://108.136.138.242/filepalio/image/${profpic}" class="align-self-start rounded-circle me-2" style="width:35px; height:35px; object-fit:cover;">
            <div class="media-body" style="display:inline-block; flex-grow:1;">
              <h6>${uname}</h6>
            </div>
          </a>
        </div>`;
    // this.html_footer = `
    // <div>
    // ${url_div}
    // <p>${description}</p>
    // </div>`;

    this.parent = document.body;
    this.modal_header = document.querySelector('#modal-product .modal-header');
    this.modal_body = document.querySelector('#modal-product .modal-body');
    // this.modal_footer = document.querySelector('#modal-product .modal-footer');

    this.modal_header.innerHTML = " ";
    this.modal_body.innerHTML = " ";
    // this.modal_footer.innerHTML = " ";

    this._createModal();

    modalIsOpen = true;

    if (window.Android) {
      window.Android.setIsProductModalOpen(true);
    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
      window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
        param1: true
      });
    }
  }

  static async build(product_code, is_product) {
    let async_result = await getProductThumbs(product_code, is_product);
    return new ShowProduct(async_result);
  }

  question() {
    // this.save_button = document.getElementById('confirm-changes');

    // return new Promise((resolve, reject) => {
    //     this.save_button.addEventListener("click", () => {
    //         event.preventDefault();
    //         resolve(true);
    //         this._destroyModal();
    //     })
    // })
  }

  _createModal() {

    // Main text

    this.modal_body.innerHTML = this.html_body;
    this.modal_header.innerHTML = this.html_header;
    // this.modal_footer.innerHTML = this.html_footer;

    // Let's rock
    $('#modal-product').modal('show');

    $('#modal-product .video-wrap video').on("play", function() {
      $(this).prop('muted', false);
    })
    $('#carouselExampleIndicators').carousel();
  }

  _destroyModal() {
    $('#modal-product').modal('hide');
  }
}

async function showProductModal(product_code, is_product) {

  // event.preventDefault();

  let add = await ShowProduct.build(product_code, is_product);
  // let response = await add.question();

}

window.onload = (event) => {
  try {
    changeLayout();
    hasStoreId();
    $('#toggle-filter').click(function () {
      $('#modal-categoryFilter').modal('toggle');
    })
    $('#submitCategory').click(function () {
      selectCategoryFilter();
    })
    checkCategoryCheckbox();
  } catch {}
};

function showHideNewPost(boolean) {
  // if (!boolean) {
  //   $('#to-new-post').addClass('d-none'); // hide
  // } else {
  //   $('#to-new-post').removeClass('d-none'); // show
  // }
}

// SHOW PRODUCT FUNCTIONS
function getProductThumbs(product_code, is_product) {
  let formData = new FormData();
  formData.append("product_id", product_code);
  formData.append("is_product", is_product);

  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/gaspol_web/logics/get_product_thumbs");

    xhr.onload = function () {
      if (this.status >= 200 && this.status < 300) {
        resolve({
          thumb_id: JSON.parse(xhr.response).THUMB_ID,
          name: JSON.parse(xhr.response).NAME,
          description: JSON.parse(xhr.response).DESCRIPTION,
          CODE: JSON.parse(xhr.response).CODE,
          LINK: JSON.parse(xhr.response).LINK
        });
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

function buttonTheme(category, checkIOS = false) {
  console.log('cat: ' + category);
  if (window.Android) {
    window.Android.setButtonTheme(category);
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.setButtonTheme.postMessage({
      param1: category
    });
    // return;
  }
}