<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<section class="content">
    <div class="error-page">
        <h2 class="headline text-warning">404</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman Tidak Ditemukan.</h3>

            <p>
                Kami tidak bisa menemukan halaman yang Anda cari.
                <br>
                Silahkan kembali ke <a href="<?= base_url('/'); ?>">halaman utama</a>.
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>

<script>
    $(function() {
        setTimeout(function() {
            location.href = "<?= base_url('/'); ?>";
        }, 5000);
    });
</script>
<?= $this->endSection(); ?>