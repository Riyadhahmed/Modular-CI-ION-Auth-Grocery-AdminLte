<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once 'parts/header.php'; ?>
    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
    </script>
</head>
<body class="hold-transition skin-red-light sidebar-mini fixed">
<div class="se-pre-con"></div>
<div class="wrapper">
    <header class="main-header">
        <?php require_once 'parts/topbar.php'; ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar ">
            <!-- Sidebar user panel -->
            <?php require_once 'parts/menu.php'; ?>
            <!-- /.sidebar -->
        </section>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('admin/admin_dashboard'); ?>"><i class="fa fa-dashboard"></i>
                            Home</a></li>
                    <li class="active"><?php echo $breadcrumbs; ?></li>
                </ol>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            {{CONTENT}}
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <?php require_once 'parts/footer.php'; ?>
        <?php require_once 'parts/datatable.php'; ?>
    </footer>
</div>
<!-- ./wrapper -->
</body>
</html>
<style>
    /* Paste this css to your style sheet file or under head tag */
    /* This only works with JavaScript,
    if it's not present, don't show loader */
    .no-js #loader {
        display: none;
    }

    .js #loader {
        display: block;
        position: absolute;
        left: 100px;
        top: 0;
    }

    .se-pre-con {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url("<?php echo base_url('assets/images/pageloader/loader-64x/Preloader_2.gif')?>") center no-repeat #fff;
    }
</style>
<script>
    //paste this code under head tag or in a seperate js file.
    // Wait for window load
    $(window).load(function () {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
    });
</script>

