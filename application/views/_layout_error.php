<!DOCTYPE html>
<html lang="en-US">
<head>
    <?php $this->load->view("components/page_header"); ?>

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/custom.css') ?>">
    <script src="<?= base_url('assets/ace/js/jquery-2.1.4.min.js') ?>"></script>
</head>
<body>
<div>
    <div class="scripts">
        <script src="<?= base_url('assets/js/global.js') ?>"></script>
        <script>
            var baseURL = "<?= base_url() ?>";
            var _global = {
                tmrID: [0, 0, 0, 0, 0, 0]
            };
            var userId = '<?= $this->session->userdata('_user_id')?>';
        </script>
    </div>
    <?php $this->load->view("components/page_topbar"); ?>

    <?php $this->load->view("components/page_menu"); ?>

    <?php $this->load->view("errors/html/error_404"); ?>

    <?php $this->load->view("components/page_footer"); ?>
</div>
</body>
</html>

