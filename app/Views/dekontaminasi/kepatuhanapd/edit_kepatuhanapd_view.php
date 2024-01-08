<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/kepatuhan-apd'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <form action="<?= base_url('/kepatuhan-apd/update/' . $dataKepatuhanApdBerdasarkanId['id']); ?>" method="POST" id="formApd">
                <?= csrf_field(); ?>
                <div class="form-row">
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Kepatuhan APD / <?= generateNoReferensi($dataKepatuhanApdBerdasarkanId['created_at'], $dataKepatuhanApdBerdasarkanId['id']); ?></h3>
                            </div>
                            <div class="card-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">SHIFT, PETUGAS, & APD</legend>
                                    <div class="row justify-content-between">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggalCek">Tanggal</label>
                                                <input type="date" class="form-control" name="tanggalCek" id="tanggalCek" value="<?= $dataKepatuhanApdBerdasarkanId['tanggal_cek']; ?>">
                                                <div class="invalid-feedback" id="invalidTanggalCek"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="d-flex mb-3">Shift</label>
                                                <div class="icheck-primary d-inline mr-3">
                                                    <input type="radio" id="radioShiftPagi" value="pagi" <?= ($dataKepatuhanApdBerdasarkanId['shift'] == 'pagi') ? 'checked' : ''; ?> name="shift" class="form-radio-input">
                                                    <label for="radioShiftPagi" class="form-check-label radio-label">
                                                        Pagi
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="radioShiftSore" value="sore" <?= ($dataKepatuhanApdBerdasarkanId['shift'] == 'sore') ? 'checked' : ''; ?> name="shift" class="form-radio-input">
                                                    <label for="radioShiftSore" class="form-check-label radio-label">
                                                        Sore
                                                    </label>
                                                    <div class="invalid-feedback mt-2" id="invalidShift"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugas">Petugas</label>
                                        <div class="form-group mb-0" id="divPetugas">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Petugas" name="petugas" id="petugas">
                                                <option value=""></option>
                                                <?php foreach ($listPegawaiCSSD as $pegawai) : ?>
                                                    <option id="pegawaiOption<?= $pegawai['nik']; ?>" value="<?= $pegawai['nik']; ?>" <?= old('petugas') == $pegawai['nik'] ? 'selected' : ''; ?>><?= $pegawai['nama']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidPetugas"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-flex">APD</label>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxAll" name="checkboxAll" value="semua" class="form-check-input">
                                            <label for="checkboxAll" class="form-check-label">
                                                <b>Pilih Semua</b>
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxHandSchoen" name="checkboxHandSchoen" value="handschoen" class="form-check-input">
                                            <label for="checkboxHandSchoen" class="form-check-label">
                                                Handschoen
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxMasker" name="checkboxMasker" value="masker" class="form-check-input">
                                            <label for="checkboxMasker" class="form-check-label">
                                                Masker
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxApron" name="checkboxApron" value="apron" class="form-check-input">
                                            <label for="checkboxApron" class="form-check-label">
                                                Apron
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxGoggle" name="checkboxGoggle" value="goggle" class="form-check-input">
                                            <label for="checkboxGoggle" class="form-check-label">
                                                Goggle
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxSepatuBoot" name="checkboxSepatuBoot" value="sepatu_boot" class="form-check-input">
                                            <label for="checkboxSepatuBoot" class="form-check-label">
                                                Sepatu Boot
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="checkboxPenutupKepala" name="checkboxPenutupKepala" value="penutup_kepala" class="form-check-input">
                                            <label for="checkboxPenutupKepala" class="form-check-label">
                                                Penutup Kepala
                                            </label>
                                        </div>
                                        <div class="invalid-feedback-custom mt-1" id="invalidApd"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" rows="2" name="keterangan" id="keterangan"></textarea>
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
                                    <legend class="text-bold">DATA KEPATUHAN APD</legend>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover table-border-head mb-0" id="tabelDataApd" name="tabelDataApd">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 24%;">Petugas</th>
                                                        <th scope="col" colspan="6" class="text-center align-middle">APD</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 22%;">Keterangan</th>
                                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 8%;">Aksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center align-middle" style="width: 7%;">Handschoen</th>
                                                        <th scope="col" class="text-center align-middle" style="width: 7%;">Masker</th>
                                                        <th scope="col" class="text-center align-middle" style="width: 7%;">Apron</th>
                                                        <th scope="col" class="text-center align-middle" style="width: 7%;">Goggle</th>
                                                        <th scope="col" class="text-center align-middle" style="width: 8%;">Sepatu Boot</th>
                                                        <th scope="col" class="text-center align-middle" style="width: 10%;">Penutup Kepala</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($dataKepatuhanApdDetailBerdasarkanIdMaster) :
                                                        foreach ($dataKepatuhanApdDetailBerdasarkanIdMaster->getResultArray() as $data) :
                                                    ?>
                                                            <tr>
                                                                <td class="align-middle"><span data-values="<?= $data['id_petugas'] ?? ''; ?>"><?= $data['nama']; ?></span></td>
                                                                <td class="text-center align-middle"><?= $data['handschoen'] ? '<i class="fas fa-check" values="' . $data['handschoen'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['masker'] ? '<i class="fas fa-check" values="' . $data['masker'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['apron'] ? '<i class="fas fa-check" values="' . $data['apron'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['goggle'] ? '<i class="fas fa-check" values="' . $data['goggle'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['sepatu_boot'] ? '<i class="fas fa-check" values="' . $data['sepatu_boot'] . '"></i>' : ''; ?></td>
                                                                <td class="text-center align-middle"><?= $data['penutup_kepala'] ? '<i class="fas fa-check" values="' . $data['penutup_kepala'] . '"></i>' : ''; ?></td>
                                                                <td class="align-middle"><?= $data['keterangan'] ?? ''; ?></td>
                                                                <td class="text-center align-middle">
                                                                    <button type="button" class="btn btn-danger btn-sm border-0" values="<?= $data['id']; ?>" onclick="hapusBarisTabel('tabelDataApd', this, '<?= $data['id'] ?? ''; ?>')"><i class="far fa-trash-alt"></i></button>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </tbody>
                                                <?php
                                                if (!$dataKepatuhanApdDetailBerdasarkanIdMaster) :
                                                ?>
                                                    <tfoot>
                                                        <td colspan="9" class="text-center align-middle data-kosong">DATA TIDAK ADA</td>
                                                    </tfoot>
                                                <?php
                                                endif;
                                                ?>
                                            </table>
                                            <div class="invalid-feedback" id="invalidTabelDataApd"></div>
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
<script src="<?= base_url(); ?>public/js/dekontaminasi/kepatuhanapd/proses_tambah_edit_kepatuhanapd.js"></script>
<?= $this->endSection(); ?>