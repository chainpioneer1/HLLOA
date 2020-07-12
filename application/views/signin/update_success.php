<?php
if (!isset($form_validation)) $form_validation = 'No';
if ($form_validation != 'No') $form_validation = 'Information error';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/frontend/forgot.css') ?>">
<script>
    var isErr = '<?=$form_validation?>';
</script>
<div class="base-container" style="top:0px;width: 100%;height: auto;">
    <div class="home-bg" data-type="success">
        <div class="login-bg">
            <a type="image" class="login-btn green-btn">返回首页</a>
        </div>
    </div>
</div>
<script>
    var _isLoginPage = 1;
    $(function () {
        $('.top-back').remove();
        if (isErr != 'No') {
            $('.login-err').css('opacity', '1');
        }
        $('.login-btn').on('click', function (object) {
            location.replace('<?= base_url('home')?>');
        });
        $('.checkbox').on('click', function (object) {
            var sel = $(this).attr('data-sel');
            if (sel == 1) sel = 0;
            else sel = 1

            $(this).attr('data-sel', sel);
        });

        $('.main-resource-toolbar > a').remove();
        $('.main-menu .top-bar').append('<div class="pageSubTitle">修改密码成功！</div>');
    })

</script>
