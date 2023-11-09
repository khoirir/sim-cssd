$(function () {
    $('#hasilPassed').change(function () {
        if ($(this).prop('checked')) {
            $('#hasilSteril').prop('disabled', false);
        }
    });

    $('#hasilFailed').change(function () {
        if ($(this).prop('checked')) {
            $('#hasilSteril').prop('disabled', true).prop('checked', false);
        }
    });
});

$('#formVerifikasiMonitoringMesinPlasma').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        processData: false,
        contentType: false,
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
                if (res.pesan.invalidTanggalKeluarAlat) {
                    $('#tanggalKeluarAlat').addClass('is-invalid');
                    $('#tanggalKeluarAlat').focus();
                    $('#invalidTanggalKeluarAlat').html(res.pesan.invalidTanggalKeluarAlat);
                }
                if (res.pesan.invalidJamKeluarAlat) {
                    $('#jamKeluarAlat').addClass('is-invalid');
                    $('#jamKeluarAlat').focus();
                    $('#invalidJamKeluarAlat').html(res.pesan.invalidJamKeluarAlat);
                }
                if (res.pesan.invalidJamKeluar) {
                    $('#jamKeluarAlat').addClass('is-invalid');
                    $('#jamKeluarAlat').focus();
                    $('#invalidJamKeluarAlat').html(res.pesan.invalidJamKeluar);
                }
                if (res.pesan.invalidNamaFileDataPrint) {
                    $('#dataPrint').addClass('is-invalid');
                    $('#invalidDataPrint').html(res.pesan.invalidNamaFileDataPrint);
                }
                if (res.pesan.invalidDataPrint) {
                    $('#dataPrint').addClass('is-invalid');
                    $('#invalidDataPrint').html(res.pesan.invalidDataPrint);
                }
                if (res.pesan.invalidNamaFileIndikatorKimiaEksternal) {
                    $('#indikatorKimiaEksternal').addClass('is-invalid');
                    $('#invalidIndikatorKimiaEksternal').html(res.pesan.invalidNamaFileIndikatorKimiaEksternal);
                }
                if (res.pesan.invalidIndikatorKimiaEksternal) {
                    $('#indikatorKimiaEksternal').addClass('is-invalid');
                    $('#invalidIndikatorKimiaEksternal').html(res.pesan.invalidIndikatorKimiaEksternal);
                }
                if (res.pesan.invalidNamaFileIndikatorKimiaInternal) {
                    $('#indikatorKimiaInternal').addClass('is-invalid');
                    $('#invalidIndikatorKimiaInternal').html(res.pesan.invalidNamaFileIndikatorKimiaInternal);
                }
                if (res.pesan.invalidIndikatorKimiaInternal) {
                    $('#indikatorKimiaInternal').addClass('is-invalid');
                    $('#invalidIndikatorKimiaInternal').html(res.pesan.invalidIndikatorKimiaInternal);
                }
                if (res.pesan.invalidNamaFileIndikatorBiologi) {
                    $('#indikatorBiologi').addClass('is-invalid');
                    $('#invalidIndikatorBiologi').html(res.pesan.invalidNamaFileIndikatorBiologi);
                }
                if (res.pesan.invalidIndikatorBiologi) {
                    $('#indikatorBiologi').addClass('is-invalid');
                    $('#invalidIndikatorBiologi').html(res.pesan.invalidIndikatorBiologi);
                }
                if (res.pesan.invalidVerifikator) {
                    $('#divVerifikator').addClass('is-invalid');
                    $('#invalidVerifikator').html(res.pesan.invalidVerifikator);
                }
                if (res.pesan.invalidHasilVerifikasi) {
                    $('.form-radio-input[name="hasilVerifikasi"]').addClass('is-invalid');
                    $('#invalidHasilVerifikasi').html(res.pesan.invalidHasilVerifikasi);
                }
                if (res.pesan.errorSimpan) {
                    console.log(res.pesan.errorSimpan);
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

$('#formHapusVerifikasi').on("submit", function (e) {
    e.preventDefault();
    swalDelete.fire({
        title: 'Hapus Verifikasi',
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
                    ).then(() => location.reload());
                }
            });
        }
    });
});