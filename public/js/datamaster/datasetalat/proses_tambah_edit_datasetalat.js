$('#formDataSetAlat').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "JSON",
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
                if (res.pesan.invalidNamaSetAlat) {
                    $('#namaSetAlat').addClass('is-invalid');
                    $('#invalidNamaSetAlat').html(res.pesan.invalidNamaSetAlat);
                }
                if (res.pesan.invalidJenisSetAlat) {
                    $('#divJenisSetAlat').addClass('is-invalid');
                    $('#invalidJenisSetAlat').html(res.pesan.invalidJenisSetAlat);
                }
                if (res.pesan.invalidSatuanSetAlat) {
                    $('#divSatuanSetAlat').addClass('is-invalid');
                    $('#invalidSatuanSetAlat').html(res.pesan.invalidSatuanSetAlat);
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
            console.log(thrownError);
            swalWithBootstrapButtons.fire(
                "Gagal",
                "Terjadi Masalah,<br>Silahkan Hubungi Tim IT",
                "error"
            );
        }
    });
});