<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-md-12">
            <form action="<?= site_url('/uji-larutan-dtt-alkacyd/data-uji-larutan'); ?>" method="POST" id="formFilterData">
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
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tanggal</label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="date" class="form-control" name="tanggalAwal" id="tanggalAwal" value="<?= $tglAwal; ?>">
                                        <div class="invalid-feedback" id="invalidTanggalAwal"></div>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="date" class="form-control" name="tanggalAkhir" id="tanggalAkhir" value="<?= $tglSekarang; ?>">
                                        <div class="invalid-feedback" id="invalidTanggalAkhir"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Hasil</label>
                                <select class="form-control" id="hasil">
                                    <option value=''>Semua</option>
                                    <option value='ungu'>Passed</option>
                                    <option value='pink'>Failed</option>
                                </select>
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
                <div class="card-header">
                    <h3 class="card-title">Uji Larutan DTT Alkacyd</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover table-border-head" id="tabelUjiLarutan">
                        <thead class="thead-light" style="pointer-events: none;">
                            <tr>
                                <th scope="col" rowspan="2" class="text-center align-middle">No. Referensi</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">Tanggal</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">METRACID 1 ml</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">ALKACID 10 ml</th>
                                <th scope="col" colspan="2" class="text-center align-middle">Hasil</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">Petugas</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">Keterangan</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">Gambar Larutan</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">Aksi</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center align-middle">Warna Ungu</th>
                                <th scope="col" class="text-center align-middle">Warna Pink</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary float-right" href="<?= base_url('/uji-larutan-dtt-alkacyd/tambah'); ?>">
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
    let url = "<?= base_url(); ?>uji-larutan-dtt-alkacyd";
</script>
<script src="<?= base_url(); ?>public/js/dekontaminasi/ujilarutan/index_ujilarutan.js"></script>
<?= $this->endSection(); ?>