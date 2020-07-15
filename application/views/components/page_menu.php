<?php
$_permission = json_decode($this->session->userdata('_permission'));
?>
<div class="side-menubar">
    <?php if ($_permission->m0 == 1) { ?>
        <div class="side-menuitem" data-id="00" data-target=""><span class="icon"></span> 首页</div>
    <?php }
    if ($_permission->m14 == 1) { ?>
        <div class="side-menuitem" data-id="14" data-target="reports"><span class="icon"></span> 日报大厅</div>
    <?php }
    if ($_permission->m1 == 1) { ?>
        <div class="side-menuitem" data-id="01" data-target="tasks/manage"><span class="icon"></span> 任务大厅</div>
    <?php }
    if ($_permission->m2 == 1) { ?>
        <div class="side-menuitem" data-id="02" data-target="projects/hall"><span class="icon"></span> 项目大厅</div>
    <?php }
    if ($_permission->m3 == 1) { ?>
        <div class="side-menuitem" data-id="03" data-target="users/action"><span class="icon"></span> 绩效中心</div>
    <?php }
    if ($_permission->m4 == 1) { ?>
        <div class="side-menuitem" data-id="04" data-target="tasks/mine"><span class="icon"></span> 我的任务</div>
    <?php }
    if ($_permission->m5 == 1) { ?>
        <div class="side-menuitem" data-id="05" data-target="projects/mine"><span class="icon"></span> 我的项目</div>
    <?php }
    if ($_permission->m6 == 1) { ?>
        <div class="side-menuitem" data-id="06" data-target="projects/manage"><span class="icon"></span> 项目管理</div>
    <?php }
    if ($_permission->m15 == 1) { ?>
        <div class="side-menuitem" data-id="15" data-target="projects/plan"><span class="icon"></span> 项目统筹</div>
    <?php }
    if ($_permission->m7 == 1) { ?>
        <div class="side-menuitem" data-id="07" data-target=""><span class="icon"></span> 行政管理</div>
    <?php }
    if ($_permission->m8 == 1) { ?>
        <div class="side-menuitem" data-id="08" data-target="userparts" data-parent="07"><span></span>部门管理</div>
    <?php }
    if ($_permission->m9 == 1) { ?>
        <div class="side-menuitem" data-id="09" data-target="userpositions" data-parent="07"><span></span>职位管理</div>
    <?php }
    if ($_permission->m10 == 1) { ?>
        <div class="side-menuitem" data-id="10" data-target="userranks" data-parent="07"><span></span>职级管理</div>
    <?php }
    if ($_permission->m11 == 1) { ?>
        <div class="side-menuitem" data-id="11" data-target="users" data-parent="07"><span></span>人员管理</div>
    <?php }
    if ($_permission->m13 == 1) { ?>
        <div class="side-menuitem" data-id="13" data-target="userprices" data-parent="07"><span></span>工资管理</div>
    <?php }
    if ($_permission->m12 == 1) { ?>
        <div class="side-menuitem" data-id="12" data-target="posts" data-parent="07"><span></span>公告信息管理</div>
    <?php }
    if ($_permission->m16 == 1) { ?>
        <div class="side-menuitem" data-id="16" data-target=""><span class="icon"></span> 财务管理</div>
    <?php }
    if ($_permission->m17 == 1) { ?>
        <div class="side-menuitem" data-id="17" data-target="payment" data-parent="16"><span></span>合同管理</div>
    <?php }
    if ($_permission->m18 == 1) { ?>
        <div class="side-menuitem" data-id="18" data-target="userpositions" data-parent="16"><span></span>公司收支录入</div>
    <?php }
    if ($_permission->m19 == 1) { ?>
        <div class="side-menuitem" data-id="19" data-target="userranks" data-parent="16"><span></span>公司收支统计</div>
    <?php }
    if ($_permission->m20 == 1) { ?>
        <div class="side-menuitem" data-id="20" data-target="users" data-parent="16"><span></span>项目收支统计</div>
    <?php } ?>
</div>
<div class="scripts">
    <script>
        $('.side-menuitem').on('click', function () {
            var that = $(this);
            var id = that.attr('data-id');
            if (id == '07' || id == '16') {
                var status = that.attr('data-sel');
                if (status) {
                    $('.side-menuitem[data-parent="' + id + '"]').slideUp('fast');
                    that.removeAttr('data-sel');
                } else {
                    $('.side-menuitem[data-parent="' + id + '"]').slideDown('fast');
                    that.attr('data-sel', 1);
                }
                return;
            }
            var target = that.attr('data-target');
            var allElems = $('.side-menuitem');
            allElems.removeAttr('data-sel');
            that.attr('data-sel', 1);
            location.href = baseURL + target;
        });

        function selectMenu(id) {
            id = makeNDigit(id, 2);
            if (parseInt(id) > 7 && parseInt(id) < 14) {
                $('.side-menuitem[data-id="07"]').attr('data-sel', 1);
                $('.side-menuitem[data-parent="16"]').hide();
            } else if (parseInt(id) > 16 && parseInt(id) < 21) {
                $('.side-menuitem[data-id="16"]').attr('data-sel', 1);
                $('.side-menuitem[data-parent="07"]').hide();
            } else {
                $('.side-menuitem[data-parent="07"]').hide();
                $('.side-menuitem[data-parent="16"]').hide();
            }
            $('.side-menuitem[data-id="' + id + '"]').attr('data-sel', 1);
        }

    </script>
</div>