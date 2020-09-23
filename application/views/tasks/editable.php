<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/projects.css') ?>">
<div class="base-container">
    <div class="nav-position-title"></div>
    <form class="search-form"
          action="<?= base_url($apiRoot); ?>" method="post">
        <div class="tab-container">
            <div class="tab-item" data-progress="-1">全部任务
                <div class="tab-number">9</div>
            </div>
            <div class="tab-item" data-progress="0">未接收
                <div class="tab-number"></div>
            </div>
            <div class="tab-item" data-progress="1">进行中
                <div class="tab-number"></div>
            </div>
            <div class="tab-item" data-progress="2">待验收
                <div class="tab-number">999<sup>+</sup></div>
            </div>
            <div class="tab-item" data-progress="3">已完成
                <div class="tab-number">999<sup>+</sup></div>
            </div>
            <input style="display:none;" name="_progress"/>
            <?php if (true || $progress == 0 || $progress == 1) { ?>
                <div class="tab-search" style="justify-content: flex-start;padding-left: 15px;">
                    <div class="input-area">
                        <div class="btn-circle btn-blue" style="font-size: 16px;" onclick="editItem();">
                            <i class="fa fa-plus"></i> 新增任务
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="tab-search">
                <div class="input-area" style="padding-left: 15px;">
                    <input name="search_keyword" placeholder="请输入内容"/>
                    <div class="btn-fontgrey" onclick="searchItems();"><i class="fa fa-search"></i></div>
                </div>
                <div class="btn-back btn-grey btn-fontgrey" onclick="goToPreviousPage();"><i
                            class="fa fa-angle-left"></i>
                </div>
            </div>
        </div>
    </form>
    <div class="content-area">
        <div class="content-title">任务列表
            <!--            <div>-->
            <!--                <div class="btn-circle btn-blue" onclick="editItem();"><i class="fa fa-plus"></i>新增任务</div>-->
            <!--            </div>-->
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>任务编号</th>
                    <th width="300">任务名称</th>
                    <th>任务负责人</th>
                    <th>任务分值</th>
                    <th>所属项目</th>
                    <th>项目负责人</th>
                    <th>任务状态</th>
                    <th width="100">发布时间</th>
                    <th width="100">接收时间</th>
                    <th width="100">提交时间</th>
                    <th width="100">验收时间</th>
                    <th width="100">截止时间</th>
                    <th>操作</th>
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
        <div class="content-title"><span></span>
            <div>
                <div style="text-align: right; margin-right: 50px;font-size: 20px;">
                    任务编号: <label name="no"></label>
                </div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <div class="edit-container">
            <div class="input-area">
                <label name="score"></label>
                <label>(分) 任务分值</label>
            </div>
            <div class="input-area">
                <label name="worker"></label>
                <label>任务负责人</label>
            </div>
            <br>
            <div class="input-area">
                <label>任务名称:</label>
                <label name="title"></label>
            </div>
            <div class="input-area">
                <label>所属项目:</label>
                <label name="project"></label>
            </div>
            <div class="input-area">
                <label>项目负责人:</label>
                <label name="project_worker"></label>
            </div>
            <div class="input-area">
                <label>发布时间:</label>
                <label name="published_at"></label>
            </div>
            <div class="input-area" data-type="started_at">
                <label>接收时间:</label>
                <label name="started_at"></label>
            </div>
            <div class="input-area" data-type="provided_at">
                <label>提交时间:</label>
                <label name="provided_at"></label>
            </div>
            <div class="input-area" data-type="completed_at">
                <label>验收时间:</label>
                <label name="completed_at"></label>
            </div>
            <div class="input-area">
                <label>截止时间:</label>
                <label name="deadline"></label>
            </div>
            <br>
            <div class="input-area textarea">
                <label>任务描述:</label>
                <label name="description"></label>
            </div>
        </div>
        <!--        <div class="edit-container" style="border:none;padding:0 80px;">-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="deleteItem(this);">删除-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="viewTasks(this);">查看任务-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="editItem(this);">编辑项目-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="input-area" style="margin: 0;text-align: center;">-->
        <!--                <div class="btn-rect btn-blue" name="btns" style="width: 210px;" onclick="completeItem(this);">-->
        <!--                    确认验收-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
    <div class="edit-area" data-type="edit">
        <div class="content-title"><span></span>
            <div>
                <div style="text-align: right; margin-right: 50px;font-size: 20px;">
                    所属项目: <label name="project"></label>
                </div>
                <div class="btn-circle btn-grey" data-type="close-panel">
                    <i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <form class="edit-form" action="" method="post">
            <div class="edit-container">
                <div class="input-area">
                    <label>任务编号:</label>
                    <input name="no" placeholder="请输入任务编号" type="text"/>
                    <label name="no" style="width: 315px;text-align: left;margin: 0"></label>
                </div>
                <div class="input-area">
                    <label>任务名称:</label>
                    <input name="title" placeholder="请输入任务名称" type="text"/>
                    <label name="title" style="width: 315px;text-align: left;margin: 0"></label>
                </div>
                <div class="input-area">
                    <label>任务负责人:</label>
                    <div class="tree-search" data-width="315">
                        <select name="worker_id" placeholder="请选择"></select>
                    </div>
                </div>
                <div class="input-area">
                    <label>任务分数:</label>
                    <input name="score" placeholder="请输入任务分数" type="number"/>
                    <div class="txt-red">注: 本月项目剩余 <span>63</span> 分</div>
                </div>
                <div class="input-area">
                    <label>任务截止时间:</label>
                    <input class="date-picker" name="deadline" placeholder="请选择" type="text"
                           data-date-format="YYYY-MM-DD hh:mm:ss"/>
                    <div class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></div>
                </div>
                <div class="input-area">
                    <label>优先级:</label>
                    <div class="tree-select" data-width="315">
                        <select name="priority">
                            <option value="0">正常</option>
                            <option value="1">重要</option>
                            <option value="2">紧急</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="input-area textarea">
                    <label>任务描述:</label>
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
    <form class="useraction-form" action="" method="post" hidden style="display:none;">
        <input name="search_keyword" style="display: none!important;" value="<?= $search_keyword; ?>"/>
        <input name="range_from" style="display: none!important;" value="<?= $range_from; ?>"/>
        <input name="range_to" style="display: none!important;" value="<?= $range_to; ?>"/>
    </form>
</div>


<div class="scripts">
    <input hidden class="_userList" value='<?= str_replace("'", "`", json_encode($userList)) ?>'>
    <input hidden class="_projectItem" value='<?= str_replace("'", "`", json_encode($projectItem)) ?>'>
    <input hidden class="_taskList" value='<?= str_replace("'", "`", json_encode($taskList)) ?>'>
    <input hidden class="_mainList" value='<?= str_replace("'", "`", json_encode($list)) ?>'>
    <input hidden class="_progressCnt" value='<?= json_encode($progressCnt) ?>'>
    <input hidden class="_filterInfo"
           value='<?= json_encode($this->session->userdata('filter') ?: array()) ?>'>

    <script>
        selectMenu('<?= $menu; ?>');
        $(function () {
            searchConfig();
        });

        var _userList = JSON.parse($('._userList').val());
        _userList = _userList.filter(function (a) {
            a.title = a.name;
            return true;
        });
        var _mainList = JSON.parse($('._mainList').val());
        var _projectItem = JSON.parse($('._projectItem').val())[0];
        var _taskList = JSON.parse($('._taskList').val());
        var _progressCnt = JSON.parse($('._progressCnt').val());
        var _filterInfo = JSON.parse($('._filterInfo').val());
        var _mainObj = '<?=$mainModel?>';
        var _apiRoot = baseURL + "<?=$apiRoot?>".split('/')[0] + '/';
        var _navTitle = '<?= $title; ?>';
        var _project = '<?= $project; ?>';
        var _remainedScore = 0;
        var _progress = parseInt('<?= $progress; ?>');
        var _titleStr = ['全部', '未接收', '进行中', '待验收', '已完成'];
        var _editItemId = 0;

        function searchConfig() {

            if (_filterInfo.queryStr) $('input[name="search_keyword"]').val(_filterInfo.queryStr);

            var tabElems = $('.tab-container');
            tabElems.find('.tab-item').each(function (idx, elem) {
                elem = $(elem);
                elem.off('click');
                elem.on('click', function () {
                    var that = $(this);
                    var progress = parseInt(that.attr('data-progress'));
                    if (progress !== _progress) {
                        $('input[name="_progress"]').val(progress);
                        location.replace(_apiRoot + 'editable/<?= $menu ?>/<?= $project?>/' + progress);
                    }
                });
                if (idx == 0) {
                    var suff = 0;
                    for (var i in _progressCnt) suff += _progressCnt[i];
                    if (suff > 999) suff = '999<sup>+</sup>';
                    elem.find('.tab-number').html(suff);
                    elem.find('.tab-number').attr('data-value', suff);
                    return;
                }
                var suff = parseInt(_progressCnt[idx - 1]);
                if (suff > 999) suff = '999<sup>+</sup>';
                elem.find('.tab-number').html(suff);
                elem.find('.tab-number').attr('data-value', suff);
            })

            $('input[name="_progress"]').val(_progress);
            _navTitle += ' ＞ ' + _titleStr[_progress + 1];
            tabElems.find('.tab-item[data-progress="' + _progress + '"]').attr('data-sel', 1);

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
            var headerTitle = '当前任务简介';
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
                editElem.find('label[name="project"]').html(mainItem.project);
                editElem.find('label[name="project_worker"]').html(mainItem.project_worker);
                editElem.find('label[name="published_at"]').html(mainItem.published_at);

                editElem.find('label[name="started_at"]').html(mainItem.started_at);
                if (!mainItem.started_at) editElem.find('div[data-type="started_at"]').hide();
                else editElem.find('div[data-type="started_at"]').show();

                editElem.find('label[name="provided_at"]').html(mainItem.provided_at);
                if (!mainItem.provided_at) editElem.find('div[data-type="provided_at"]').hide();
                else editElem.find('div[data-type="provided_at"]').show();

                editElem.find('label[name="completed_at"]').html(mainItem.completed_at);
                if (!mainItem.completed_at) editElem.find('div[data-type="completed_at"]').hide();
                else editElem.find('div[data-type="completed_at"]').show();

                editElem.find('label[name="score"]').html(mainItem.score);
                editElem.find('label[name="deadline"]').html(mainItem.deadline);
                editElem.find('label[name="description"]').html(mainItem.description);
                editElem.find('div[name="btns"]').attr('data-id', mainItem.id);
            }

            editElem.find('.content-title > span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);

            editElem.fadeIn('fast');
        }

        var _prevAddedUserId = 0;

        function editItem(elem) {
            $('.edit-area[data-type="view"]').fadeOut();
            var editElem = $('.edit-area[data-type="edit"]');

            makeTreeSearchSelect(editElem.find('select[name="worker_id"]'),
                _userList, '', 'part', 'id', 'title', function (e) {
                    var that = editElem.find('select[name="worker_id"]');
                    var id = that.val();
                    _prevAddedUserId = id;
                });
            $('div[data-type="close-panel"]').off('click');
            $('div[data-type="close-panel"]').on('click', function () {
                $('.base-container .nav-position-title').html(_navTitle);
                editElem.fadeOut('fast');
            });

            var headerTitle = '新增任务';
            if (!elem) {
                editElem.find('input').val('');
                editElem.find('select').val('');
                editElem.find('textarea').val('');
                editElem.find('select[name="priority"]').val('0');
                if (_prevAddedUserId > 0) editElem.find('select[name="worker_id"]').val(_prevAddedUserId);
                _editItemId = 0;

                editElem.find('label[name="project"]').html(_projectItem.title + " (" + _projectItem.no + ")");

                var curMonth = makeDateString().substr(0, 7);

                var priceDetail = JSON.parse(_projectItem.price_detail);
                var curMonthScore = 0;
                var curMonthScoreOut = 0;
                for (var i = 0; i < priceDetail.length; i++) {
                    var item = priceDetail[i];
                    if (item.created.substr(0, 7) != curMonth) continue;
                    curMonthScore += item.price * 1;
                    if (item.price_other)
                        curMonthScoreOut += item.price_other * 1;
                }
                curMonthScore = (curMonthScore * .6 - curMonthScoreOut) / 150;

                var taskScore = 0;
                var taskNo = 0;
                var allTasks = _taskList.filter(function (a) {
                    if (a.project_id != _project) return false;
                    if (a.info == '__manage__') return false;
                    taskNo++;
                    if (a.create_time.substr(0, 7) != curMonth) return false;
                    taskScore += a.score * 1;
                    return true;
                });

                _remainedScore = Math.round((curMonthScore - taskScore) * 100) / 100;
                editElem.find('div.txt-red span').html(_remainedScore.toFixed(2));

                editElem.find('label[name="no"]').hide();
                editElem.find('label[name="title"]').hide();
                editElem.find('input[name="no"]').show();
                editElem.find('input[name="no"]').val(_projectItem.no + makeNDigit(taskNo + 1, 4));
                editElem.find('input[name="title"]').show();
                var lastDay = makeDateString(makeDateObject()).substr(0, 7) + '-01 23:30:00';
                lastDay = makeDateObject(lastDay);
                lastDay.setMonth(lastDay.getMonth() + 1);
                lastDay.setDate(lastDay.getDate() - 1);

                editElem.find('input[name="deadline"]').handleDtpicker('setDate', lastDay);

                tree_search();
                tree_select();
            } else {
                headerTitle = '编辑任务';
                var that = $(elem);
                var id = that.attr('data-id');
                var mainItem = _mainList.filter(function (a) {
                    return a.id == id;
                });
                if (mainItem.length > 0) {
                    mainItem = mainItem[0];
                    _editItemId = mainItem.id;

                    var curMonth = makeDateString().substr(0, 7);

                    var priceDetail = JSON.parse(_projectItem.price_detail);
                    var curMonthScore = 0;
                    var curMonthScoreOut = 0;
                    for (var i = 0; i < priceDetail.length; i++) {
                        var item = priceDetail[i];
                        if (item.created.substr(0, 7) != curMonth) continue;
                        curMonthScore += item.price * 1;
                        if (item.price_other)
                            curMonthScoreOut += item.price_other * 1;
                    }
                    curMonthScore = (curMonthScore * .6 - curMonthScoreOut) / 150;

                    var taskScore = 0;
                    var allTasks = _taskList.filter(function (a) {
                        if (a.info == '__manage__') return false;
                        if (a.create_time.substr(0, 7) != curMonth) return false;
                        if (a.project_id != _project) return false;
                        taskScore += a.score * 1;
                        return true;
                    });

                    _remainedScore = Math.round((curMonthScore - taskScore) * 100) / 100;

                    editElem.find('div.txt-red span').html(_remainedScore.toFixed(2));

                    editElem.find('label[name="no"]').html(mainItem.no);
                    editElem.find('label[name="title"]').html(mainItem.title);
                    editElem.find('label[name="project"]').html(mainItem.project);
                    editElem.find('input[name="no"]').val(mainItem.no);
                    editElem.find('input[name="title"]').val(mainItem.title);
                    if (mainItem.worker_id != '0') {
                        editElem.find('select[name="worker_id"]').val(mainItem.worker_id);
                    }
                    editElem.find('input[name="score"]').val(mainItem.score);
                    editElem.find('input[name="deadline"]').val(mainItem.deadline);
                    editElem.find('textarea[name="description"]').val(mainItem.description);
                    editElem.find('select[name="priority"]').val(mainItem.priority);

                    if (mainItem.progress == 0) {
                        editElem.find('label[name="no"]').hide();
                        editElem.find('label[name="title"]').hide();
                        editElem.find('input[name="no"]').show();
                        editElem.find('input[name="title"]').show();
                    } else {
                        editElem.find('label[name="no"]').show();
                        editElem.find('label[name="title"]').show();
                        editElem.find('input[name="no"]').hide();
                        editElem.find('input[name="title"]').hide();
                    }


                    editElem.find('input[name="deadline"]').handleDtpicker('setDate', makeDateObject(mainItem.deadline));

                    tree_search();
                    tree_select();
                }
            }

            editElem.find('.content-title span').html(headerTitle);
            $('.base-container .nav-position-title').html(_navTitle + ' ＞ ' + headerTitle);

            editElem.fadeIn('fast');
        }

        var _isProcessing = false;

        function editPerform(elem) {
            var that = $(elem);

            var noElem = that.find('input[name="no"]');
            var titleElem = that.find('input[name="title"]');
            var scoreElem = that.find('input[name="score"]');
            var warn = '';
            if (!noElem.val()) warn = '请输入任务编号';
            else if (!titleElem.val()) warn = '请输入任务名称';
            else if (!scoreElem.val()) warn = '请输入任务分数';
            else if (parseFloat(scoreElem.val()) > _remainedScore) warn = '任务分数无效';
            if (warn != '') {
                showConfirm(baseURL + 'assets/images/modal/modal-confirm-top.png',
                    '', warn);
                return;
            }
            noElem.val(noElem.val().trim());
            titleElem.val(titleElem.val().trim());
            scoreElem.val(scoreElem.val().trim());


            if (_isProcessing) return;
            _isProcessing = true;
            $('.modal-container[data-type="modal"]').fadeIn('fast');
            $(".uploading-progress").fadeIn('fast');

            var fdata = new FormData(that[0]);
            fdata.append("id", _editItemId);
            fdata.append("project_id", _project);
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

        function deleteItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            showConfirm(baseURL + 'assets/images/modal/modal-confirm-top.png',
                '', '您确定要删除这个任务吗?', function () {
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

        function completeItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');

            showConfirm(baseURL + 'assets/images/modal/modal-confirm-top.png',
                '', '确定验收通过这个任务么?_验收通过_不通过', function () {
                    $.ajax({
                        type: "post",
                        url: _apiRoot + "completeItem",
                        dataType: "json",
                        data: {id: id},
                        success: function (res) {
                            if (res.status == 'success') {
                                showNotify('<i class="fa fa-check-circle" style="padding-right:0;"></i><br>' +
                                    '<span style="font-size:18px;">任务验收通过</span><br>' +
                                    '');
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                                // location.reload();
                            } else { //failed
                                alert(res.data);
                            }
                        }
                    });
                }, function () {
                    $.ajax({
                        type: "post",
                        url: _apiRoot + "rejectItem",
                        dataType: "json",
                        data: {id: id},
                        success: function (res) {
                            if (res.status == 'success') {
                                showNotify('<i class="fa fa-exclamation-circle" style="padding-right:0;"></i><br>' +
                                    '<span style="font-size:18px;">任务验收未通过</span><br>' +
                                    '');
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

        function goToPreviousPage() {
            $('input[name="search_keyword"]').val(setPreviousKeyword());
            $('input[name="range_from"]').val('');
            $('input[name="range_to"]').val('');
            $('.useraction-form').attr('action', setSearchKeyword());
            $('.useraction-form').submit();
        }
    </script>
</div>