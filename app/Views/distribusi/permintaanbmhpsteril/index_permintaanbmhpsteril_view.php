<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-md-10">
            <form action="<?= site_url('/permintaan-bmhp-steril/data-permintaan-bmhp-steril'); ?>" method="POST" id="formFilterData">
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
                                <label for="ruangan">Ruangan</label>
                                <div class="form-group mb-0" id="divRuangan">
                                    <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Ruangan" name="ruangan" id="ruangan">
                                        <option value="semua">Semua</option>
                                        <?php
                                        foreach ($listDepartemen as $departemen) :
                                            if ($departemen['dep_id'] !== 'CSSD') :
                                        ?>
                                                <option id="departemenOption<?= $departemen['dep_id']; ?>" value="<?= $departemen['dep_id']; ?>"><?= $departemen['nama']; ?></option>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </select>
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
        <div class="col-lg-10">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Permintaan BMHP/Kasa Steril</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-border-head table-hover" id="tabelPermintaanBmhpSteril">
                            <thead class="thead-light">
                                <th scope="col" class="text-center align-middle">No. Referensi</th>
                                <th scope="col" class="text-center align-middle">Tanggal</th>
                                <th scope="col" class="text-center align-middle">Jam</th>
                                <th scope="col" class="text-center align-middle">Ruangan</th>
                                <th scope="col" class="text-center align-middle">Petugas Minta</th>
                                <th scope="col" class="text-center align-middle">Petugas CSSD</th>
                                <th scope="col" class="text-center align-middle">Detail</th>
                                <th scope="col" class="text-center align-middle">Aksi</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="<?= base_url('/permintaan-bmhp-steril/tambah'); ?>" class="btn btn-primary float-right">
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
    let url = "<?= base_url(); ?>/permintaan-bmhp-steril";
</script>
<script src="<?= base_url(); ?>/public/js/distribusi/permintaanbmhpsteril/index_permintaanbmhpsteril.js"></script>
<?= $this->endSection(); ?>