$(function () {
    let tanggal = sessionStorage.getItem('monitoringmesinsteam');
    if (tanggal) {
        let data = JSON.parse(tanggal);
        $('#tanggalAwal').val(data.tglAwal);
        $('#tanggalAkhir').val(data.tglAkhir);
    }
    tampilDataTabelMonitoringMesinSteam();
});

function tampilDataTabelMonitoringMesinSteam() {
    let form = $('#formFilterData');
    $('#tabelMonitoringMesinSteam').DataTable(
        {
            bDestroy: true,
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
            bInfo: true,
            autoWidth: false,
            responsive: true,
            deferRender: true,
            ajax: {
                url: form.attr('action'),
                type: form.attr('method'),
                data: function (d) {
                    d.tglAwal = $('#tanggalAwal').val();
                    d.tglAkhir = $('#tanggalAkhir').val();
                }
            },
            lengthMenu: [
                [10, 50, 100],
                [10, 50, 100],
            ],
            columns: [
                {
                    "data": 'noReferensi',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'tanggalMonitoring',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'jamMasukAlat',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'siklus',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'mesin',
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": 'verifikasi',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'keterangan',
                    "orderable": false,
                    "className": "pl-3 align-middle"
                },
                {
                    "data": 'detail',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'aksi',
                    "orderable": false,
                    "className": "text-center align-middle"
                }
            ],
            language: {
                lengthMenu: 'TAMPIL _MENU_ DATA',
                zeroRecords: 'TIDAK ADA DATA',
                info: 'HALAMAN _PAGE_ DARI _PAGES_',
                infoEmpty: '',
                infoFiltered: '(FILTER DARI _MAX_ TOTAL DATA)',
                loadingRecords: "Loading...",
                processing: "Proses menampilkan data...",
            },
        }
    );
}

function detailMonitoringMesinSteam(el,id) {
    $.ajax({
        type: 'GET',
        url: url + '/detail/' + id,
        dataType: 'JSON',
        beforeSend: function () {
            $(el).prop('disabled', true);
            $(el).html(`<i class="fa fa-spin fa-spinner"></i>`);
        },
        complete: function () {
            $(el).prop('disabled', false);
            $(el).html(`<i class="fas fa-file-lines"></i>`);
        },
        success: function (res) {
            if (!res.sukses) {
                swalWithBootstrapButtons.fire(
                    "Gagal",
                    res.pesan,
                    "error"
                );
            }
            if (res.sukses) {
                $('.viewmodal').html(res.data).show();
                $('#modalDetailMonitoringMesinSteam').modal('show');
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

$('#formFilterData').on('submit', function (e) {
    e.preventDefault();
    let tglAwal = $('#tanggalAwal').val();
    let tglAkhir = $('#tanggalAkhir').val();
    let pesanError = [];
    if (tglAwal == "") {
        pesanError.push({
            idFeedback: "#invalidTanggalAwal",
            pesan: "Tanggal awal harus diisi",
            invalidElement: "#tanggalAwal"
        });
    }
    if (tglAkhir == "") {
        pesanError.push({
            idFeedback: "#invalidTanggalAkhir",
            pesan: "Tanggal akhir harus diisi",
            invalidElement: "#tanggalAkhir"
        });
    }
    if (tglAkhir < tglAwal) {
        pesanError.push({
            idFeedback: "#invalidTanggalAkhir",
            pesan: "Tanggal akhir harus lebih dari tanggal awal",
            invalidElement: "#tanggalAkhir"
        });
    }
    if (pesanError.length > 0) {
        $(".invalid-feedback").html("");
        $('.is-invalid').removeClass('is-invalid');
        $.each(pesanError, function (i, val) {
            $(val.idFeedback).html(val.pesan);
            $(val.invalidElement).addClass('is-invalid');
        });
    } else {
        $(".invalid-feedback").html("");
        $('.is-invalid').removeClass('is-invalid');
        $('#tabelMonitoringMesinSteam').DataTable().ajax.reload();
        sessionStorage.setItem('monitoringmesinsteam', JSON.stringify({ tglAwal: tglAwal, tglAkhir: tglAkhir }));
    }
});

$('#tabelMonitoringMesinSteam tbody').on("click", ".formHapus button[type='submit']", function (e) {
    e.preventDefault();
    swalDelete.fire({
        title: 'Hapus Monitoring Mesin Steam',
        text: "Apakah yakin hapus?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "YAKIN",
        cancelButtonText: "BATAL",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $(this).closest("form");
            let data = form.serialize();
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: data,
                dataType: "JSON",
                success: function (res) {
                    console.log(res);
                    if (res.sukses) {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "success"
                        ).then(() => $('#tabelMonitoringMesinSteam').DataTable().ajax.reload(null, false));
                    } else {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "error"
                        ).then(() => $('#tabelMonitoringMesinSteam').DataTable().ajax.reload(null, false));
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