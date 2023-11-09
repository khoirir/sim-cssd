$('input[name="radioHasilWarna"]').change(function () {
    let keterangan = $('#keterangan');

    if ($(this).val() === 'pink') {
        keterangan.val('Failed');
    } else if ($(this).val() === 'ungu') {
        keterangan.val('Passed');
    }
});

$('#formUjiSealerPouchs').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#btnSimpan').prop('disabled', true);
            $('#btnSimpan').html(`<i class="fa fa-spin fa-spinner mr-2"></i> Simpan`);
        },
        complete: function () {
            $('#btnSimpan').prop('disabled', false);
            $('#btnSimpan').html(`<i class="fas fa-save mr-2"></i> Simpan`);
        },
        success: function (res) {
            if (!res.sukses) {
                $(".invalid-feedback").html("");
                $('.is-invalid').removeClass('is-invalid');
                if (res.pesan.invalidTanggalUji) {
                    $('#tanggalUji').addClass('is-invalid');
                    $('#invalidTanggalUji').html(res.pesan.invalidTanggalUji);
                }
                if (res.pesan.invalidPetugas) {
                    $('#divPetugas').addClass('is-invalid');
                    $('#invalidPetugas').html(res.pesan.invalidPetugas);
                }
                if (res.pesan.invalidNamaBuktiUjiSealer) {
                    $('#uploadBuktiUjiSealer').addClass('is-invalid');
                    $('#invalidUploadBuktiUjiSealer').html(res.pesan.invalidNamaBuktiUjiSealer);
                }
                if (res.pesan.invalidUploadBuktiUjiSealer) {
                    $('#uploadBuktiUjiSealer').addClass('is-invalid');
                    $('#invalidUploadBuktiUjiSealer').html(res.pesan.invalidUploadBuktiUjiSealer);
                }
                if (res.pesan.invalidCheckboxSuhuMesin) {
                    $('#checkboxSuhuMesin').addClass('is-invalid');
                    $('#invalidCheckboxSuhuMesin').html(res.pesan.invalidCheckboxSuhuMesin);
                }
                if (res.pesan.invalidCheckboxSpeedSedang) {
                    $('#checkboxSpeedSedang').addClass('is-invalid');
                    $('#invalidCheckboxSpeedSedang').html(res.pesan.invalidCheckboxSpeedSedang);
                }
                if (res.pesan.invalidHasilUji) {
                    $('.form-radio-input').addClass('is-invalid');
                    $('#invalidHasilUji').html(res.pesan.invalidHasilUji);
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
        error: function (xhr, textStatus, thrownError) {
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