<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-md-8">
            <form action="<?= site_url('monitoring-suhu-kelembapan/suhu-kelembapan-berdasarkan-tanggal'); ?>" method="POST" id="formFilterData">
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
                        <button class="btn btn-info float-right" type="submit" id="btnTampilkan">
                            <i class="fas fa-eye mr-2"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Suhu & Kelembapan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover table-border-head" id="tabelSuhuKelembapan">
                        <thead class="thead-light" style="pointer-events: none;">
                            <th class="text-center align-middle">No. Referensi</th>
                            <th class="text-center align-middle">Tanggal</th>
                            <th class="text-center align-middle">Suhu</th>
                            <th class="text-center align-middle">Kelembapan</th>
                            <th class="text-center align-middle">Petugas</th>
                            <th class="text-center align-middle">Tindakan</th>
                            <th class="text-center align-middle">Aksi</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a class="btn btn-info" href="<?= base_url('/monitoring-suhu-kelembapan/grafik'); ?>">
                        <i class="far fa-chart-bar mr-2"></i> Lihat Grafik
                    </a>
                    <a class="btn btn-primary float-right" href="<?= base_url('/monitoring-suhu-kelembapan/tambah'); ?>">
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
    let url = "<?= base_url(); ?>/monitoring-suhu-kelembapan";
</script>
<script src="<?= base_url(); ?>/js/dekontaminasi/monitoringsuhukelembapan/index_monitoringsuhukelembapan.js"></script>
<?= $this->endSection(); ?>