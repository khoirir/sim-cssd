<div class="modal fade" id="modalDetailPermintaanBmhpSteril" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Detail Permintaan BMHP/Kasa Steril / <?= generateNoReferensi($dataPermintaan['created_at'], $dataPermintaan['id']); ?></h6>
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
                        <strong><i class="fas fa-user-nurse mr-1"></i> Petugas Minta BMHP Steril</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPermintaan['petugas_minta']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-house-medical mr-1"></i> Ruangan</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPermintaan['ruangan']; ?></p>
                        <hr class="mt-0">
                    </div>
                    <div class="col-md-8">
                        <strong><i class="fa-solid fa-kit-medical mr-1"></i> Data BMHP</strong>
                        <div class="table-responsive mt-1">
                            <table class="table table-sm table-border-head mb-0" id="tabelDataBmhp" name="tabelDataBmhp">
                                <thead class="thead-light">
                                    <th scope="col" class="text-center align-middle" style="width: 30%;">Nama BMHP</th>
                                    <th scope="col" class="text-center align-middle" style="width: 15%;">Harga</th>
                                    <th scope="col" class="text-center align-middle" style="width: 10%;">Jumlah</th>
                                    <th scope="col" class="text-center align-middle" style="width: 15%;">Subtotal</th>
                                    <th scope="col" class="text-center align-middle" style="width: 20%;">Keterangan</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0.00;
                                    foreach ($dataDetail as $data) :
                                        $subtotal = $data['harga'] * $data['jumlah'];
                                    ?>
                                        <tr>
                                            <td class="align-middle"><?= $data['nama_set_alat']; ?></td>
                                            <td class="align-middle text-right pr-3"><?= number_format($data['harga'], 2, ',', '.'); ?></td>
                                            <td class="text-center align-middle"><?= $data['jumlah']; ?></td>
                                            <td class="align-middle text-right pr-3"><?= number_format($subtotal, 2, ',', '.'); ?></td>
                                            <td class="align-middle pl-3"><?= $data['keterangan']; ?></td>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>