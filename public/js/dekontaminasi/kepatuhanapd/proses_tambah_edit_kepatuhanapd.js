$('#checkboxAll').click(function () {
    $('input[type="checkbox"]').prop('checked', this.checked);
});

$('input[type="checkbox"]').click(function () {
    if (!$(this).prop("checked")) {
        $('#checkboxAll').prop('checked', false);
    }
});

function validasiTambah() {
    let pesanError = [];
    if ($('#petugas').val() === "") {
        pesanError.push({
            idFeedback: "#invalidPetugas",
            pesan: "Petugas harus diisi",
            invalidElement: "#divPetugas"
        });
    }
    if (validasiInputTabel('#tabelDataApd', $('#petugas').val(), 0) > 0) {
        pesanError.push({
            idFeedback: "#invalidPetugas",
            pesan: "Petugas sudah dipilih",
            invalidElement: "#divPetugas"
        });
    }
    if ($('input[type="checkbox"]:checked').length == 0) {
        pesanError.push({
            idFeedback: "#invalidApd",
            pesan: "APD harus dipilih",
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
        let petugas = $('#petugas').val();
        let namaPetugas = $('#pegawaiOption' + petugas).text();
        let valuesAPD = [];
        let checkboxesAPD = $('input[type="checkbox"]:checked');
        checkboxesAPD.each(function () {
            let key = $(this).val();
            let value = $(this).val();
            valuesAPD[key] = `<i class="fas fa-check" values="${value}"></i>`;
        });
        let keterangan = $('#keterangan').val();
        $('#tabelDataApd').find('tfoot').remove();
        let baris = /* html */
            `<tr>
                <td class="align-middle"><span data-values="${petugas}">${namaPetugas}</span></td>
                <td class="text-center align-middle">${valuesAPD['handschoen'] ?? ''}</td>
                <td class="text-center align-middle">${valuesAPD['masker'] ?? ''}</td>
                <td class="text-center align-middle">${valuesAPD['apron'] ?? ''}</td>
                <td class="text-center align-middle">${valuesAPD['goggle'] ?? ''}</td>
                <td class="text-center align-middle">${valuesAPD['sepatu_boot'] ?? ''}</td>
                <td class="text-center align-middle">${valuesAPD['penutup_kepala'] ?? ''}</td>
                <td class="align-middle">${keterangan}</td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel('tabelDataApd', this)"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>`;
        $('#tabelDataApd tbody').append(baris);
        $('#petugas').val("").trigger('change');
        $('input[type="checkbox"]').prop('checked', false);
        $('#keterangan').val("");
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

$('#formApd').on('submit', function (e) {
    e.preventDefault();
    let tanggalCek = $('#tanggalCek').val();
    let shift = $('input[name="shift"]:checked').val();
    let dataApd = [];
    let jumlahDataKepatuhanApdDetail = 0;
    $('#tabelDataApd tbody tr').each(function (i) {
        let id = $(this).find('td:eq(8) button').attr('values');
        if (!id) {
            dataApd.push({
                'idPetugas': $(this).find('td:eq(0) span').data('values'),
                'namaPetugas': $(this).find('td:eq(0)').text(),
                'handschoen': $(this).find('td:eq(1) i').attr('values'),
                'masker': $(this).find('td:eq(2) i').attr('values'),
                'apron': $(this).find('td:eq(3) i').attr('values'),
                'goggle': $(this).find('td:eq(4) i').attr('values'),
                'sepatuBoot': $(this).find('td:eq(5) i').attr('values'),
                'penutupKepala': $(this).find('td:eq(6) i').attr('values'),
                'keterangan': $(this).find('td:eq(7)').text(),
            });
        }
        jumlahDataKepatuhanApdDetail++;
    });
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
            tanggalCek: tanggalCek,
            shift: shift,
            dataKepatuhanApdDetail: dataApd,
            idKepatuhanApdHapus: idDetailHapus,
            jumlahDataKepatuhanApdDetail: jumlahDataKepatuhanApdDetail
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
                if (res.pesan.invalidTanggalCek) {
                    $('#tanggalCek').addClass('is-invalid');
                    $('#invalidTanggalCek').html(res.pesan.invalidTanggalCek);
                }
                if (res.pesan.invalidShift) {
                    $('.form-radio-input').addClass('is-invalid');
                    $('#invalidShift').html(res.pesan.invalidShift);
                }
                if (res.pesan.invalidTabelDataApd) {
                    $('#tabelDataApd').addClass('is-invalid');
                    $('#invalidTabelDataApd').html(res.pesan.invalidTabelDataApd);
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