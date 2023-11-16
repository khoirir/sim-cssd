const dataForm = {
    tab_per_ruangan: {
        idForm: '#formFilterData',
        idTabel: '#tabelLaporanBmhpSteril',
        idHeaderTabel: '#headerRuangan',
        idHeaderDataTabel: '#headerDataRuangan',
        idTanggalAwal: '#tanggalAwal',
        idTanggalAkhir: '#tanggalAkhir',
        idFeedbackTanggalAwal: "#invalidTanggalAwal",
        idFeedbackTanggalAkhir: "#invalidTanggalAkhir",
        pesanTanggalAwalKosong: "Tanggal awal harus diisi",
        pesanTanggalAkhir: {
            kosong: "Tanggal akhir harus diisi",
            kurang: "Tanggal akhir harus lebih dari tanggal awal"
        }
    },
    tab_per_bulan: {
        idForm: '#formFilterDataPerBulan',
        idTabel: '#tabelLaporanBmhpSterilPerBulan',
        idHeaderTabel: '#headerBulan',
        idHeaderDataTabel: '#headerDataBulan',
        idTanggalAwal: '#bulanAwal',
        idTanggalAkhir: '#bulanAkhir',
        idFeedbackTanggalAwal: "#invalidBulanAwal",
        idFeedbackTanggalAkhir: "#invalidBulanAkhir",
        pesanTanggalAwalKosong: "Bulan awal harus diisi",
        pesanTanggalAkhir: {
            kosong: "Bulan akhir harus diisi",
            kurang: "Bulan akhir harus lebih dari bulan awal"
        },
    }
}
$(function () {
    $(".tab-pane").each(function () {
        let tab = $(this).attr("id");
        let dataStorage = sessionStorage.getItem('laporanbmhpsteril' + tab);
        if (dataStorage) {
            let data = JSON.parse(dataStorage);
            $(dataForm[tab].idTanggalAwal).val(data.tglAwal);
            $(dataForm[tab].idTanggalAkhir).val(data.tglAkhir);
        }
        dataHeaderTabelLaporanBmhp(tab);
    });

    let tabAktif = $(".tab-pane.active").attr('id');
    $("ul#nav-pills a.nav-link").click(function () {
        $("ul#nav-pills a.nav-link, .tab-pane").removeClass("active");

        $(this).addClass("active");
        let selectedTab = $(this).attr("href");
        $(selectedTab).addClass("active");

        tabAktif = $(selectedTab).attr('id');
    });

    $(document).on("submit", $(dataForm[tabAktif].idForm), function (e) {
        e.preventDefault();
        let tglAwal = $(dataForm[tabAktif].idTanggalAwal).val();
        let tglAkhir = $(dataForm[tabAktif].idTanggalAkhir).val();
        let pesanError = [];
        if (tglAwal == "") {
            pesanError.push({
                idFeedback: dataForm[tabAktif].idFeedbackTanggalAwal,
                pesan: dataForm[tabAktif].pesanTanggalAwalKosong,
                invalidElement: dataForm[tabAktif].idTanggalAwal
            });
        }
        if (tglAkhir == "") {
            pesanError.push({
                idFeedback: dataForm[tabAktif].idFeedbackTanggalAkhir,
                pesan: dataForm[tabAktif].pesanTanggalAkhir.kosong,
                invalidElement: dataForm[tabAktif].idTanggalAkhir
            });
        }
        if (tglAkhir < tglAwal) {
            pesanError.push({
                idFeedback: dataForm[tabAktif].idFeedbackTanggalAkhir,
                pesan: dataForm[tabAktif].pesanTanggalAkhir.kurang,
                invalidElement: dataForm[tabAktif].idTanggalAkhir
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
            if ($.fn.DataTable.isDataTable(dataForm[tabAktif].idTabel)) {
                $(dataForm[tabAktif].idTabel).DataTable().clear().destroy();
            }
            dataHeaderTabelLaporanBmhp(tabAktif);
            sessionStorage.setItem('laporanbmhpsteril' + tabAktif,
                JSON.stringify({
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                })
            );
        }
    });
});

function eksporExcel(tabel) {
    if ($.fn.DataTable.isDataTable(tabel)) {
        $(tabel).DataTable().button('.buttons-excel').trigger();
    }else{
        swalWithBootstrapButtons.fire(
            "Gagal",
            "Terjadi Masalah,<br>Silahkan Hubungi Tim IT",
            "error"
        );
    }
}

function dataHeaderTabelLaporanBmhp(tab) {
    let dataParam = {
        tab_per_ruangan: {
            tglAwal: $('#tanggalAwal').val(),
            tglAkhir: $('#tanggalAkhir').val()
        },
        tab_per_bulan: {
            tglAwal: $('#bulanAwal').val() + '-01',
            tglAkhir: $('#bulanAkhir').val() + '-' + new Date($('#bulanAkhir').val().substring(0, 4), $('#bulanAkhir').val().substring(5), 0).getDate()
        }
    }
    $.ajax({
        type: 'POST',
        url: url + '/data-header-tabel-laporan-bmhp/' + tab,
        data: dataParam[tab],
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
                        "data": 'bmhp',
                        "orderable": false,
                        "className": "align-middle"
                    },
                    {
                        "data": function (row) {
                            return row.harga ?? row.satuan;
                        },
                        "orderable": false,
                        "render": function (data, type, row) {
                            let regex = /^[a-zA-Z]+$/i;
                            let style = regex.test(data) ? "float:left" : "float:right";
                            return /* html */`<span style="${style}">${data}</span>`;
                        },
                    },
                ];

                let th = '';
                $.each(res.data, function (ind, val) {
                    // let regex = /^[A-Z]{3}-\d{2}$/; regex untuk cek inputan `JAN-23`
                    let regex = /^(?:[A-Z]{3}-\d{2}|Jumlah|-)$/;
                    th +=/* html */`<th scope="col" class="text-center align-middle" style="width:${regex.test(val) ? '3%' : '7%'};">${val.toUpperCase()}</th>`;
                    dataColumns.push(
                        {
                            "data": ind,
                            "orderable": false,
                            "className": regex.test(val) ? "text-center align-middle" : "text-right align-middle",
                            // "render":function(data, type, row){
                            //     if (regex.test(data)) {
                            //         return /* html */`<span style="float-left">${data}</span>`;
                            //     } else {
                            //         let formatCurrency = Intl.NumberFormat('id-ID');
                            //         let hasil = formatCurrency.format(data);
                            //         return /* html */`<span style="float-right">${hasil}</span>`;
                            //     }
                            // }
                        }
                    );
                });

                $(dataForm[tab].idHeaderTabel).attr('colspan', Object.keys(res.data).length);
                $(dataForm[tab].idHeaderDataTabel).html(th);
                tampilLaporanBmhpSteril(tab, dataParam[tab], dataColumns);
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

function tampilLaporanBmhpSteril(tab, dataParam, dataColumns) {
    let form = $(dataForm[tab].idForm);
    $(dataForm[tab].idTabel).DataTable(
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
                url: form.attr('action') + '/' + tab,
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



