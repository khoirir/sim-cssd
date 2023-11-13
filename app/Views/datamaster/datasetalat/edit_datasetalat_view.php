<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/data-set-alat'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Set Alat</h3>
                </div>

                <form action="<?= base_url('/data-set-alat/update/' . $dataSetAlat['id']); ?>" method="POST" id="formDataSetAlat">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">SET ALAT</legend>
                            <div class="form-group">
                                <label for="namaSetAlat">Nama Set Alat</label>
                                <input type="text" class="form-control" name="namaSetAlat" id="namaSetAlat" value="<?= $dataSetAlat['nama_set_alat']; ?>">
                                <div class="invalid-feedback" id="invalidNamaSetAlat"></div>
                            </div>
                            <div class="form-group">
                                <label for="jenisSetAlat">Jenis Set Alat</label>
                                <div class="form-group mb-0" id="divJenisSetAlat">
                                    <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Jenis Set Alat" name="jenisSetAlat" id="jenisSetAlat">
                                        <option></option>
                                        <?php foreach ($jenisSetAlat as $jenis) : ?>
                                            <option value="<?= $jenis['id']; ?>" <?= ($dataSetAlat['id_jenis'] === $jenis['id']) ? 'selected' : ''; ?>><?= $jenis['nama_jenis']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="invalid-feedback mt-1" id="invalidJenisSetAlat"></div>
                            </div>
                            <div class="form-group">
                                <label for="satuanSetAlat">Satuan Set Alat</label>
                                <div class="form-group mb-0" id="divSatuanSetAlat">
                                    <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Satuan Set Alat" name="satuanSetAlat" id="satuanSetAlat">
                                        <option></option>
                                        <?php foreach ($satuanSetAlat as $satuan) : ?>
                                            <option value="<?= $satuan['id']; ?>" <?= ($dataSetAlat['id_satuan'] === $satuan['id']) ? 'selected' : ''; ?>><?= strtoupper($satuan['kode_satuan']) . ' (' . $satuan['nama_satuan'] . ')'; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="invalid-feedback mt-1" id="invalidSatuanSetAlat"></div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right" id="btnSimpan">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->
<script src="<?= base_url(); ?>/public/js/datamaster/datasetalat/proses_tambah_edit_datasetalat.js"></script>
<?= $this->endSection(); ?>