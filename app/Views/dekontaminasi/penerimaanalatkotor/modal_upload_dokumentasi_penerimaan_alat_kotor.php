<div class="modal fade" id="modalUploadDokumentasi">
    <div class="modal-dialog modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Upload Dokumentasi</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('/penerimaan-alat-kotor/upload'); ?>" method="POST" id="formUploadDokumentasi">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="idPenerimaanAlatKotor" id="idPenerimaanAlatKotor" value="<?= $id; ?>">
                        <div class="previewUploadDokumentasi card mb-2" style="display: none;"></div>
                        <input class="form-control" type="file" name="uploadDokumentasi" id="uploadDokumentasi" onchange="previewFile(this, 'previewUploadDokumentasi', '#previewName')" accept=".jpg, .jpeg, .png">
                        <div class="invalid-feedback" id="invalidUploadDokumentasi"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success float-right" id="btnUpload">
                        <i class="fas fa-upload mr-2"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#formUploadDokumentasi').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData($(this)[0]);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btnUpload').prop('disabled', true);
                $('#btnUpload').html(`<i class="fa fa-spin fa-spinner mr-2"></i> Upload`);
            },
            complete: function() {
                $('#btnUpload').prop('disabled', false);
                $('#btnUpload').html(`<i class="fas fa-upload mr-2"></i> Upload`);
            },
            success: function(res) {
                if (!res.sukses) {
                    $(".invalid-feedback").html("");
                    $('.is-invalid').removeClass('is-invalid');
                    if (res.pesan.invalidUploadDokumentasi) {
                        $('#uploadDokumentasi').addClass('is-invalid');
                        $('#invalidUploadDokumentasi').html(res.pesan.invalidUploadDokumentasi);
                    }
                    if (res.pesan.errorSimpan) {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "error"
                        );
                    }
                }
                if (res.sukses) {
                    swalWithBootstrapButtons.fire(
                        res.pesan.judul,
                        res.pesan.teks,
                        "success"
                    ).then(() => location.reload());
                }
            },
            error: function(xhr, textStatus, thrownError) {
                console.log(xhr.status + " => " + textStatus);
                console.log(thrownError)
                swalWithBootstrapButtons.fire(
                    "Gagal",
                    "Terjadi Masalah,<br>Silahkan Hubungi Tim IT",
                    "error"
                );
            }
        });
    });
</script>