<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/monitoring-packing-alat'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <form action="<?= base_url('/monitoring-packing-alat/update/' . $dataMonitoringPackingAlatBerdasarkanId['id']); ?>" method="POST" id="formPackingAlat">
                <?= csrf_field(); ?>
                <div class="form-row">
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Monitoring Packing Alat / <?= generateNoReferensi($dataMonitoringPackingAlatBerdasarkanId['created_at'], $dataMonitoringPackingAlatBerdasarkanId['id']); ?></h3>
                            </div>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">PETUGAS & ALAT</legend>
                                    <div class="form-group">
                                        <label for="tanggalPacking">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggalPacking" id="tanggalPacking" value="<?= $dataMonitoringPackingAlatBerdasarkanId['tanggal_packing']; ?>">
                                        <div class="invalid-feedback" id="invalidTanggalPacking"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugas">Petugas</label>
                                        <div class="form-group mb-0" id="divPetugas">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas" name="petugas" id="petugas">
                                                <option value=""></option>
                                                <?php foreach ($listPegawaiCSSD as $pegawai) : ?>
                                                    <option id="petugasOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>"><?= $pegawai['nama']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidPetugas"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="setAlat">Nama Alat</label>
                                        <div class="form-group mb-0" id="divSetAlat">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Alat" name="setAlat" id="setAlat">
                                                <option></option>
                                                <?php foreach ($listSetAlat as $setAlat) : ?>
                                                    <option id="setAlatOption<?= $setAlat['id']; ?>" value="<?= $setAlat['id']; ?>"><?= $setAlat['nama_set_alat']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback-custom mt-1" id="invalidSetAlat"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex mb-0">Uji Visual</label>
                                        <div class="row align-items-start mt-0">
                                            <div class="icheck-primary col-md-3">
                                                <input type="checkbox" id="checkboxBersih" name="checkboxUjiVisual" value="bersih" class="form-check-input">
                                                <label for="checkboxBersih" class="form-check-label">
                                                    Bersih
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-3">
                                                <input type="checkbox" id="checkboxTajam" name="checkboxUjiVisual" value="tajam" class="form-check-input">
                                                <label for="checkboxTajam" class="form-check-label">
                                                    Tajam
                                                </label>
                                            </div>
                                            <div class="icheck-primary col-md-3">
                                                <input type="checkbox" id="checkboxLayak" name="checkboxUjiVisual" value="layak" class="form-check-input">
                                                <label for="checkboxLayak" class="form-check-label">
                                                    Layak
                                                </label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidUjiVisual"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex mb-1">Indikator</label>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxIndikator" name="checkboxIndikator" value="indikator" class="form-check-input">
                                            <label for="checkboxIndikator">
                                            </label>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidIndikator"></div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary float-right" id="btnTambah">
                                    <i class="fas fa-plus mr-2"></i> Tambah
                                </button>
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
                                    <legend class="text-bold">DATA ALAT</legend>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover table-border-head mb-0" id="tabelDataAlat" name="tabelDataAlat">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 30%;">Nama Alat</th>
                                                        <th scope="col" colspan="3" class="text-center" style="width: 22%;">Uji Visual</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 8%;">Indikator</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 30%;">Petugas</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 10%;">Aksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center align-middle">Bersih</th>
                                                        <th scope="col" class="text-center align-middle">Tajam</th>
                                                        <th scope="col" class="text-center align-middle">Layak</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($dataMonitoringPackingAlatDetailBerdasarkanIdMaster) :
                                                        foreach ($dataMonitoringPackingAlatDetailBerdasarkanIdMaster->getResultArray() as $data) :
                                                    ?>
                                                            <tr>
                                                                <td class="align-middle"><span data-values="<?= $data['id_alat']; ?>"><?= $data['nama_set_alat']; ?></span></td>
                                                                <td class="text-center align-middle"><?= $data['bersih'] ? '<i class="fas fa-check" values="' . $data['bersih'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['tajam'] ? '<i class="fas fa-check" values="' . $data['tajam'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['layak'] ? '<i class="fas fa-check" values="' . $data['layak'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['indikator'] ? '<i class="fas fa-check" values="' . $data['indikator'] . '"></i>' : ''; ?></td>
                                                                <td class="align-middle"><span data-values="<?= $data['id_petugas']; ?>"><?= $data['nama']; ?></span></td>
                                                                <td class="text-center align-middle">
                                                                    <button type="button" class="btn btn-danger btn-sm border-0" values="<?= $data['id']; ?>" onclick=" hapusBarisTabel('tabelDataAlat', this, '<?= $data['id']; ?>')"><i class="far fa-trash-alt"></i></button>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </tbody>
                                                <?php
                                                if (!$dataMonitoringPackingAlatDetailBerdasarkanIdMaster) :
                                                ?>
                                                    <tfoot>
                                                        <td colspan="9" class="text-center align-middle data-kosong">DATA TIDAK ADA</td>
                                                    </tfoot>
                                                <?php
                                                endif;
                                                ?>
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
<script src="<?= base_url(); ?>public/js/packingalat/monitoringpackingalat/proses_tambah_edit_monitoringpackingalat.js"></script>
<?= $this->endSection(); ?>