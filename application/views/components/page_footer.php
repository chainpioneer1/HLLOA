<!--    -->


<!----confirm modal-->
<div class="modal-container" data-type="modal">
    <div class="confirm-modal" tabindex="-1">
        <img src="<?= base_url('assets/images/modal/modal-confirm-top.png') ?>"/>
        <div class="modal-header">
            <div></div>
            <button class="btn-fontgrey" data-type="close"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-body" style="overflow-y: auto;">
            <div></div>
        </div>
        <div class="modal-footer" data-type="1">
            <button type="button" class="btn-blue" data-type="yes">确定</button>
            <button type="button" class="btn-blue" data-type="no">取消</button>
        </div>
    </div>
    <div class="uploading-progress" style="width: 200px;">
        <div class="modal-body">
            <div class="">处理中</div>
            <img src="<?= base_url('assets/images/ajax-loader.gif') ?>"/>
            <div class="progress-val">30%</div>
        </div>
    </div>
</div>

<div class="notify-container" data-type="modal">
    <div></div>
</div>

<?php $this->load->view($subscript); ?>

<div class="scripts">
    <script>
        var parentView = '<?=(isset($parentView) ? $parentView : '')?>';

        function goPreviousPage() {
            if (parentView == 'back') history.go(-1);
            else location.replace(baseURL + parentView);
        }

        $(function () {
            if (parentView == '')
                $('.top-back').hide();
            window._resize();
            $(window).on('resize', window._resize);
            $('body').css({'opacity': 1});
            setTimeout(function () {
                initializePickers();
            }, 1);
        });
        window._resize = function () {
            var bgW = $($('body > div')[0]).width();
            var bgH = $($('body > div > div')[0]).height();
            var w = window.innerWidth;

            if (true || w > 1250) $($('body > div')[0]).css({left: w / 2});
            else $($('body > div')[0]).css({left: 1250 / 2});
            // w = 1250;

            var h = window.innerHeight;
            if (w > bgW) w = bgW;
            var scale = w / bgW * 1920 / bgW;
            window._scale = scale;
            // scale = 1;
            $($('body > div')[0]).css({
                'transform': 'translateX(-50%) scale(' + scale.toFixed(3) + ')',
                '-webkit-transform': 'translateX(-50%) scale(' + scale.toFixed(3) + ')',
                '-moz-transform': 'translateX(-50%) scale(' + scale.toFixed(3) + ')',
                '-ms-transform': 'translateX(-50%) scale(' + scale.toFixed(3) + ')',
                '-o-transform': 'translateX(-50%) scale(' + scale.toFixed(3) + ')',
                'height': 0,
                overflow: 'unset'
            });
            return scale;
        }

        // window._resize();
        $('.scripts').remove();
    </script>
</div>