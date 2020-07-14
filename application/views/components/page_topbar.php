<div class="page-topbar">
    <div class="topbar-logo"><img src="<?= base_url('assets/images/logo.png') ?>"></div>
    <div class="topbar-profile">
        <div class="profile-item btn-blue" data-type="avatar" style="background: url(<?= base_url($this->session->userdata('_avatar')); ?>)"></div>
        <div class="profile-item" data-type="username"><?= $this->session->userdata('_name'); ?></div>
<!--        <div class="profile-item btn-fontgrey" data-type="logout"><i class="fa fa-power-off"></i></div>-->
    </div>
</div>
<div class="page-profile-menu">
    <div class="profile-menuitem" data-target="users/profile">个人中心</div>
    <div class="profile-menuitem" data-target="userprices/mine">我的工资</div>
    <div class="profile-menuitem" data-target="reports/mine">我的日报</div>
    <div class="profile-menuitem" data-target="users/change">修改密码</div>
    <div class="profile-menuitem" data-target="signin/signout" data-type="1">退出登录</div>
</div>
<div class="scripts">
    <script>
        $(function () {
            $('.profile-item').on('click', function () {
                var that = $(this);
                var type = that.attr('data-type');
                switch (type) {
                    case 'avatar':
                    case 'username':
                        showMenuElem();
                        break;
                    case 'logout':
                        location.replace(baseURL + 'signin/signout');
                        break;
                }
            }).on('mouseover', function () {
                var that = $(this);
                var type = that.attr('data-type');
                switch (type) {
                    case 'avatar':
                    case 'username':
                        showMenuElem(true);
                        break;
                    case 'logout':
                        break;
                }
            });
            $('.base-container').on('mouseup', function () {
                showMenuElem(false);
            });
            $('.profile-menuitem').on('click', function () {
                var that = $(this);
                if(that.attr('data-type')=='1') location.replace(baseURL + that.attr('data-target'));
                else location.href = baseURL + that.attr('data-target');
            })
        })

        function showMenuElem(isShow) {
            var menuElem = $('.page-profile-menu');
            var status = menuElem.attr('data-sel');
            if (isShow != undefined) status = !isShow;
            if (status) {
                menuElem.slideUp('fast');
                menuElem.removeAttr('data-sel');
            } else {
                menuElem.slideDown('fast');
                menuElem.attr('data-sel', 1);
            }
        }
    </script>
</div>