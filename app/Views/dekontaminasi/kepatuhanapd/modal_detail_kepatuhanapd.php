<div class="modal fade" id="modalDetailKepatuhanAPD" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Detail Kepatuhan APD / <?= generateNoReferensi($dataKepatuhanApd['created_at'], $dataKepatuhanApd['id']); ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <strong><i class="far fa-calendar mr-1"></i> Tanggal</strong>
                        <p class="text-muted mb-0">
                            <?= date('d-m-Y', strtotime($dataKepatuhanApd['tanggal_cek'])); ?>
                        </p>
                        <hr class="mt-0">
                    </div>
                    <div class="col-lg-6">
                        <strong><i class="fa-solid fa-business-time mr-1"></i></i> Shift</strong>
                        <p class="text-muted mb-0">
                            <?= ucfirst($dataKepatuhanApd['shift']); ?>
                        </p>
                        <hr class="mt-0">
                    </div>
                </div>
                <strong><i class="fa-solid fa-head-side-mask mr-1"></i></i></i> Data Kepatuhan APD</strong>
                <div class="table-responsive mt-2">
                    <table class="table table-sm table-border-head mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" rowspan="2" class="text-center align-middle">Petugas</th>
                                <th scope="col" colspan="6" class="text-center align-middle">APD</th>
                                <th scope="col" rowspan="2" class="text-center align-middle">Keterangan</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center align-middle">Handschoen</th>
                                <th scope="col" class="text-center align-middle">Masker</th>
                                <th scope="col" class="text-center align-middle">Apron</th>
                                <th scope="col" class="text-center align-middle">Goggle</th>
                                <th scope="col" class="text-center align-middle">Sepatu Boot</th>
                                <th scope="col" class="text-center align-middle">Penutup Kepala</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataDetail as $data) :
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
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                    <div class="invalid-feedback" id="invalidTabelDataApd"></div>
                </div>
            </div>
            <div class="modal-footer">
                &nbsp;
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
</div>