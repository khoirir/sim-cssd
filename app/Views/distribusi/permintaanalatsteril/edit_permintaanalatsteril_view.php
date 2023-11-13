<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/permintaan-alat-steril'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <form action="<?= base_url('/permintaan-alat-steril/update/' . $dataPermintaan['id']); ?>" method="POST" id="formPermintaanAlatSteril">
                <?= csrf_field(); ?>
                <div class="form-row">
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Permintaan Alat Steril / <?= generateNoReferensi($dataPermintaan['created_at'], $dataPermintaan['id']); ?></h3>
                            </div>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Petugas & Ruangan</legend>
                                    <div class="form-group">
                                        <label for="tanggalPermintaan">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggalPermintaan" id="tanggalPermintaan" value="<?= date('Y-m-d', strtotime($dataPermintaan['tanggal_minta'])); ?>">
                                        <div class="invalid-feedback" id="invalidTanggalPermintaan"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jamPermintaan">Jam</label>
                                        <input type="time" class="form-control" name="jamPermintaan" id="jamPermintaan" value="<?= date('H:i', strtotime($dataPermintaan['tanggal_minta'])); ?>">
                                        <div class="invalid-feedback" id="invalidJamPermintaan"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugasCSSD">Petugas CSSD</label>
                                        <div class="form-group mb-0" id="divPetugasCSSD">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas CSSD" name="petugasCSSD" id="petugasCSSD">
                                                <option value=""></option>
                                                <?php
                                                foreach ($listPegawaiCSSD as $pegawai) :
                                                ?>
                                                    <option id="pegawaiOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= ($dataPermintaan['id_petugas_cssd']) == $pegawai['nik'] ? 'selected' : ''; ?>><?= $pegawai['nama']; ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback mt-1" id="invalidPetugasCSSD"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugasMinta">Petugas Minta Alat Steril</label>
                                        <div class="form-group mb-0" id="divPetugasMinta">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas Minta" name="petugasMinta" id="petugasMinta">
                                                <option></option>
                                                <?php
                                                foreach ($listPegawai as $pegawai) :
                                                    if ($pegawai['departemen'] !== 'CSSD') :
                                                ?>
                                                        <option id="pegawaiMintaOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= ($dataPermintaan['id_petugas_minta']) == $pegawai['nik'] ? 'selected' : ''; ?>><?= $pegawai['nama']; ?></option>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback mt-1" id="invalidPetugasMinta"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ruangan">Ruangan</label>
                                        <input type="text" class="form-control" name="inputRuangan" id="inputRuangan" value="<?= $dataPermintaan['ruangan']; ?>" readonly>
                                        <input type="hidden" class="form-control" name="idRuangan" id="idRuangan" value="<?= $dataPermintaan['id_ruangan']; ?>" disabled>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="card-footer">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">&nbsp;</h3>
                            </div>
                            <div class="card-body pb-0">
                                <fieldset class="content-group">
                                    <legend class="text-bold">ALAT, jumlah, & Keterangan</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="alat">Nama Alat</label>
                                            <div class="form-group mb-0" id="divAlat">
                                                <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Alat" name="alat" id="alat">
                                                    <option></option>
                                                </select>
                                                <div class="invalid-feedback-custom mt-1" id="invalidAlat"></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="jumlah">Jumlah</label>
                                            <input class="form-control" name="jumlah" id="jumlah" type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Input harus berupa angka">
                                            <div class="invalid-feedback-custom mt-1" id="invalidJumlah"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" rows="3" name="keterangan" id="keterangan"></textarea>
                                        <div class="invalid-feedback-custom" id="invalidKeterangan"></div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary float-right" id="btnTambah">
                                    <i class="fas fa-plus mr-2"></i> Tambah
                                </button>
                            </div>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">DATA ALAT</legend>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover table-border-head mb-0" id="tabelDataAlat" name="tabelDataAlat">
                                                <thead class="thead-light">
                                                    <th scope="col" rowspan="3" class="text-center align-middle" style="width: 50%;">Nama Alat</th>
                                                    <th scope="col" rowspan="3" class="text-center align-middle" style="width: 10%;">Jumlah</th>
                                                    <th scope="col" rowspan="3" class="text-center align-middle" style="width: 30%;">Keterangan</th>
                                                    <th scope="col" rowspan="3" class="text-center align-middle" style="width: 10%;">Aksi</th>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($dataDetail->getResultArray() as $detail) : ?>
                                                        <tr>
                                                            <td class="align-middle" style="width: 50%"><span data-values="<?= $detail['id_alat']; ?>"><?= $detail['nama_set_alat']; ?></span></td>
                                                            <td class="text-center align-middle" style="width: 10%"><span data-values="<?= $detail['id_detail_penerimaan_alat_kotor']; ?>"><?= $detail['jumlah']; ?></span></td>
                                                            <td class="align-middle" style="width: 30%"><span><?= $detail['keterangan']; ?></span></td>
                                                            <td class="text-center align-middle" style="width: 10%">
                                                                <button type="button" class="btn btn-danger btn-sm border-0" values="<?= $detail['id']; ?>" onclick="hapusBarisTabel(this,'<?= $detail['id']; ?>')"><i class="far fa-trash-alt"></i></button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <?php if ($dataDetail->getNumRows() === 0) : ?>
                                                    <tfoot>
                                                        <td colspan="4" class="text-center align-middle data-kosong">DATA TIDAK ADA</td>
                                                    </tfoot>
                                                <?php endif; ?>
                                            </table>
                                            <div class="invalid-feedback" id="invalidTabelDataAlat"></div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right" id="btnSimpan">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->
<script>
    let url = "<?= base_url('/permintaan-alat-steril'); ?>";
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
</script>
<script src="<?= base_url(); ?>/public/js/distribusi/permintaanalatsteril/proses_tambah_edit_permintaanalatsteril.js"></script>
<!-- /.content -->
<?= $this->endSection(); ?>