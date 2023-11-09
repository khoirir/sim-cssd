<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <form action="<?= site_url('/monitoring-mesin-plasma/data-monitoring-mesin-plasma-berdasarkan-tanggal'); ?>" method="POST" id="formFilterData">
                <?= csrf_field(); ?>
                <div class="card card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Filter Data</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <label>Tanggal</label>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="tanggalAwal" id="tanggalAwal" value="<?= $tglAwal; ?>">
                                <div class="invalid-feedback" id="invalidTanggalAwal"></div>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="tanggalAkhir" id="tanggalAkhir" value="<?= $tglSekarang; ?>">
                                <div class="invalid-feedback" id="invalidTanggalAkhir"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-info float-right" type="submit">
                            <i class="fas fa-eye mr-2"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Monitoring Mesin Plasma</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-border-head table-hover" id="tabelMonitoringMesinPlasma">
                            <thead class="thead-light" style="pointer-events: none;">
                                <th scope="col" class="text-center align-middle">No. Referensi</th>
                                <th scope="col" class="text-center align-middle">Tanggal</th>
                                <th scope="col" class="text-center align-middle">Jam</th>
                                <th scope="col" class="text-center align-middle">Siklus</th>
                                <th scope="col" class="text-center align-middle">Verifikasi</th>
                                <th scope="col" class="text-center align-middle">Keterangan / Hasil Verifikasi</th>
                                <th scope="col" class="text-center align-middle">Detail</th>
                                <th scope="col" class="text-center align-middle">Aksi</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary float-right" href="<?= base_url('/monitoring-mesin-plasma/tambah'); ?>">
                        <i class="fas fa-plus mr-2"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
    <div class="viewmodal" style="display: none;"></div>
</section>
<!-- /.content -->
<script>
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
    let url = "<?= base_url(); ?>/monitoring-mesin-plasma";
</script>
<script src="<?= base_url(); ?>/js/packingalat/monitoringmesinplasma/index_monitoringmesinplasma.js"></script>
<?= $this->endSection(); ?>