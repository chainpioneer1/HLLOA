<?php
$type = $this->session->userdata('filter');
if ($type) $type = $type[$mainModel . '.type'];
$titleStr = ['公告', '庆祝'];
$curTitle = $titleStr[$type];
?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/projects.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/posts.css') ?>">
<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <!--            <div class="tab-item" data-type="all" data-sel="1">全部-->
            <!--                <div class="tab-number">9</div>-->
            <!--            </div>-->
            <div class="tab-item" data-type="0"><?= $titleStr[0] ?>栏
                <div class="tab-number">0</div>
            </div>
            <div class="tab-item" data-type="1"><?= $titleStr[1] ?>栏
                <div class="tab-number">0</div>
            </div>
            <input style="display:none;" name="_type"/>
            <div class="tab-search" style="padding-left: 15px;">
                <div class="input-area">
                    <div class="btn-circle btn-blue" style="font-size: 16px;" onclick="editItem();">
                        <i class="fa fa-plus"></i> 创建<?= $curTitle; ?>栏
                    </div>
                </div>
            </div>
            <!--            <div class="tab-search">-->
            <!--                <div class="input-area" style="padding-left: 15px;">-->
            <!--                    <input name="search_keyword" placeholder="请输入内容"/>-->
            <!--                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>-->
            <!--                </div>-->
            <!--                <div class="btn-back btn-grey btn-fontgrey"><i class="fa fa-angle-left"></i></div>-->
            <!--            </div>-->
        </div>
    </form>

    <div class="content-area">
        <div class="content-table">
            <?php
            foreach ($list as $item) {
                if ($item->type != $type) continue;
                if ($item->type == 1) {

                    ?>
                    <div class="content-item" data-type="<?= $item->type; ?>">
                        <div>
                            <div>
                                <div class="btn-transparent" data-id="<?= $item->id; ?>"
                                     onclick="editItem(this);"></div>
                                <img src="<?= base_url($item->data); ?>">
                                <div class="project-btns transition btn-blue" onclick="deleteItem(this);"
                                     data-id="<?= $item->id; ?>">
                                    <div class="btn-rect" data-id="4"><i class="fa fa-minus-circle"></i> 删除</div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else {
                    $ext = explode('.', $item->data);
                    $ext = $ext[count($ext) - 1];
                    if ($ext != 'pdf') $ext = 'word';
                    ?>
                    <div class="content-item" data-type="<?= $item->type; ?>">
                        <div>
                            <div class="item-info">
                                <div class="btn-transparent" data-id="<?= $item->id; ?>"
                                     onclick="editItem(this);"></div>
                                <div class="item-info-left"><?= substr($item->update_time, 0, 10); ?></div>
                                <div class="item-info-right">
                                    <div>
                                        <i class="fa fa-file-<?= $ext?>" style="font-size: 26px;color:#5f68e6;"></i>
                                        <?= $item->title; ?>
                                    </div>
                                </div>
                                <div class="project-btns transition btn-blue" onclick="deleteItem(this);"
                                     data-id="<?= $item->id; ?>">
                                    <div class="btn-rect" data-id="4"><i class="fa fa-minus-circle"></i> 删除</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
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
                    项目编号: <label name="no"></label>
                </div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <div class="edit-container">
            <div class="input-area">
                <label name="total_score"></label>
                <label>(分) 项目总分</label>
            </div>
            <div class="input-area">
                <label name="worker"></label>
                <label>项目负责人</label>
            </div>
            <br>
            <div class="input-area">
                <label>项目名称:</label>
                <label name="title"></label>
            </div>
            <div class="input-area">
                <label>项目金额:</label>
                <label name="init_price"></label>
            </div>
            <div class="input-area">
                <label>合同金额:</label>
                <label name="work_price"></label>
            </div>
            <div class="input-area">
                <label>项目发布时间:</label>
                <label name="published_at"></label>
            </div>
            <div class="input-area">
                <label>项目截止日期:</label>
                <label name="deadline"></label>
            </div>
            <br>
            <div class="input-area textarea">
                <label>项目描述:</label>
                <label name="description"></label>
            </div>
        </div>
        <div class="edit-container" style="border:none;padding:0 80px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="deleteItem(this);">删除
                </div>
            </div>
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="editItem(this);">编辑项目
                </div>
            </div>
        </div>
    </div>
    <div class="edit-area" data-type="edit">
        <div class="content-title"><span></span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <input name="type" type="text" value="<?= $type ?>"
                       hidden style="display: none"/>
                <?php if ($type == 0) { ?>
                    <div class="input-area">
                        <label><?= $curTitle ?>标题:</label>
                        <input name="title" placeholder="请输入主标题" type="text"/>
                    </div>
                    <div class="input-area docarea">
                        <label><?= $curTitle ?>内容:</label>
                        <!--                        <textarea name="data" placeholder="请输入内容"></textarea>-->
                        <div class="btn-uploader">
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
                <?php } else { ?>
                    <div class="input-area textarea">
                        <label>上传图片:</label>
                        <input name="title" type="text" hidden style="display: none;"/>
                        <div class="btn-uploader">
                            <div data-name="imgFile"
                                 class="img_preview" onclick="selectFile(this);">
                                <div class="plus-btn transition">
                                    <i class="fa fa-plus"></i>
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                            <input data-name="imgFile" name="imgFileFormat" hidden style="display: none"/>
                            <input name="imgFile"
                                   class="form-control" hidden style="display: none"
                                   accept=".png,.jpg,.bmp,.gif,.jpeg" type="file"/>
                        </div>
                    </div>
                <?php } ?>
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
    <input hidden class="_userList" value='<?= str_replace("'", "`", json_encode($userList)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>
    <input hidden class="_typeCnt" value='<?= json_encode($typeCnt) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('<?= $menuId; ?>');
        $(function () {
            searchConfig();
        });

        var _userList = JSON.parse($('._userList').val());
        _userList = _userList.filter(function (a) {
            a.title = a.name;
            return true;
        });
        var _mainList = JSON.parse($('._mainList').val());
        var _typeCnt = JSON.parse($('._typeCnt').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _type = 0;
        var _titleStr = ['<?= $titleStr[0]; ?>栏', '<?= $titleStr[1]; ?>栏'];
        var _editItemId = 0;

        function searchConfig() {
            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);

            var tabElems = $('.tab-container');
            tabElems.find('.tab-item').each(function (idx, elem) {
                elem = $(elem);
                elem.off('click');
                elem.on('click', function () {
                    var that = $(this);
                    var progress = parseInt(that.attr('data-type'));
                    if (progress != _type) {
                        $('input[name="_type"]').val(progress);
                        searchItems();
                    }
                });

                var suff = parseInt(_typeCnt[idx]);
                if (suff > 999) suff = '999<sup>+</sup>';
                elem.find('.tab-number').html(suff);
                elem.find('.tab-number').attr('data-value', suff);
            })

            if (_filterInfo[_mainObj + '.type'] !== '') {
                _type = parseInt(_filterInfo[_mainObj + '.type']);
                $('input[name="_type"]').val(_type);
                _navTitle += ' ＞ ' + _titleStr[_type];
                tabElems.find('.tab-item[data-type="' + _type + '"]').attr('data-sel', 1);
            }

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
            var headerTitle = '项目简介';
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
                editElem.find('label[name="init_price"]').html('￥' + mainItem.init_price);
                editElem.find('label[name="work_price"]').html('￥' + mainItem.work_price);
                editElem.find('label[name="started_at"]').html(mainItem.started_at);
                editElem.find('label[name="published_at"]').html(mainItem.published_at);
                editElem.find('label[name="total_score"]').html(mainItem.total_score);
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
            // makeSelectElem(editElem.find('select[name="worker_id"]'),
            //     _userList, function (e) {
            //         var that = editElem.find('select[name="worker_id"]');
            //         var id = that.val();
            //     }
            // );

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });
            var headerTitle = '新增' + _titleStr[_type];
            if (!elem) {
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                editElem.find('.doc_preview').removeAttr('data-sel');
                editElem.find('.img_preview').removeAttr('data-sel');
                if (_type == 1) {
                    editElem.find('.img_preview').css({
                        'background-image': 'none'
                    });
                }
                _editItemId = 0;
            } else {
                headerTitle = '编辑' + _titleStr[_type];
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;
                    if (_type == 1) {
                        editElem.find('.img_preview').css({
                            'background-image': 'url(' + baseURL + mainItem.data + ')'
                        });
                        editElem.find('.img_preview').attr('data-sel', 1);
                    } else {
                        editElem.find('input[name="title"]').val(mainItem.title);
                        editElem.find('label[name="title"]').html(mainItem.title);
                        var ext = getFiletypeFromURL(mainItem.data);
                        editElem.find('.doc_preview').removeAttr('data-type');
                        if(ext == 'pdf') editElem.find('.doc_preview').attr('data-type', ext);
                        editElem.find('.doc_preview').attr('data-sel', 1);

                    }
                }
            }

            editElem.find('.content-title span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);

            editElem.fadeIn('fast');
        }

        var _isProcessing = false;

        function editPerform(elem) {
            var that = $(elem);
            $('input[name="type"]').val(_type);
            if (_type == 1) {
                $('input[name="title"]').val('庆祝栏');
            }
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
                '', '您确定要删除这个' + _titleStr[_type] + '吗?', function () {
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

        function selectFile(elem) {
            var that = $(elem);
            var name = that.attr('data-name');
            $('input[name="' + name + '"]').click();
        }

        $('input[type="file"]').on('click', function (object) {
            var that = $(this);
            var name = that.attr('name');
            that.val('');
            that.parent().find('div[data-name="' + name + '"]').removeAttr('data-sel');
        });
        $('input[type="file"]').on('change', function () {
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

        function preview_image(name, file) {
            if (name != 'imgFile' && name != '7') return;
            var previewer = $('div.img_preview[data-name="' + name + '"]');
            var reader = new FileReader();
            reader.onloadend = function () {
                previewer.css({
                    'background-image': 'url(' + reader.result + ')'
                })
                previewer.attr('data-sel', 1);
            };
            if (file) {
                reader.readAsDataURL(file);//reads the data as a URL
            } else {
                previewer.css({
                    'background-image': '#f0f0f0'
                })
            }
        }


    </script>
</div>