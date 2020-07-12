<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <!--            <div class="tab-item" data-type="all" data-sel="1">全部-->
            <!--                <div class="tab-number">9</div>-->
            <!--            </div>-->
            <!--            <div class="tab-item" data-type="published">未开始-->
            <!--                <div class="tab-number">99</div>-->
            <!--            </div>-->
            <!--            <div class="tab-item" data-type="started">进行中-->
            <!--                <div class="tab-number">999</div>-->
            <!--            </div>-->
            <!--            <div class="tab-item" data-type="provided">待验收-->
            <!--                <div class="tab-number">999<sup>+</sup></div>-->
            <!--            </div>-->
            <!--            <div class="tab-item" data-type="completed">已完成-->
            <!--                <div class="tab-number">999<sup>+</sup></div>-->
            <!--            </div>-->
            <div class="tab-search" style="justify-content: flex-start;">
                <div class="input-area" style="padding: 0 20px;">
                    <label>工资表月份:</label>
                    <div class="month-selector">
                        <div class="input-group-addon" onclick="$('#MonthPicker_Button_')[0].click();"><i
                                    class="fa fa-calendar bigger-110"></i></div>
                        <input type="text" placeholder="请选择" name="ref_month" class="monthpicker"
                               value="<?= substr($range_from, 0, 7); ?>" readonly/>
                    </div>
                    <label style="margin-left: 20px;">本月工资日天数:</label>
                    <input name="workdays" placeholder="请输入内容" value="<?= $workdays; ?>"/>
                    <!--                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>-->
                </div>
            </div>
            <div class="tab-search">
                <!--                            <div class="input-area">-->
                <!--                                <input name="search_keyword" placeholder="请输入内容"/>-->
                <!--                                <div class="btn-fontgrey"><i class="fa fa-search"></i></div>-->
                <!--                            </div>-->
                <div class="btn-back btn-grey btn-fontgrey" onclick="location.replace('<?= base_url('userprices') ?>')">
                    <i class="fa fa-angle-left"></i>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">编辑工资信息
            <!--            <div>-->
            <!--                <div class="btn-circle btn-red" onclick="download_table();" style="margin-right: 10px;"><i-->
            <!--                            class="fa fa-download"></i> 导出数据-->
            <!--                </div>-->
            <!--                                <div class="btn-circle btn-green" onclick="editItem();" style="margin-right: 10px;"><i class="fa fa-plus"></i> 添加信息</div>-->
            <!--                <div class="btn-circle btn-blue" onclick="edit_table();"><i class="fa fa-edit"></i> 编辑信息</div>-->
            <!--                <div class="btn-back btn-grey btn-fontgrey"><i class="fa fa-angle-left"></i></div>-->
            <!--            </div>-->
        </div>
        <div class="content-table salary price" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th data-col="a">序号</th>
                    <th data-col="b">部门</th>
                    <th data-col="c">职务</th>
                    <th data-col="d">姓名</th>
                    <th data-col="e">出勤天数</th>
                    <th data-col="f">资质补贴</th>
                    <th data-col="g">补(扣)款</th>
                    <th data-col="h">事假</th>
                    <th data-col="i">事假扣款</th>
                    <th data-col="j">病假</th>
                    <th data-col="k">病假扣款</th>
                    <th data-col="l">调休</th>
                    <th data-col="m">其它假</th>
                    <th data-col="n">养老保险</th>
                    <th data-col="o">医疗保险</th>
                    <th data-col="p">失业保险</th>
                    <th data-col="q">公积金</th>
                    <th data-col="r">个所税</th>
                    <th data-col="s">本月实际合格绩效分</th>
                </tr>
                </thead>
                <tbody><?= $tbl_content; ?></tbody>
            </table>
        </div>
        <form class="table-form" method="post"></form>
        <div class="content-pagination">
            <div class="edit-container" style="border:none;padding:0 125px;">
                <div class="input-area" style="margin: 0;text-align: center;">
                    <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.content-table tbody');">
                        保存
                    </div>
                </div>
            </div>
            <?php echo $this->pagination->create_links(); ?>
            <div class="scripts">
                <script>
                    appendPagination('<?= $curPage; ?>', '<?= $perPage; ?>',
                        '<?= $cntPage; ?>', '<?= $apiRoot; ?>');
                </script>
            </div>
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
        <input name="search_keyword" style="display: none!important;" value="<?= $search_keyword; ?>"/>
        <input name="range_from" style="display: none!important;" value="<?= $range_from; ?>"/>
        <input name="range_to" style="display: none!important;" value="<?= $range_to; ?>"/>
    </form>
</div>

<div class="scripts">
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('<?= $menu; ?>');
        $(function () {
            searchConfig();
        });

        // var _partList = JSON.parse($('._partList').val());
        // var _positionList = JSON.parse($('._positionList').val());
        // var _rankList = JSON.parse($('._rankList').val());
        // var _roleList = JSON.parse($('._roleList').val());
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;
        var _focusTmr = 0;

        function searchConfig() {
            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);
            if (_filterInfo.range_from) $('input[name="range_disp"]').val(_filterInfo.range_from);

            $('.base-container .nav-position-title').html(_navTitle);

            $('input[name="ref_month"]').off('blur');
            $('input[name="ref_month"]').on('blur', function () {
                searchItems();
            });

            $('td input').off('input');
            $('td input').off('keydown');
            $('td input').off('focus');
            $('td input').off('change');
            $('td input').on('change', function () {
                var that = $(this);
                if (parseFloat(that.val()) == 0) {
                    that.val('');
                }
            }).on('focus', function () {
                var that = this;
                clearTimeout(_focusTmr);
                _focusTmr = setTimeout(function () {
                    that.select();
                }, 10);
            }).on('input', function () {
                var that = $(this);
                var id = that.attr('data-id');
                var col = that.attr('data-col');
                var colElems = $('td input[data-col="' + col + '"]');
                var sum = 0;
                for (var i = 0; i < colElems.length; i++) {
                    var item = $(colElems[i]);
                    if (item.attr('data-id') == '-1') continue;
                    if (!item.val()) continue;
                    sum += parseFloat(item.val());
                }
                $('td input[data-id="-1"][data-col="' + col + '"]').val(sum);
            }).on('keydown', function (e) {
                var that = $(this);
                var col = that.attr('data-col');
                var trElem = that.parent().parent();
                switch (e.keyCode) {
                    case 40: // down key
                        if (trElem.next().attr('data-id')) {
                            trElem.next().find('input[data-col="' + col + '"]').focus();
                        }
                        break;
                    case 38: // up key
                        if (trElem.prev().attr('data-id')) {
                            trElem.prev().find('input[data-col="' + col + '"]').focus();
                        }
                        break;
                }

            });
            $('td input').trigger('change');
            $('td input').trigger('input');
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function viewItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            setPreviousKeyword($('input[name="search_keyword"]').val());
            setSearchKeyword(window.location);
            $('input[name="search_keyword"]').val('');
            $('.useraction-form').attr('action', baseURL + 'tasks/useraction/3/' + id);
            $('.useraction-form').submit();
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
            var rows = that.find('tr');
            var jsonData = [];
            var refDate = $('input[name="ref_month"]').val();
            var workdays = $('input[name="workdays"]').val();
            for (var i = 0; i < rows.length; i++) {
                var rowElem = $(rows[i]);
                var cols = rowElem.find('td input');
                var id = rowElem.attr('data-id');
                if (id == '-1') continue;
                var rowData = {id: id, refdate: refDate, workdays: workdays};
                for (var j = 0; j < cols.length; j++) {
                    var colElem = $(cols[j]);
                    var field = colElem.attr('data-col');
                    var val = colElem.val();
                    if (val == "") val = "0";
                    rowData[field] = val;
                }
                jsonData.push(rowData);
            }
            console.log(jsonData);

            if (_isProcessing) return;
            _isProcessing = true;
            $('.modal-container[data-type="modal"]').fadeIn('fast');
            $(".uploading-progress").fadeIn('fast');

            var fdata = new FormData($('.table-form')[0]);
            fdata.append("jsonData", JSON.stringify(jsonData));
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
                console.log(res);
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