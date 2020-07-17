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
                    <label style="margin-left: 20px;">关键字:</label>
                    <input name="search_keyword" placeholder="请输入内容"/>
                    <label>合同状态:</label>
                    <div class="tree-select" data-width="200">
                        <select name="search_status"></select>
                    </div>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">合同列表
            <div>
                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i> 新建合同</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>合同编号</th>
                    <th>合同名称</th>
                    <th>合同金额（￥）</th>
                    <th>已回款金额（￥）</th>
                    <th>客户名称</th>
                    <th>项目负责人</th>
                    <th>到期日期</th>
                    <th>签订日期</th>
                    <th>合同状态</th>
                    <th width="250px">操作</th>
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
        <div class="content-title"><span>合同详情</span>
            <div>
                <!--<div style="text-align: right; margin-right: 50px;font-size: 20px;">
                    项目编号: <label name="no"></label>
                </div>-->
                <div class="btn-circle btn-blue" style="font-size: 16px;margin-right:20px;"
                     onclick="showData(this);">
                    <i class="fa fa-file"></i> 查看附件
                </div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <div class="content-table" data-type="summary">
            <table>
                <thead>
                <tr>
                    <th>合同编号</th>
                    <th>合同名称</th>
                    <th>合同金额（￥）</th>
                    <th>已回款金额（￥）</th>
                    <th>客户名称</th>
                    <th>项目负责人</th>
                    <th width="100">到期日期</th>
                    <th width="100">签订日期</th>
                    <th width="100">合同状态</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="content-title" style="padding-top: 30px;"><span>回款金额明细</span></div>
        <div class="content-table" data-type="price-detail">
            <table>
                <thead>
                <tr>
                    <th width="100">序号</th>
                    <th width="200">合同编号</th>
                    <th>合同名称</th>
                    <th width="200">回款金额（￥）</th>
                    <th width="200">回款日期</th>
                    <th width="200">创建时间</th>
                    <th width="150">创建人</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="edit-container" style="border:none;padding:30px 80px;text-align: center">
            <?php if (true || $progress == 0) { ?>
                <div class="input-area" style="margin: 0;text-align: center;">
                    <div class="btn-rect btn-orange" name="btns" onclick="appendPrice(this);">
                        <i class="fa fa-plus"></i> 新增回款金额
                    </div>
                </div>
            <?php } else { ?>
                <div class="input-area" style="margin: 0;text-align: center;">
                    <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="viewTasks(this);">查看任务
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="edit-area" data-type="edit-main">
        <div class="content-title"><span>新建合同</span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <div class="input-area">
                    <label>*合同编号:</label>
                    <input name="no" placeholder="请输入合同编号" type="text"/>
                </div>
                <div class="input-area">
                    <label>*合同名称:</label>
                    <input name="title" placeholder="请输入合同名称" type="text" maxlength="11"/>
                </div>
                <div class="input-area">
                    <label>合同金额:</label>
                    <input name="total_price" placeholder="请输入合同金额" type="text"/>
                </div>
                <div class="input-area">
                    <label>客户名称:</label>
                    <input name="client_name" placeholder="请输入客户名称" type="text"/>
                </div>
                <div class="input-area">
                    <label>*签订日期:</label>
                    <input class="date-picker" name="signed_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area">
                    <label>*到期日期:</label>
                    <input class="date-picker" name="expire_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area" data-name="status">
                    <label>合同状态:</label>
                    <div class="tree-select" data-width="315">
                        <select name="progress"></select>
                    </div>
                </div>
                <div class="input-area textarea">
                    <label>备注信息:</label>
                    <textarea name="description" placeholder="请输入内容"></textarea>
                </div>

                <input name="type" type="text" value="<?= $type ?>"
                       hidden style="display: none"/>
                <div class="input-area docarea" style="width: 19.5%;">
                    <label>附件:</label>
                    <!--                        <textarea name="data" placeholder="请输入内容"></textarea>-->
                    <div class="btn-uploader" style="width: 130px;height:80px;">
                        <div data-name="docFile"
                             class="doc_preview" onclick="selectFile(this);">
                            <div class="plus-btn transition">
                                <i class="fa fa-plus"></i>
                                <i class="fa fa-trash-alt"></i>
                            </div>
                        </div>
                        <input data-name="docFile" name="docFileFormat" hidden style="display: none"/>
                        <input name="docFile"
                               class="form-control" hidden style="display: none"
                               accept=".doc,.docx,.pdf" type="file"/>
                    </div>
                </div>
            </div>
        </form>
        <div class="edit-container" style="border:none;padding:0 125px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.edit-form');">保存</div>
            </div>
        </div>
    </div>
</div>


<div class="edit-area modal-container" data-type="edit">
    <div class="confirm-modal" tabindex="-1">
        <img src="<?= base_url('assets/images/modal/modal-edit-top.png') ?>"/>
        <div class="modal-header">
            <div></div>
            <button class="btn-fontgrey" data-type="close"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-body">
            <form class="edit-form" action="" method="post">
                <div class="input-area">
                    <label>*增加金额:</label>
                    <input name="price" placeholder="请输入金额" type="number"/>
                </div>
                <div class="input-area" style="position: relative;">
                    <label>*回款日期:</label>
                    <input class="date-picker" name="paid" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area textarea">
                    <label>备注信息:</label>
                    <textarea name="description" placeholder="请输入内容"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-blue" data-type="yes">保存</button>
        </div>
    </div>
</div>


<div class="scripts">
    <input hidden class="_userList" value='<?= str_replace("'", "`", json_encode($userList)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('17');
        $(function () {
            searchConfig();
        });

        var _userList = JSON.parse($('._userList').val());
        var _statusList = [{id: 0, title: "未签"}, {id: 1, title: "已签"},
            {id: 2, title: "已完成"}, {id: 3, title: "终止"}];
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;
        var _type = 0;

        function searchConfig() {

            makeSelectElem($('select[name="search_status"]'), _statusList);

            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);
            if (_filterInfo['tbl_contracts.status']) $('select[name="search_status"]').val(_filterInfo['tbl_contracts.status']);

            tree_select();
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
            var headerTitle = '合同详情';
            var that = $(elem);
            var id = that.attr('data-id');
            makeDetailTable(id);

            // editElem.find('.content-title > span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);
            editElem.find('.edit-container .btn-rect').attr('data-id', id);
            editElem.find('.content-title .btn-circle').attr('data-id', id);
            editElem.fadeIn('fast');
        }

        function showData(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            window.open(baseURL + 'payment/viewData/' + id);
        }

        function makeDetailTable(id) {
            if (!id) return;
            var mainItem = _mainList.filter(function (a) {
                return a.id == id;
            });
            if (mainItem.length > 0) {
                mainItem = mainItem[0];

                var priceDetail = mainItem.price_detail;
                if (priceDetail) priceDetail = JSON.parse(priceDetail);
                else priceDetail = [];
                var priceTotal = 0;
                var detail_html = '';
                for (var i = 0; i < priceDetail.length; i++) {
                    var item = priceDetail[i];
                    priceTotal += item.price * 1;
                    var userItem = _userList.filter(function (a) {
                        return a.id == mainItem.planner_id;
                    })[0];
                    detail_html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + mainItem.no + '</td>' +
                        '<td>' + mainItem.title + '</td>' +
                        '<td>' + item.price + '</td>' +
                        '<td>' + item.paid + '</td>' +
                        '<td>' + item.created + '</td>' +
                        '<td>' + userItem.name + '</td>' +
                        '<td>' + item.description + '</td>' +
                        '</tr>';
                }
                $('.edit-area .content-table[data-type="price-detail"] tbody').html(detail_html);

                var statusStr = _statusList.filter(function (a) {
                    return a.id == mainItem.progress;
                });
                if (statusStr.length) statusStr = statusStr[0].title;
                else statusStr = '';
                var summary_html = '<tr>' +
                    '<td>' + mainItem.no + '</td>' +
                    '<td>' + mainItem.title + '</td>' +
                    '<td>' + mainItem.total_price + '</td>' +
                    '<td>' + priceTotal + '</td>' +
                    '<td>' + mainItem.client_name + '</td>' +
                    '<td>' + (mainItem.worker ? mainItem.worker : '') + '</td>' +
                    '<td>' + mainItem.expire_date + '</td>' +
                    '<td>' + mainItem.signed_date + '</td>' +
                    '<td>' + statusStr + '</td>' +
                    '</tr>';
                $('.edit-area .content-table[data-type="summary"] tbody').html(summary_html);
            }

        }

        function appendPrice(elem) {
            var editElem = $('.edit-area.modal-container[data-type="edit"]');
            var headerTitle = '新增回款金额';
            if (!elem) {
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                _editItemId = 0;
            } else {
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                headerTitle = '新增回款金额';
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                }
            }
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ 合同详情 ＞ ' + headerTitle);
            showEdit(baseURL + 'assets/images/modal/modal-edit-top.png',
                headerTitle, '', function () {
                    var priceDetail = mainItem.price_detail;
                    if (priceDetail) priceDetail = JSON.parse(priceDetail);
                    else priceDetail = '[]';
                    var modalElem = $('.edit-area.modal-container[data-type="edit"]');
                    var priceItem = {
                        price: modalElem.find('input[name="price"]').val(),
                        paid: modalElem.find('input[name="paid"]').val(),
                        description: modalElem.find('textarea[name="description"]').val()
                    };
                    priceDetail.push(priceItem);

                    $.ajax({
                        type: "post",
                        url: _apiRoot + "updatePriceDetail",
                        dataType: "json",
                        data: {
                            id: _editItemId,
                            price: priceItem.price,
                            paid: priceItem.paid,
                            description: priceItem.description,
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                _mainList.filter(function (a) {
                                    if (a.id == _editItemId) {
                                        a.price_detail = res.data.price_detail;
                                        a.paid_price = res.data.paid_price;
                                    }
                                });
                                $('.content-area table td > div.btn-rect[data-id="'+_editItemId+'"]')
                                    .parent().parent().find('.paid_price')
                                    .html((res.data.paid_price*1).toFixed(2));
                                makeDetailTable(_editItemId);
                                showNotify('<i class="fa fa-check"></i> 添加记录成功');
                                // location.reload();
                            } else { //failed
                                alert(res.data);
                            }
                        }
                    });
                }, function () {
                    $('.base-container .nav-position-title').html(_navTitle + ' ＞ 项目详情');
                }
            );
            editElem.fadeIn('fast');
        }

        function editItem(elem) {
            var editElem = $('.edit-area[data-type="edit-main"]');
            makeSelectElem(editElem.find('select[name="progress"]'),
                _statusList, function (e) {
                }
            );

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            if (!elem) {
                editElem.find('.content-title span').html('新建合同');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新建合同');
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                editElem.find('div[data-name="status"]').hide();
                editElem.find('.doc_preview').removeAttr('data-sel');
                _editItemId = 0;
            } else {
                editElem.find('.content-title span').html('编辑合同信息');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 编辑合同信息');
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    editElem.find('div[data-name="status"]').show();
                    editElem.find('input[name="no"]').val(mainItem.no);
                    editElem.find('input[name="title"]').val(mainItem.title);
                    editElem.find('input[name="total_price"]').val(mainItem.total_price);
                    editElem.find('input[name="client_name"]').val(mainItem.client_name);
                    editElem.find('input[name="signed_date"]').val(mainItem.signed_date);
                    editElem.find('input[name="expire_date"]').val(mainItem.expire_date);
                    editElem.find('select[name="progress"]').val(mainItem.progress);
                    editElem.find('textarea[name="description"]').val(mainItem.description);

                    editElem.find('input[name="expire_date"]').handleDtpicker('setDate', makeDateObject(mainItem.expire_date));
                    editElem.find('input[name="signed_date"]').handleDtpicker('setDate', makeDateObject(mainItem.signed_date));
                    tree_select();

                    editElem.find('input[type="file"]').val('');
                    var ext = getFiletypeFromURL(mainItem.data);
                    editElem.find('.doc_preview').removeAttr('data-type');
                    if (ext == 'pdf') editElem.find('.doc_preview').attr('data-type', ext);
                    editElem.find('.doc_preview').attr('data-sel', 1);
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
            fdata.append("type", _type);
            $.ajax({
                url: _apiRoot + "updateContract",
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

        function selectFile(elem) {
            var that = $(elem);
            var name = that.attr('data-name');
            $('input[name="' + name + '"]').click();
        }

        $('input[type="file"]')
            .on('click', function (object) {
                var that = $(this);
                var name = that.attr('name');
                that.val('');
                that.parent().find('div[data-name="' + name + '"]').removeAttr('data-sel');
            })
            .on('change', function () {
                var name = $(this).attr('name');
                var totalStr = this.files[0].name;
                var realNameStr = getFilenameFromURL(totalStr);
                var type = getFiletypeFromURL(realNameStr);
                if (name == 'imgFile') {
                    if (type != 'jpg' && type != 'jpeg'
                        && type != 'png' && type != 'bmp' && type != 'gif') {
                        alert('图片格式不正确..');
                        return;
                    }
                } else if (name == 'docFile') {
                    if (type != 'doc' && type != 'docx' && type != 'pdf') {
                        alert('文档格式不正确..');
                        return;
                    }
                    var previewer = $('div.doc_preview[data-name="' + name + '"]');
                    previewer.attr('data-sel', 1);
                    previewer.attr('data-type', type);
                } else {
                    if (type != 'jpg' && type != 'jpeg' && type != 'png' && type != 'bmp' && type != 'gif'
                        && type != 'docx' && type != 'doc'
                        && type != 'ppt' && type != 'pptx'
                        && type != 'pdf'
                        && type != 'html' && type != 'htm'
                        && type != 'mp4' && type != 'mp3'
                        && type != 'zip') {
                        alert('课程内容格式不正确..');
                        return;
                    }
                }
                $('.name-view[data-name="' + name + '"]').html(realNameStr);
                $('input[data-name="' + name + '"]').val(type);
                preview_image(name, this.files[0]);
            });

    </script>
</div>