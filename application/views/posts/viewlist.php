<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/projects.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/posts.css') ?>">

<div class="base-container">
    <div class="nav-position-title"></div>
    <div class="content-area">
        <div class="content-title">公告列表
            <div class="tab-container">
                <div class="tab-search">
                    <div class="btn-back btn-grey btn-fontgrey" onclick="history.back();"><i class="fa fa-angle-left"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-table" style="padding: 0;">
            <table>
                <thead>
                <tr>
                    <th>公告标题</th>
                    <th width="250">公告时间</th>
                </tr>
                </thead>
                <tbody><?= $tbl_content; ?></tbody>
            </table>
        </div>
    </div>
</div>


<div class="scripts">
    <input hidden class="_mainList" value='<?= str_replace("'","`",json_encode($list)) ?>'>

    <script>
        selectMenu('<?= $menu; ?>');
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
        }

        function searchItems() {
            $('.search-form').submit();
        }

        function viewItem(elem) {
            var that = $(elem);
            var id = that.attr('data-id');
            window.open(baseURL + 'posts/view/'+id, '_blank');
        }

    </script>
</div>