<!DOCTYPE html>
<html lang="en-US">
<head>
    <?php $this->load->view("components/page_header"); ?>

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/custom.css') ?>">
    <script src="<?= base_url('assets/ace/js/jquery-2.1.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/domlib.js') ?>"></script>
</head>
<body style="width: calc(100vw);height:calc(100vh);">
    <?php $this->load->view($subview); ?>
    <?php $this->load->view("components/page_footer"); ?>
</body>
</html>

