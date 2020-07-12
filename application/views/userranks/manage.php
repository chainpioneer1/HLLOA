<style>
    .edit-area.modal-container .modal-body {
        max-height: unset;
    }

    .edit-area.modal-container div.input-area {
        padding: 15px 0;
    }
</style>
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
                    <!--                    <label>部门:</label>-->
                    <!--                    <select name="search_part"></select>-->
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">职级列表
            <div>
                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i> 新增职级</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>职级名称</th>
                    <th>标准绩效</th>
                    <th>职级系数</th>
                    <th>岗位工资（元）</th>
                    <th>绩效工资（元）</th>
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
                    <label>职级名称:</label>
                    <input name="title" placeholder="请输入职级名称" type="text"/>
                </div>
                <div class="input-area">
                    <label>标准绩效:</label>
                    <input name="standard_factor" placeholder="请输入标准绩效" type="number"/>
                </div>
                <div class="input-area">
                    <label>职级系数:</label>
                    <input name="rank_factor" placeholder="请输入职级系数" type="number"/>
                </div>
                <div class="input-area">
                    <label>岗位工资(元):</label>
                    <input name="gangwei_price" placeholder="请输入岗位工资" type="number"/>
                </div>
                <div class="input-area">
                    <label>绩效工资(元):</label>
                    <input name="jixiao_price" placeholder="请输入绩效工资" type="number"/>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-blue" data-type="yes" onclick="editPerform('.edit-form')">保存</button>
        </div>
    </div>
</div>


<div class="scripts">
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('10')
        $(function () {
            searchConfig();
        });

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

        function editItem(elem) {
            var editElem = $('.edit-area');
            var headerTitle = '新增职级';
            if (!elem) {
                editElem.find('input').val('');
                editElem.find('select').val('');
                _editItemId = 0;
            } else {
                headerTitle = '编辑职级';
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    editElem.find('input[name="title"]').val(mainItem.title);
                    editElem.find('input[name="standard_factor"]').val(mainItem.standard_factor);
                    editElem.find('input[name="rank_factor"]').val(mainItem.rank_factor);
                    editElem.find('input[name="gangwei_price"]').val(mainItem.gangwei_price);
                    editElem.find('input[name="jixiao_price"]').val(mainItem.jixiao_price);
                }
            }
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);
            showEdit(baseURL + 'assets/images/modal/modal-edit-top.png',
                headerTitle, '', function () {

                }, function () {
                    $('.base-container .nav-position-title').html(_navTitle);
                }
            );
            editElem.fadeIn('fast');
        }

        var _isProcessing = false;

        function editPerform(elem) {
            var that = $(elem);

            if (_isProcessing) return;
            _isProcessing = true;
            $(".modal-container").fadeIn('fast');
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
                $(".modal-container").fadeOut('fast');
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
                '', '您确定要删除这个职级吗?', function () {
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
</div>