$(function () {
    let dataStorage = sessionStorage.getItem('laporansterilisasiinstrumen');
    if (dataStorage) {
        let data = JSON.parse(dataStorage);
        $('#bulanAwal').val(data.tglAwal);
        $('#bulanAkhir').val(data.tglAkhir);
    }

    dataHeaderTabelLaporanSterilisasi();

    $("#formFilterData").on('submit', function (e) {
        e.preventDefault();
        let tglAwal = $('#bulanAwal').val();
        let tglAkhir = $('#bulanAkhir').val();
        let pesanError = [];
        if (tglAwal == "") {
            pesanError.push({
                idFeedback: "#invalidBulanAwal",
                pesan: "Bulan awal harus diisi",
                invalidElement: "#bulanAwal"
            });
        }
        if (tglAkhir == "") {
            pesanError.push({
                idFeedback: "#invalidBulanAkhir",
                pesan: "Bulan akhir harus diisi",
                invalidElement: "#bulanAkhir"
            });
        }
        if (tglAkhir < tglAwal) {
            pesanError.push({
                idFeedback: "#invalidBulanAkhir",
                pesan: "Bulan akhir harus lebih dari bulan awal",
                invalidElement: "#bulanAkhir"
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
            if ($.fn.DataTable.isDataTable('#tabelLaporanSterilisasiInstrumen')) {
                $('#tabelLaporanSterilisasiInstrumen').DataTable().clear().destroy();
            }
            dataHeaderTabelLaporanSterilisasi();
            sessionStorage.setItem('laporansterilisasiinstrumen',
                JSON.stringify({
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                })
            );
        }
    });

    $('#btnEksporExcel').on('click', function () {
        $("#tabelLaporanSterilisasiInstrumen").DataTable().button('.buttons-excel').trigger();
    });
});

function dataHeaderTabelLaporanSterilisasi() {
    let dataParam = {
        tglAwal: $('#bulanAwal').val() + '-01',
        tglAkhir: $('#bulanAkhir').val() + '-' + new Date($('#bulanAkhir').val().substring(0, 4), $('#bulanAkhir').val().substring(5), 0).getDate()
    };

    $.ajax({
        type: 'POST',
        url: url + '/data-header-tabel-laporan-sterilisasi',
        data: dataParam,
        dataType: 'JSON',
        success: function (res) {
            if (res.sukses) {
                let dataColumns = [
                    {
                        "data": 'no',
                        "orderable": false,
                        "className": "text-center align-middle"
                    },
                    {
                        "data": 'ruangan',
                        "orderable": false,
                        "className": "align-middle"
                    }
                ];

                let th = '';
                $.each(res.data, function (ind, val) {
                    th +=/* html */`<th scope="col" class="text-center align-middle" style="width:'3%';">${val.toUpperCase()}</th>`;
                    dataColumns.push(
                        {
                            "data": ind,
                            "orderable": false,
                            "className": "text-center align-middle"
                        }
                    );
                });

                $('#headerBulan').attr('colspan', Object.keys(res.data).length);
                $('#headerDataBulan').html(th);
                tampilLaporanSterilisasiInstrumen(dataParam, dataColumns);
            }
            if (!res.sukses) {
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

function tampilLaporanSterilisasiInstrumen(dataParam, dataColumns) {
    let form = $('#formFilterData');
    $('#tabelLaporanSterilisasiInstrumen').DataTable(
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
                data: dataParam
            },
            buttons: [
                {
                    "extend": 'excelHtml5',
                    "action": exportAction
                },
            ],
            lengthMenu: [
                [10, 50, 100],
                [10, 50, 100],
            ],
            columns: dataColumns,
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




