function validasiTambah() {
    let pesanError = [];
    if ($('#setAlat').val() === "") {
        pesanError.push({
            idFeedback: "#invalidSetAlat",
            pesan: "Set/alat harus dipilih",
            invalidElement: "#divSetAlat"
        });
    }
    if ($('#jumlah').val() === "") {
        pesanError.push({
            idFeedback: "#invalidJumlah",
            pesan: "Jumlah harus diisi",
            invalidElement: "#jumlah"
        });
    }
    if (validasiInputTabel('#tabelDataSetAlat', $('#setAlat').val(), 0) > 0) {
        pesanError.push({
            idFeedback: "#invalidSetAlat",
            pesan: "Set/alat sudah dipilih",
            invalidElement: "#divSetAlat"
        });
    }
    if ($('input[type="checkbox"]:checked').length == 0) {
        pesanError.push({
            idFeedback: "#invalidProses",
            pesan: "Proses harus dipilih",
            invalidElement: ".form-check-input"
        });
    }
    if ($('input[type="radio"]:checked').length == 0) {
        pesanError.push({
            idFeedback: "#invalidPemilihanMesin",
            pesan: "Mesin harus dipilih",
            invalidElement: ".form-radio-input"
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
        let jumlah = $('#jumlah').val();
        let valuesProses = [];
        let checkboxesProses = $('input[type="checkbox"]:checked');
        checkboxesProses.each(function () {
            let key = $(this).val();
            let value = $(this).val();
            valuesProses[key] = `<i class="fas fa-check" values="${value}"></i>`;
        });
        let pemilihanMesin = $('input[name="pemilihanMesin"]:checked').val();
        $('#tabelDataSetAlat').find('tfoot').remove();
        let baris = /* html */
            `<tr>
                <td class="align-middle"><span data-values="${setAlat}">${namaSetAlat}</span></td>
                <td class="text-center align-middle">${jumlah}</td>
                <td class="text-center align-middle">${valuesProses['enzym'] ?? ''}</td>
                <td class="text-center align-middle">${valuesProses['dtt'] ?? ''}</td>
                <td class="text-center align-middle">${valuesProses['ultrasonic'] ?? ''}</td>
                <td class="text-center align-middle">${valuesProses['bilas'] ?? ''}</td>
                <td class="text-center align-middle">${valuesProses['washer'] ?? ''}</td>
                <td class="text-center align-middle">${pemilihanMesin}</td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel('tabelDataSetAlat', this)"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>`;
        $('#tabelDataSetAlat tbody').append(baris);
        $('#setAlat').val("").trigger('change');
        $('#jumlah').val("");
        $('input[type="checkbox"]').prop('checked', false);
        $('input[name="pemilihanMesin').prop('checked', false);
    }
});

let idDetailHapus = [];

function hapusBarisTabel(tabel, el, idDetail = null) {
    let baris = $(el).closest('td').parent()[0].sectionRowIndex;
    $('#' + tabel + ' tbody').find('tr').eq(baris).remove();
    let jmlBaris = $('#' + tabel + ' tbody tr').length;
    if (jmlBaris === 0) {
        $('#' + tabel).append(`<tfoot><td colspan="9" class="text-center align-middle data-kosong">DATA TIDAK ADA</td></tfoot>`);
    }
    if (idDetail) {
        idDetailHapus.push(idDetail);
    }
}

$('#formPenerimaanAlatKotor').on('submit', function (e) {
    e.preventDefault();
    let tanggalPenerimaan = $('#tanggalPenerimaan').val();
    let jamPenerimaan = $('#jamPenerimaan').val();
    let petugasCSSD = $('#petugasCSSD').val();
    let petugasPenyetor = $('#petugasPenyetor').val();
    let ruangan = $('#ruangan').val();
    let dataSetAlat = [];
    let jumlahDataSetAlatDetail = 0;
    $('#tabelDataSetAlat tbody tr').each(function (i) {
        let id = $(this).find('td:eq(8) button').attr('values');
        if (!id) {
            dataSetAlat.push({
                'idSetAlat': $(this).find('td:eq(0) span').data('values'),
                'namaSetAlat': $(this).find('td:eq(0)').text(),
                'jumlah': $(this).find('td:eq(1)').text(),
                'enzym': $(this).find('td:eq(2) i').attr('values'),
                'dtt': $(this).find('td:eq(3) i').attr('values'),
                'ultrasonic': $(this).find('td:eq(4) i').attr('values'),
                'bilas': $(this).find('td:eq(5) i').attr('values'),
                'washer': $(this).find('td:eq(6) i').attr('values'),
                'pemilihanMesin': $(this).find('td:eq(7)').text(),
            });
        }
        jumlahDataSetAlatDetail++;
    });
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
            tanggalPenerimaan: tanggalPenerimaan,
            jamPenerimaan: jamPenerimaan,
            jamTerima: tanggalPenerimaan + " " + jamPenerimaan,
            petugasCSSD: petugasCSSD,
            petugasPenyetor: petugasPenyetor,
            ruangan: ruangan,
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
                if (res.pesan.invalidTanggalPenerimaan) {
                    $('#tanggalPenerimaan').addClass('is-invalid');
                    $('#invalidTanggalPenerimaan').html(res.pesan.invalidTanggalPenerimaan);
                }
                if (res.pesan.invalidJamPenerimaan) {
                    $('#jamPenerimaan').addClass('is-invalid');
                    $('#invalidJamPenerimaan').html(res.pesan.invalidJamPenerimaan);
                }
                if (res.pesan.invalidJamTerima) {
                    $('#jamPenerimaan').addClass('is-invalid');
                    $('#invalidJamPenerimaan').html(res.pesan.invalidJamTerima);
                }
                if (res.pesan.invalidPetugasCSSD) {
                    $('#divPetugasCSSD').addClass('is-invalid');
                    $('#invalidPetugasCSSD').html(res.pesan.invalidPetugasCSSD);
                }
                if (res.pesan.invalidPetugasPenyetor) {
                    $('#divPetugasPenyetor').addClass('is-invalid');
                    $('#invalidPetugasPenyetor').html(res.pesan.invalidPetugasPenyetor);
                }
                if (res.pesan.invalidRuangan) {
                    $('#divRuangan').addClass('is-invalid');
                    $('#invalidRuangan').html(res.pesan.invalidRuangan);
                }
                if (res.pesan.invalidTabelDataSetAlat) {
                    $('#tabelDataSetAlat').addClass('is-invalid');
                    $('#invalidTabelDataSetAlat').html(res.pesan.invalidTabelDataSetAlat);
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