$('#formHasilTindakan').on('submit', function (e) {
    e.preventDefault();
    let hasilTindakan = $('#hasilTindakan').val();
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
            hasilTindakan: hasilTindakan
        },
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
                $("#invalidTindakan").html("");
                $('#hasilTindakan').removeClass('is-invalid');
                if (res.pesan.invalidHasilTindakan) {
                    $('#hasilTindakan').addClass('is-invalid');
                    $('#invalidTindakan').html(res.pesan.invalidHasilTindakan);
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

$('#formHapusTindakan').on('submit', function (e) {
    e.preventDefault();
    swalDelete.fire({
        title: 'Hasil & Tindakan',
        text: "Apakah yakin hapus?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "YAKIN",
        cancelButtonText: "BATAL",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let data = $(this).serialize();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: data,
                dataType: "JSON",
                success: function (res) {
                    if (res.sukses) {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "success"
                        ).then(() => location.reload());
                    } else {
                        console.log(res.pesan.errorHapus);
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "error"
                        );
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
        }
    });
});