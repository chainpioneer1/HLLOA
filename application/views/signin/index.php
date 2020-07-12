<?php
if (!isset($form_validation)) $form_validation = '';
?>
<div class="base-container">
    <div class="home-bg">
        <div class="login-bg">
            <form method="post" class="login_form" action="<?= base_url('signin/index'); ?>">
                <div>用户登录</div>
                <div class="login-err">您输入的账号或密码有误,请重新输入</div>
                <input type="text" name="account" maxlength="18" id="username" placeholder="请输入账号"/>
                <input type="password" name="password" maxlength="18" id="password" placeholder="请输入密码"/>
                <a type="image" name="submit" class="login-btn btn-blue">登录</a>
                <div style="font-size:16px;color:rgba(153,153,153,1);padding-top: 15px;cursor:pointer;"
                     onclick="location.replace('<?= base_url('signin'); ?>')">
                    如有问题请拨打00-000000
                </div>

                <button type="submit" hidden></button>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/signin.css') ?>">
<div class="scripts">
    <script>
        var isErr = `<?=$form_validation?>`;
        var _isLoginPage = 1;
        $(function () {
            $('.top-back').remove();
            if (isErr != '') {
                $('.login-err').css('opacity', '1');
            }
            $('.login-btn').on('click', function (object) {
                $('.login_form').submit();
            })
            $('.checkbox').on('click', function (object) {
                var sel = $(this).attr('data-sel');
                if (sel == 1) sel = 0;
                else sel = 1

                $(this).attr('data-sel', sel);
            });


        })
    </script>
</div>
