<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Data Jenis Set Alat</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-border-head table-hover" id="tabelDataJenisSetAlat">
                        <thead class="thead-light" style="pointer-events: none;">
                            <th scope="col" class="text-center align-middle" style="width:30%;">No. Referensi</th>
                            <th scope="col" class="text-center align-middle" style="width:40%;">Nama Jenis</th>
                            <th scope="col" class="text-center align-middle" style="width:30%;">Aksi</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary float-right" href="<?= base_url('/data-jenis-set-alat/tambah'); ?>">
                        <i class="fas fa-plus mr-2"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->
<script>
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
    let url = "<?= base_url(); ?>/data-jenis-set-alat";
</script>
<script src="<?= base_url(); ?>/js/datamaster/datajenissetalat/index_datajenissetalat.js"></script>
<?= $this->endSection(); ?>