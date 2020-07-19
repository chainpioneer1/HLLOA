<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <!--            <div class="tab-item" data-type="-1" data-sel="1">全部-->
            <!--                <div class="tab-number">9</div>-->
            <!--            </div>-->
            <div class="tab-item" data-progress="0">本月
                <!--                            <div class="tab-number">99</div>-->
            </div>
            <div class="tab-item" data-progress="1">上月
                <!--                            <div class="tab-number">999</div>-->
            </div>
            <div class="tab-item" data-progress="2">本季度
                <!--                            <div class="tab-number">999<sup>+</sup></div>-->
            </div>
            <div class="tab-item" data-progress="3">本年度
                <!--                            <div class="tab-number">999<sup>+</sup></div>-->
            </div>
            <!--            <div class="tab-search" style="justify-content: flex-end;">-->
            <!--                <div class="input-area">-->
            <!--                    <label style="margin-left: 20px;">姓名:</label>-->
            <!--                    <input name="search_name" placeholder="请输入内容"/>-->
            <!--                    <label style="margin-left: 20px;">收支类型:</label>-->
            <!--                    <div class="tree-select" data-width="200">-->
            <!--                        <select name="search_type"></select>-->
            <!--                    </div>-->
            <!--                    <label>月份:</label>-->
            <!--                    <div class="range-selector">-->
            <!--                        <input name="range_from" style="display: none!important;" value="-->
            <? //= $range_from; ?><!--"/>-->
            <!--                        <input name="range_to" style="display: none!important;" value="-->
            <? //= $range_to; ?><!--"/>-->
            <!--                        <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>-->
            <!--                        <input type="text" placeholder="请选择" name="range_disp"/>-->
            <!--                    </div>-->
            <!--                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
    </form>
    <form class="useraction-form" action="" method="post" hidden style="display:none;">
        <input name="search_keyword" style="display: none!important;" value="<?= $search_keyword; ?>"/>
        <input name="range_from" style="display: none!important;" value="<?= $range_from; ?>"/>
        <input name="range_to" style="display: none!important;" value="<?= $range_to; ?>"/>
    </form>
    <div class="content-area">
        <div class="content-title">公司收支数据
            <div>
                <!--                <div class="btn-circle btn-red" onclick="export2Excel();" style="margin-right: 20px;"><i-->
                <!--                            class="fa fa-download"></i> 导出数据-->
                <!--                </div>-->
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <div class="payment-info">
                <div class="info-item">
                    <div>进行中项目</div>
                    <div>12</div>
                </div>
                <div class="info-item">
                    <div>公司收入</div>
                    <div>12</div>
                </div>
                <div class="info-item">
                    <div>公司支出</div>
                    <div>12</div>
                </div>
                <div class="info-item">
                    <div>公司收益</div>
                    <div>12</div>
                </div>
                <div class="info-item">
                    <div>项目毛利润</div>
                    <div>12</div>
                </div>
            </div>
            <div class="payment-info">
                <div class="info-item info-weather">
                    <iframe src="http://i.tianqi.com/index.php?c=code&id=13"></iframe>
                </div>
                <div class="info-item info-graph">
                    <div>公司收入</div>
                    <div>12</div>
                </div>
            </div>
        </div>
        <!--<div class="content-pagination">
            <?php echo $this->pagination->create_links(); ?>
            <div class="scripts">
                <script>
                    appendPagination('<?= $curPage; ?>', '<?= $perPage; ?>',
                        '<?= $cntPage; ?>', '<?= $apiRoot; ?>');
                </script>
            </div>
        </div>-->
    </div>
    <div class="edit-area">
        <div class="content-title"><span>新增收支</span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <div class="input-area">
                    <label>*收支对象:</label>
                    <input name="title" placeholder="请输入名称" type="text"/>
                </div>
                <br/>
                <div class="input-area">
                    <label>银行账号:</label>
                    <input name="bank_account" placeholder="请输入联系人电话" type="text" maxlength="11"/>
                </div>
                <div class="input-area">
                    <label>开户银行:</label>
                    <input name="bank_name" placeholder="请输入您的邮箱号" type="text"/>
                </div>
                <div class="input-area">
                    <label>开户人:</label>
                    <input name="bank_user" placeholder="请输入您的邮箱号" type="text"/>
                </div>
                <div class="input-area">
                    <label>*收支金额:</label>
                    <input name="price" placeholder="请输入您的邮箱号" type="number"/>
                </div>
                <div class="input-area">
                    <label>收支类型:</label>
                    <div class="tree-select" data-width="315">
                        <select name="type"></select>
                    </div>
                </div>
                <div class="input-area">
                    <label>所属项目:</label>
                    <div class="tree-select" data-width="315">
                        <select name="project_id"></select>
                    </div>
                </div>
                <div class="input-area">
                    <label>*收支日期:</label>
                    <input class="date-picker" name="paid_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
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
    <div class="exportTbl" style="display: none;"></div>
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
        // var _projectList = JSON.parse($('._projectList').val());
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _progress = '<?= $progress; ?>';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;
        var _statusList = [{id: 0, title: '主营业务收入'}, {id: 1, title: '其他业务收入'},
            {id: 2, title: '项目成本支出'}, {id: 3, title: '费用支出'}];

        function searchConfig() {
            $('.base-container .nav-position-title').html(_navTitle);

            var tabElems = $('.tab-container');
            tabElems.find('.tab-item').each(function (idx, elem) {
                elem = $(elem);
                var idx = parseInt(elem.attr('data-progress'));
                elem.off('click');
                elem.on('click', function () {
                    var that = $(this);
                    var progress = parseInt(that.attr('data-progress'));
                    if (progress != _progress) {
                        $('input[name="_progress"]').val(progress);
                        location.replace(_apiRoot + 'companydata/' + progress);
                    }
                });
            })

            $('input[name="_progress"]').val(_progress);
            tabElems.find('.tab-item[data-progress="' + _progress + '"]').attr('data-sel', 1);
            var projectCnt = _mainList.project_cnt;
            var totalGet = 0;
            var totalOut = 0;
            var totalProfit = 0;
            var projectGet = 0;
            for (var i = 0; i < _mainList.company_data.length; i++) {
                var item = _mainList.company_data[i];
                if (item.type == 0 || item.type == 1) totalGet += item.price_total * 1;
                if (item.type == 2 || item.type == 3) totalOut += item.price_total * 1;
                if (item.project_id > 0) projectGet += item.price_total * 1;
            }
            var infoData = [projectCnt,
                '￥' + totalGet.toFixed(2), '￥' + totalOut.toFixed(2),
                '￥' + (totalGet + totalOut).toFixed(2),
                '￥' + projectGet.toFixed(2)
            ];
            var cardElem = $('.payment-info:first-child .info-item div:last-child');

            for (var i = 0; i < cardElem.length; i++) {
                var elem = $(cardElem[i]);
                elem.html(infoData[i]);
            }
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

        function editTable(elem) {
            window.open(baseURL + 'userprices/manage', '_self');
        }

        function editItem(elem) {
            var editElem = $('.edit-area');
            makeSelectElem(editElem.find('select[name="project_id"]'), _projectList);
            makeSelectElem(editElem.find('select[name="type"]'), _statusList);

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            if (!elem) {
                editElem.find('.content-title span').html('新增收支');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新增收支');
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                _editItemId = 0;
            } else {
                editElem.find('.content-title span').html('编辑收支信息');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 编辑收支信息');
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    console.log(mainItem);
                    editElem.find('input[name="title"]').val(mainItem.title);

                    editElem.find('input[name="bank_name"]').val(mainItem.bank_name);
                    editElem.find('input[name="bank_account"]').val(mainItem.bank_account);
                    editElem.find('input[name="bank_user"]').val(mainItem.bank_user);

                    editElem.find('input[name="price"]').val(mainItem.price);
                    editElem.find('select[name="type"]').val(mainItem.type);
                    editElem.find('select[name="project_id"]').val(mainItem.project_id);

                    editElem.find('textarea[name="description"]').val(mainItem.description);

                    editElem.find('input[name="paid_date"]').handleDtpicker('setDate', makeDateObject(mainItem.paid_date));

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

        function deleteItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            showConfirm(baseURL + 'assets/images/modal/modal-confirm-top.png',
                '', '您确定要删除这个收支录入吗?', function () {
                    $.ajax({
                        type: "post",
                        url: _apiRoot + "deleteItem",
                        dataType: "json",
                        data: {id: id},
                        success: function (res) {
                            if (res.status == 'success') {
                                showNotify('<i class="fa fa-check"></i> 删除成功');
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
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
    <!--    <script src="--><? //= base_url('assets/plugins/export_table/jquery-3.3.1.js') ?><!--"></script>-->
    <script src="<?= base_url('assets/plugins/export_table/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/export_table/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/export_table/jszip.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/export_table/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/export_table/vfs_fonts.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/export_table/buttons.html5.min.js') ?>"></script>
    <script>
        function exportConfig() {
            var headerData = [];
            var listData = [];
            var headerElems = $('.content-table thead th');
            var listElems = $('.content-table tbody tr');
            for (var i = 0; i < headerElems.length; i++) headerData.push($(headerElems[i]).html());
            for (var i = 0; i < listElems.length; i++) {
                var colElems = $(listElems[i]).find('td');
                var rowData = [];
                if ($(listElems[i]).attr('data-id') == '-1') rowData = ['', ''];
                for (var j = 0; j < colElems.length; j++) {
                    rowData.push($(colElems[j]).html());
                }
                listData.push(rowData);
            }
            initTableData(headerData, listData);
            prepareExport2Excel()
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
                '<table id="exportTbl" data-file="工资列表">' +
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
    </script>
    <!--    Excel Downloading Parts end-->
</div>