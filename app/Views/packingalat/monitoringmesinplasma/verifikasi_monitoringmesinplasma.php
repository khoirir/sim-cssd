<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/monitoring-mesin-plasma'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="form-row">
                <div class="col-lg-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Verifikasi Monitoring Mesin Plasma / <?= generateNoReferensi($dataMesinPlasma['created_at'], $dataMesinPlasma['id']); ?></h3>
                        </div>
                        <div class="card-body">
                            <fieldset class="content-group">
                                <legend class="text-bold">Siklus, Operator, & Alat</legend>
                                <div class="row">
                                    <div class="col">
                                        <strong><i class="far fa-calendar-alt mr-1"></i> Waktu Masuk Alat</strong>
                                        <p class="text-muted mt-1 mb-0"><?= date("d-m-Y H:i", strtotime($dataMesinPlasma['tanggal_monitoring'])); ?></p>
                                        <hr class="mt-0">
                                        <strong><i class="fas fa-arrows-spin mr-1"></i> Siklus</strong>
                                        <p class="text-muted mt-1 mb-0"><?= $dataMesinPlasma['siklus']; ?></p>
                                        <hr class="mt-0">
                                        <strong><i class="fas fa-user-gear mr-1"></i> Operator <?= ucfirst($dataMesinPlasma['shift']); ?></strong>
                                        <p class="text-muted mt-1 mb-0">
                                            <?php foreach ($operator as $op) :
                                                echo $op['nama'] . "<br>";
                                            endforeach; ?>
                                        </p>
                                        <hr class="mt-0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong><i class="fas fa-kit-medical mr-1"></i> Data Alat</strong>
                                        <div class="table-responsive mt-1">
                                            <table class="table table-sm table-border-head">
                                                <thead class=" thead-light">
                                                    <th scope="col" class="text-center align-middle" style="width: 5%">No.</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 25%">Ruangan</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 35%">Nama Alat</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 10%">Jumlah</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($detailAlat as $detail) :
                                                    ?>
                                                        <tr>
                                                            <td class="align-middle text-center"><?= $no . "."; ?></td>
                                                            <td class="align-middle"><?= $detail['ruangan']; ?></td>
                                                            <td class="align-middle"><?= $detail['nama_set_alat']; ?></td>
                                                            <td class="text-center align-middle"><?= $detail['jumlah']; ?></td>
                                                        </tr>
                                                    <?php
                                                        $no++;
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">&nbsp;</h3>
                        </div>
                        <?php
                        if ($dataVerifikasi) {
                            $id = $dataVerifikasi['id'];
                            $tanggalKeluarAlat = date("Y-m-d", strtotime($dataVerifikasi['waktu_keluar_alat']));
                            $jamKeluarAlat = date("H:i", strtotime($dataVerifikasi['waktu_keluar_alat']));
                            $displayPreviewDataPrint = 'block';
                            $dataPrint = base_url('/img/monitoringmesinplasma/' . $dataVerifikasi['data_print']);
                            $displayPreviewIndikatorEksternal = 'block';
                            $indikatorEksternal = base_url('/img/monitoringmesinplasma/' . $dataVerifikasi['indikator_eksternal']);
                            $displayPreviewIndikatorInternal = 'block';
                            $indikatorInternal = base_url('/img/monitoringmesinplasma/' . $dataVerifikasi['indikator_internal']);
                            $displayPreviewIndikatorBiologi = 'block';
                            $indikatorBiologi = base_url('/img/monitoringmesinplasma/' . $dataVerifikasi['indikator_biologi']);
                            $verifikator = $dataVerifikasi['id_petugas_verifikator'];
                            $hasilVerifikasi = $dataVerifikasi['hasil_verifikasi'];
                        }
                        ?>
                        <form action="<?= base_url('/monitoring-mesin-plasma/simpan-verifikasi/' . ($id ?? $dataMesinPlasma['id'])); ?>" method="POST" enctype="multipart/form-data" id="formVerifikasiMonitoringMesinPlasma">
                            <?= csrf_field(); ?>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Waktu Keluar Alat & Data Print</legend>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="tanggalKeluarAlat">Tanggal Keluar Alat</label>
                                            <input type="hidden" name="tanggalMonitoring" value="<?= $dataMesinPlasma['tanggal_monitoring']; ?>">
                                            <input type="date" class="form-control" name="tanggalKeluarAlat" id="tanggalKeluarAlat" value="<?= $tanggalKeluarAlat ?? date("Y-m-d", strtotime($dataMesinPlasma['tanggal_monitoring'])); ?>">
                                            <div class="invalid-feedback" id="invalidTanggalKeluarAlat"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="jamKeluarAlat">Jam Keluar Alat</label>
                                            <input type="time" class="form-control" name="jamKeluarAlat" id="jamKeluarAlat" value="<?= $jamKeluarAlat ?? ""; ?>">
                                            <div class="invalid-feedback" id="invalidJamKeluarAlat"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="dataPrint">Data Print</label>
                                            <div class="previewDataPrint card mb-2" style="display: <?= $displayPreviewDataPrint ?? 'none'; ?>">
                                                <img class="card-img p-1" src="<?= $dataPrint ?? ''; ?>" style="width:100%; height: 300px; object-fit: contain;" />
                                            </div>
                                            <input class=" form-control" type="file" name="dataPrint" id="dataPrint" onchange="previewFile(this,'previewDataPrint', '#namaFileDataPrint')" accept=".jpg, .jpeg, .png">
                                            <input type="hidden" id="namaFileDataPrint" name="namaFileDataPrint" value="<?= $dataPrint ?? ''; ?>">
                                            <div class="invalid-feedback" id="invalidDataPrint"></div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="content-group">
                                    <legend class="text-bold">Indikator & Verifikasi</legend>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="indikatorKimiaEksternal">Indikator Kimia Eksternal</label>
                                            <div class="previewIndikatorKimiaEksternal card mb-2" style="display: <?= $displayPreviewIndikatorEksternal ?? 'none'; ?>;">
                                                <img class="card-img p-1" src="<?= $indikatorEksternal ?? ''; ?>" style="width:100%; height: 300px; object-fit: contain;" />
                                            </div>
                                            <input class="form-control" type="file" name="indikatorKimiaEksternal" id="indikatorKimiaEksternal" onchange="previewFile(this,'previewIndikatorKimiaEksternal', '#namaFileIndikatorKimiaEksternal')" accept=".jpg, .jpeg, .png">
                                            <input type="hidden" id="namaFileIndikatorKimiaEksternal" name="namaFileIndikatorKimiaEksternal" value="<?= $indikatorEksternal ?? ''; ?>">
                                            <div class="invalid-feedback" id="invalidIndikatorKimiaEksternal"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="indikatorKimiaInternal">Indikator Kimia Internal</label>
                                            <div class="previewIndikatorKimiaInternal card mb-2" style="display: <?= $displayPreviewIndikatorInternal ?? 'none'; ?>;">
                                                <img class="card-img p-1" src="<?= $indikatorInternal ?? ''; ?>" style="width:100%; height: 300px; object-fit: contain;" />
                                            </div>
                                            <input class=" form-control" type="file" name="indikatorKimiaInternal" id="indikatorKimiaInternal" onchange="previewFile(this,'previewIndikatorKimiaInternal', '#namaFileIndikatorKimiaInternal')" accept=".jpg, .jpeg, .png">
                                            <input type="hidden" id="namaFileIndikatorKimiaInternal" name="namaFileIndikatorKimiaInternal" value="<?= $indikatorInternal ?? ''; ?>">
                                            <div class=" invalid-feedback" id="invalidIndikatorKimiaInternal"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="indikatorBiologi">Indikator Biologi</label>
                                            <div class="previewIndikatorBiologi card mb-2" style="display: <?= $displayPreviewIndikatorBiologi ?? 'none'; ?>;">
                                                <img class="card-img p-1" src="<?= $indikatorBiologi ?? ''; ?>" style="width:100%; height: 300px; object-fit: contain;" />
                                            </div>
                                            <input class=" form-control" type="file" name="indikatorBiologi" id="indikatorBiologi" onchange="previewFile(this,'previewIndikatorBiologi', '#namaFileIndikatorBiologi')" accept=".jpg, .jpeg, .png">
                                            <input type="hidden" id="namaFileIndikatorBiologi" name="namaFileIndikatorBiologi" value="<?= $indikatorBiologi ?? ''; ?>">
                                            <div class=" invalid-feedback" id="invalidIndikatorBiologi"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="verifikator">Verifikator</label>
                                            <div class="form-group mb-0" id="divVerifikator">
                                                <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Verifikator" name="verifikator" id="verifikator">
                                                    <option value=""></option>
                                                    <?php foreach ($listPegawaiCSSD as $pegawai) : ?>
                                                        <option id="verifikatorOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= ((isset($verifikator)) ? (($verifikator === $pegawai['nik']) ? 'selected' : '') : ''); ?>>
                                                            <?= $pegawai['nama']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback mt-1" id="invalidVerifikator"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex">Hasil Verifikasi</label>
                                        <div class="icheck-primary">
                                            <input type="radio" id="hasilSteril" name="hasilVerifikasi" class="form-radio-input" value="Steril" <?= isset($hasilVerifikasi) ? ($hasilVerifikasi === 'Steril' ? 'checked' : '') : ''; ?>>
                                            <label for="hasilSteril" class="form-check-label radio-label">
                                                Steril
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="radio" id="hasilTidakSteril" name="hasilVerifikasi" class="form-radio-input" value="Tidak Steril" <?= isset($hasilVerifikasi) ? (strtolower($hasilVerifikasi) === 'tidak steril' ? 'checked' : '') : ''; ?>>
                                            <label for="hasilTidakSteril" class="form-check-label radio-label">
                                                Tidak Steril
                                            </label>
                                            <div class="invalid-feedback mt-2" id="invalidHasilVerifikasi"></div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="content-group">
                                    <legend class="text-bold">Keterangan Hasil Verifikasi</legend>
                                    <dl class="row mt-2">
                                        <dt class="col-sm-3">Indikator Kimia Eksternal</dt>
                                        <dd class="col-sm-9">Biru - Tidak Steril</dd>
                                        <dd class="col-sm-9 offset-sm-3">Hijau - Steril</dd>
                                        <dt class="col-sm-3">Indikator Kimia Internal</dt>
                                        <dd class="col-sm-9">Biru - Tidak Steril</dd>
                                        <dd class="col-sm-9 offset-sm-3">Hijau - Steril</dd>
                                        <dt class="col-sm-3">Indikator Biologi</dt>
                                        <dd class="col-sm-9">Biru - Tidak Steril</dd>
                                        <dd class="col-sm-9 offset-sm-3">Hijau - Steril</dd>
                                    </dl>
                                </fieldset>
                            </div>
                        </form>
                        <div class="card-footer">
                            <?php if ($prosesUlang > 0 || $dataDistribusi > 0) : ?>
                                &nbsp;
                            <?php else : ?>
                                <div class="<?= $dataVerifikasi ? 'd-flex justify-content-between' : ''; ?>">
                                    <?php if ($dataVerifikasi) : ?>
                                        <form action="<?= base_url('/monitoring-mesin-plasma/hapus-verifikasi/' . $id); ?>" method="POST" id="formHapusVerifikasi">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger">
                                                <i class=" far fa-trash-alt mr-2"></i> Hapus
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-success float-right" form="formVerifikasiMonitoringMesinPlasma" id="btnSimpan">
                                        <i class="fas fa-save mr-2"></i> Simpan
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->
<script src="<?= base_url(); ?>/js/packingalat/monitoringmesinplasma/verifikasi_monitoringmesinplasma.js"></script>
<?= $this->endSection(); ?>