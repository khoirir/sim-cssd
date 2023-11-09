$(function () {
    let dataStorage = sessionStorage.getItem('penerimaanalatkotor');
    if (dataStorage) {
        let data = JSON.parse(dataStorage);
        $('#tanggalAwal').val(data.tglAwal);
        $('#tanggalAkhir').val(data.tglAkhir);
        $('#ruangan').val(data.ruangan).trigger('change');;
        $('#dokumentasi').val(data.dokumentasi).trigger('change');;
    }
    tampilDataTabelPenerimaanAlatKotor();
});

function tampilDataTabelPenerimaanAlatKotor() {
    let form = $('#formFilterData');
    $('#tabelPenerimaanAlatKotor').DataTable(
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
                    d.ruangan = $('#ruangan').val();
                    d.dokumentasi = $('#dokumentasi').val();
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
                    "data": 'tanggalPenerimaan',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'jamPenerimaan',
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'ruangan',
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": 'petugasPenyetor',
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": 'petugasCSSD',
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": "dokumentasi",
                    "render": function (data, type, row) {
                        if (data) {
                            let check = /* html */ `<i class="fas fa-check"></i>`;

                            return check;
                        }
                        let checked = '';
                        $.each(checkboxValues, function (i, v) {
                            if (v == row.id) {
                                checked = "checked";
                            }
                        });
                        return /* html */`
                            <div class="icheck-primary ml-2">
                                <input type="checkbox" id="checkbox-${row.id}" name="checkboxUpload" value="${row.id}" class="form-check-input" ${checked}>
                                <label for="checkbox-${row.id}"></label>
                            </div>`;
                    },
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'statusAlat',
                    "render": function (data, type, row) {
                        return data !== "" ? data : `<i class="fa-solid fa-minus"></i>`;
                    },
                    "orderable": false,
                    "className": "pl-3 align-middle"
                },
                {
                    "data": 'id',
                    "render": function (data, type, row) {
                        let html = /* html */
                            `<button data-popup='tooltip' title='Detail Penerimaan Alat Kotor' class="btn btn-info btn-sm border-0" onclick="detailPenerimaanAlatKotor(this,'${data}')">
                                <i class="fas fa-file-lines"></i>
                            </button>`;

                        return html;
                    },
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": 'id',
                    "render": function (data, type, row) {
                        if (row.statusAlat !== "") {
                            return `<i class="fa-solid fa-minus"></i>`;
                        }
                        let editUrl = url + "/edit/" + data;
                        let hapusUrl = url + "/hapus/" + data;
                        let html = /* html */`
                                    <div class="d-flex justify-content-center">
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
                infoFiltered: '(FILTER DARI _MAX_ TOTAL DATA)',
                loadingRecords: "Loading...",
                processing: "Proses menampilkan data...",
            },
        }
    );
}

function detailPenerimaanAlatKotor(el, id) {
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
                $('#modalDetailPenerimaanAlatKotor').modal('show');
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

$('#btnUploadDokumentasi').on('click', function () {
    $.ajax({
        type: 'POST',
        url: url + '/dokumentasi',
        data: {
            id: checkboxValues
        },
        dataType: 'JSON',
        success: function (res) {
            if (res.sukses) {
                $('.viewmodal').html(res.data).show();
                $('#modalUploadDokumentasi').modal('show');
            }
            if (!res.sukses) {
                swalWithBootstrapButtons.fire(
                    "Gagal",
                    res.pesan,
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
});

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
        $('#tabelPenerimaanAlatKotor').DataTable().ajax.reload();
        sessionStorage.setItem('penerimaanalatkotor',
            JSON.stringify({
                tglAwal: tglAwal,
                tglAkhir: tglAkhir,
                ruangan: $('#ruangan').val(),
                dokumentasi: $('#dokumentasi').val()
            })
        );
    }
});

$('#tabelPenerimaanAlatKotor tbody').on("click", ".formHapus button[type='submit']", function (e) {
    e.preventDefault();
    swalDelete.fire({
        title: 'Hapus Penerimaan Alat Kotor',
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
                        ).then(() => $('#tabelPenerimaanAlatKotor').DataTable().ajax.reload(null, false));
                    } else {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "error"
                        ).then(() => $('#tabelPenerimaanAlatKotor').DataTable().ajax.reload(null, false));
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

let checkboxValues = [];
$('#tabelPenerimaanAlatKotor tbody').on('change', 'input[name="checkboxUpload"]', function () {
    let checkedCheckboxes = $('input[name="checkboxUpload"]:checked');
    checkedCheckboxes.each(function () {
        hapusElemenArray(this, checkboxValues);
        checkboxValues.push($(this).val());
    });

    let uncheckedCheckboxes = $('input[name="checkboxUpload"]:not(:checked)');
    uncheckedCheckboxes.each(function () {
        hapusElemenArray(this, checkboxValues);
    });

    $('#btnUploadDokumentasi').prop('hidden', checkboxValues.length === 0);
});

function hapusElemenArray(el, arrEl) {
    let val = $(el).val();
    let index = arrEl.indexOf(val);
    if (index > -1) {
        arrEl.splice(index, 1);
    }
}