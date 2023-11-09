$(function () {
    let tanggal = sessionStorage.getItem('suhudankelembapan');
    if (tanggal) {
        let data = JSON.parse(tanggal);
        $('#tanggalAwal').val(data.tglAwal);
        $('#tanggalAkhir').val(data.tglAkhir);
    }
    tampilDataTabelMonitoringSuhuKelembapan();
});

function tampilDataTabelMonitoringSuhuKelembapan() {
    let form = $('#formFilterData');
    $('#tabelSuhuKelembapan').DataTable(
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
                    "data": "noReferensi",
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": "tanggalCatat",
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": "suhu",
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": "kelembapan",
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": "petugas",
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": "tindakan",
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        let editUrl = url + "/edit/" + data;
                        let hapusUrl = url + "/hapus/" + data;
                        let html = /* html */
                            `<div class="d-flex justify-content-center">
                                <a href="${editUrl}" class="btn btn-warning btn-sm border-0" data-popup='tooltip' title='Edit Data'>
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="${hapusUrl}" method="POST" class="formHapus">
                                    <input type="hidden" name="${csrfToken}" value="${csrfHash}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="ml-1 btn btn-danger btn-sm border-0" data-popup='tooltip' title='Hapus Data'>
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>`;

                        return html;
                    },
                    "orderable": false,
                    "className": "text-center align-middle"
                }
            ],
            language: {
                lengthMenu: 'TAMPIL _MENU_ DATA',
                zeroRecords: 'TIDAK ADA DATA',
                info: 'HALAMAN _PAGE_ DARI _PAGES_',
                infoEmpty: '',
                infoFiltered: '(FILTEE DARI _MAX_ TOTAL DATA)',
                loadingRecords: "Loading...",
                processing: "Proses menampilkan data...",
            },
        }
    );
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
        $('#tabelSuhuKelembapan').DataTable().ajax.reload();
        sessionStorage.setItem('suhudankelembapan', JSON.stringify({ tglAwal: tglAwal, tglAkhir: tglAkhir }));
    }
});

$(document).on("click", ".formHapus button[type='submit']", function (e) {
    e.preventDefault();
    swalDelete.fire({
        title: 'Hapus Suhu & Kelembapan',
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
                    if (res.sukses) {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "success"
                        ).then(() => $('#tabelSuhuKelembapan').DataTable().ajax.reload(null, false));
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



