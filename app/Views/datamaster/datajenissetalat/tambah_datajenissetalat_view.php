<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/data-jenis-set-alat'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Data Jenis Set Alat</h3>
                </div>

                <form action="<?= base_url('/data-jenis-set-alat/simpan'); ?>" method="POST" id="formDataJenisSetAlat">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">JENIS SET ALAT</legend>
                            <div class="form-group">
                                <label for="namaJenis">Nama Jenis</label>
                                <input type="text" class="form-control" name="namaJenis" id="namaJenis">
                                <div class="invalid-feedback" id="invalidNamaJenis"></div>
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
<script src="<?= base_url(); ?>public/js/datamaster/datajenissetalat/proses_tambah_edit_datajenissetalat.js"></script>
<?= $this->endSection(); ?>