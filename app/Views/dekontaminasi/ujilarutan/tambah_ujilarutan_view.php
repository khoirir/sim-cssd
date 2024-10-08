<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/uji-larutan-dtt-alkacyd'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Uji Larutan DTT Alkacyd</h3>
                </div>
                <form action="<?= base_url('/uji-larutan-dtt-alkacyd/simpan'); ?>" method="POST" enctype="multipart/form-data" id="formUjiLarutanDttAlkacyd">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">Petugas, Larutan, & Hasil</legend>
                            <div class="form-group">
                                <label for="tanggalUji">Tanggal</label>
                                <input type="date" class="form-control" name="tanggalUji" id="tanggalUji" value="<?= $tanggalSekarang; ?>">
                                <div class="invalid-feedback" id="invalidTanggalUji"></div>
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
                                <label for="uploadLarutan">Gambar Larutan</label>
                                <div class="previewUploadLarutan card mb-2" style="display:none; width: 50%;"></div>
                                <input class="form-control" type="file" name="uploadLarutan" id="uploadLarutan" onchange="previewFile(this, 'previewUploadLarutan', '#previewName')" accept=".jpg, .jpeg, .png">
                                <input type="hidden" id="previewName" name="namaFile">
                                <div class="invalid-feedback" id="invalidUploadLarutan"></div>
                            </div>
                            <div class=" form-group">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="checkboxMetracid" name="checkboxMetracid" value="checked" class="form-check-input">
                                    <label for="checkboxMetracid" class="form-check-label check-label">
                                        METRACID 1 ml
                                    </label>
                                    <div class="invalid-feedback" id="invalidCheckboxMetracid"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="checkboxAlkacid" name="checkboxAlkacid" value="checked" class="form-check-input">
                                    <label for="checkboxAlkacid" class="form-check-label check-label">
                                        ALKACID 10 ml
                                    </label>
                                    <div class="invalid-feedback" id="invalidCheckboxAlkacid"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-flex mb-3">Hasil</label>
                                <div class="icheck-primary d-inline mr-5">
                                    <input type="radio" id="radioWarnaUngu" value="ungu" name="radioHasilWarna" class="form-radio-input">
                                    <label for="radioWarnaUngu" class="form-check-label radio-label">
                                        Warna Ungu
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioWarnaPink" value="pink" name="radioHasilWarna" class="form-radio-input">
                                    <label for="radioWarnaPink" class="form-check-label radio-label">
                                        Warna Pink
                                    </label>
                                    <div class="invalid-feedback mt-2" id="invalidHasilWarna"></div>
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
<script src="<?= base_url(); ?>/js/dekontaminasi/ujilarutan/proses_tambah_edit_ujilarutan.js"></script>
<?= $this->endSection(); ?>