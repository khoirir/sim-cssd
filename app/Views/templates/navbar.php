<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <?php if (session()->get('id_user')) : ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?= session()->get('foto'); ?>" class="img-circle mr-2" alt="User Image" width="30" height="30"> <?= session()->get('nama'); ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <!-- <a class="dropdown-item" href="#">
                        <i class="fa fa-user-edit mr-2"></i> Edit Profil
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= base_url('logout'); ?>" id="logout">
                        <i class="fa fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<script>
    $(document).ready(function() {
        const logout = $('#logout');
        logout.on('click', function(event) {
            event.preventDefault();
            sessionStorage.clear();
            window.location.href = logout.attr('href');
        });
    });
</script>