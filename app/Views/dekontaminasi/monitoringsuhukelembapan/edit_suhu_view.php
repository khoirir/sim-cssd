<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-6">
            <a class="btn btn-primary mb-3" href="<?= base_url('/monitoring-suhu-kelembapan'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Edit Suhu & Kelembapan / <?= generateNoReferensi($dataSuhuKelembapanBerdasarkanId['created_at'], $dataSuhuKelembapanBerdasarkanId['id']); ?></h3>
                </div>
                <form action="<?= base_url('/monitoring-suhu-kelembapan/update/' . $dataSuhuKelembapanBerdasarkanId['id']); ?>" method="POST" id="formSuhuKelembapan">
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <fieldset class="content-group">
                            <legend class="text-bold">PETUGAS, SUHU, & KELEMBAPAN</legend>
                            <div class="form-group">
                                <label for="tanggalCatat">Tanggal</label>
                                <input type="date" class="form-control" name="tanggalCatat" id="tanggalCatat" value="<?= $dataSuhuKelembapanBerdasarkanId['tgl_catat']; ?>" readonly>
                                <div class="invalid-feedback" id="invalidTanggalCatat"></div>
                            </div>
                            <div class=" form-group">
                                <label for="petugas">Petugas</label>
                                <div class="form-group mb-0" id="divPetugas">
                                    <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas" name="petugas" id="petugas">
                                        <option></option>
                                        <?php foreach ($listPegawaiCSSD as $pegawai) : ?>
                                            <option value="<?= $pegawai['nik']; ?>" <?= ($dataSuhuKelembapanBerdasarkanId['id_petugas']) == $pegawai['nik'] ? 'selected' : ''; ?>><?= $pegawai['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="invalid-feedback mt-1" id="invalidPetugas"></div>
                            </div>
                            <div class="form-group">
                                <label for="suhu">Suhu</label>
                                <input class="form-control" name="suhu" id="suhu" type="text" value="<?= $dataSuhuKelembapanBerdasarkanId['suhu']; ?>" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Input harus berupa angka" placeholder="Suhu (°C)">
                                <div class="invalid-feedback" id="invalidSuhu"></div>
                            </div>
                            <div class="form-group">
                                <label for="kelembapan">Kelembapan</label>
                                <input class="form-control" name="kelembapan" id="kelembapan" value="<?= $dataSuhuKelembapanBerdasarkanId['kelembapan']; ?>" type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Input harus berupa angka" placeholder="Kelembapan (%)">
                                <div class="invalid-feedback" id="invalidKelembapan"></div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            <label class="text-danger mb-0">Standar suhu ruang CSSD: 22 - 30 °C<br>Standar kelembapan ruang CSSD: 35 - 75 %</label>
                        </div>
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
<script src="<?= base_url(); ?>/js/dekontaminasi/monitoringsuhukelembapan/proses_tambah_edit_monitoringsuhukelembapan.js"></script>
<?= $this->endSection(); ?>