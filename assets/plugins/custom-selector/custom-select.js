document.addEventListener('click', function (ev) {
    closeAllTreeSelect(this);
    // closeAllTreeSearch(this);
});

function closeAllTreeSelect(ele) {
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("tree-select-items");
    y = document.getElementsByClassName("tree-select-selected");
    for (i = 0; i < y.length; i++) {
        if (ele === y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("tree-select-arrow-active");
        }
    }
    for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("tree-select-hide");
        }
    }
}

function closeAllTreeSearch(ele) {
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("tree-search-items");
    y = document.getElementsByClassName("tree-search-selected");
    for (i = 0; i < y.length; i++) {
        if (ele === y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("tree-select-arrow-active");
        }
    }
    for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("tree-search-hide");
        }
    }
}

function tree_select(ele) {
    if (!ele) ele = $('.tree-select');
    $(ele).each(function (idx, elem) {
        elem = $(elem);
        elem.find('.tree-select-selected').remove();
        elem.find('.tree-select-items').remove();

        var selectElement = $(this).find('select')[0];
        var selectedItem = document.createElement("DIV");
        selectedItem.setAttribute("class", "tree-select-selected");
        var selIdx = selectElement.selectedIndex;
        if (selIdx < 0) selIdx = 0;
        if (selectElement.options.length)
            selectedItem.innerHTML = selectElement.options[selIdx].innerHTML;
        $(this).append(selectedItem);
        var optionItems = document.createElement("DIV");
        optionItems.setAttribute("class", "tree-select-items tree-select-hide");
        for (var j = 0; j < selectElement.length; j++) {
            /*for each option in the original select element,
            create a new DIV that will act as an option item:*/
            var optionItem = document.createElement("DIV");
            if (j === selectElement.selectedIndex)
                optionItem.setAttribute('class', 'tree-same-as-selected');
            optionItem.innerHTML = "<span>" + selectElement.options[j].innerHTML + "</span>";
            optionItem.addEventListener("click", function (e) {
                var y, i, k, s, h;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                h = this.parentNode.previousSibling;
                for (i = 0; i < s.length; i++) {
                    if (s.options[i].innerHTML === $(this).find('span')[0].innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("tree-same-as-selected");
                        for (k = 0; k < y.length; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "tree-same-as-selected");
                        break;
                    }
                }
                h.click();
                $(s).trigger('change');
            });
            optionItems.appendChild(optionItem);
        }
        $(this).append(optionItems);
        selectedItem.addEventListener("click", function (e) {
            e.stopPropagation();
            closeAllTreeSelect(this);
            this.nextSibling.classList.toggle("tree-select-hide");
            this.classList.toggle("tree-select-arrow-active");
        });
    })
}

function tree_search(ele, value, name, group, list) {
    if (!ele) ele = $('.tree-search');
    if (!value) value = 'id';
    if (!name) name = 'name';
    if (!group) group = 'part';
    if (!list) list = _userList;
    var parentGroups = removeDuplicated(list, group);
    $(ele).each(function (idx, elem) {
        elem = $(elem);

        elem.find('.tree-search-selected').remove();
        elem.find('.tree-search-items').remove();

        var that = this;
        var selectElement = $(that).find('select')[0];
        if (!$(selectElement).val()) $(selectElement).val(null);
        var selectedIdx = selectElement.selectedIndex;
        // console.log("selectElement: ", selectElement);
        if (!selectElement.options.length) return;
        var selectedItem = document.createElement("DIV");
        selectedItem.setAttribute("class", "tree-search-selected");
        if (selectedIdx >= 0) {
            selectedItem.setAttribute('data-parent',
                selectElement.options[selectedIdx].getAttribute('data-parent'));
            selectedItem.innerHTML = "<span>" + selectElement.options[selectedIdx].innerHTML
                + "</span><i class='fa fa-user-plus'></i>";
        }
        $(this).append(selectedItem);
        var optionItems = document.createElement("DIV");
        optionItems.setAttribute("class", "tree-search-items tree-search-hide");

        // search input field
        var searchField = document.createElement('div');
        searchField.setAttribute('class', 'tree-search-field');
        optionItems.appendChild(searchField);
        searchField.innerHTML = "<input placeholder=''/>" +
            "<button type='button' onclick='treeSearchBtn(this)'><i class='fa fa-search'></i></button>";

        // search content
        var searchContent = document.createElement('div');
        searchContent.setAttribute('class', 'tree-search-content');
        for (var i = 0; i < parentGroups.length; i++) {
            // parent item
            if (parentGroups[i] == null) continue;
            var parentItem = document.createElement("div");
            parentItem.setAttribute('data-id', parentGroups[i]);
            if (selectedIdx >= 0 && selectElement[selectedIdx].getAttribute('data-parent') === parentGroups[i]) {
                // processing selected item
                parentItem.setAttribute('class', 'tree-parent tree-open');
                parentItem.innerHTML = "<i class='fa fa-minus-circle'></i><span>" + parentGroups[i] + "</span>";
                searchContent.appendChild(parentItem);
                // child items
                for (var j = 0; j < selectElement.options.length; j++) {
                    if (selectElement.options[j].getAttribute('data-parent') === parentGroups[i]) {
                        var childItem = document.createElement('div');
                        if (j === selectElement.selectedIndex)
                            childItem.setAttribute('class',
                                'tree-child tree-open tree-same-as-selected');
                        else
                            childItem.setAttribute('class', 'tree-child tree-open');

                        childItem.setAttribute('data-id', parentGroups[i]);
                        childItem.innerHTML = "<span>" + selectElement.options[j].innerHTML + "</span>";
                        childItem.addEventListener("click", function (e) {
                            var a, b, c, s, h;
                            s = this.parentNode.parentNode.parentNode.getElementsByTagName("select")[0];
                            h = this.parentNode.parentNode.previousSibling;
                            for (b = 0; b < s.length; b++) {
                                if (s.options[b].innerHTML === $(this).find('span')[0].innerHTML) {
                                    s.selectedIndex = b;
                                    h.innerHTML = this.innerHTML + '<i class="fa fa-user-plus"></i>';
                                    a = this.parentNode.getElementsByClassName("tree-same-as-selected");
                                    for (c = 0; c < a.length; c++) {
                                        a[c].classList.remove("tree-same-as-selected");
                                    }
                                    this.classList.add("tree-same-as-selected");
                                    break;
                                }
                            }
                            $(s).trigger('change');
                        });

                        searchContent.appendChild(childItem);
                    }
                }
            } else {
                // processing unselected item
                parentItem.setAttribute('class', 'tree-parent tree-close');
                parentItem.innerHTML = "<i class='fa fa-plus-circle'></i><span>" + parentGroups[i] + "</span>";
                searchContent.appendChild(parentItem);
                // child items
                for (var j = 0; j < selectElement.options.length; j++) {
                    if (selectElement.options[j].getAttribute('data-parent') === parentGroups[i]) {
                        var childItem = document.createElement('div');
                        childItem.setAttribute('class', 'tree-child tree-close');
                        childItem.setAttribute('data-id', parentGroups[i]);
                        childItem.innerHTML = "<span>" + selectElement.options[j].innerHTML + "</span>";
                        childItem.addEventListener("click", function (e) {
                            var a, b, c, s, h;
                            s = this.parentNode.parentNode.parentNode.getElementsByTagName("select")[0];
                            h = this.parentNode.parentNode.previousSibling;
                            for (b = 0; b < s.length; b++) {
                                if (s.options[b].innerHTML === $(this).find('span')[0].innerHTML) {
                                    s.selectedIndex = b;
                                    h.innerHTML = this.innerHTML + '<i class="fa fa-user-plus"></i>';
                                    a = this.parentNode.getElementsByClassName("tree-same-as-selected");
                                    for (c = 0; c < a.length; c++) {
                                        a[c].classList.remove("tree-same-as-selected");
                                    }
                                    this.classList.add("tree-same-as-selected");
                                    break;
                                }
                            }
                            $(s).trigger('change');
                        });
                        searchContent.appendChild(childItem);
                    }
                }
            }
            parentItem.addEventListener('click', function (e) {
                var parentID = $(this).attr('data-id');
                var parentClass = $(this).attr('class');
                if (parentClass === 'tree-parent tree-open') {
                    $(this).removeClass('tree-open').addClass('tree-close');
                    $(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                    var childEls = $(this).parent().find('div.tree-child[data-id="' + parentID + '"]');
                    childEls.each(function () {
                        $(this).removeClass('tree-open').addClass('tree-close');
                    })
                } else if (parentClass === 'tree-parent tree-close') {
                    $(this).removeClass('tree-close').addClass('tree-open');
                    $(this).find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
                    var childEls = $(this).parent().find('div.tree-child[data-id="' + parentID + '"]');
                    childEls.each(function () {
                        $(this).removeClass('tree-close').addClass('tree-open');
                    })
                }
            })
        }
        optionItems.appendChild(searchContent);

        // bottom button field
        var buttonField = document.createElement('div');
        buttonField.setAttribute('class', 'tree-search-button');
        buttonField.innerHTML = "<button type='button' onclick='removeTreeSearch(this)'>确定</button>" +
            "<button type='button' onclick='clearTreeSearch(this)'>取消选择</button>";
        optionItems.appendChild(buttonField);


        $(this).append(optionItems);
        selectedItem.addEventListener("click", function (e) {
            e.stopPropagation();
            closeAllTreeSearch(this);
            this.nextSibling.classList.toggle("tree-search-hide");
            this.classList.toggle("tree-search-active");
        });
    })
}

function tree_multi_search(ele, value, name, group, list) {
    if (!ele) ele = $('.tree-multi-search');
    if (!value) value = 'id';
    if (!name) name = 'name';
    if (!group) group = 'part';
    if (!list) list = _userList;
    var parentGroups = removeDuplicated(list, group);
    $(ele).each(function (idx, elem) {
        elem = $(elem);

        elem.find('.tree-search-selected').remove();
        elem.find('.tree-search-items').remove();

        var that = this;
        var selectElement = $(that).find('select')[0];
        if (!$(selectElement).val()) $(selectElement).val(null);
        var selectedIdx = selectElement.selectedIndex;
        // console.log("selectElement: ", selectElement);
        if (!selectElement.options.length) return;
        var selectedItem = document.createElement("DIV");
        selectedItem.setAttribute("class", "tree-search-selected");
        if (selectedIdx >= 0) {
            selectedItem.setAttribute('data-parent',
                selectElement.options[selectedIdx].getAttribute('data-parent'));
            selectedItem.innerHTML = "<span>" + selectElement.options[selectedIdx].innerHTML
                + "</span><i class='fa fa-user-plus'></i>";
        }
        $(that).append(selectedItem);
        var optionItems = document.createElement("DIV");
        optionItems.setAttribute("class", "tree-search-items tree-search-hide");

        // search input field
        var searchField = document.createElement('div');
        searchField.setAttribute('class', 'tree-search-field');
        optionItems.appendChild(searchField);
        searchField.innerHTML = "<input placeholder=''/>" +
            "<button type='button' onclick='treeSearchBtn(this)'><i class='fa fa-search'></i></button>";

        // search content
        var searchContent = document.createElement('div');
        searchContent.setAttribute('class', 'tree-search-content');
        for (var i = 0; i < parentGroups.length; i++) {
            // parent item
            if (parentGroups[i] == null) continue;
            var parentItem = document.createElement("div");
            parentItem.setAttribute('data-id', parentGroups[i]);
            if (selectedIdx >= 0 && selectElement[selectedIdx].getAttribute('data-parent') === parentGroups[i]) {
                // processing selected item
                parentItem.setAttribute('class', 'tree-parent tree-open');
                parentItem.innerHTML = "<i class='fa fa-minus-circle'></i><span>" + parentGroups[i] + "</span>";
                searchContent.appendChild(parentItem);
                // child items
                for (var j = 0; j < selectElement.options.length; j++) {
                    if (selectElement.options[j].getAttribute('data-parent') === parentGroups[i]) {
                        var childItem = document.createElement('div');
                        if (j === selectElement.selectedIndex)
                            childItem.setAttribute('class',
                                'tree-child tree-open tree-same-as-selected');
                        else
                            childItem.setAttribute('class', 'tree-child tree-open');

                        childItem.setAttribute('data-id', parentGroups[i]);
                        childItem.innerHTML = "<span>" + selectElement.options[j].innerHTML + "</span>";
                        childItem.addEventListener("click", function (e) {
                            var a, b, c, s, h;
                            s = this.parentNode.parentNode.parentNode.getElementsByTagName("select")[0];
                            h = this.parentNode.parentNode.previousSibling;
                            for (b = 0; b < s.length; b++) {
                                if (s.options[b].innerHTML === $(this).find('span')[0].innerHTML) {
                                    s.selectedIndex = b;
                                    h.innerHTML = this.innerHTML + '<i class="fa fa-user-plus"></i>';
                                    a = this.parentNode.getElementsByClassName("tree-same-as-selected");
                                    for (c = 0; c < a.length; c++) {
                                        a[c].classList.remove("tree-same-as-selected");
                                    }
                                    this.classList.add("tree-same-as-selected");
                                    break;
                                }
                            }
                            $(s).trigger('change');
                        });

                        searchContent.appendChild(childItem);
                    }
                }
            } else {
                // processing unselected item
                parentItem.setAttribute('class', 'tree-parent tree-close');
                parentItem.innerHTML = "<i class='fa fa-plus-circle'></i><span>" + parentGroups[i] + "</span>";
                searchContent.appendChild(parentItem);
                // child items
                for (var j = 0; j < selectElement.options.length; j++) {
                    if (selectElement.options[j].getAttribute('data-parent') === parentGroups[i]) {
                        var childItem = document.createElement('div');
                        childItem.setAttribute('class', 'tree-child tree-close');
                        childItem.setAttribute('data-id', parentGroups[i]);
                        childItem.innerHTML = "<span>" + selectElement.options[j].innerHTML + "</span>";
                        childItem.addEventListener("click", function (e) {
                            var a, b, c, s, h;
                            s = this.parentNode.parentNode.parentNode.getElementsByTagName("select")[0];
                            h = this.parentNode.parentNode.previousSibling;
                            for (b = 0; b < s.length; b++) {
                                if (s.options[b].innerHTML === $(this).find('span')[0].innerHTML) {
                                    s.selectedIndex = b;
                                    h.innerHTML = this.innerHTML + '<i class="fa fa-user-plus"></i>';
                                    a = this.parentNode.getElementsByClassName("tree-same-as-selected");
                                    for (c = 0; c < a.length; c++) {
                                        a[c].classList.remove("tree-same-as-selected");
                                    }
                                    this.classList.add("tree-same-as-selected");
                                    break;
                                }
                            }
                            $(s).trigger('change');
                        });
                        searchContent.appendChild(childItem);
                    }
                }
            }
            parentItem.addEventListener('click', function (e) {
                var parentID = $(this).attr('data-id');
                var parentClass = $(this).attr('class');
                if (parentClass === 'tree-parent tree-open') {
                    $(this).removeClass('tree-open').addClass('tree-close');
                    $(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                    var childEls = $(this).parent().find('div.tree-child[data-id="' + parentID + '"]');
                    childEls.each(function () {
                        $(this).removeClass('tree-open').addClass('tree-close');
                    })
                } else if (parentClass === 'tree-parent tree-close') {
                    $(this).removeClass('tree-close').addClass('tree-open');
                    $(this).find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
                    var childEls = $(this).parent().find('div.tree-child[data-id="' + parentID + '"]');
                    childEls.each(function () {
                        $(this).removeClass('tree-close').addClass('tree-open');
                    })
                }
            })
        }
        optionItems.appendChild(searchContent);

        // bottom button field
        var buttonField = document.createElement('div');
        buttonField.setAttribute('class', 'tree-search-button');
        buttonField.innerHTML = "<button type='button' onclick='removeTreeSearch(this)'>确定</button>" +
            "<button type='button' onclick='clearTreeMultiSearch(this)'>取消选择</button>";
        optionItems.appendChild(buttonField);


        $(this).append(optionItems);
        selectedItem.addEventListener("click", function (e) {
            e.stopPropagation();
            closeAllTreeSearch(this);
            this.nextSibling.classList.toggle("tree-search-hide");
            this.classList.toggle("tree-search-active");
        });
    })
}

function removeTreeSearch(ele) {
    ele.parentNode.parentElement.classList.add('tree-search-hide');
}

function clearTreeSearch(ele) {
    ele.parentNode.parentNode.parentElement.getElementsByTagName('select')[0].value = '';
    ele.parentNode.parentElement.classList.add('tree-search-hide');
    tree_search();
}

function clearTreeMultiSearch(ele) {
    ele.parentNode.parentNode.parentElement.getElementsByTagName('select')[0].value = '';
    ele.parentNode.parentElement.classList.add('tree-search-hide');
    tree_multi_search();
}

function treeSearchBtn(ele) {
    var search_key = $(ele).parent().find('input').val();
    console.log(search_key);
    var searchContent = $(ele).parent().parent().find('.tree-search-content')[0];
    $(searchContent).find('.tree-parent').each(function () {
        $(this).removeClass('tree-open').removeClass('tree-close').addClass('tree-open');
        $(this).find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
    });
    $(searchContent).find('.tree-child').each(function () {
        console.log(this.innerHTML);
        if (this.innerHTML.indexOf(search_key) >= 0) {
            $(this).removeClass('tree-close').removeClass('tree-open').addClass('tree-open')
        } else {
            $(this).removeClass('tree-close').removeClass('tree-open').addClass('tree-close')
        }
    })
}

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

////////// make tree search select
function makeTreeSearchSelect(selElem, data, defaultStr, parent_id, child_id, title, changeCallback) {
    var defaultStr = selElem.attr('placeholder');
    if (!defaultStr) defaultStr = '请选择';
    var content_html = '<option value="" data-parent="">' + defaultStr + '</option>';
    // make part List
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        // if (item.status == '0') continue;
        content_html += '<option value="' + item[child_id] + '" data-parent="' + item[parent_id] + '">'
            + item[title] + '</option>';
    }
    selElem.html(content_html);
    selElem.off('change');
    selElem.on('change', function (e) {
        if (changeCallback) changeCallback(e);
    });
    tree_search();
}

////////// make tree multi search select
function makeTreeMultiSearchSelect(selElem, data, defaultStr, parent_id, child_id, title, changeCallback) {
    var defaultStr = selElem.attr('placeholder');
    if (!defaultStr) defaultStr = '请选择';
    var content_html = '<option value="" data-parent="">' + defaultStr + '</option>';
    // make part List
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        // if (item.status == '0') continue;
        content_html += '<option value="' + item[child_id] + '" data-parent="' + item[parent_id] + '">'
            + item[title] + '</option>';
    }
    selElem.html(content_html);
    selElem.off('change');
    selElem.on('change', function (e) {
        if (changeCallback) changeCallback(e);
    });
    tree_multi_search();
}

/////////// make Select Element
function makeSelectElem(selElem, data, changeCallback) {
    var defaultStr = selElem.attr('placeholder');
    if (!defaultStr) defaultStr = '请选择';
    var content_html = '<option value="">' + defaultStr + '</option>';
    // make part List
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        // if (item.status == '0') continue;
        content_html += '<option value="' + item.id + '">' + item.title + '</option>';
    }
    selElem.html(content_html);
    selElem.off('change');
    selElem.on('change', function (e) {
        if (changeCallback) changeCallback(e);
    });
    tree_select();
}
