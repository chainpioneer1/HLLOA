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
            <!--            <div class="tab-search">-->
            <!--                <div class="input-area">-->
            <!--                    <input name="search_keyword" placeholder="请输入内容"/>-->
            <!--                    <div class="btn-fontgrey"><i class="fa fa-search"></i></div>-->
            <!--                </div>-->
            <!--                <div class="btn-back btn-grey btn-fontgrey"><i class="fa fa-angle-left"></i></div>-->
            <!--            </div>-->
            <div class="tab-search" style="justify-content: flex-start;">
                <div class="input-area">
                    <label style="margin-left: 20px;">姓名:</label>
                    <input name="search_keyword" placeholder="请输入内容"/>
                    <label>日期:</label>
                    <div class="range-selector">
                        <input name="range_from" style="display: none!important;" value="<?= $range_from; ?>"/>
                        <input name="range_to" style="display: none!important;" value="<?= $range_to; ?>"/>
                        <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                        <input type="text" placeholder="请选择" name="range_disp"/>
                    </div>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">绩效列表
            <div>
                <div class="btn-circle btn-blue" onclick="downloadTotalItems();" style="margin-right: 20px;"><i
                            class="fa fa-download"></i> 导出列表和详情
                </div>
                <div class="btn-circle btn-blue" onclick="downloadItems();"><i class="fa fa-download"></i> 导出列表</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>排序</th>
                    <th>头像</th>
                    <th>姓名</th>
                    <th>工作岗位</th>
                    <th>所属部门</th>
                    <th>月份</th>
                    <th>绩效分数</th>
                    <th width="150px">操作</th>
                </tr>
                </thead>
                <tbody><?= $tbl_content; ?></tbody>
            </table>
        </div>
        <div class="content-pagination">
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

    <div class="exportTbl" style="display: none;">
        <table id="exportTbl" data-file="Table">
            <thead></thead>
            <tbody></tbody>
        </table>
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

        function searchConfig() {
            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);

            $('.base-container .nav-position-title').html(_navTitle);
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


    <!--    Excel Downloading Parts -->
    <!--    <script src="--><? //= base_url('assets/js/export_table/jquery-3.3.1.js') ?><!--"></script>-->
    <script src="<?= base_url('assets/js/export_table/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/export_table/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/export_table/jszip.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/export_table/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/export_table/vfs_fonts.js') ?>"></script>
    <script src="<?= base_url('assets/js/export_table/buttons.html5.min.js') ?>"></script>
    <script>
        function downloadItems() {
            if (_isProcessing) return;
            _isProcessing = true;
            var frmData = new FormData($('.search-form')[0]);

            $.ajax({
                type: "post",
                url: _apiRoot + "downloadAction",
                contentType: false,
                cache: false,
                processData: false,
                data: frmData,
                success: function (res) {
                    try {
                        res = JSON.parse(res)
                    } catch (e) {
                        alert(JSON.stringify(e));
                        return;
                    }
                    if (res.status == 'success') {
                        var headers = ['排序', '姓名', '工作岗位', '所属部门', '月份', '绩效分数'];
                        var datas = [];
                        var retData = res.data;
                        for (var i = 0; i < retData.length; i++) {
                            var item = retData[i];
                            datas.push([
                                i + 1,
                                item.name,
                                item.position,
                                item.part,
                                item.task_completed,
                                item.user_score
                            ]);
                        }
                        initTableData(headers, datas);
                        prepareExport2Excel('绩效统计');
                        export2Excel();
                    } else { //failed
                        alert(res.data);
                    }
                    _isProcessing = false;
                },
                fail: function () {
                    _isProcessing = false;
                }
            });
        }

        function downloadTotalItems() {
            if (_isProcessing) return;
            _isProcessing = true;
            var frmData = new FormData($('.search-form')[0]);

            $.ajax({
                type: "post",
                url: _apiRoot + "downloadActionDetail",
                contentType: false,
                cache: false,
                processData: false,
                data: frmData,
                success: function (res) {
                    try {
                        res = JSON.parse(res)
                    } catch (e) {
                        alert(JSON.stringify(e));
                        return;
                    }
                    if (res.status == 'success') {
                        var headers0 = [];
                        var headers1 = ['排序', '姓名', '工作岗位', '所属部门', '月份', '绩效分数'];
                        var headers2 = ['序号', '任务编号', '任务名称', '任务负责人', '所属项目', '项目负责人',
                            '任务分值', '任务状态', '发布时间', '接收时间', '提交时间', '验收时间', '截止时间','',''];

                        var progressStr = ["未接收", "进行中", "待验收", "已完成"];
                        for (var i = 0; i < headers2.length; i++) {
                            if (i > headers0.length - 1) headers0[i] = '';
                            if (i > headers1.length - 1) headers1[i] = '';
                        }
                        var datas = [];
                        var retData = res.data;
                        var rr = 0;
                        for (var i = 0; i < retData.length; i++) {
                            var item = retData[i];
                            rr++;
                            datas.push([rr, '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
                            rr++;
                            datas.push([
                                rr, '',
                                headers1[0],
                                headers1[1],
                                headers1[2],
                                headers1[3],
                                headers1[4],
                                headers1[5], '', '', '', '', '', '', ''
                            ]);
                            rr++;
                            datas.push([
                                rr, '',
                                i + 1,
                                item.name,
                                item.position,
                                item.part,
                                item.task_completed,
                                item.user_score, '', '', '', '', '', '', ''
                            ]);
                            rr++;
                            datas.push([
                                rr, '',
                                headers2[0],
                                headers2[1],
                                headers2[2],
                                headers2[3],
                                headers2[4],
                                headers2[5],
                                headers2[6],
                                headers2[7],
                                headers2[8],
                                headers2[9],
                                headers2[10],
                                headers2[11],
                                headers2[12]
                            ]);
                            var details = item.details;
                            for (var k = 0; k < details.length; k++) {
                                var dItem = details[k];
                                rr++;
                                datas.push([
                                    rr, '',
                                    k + 1,
                                    dItem.no,
                                    dItem.title,
                                    dItem.worker,
                                    dItem.project,
                                    dItem.project_worker,
                                    dItem.score,
                                    progressStr[dItem.progress],
                                    (dItem.published_at ? dItem.published_at : '- -'),
                                    (dItem.started_at ? dItem.started_at : '- -'),
                                    (dItem.provided_at ? dItem.provided_at : '- -'),
                                    (dItem.completed_at ? dItem.completed_at : '- -'),
                                    (dItem.deadline ? dItem.deadline : '- -')
                                ])
                            }
                        }
                        console.log(datas);
                        initTableData(headers0, datas);
                        prepareExport2Excel('绩效统计');
                        export2Excel();
                    } else { //failed
                        alert(res.data);
                    }
                    _isProcessing = false;
                },
                fail: function () {
                    _isProcessing = false;
                }
            });
        }

        function initTableData(headerList, dataList) {
            if (!headerList) headerList = [];
            if (!dataList) dataList = [];
            var headerHtml = '';
            headerHtml += '<tr>';
            for (var i = 0; i < headerList.length; i++) {
                var item = headerList[i];
                headerHtml += '<th>' + item + '</th>';
            }
            headerHtml += '</tr>';

            var dataHtml = '';
            for (var i = 0; i < dataList.length; i++) {
                var item = dataList[i];
                dataHtml += '<tr>';
                for (var j = 0; j < headerList.length; j++) {
                    dataHtml += '<td>' + item[j] + '</td>';
                }
                dataHtml += '</tr>';
            }
            if (headerHtml == '') headerHtml = '<tr><th></th></tr>';
            if (dataHtml == '') dataHtml = '<tr><td></td></tr>';
            $('.exportTbl').html(
                '<table id="exportTbl" data-file="统计数据">' +
                '<thead>' + headerHtml + '</thead>' +
                '<tbody>' + dataHtml + '</tbody>' +
                '</table>'
            );
        }

        function prepareExport2Excel(title) {
            if (!title) title = $('#exportTbl').attr('data-file');
            $('#exportTbl').DataTable({
                dom: 'Bfrtip',
                // buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    extension: '.xlsx',
                    autoFilter: true,
                    filename: title
                }]
            });
        }

        function export2Excel(table) {
            setTimeout(function () {
                if (!table)
                    $('.exportTbl .dt-button.buttons-excel').click();
                else
                    table.buttons.exportData({})
            }, 5);
        }

        $('.scripts').remove();
    </script>
    <!--    Excel Downloading Parts end-->
</div>