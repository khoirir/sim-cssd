<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/monitoring-mesin-plasma'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <form action="<?= base_url('/monitoring-mesin-plasma/update/' . $dataMesinPlasma['id']); ?>" method="POST" enctype="multipart/form-data" id="formMonitoringMesinPlasma">
                <?= csrf_field(); ?>
                <div class="form-row">
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Monitoring Mesin Plasma / <?= generateNoReferensi($dataMesinPlasma['created_at'], $dataMesinPlasma['id']); ?></h3>
                            </div>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Siklus & Operator</legend>
                                    <div class="form-group">
                                        <label for="tanggalMonitoring">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggalMonitoring" id="tanggalMonitoring" value="<?= date('Y-m-d', strtotime($dataMesinPlasma['tanggal_monitoring'])); ?>">
                                        <div class="invalid-feedback" id="invalidTanggalMonitoring"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jamMasukAlat">Jam Masuk Alat</label>
                                        <input type="time" class="form-control" name="jamMasukAlat" id="jamMasukAlat" value="<?= date('H:i', strtotime($dataMesinPlasma['tanggal_monitoring'])); ?>">
                                        <div class="invalid-feedback" id="invalidJamMasukAlat"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="siklus">Siklus</label>
                                        <input class="form-control" name="siklus" id="siklus" type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Input harus berupa angka" value="<?= $dataMesinPlasma['siklus']; ?>">
                                        <div class="invalid-feedback mt-2" id="invalidSiklus"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex mb-3">Shift</label>
                                        <div class="icheck-primary d-inline mr-3">
                                            <input type="radio" id="radioShiftPagi" value="pagi" name="shift" <?= ($dataMesinPlasma['shift'] == 'pagi') ? 'checked' : ''; ?> class="form-radio-input">
                                            <label for="radioShiftPagi" class="form-check-label radio-label">
                                                Pagi
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radioShiftSore" value="sore" name="shift" <?= ($dataMesinPlasma['shift'] == 'sore') ? 'checked' : ''; ?> class="form-radio-input">
                                            <label for="radioShiftSore" class="form-check-label radio-label">
                                                Sore
                                            </label>
                                            <div class="invalid-feedback mt-2" id="invalidShift"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="operator">Operator</label>
                                        <div class="form-group mb-0" id="divOperator">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Operator" name="operator" id="operator" multiple>
                                                <option value=""></option>
                                                <?php
                                                foreach ($listPegawaiCSSD as $pegawai) :
                                                    $selected = '';
                                                    $idValue = "";
                                                    if (in_array($pegawai['nik'], array_column($dataOperator, 'id_operator'))) {
                                                        $selected = 'selected';
                                                        $operatorIds = array_column($dataOperator, 'id_operator');
                                                        $key = array_search($pegawai['nik'], $operatorIds);
                                                        if ($key !== false) {
                                                            $idValue = $dataOperator[$key]['id'];
                                                        }
                                                    }
                                                ?>
                                                    <option id="operatorOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= $selected; ?>><?= $pegawai['nama']; ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback mt-1" id="invalidOperator"></div>
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
                                                        <option id="ruanganOption<?= $departemen['dep_id']; ?>" value="<?= $departemen['dep_id']; ?>"><?= $departemen['nama']; ?></option>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidRuangan"></div>
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
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Alat & Jumlah</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="alat">Alat</label>
                                            <div class="form-group mb-0" id="divAlat">
                                                <select class="form-control" style="width: 100%;" name="alat" id="alat">
                                                    <option></option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback-custom mt-1" id="invalidAlat"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="jumlah">Jumlah</label>
                                            <input class="form-control" name="jumlah" id="jumlah" type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Input harus berupa angka">
                                            <div class="invalid-feedback-custom mt-1" id="invalidJumlah"></div>
                                        </div>
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
                                    <legend class="text-bold">Data Alat</legend>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-border-head table-hover" id="tabelDataAlat">
                                            <thead class="thead-light">
                                                <th scope="col" class="text-center align-middle" style="width: 30%">Ruangan</th>
                                                <th scope="col" class="text-center align-middle" style="width: 50%">Nama Alat</th>
                                                <th scope="col" class="text-center align-middle" style="width: 10%">Jumlah</th>
                                                <th scope="col" class="text-center align-middle" style="width: 10%">Aksi</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($dataDetail->getResultArray() as $data) :
                                                ?>
                                                    <tr>
                                                        <td class="align-middle" style="width: 30%"><span data-values="<?= $data['id_ruangan']; ?>"><?= $data['ruangan']; ?></span></td>
                                                        <td class="align-middle" style="width: 50%"><span data-values="<?= $data['id_alat']; ?>"><?= $data['nama_set_alat']; ?></span></td>
                                                        <td class="text-center align-middle" style="width: 10%"><span data-values="<?= $data['id_detail_penerimaan_alat_kotor'] ?: $data['id_alat']; ?>"><?= $data['jumlah']; ?></span></td>
                                                        <td class="text-center align-middle" style="width: 10%">
                                                            <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel(this,'<?= $data['id']; ?>')" data-idalatkotor="<?= $data['id_detail_penerimaan_alat_kotor'] ?: ""; ?>" values="<?= $data['id']; ?>"><i class="far fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endforeach;
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="invalid-feedback" id="invalidTabelDataAlat"></div>
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
    let urlDataAlatKotor = "<?= base_url('/penerimaan-alat-kotor/data-alat-kotor'); ?>";
    let url = "<?= base_url('/monitoring-mesin-plasma'); ?>";
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
</script>
<script src="<?= base_url(); ?>/js/packingalat/monitoringmesinplasma/proses_tambah_edit_monitoringmesinplasma.js"></script>
<?= $this->endSection(); ?>