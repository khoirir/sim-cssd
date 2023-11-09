<div class="modal fade" id="modalGambarLarutan" tabindex="-1">
    <div class="modal-dialog modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-top: #17A2B8 3px solid;">
            <div class="modal-header">
                <h6 class="modal-title">Gambar Larutan / <?= $noReferensi; ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mt-2" style="width: 100%;">
                    <img class="card-img p-1" src="<?= base_url('img/ujilarutan/' . $larutan); ?>" style="width:100%; height: 400px; object-fit: contain;" />
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>