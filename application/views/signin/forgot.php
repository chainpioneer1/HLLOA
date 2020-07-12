<?php
if (!isset($form_validation)) $form_validation = 'No';
if ($form_validation != 'No') $form_validation = 'Information error';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/frontend/forgot.css') ?>">
<script>
    var isErr = '<?=$form_validation?>';
</script>
<div class="base-container" style="top:0px;width: 100%;height: auto;">
    <div class="home-bg" data-type="forgot">
        <div class="login-bg">
            <?php
            if (false) {
                echo '<form method="post" class="login_form" action="' . base_url('api/getAuthCode') . '">';
            } else if (false) {
                echo '<form method="post" class="login_form" action="http://www.qdedu.net/uc/login/login.do?method=samlsso">';
            } else {
                echo '<form method="post" class="forgot_form" action="' . base_url('signin/forgot') . '">';
            }
            ?>
            <input type="text" name="password_old" maxlength="8" placeholder="请输入初始密码"/>
            <input type="password" name="password_new" maxlength="8" placeholder="请输入新密码"/>
            <input type="password" name="password_confirm" maxlength="8" placeholder="请再次输入新密码"/>
            <div class="login-err">您输入的新密码前后不一致,请重新输入</div>
            <div class="input-err">请输入6~8位字母和数字组合</div>
            <a type="image" name="submit" class="login-btn green-btn">确认修改</a>

            <button type="submit" hidden></button>
            </form>
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
            var pwd_old = $('input[name="password_old"]').val();
            var pwd_new = $('input[name="password_new"]').val();
            var pwd_confirm = $('input[name="password_confirm"]').val();
            $('.login-err').css('opacity',0);
            $('.input-err').css('opacity',0);
            // if(pwd_old.length<6 || pwd_old.length>8){
            //     $('.login-err').css('opacity',1);
            //     return;
            // }
            if(pwd_new.length<6 || pwd_new.length>8){
                $('.input-err').css('opacity',1);
                return;
            }
            if(pwd_new != pwd_confirm){
                $('.login-err').css('opacity',1);
                return;
            }

            $('.forgot_form').submit();
        });
        $('.checkbox').on('click', function (object) {
            var sel = $(this).attr('data-sel');
            if (sel == 1) sel = 0;
            else sel = 1

            $(this).attr('data-sel', sel);
        });

        $('.main-resource-toolbar > a').remove();
        $('.main-menu .top-bar').append('<div class="pageSubTitle">修改密码！<br><span>请牢记您修改后的密码以免丢失</span></div>');
    })

</script>
