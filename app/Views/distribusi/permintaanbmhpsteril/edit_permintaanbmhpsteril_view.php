<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/permintaan-bmhp-steril'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <form action="<?= base_url('/permintaan-bmhp-steril/update/' . $dataPermintaan['id']); ?>" method="POST" id="formPermintaanBmhpSteril">
                <?= csrf_field(); ?>
                <div class="form-row">
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Permintaan BMHP Steril</h3>
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
                                        <label for="petugasMinta">Petugas Minta BMHP Steril</label>
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
                                        <div class="form-group mb-0" id="divRuangan">
                                            <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih Ruangan" name="ruangan" id="ruangan">
                                                <option></option>
                                                <?php
                                                foreach ($listDepartemen as $departemen) :
                                                    if ($departemen['dep_id'] !== 'CSSD') :
                                                ?>
                                                        <option id="departemenOption<?= $departemen['dep_id']; ?>" value="<?= $departemen['dep_id']; ?>" <?= ($dataPermintaan['id_ruangan']) == $departemen['dep_id'] ? 'selected' : ''; ?>><?= $departemen['nama']; ?></option>
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
                                    <legend class="text-bold">bmhp, jumlah, & Keterangan</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="namaBmhp">Nama BMHP</label>
                                            <div class="form-group mb-0" id="divNamaBmhp">
                                                <select class="form-control select2" style="width: 100%;" data-placeholder="Pilih BMHP" name="namaBmhp" id="namaBmhp">
                                                    <option></option>
                                                </select>
                                                <div class="invalid-feedback-custom mt-1" id="invalidBmhp"></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="harga">Harga</label>
                                            <input type="text" class="form-control" name="harga" id="harga" readonly>
                                            <div class="invalid-feedback-custom" id="invalidHarga"></div>
                                        </div>
                                        <div class="form-group col-md-3">
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
                                    <legend class="text-bold">DATA BMHP</legend>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover table-border-head mb-0" id="tabelDataBmhp" name="tabelDataBmhp">
                                                <thead class="thead-light">
                                                    <th scope="col" class="text-center align-middle" style="width: 30%;">Nama BMHP</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 15%;">Harga</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 10%;">Jumlah</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 15%;">Subtotal</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 20%;">Keterangan</th>
                                                    <th scope="col" class="text-center align-middle" style="width: 10%;">Aksi</th>
                                                </thead>
                                                <?php
                                                if ($dataDetail->getNumRows() > 0) :
                                                    $total = 0.00;
                                                ?>
                                                    <tbody>
                                                        <?php
                                                        foreach ($dataDetail->getResultArray() as $detail) :
                                                            $subtotal = $detail['harga'] * $detail['jumlah'];
                                                        ?>
                                                            <tr>
                                                                <td class="align-middle" style="width: 30%"><span data-values="<?= $detail['id_bmhp']; ?>"><?= $detail['nama_set_alat']; ?></span></td>
                                                                <td class="text-right align-middle pr-3" style="width: 15%"><?= number_format($detail['harga'], 2, ',', '.'); ?></td>
                                                                <td class="text-center align-middle" style="width: 10%"><span data-values="<?= $detail['id_monitoring_mesin_steam_detail']; ?>"><?= $detail['jumlah']; ?></span></td>
                                                                <td class="text-right align-middle pr-3" style="width: 15%"><?= number_format($subtotal, 2, ',', '.'); ?></td>
                                                                <td class="align-middle pl-3" style="width: 12%"><?= $detail['keterangan']; ?></td>
                                                                <td class="text-center align-middle" style="width: 10%">
                                                                    <button type="button" class="btn btn-danger btn-sm border-0" values="<?= $detail['id']; ?>" onclick="hapusBarisTabel(this, '<?= $detail['id']; ?>')"><i class="far fa-trash-alt"></i></button>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $total += $subtotal;
                                                        endforeach;
                                                        ?>
                                                    </tbody>
                                                    <tfoot class="tfoot-light">
                                                        <th scope="col" colspan="3" class="text-center align-middle th-custom">Total</th>
                                                        <th class="text-right pr-3 total border-right-0"><strong><?= number_format($total, 2, ',', '.'); ?></strong></th>
                                                        <th colspan="2" class="border-left-0"></th>
                                                    </tfoot>
                                                <?php else : ?>
                                                    <tbody>
                                                        <tr class="data-kosong">
                                                            <td colspan="6" class="text-center align-middle">DATA TIDAK ADA</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="tfoot-light">
                                                        <th scope="col" colspan="3" class="text-center align-middle th-custom">Total</th>
                                                        <th class="text-right pr-3 total border-right-0"><strong>0,00</strong></th>
                                                        <th colspan="2" class="border-left-0"></th>
                                                    </tfoot>
                                                <?php endif; ?>
                                            </table>
                                            <div class="invalid-feedback" id="invalidTabelDataBmhp"></div>
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
    let url = "<?= base_url('/permintaan-bmhp-steril'); ?>";
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
</script>
<script src="<?= base_url(); ?>/js/distribusi/permintaanbmhpsteril/proses_tambah_edit_permintaanbmhpsteril.js"></script>
<!-- /.content -->
<?= $this->endSection(); ?>