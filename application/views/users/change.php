<?php
$item = $userInfo[0];
$titleStr = '修改密码';
?>
<div class="base-container">

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
                <div class="input-area">
                    <label>旧密码:</label>
                    <input name="password_old" placeholder="请输入旧密码" type="password"/>
                </div>
                <br>
                <div class="input-area">
                    <label>新密码:</label>
                    <input name="password_new" placeholder="请输入旧密码" type="password"/>
                    <div class="txt-red">请输入由字母和数字组成的8个字符长度的密码</div>
                </div>
                <div class="input-area">
                    <label>确认新密码:</label>
                    <input name="cpassword_new" placeholder="请输入旧密码" type="password"/>
                </div>
            </div>
        </form>
        <div class="edit-container" style="border:none;padding:20px 125px;">
            <div class="input-area" style="margin: 0;text-align: center;">
                <div class="btn-rect btn-blue" style="width: 210px;" onclick="editPerform('.edit-form');">保存</div>
            </div>
        </div>
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
            var titleStr = '<?= $titleStr?>';
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
                    'background-image': 'url(' + baseURL + mainItem.avatar + ')',
                    'background-color': 'white',
                });
            }
            editElem.fadeIn('fast');
        }

        var _isProcessing = false;

        function editPerform(elem) {
            var that = $(elem);

            var pwd0 = that.find('input[name="password_old"]').val();
            var pwd = that.find('input[name="password_new"]').val();
            var cpwd = that.find('input[name="cpassword_new"]').val();

            var isValid = '';
            if (pwd == pwd0) isValid = '密码没有改变了';
            if (pwd != cpwd) isValid = '确认密码不匹配';
            if (!pwd) isValid = '请输入新密码';
            if (!pwd0) isValid = '请输入旧密码';

            if (isValid) {
                alert(isValid);
                return;
            }

            if (_isProcessing) return;
            _isProcessing = true;
            $('.modal-container[data-type="modal"]').fadeIn('fast');
            $(".uploading-progress").fadeIn('fast');

            var fdata = new FormData(that[0]);
            fdata.append("id", _editItemId);
            $.ajax({
                url: _apiRoot + "updatePwd",
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
                    history.back();
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