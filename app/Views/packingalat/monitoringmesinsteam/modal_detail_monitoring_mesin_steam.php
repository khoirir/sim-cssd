<div class="modal fade" id="modalDetailMonitoringMesinSteam">
    <?php
    if ($dataVerifikasi) {
        $tanggalKeluarAlat = date("d-m-Y H:i", strtotime($dataVerifikasi['waktu_keluar_alat']));
        $jamKeluarAlat = date("H:i", strtotime($dataVerifikasi['waktu_keluar_alat']));
        $dataPrint = '/img/monitoringmesinsteam/' . $dataVerifikasi['data_print'];
        $indikatorEksternal = '/img/monitoringmesinsteam/' . $dataVerifikasi['indikator_eksternal'];
        $indikatorInternal = '/img/monitoringmesinsteam/' . $dataVerifikasi['indikator_internal'];
        $indikatorBiologi = '/img/monitoringmesinsteam/' . $dataVerifikasi['indikator_biologi'];
        $verifikator = $dataVerifikasi['verifikator'];
        $hasilVerifikasi = $dataVerifikasi['hasil_verifikasi'];
        $warnaHasil = $hasilVerifikasi === 'Failed' ? 'danger' : 'success';
    }
    ?>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Detail Monitoring Mesin Steam /
                    <?php
                    echo generateNoReferensi($dataMesinSteam['created_at'], $dataMesinSteam['id']);
                    if ($dataMesinSteam['proses_ulang']) {
                        echo " / Proses Ulang dari " . generateNoReferensi($dataMesinSteam['created_proses_ulang'], $dataMesinSteam['proses_ulang']);
                    }
                    ?>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <strong><i class="far fa-calendar-alt mr-1"></i> Waktu Masuk Alat</strong>
                        <p class="text-muted mt-1 mb-0"><?= date("d-m-Y H:i", strtotime($dataMesinSteam['tanggal_monitoring'])); ?></p>
                        <hr class="mt-0">
                        <strong><i class="far fa-calendar-check mr-1"></i> Waktu Keluar Alat</strong>
                        <p class="text-muted mt-1 mb-0"><?= $tanggalKeluarAlat ?? '-'; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-gears mr-1"></i> Mesin</strong>
                        <p class="text-muted mt-1 mb-0"><?= $dataMesinSteam['mesin']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-arrows-spin mr-1"></i> Siklus</strong>
                        <p class="text-muted mt-1 mb-0"><?= $dataMesinSteam['siklus']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-user-gear mr-1"></i> Operator <?= ucfirst($dataMesinSteam['shift']); ?></strong>
                        <p class="text-muted mt-1 mb-0">
                            <?php foreach ($operator as $op) :
                                echo $op['nama'] . "<br>";
                            endforeach; ?>
                        </p>
                        <hr class="mt-0">
                    </div>
                    <div class="col-lg-8">
                        <strong><i class="fas fa-kit-medical mr-1"></i> Data Alat</strong>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-border-head">
                                <thead class=" thead-light">
                                    <th scope="col" class="text-center align-middle" style="width: 5%">No.</th>
                                    <th scope="col" class="text-center align-middle" style="width: 25%">Ruangan</th>
                                    <th scope="col" class="text-center align-middle" style="width: 25%">Nama Alat</th>
                                    <th scope="col" class="text-center align-middle" style="width: 10%">Jumlah</th>
                                    <th scope="col" class="text-center align-middle" style="width: 35%">Data Print</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $jumlahRowspan = $detailAlat->getNumRows();
                                    foreach ($detailAlat->getResultArray() as $detail) :
                                    ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $no . "."; ?></td>
                                            <td class="align-middle"><?= $detail['ruangan']; ?></td>
                                            <td class="align-middle"><?= $detail['nama_set_alat']; ?></td>
                                            <td class="text-center align-middle"><?= $detail['jumlah']; ?></td>
                                            <?php
                                            if ($no === 1) :
                                            ?>
                                                <td class="align-middle text-center" rowspan="<?= $jumlahRowspan; ?>" style="border-left: 1px solid rgba(0, 0, 0, 0.2)">
                                                    <img class="card-img p-1" src="<?= base_url($dataPrint ?? '/img/placeholder.svg'); ?>" style="width:100%; height: 250px; object-fit: contain;" />
                                                </td>
                                            <?php
                                            endif;
                                            ?>
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
                <hr>
                <div class="row">
                    <div class="col-lg-4">
                        <strong><i class="fas fa-flask mr-1"></i> Indikator Kimia Eksternal</strong>
                        <div class="card mt-2">
                            <img class="card-img p-1" src="<?= base_url($indikatorEksternal ?? '/img/placeholder.svg'); ?>" style="width:100%; height: 200px; object-fit: contain;" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <strong><i class="fas fa-vial mr-1"></i> Indikator Kimia Internal</strong>
                        <div class="card mt-2">
                            <img class="card-img p-1" src="<?= base_url($indikatorInternal ?? '/img/placeholder.svg'); ?>" style="width:100%; height: 200px; object-fit: contain;" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <strong><i class="fas fa-dna mr-1"></i> Indikator Biologi</strong>
                        <div class="card mt-2">
                            <img class="card-img p-1" src="<?= base_url($indikatorBiologi ?? '/img/placeholder.svg'); ?>" style="width:100%; height: 200px; object-fit: contain;" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <strong><i class="fas fa-check-circle mr-1"></i> Hasil Verifikasi</strong>
                        <p class="text-muted mt-1 mb-0"><span class="badge badge-<?= $warnaHasil ?? 'secondary'; ?>"><?= strtoupper($hasilVerifikasi ?? '-'); ?></span></p>
                        <hr class="mt-0">
                    </div>
                    <div class="col-lg-8">
                        <strong><i class="fas fa-user-check mr-1"></i> Verifikator</strong>
                        <p class="text-muted mt-1 mb-0"><?= $verifikator ?? '-'; ?></p>
                        <hr class="mt-0">
                    </div>
                </div>
                <strong><i class="fa-solid fa-circle-info mr-1"></i></i> Keterangan</strong>
                <dl class="row mt-2">
                    <dt class="col-sm-3">Passed</dt>
                    <dd class="col-sm-9">Proses Complete dengan uji (indikator) dengan atau tanpa instrumen dalam mesin</dd>
                    <dt class="col-sm-3">Steril</dt>
                    <dd class="col-sm-9">Proses Complete dengan uji (indikator) dengan instrumen dalam mesin</dd>
                    <dt class="col-sm-3">Failed</dt>
                    <dd class="col-sm-9">Proses FAILED/EROR/ALARM (TIDAK STERIL)</dd>
                    <dt class="col-sm-3">Indikator Kimia Eksternal</dt>
                    <dd class="col-sm-9">Biru - Tidak Steril</dd>
                    <dd class="col-sm-9 offset-sm-3">Hitam - Steril</dd>
                    <dt class="col-sm-3">Indikator Kimia Internal</dt>
                    <dd class="col-sm-9">Biru - Tidak Steril</dd>
                    <dd class="col-sm-9 offset-sm-3">Pink - Steril</dd>
                    <dt class="col-sm-3">Indikator Biologi</dt>
                    <dd class="col-sm-9">Kuning - Tidak Steril</dd>
                    <dd class="col-sm-9 offset-sm-3">Hitam - Steril</dd>
                </dl>
            </div>
            <div class="modal-footer justify-content-lg-start">
                &nbsp;
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>