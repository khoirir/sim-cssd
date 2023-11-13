<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Data Satuan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-border-head table-hover" id="tabelDataSatuan">
                        <thead class="thead-light" style="pointer-events: none;">
                            <th scope="col" class="text-center align-middle" style="width: 25%;">No. Referensi</th>
                            <th scope="col" class="text-center align-middle" style="width: 25%;">Kode Satuan</th>
                            <th scope="col" class="text-center align-middle" style="width: 25%;">Nama Satuan</th>
                            <th scope="col" class="text-center align-middle" style="width: 25%;">Aksi</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary float-right" href="<?= base_url('/data-satuan/tambah'); ?>">
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
    let url = "<?= base_url(); ?>/data-satuan";
</script>
<script src="<?= base_url(); ?>/public/js/datamaster/datasatuan/index_datasatuan.js"></script>
<?= $this->endSection(); ?>