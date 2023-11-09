<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/data-bmhp'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Edit Data BMHP</h3>
                </div>

                <form action="<?= base_url('/data-bmhp/update/' . $dataBmhp['id']); ?>" method="POST" id="formDataBmhp">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">BMHP</legend>
                            <div class="form-group">
                                <label for="namaBmhp">Nama BMHP</label>
                                <input type="text" class="form-control" name="namaBmhp" id="namaBmhp" value="<?= $dataBmhp['nama_set_alat']; ?>">
                                <div class="invalid-feedback" id="invalidNamaBmhp"></div>
                            </div>
                            <div class="form-group">
                                <label for="hargaBmhp">Harga BMHP</label>
                                <input type="text" class="form-control" name="hargaBmhp" id="hargaBmhp" value="<?= number_format($dataBmhp['harga'], 2, ',', '.'); ?>">
                                <div class="invalid-feedback" id="invalidHargaBmhp"></div>
                            </div>
                            <div class="form-group">
                                <label for="satuanBmhp">Satuan BMHP</label>
                                <div class="form-group mb-0" id="divSatuanBmhp">
                                    <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Satuan BMHP" name="satuanBmhp" id="satuanBmhp">
                                        <option></option>
                                        <?php foreach ($satuanBmhp as $satuan) : ?>
                                            <option value="<?= $satuan['id']; ?>" <?= ($dataBmhp['id_satuan'] === $satuan['id']) ? 'selected' : ''; ?>><?= strtoupper($satuan['kode_satuan']) . ' (' . $satuan['nama_satuan'] . ')'; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="invalid-feedback mt-1" id="invalidSatuanBmhp"></div>
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
<script src="<?= base_url(); ?>/js/datamaster/databmhp/proses_tambah_edit_databmhp.js"></script>
<?= $this->endSection(); ?>