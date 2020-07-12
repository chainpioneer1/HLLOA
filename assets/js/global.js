/////////// utility functions

function removeDuplicated(arr, subKey) {
    var m = {};
    if (!subKey) subKey = '';
    var newarr = [];
    for (var i = 0; i < arr.length; i++) {
        var v = arr[i];
        if (subKey != '') v = arr[i][subKey];
        if (!m[v]) {
            newarr.push(v);
            m[v] = true;
        }
    }
    return newarr;
}

function removeDuplicatedObject(arr, subKey) {
    var m = {};
    if (!subKey) subKey = '';
    var newarr = [];
    for (var i = 0; i < arr.length; i++) {
        var v = arr[i];
        if (subKey != '') v = arr[i][subKey];
        if (!m[v]) {
            newarr.push(arr[i]); // returned array cell
            m[v] = true;
        }
    }
    return newarr;
}

function makeNDigit(num, len) {
    num = num.toString();
    if (!len) len = 2;
    var ret = '';
    for (var i = 0; i < len; i++) ret += '0';
    ret += num;
    ret = ret.substr(-len);
    return ret;
}

function makeDateString(dateVal, lang) {
    if (!lang) lang = 'en';
    if (!dateVal) dateVal = new Date();
    if (lang == 'cn') {
        return dateVal.getFullYear() + '年' +
            makeNDigit(dateVal.getMonth() + 1) + '月' +
            makeNDigit(dateVal.getDate()) + '日';
    }
    return dateVal.getFullYear() + '-' +
        makeNDigit(dateVal.getMonth() + 1) + '-' +
        makeNDigit(dateVal.getDate());
}

function makeDateObject(dateStr) {
    var dateObj = new Date();
    if(dateStr == undefined) return dateObj;
    if(typeof dateStr == 'number') return new Date(dateStr);
    dateStr = dateStr.replace(/-/g, '/');
    dateObj = new Date(dateStr);
    return dateObj;
}

function isHoliday(date) {
    var dateStr = makeDateString(new Date(date));
    var weekDay = new Date(date).getDay();
    var isHoliday = false;
    if (false && (weekDay == 0 || weekDay == 6)) {
        isHoliday = _holidayList.filter(function (a) {
            return a.status == '2' && a.date == dateStr
        }).length;
        if (isHoliday > 0) isHoliday = false;
        else isHoliday = true;
    } else {
        isHoliday = _holidayList.filter(function (a) {
            return a.status == '1' && a.date == dateStr
        }).length;
        isHoliday = (isHoliday > 0);
    }
    return isHoliday;
}
////////////// textarea auto sizing height

function autoResize(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight + 3) + "px";
}


/////////////////////////////////////////////
///////////  make custom pagination part

function appendPagination(curPage, perPage, cntRecord, urlRoot, totalRecords) {
    var perPage = perPage * 1;
    var totalPages = Math.floor((cntRecord * 1 - 1) / perPage + 1);
    curPage = Math.floor((curPage == '' ? 1 : curPage * 1) / perPage + 1);
    var content_html = '<li><div>共' + totalPages + '页</div></li>';
    content_html += '<li><div>跳转到</div></li>';
    content_html += '<li><input value=""></li>';
    content_html += '<li><div>页</div></li>';
    content_html += '<li><a href="javascript:showPage(' + perPage + ',' + totalPages + ',\'' + urlRoot + '\');">确定</a></li>';
    $('.pagination').append(content_html);
    if (totalRecords != undefined) cntRecord = totalRecords * 1;
    $('.pagination').prepend('<li><div>共' + cntRecord + '条</div></li>');
    var firstPageElem = $('.pagination a[data-ci-pagination-page="1"]');
    firstPageElem.attr('href', firstPageElem.attr('href') + '/0');
    $('.pagination a').each(function (idx, elem) {
        var elem = $(elem);
        var url = elem.attr('href');
        if (url.substr(0, 10) != 'javascript') {
            elem.attr('href', 'javascript:;');
            elem.attr('data-target', url);
            elem.off('click');
            elem.on('click', function () {
                location.replace(url);
            })
        }
    })
}

window.showPage = function (perPage, totalPages, urlRoot) {
    var pageNum = $('.pagination>li>input').val();
    if (pageNum < 1 || pageNum > totalPages) return;
    pageNum = (pageNum * 1 - 1) * perPage;
    if (pageNum <= 0) pageNum = '';
    location.replace(baseURL + urlRoot + '/' + pageNum);
}

//////////////////////////////////////////////////


///////////  make confirm modal part

function showConfirm(bgImg, title, content, confirmCallback, cancelCallback) {
    var elem = $('.modal-container[data-type="modal"] .confirm-modal');
    if (!title) title = '';
    if (!content) content = '';
    elem.find('img').attr('src', bgImg);
    elem.find('.modal-header div').html(title);
    var btnTxts = content.split('_');
    elem.find('.modal-footer').attr('data-type', 1);
    if (btnTxts.length > 0) {
        content = btnTxts[0];
        elem.find('button[data-type="yes"]').html(btnTxts[1]);
        if (btnTxts.length > 1) {
            elem.find('.modal-footer').attr('data-type', 2);
            elem.find('button[data-type="no"]').html(btnTxts[2]);
        }
    }
    elem.find('.modal-body div').html(content);
    $('.modal-container[data-type="modal"] button').off('click');
    $('.modal-container[data-type="modal"] button').on('click', function () {
        var that = $(this);
        var type = that.attr('data-type');
        switch (type) {
            case 'close':
                $('.modal-container[data-type="modal"]').fadeOut('fast');
                elem.fadeOut('fast');
                break;
            case 'yes':
                $('.modal-container[data-type="modal"]').fadeOut('fast');
                elem.fadeOut('fast');
                if (confirmCallback) confirmCallback();
                break;
            case 'no':
                $('.modal-container[data-type="modal"]').fadeOut('fast');
                elem.fadeOut('fast');
                if (cancelCallback) cancelCallback();
                break;
        }
    });
    $('.modal-container[data-type="modal"]').fadeIn('fast');
    elem.fadeIn('fast');
}

function showEdit(bgImg, title, content, confirmCallback, closeCallback) {
    var elem = $('.modal-container[data-type="edit"] .confirm-modal');
    if (!title) title = '';
    if (!content) content = '';
    elem.find('img').attr('src', bgImg);
    elem.find('.modal-header div').html(title);
    $('.modal-container[data-type="edit"] button').off('click');
    $('.modal-container[data-type="edit"] button').on('click', function () {
        var that = $(this);
        var type = that.attr('data-type');
        switch (type) {
            case 'close':
                $('.modal-container[data-type="edit"]').fadeOut('fast');
                elem.fadeOut('fast');
                if (closeCallback) closeCallback();
                break;
            case 'yes':
                $('.modal-container[data-type="edit"]').fadeOut('fast');
                elem.fadeOut('fast');
                if (confirmCallback) confirmCallback();
                break;
        }
    });
    $('.modal-container[data-type="edit"]').fadeIn('fast');
    elem.fadeIn('fast');
}

//////////////////////////////////////////////////


///////////  make notify modal part

var _notifyTmr = 0;

function showNotify(content, delay) {
    var elem = $('.notify-container[data-type="modal"]');
    if (!content) content = '';
    if (!delay) delay = 2000;
    elem.find('div').html(content);
    elem.fadeIn('fast');
    clearTimeout(_notifyTmr);
    _notifyTmr = setTimeout(function () {
        elem.fadeOut('fast');
    }, delay);

}

//////////////////////////////////////////////////


///////////////// session part ///////////////////

function setSessionRangeFrom(value) {
    var key = 'range_from';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}

function setSessionRangeTo(value) {
    var key = 'range_to';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}

function setPreviousKeyword(value) {
    var key = 'search_previous_keyword';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}

function setPreviousPart(value) {
    var key = 'search_previous_part';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}
function setSearchKeyword(value) {
    var key = 'search_keyword';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}

function setRangeFrom(value) {
    var key = 'search_range_from';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}

function setRangeTo(value) {
    var key = 'search_range_to';
    if (value == undefined) {
        return sessionStorage.getItem(key);
    }
    sessionStorage.setItem(key, value);
    return value;
}

//////////////////////////////////////////////////