<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/reports.css') ?>">
<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <div class="tab-search" style="justify-content: flex-start;">
                <div class="input-area">
                    <label style="margin-left: 20px;">姓名:</label>
                    <input name="search_keyword" placeholder="请输入内容"/>
                    <label>部门:</label>
                    <div class="tree-select" data-width="200">
                        <select name="search_part"></select>
                    </div>
                    <label>日期:</label>
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
            <!--            <div>-->
            <!--                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i> 新增人员</div>-->
            <!--            </div>-->
            <div class="tab-container">
                <div class="tab-item" data-progress="1">今天</div>
                <div class="tab-item" data-progress="2">昨天</div>
                <div class="tab-item" data-progress="3">7天</div>
            </div>
        </div>
        <div class="content-pagination" style="height: auto;justify-content: flex-end;padding-right:20px;"></div>
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
        <div class="content-pagination"></div>
        <div class="scripts">
            <script>
                $('.content-pagination').html('<?php echo $this->pagination->create_links(); ?>');
                appendPagination('<?= $curPage; ?>', '<?= $perPage; ?>',
                    '<?= $cntPage; ?>', '<?= $apiRoot; ?>', '<?= $perPageUsers; ?>');
                $(function () {
                    $('.pagination li:last-child').remove();
                    $('.pagination li a').each(function (idx, elem) {
                        var that = $(elem);
                        var pgNum = that.html();
                        if (!parseInt(pgNum)) return;
                        var endDate = makeDateObject($('input[name="range_to"]').val());
                        endDate.setDate(endDate.getDate() - parseInt(pgNum));
                        if (isHoliday(endDate)) that.parent().remove();
                        else that.html(endDate.getDate() + '日');
                    })
                    $('.pagination li div, .pagination li input').each(function (idx, elem) {
                        $(elem).parent().remove();
                    })
                })
            </script>
        </div>
    </div>
    <div class="edit-area">
        <div class="content-title"><span>新增人员</span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <div class="input-area">
                    <label>成员姓名:</label>
                    <input name="name" placeholder="请输入名称" type="text"/>
                </div>
                <div class="input-area">
                    <label>联系电话:</label>
                    <input name="phone" placeholder="请输入联系人电话" type="text" maxlength="11"/>
                </div>
                <div class="input-area">
                    <label>邮箱:</label>
                    <input name="email" placeholder="请输入您的邮箱号" type="text"/>
                </div>
                <div class="input-area">
                    <label>所属部门:</label>
                    <div class="tree-select" data-width="315">
                        <select name="part_id" placeholder="全部"></select>
                    </div>
                </div>
                <div class="input-area">
                    <label>职位:</label>
                    <div class="tree-select" data-width="315">
                        <select name="position_id"></select>
                    </div>
                </div>
                <div class="input-area">
                    <label>职级:</label>
                    <div class="tree-select" data-width="315">
                        <select name="rank_id"></select>
                    </div>
                </div>
                <div class="input-area">
                    <label>入职时间:</label>
                    <input class="date-picker" name="entry_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area">
                    <label>登录账号:</label>
                    <input name="account" placeholder="请输入账号"/>
                </div>
                <div class="input-area">
                    <label>账号类型:</label>
                    <div class="tree-select" data-width="315">
                        <select name="role_id"></select>
                    </div>
                </div>
                <div class="input-area textarea">
                    <label>备注信息:</label>
                    <textarea name="description" placeholder="请输入内容"></textarea>
                </div>
            </div>
        </form>
        <div class="edit-container" style="border:none;padding:0 125px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.edit-form');">保存</div>
            </div>
        </div>
    </div>
    <form class="useraction-form" action="" method="post" hidden style="display:none;">
        <input name="search_keyword" style="display: none!important;"/>
        <input name="search_part" style="display: none!important;"/>
        <input name="range_from" style="display: none!important;" value="2020-01-01 00:00:00"/>
        <input name="range_to" style="display: none!important;" value="2100-01-01 00:00:00"/>
    </form>
</div>

<div class="scripts">
    <input hidden class="_userList" value='<?= str_replace("'", "`", json_encode($userList)) ?>'>
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
        var _userList = JSON.parse($('._userList').val());
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
        // var _roleList = JSON.parse($('._roleList').val());
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _curFilterDate = '<?=$curFilterDate?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;

        function searchConfig() {
            makeSelectElem($('select[name="search_part"]'), _partList);

            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);
            if (_filterInfo['tbl_user_part.id']) {
                $('select[name="search_part"]').val(_filterInfo['tbl_user_part.id']);
                $('input[name="search_part"]').val(_filterInfo['tbl_user_part.id']);
            }

            tree_select();

            $('.base-container .nav-position-title').html(_navTitle);

            var _fromDate = makeDateString(makeDateObject('<?= $range_from ?>'));
            var _toDate = makeDateString(makeDateObject('<?= $range_to ?>'));
            var tmpDate = makeDateObject();
            var today = makeDateString(tmpDate);
            var tomorrow = makeDateString(makeDateObject(tmpDate.setDate(tmpDate.getDate() + 1)));
            var yesterday = makeDateString(makeDateObject(tmpDate.setDate(tmpDate.getDate() - 2)));
            var lastweek = makeDateString(makeDateObject(tmpDate.setDate(tmpDate.getDate() - 5)));
            $('.tab-container .tab-item').off('click');
            $('.tab-container .tab-item').on('click', function () {
                var that = $(this);
                var type = that.attr('data-progress');
                var rangeFrom = makeDateObject();
                var rangeTo = makeDateObject();
                switch (type) {
                    case '1': // today
                        rangeFrom = makeDateObject(today + ' 00:00:00');
                        rangeTo = makeDateObject(tomorrow + ' 00:00:00');
                        break;
                    case '2': // yesterday
                        rangeFrom = makeDateObject(yesterday + ' 00:00:00');
                        rangeTo = makeDateObject(today + ' 00:00:00');
                        break;
                    case '3': // 1 week
                        rangeFrom = makeDateObject(lastweek + ' 00:00:00');
                        rangeTo = makeDateObject(tomorrow + ' 00:00:00');
                        break;
                }
                var formElem = $('.useraction-form');
                formElem.find('input[name="range_from"]').val(makeDateString(rangeFrom) + ' 00:00:00');
                formElem.find('input[name="range_to"]').val(makeDateString(rangeTo) + ' 00:00:00');
                formElem.attr('action', baseURL + 'reports');
                formElem.submit();
            });
            if (_fromDate == today && _toDate == tomorrow) {
                $('.tab-container .tab-item[data-progress="1"]').attr('data-sel', 1);
            } else if (_fromDate == yesterday && _toDate == today) {
                $('.tab-container .tab-item[data-progress="2"]').attr('data-sel', 1);
            } else if (_fromDate == lastweek && _toDate == tomorrow) {
                $('.tab-container .tab-item[data-progress="3"]').attr('data-sel', 1);
            }

            makeContents();
            $('.report-container').masonry({
                itemSelector: '.grid-item',
            });
        }

        function makeContents() {
            var parts = _partList; //removeDuplicatedObject(_mainList, 'part');
            var content_html = '';
            if (!isHoliday(_curFilterDate)) {
                for (var i = 0; i < parts.length; i++) {
                    var partItem = parts[i];
                    var partTitle = partItem.title;
                    if (_filterInfo['tbl_user_part.id'] && partItem.id != _filterInfo['tbl_user_part.id'])
                        continue;
                    if (!partTitle) partTitle = '未定部门';
                    var partDatas = _mainList.filter(function (a) {
                        return a.part == partTitle;
                    });
                    var userDatas = _userList.filter(function (a) {
                        a.bossStr = '';
                        return a.part == partTitle;
                    });
                    userDatas = userDatas.sort(function (a, b) {
                        if (a.id == partItem.boss_id) {
                            // a.bossStr = ' - 部门负责人';
                            return -1;
                        }
                        if (b.id == partItem.boss_id) {
                            // b.bossStr = ' - 部门负责人';
                            return 1;
                        }
                    });
                    content_html += '<div class="report-part-title">' + partTitle + '</div>';
                    content_html += '<div class="report-container">';
                    var isUserExist = false;
                    for (var j = 0; j < userDatas.length; j++) {
                        var item = userDatas[j];
                        var avatar = baseURL + 'assets/images/icon-profile.png';
                        if (item.avatar != '') avatar = baseURL + item.avatar;
                        var positionStr = item.position;
                        if (!positionStr) positionStr = '未定职位';
                        var reportItem = partDatas.filter(function (a) {
                            return a.author_id == item.id;
                        });
                        if (_filterInfo['queryStr'] && reportItem.length == 0) continue;
                        content_html += '<div class="grid-item">' +
                            '<div>' +

                            '<div class="report-header">' +
                            '<div class="report-no">' + makeNDigit(j + 1, 2) + '</div>' +
                            '<div class="report-avatar" style="background-image: url(' + avatar + ')"' +
                            ' onclick="viewItem(this)" data-id="' + item.id + '"></div>' +
                            '<div class="report-info">' +
                            '<div class="report-info-name">' + item.name + item.bossStr + '</div>' +
                            '<div class="report-info-part">' + positionStr + '</div>' +
                            '</div>' +
                            '<div class="report-date">日期: ' + _curFilterDate.substr(0, 10) + '</div>' +
                            '</div>' +
                            '<div class="report-body">';
                        if (reportItem.length) {
                            reportItem = reportItem[0];
                            var taskDatas = JSON.parse(reportItem.data);
                            for (var k = 0; k < taskDatas.length; k++) {
                                var tItem = taskDatas[k];
                                // if (tItem.desc != '') tItem.status = 1;
                                content_html += '<div class="report-task" data-status="' + tItem.status + '">' +
                                    '<div class="report-task-title">' + (k + 1) + '. ' + tItem.title;
                                if (tItem.desc != '') {
                                    content_html += '<br><span>说明: ' + tItem.desc + '</span>';
                                }
                                content_html += '</div>';
                                content_html += '<div class="report-task-status"></div>' +
                                    '</div>';
                            }
                        }

                        content_html += '</div>';
                        content_html += '</div>';
                        content_html += '</div>';
                    }
                    content_html += '</div>';
                }
            }
            $('.content-table').html(content_html);
            $('.report-container').each(function (idx, elem) {
                var that = $(elem);
                if(!that.html()){
                    that.prev().remove();
                    that.remove();
                }
            })
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function viewItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            setRangeFrom('<?= $range_from?>');
            setRangeTo('<?= $range_to?>');
            setSearchKeyword(window.location);
            setPreviousKeyword($('input[name="search_keyword"]').val());
            setPreviousPart($('input[name="search_part"]').val());
            var rangeFrom = makeDateObject();
            var rangeTo = makeDateObject();
            rangeFrom = makeDateObject(rangeFrom.setMonth(rangeFrom.getMonth() - 1));
            var formElem = $('.useraction-form');
            formElem.find('input[name="search_keyword"]').val('');
            formElem.find('input[name="search_part"]').val('');
            formElem.find('input[name="range_from"]').val(makeDateString(rangeFrom) + ' 00:00:00');
            formElem.find('input[name="range_to"]').val(makeDateString(rangeTo) + ' 23:59:59');
            formElem.attr('action', baseURL + 'reports/viewlist/' + id);
            formElem.submit();
        }

        function editItem(elem) {
            var editElem = $('.edit-area');
            makeSelectElem(editElem.find('select[name="part_id"]'),
                _partList, function (e) {
                    var that = editElem.find('select[name="part_id"]');
                    var id = that.val();
                    var tmpPositions = _positionList.filter(function (a) {
                        return a.part_id == id;
                    });
                    makeSelectElem(editElem.find('select[name="position_id"]'), tmpPositions);
                }
            );
            makeSelectElem(editElem.find('select[name="position_id"]'), []);
            makeSelectElem(editElem.find('select[name="rank_id"]'), _rankList);
            makeSelectElem(editElem.find('select[name="role_id"]'), _roleList);

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            if (!elem) {
                editElem.find('.content-title span').html('新增人员');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新增人员');
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                _editItemId = 0;
            } else {
                editElem.find('.content-title span').html('编辑人员信息');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 编辑人员信息');
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    editElem.find('select[name="part_id"]').val(mainItem.part_id);
                    editElem.find('select[name="part_id"]').trigger('change');
                    editElem.find('input[name="name"]').val(mainItem.name);
                    editElem.find('input[name="phone"]').val(mainItem.phone);
                    editElem.find('input[name="email"]').val(mainItem.email);
                    editElem.find('input[name="account"]').val(mainItem.account);
                    editElem.find('input[name="entry_date"]').val(mainItem.entry_date);
                    editElem.find('textarea[name="description"]').val(mainItem.description);
                    editElem.find('select[name="rank_id"]').val(mainItem.rank_id);
                    editElem.find('select[name="role_id"]').val(mainItem.role_id);
                    editElem.find('select[name="position_id"]').val(mainItem.position_id);

                    editElem.find('input[name="entry_date"]').handleDtpicker('setDate', makeDateObject(mainItem.entry_date));
                    tree_select();
                }
            }

            editElem.fadeIn('fast');
        }

        var _isProcessing = false;

        function editPerform(elem) {
            var that = $(elem);

            if (_isProcessing) return;
            _isProcessing = true;
            $('.modal-container[data-type="modal"]').fadeIn('fast');
            $(".uploading-progress").fadeIn('fast');

            var fdata = new FormData(that[0]);
            fdata.append("id", _editItemId);
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