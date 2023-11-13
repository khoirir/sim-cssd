<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIM CSSD | Login</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>/public/img/logo-karsa.jpg">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>/public/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url(); ?>/public/plugins/sweetalert2/sweetalert2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/public/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <form action="<?= base_url('/attempt-login'); ?>" method="post" id="formLogin">
                <div class="card-header text-center">
                    <img src="<?= base_url(); ?>/public/img/logo-karsa.jpg" alt="logo" style="width: 40%;height: auto;" class="card-img-top">
                    <h5>SIM CSSD</h5>
                </div>
                <div class="card-body">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" id="username" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="invalidUsername"></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="invalidPassword"></div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right" id="btnSubmit">
                        <i class="fa fa-sign-in-alt mr-2"></i>Login
                    </button>
                </div>
            </form>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/public/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>/public/js/adminlte.min.js"></script>
    <!-- sweetalert2 -->
    <script src="<?= base_url(); ?>/public/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $('#formLogin').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#btnSubmit').prop('disabled', true);
                    $('#btnSubmit').html(`<i class="fa fa-spin fa-spinner mr-2"></i> Login`);
                },
                complete: function() {
                    $('#btnSubmit').prop('disabled', false);
                    $('#btnSubmit').html(`<i class="fa fa-sign-in-alt mr-2"></i> Login`);
                },
                success: function(res) {
                    if (!res.sukses) {
                        $(".invalid-feedback").html("");
                        $('.is-invalid').removeClass('is-invalid');
                        if (res.pesan.username) {
                            $('#username').addClass('is-invalid');
                            $('#invalidUsername').html(res.pesan.username);
                        }
                        if (res.pesan.password) {
                            $('#password').addClass('is-invalid');
                            $('#invalidPassword').html(res.pesan.password);
                        }
                    }
                    if (res.sukses) {
                        window.location = res.pesan.url;
                    }
                },
                error: function(xhr, textStatus, thrownError) {
                    console.log(xhr.status + " => " + textStatus);
                    console.log(thrownError);
                    Swal.fire(
                        "Login Gagal",
                        "Terjadi Masalah,<br>Silahkan Hubungi Tim IT",
                        "error"
                    );
                }
            });
        });
    </script>
</body>

</html>