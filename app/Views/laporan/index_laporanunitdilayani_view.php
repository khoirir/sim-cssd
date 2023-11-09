<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-8">
            <form action="<?= site_url('/laporan-unit-dilayani/data-jumlah-dilayani'); ?>" method="POST" id="formFilterData">
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
                        <div class="form-row justify-content-between">
                            <div class="form-group col-md-6">
                                <label>Tanggal</label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="month" class="form-control" name="bulanAwal" id="bulanAwal" value="<?= $bulanAwal; ?>">
                                        <div class="invalid-feedback" id="invalidBulanAwal"></div>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="month" class="form-control" name="bulanAkhir" id="bulanAkhir" value="<?= $bulanSekarang; ?>">
                                        <div class="invalid-feedback" id="invalidBulanAkhir"></div>
                                    </div>
                                </div>
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
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header p-3">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-border-head" id="tabelLaporanUnitDilayani">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center align-middle" rowspan="2" style="width: 10%;">NO.</th>
                                    <th scope="col" class="text-center align-middle" rowspan="2" style="width: 40%;">BULAN</th>
                                    <th scope="col" class="text-center align-middle" colspan="2" style="width: 50%;">JENIS KEGIATAN</th>
                                </tr>
                                <tr>
                                    <th scope="col" class="text-center align-middle" style="width: 25%;">Sterilisasi (Unit)</th>
                                    <th scope="col" class="text-center align-middle" style="width: 25%;">Produksi (Item)</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-info" id="btnEksporExcel">
                        <i class="far fa-file-excel mr-2"></i> Ekspor Excel
                    </button>
                </div>
            </div>
        </div><!--/. container-fluid -->
</section><!-- /.content -->
<script>
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
    let url = "<?= base_url(); ?>/laporan-unit-dilayani";
</script>
<script src="<?= base_url(); ?>/js/laporan/index_laporanunitdilayani.js"></script>
<?= $this->endSection(); ?>