<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <div class="tab-search" style="justify-content: flex-start;">
                <div class="input-area">
                    <label style="margin-left: 20px;">关键字:</label>
                    <input name="search_keyword" placeholder="请输入内容"/>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">账号类型列表
            <div>
                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i> 新增类型</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>编码</th>
                    <th>类型名称</th>
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
    <div class="edit-area" style="height: auto;margin-bottom: 60px;">
        <div class="content-title"><span>新增类型</span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <div class="input-area">
                    <label>账号类型编码:</label>
                    <input name="no" placeholder="请输入类型编码" type="text"/>
                </div>
                <div class="input-area">
                    <label>账号类型名称:</label>
                    <input name="title" placeholder="请输入类型名称" type="text" maxlength="11"/>
                </div>
                <br/>
                <input name="permission" hidden/>
                <div class="input-area permission">
                    <div><input type="checkbox" class="menuItem" data-id="m0" data-p="m0"/>首页</div>
                    <div><input type="checkbox" class="menuItem" data-id="m14" data-p="m0"/>日报大厅</div>
                    <div><input type="checkbox" class="menuItem" data-id="m1" data-p="m0"/>任务大厅</div>
                    <div><input type="checkbox" class="menuItem" data-id="m2" data-p="m0"/>项目大厅</div>
                    <div><input type="checkbox" class="menuItem" data-id="m3" data-p="m0"/>绩效中心</div>
                    <div><input type="checkbox" class="menuItem" data-id="m4" data-p="m0"/>我的任务</div>
                    <div><input type="checkbox" class="menuItem" data-id="m5" data-p="m0"/>我的项目</div>
                    <div></div>
                    <div style="margin-top: 20px;">
                        <input type="checkbox" class="menuItem" data-id="m6"/>项目管理
                    </div>
                    <div style="margin-top: 20px;">
                        <input type="checkbox" class="menuItem" data-id="m15"/>项目统筹
                    </div>
                    <div></div>
                    <div></div>
                    <div style="margin-top: 20px;">
                        <input type="checkbox" class="menuItem" data-id="m7"/>行政管理
                    </div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m8" data-p="m7"/>部门管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m9" data-p="m7"/>职位管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m10" data-p="m7"/>职级管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m11" data-p="m7"/>人员管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m13" data-p="m7"/>工资管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m12" data-p="m7"/>公告信息管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m21" data-p="m7"/>账号类型管理</div>
                    <div></div>
                    <div style="margin-top: 20px;">
                        <input type="checkbox" class="menuItem" data-id="m16"/>财务管理
                    </div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m17" data-p="m16"/>合同管理</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m18" data-p="m16"/>公司收支录入</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m19" data-p="m16"/>公司收支统计</div>
                    <div><input type="checkbox" class="menuItem submenu" data-id="m20" data-p="m16"/>项目收支统计</div>
                </div>
            </div>
        </form>
        <div class="edit-container" style="border:none;padding:0 125px;margin-bottom: 60px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.edit-form');">保存</div>
            </div>
        </div>
    </div>
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

        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;

        function searchConfig() {
            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);

            $('.base-container .nav-position-title').html(_navTitle);

            $('.permission > div input[type="checkbox"]').off('click');
            $('.permission > div input[type="checkbox"]').on('click', function () {
                var that = $(this);
                var id = that.attr('data-id');
                if (id == 'm7' || id == 'm16') {
                    console.log('--- id, checked', id, that[0].checked);
                    $('.permission > div input[data-p="' + id + '"]').prop('checked',
                        that[0].checked
                    )
                }
                var checkElems = $('.permission > div input[type="checkbox"]');
                var permission = {};
                for (var i = 0; i < checkElems.length; i++) {
                    var item = $(checkElems[i]);
                    var kId = item.attr('data-id');
                    permission[kId] = (item[0].checked ? 1 : 0);
                }
                $('.edit-area input[name="permission"]').val(JSON.stringify(permission));
            });

        }

        function searchItems() {
            $('.search-form').submit();
        }

        function editItem(elem) {
            var editElem = $('.edit-area');

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            if (!elem) {
                editElem.find('.content-title span').html('新增账号类型');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新增账号类型');
                editElem.find('input').val('');
                editElem.find('input[type="checkbox"]').prop('checked', false);
                editElem.find('input[type="checkbox"][data-p="m0"]').click();
                _editItemId = 0;
            } else {
                editElem.find('.content-title span').html('编辑账号类型');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 编辑账号类型');
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    editElem.find('input[name="no"]').val(mainItem.no);
                    editElem.find('input[name="title"]').val(mainItem.title);

                    var permission = JSON.parse(mainItem.permission);
                    var keys = Object.keys(permission);
                    console.log('--- permission keys: ', keys);
                    editElem.find('input[type="checkbox"]').prop('checked', false);
                    for (var i = 0; i < keys.length; i++) {
                        var kId = keys[i];
                        if (permission[kId] == 0) continue;
                        if(kId == 'm7'|| kId=='m16'){
                            editElem.find('input[data-id="' + kId + '"]').prop('checked', true);
                            continue;
                        }
                        editElem.find('input[data-id="' + kId + '"]').click();
                    }

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