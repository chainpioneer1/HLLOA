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
    <div class="edit-area">
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
                    <input name="no" placeholder="请输入名称" type="text"/>
                </div>
                <div class="input-area">
                    <label>*合同名称:</label>
                    <input name="title" placeholder="请输入联系人电话" type="text" maxlength="11"/>
                </div>
                <div class="input-area">
                    <label>合同金额:</label>
                    <input name="total_price" placeholder="请输入您的邮箱号" type="text"/>
                </div>
                <div class="input-area">
                    <label>客户名称:</label>
                    <input name="client_name" placeholder="请输入您的邮箱号" type="text"/>
                </div>
                <div class="input-area">
                    <label>签订日期:</label>
                    <input class="date-picker" name="signed_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area">
                    <label>到期日期:</label>
                    <input class="date-picker" name="expire_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area" data-name="status">
                    <label>合同状态:</label>
                    <div class="tree-select" data-width="315">
                        <select name="status"></select>
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
</div>


<div class="scripts">
    <input hidden class="_mainList" value='<?= str_replace("'","`",json_encode($list)) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('17');
        $(function () {
            searchConfig();
        });

        var _statusList = [{id:0,title:"未签"}, {id:1,title:"已签"}, {id:2,title:"已完成"}, {id:3,title:"终止"}];
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;

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

        function editItem(elem) {
            var editElem = $('.edit-area');
            makeSelectElem(editElem.find('select[name="status"]'),
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
                    editElem.find('select[name="status"]').val(mainItem.status);
                    editElem.find('textarea[name="description"]').val(mainItem.description);

                    editElem.find('input[name="expire_date"]').handleDtpicker('setDate', makeDateObject(mainItem.expire_date));
                    editElem.find('input[name="signed_date"]').handleDtpicker('setDate', makeDateObject(mainItem.signed_date));
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