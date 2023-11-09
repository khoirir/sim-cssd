function validasiTambah() {
    let pesanError = [];
    if ($('#petugas').val() === "") {
        pesanError.push({
            idFeedback: "#invalidPetugas",
            pesan: "Petugas harus dipilih",
            invalidElement: "#divPetugas"
        });
    }
    if ($('#setAlat').val() === "") {
        pesanError.push({
            idFeedback: "#invalidSetAlat",
            pesan: "Alat harus dipilih",
            invalidElement: "#divSetAlat"
        });
    }
    if (validasiInputTabel('#tabelDataAlat', $('#setAlat').val(), 0) > 0) {
        pesanError.push({
            idFeedback: "#invalidSetAlat",
            pesan: "Alat sudah dipilih",
            invalidElement: "#divSetAlat"
        });
    }
    if ($('input[name="checkboxUjiVisual"]:checked').length == 0) {
        pesanError.push({
            idFeedback: "#invalidUjiVisual",
            pesan: "Uji Visual harus dipilih",
            invalidElement: ".form-check-input"
        });
    }
    if ($('input[name="checkboxIndikator"]:checked').length == 0) {
        pesanError.push({
            idFeedback: "#invalidIndikator",
            pesan: "Indikator harus dipilih",
            invalidElement: ".form-check-input"
        });
    }
    if (pesanError.length > 0) {
        $(".invalid-feedback-custom").html("");
        $('.is-invalid-custom').removeClass('is-invalid-custom');
        $.each(pesanError, function (i, val) {
            $(val.idFeedback).html(val.pesan);
            $(val.invalidElement).addClass('is-invalid-custom');
        });
        return false;
    }
    return true;
}

$('#btnTambah').click(function () {
    if (validasiTambah()) {
        $(".invalid-feedback-custom").html("");
        $('.is-invalid-custom').removeClass('is-invalid-custom');
        let setAlat = $('#setAlat').val();
        let namaSetAlat = $('#setAlatOption' + setAlat).text();
        let ujiVisual = [];
        let checkboxesUjiVisual = $('input[name="checkboxUjiVisual"]:checked');
        checkboxesUjiVisual.each(function () {
            let key = $(this).val();
            let value = $(this).val();
            ujiVisual[key] = `<i class="fas fa-check" values="${value}"></i>`;
        });
        let indikatorVal = $('input[name="checkboxIndikator"]:checked').val();
        let indikator = indikatorVal === '' ? '' : `<i class="fas fa-check" values="${indikatorVal}"></i>`;
        let petugas = $('#petugas').val();
        let namaPetugas = $('#petugasOption' + petugas).text();
        $('#tabelDataAlat').find('tfoot').remove();
        let baris = /* html */
            `<tr>
                <td class="align-middle"><span data-values="${setAlat}">${namaSetAlat}</span></td>
                <td class="text-center align-middle">${ujiVisual['bersih'] ?? ''}</td>
                <td class="text-center align-middle">${ujiVisual['tajam'] ?? ''}</td>
                <td class="text-center align-middle">${ujiVisual['layak'] ?? ''}</td>
                <td class="text-center align-middle">${indikator}</td>
                <td class="align-middle"><span data-values="${petugas}">${namaPetugas}</span></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel('tabelDataAlat', this)"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>`;
        $('#tabelDataAlat tbody').append(baris);
        $('#setAlat').val("").trigger('change');
        $('input[name="checkboxUjiVisual"]').prop('checked', false);
        $('input[name="checkboxIndikator').prop('checked', false);
    }
});

let idDetailHapus = [];

function hapusBarisTabel(tabel, el, idDetail = null) {
    let baris = $(el).closest('td').parent()[0].sectionRowIndex;
    $('#' + tabel + ' tbody').find('tr').eq(baris).remove();
    let jmlBaris = $('#' + tabel + ' tbody tr').length;
    if (jmlBaris === 0) {
        $('#' + tabel).append(`<tfoot><td colspan="7" class="text-center align-middle data-kosong">DATA TIDAK ADA</td></tfoot>`);
    }
    if (idDetail) {
        idDetailHapus.push(idDetail);
    }
}

$('#formPackingAlat').on('submit', function (e) {
    e.preventDefault();
    let tanggalPacking = $('#tanggalPacking').val();
    let dataSetAlat = [];
    let jumlahDataSetAlatDetail = 0;
    $('#tabelDataAlat tbody tr').each(function (i) {
        let id = $(this).find('td:eq(6) button').attr('values');
        if (!id) {
            dataSetAlat.push({
                'idAlat': $(this).find('td:eq(0) span').data('values'),
                'bersih': $(this).find('td:eq(1) i').attr('values'),
                'tajam': $(this).find('td:eq(2) i').attr('values'),
                'layak': $(this).find('td:eq(3) i').attr('values'),
                'indikator': $(this).find('td:eq(4) i').attr('values'),
                'idPetugas': $(this).find('td:eq(5) span').data('values'),
            });
        }
        jumlahDataSetAlatDetail++;
    });
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
            tanggalPacking: tanggalPacking,
            dataSetAlat: dataSetAlat,
            idDetailSetAlatHapus: idDetailHapus,
            jumlahDataSetAlatDetail: jumlahDataSetAlatDetail
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
                $(".invalid-feedback").html("");
                $('.is-invalid').removeClass('is-invalid');
                if (res.pesan.invalidTanggalPacking) {
                    $('#tanggalPacking').addClass('is-invalid');
                    $('#invalidTanggalPacking').html(res.pesan.invalidTanggalPacking);
                }
                if (res.pesan.invalidTabelDataAlat) {
                    $('#tabelDataAlat').addClass('is-invalid');
                    $('#invalidTabelDataAlat').html(res.pesan.invalidTabelDataAlat);
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