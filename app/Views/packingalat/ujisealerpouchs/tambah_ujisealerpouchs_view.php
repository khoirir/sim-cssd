<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/uji-sealer-pouchs'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Uji Sealer Pouchs</h3>
                </div>
                <form action="<?= base_url('/uji-sealer-pouchs/simpan'); ?>" method="POST" enctype="multipart/form-data" id="formUjiSealerPouchs">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">petugas, Bukti uji, & hasil</legend>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tanggalUji">Tanggal</label>
                                    <input type="date" class="form-control" name="tanggalUji" id="tanggalUji" value="<?= $tanggalSekarang; ?>">
                                    <div class="invalid-feedback" id="invalidTanggalUji"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="petugas">Petugas</label>
                                <div class="form-group mb-0" id="divPetugas">
                                    <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas" name="petugas" id="petugas">
                                        <option></option>
                                        <?php foreach ($listPegawaiCSSD as $pegawai) : ?>
                                            <option value="<?= $pegawai['nik']; ?>"><?= $pegawai['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="invalid-feedback mt-1" id="invalidPetugas"></div>
                            </div>
                            <div class="form-group">
                                <label for="uploadBuktiUjiSealer">Upload Bukti Uji Sealer</label>
                                <div class="previewBuktiUjiSealer card mb-2" style="display:none; width: 50%;"></div>
                                <input class="form-control" type="file" name="uploadBuktiUjiSealer" id="uploadBuktiUjiSealer" onchange="previewFile(this, 'previewBuktiUjiSealer', '#previewName')" accept=".jpg, .jpeg, .png">
                                <input type="hidden" id="previewName" name="namaFile">
                                <div class="invalid-feedback" id="invalidUploadBuktiUjiSealer"></div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="checkboxSuhuMesin" name="checkboxSuhuMesin" value="checked" class="form-check-input">
                                    <label for="checkboxSuhuMesin" class="form-check-label check-label">
                                        Suhu Mesin 200
                                    </label>
                                    <div class="invalid-feedback" id="invalidCheckboxSuhuMesin"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="checkboxSpeedSedang" name="checkboxSpeedSedang" value="checked" class="form-check-input">
                                    <label for="checkboxSpeedSedang" class="form-check-label check-label">
                                        Speed Sedang
                                    </label>
                                    <div class="invalid-feedback" id="invalidCheckboxSpeedSedang"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-flex">Hasil</label>
                                <div class="icheck-primary d-inline mr-5">
                                    <input type="radio" id="radioBocor" value="bocor" name="radioHasilUji" class="form-radio-input">
                                    <label for="radioBocor" class="form-check-label radio-label">
                                        Bocor
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioTidak" value="tidak" name="radioHasilUji" class="form-radio-input">
                                    <label for="radioTidak" class="form-check-label radio-label">
                                        Tidak
                                    </label>
                                    <div class="invalid-feedback mt-2" id="invalidHasilUji"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" rows="2" name="keterangan" id="keterangan"></textarea>
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
<script src="<?= base_url(); ?>/js/packingalat/ujisealerpouchs/proses_tambah_edit_ujisealerpouchs.js"></script>
<?= $this->endSection(); ?>