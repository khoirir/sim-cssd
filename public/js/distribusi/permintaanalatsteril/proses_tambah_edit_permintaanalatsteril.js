$(function () {
    $("#alat").select2({
        placeholder: 'Pilih Alat',
    });
    if ($('#idRuangan').val()) {
        dataAlatSteril($('#idRuangan').val());
    }

    $("#liveToastBtn").click(function () {
        $('.toast').toast('show');
    });
});


$("#ruangan").change(function () {
    let idRuangan = $(this).val();
    $("#jumlah").val("");
    dataAlatSteril(idRuangan);
});

function dataAlatSteril(idRuangan) {
    $.ajax({
        type: "GET",
        url: url + "/data-alat-steril/" + idRuangan,
        beforeSend: function () {
            $('select#alat').html("<option></option>");
        },
        dataType: "JSON",
        success: function (res) {
            if (res.sukses) {
                let data = res.data;
                $("#alat").select2({
                    placeholder: 'Pilih Alat',
                    data: data,
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function (data) {
                        return data.html;
                    },
                    templateSelection: function (data) {
                        return data.text;
                    }
                })
            } else {
                $('select#alat').html("<option></option>");
            }
        },
        error: function () {
            $('select#alat').html("<option></option>");
        }
    });
}

$("#alat").change(function () {
    let dataAlat = $(this).select2('data')[0];
    let jumlahAlat = dataAlat.jumlah;

    $("#jumlah").val(jumlahAlat);
});

$("#jumlah").on("input", function (e) {
    let dataAlat = $("#alat").select2('data')[0];
    let jumlahAlat = parseInt(dataAlat.jumlah);
    if (parseInt($(this).val()) > jumlahAlat) {
        $(this).addClass('is-invalid-custom');
        $("#invalidJumlah").text('Jumlah melebihi jumlah alat yang tersedia');
    } else {
        $(this).removeClass('is-invalid-custom');
        $("#invalidJumlah").text('');
    }
});

function validasiTambah() {
    let pesanError = [];
    if ($('#alat').val() === "") {
        pesanError.push({
            idFeedback: "#invalidAlat",
            pesan: "Alat harus dipilih",
            invalidElement: "#divAlat"
        });
    }
    if ($('#jumlah').val() === "" || parseInt($('#jumlah').val()) === 0) {
        pesanError.push({
            idFeedback: "#invalidJumlah",
            pesan: "Jumlah harus diisi",
            invalidElement: "#jumlah"
        });
    }
    if (parseInt($('#jumlah').val()) > 0) {
        if (parseInt($('#jumlah').val()) > parseInt($("#alat").select2('data')[0].jumlah)) {
            pesanError.push({
                idFeedback: "#invalidJumlah",
                pesan: "Jumlah melebihi jumlah alat yang tersedia",
                invalidElement: "#jumlah"
            });
        }
    }
    if (validasiInputTabel('#tabelDataAlat', $("#alat").select2('data')[0].detail, 1) > 0) {
        pesanError.push({
            idFeedback: "#invalidAlat",
            pesan: "Alat sudah dipilih",
            invalidElement: "#divAlat"
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
        let dataAlat = $("#alat").select2('data')[0];
        let idAlat = dataAlat.id;
        let namaAlat = dataAlat.text;
        let idDetailAlat = dataAlat.detail;
        let jumlah = $('#jumlah').val();
        let keterangan = $('#keterangan').val();
        $('#tabelDataAlat').find('tfoot').remove();
        let baris = /* html */
            `<tr>
                <td class="align-middle" style="width: 50%"><span data-values="${idAlat}">${namaAlat}</span></td>
                <td class="text-center align-middle" style="width: 10%"><span data-values="${idDetailAlat}">${jumlah}</span></td>
                <td class="align-middle" style="width: 30%"><span>${keterangan}</span></td>
                <td class="text-center align-middle" style="width: 10%">
                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel(this)"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>`;
        $('#tabelDataAlat tbody').append(baris);
        $('#invalidTabelDataAlat').html('');
        $('#alat').val("").trigger('change');
        $("#jumlah").val("");
        $('#keterangan').val("");
        $("#ruangan").prop("disabled", true);
    }
});

function hapusBarisTabel(el, idDetail = null) {
    let baris = $(el).closest('td').parent()[0].sectionRowIndex;
    if (idDetail) {
        $.ajax({
            type: "POST",
            url: url + "/hapus-detail/" + idDetail,
            data: {
                _method: "DELETE",
                [csrfToken]: csrfHash,
                idDetail: idDetail
            },
            dataType: "JSON",
            beforeSend: function () {
                $(el).prop('disabled', true);
                $(el).html(`<i class="fa fa-spin fa-spinner mr-2"></i>`);
            },
            complete: function () {
                $(el).prop('disabled', false);
                $(el).html(`<i class="far fa-trash-alt"></i>`);
            },
            success: function (res) {
                toastr[res.toastr.tipe](res.toastr.teks);
                if (res.sukses) {
                    $('#tabelDataAlat tbody').find('tr').eq(baris).remove();
                    dataAlatSteril($('#idRuangan').val());
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
    } else {
        $('#tabelDataAlat tbody').find('tr').eq(baris).remove();
    }
    let jmlBaris = $('#tabelDataAlat tbody tr').length;
    if (jmlBaris === 0) {
        $('#tabelDataAlat').append(`<tfoot><td colspan="4" class="text-center align-middle data-kosong">DATA TIDAK ADA</td></tfoot>`);
        $("#ruangan").prop("disabled", false);
    }
}

function batalHapus(el, idDetail) {
    $.ajax({
        type: "POST",
        url: url + "/batal-hapus-detail/" + idDetail,
        data: {
            [csrfToken]: csrfHash,
            idDetail: idDetail
        },
        dataType: "JSON",
        beforeSend: function () {
            $(el).prop('disabled', true);
        },
        complete: function () {
            $(el).prop('disabled', false);
        },
        success: function (res) {
            if (res.sukses) {
                $('#tabelDataAlat tbody').append(res.data);
            }
            toastr[res.toastr.tipe](res.toastr.teks);
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
    dataAlatSteril($('#idRuangan').val());
}

$('#formPermintaanAlatSteril').on('submit', function (e) {
    e.preventDefault();
    let tanggalPermintaan = $('#tanggalPermintaan').val();
    let jamPermintaan = $('#jamPermintaan').val();
    let petugasCSSD = $("#petugasCSSD").val();
    let petugasMinta = $("#petugasMinta").val();
    let ruangan = $('#idRuangan').val() ?? $('#ruangan').val();
    let dataAlat = [];
    let jumlahDataAlatDetail = 0;
    $('#tabelDataAlat tbody tr').each(function (i) {
        let id = $(this).find('td:eq(3) button').attr('values');
        if (!id) {
            dataAlat.push({
                'idAlat': $(this).find('td:eq(0) span').data('values'),
                'alat': $(this).find('td:eq(0) span').text(),
                'jumlah': $(this).find('td:eq(1) span').text(),
                'keterangan': $(this).find('td:eq(2) span').text(),
                'idDetailAlatKotor': $(this).find('td:eq(1) span').data('values'),
            });
        }
        jumlahDataAlatDetail++;
    });
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
            tanggalPermintaan: tanggalPermintaan,
            jamPermintaan: jamPermintaan,
            jamMinta: tanggalPermintaan + " " + jamPermintaan,
            petugasCSSD: petugasCSSD,
            petugasMinta: petugasMinta,
            ruangan: ruangan,
            dataAlat: dataAlat,
            jumlahDataAlatDetail: jumlahDataAlatDetail
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
                if (res.pesan.invalidTanggalPermintaan) {
                    $('#tanggalPermintaan').addClass('is-invalid');
                    $('#invalidTanggalPermintaan').html(res.pesan.invalidTanggalPermintaan);
                }
                if (res.pesan.invalidJamPermintaan) {
                    $('#jamPermintaan').addClass('is-invalid');
                    $('#invalidJamPermintaan').html(res.pesan.invalidJamPermintaan);
                }
                if (res.pesan.invalidJamMinta) {
                    $('#jamPermintaan').addClass('is-invalid');
                    $('#invalidJamPermintaan').html(res.pesan.invalidJamMinta);
                }
                if (res.pesan.invalidPetugasCSSD) {
                    $('#divPetugasCSSD').addClass('is-invalid');
                    $('#invalidPetugasCSSD').html(res.pesan.invalidPetugasCSSD);
                }
                if (res.pesan.invalidPetugasMinta) {
                    $('#divPetugasMinta').addClass('is-invalid');
                    $('#invalidPetugasMinta').html(res.pesan.invalidPetugasMinta);
                }
                if (res.pesan.invalidRuangan) {
                    $('#divRuangan').addClass('is-invalid');
                    $('#invalidRuangan').html(res.pesan.invalidRuangan);
                }
                if (res.pesan.invalidTabelDataAlat) {
                    $('#tabelDataAlat').addClass('is-invalid');
                    $('#invalidTabelDataAlat').html(res.pesan.invalidTabelDataAlat);
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
                ).then(() => {
                    location.reload();
                });
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

