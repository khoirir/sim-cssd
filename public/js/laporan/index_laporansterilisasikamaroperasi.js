$(function () {
    let dataStorage = sessionStorage.getItem('laporansterilkamaroperasi');
    if (dataStorage) {
        let data = JSON.parse(dataStorage);
        $('#bulanAwal').val(data.tglAwal);
        $('#bulanAkhir').val(data.tglAkhir);
    }

    tampilLaporanSterilisasiKamarOperasi();

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
            $('#tabelLaporanSterilisasiKamarOperasi').DataTable().ajax.reload();
            sessionStorage.setItem('laporansterilkamaroperasi',
                JSON.stringify({
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                })
            );
        }
    });
     
    $('#btnEksporExcel').on('click', function () {
        $("#tabelLaporanSterilisasiKamarOperasi").DataTable().button('.buttons-excel').trigger();
    });
});


function tampilLaporanSterilisasiKamarOperasi() {
    let form = $("#formFilterData");
    $("#tabelLaporanSterilisasiKamarOperasi").DataTable(
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
                    d.tglAwal = $('#bulanAwal').val() + '-01';
                    d.tglAkhir = $('#bulanAkhir').val() + '-' + new Date($('#bulanAkhir').val().substring(0, 4), $('#bulanAkhir').val().substring(5), 0).getDate();
                }
            },
            buttons: [
                {
                    "extend": 'excelHtml5',
                    "action": exportAction
                },
            ],
            lengthMenu: [
                [12, 60, 120],
                [12, 60, 120],
            ],
            columns: [
                {
                    "data": 'no',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'bulan',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'set',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'pouches',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'linen',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
            ],
            language: {
                lengthMenu: 'TAMPIL _MENU_ DATA',
                zeroRecords: 'TIDAK ADA DATA',
                info: 'HALAMAN _PAGE_ DARI _PAGES_',
                infoEmpty: '',
                infoFiltered: '(FILTER DARI _MAX_ TOTAL DATA)',
                loadingRecords: "Loading...",
                processing: "Proses menampilkan data...",
            }
        }
    );
}




