<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/projects.css') ?>">
<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <div class="tab-item" data-progress="-1">全部任务
                <div class="tab-number">9</div>
            </div>
            <div class="tab-item" data-progress="0">未接收
                <div class="tab-number"></div>
            </div>
            <div class="tab-item" data-progress="1">进行中
                <div class="tab-number"></div>
            </div>
            <div class="tab-item" data-progress="2">待验收
                <div class="tab-number">999<sup>+</sup></div>
            </div>
            <div class="tab-item" data-progress="3">已完成
                <div class="tab-number">999<sup>+</sup></div>
            </div>
            <input style="display:none;" name="_progress"/>
            <!--            <div class="tab-search" style="justify-content: flex-start;padding-left: 15px;">-->
            <!--                <div class="input-area">-->
            <!--                    <div class="btn-circle btn-blue" style="font-size: 14px;" onclick="editItem();">-->
            <!--                        <i class="fa fa-plus"></i>新增任务-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <div class="tab-search">
                <div class="input-area" style="padding-left: 15px;">
                    <input name="search_keyword" placeholder="请输入内容"/>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
                <div class="btn-back btn-grey btn-fontgrey" onclick="goToPreviousPage()"><i class="fa fa-angle-left"></i>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">任务列表
            <div>
                <div class="btn-circle btn-blue" onclick="downloadItems();"><i class="fa fa-download"></i> 导出列表</div>
            </div>
            <!--            <div>-->
            <!--                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i>新增任务</div>-->
            <!--            </div>-->
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>任务编号</th>
                    <th>任务名称</th>
                    <th>任务负责人</th>
                    <th>任务分值</th>
                    <th>所属项目</th>
                    <th>项目负责人</th>
                    <th>任务状态</th>
                    <th width="100">发布时间</th>
                    <th width="100">接收时间</th>
                    <th width="100">提交时间</th>
                    <th width="100">验收时间</th>
                    <th width="100">截止时间</th>
                    <th width="130">操作</th>
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

    <div class="edit-area" data-type="view">
        <div class="content-title"><span></span>
            <div>
                <div style="text-align: right; margin-right: 50px;font-size: 20px;">
                    任务编号: <label name="no"></label>
                </div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <div class="edit-container">
            <div class="input-area">
                <label name="score"></label>
                <label>(分) 任务分值</label>
            </div>
            <div class="input-area">
                <label name="worker"></label>
                <label>任务负责人</label>
            </div>
            <br>
            <div class="input-area">
                <label>任务名称:</label>
                <label name="title"></label>
            </div>
            <div class="input-area">
                <label>所属项目:</label>
                <label name="project"></label>
            </div>
            <div class="input-area">
                <label>项目负责人:</label>
                <label name="project_worker"></label>
            </div>
            <div class="input-area">
                <label>发布时间:</label>
                <label name="published_at"></label>
            </div>
            <div class="input-area" data-type="started_at">
                <label>接收时间:</label>
                <label name="started_at"></label>
            </div>
            <div class="input-area" data-type="provided_at">
                <label>提交时间:</label>
                <label name="provided_at"></label>
            </div>
            <div class="input-area" data-type="completed_at">
                <label>验收时间:</label>
                <label name="completed_at"></label>
            </div>
            <div class="input-area">
                <label>截止时间:</label>
                <label name="deadline"></label>
            </div>
            <br>
            <div class="input-area textarea">
                <label>任务描述:</label>
                <label name="description"></label>
            </div>
        </div>
        <!--        <div class="edit-container" style="border:none;padding:0 80px;">-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="deleteItem(this);">删除-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="viewTasks(this);">查看任务-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="editItem(this);">编辑项目-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="completeItem(this);">-->
        <!--                    确认验收-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
    <div class="edit-area" data-type="edit">
        <div class="content-title"><span>当前任务简介</span>
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
                    <select name="part_id" placeholder="全部"></select>
                </div>
                <div class="input-area">
                    <label>职位:</label>
                    <select name="position_id"></select>
                </div>
                <div class="input-area">
                    <label>职级:</label>
                    <select name="rank_id"></select>
                </div>
                <div class="input-area">
                    <label>入职时间:</label>
                    <input class="date-picker" name="entry_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area">
                    <label>登录账号:</label>
                    <input name="account" placeholder="请输入账号"/>
                </div>
                <div class="input-area">
                    <label>账号类型:</label>
                    <select name="role_id"></select>
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
    <div class="exportTbl" style="display: none;">
        <table id="exportTbl" data-file="Table">
            <thead></thead>
            <tbody></tbody>
        </table>
    </div>
</div>


<div class="scripts">
    <input hidden class="_mainList" value='<?= str_replace("'","`",json_encode($list)) ?>'>
    <input hidden class="_progressCnt" value='<?= json_encode($progressCnt) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('<?= $menu; ?>');
        $(function () {
            searchConfig();
        });

        var _mainList = JSON.parse($('._mainList').val());
        var _progressCnt = JSON.parse($('._progressCnt').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _project = '<?= $project; ?>';
        var _projectTitle = '<?= $projectTitle; ?>';
        var _progress = parseInt('<?= $progress; ?>');
        var _titleStr = ['全部', '未接收', '进行中', '待验收', '已完成'];
        var _editItemId = 0;

        function searchConfig() {

            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);

            var tabElems = $('.tab-container');
            tabElems.find('.tab-item').each(function (idx, elem) {
                elem = $(elem);
                elem.off('click');
                elem.on('click', function () {
                    var that = $(this);
                    var progress = parseInt(that.attr('data-progress'));
                    if (progress !== _progress) {
                        $('input[name="_progress"]').val(progress);
                        location.replace(_apiRoot + 'viewlists/<?= $menu ?>/<?= $project?>/' + progress);
                    }
                });
                if (idx == 0) {
                    var suff = 0;
                    for (var i in _progressCnt) suff += _progressCnt[i];
                    if (suff > 999) suff = '999<sup>+</sup>';
                    elem.find('.tab-number').html(suff);
                    elem.find('.tab-number').attr('data-value',suff);
                    return;
                }
                var suff = parseInt(_progressCnt[idx - 1]);
                if (suff > 999) suff = '999<sup>+</sup>';
                elem.find('.tab-number').html(suff);
                elem.find('.tab-number').attr('data-value',suff);
            })

            $('input[name="_progress"]').val(_progress);
            _navTitle += ' ＞ ' + _titleStr[_progress + 1];
            tabElems.find('.tab-item[data-progress="' + _progress + '"]').attr('data-sel', 1);

            $('.base-container .nav-position-title').html(_navTitle);
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function viewItem(elem) {
            $('.edit-area').hide();
            var editElem = $('.edit-area[data-type="view"]');

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });
            var headerTitle = '当前任务简介';
            var that = $(elem);
            var id = that.attr('data-id');
            var mainItem = _mainList.filter(function (a) {
                return a.id == id;
            });
            if (mainItem.length > 0) {
                mainItem = mainItem[0];
                _editItemId = mainItem.id;
                editElem.find('label[name="no"]').html(mainItem.no);
                editElem.find('label[name="title"]').html(mainItem.title);
                editElem.find('label[name="worker"]').html(mainItem.worker);
                editElem.find('label[name="project"]').html(mainItem.project);
                editElem.find('label[name="project_worker"]').html(mainItem.project_worker);
                editElem.find('label[name="published_at"]').html(mainItem.published_at);

                editElem.find('label[name="started_at"]').html(mainItem.started_at);
                if (!mainItem.started_at) editElem.find('div[data-type="started_at"]').hide();
                else editElem.find('div[data-type="started_at"]').show();

                editElem.find('label[name="provided_at"]').html(mainItem.provided_at);
                if (!mainItem.provided_at) editElem.find('div[data-type="provided_at"]').hide();
                else editElem.find('div[data-type="provided_at"]').show();

                editElem.find('label[name="completed_at"]').html(mainItem.completed_at);
                if (!mainItem.completed_at) editElem.find('div[data-type="completed_at"]').hide();
                else editElem.find('div[data-type="completed_at"]').show();

                editElem.find('label[name="score"]').html(mainItem.score);
                editElem.find('label[name="deadline"]').html(mainItem.deadline);
                editElem.find('label[name="description"]').html(mainItem.description);
                editElem.find('div[name="btns"]').attr('data-id', mainItem.id);
            }

            editElem.find('.content-title > span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);

            editElem.fadeIn('fast');
        }

        function editItem(elem) {
            $('.edit-area[data-type="view"]').fadeOut();
            var editElem = $('.edit-area[data-type="edit"]');
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

            var headerTitle = '新增任务';
            if (!elem) {
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                _editItemId = 0;
            } else {
                headerTitle = '编辑任务';
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

            editElem.find('.content-title span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);

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

        function goToPreviousPage() {
            $('input[name="search_keyword"]').val(setPreviousKeyword());
            $('input[name="range_from"]').val('');
            $('input[name="range_to"]').val('');
            $('.useraction-form').attr('action', setSearchKeyword());
            $('.useraction-form').submit();
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
            var ajaxURL = $('.search-form').attr('action');
            ajaxURL = ajaxURL.replace(/viewlists/g, 'downloadViewlist');
            $.ajax({
                type: "post",
                url: ajaxURL,
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
                        var progressStr = ["未接收", "进行中", "待验收", "已完成"];
                        var headers = [
                            '序号', '任务编号', '任务名称', '任务负责人', '任务分值',
                            '所属项目', '项目负责人', '任务状态',
                            '发布时间', '接收时间', '提交时间',
                            '验收时间', '截止时间'
                        ];
                        var datas = [];
                        var retData = res.data;
                        var scoreSum = 0;
                        for (var i = 0; i < retData.length; i++) {
                            var item = retData[i];
                            scoreSum += item.score * 1;
                            datas.push([
                                i + 1, item.no, item.title, item.worker, item.score,
                                item.project, item.project_worker, progressStr[item.progress],
                                item.published_at, item.started_at, item.provided_at,
                                item.completed_at, item.deadline
                            ]);
                            if (i == retData.length - 1) {
                                datas.push([
                                    '', '', '总计', '', Math.round(scoreSum * 100) / 100,
                                    '', '', '',
                                    '', '', '',
                                    '', ''
                                ])
                            }
                        }
                        initTableData(headers, datas);
                        prepareExport2Excel(_projectTitle + '项目 - 任务列表');
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