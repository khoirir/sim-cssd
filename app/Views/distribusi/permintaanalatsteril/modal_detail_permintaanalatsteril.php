<div class="modal fade" id="modalDetailPermintaanAlatSteril" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Detail Permintaan Alat Steril / <?= generateNoReferensi($dataPermintaan['created_at'], $dataPermintaan['id']); ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="far fa-calendar mr-1"></i> Tanggal Permintaan</strong>
                        <p class="text-muted mb-0">
                            <?= date("d-m-Y", strtotime($dataPermintaan['tanggal_minta'])); ?>
                        </p>
                        <hr class="mt-0">
                        <strong><i class="far fa-clock mr-1"></i> Jam Permintaan</strong>
                        <p class="text-muted mb-0">
                            <?= date("H:i", strtotime($dataPermintaan['tanggal_minta'])); ?>
                        </p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-user-gear mr-1"></i> Petugas CSSD</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPermintaan['petugas_cssd']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-user-nurse mr-1"></i> Petugas Minta Alat Steril</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPermintaan['petugas_minta']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-house-medical mr-1"></i> Ruangan</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPermintaan['ruangan']; ?></p>
                        <hr class="mt-0">
                    </div>
                    <div class="col-md-8">
                        <strong><i class="fa-solid fa-kit-medical mr-1"></i> Data Alat</strong>
                        <div class="table-responsive mt-1">
                            <table class="table table-sm table-border-head mb-0">
                                <thead class="thead-light">
                                    <th scope="col" rowspan="3" class="text-center align-middle">Nama Alat</th>
                                    <th scope="col" rowspan="3" class="text-center align-middle">Jumlah</th>
                                    <th scope="col" rowspan="3" class="text-center align-middle">Keterangan</th>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($dataDetail as $data) :
                                    ?>
                                        <tr>
                                            <td class="align-middle"><?= $data['nama_set_alat']; ?></td>
                                            <td class="text-center align-middle"><?= $data['jumlah']; ?></td>
                                            <td class="align-middle pl-3"><?= $data['keterangan']; ?></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>