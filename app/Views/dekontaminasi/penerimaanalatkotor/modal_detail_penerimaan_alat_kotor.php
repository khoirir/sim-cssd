<div class="modal fade" id="modalDetailPenerimaanAlatKotor" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Detail Penerimaan Alat Kotor / <?= generateNoReferensi($dataPenerimaan['created_at'], $dataPenerimaan['id']); ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="far fa-calendar mr-1"></i> Tanggal Penerimaan</strong>
                        <p class="text-muted mb-0">
                            <?= date("d-m-Y", strtotime($dataPenerimaan['tanggal_penerimaan'])); ?>
                        </p>
                        <hr class="mt-0">
                        <strong><i class="far fa-clock mr-1"></i> Jam Penerimaan</strong>
                        <p class="text-muted mb-0">
                            <?= date("H:i", strtotime($dataPenerimaan['tanggal_penerimaan'])); ?>
                        </p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-user-gear mr-1"></i> Petugas CSSD</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPenerimaan['petugas_cssd']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-user-nurse mr-1"></i> Petugas Penyetor</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPenerimaan['petugas_penyetor']; ?></p>
                        <hr class="mt-0">
                        <strong><i class="fas fa-house-medical mr-1"></i> Ruangan</strong>
                        <p class="text-muted mb-0 mt-1"><?= $dataPenerimaan['ruangan']; ?></p>
                        <hr class="mt-0">
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-file mr-1"></i> Dokumentasi</strong>
                        <div class="mt-1">
                            <div class="card mt-2" style="width: 100%;">
                                <?php
                                $gambarDokumentasi = '/img/placeholder.svg';
                                $dokumentasi = $dataPenerimaan['upload_dokumentasi'];
                                if ($dokumentasi) {
                                    $gambarDokumentasi = '/img/penerimaanalatkotor/' . $dokumentasi;
                                }
                                ?>
                                <img class="card-img p-1" src="<?= base_url($gambarDokumentasi); ?>" style="width:100%; height: 260px; object-fit: contain;" />
                                <?php if ($dokumentasi) : ?>
                                    <div style="position: absolute; bottom: 3px; right: 3px;">
                                        <form action="<?= base_url('/penerimaan-alat-kotor/hapus-dokumentasi/' . $dataPenerimaan['id']); ?>" method="POST" id="formHapusDokumentasi">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="ml-1 btn btn-danger btn-sm border-0" data-popup='tooltip' title='Hapus Dokumentasi'>
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong><i class="fa-solid fa-kit-medical mr-1"></i> Data Set/Alat</strong>
                        <div class="table-responsive mt-1">
                            <table class="table table-sm table-border-head mb-0" id="tabelDataSetAlat" name="tabelDataSetAlat">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" rowspan="3" class="text-center align-middle" style="width: 20%;">Nama Set/Alat</th>
                                        <th scope="col" colspan="5" class="text-center" style="width: 35%;">Proses</th>
                                        <th scope="col" rowspan="3" class="text-center align-middle" style="width: 10%;">Mesin</th>
                                        <th scope="col" colspan="3" class="text-center align-middle" style="width: 35%;">Jumlah</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" colspan="2" class="text-center">Manual</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Ultrasonic</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Bilas</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Washer</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Terima</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Diproses</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Distribusi</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="text-center">Enzym</th>
                                        <th scope="col" class="text-center">DTT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($dataDetail->getResultArray() as $data) :
                                        $diproses = $data['status_proses'] ? ((int)$data['jumlah'] - (int)$data['sisa']) : '<i class="fa-solid fa-minus"></i>';

                                        $distribusi = $data['status_distribusi'] ? ((int)$data['jumlah'] - (int)$data['sisa_distribusi']) : '<i class="fa-solid fa-minus"></i>';
                                    ?>
                                        <tr>
                                            <td class="align-middle"><?= $data['nama_set_alat']; ?></td>
                                            <td class="text-center align-middle"><?= $data['enzym'] ? '<i class="fas fa-check" values="' . $data['enzym'] . '"></i>' : ''; ?></td>
                                            <td class="text-center align-middle"><?= $data['dtt'] ? '<i class="fas fa-check" values="' . $data['dtt'] . '"></i>' : ''; ?></td>
                                            <td class="text-center align-middle"><?= $data['ultrasonic'] ? '<i class="fas fa-check" values="' . $data['ultrasonic'] . '"></i>' : ''; ?></td>
                                            <td class="text-center align-middle"><?= $data['bilas'] ? '<i class="fas fa-check" values="' . $data['bilas'] . '"></i>' : ''; ?></td>
                                            <td class="text-center align-middle"><?= $data['washer'] ? '<i class="fas fa-check" values="' . $data['washer'] . '"></i>' : ''; ?></td>
                                            <td class="align-middle pl-3"><?= $data['pemilihan_mesin']; ?></td>
                                            <td class="text-center align-middle"><?= $data['jumlah']; ?></td>
                                            <td class="align-middle text-center"><?= $diproses; ?></td>
                                            <td class="align-middle text-center"><?= $distribusi; ?></td>
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
<script>
    $('#formHapusDokumentasi').on('submit', function(e) {
        e.preventDefault();
        swalDelete.fire({
            title: 'Hapus Dokumentasi',
            text: "Apakah yakin hapus?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: "YAKIN",
            cancelButtonText: "BATAL",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let data = $(this).serialize();
                console.log($(this).attr('action'));
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        console.log(res);
                        if (res.sukses) {
                            swalWithBootstrapButtons.fire(
                                res.pesan.judul,
                                res.pesan.teks,
                                "success"
                            ).then(() => location.reload());
                        } else {
                            swalWithBootstrapButtons.fire(
                                res.pesan.judul,
                                res.pesan.teks,
                                "error"
                            );
                        }
                    },
                    error: function(xhr, textStatus, thrownError) {
                        console.log(xhr.status + " => " + textStatus);
                        console.log(thrownError);
                        swalWithBootstrapButtons.fire(
                            "Gagal",
                            "Terjadi Masalah,<br>Silahkan Hubungi Tim IT",
                            "error"
                        );
                    }
                });
            }
        });
    });
</script>