<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIM CSSD | <?= $title; ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>public/img/logo-karsa.jpg">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- daterangepicker -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Tempusdominus Bootstrap 4 Datetimepicker-->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/select2/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/css/adminlte.min.css">
    <!-- Datatables -->
    <link href="https://cdn.datatables.net/v/bs4/dt-1.13.4/rg-1.3.1/datatables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/css/custom.css">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/sweetalert2/sweetalert2.min.css">
    <!-- toastr -->
    <link rel="stylesheet" href="<?= base_url(); ?>public/plugins/toastr/toastr.min.css">
    <!-- jQuery -->
    <script src="<?= base_url(); ?>public/plugins/jquery/jquery.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?= $this->include('templates/navbar'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('templates/sidebar'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <h1 class="m-0"><?= $header; ?></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <?= $this->renderSection('page-content'); ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= date('Y'); ?> <a href="https://rsukarsahusadabatu.jatimprov.go.id/pelayanan/nonmedis/sistem_informasi" target="_blank">Unit Teknologi Informasi - RSUD Karsa Husada Batu</a>.</strong>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- Bootstrap -->
    <script src="<?= base_url(); ?>public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Datatables -->
    <script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/rg-1.3.1/datatables.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/jszip/jszip.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?= base_url(); ?>public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- Select2 -->
    <script src="<?= base_url(); ?>public/plugins/select2/js/select2.full.min.js"></script>
    <!-- Moment Datetimepicker -->
    <script src="<?= base_url(); ?>public/plugins/moment/moment.min.js"></script>
    <script src="<?= base_url(); ?>public/plugins/inputmask/jquery.maskMoney.min.js"></script>
    <!-- date-range-picker -->
    <script src="<?= base_url(); ?>public/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 Datetimepicker-->
    <script src="<?= base_url(); ?>public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>public/js/adminlte.js"></script>
    <!-- sweetalert2 -->
    <script src="<?= base_url(); ?>public/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- toastr -->
    <script src="<?= base_url(); ?>public/plugins/toastr/toastr.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= base_url(); ?>public/js/custom.js"></script>
</body>

</html>