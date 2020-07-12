<?php
$progress = $this->session->userdata('filter');
if ($progress) $progress = $progress[$mainModel . '.progress'];
?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/owl-carousel/owl.carousel.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/projects.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/home.css') ?>">
<div class="base-container">
    <div class="content-area-item2x">
        <div class="post-title">公告
            <div class="show-more" onclick="showPosts();"><span>更多 >></span></div>
        </div>
        <?php
        $i = 0;
        foreach ($list as $item) {
            if ($item->type != 0) continue;
            $i++;
            if ($i > 3) break;
            $ext = explode('.', $item->data);
            $ext = $ext[count($ext) - 1];
            if($ext !='pdf') $ext = 'word';
            ?>
            <div class="message-item transition">
                <div class="content-area transition" onclick="viewItem(this);" data-id="<?= $item->id; ?>">
                    <div class="item2x-small">
                        <div class="item2x-small-left"><?= substr($item->update_time, 0, 10); ?></div>
                        <div class="item2x-small-middle">
                            <div class="item2x-small-right-top">
                                <i class="fa fa-file-<?= $ext?>" style="font-size: 30px;color:#5f68e6;"></i>
                                <?= $item->title; ?>
                            </div>
                        </div>
                        <div class="item2x-small-right">
                            <span>查询详情 >></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="content-area-item2x">
        <div class="post-title">荣誉墙</div>
        <div>
            <div class="content-area">
                <div class="item2x-big">
                    <div class="item2x-big-right">
                        <div class="owl-carousel">
                            <?php
                            $i = 0;
                            foreach ($list as $item) {
                                if ($item->type != 1) continue;
                                $i++;
                                if ($i > 5) break;
                                ?>
                                <div class="owl-item">
                                    <img src="<?= base_url($item->data); ?>">
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="item2x-big-left">
                        <img src="<?= base_url(); ?>assets/images/home/home01.png">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="content-table">
            <span>本月绩效排名</span>
        </div>
        <div class="content-table" data-type="userList"></div>
    </div>
</div>


<div class="scripts">
    <script src="<?= base_url('assets/plugins/owl-carousel/owl.carousel.min.js') ?>"></script>
    <input hidden class="_userList" value='<?= str_replace("'", "`", json_encode($userList)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>

    <script>
        selectMenu('0');
        $(function () {
            searchConfig();
            makeUserList();

            $('.owl-carousel').owlCarousel({
                items: 1,
                dots: true,
                autoplay: true,
                loop: true,
                mouseDrag: true,
                touchDrag: true,
                nav: true,
                navText: ["<i class='fa fa-angle-left'></i>",
                    "<i class='fa fa-angle-right'></i>"],
                smartSpeed: 1500
            })
        });

        var _userList = JSON.parse($('._userList').val());
        _userList = _userList.filter(function (a) {
            a.title = a.name;
            return true;
        });
        _userList = _userList.sort(function (a, b) {
            var ret = 0;
            if (a.id > b.id) ret = 1;
            else if (a.id < b.id) ret = -1;

            if (b.id == b.boss_id) ret = 1;
            else if (a.id == a.boss_id) ret = -1;

            if (a.part > b.part) ret = 1;
            else if (a.part < b.part) ret = -1;

            if (a.user_score * 1 < b.user_score * 1) ret = 1;
            else if (a.user_score * 1 > b.user_score * 1) ret = -1;
            return ret;
        })
        var _mainList = JSON.parse($('._mainList').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';

        function searchConfig() {

        }

        function showPosts() {
            window.open(baseURL + 'posts/viewlist', '_self');
        }

        function makeUserList() {
            var html = '';
            for (var i = 0; i < _userList.length; i++) {
                var item = _userList[i];
                html += '<div class="content-item">' +
                    '<div onclick="viewTasks(this)" data-id="' + item.id + '">' +
                    '<img src="' + baseURL + item.avatar + '">' +
                    '<div class="content-item-no">NO' + (i + 1) + '</div>' +
                    '<div class="content-item-name">' + item.name + '</div>' +
                    '<div class="content-item-action">' +
                    '<div>' +
                    '<label>' + item.user_score + '</label>' +
                    '<span>分</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }
            $('.content-table[data-type="userList"]').html(html);
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function viewTasks(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            setSearchKeyword(window.location);
            location.href = baseURL + 'tasks/useraction/0/' + id;
        }

        function viewItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            // var tmpItem = _mainList.filter(function (a) {
            //     return a.id == id;
            // });
            // if (tmpItem.length == 0) return;
            // tmpItem = tmpItem[0];
            window.open(baseURL + 'posts/view/' + id);
            return;
            showConfirm(baseURL + 'assets/images/modal/modal-notify-top.png',
                tmpItem.title, tmpItem.data, function () {

                }
            );
            $('.confirm-modal .modal-footer button').html('知道了');
            $('.confirm-modal .modal-footer button').css({width: 147});
            var modalContainer = $('.modal-container[data-type="modal"]');
            $('body').append(modalContainer);
            modalContainer.css({
                position: 'fixed',
                width: 'calc(100vw)',
                height: 'calc(100vh)'
            })
        }

        function editItem(elem) {
            $('.edit-area[data-type="view"]').fadeOut();
            var editElem = $('.edit-area[data-type="edit"]');
            makeTreeSearchSelect(editElem.find('select[name="worker_id"]'), _userList,
                '', 'part', 'id', 'title', function (e) {
                    var that = editElem.find('select[name="worker_id"]');
                    var id = that.val();
                });

            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });
            var headerTitle = '新增项目';
            if (!elem) {
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                _editItemId = 0;
            } else {
                headerTitle = '编辑项目';
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
                    editElem.find('select[name="worker_id"]').val(mainItem.worker_id);
                    editElem.find('input[name="init_price"]').val(mainItem.init_price);
                    editElem.find('input[name="work_price"]').val(mainItem.work_price);
                    editElem.find('input[name="total_score"]').val(mainItem.total_score);

                    editElem.find('label[name="no"]').html(mainItem.no);
                    editElem.find('label[name="title"]').html(mainItem.title);
                    editElem.find('label[name="worker"]').html(mainItem.worker);
                    editElem.find('label[name="init_price"]').html('￥' + mainItem.init_price);
                    editElem.find('label[name="work_price"]').html('￥' + mainItem.work_price);
                    editElem.find('label[name="started_at"]').html(mainItem.started_at);
                    editElem.find('label[name="published_at"]').html(mainItem.published_at);
                    editElem.find('label[name="total_score"]').html(mainItem.total_score);

                    editElem.find('input[name="deadline"]').val(mainItem.deadline);
                    editElem.find('textarea[name="description"]').val(mainItem.description);

                    editElem.find('input[name="deadline"]').handleDtpicker('setDate', makeDateObject(mainItem.deadline));

                    tree_search();
                }
            }

            editElem.find('.content-title span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);

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
                '', '您确定要删除这个项目吗?', function () {
                    $.ajax({
                        type: "post",
                        url: _apiRoot + "deleteItem",
                        dataType: "json",
                        data: {id: id},
                        success: function (res) {
                            if (res.status == 'success') {
                                showNotify('<i class="fa fa-check"></i> 删除成功');
                                setTimeout(function () {
                                    //location.reload();
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