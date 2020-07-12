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
                    <label>部门:</label>
                    <div class="tree-select" data-width="200">
                        <select name="search_part"></select>
                    </div>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">人员列表
            <div>
                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i> 新增人员</div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>头像</th>
                    <th>姓名</th>
                    <th>账号</th>
                    <th>职位</th>
                    <th>职级</th>
                    <th>联系电话</th>
                    <th>邮箱</th>
                    <th>部门</th>
                    <th>入职日期</th>
                    <th>人员状态</th>
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
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
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
                <div class="input-area">
                    <label>在职状态:</label>
                    <div class="tree-select" data-width="315">
                        <select name="status">
                            <option value="1">在职</option>
                            <option value="0">离职</option>
                        </select>
                    </div>
                </div>
                <div class="input-area" data-name="status" style="display: none;">
                    <label>离职时间:</label>
                    <input class="date-picker" name="leave_date" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area">
                    <label>绩效统计:</label>
                    <div class="tree-select" data-width="315">
                        <select name="is_calc_score">
                            <option value="1">计算</option>
                            <option value="0">不计算</option>
                        </select>
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
    <input hidden class="_partList" value='<?= str_replace("'","`",json_encode($partList)) ?>'>
    <input hidden class="_positionList" value='<?= str_replace("'","`",json_encode($positionList)) ?>'>
    <input hidden class="_rankList" value='<?= str_replace("'","`",json_encode($rankList)) ?>'>
    <input hidden class="_roleList" value='<?= str_replace("'","`",json_encode($roleList)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'","`",json_encode($list)) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('11');
        $(function () {
            searchConfig();
        });

        var _partList = JSON.parse($('._partList').val());
        var _positionList = JSON.parse($('._positionList').val());
        var _rankList = JSON.parse($('._rankList').val());
        var _roleList = JSON.parse($('._roleList').val());
        var _mainList = JSON.parse($('._mainList').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;

        function searchConfig() {

            makeSelectElem($('select[name="search_part"]'), _partList);

            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);
            if (_filterInfo['tbl_user_part.id']) $('select[name="search_part"]').val(_filterInfo['tbl_user_part.id']);

            tree_select();
            $('.base-container .nav-position-title').html(_navTitle);

        }

        function searchItems() {
            $('.search-form').submit();
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
            editElem.find('select[name="status"]').off('change');
            editElem.find('select[name="status"]').on('change', function () {
                var that = $(this);
                var status = that.val();
                if (status == '0') editElem.find('div[data-name="status"]').show();
                else editElem.find('div[data-name="status"]').hide();
            });

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });
            editElem.find('div[data-name="status"]').hide();

            if (!elem) {
                editElem.find('.content-title span').html('新增人员');
                $('.base-container .nav-position-title').html(_navTitle + ' ＞ 新增人员');
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                editElem.find('select[name="status"]').val('1');
                editElem.find('select[name="is_calc_score"]').val('1');
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
                    editElem.find('select[name="status"]').val(mainItem.status);
                    editElem.find('select[name="is_calc_score"]').val(mainItem.is_calc_score);

                    editElem.find('input[name="entry_date"]').handleDtpicker('setDate', makeDateObject(mainItem.entry_date));
                    if (mainItem.leave_date)
                        editElem.find('input[name="leave_date"]').handleDtpicker('setDate', makeDateObject(mainItem.leave_date));
                    else
                        editElem.find('input[name="leave_date"]').handleDtpicker('setDate', makeDateObject());
                    if (mainItem.status == '0') editElem.find('div[data-name="status"]').show();
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