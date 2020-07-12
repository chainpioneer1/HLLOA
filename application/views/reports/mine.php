<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/reports.css') ?>">
<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <div class="tab-search" style="justify-content: flex-start;">
                <div class="input-area">
                    <label style="margin-left: 20px;">日期:</label>
                    <div class="date-range-selector">
                        <input name="range_from" style="display: none!important;" value="<?= $range_from; ?>"/>
                        <input name="range_to" style="display: none!important;" value="<?= $range_to; ?>"/>
                        <!--                        <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>-->
                        <input type="text" placeholder="请选择" name="range_disp"/>
                    </div>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">日报列表
            <div>
                <div class="btn-circle btn-blue" onclick="addItem();"><i class="fa fa-plus"></i> 新增日报</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <div class="report-part-title">设计设计设计部</div>
            <div class="report-container">
                <div class="grid-item">
                    <div>
                        <div class="report-header">
                            <div class="report-no">05</div>
                            <div class="report-avatar"></div>
                            <div class="report-info">
                                <div class="report-info-name">用户名</div>
                                <div class="report-info-part">部门名称</div>
                            </div>
                            <div class="report-date">日期: 2020-04-17</div>
                        </div>
                        <div class="report-body">
                            <div class="report-task" data-status="0">
                                <div class="report-task-title">1. asldkfjaslkfdj</div>
                                <div class="report-task-status"></div>
                            </div>
                            <div class="report-task" data-status="0">
                                <div class="report-task-title">1. asldkfjaslkfdj</div>
                                <div class="report-task-status"></div>
                            </div>
                            <div class="report-task" data-status="0">
                                <div class="report-task-title">1. asldkfjaslkfdj</div>
                                <div class="report-task-status"></div>
                            </div>
                            <div class="report-task" data-status="0">
                                <div class="report-task-title">1. asldkfjaslkfdj</div>
                                <div class="report-task-status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="edit-area" data-type="add">
        <div class="content-title"><span>新增日报</span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form report-add" action="" method="post" data-type="add">
            <div class="edit-container" data-type="add">
                <div class="input-area">
                    <label>今日任务:</label>
                    <div class="report-add-container">
                        <div class="report-add-item">
                            <div>1</div>
                            <textarea name="data[]" oninput="autoResize(this);"></textarea>
                            <div onclick="appendReport(this)" data-type="remove"><i class="fa fa-minus"></i></div>
                        </div>
                    </div>
                    <div class="btn-circle btn-red" data-type="add" onclick="appendReport(this);">
                        <i class="fa fa-plus"></i> 添加任务
                    </div>
                </div>
            </div>
        </form>
        <div class="edit-container" style="border:none;padding:20px 125px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.edit-form.report-add');">
                    保存
                </div>
            </div>
        </div>
    </div>
    <div class="edit-area" data-type="edit">
        <div class="content-title"><span>验收任务</span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form report-edit" action="" method="post" data-type="edit">
            <div class="content-table" style="padding: 0;">
                <table>
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="400">今日目标</th>
                        <th width="100">完成情况</th>
                        <th width="550">情况说明</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </form>
        <div class="edit-container" style="border:none;padding:0 125px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.edit-form.report-edit');">
                    保存
                </div>
            </div>
        </div>
    </div>
</div>

<div class="scripts">
    <input hidden class="_lastReport" value='<?= str_replace("'", "`", json_encode($lastReport)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>
    <input hidden class="_partList" value='<?= str_replace("'", "`", json_encode($partList)) ?>'>
    <input hidden class="_holidays" value='<?= $holidays ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('<?= $menu; ?>');
        $(function () {
            searchConfig();
        });

        var _partList = JSON.parse($('._partList').val());
        // var _positionList = JSON.parse($('._positionList').val());
        // var _rankList = JSON.parse($('._rankList').val());
        // var _roleList = JSON.parse($('._roleList').val());
        var _holidays = JSON.parse($('._holidays').val()).data[0].holiday;
        var _holidayList = [];
        for (var i = 0; i < _holidays.length; i++) {
            var items = _holidays[i].list;
            for (var j = 0; j < items.length; j++) {
                items[j].date = makeDateString(makeDateObject(items[j].date));
                _holidayList.push(items[j]);
            }
        }
        _holidayList = removeDuplicatedObject(_holidayList, 'date');
        var _lastReport = JSON.parse($('._lastReport').val());
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;
        var _isAddNew = 0;

        function searchConfig() {
            makeSelectElem($('select[name="search_part"]'), _partList);

            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);
            if (_filterInfo['tbl_user_part.id']) $('select[name="search_part"]').val(_filterInfo['tbl_user_part.id']);

            tree_select();

            $('.base-container .nav-position-title').html(_navTitle);

            makeContents();
            $('.report-container').masonry({
                itemSelector: '.grid-item',
            });

            var lastReport = _lastReport;
            if (lastReport.length > 0) {
                lastReport = lastReport[0];
                var isAllChecked = true;
                var reportData = JSON.parse(lastReport.data);
                for (var i = 0; i < reportData.length; i++) {
                    var item = reportData[i];
                    if (item.status == 0) isAllChecked = false;
                }
                if (isAllChecked) { // disable check button
                    var lastDate = lastReport.create_time.substr(0, 10);
                    var todayDate = makeDateObject().toISOString().substr(0, 10);
                    if (lastDate == todayDate) {
                        $('.content-area .content-title .btn-circle').removeAttr('onclick');
                        $('.content-area .content-title .btn-circle').css({
                            opacity: .6,
                            'pointer-events': 'none'
                        });
                    }
                } else {
                    // show check button
                    $('.content-area .content-title .btn-circle').html(
                        '<i class="fa fa-pen"></i>&nbsp;&nbsp;验收'
                    );
                    $('.content-area .content-title .btn-circle').attr({
                        'onclick': 'editItem(this)',
                        'data-id': lastReport.id,
                        'class': 'btn-circle btn-red'
                    });
                }
            } else {
                // show add button
            }
        }

        function makeContents() {
            var parts = removeDuplicatedObject(_mainList, 'part');
            var content_html = '';
            var fromDate = makeDateObject("<?= $range_from?>");
            var toDate = makeDateObject("<?= $range_to?>");
            var diff = parseInt((toDate - fromDate) / (24 * 60 * 60 * 1000));
            console.log(fromDate, toDate, diff);
            for (var i = 0; i < parts.length; i++) {
                var partItem = parts[i];
                var partDatas = _mainList.filter(function (a) {
                    return a.part == partItem.part;
                });
                // content_html += '<div class="report-part-title">' + partItem.part + '</div>';
                content_html += '<div class="report-container">';
                for (var j = 0; j < diff; j++) {
                    fromDate = makeDateObject(toDate.setDate(toDate.getDate() - 1));
                    if(isHoliday(toDate)) continue;
                    var item = partDatas.filter(function (a) {
                        return a.create_time.substr(0, 10) == makeDateString(fromDate);
                    });
                    if (item.length == 0) {
                        item = {id: -1, data: '[]'};
                        if (makeDateString(fromDate) == makeDateString(makeDateObject())) {
                            continue;
                        }
                    } else {
                        item = item[0];
                    }
                    var taskDatas = JSON.parse(item.data);
                    var isEditable = false;
                    for (var k = 0; k < taskDatas.length; k++) {
                        var tItem = taskDatas[k];
                        if (tItem.status == 0) isEditable = true;
                    }
                    var avatar = baseURL + 'assets/images/icon-profile.png';
                    if (item.avatar != '') avatar = baseURL + item.avatar;
                    content_html += '<div class="grid-item">' +
                        '<div>' +

                        '<div class="report-header">' +
                        '<div class="report-no">' + makeNDigit(j + 1, 2) + '</div>';
                    if (item.id == _lastReport[0].id && isEditable)
                        content_html += '<div class="report-edit" ' +
                            ' onclick="addItem(this)" ' +
                            ' data-id="' + item.id + '">新增任务</div>';
                    else
                        content_html += '<div class="report-edit" ' +
                            ' style="opacity:0;pointer-events: none;"></div>';
                    content_html += '<div class="report-date mine">' + makeDateString(fromDate) + '</div>' +
                        '</div>' +
                        '<div class="report-body">';

                    for (var k = 0; k < taskDatas.length; k++) {
                        var tItem = taskDatas[k];
                        // if (tItem.desc != '') tItem.status = 1;
                        tItem.title = tItem.title.replace(/\n/g, '<br>');
                        content_html += '<div class="report-task" data-status="' + tItem.status + '">' +
                            '<div class="report-task-title">' + (k + 1) + '. ' + tItem.title;
                        if (tItem.desc != '') {
                            tItem.desc = tItem.desc.replace(/\n/g, '<br>');
                            content_html += '<br><span>说明: ' + tItem.desc + '</span>';
                        }
                        content_html += '</div>';
                        content_html += '<div class="report-task-status"></div>' +
                            '</div>';
                    }

                    content_html += '</div>';
                    content_html += '</div>';
                    content_html += '</div>';
                }
                content_html += '</div>';
            }
            $('.content-area .content-table').html(content_html);
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function viewItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            setSearchKeyword(window.location);
            $('.useraction-form').attr('action', baseURL + 'tasks/useraction/3/' + id);
            $('.useraction-form').submit();
        }

        function appendReport(elem) {
            var that = $(elem);
            var type = that.attr('data-type');
            var reportContainer = $('.report-add-container');
            switch (type) {
                case 'add':
                    var no = reportContainer.find('.report-add-item').length + 1;
                    var content_html = '<div class="report-add-item">' +
                        '<div>' + no + '</div>' +
                        '<textarea name="data[]" oninput="autoResize(this);"></textarea>' +
                        '<div onclick="appendReport(this)" ' +
                        ' data-type="remove"><i class="fa fa-minus"></i></div>' +
                        '</div>';
                    reportContainer.append(content_html);
                    break;
                case 'remove':
                    that.parent().remove();
                    var reportElems = reportContainer.find('.report-add-item');
                    for (var i = 0; i < reportElems.length; i++) {
                        var item = $(reportElems[i]);
                        item.find('div:first-child').html(i + 1);
                    }
                    break;
            }
        }

        function addItem(elem) {
            var editElem = $('.edit-area[data-type="add"]');

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            editElem.find('.content-title span').html('新增日报');
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新增日报');
            editElem.find('input').val('');
            editElem.find('textarea').val('');
            _editItemId = 0;
            _isAddNew = 1;
            if (elem != undefined) {
                var that = $(elem);
                var lastReport = _mainList.filter(function (a) {
                    return a.id == that.attr('data-id');
                });
                if (lastReport.length > 0) {
                    lastReport = lastReport[0];
                    _editItemId = lastReport.id;
                    var reportContainer = $('.report-add-container');
                    var reportData = JSON.parse(lastReport.data);
                    reportContainer.html('');
                    for (var i = 0; i < reportData.length; i++) {
                        var item = reportData[i];
                        var content_html = '<div class="report-add-item" data-written="1">' +
                            '<div>' + (i + 1) + '</div>' +
                            '<textarea name="data[]" oninput="autoResize(this);" ' +
                            ' readonly></textarea>' +
                            // '<div onclick="appendReport(this)" ' +
                            // ' data-type="remove"><i class="fa fa-minus"></i></div>' +
                            '</div>';
                        reportContainer.append(content_html);
                        reportContainer.find('.report-add-item:last-child textarea').val(item.title);
                    }
                }
            }
            $('html').animate({scrollTop: 0});
            editElem.fadeIn('fast');
            reportContainer.find('.report-add-item textarea').trigger('input');
        }

        function editItem(elem) {
            var editElem = $('.edit-area[data-type="edit"]');

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            editElem.find('.content-title span').html('验收任务');
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ 验收任务');
            var that = $(elem);

            var id = that.attr('data-id');
            var mainItem = _mainList.filter(function (a) {
                return a.id == id;
            });
            if (mainItem.length > 0) {
                mainItem = mainItem[0];
                _editItemId = mainItem.id;
                _isAddNew = 0;
                var reportData = JSON.parse(mainItem.data);
                var content_html = '';
                for (var i = 0; i < reportData.length; i++) {
                    var item = reportData[i];
                    item.title = item.title.replace(/\n/g, '<br>');
                    content_html += '<tr>';
                    content_html += '<td>' + makeNDigit(i + 1) + '</td>';
                    content_html += '<td>' + item.title + '</td>';
                    content_html += '<td><input class="ace ace-switch" type="checkbox"' +
                        ' name="status[]" /><span class="lbl"></span></td>';
                    content_html += '<td>备注: <textarea type="text" ' +
                        ' name="desc[]" placeholder="请输入内容" ' +
                        ' oninput="autoResize(this);"></textarea><span>必填*</span></td>';
                    content_html += '</tr>';
                }
                editElem.find('table tbody').html(content_html);
                editElem.find('input[type="checkbox"]').prop('checked', true);
                editElem.find('input[type="checkbox"]').off('click');
                editElem.find('input[type="checkbox"]').on('click', function () {
                    var chkElem = $(this);
                    var status = chkElem.prop('checked');
                    if (!status) { // not checked
                        chkElem.parent().next().find('span').css('color', 'red');
                    } else {
                        chkElem.parent().next().find('span').css('color', 'transparent');
                    }
                });
            }
            // $('body').append(editElem);
            // setTransform(editElem, window._scale, 0, [window.innerWidth/2,window.innerHeight/2],['center','center']);
            $('html').animate({scrollTop: 0});
            editElem.fadeIn('fast');
        }

        var _isProcessing = false;

        function editPerform(elem) {
            var that = $(elem);

            var statusElems = $('input[name="status[]"]');
            var descElems = $('textarea[name="desc[]"]');
            var statusStr = '';
            var isValid = true;
            for (var i = 0; i < statusElems.length; i++) {
                var isChecked = $(statusElems[i]).prop('checked');
                if (i > 0) statusStr += ',';
                statusStr += '' + (isChecked ? 2 : 1);
                if (!isChecked && $(descElems[i]).val() == '') isValid = false;
            }

            if (!isValid) {
                alert('请输入未完成任务的情况说明');
                return;
            }

            if (_isProcessing) return;
            _isProcessing = true;
            $('.modal-container[data-type="modal"]').fadeIn('fast');
            $(".uploading-progress").fadeIn('fast');
            var fdata = new FormData(that[0]);
            fdata.append("id", _editItemId);
            fdata.append("isAddNew", _isAddNew);
            fdata.append("statusVal", statusStr);
            $.ajax({
                url: _apiRoot + "updateItem",
                type: "POST",
                data: fdata,
                contentType: false,
                cache: false,
                processData: false,
                async: true,
                xhr: function () {
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function (event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            $(".progress-val").html(percent + '%');
                        }, true);
                    }
                    return xhr;
                },
                mimeType: "multipart/form-data"
            }).done(function (res) { //
                var ret;
                $('.modal-container[data-type="modal"]').fadeOut('fast');
                $(".uploading-progress").fadeOut('fast');

                _isProcessing = false;
                try {
                    ret = JSON.parse(res);
                } catch (e) {
                    alert('操作失败 : ' + JSON.stringify(e));
                    console.log(res);
                    return;
                }
                if (ret.status == 'success') {
                    location.reload();
                } else { //failed
                    alert('操作失败 : ' + ret.data);
                }
            });
        }

        function resetItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            showConfirm(baseURL + 'assets/images/modal/modal-confirm-top.png',
                '', '是否重置密码?', function () {
                    $.ajax({
                        type: "post",
                        url: _apiRoot + "resetItem",
                        dataType: "json",
                        data: {id: id},
                        success: function (res) {
                            if (res.status == 'success') {
                                showNotify('<i class="fa fa-check"></i> 密码重置成功');
                                // location.reload();
                            } else { //failed
                                alert(res.data);
                            }
                        }
                    });
                }
            );
        }

    </script>
</div>