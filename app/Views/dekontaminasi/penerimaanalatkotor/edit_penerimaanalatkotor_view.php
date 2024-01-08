<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/penerimaan-alat-kotor'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <form action="<?= base_url('/penerimaan-alat-kotor/update/' . $dataPenerimaanAlatKotorBerdasarkanId['id']); ?>" method="POST" id="formPenerimaanAlatKotor">
                <?= csrf_field(); ?>
                <div class="form-row">
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Penerimaan Alat Kotor / <?= generateNoReferensi($dataPenerimaanAlatKotorBerdasarkanId['created_at'], $dataPenerimaanAlatKotorBerdasarkanId['id']); ?></h3>
                            </div>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Tanggal, Petugas, & Ruangan</legend>
                                    <div class="form-group">
                                        <label for="tanggalPenerimaan">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggalPenerimaan" id="tanggalPenerimaan" value="<?= date('Y-m-d', strtotime($dataPenerimaanAlatKotorBerdasarkanId['tanggal_penerimaan'])); ?>">
                                        <div class="invalid-feedback" id="invalidTanggalPenerimaan"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jamPenerimaan">Jam</label>
                                        <input type="time" class="form-control" name="jamPenerimaan" id="jamPenerimaan" value="<?= date('H:i', strtotime($dataPenerimaanAlatKotorBerdasarkanId['tanggal_penerimaan'])); ?>">
                                        <div class="invalid-feedback" id="invalidJamPenerimaan"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugasCSSD">Petugas CSSD</label>
                                        <div class="form-group mb-0" id="divPetugasCSSD">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas CSSD" name="petugasCSSD" id="petugasCSSD">
                                                <option value=""></option>
                                                <?php foreach ($listPegawaiCSSD as $pegawai) : ?>
                                                    <option id="pegawaiOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= ($dataPenerimaanAlatKotorBerdasarkanId['id_petugas_cssd']) == $pegawai['nik'] ? 'selected' : ''; ?>><?= $pegawai['nama']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback mt-1" id="invalidPetugasCSSD"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugasPenyetor">Petugas Penyetor</label>
                                        <div class="form-group mb-0" id="divPetugasPenyetor">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas Penyetor" name="petugasPenyetor" id="petugasPenyetor">
                                                <option></option>
                                                <?php
                                                foreach ($listPegawai as $pegawai) :
                                                    if ($pegawai['departemen'] !== 'CSSD') :
                                                ?>
                                                        <option id="pegawaiPenyetorOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= ($dataPenerimaanAlatKotorBerdasarkanId['id_petugas_penyetor']) == $pegawai['nik'] ? 'selected' : ''; ?>><?= $pegawai['nama']; ?></option>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback mt-1" id="invalidPetugasPenyetor"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ruangan">Ruangan</label>
                                        <div class="form-group mb-0" id="divRuangan">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Ruangan" name="ruangan" id="ruangan">
                                                <option></option>
                                                <?php
                                                foreach ($listDepartemen as $departemen) :
                                                    if ($departemen['dep_id'] !== 'CSSD') :
                                                ?>
                                                        <option id="departemenOption<?= $departemen['dep_id']; ?>" value="<?= $departemen['dep_id']; ?>" <?= ($dataPenerimaanAlatKotorBerdasarkanId['id_ruangan']) == $departemen['dep_id'] ? 'selected' : ''; ?>><?= $departemen['nama']; ?></option>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback mt-1" id="invalidRuangan"></div>
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
                                    <legend class="text-bold">SET/ALAT & PROSES</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="setAlat">Nama Set/Alat</label>
                                            <div class="form-group mb-0" id="divSetAlat">
                                                <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Set/Alat" name="setAlat" id="setAlat">
                                                    <option></option>
                                                    <?php foreach ($listSetAlat as $setAlat) : ?>
                                                        <option id="setAlatOption<?= $setAlat['id']; ?>" value="<?= $setAlat['id']; ?>"><?= $setAlat['nama_set_alat']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback-custom mt-1" id="invalidSetAlat"></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="jumlah">Jumlah</label>
                                            <input class="form-control" name="jumlah" id="jumlah" type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Input harus berupa angka">
                                            <div class="invalid-feedback-custom mt-1" id="invalidJumlah"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex">Proses</label>
                                        <div class="row align-items-start">
                                            <div class="icheck-primary col-md-2">
                                                <input type="checkbox" id="checkboxEnzym" name="checkboxEnzym" value="enzym" class="form-check-input">
                                                <label for="checkboxEnzym" class="form-check-label">
                                                    Manual - Enzym
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-2">
                                                <input type="checkbox" id="checkboxDTT" name="checkboxDTT" value="dtt" class="form-check-input">
                                                <label for="checkboxDTT" class="form-check-label">
                                                    Manual - DTT
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-2">
                                                <input type="checkbox" id="checkboxUltrasonic" name="checkboxUltrasonic" value="ultrasonic" class="form-check-input">
                                                <label for="checkboxUltrasonic" class="form-check-label">
                                                    Ultrasonic
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-2">
                                                <input type="checkbox" id="checkboxBilas" name="checkboxBilas" value="bilas" class="form-check-input">
                                                <label for="checkboxBilas" class="form-check-label">
                                                    Bilas
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-2">
                                                <input type="checkbox" id="checkboxWasher" name="checkboxWasher" value="washer" class="form-check-input">
                                                <label for="checkboxWasher" class="form-check-label">
                                                    Washer
                                                </label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidProses"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex">Pemilihan Mesin</label>
                                        <div class="row align-items-start">
                                            <div class="icheck-primary col-md-2">
                                                <input type="radio" id="radioMesinSteam" value="Steam" name="pemilihanMesin" class="form-radio-input">
                                                <label for="radioMesinSteam" class="form-check-label radio-label">
                                                    Mesin Steam
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-2">
                                                <input type="radio" id="radioMesinEog" value="EOG" name="pemilihanMesin" class="form-radio-input">
                                                <label for="radioMesinEog" class="form-check-label radio-label">
                                                    Mesin EOG
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-2">
                                                <input type="radio" id="radioMesinPlasma" value="Plasma" name="pemilihanMesin" class="form-radio-input">
                                                <label for="radioMesinPlasma" class="form-check-label radio-label">
                                                    Mesin Plasma
                                                </label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidPemilihanMesin"></div>
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
                                    <legend class="text-bold">DATA SET/ALAT</legend>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover table-border-head mb-0" id="tabelDataSetAlat" name="tabelDataSetAlat">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" rowspan="3" class="text-center align-middle" style="width: 30%;">Nama Set/Alat</th>
                                                        <th scope="col" rowspan="3" class="text-center align-middle" style="width: 10%;">Jumlah</th>
                                                        <th scope="col" colspan="5" class="text-center" style="width: 35%;">Proses</th>
                                                        <th scope="col" rowspan="3" class="text-center align-middle" style="width: 15%;">Mesin</th>
                                                        <th scope="col" rowspan="3" class="text-center align-middle" style="width: 10%;">Aksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" colspan="2" class="text-center">Manual</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle">Ultrasonic</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle">Bilas</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle">Washer</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Enzym</th>
                                                        <th scope="col" class="text-center">DTT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($dataPenerimaanAlatKotorDetailBerdasarkanIdMaster) :
                                                        foreach ($dataPenerimaanAlatKotorDetailBerdasarkanIdMaster->getResultArray() as $data) :
                                                    ?>
                                                            <tr>
                                                                <td class="align-middle"><span data-values="<?= $data['id_set_alat']; ?>"><?= $data['nama_set_alat']; ?></span></td>
                                                                <td class="text-center align-middle"><?= $data['jumlah']; ?></td>
                                                                <td class="text-center align-middle"><?= $data['enzym'] ? '<i class="fas fa-check" values="' . $data['enzym'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['dtt'] ? '<i class="fas fa-check" values="' . $data['dtt'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['ultrasonic'] ? '<i class="fas fa-check" values="' . $data['ultrasonic'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['bilas'] ? '<i class="fas fa-check" values="' . $data['bilas'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['washer'] ? '<i class="fas fa-check" values="' . $data['washer'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['pemilihan_mesin']; ?></td>
                                                                <td class="text-center align-middle">
                                                                    <button type="button" class="btn btn-danger btn-sm border-0" values="<?= $data['id']; ?>" onclick=" hapusBarisTabel('tabelDataSetAlat', this, '<?= $data['id'] ?? ''; ?>')"><i class="far fa-trash-alt"></i></button>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </tbody>
                                                <?php
                                                if (!$dataPenerimaanAlatKotorDetailBerdasarkanIdMaster) :
                                                ?>
                                                    <tfoot>
                                                        <td colspan="9" class="text-center align-middle data-kosong">DATA TIDAK ADA</td>
                                                    </tfoot>
                                                <?php
                                                endif;
                                                ?>
                                            </table>
                                            <div class="invalid-feedback" id="invalidTabelDataSetAlat"></div>
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
<script src="<?= base_url(); ?>public/js/dekontaminasi/penerimaanalatkotor/proses_tambah_edit_penerimaanalatkotor.js"></script>
<?= $this->endSection(); ?>