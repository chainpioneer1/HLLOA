<?php
$item = $userInfo[0];
$titleStr = '用户信息';
?>
<div class="base-container">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/users_profile.css') ?>">

    <div class="nav-position-title"></div>
    <div class="edit-area" style="display: block;">
        <div class="content-title"><span></span>
            <div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <div class="avatar-area">
                    <!--                    <div class="btn-transparent" data-id="" onclick=""></div>-->
                    <div class="item-info-left">
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
                    <div class="item-info-right">
                        <div><label name="account"></label></div>
                        <div data-name="imgFile"
                             class="btn-rect btn-red" onclick="selectFile(this);">上传图片
                        </div>
                    </div>
                </div>
                <br>
                <div class="input-area">
                    <label>用户名:</label>
                    <label name="name"></label>
                </div>
                <div class="input-area">
                    <label>手机号:</label>
                    <label name="phone"></label>
                </div>
                <div class="input-area">
                    <label>入职时间:</label>
                    <label name="entry_date"></label>
                </div>
                <div class="input-area">
                    <label>所属部门:</label>
                    <label name="part"></label>
                </div>
                <div class="input-area">
                    <label>职位:</label>
                    <label name="position"></label>
                </div>
                <div class="input-area">
                    <label>职级:</label>
                    <label name="rank"></label>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="scripts">
    <input hidden class="_mainList" value='<?= str_replace("'","`",json_encode($userInfo)) ?>'>

    <script>
        selectMenu('-1');
        $(function () {
            searchConfig();
        });

        var _mainList = JSON.parse($('._mainList').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _editItemId = 0;

        function searchConfig() {
            $('.base-container .nav-position-title').html(_navTitle);
            editItem();
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function editItem(elem) {
            var editElem = $('.edit-area');
            // makeSelectElem(editElem.find('select[name="part_id"]'),
            //     _partList, function (e) {
            //         var that = editElem.find('select[name="part_id"]');
            //         var id = that.val();
            //         var tmpPositions = _positionList.filter(function (a) {
            //             return a.part_id == id;
            //         });
            //         makeSelectElem(editElem.find('select[name="position_id"]'), tmpPositions);
            //     }
            // );

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                history.back();
                // $('.base-container .nav-position-title').html(_navTitle);
                // editElem.fadeOut('fast');
            });
            var titleStr = '<?= $titleStr; ?>'
            editElem.find('.content-title span').html(titleStr);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + titleStr);
            // var that = $(elem);
            // var id = that.attr('data-id');
            var mainItem = _mainList;
            if (mainItem.length > 0) {
                mainItem = mainItem[0];
                _editItemId = mainItem.id;
                editElem.find('label[name="account"]').html(mainItem.account);
                editElem.find('label[name="name"]').html(mainItem.name);
                editElem.find('label[name="phone"]').html(mainItem.phone);
                editElem.find('label[name="email"]').html(mainItem.email);
                editElem.find('label[name="entry_date"]').html(mainItem.entry_date);
                editElem.find('label[name="part"]').html(mainItem.part);
                editElem.find('label[name="position"]').html(mainItem.position);
                editElem.find('label[name="rank"]').html(mainItem.rank);
                editElem.find('label[name="description"]').html(mainItem.description);
                editElem.find('.img_preview').css({
                    'background-image': 'url('+baseURL + mainItem.avatar+')',
                    'background-color': 'white',
                });
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
                url: _apiRoot + "updateAvatar",
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

        $('input[type="file"]').on('click', function (object) {
            $(this).val('');
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
            editPerform($('.edit-form'));
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