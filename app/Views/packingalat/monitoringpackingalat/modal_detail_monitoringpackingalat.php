<div class="modal fade" id="modalDetailMonitoringPackingAlat" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Detail Monitoring Packing Alat / <?= generateNoReferensi($dataPackingAlat['created_at'], $dataPackingAlat['id']); ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <strong><i class="far fa-calendar mr-1"></i> Tanggal Packing</strong>
                <p class="text-muted mb-0">
                    <?= date('d-m-Y', strtotime($dataPackingAlat['tanggal_packing'])); ?>
                </p>
                <hr class="mt-0">
                <strong><i class="fas fa-kit-medical mr-1"></i> Data Alat</strong>
                <div class="table-responsive mt-2">
                    <table class="table table-sm table-border-head mb-0" id="tabelDataAlat" name="tabelDataAlat">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" rowspan="2" class="text-center align-middle" style="width: 30%;">Nama Alat</th>
                                <th scope="col" colspan="3" class="text-center" style="width: 22%;">Uji Visual</th>
                                <th scope="col" rowspan="2" class="text-center align-middle" style="width: 8%;">Indikator</th>
                                <th scope="col" rowspan="2" class="text-center align-middle" style="width: 30%;">Petugas</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center align-middle">Bersih</th>
                                <th scope="col" class="text-center align-middle">Tajam</th>
                                <th scope="col" class="text-center align-middle">Layak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataDetail as $data) :
                            ?>
                                <tr>
                                    <td class="align-middle"><span data-values="<?= $data['id_alat']; ?>"><?= $data['nama_set_alat']; ?></span></td>
                                    <td class="text-center align-middle"><?= $data['bersih'] ? '<i class="fas fa-check" values="' . $data['bersih'] . '"></i>' : ''; ?></td>
                                    <td class="text-center align-middle"><?= $data['tajam'] ? '<i class="fas fa-check" values="' . $data['tajam'] . '"></i>' : ''; ?></td>
                                    <td class="text-center align-middle"><?= $data['layak'] ? '<i class="fas fa-check" values="' . $data['layak'] . '"></i>' : ''; ?></td>
                                    <td class="text-center align-middle"><?= $data['indikator'] ? '<i class="fas fa-check" values="' . $data['indikator'] . '"></i>' : ''; ?></td>
                                    <td class="align-middle"><span data-values="<?= $data['id_petugas']; ?>"><?= $data['nama']; ?></span></td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
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