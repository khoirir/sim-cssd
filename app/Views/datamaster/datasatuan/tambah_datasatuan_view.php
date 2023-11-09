<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/data-satuan'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Data Satuan</h3>
                </div>

                <form action="<?= base_url('/data-satuan/simpan'); ?>" method="POST" id="formDataSatuan">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">SATUAN</legend>
                            <div class="form-group">
                                <label for="kodeSatuan">Kode Satuan</label>
                                <input type="text" class="form-control" name="kodeSatuan" id="kodeSatuan">
                                <div class="invalid-feedback" id="invalidKodeSatuan"></div>
                            </div>
                            <div class="form-group">
                                <label for="namaSatuan">Nama Satuan</label>
                                <input type="text" class="form-control" name="namaSatuan" id="namaSatuan">
                                <div class="invalid-feedback" id="invalidNamaSatuan"></div>
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
<script src="<?= base_url(); ?>/js/datamaster/datasatuan/proses_tambah_edit_datasatuan.js"></script>
<?= $this->endSection(); ?>