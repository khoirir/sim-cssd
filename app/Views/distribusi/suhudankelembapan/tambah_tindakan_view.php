<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-7">
            <a class="btn btn-primary mb-3" href="<?= base_url('/suhu-dan-kelembapan'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Tindakan Jika Suhu & Kelembapan di Luar Batas / <?= $noReferensi; ?></h3>
                </div>
                <form action="<?= base_url('/suhu-dan-kelembapan/simpan-tindakan/' . $idSuhuKelembapan); ?>" method="POST" id="formHasilTindakan">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">HASIL & TINDAKAN</legend>
                            <div class="form-group">
                                <label for="tanggalCatat">Tanggal</label>
                                <input class="form-control" name="tanggalCatat" type="text" id="tanggalCatat" value="<?= $tanggal; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="petugas">Petugas</label>
                                <input class="form-control" name="petugas" type="text" id="petugas" value="<?= $petugas; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="suhu">Suhu</label>
                                <input class="form-control" name="suhu" type="text" id="suhu" value="<?= $suhu; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="kelembapan">Kelembapan</label>
                                <input class="form-control" name="kelembapan" type="text" id="kelembapan" value="<?= $kelembapan; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="hasilTindakan">Hasil dan Tindakan</label>
                                <textarea class="form-control" rows="3" name="hasilTindakan" id="hasilTindakan"><?= $hasilTindakan; ?></textarea>
                                <div class="invalid-feedback" id="invalidTindakan"></div>
                            </div>
                        </fieldset>
                    </div>
                </form>
                <div class="card-footer">
                    <div class="<?= $hasilTindakan ? 'd-flex justify-content-between' : ''; ?>">
                        <?php if ($hasilTindakan) : ?>
                            <form action="<?= base_url('/suhu-dan-kelembapan/hapus-tindakan/' . $idSuhuKelembapan); ?>" method="POST" id="formHapusTindakan">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">
                                    <i class=" far fa-trash-alt mr-2"></i> Hapus
                                </button>
                            </form>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-success float-right" form="formHasilTindakan" id="btnSimpan">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->
<script src="<?= base_url(); ?>/js/distribusi/suhudankelembapan/tindakan_suhudankelembapan.js"></script>
<?= $this->endSection(); ?>