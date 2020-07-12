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
        <div class="content-title">部门列表
            <div>
                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i> 新增部门</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>部门名称</th>
                    <th>部门负责人</th>
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
                    <label>部门名称:</label>
                    <input name="title" placeholder="请输入名称" type="text"/>
                </div>
                <div class="input-area">
                    <label>部门负责人:</label>
                    <div class="tree-search" data-width="315">
                        <select name="boss_id"></select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-blue" data-type="yes" onclick="editPerform('.edit-form')">保存</button>
        </div>
    </div>
</div>


<div class="scripts">
    <input hidden class="_userList" value='<?= str_replace("'","`",json_encode($userList)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'","`",json_encode($list)) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('08')
        $(function () {
            searchConfig();
        });

        var _userList = JSON.parse($('._userList').val());
        _userList = _userList.filter(function (a) {
            a.title = a.name;
            return true;
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
            makeTreeSearchSelect(editElem.find('select[name="boss_id"]'), _userList,
                '', 'part', 'id', 'title', function (e) {
                    var that = editElem.find('select[name="boss_id"]');
                    var id = that.val();
                });
            var headerTitle = '新增部门';
            if (!elem) {
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新增部门');
                editElem.find('input').val('');
                editElem.find('select').val('');
                _editItemId = 0;
            } else {
                headerTitle = '编辑部门';
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 编辑部门');
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    editElem.find('select[name="boss_id"]').val(mainItem.boss_id);
                    editElem.find('input[name="title"]').val(mainItem.title);
                    tree_search();
                }
            }
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
                '', '您确定要删除这个部门吗?', function () {
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