$(function () {
    $("#alat").select2({
        placeholder: 'Pilih Alat',
    });

    $("#liveToastBtn").click(function () {
        $('.toast').toast('show');
    });
});

$("#ruangan").change(function () {
    let idRuangan = $(this).val();
    $("#jumlah").val("");
    $.ajax({
        type: "POST",
        url: urlDataAlatKotor,
        data: {
            idRuangan: idRuangan,
            mesin: "EOG"
        },
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
});

$("#alat").change(function () {
    let dataAlat = $(this).select2('data')[0];
    let jumlahAlat = dataAlat.jumlah ?? 0;

    $("#jumlah").val(jumlahAlat);
});

$("#jumlah").on("input", function (e) {
    let dataAlat = $("#alat").select2('data')[0];
    let jumlahAlat = parseInt(dataAlat.jumlah ?? 0);
    if (jumlahAlat > 0) {
        if (parseInt($(this).val()) > jumlahAlat) {
            $(this).addClass('is-invalid-custom');
            $("#invalidJumlah").text('Jumlah melebihi jumlah alat yang tersedia');
        } else {
            $(this).removeClass('is-invalid-custom');
            $("#invalidJumlah").text('');
        }
    }
});

function validasiTambah() {
    let pesanError = [];
    if ($('#ruangan').val() === "") {
        pesanError.push({
            idFeedback: "#invalidRuangan",
            pesan: "Ruangan harus dipilih",
            invalidElement: "#divRuangan"
        });
    }
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
    if (validasiInputTabel(
        '#tabelDataAlat',
        $("#alat").select2('data')[0].detail ?? $("#alat").select2('data')[0].id,
        2) > 0
    ) {
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
        let ruangan = $('#ruangan').val();
        let namaRuangan = $('#ruanganOption' + ruangan).text();
        let dataAlat = $("#alat").select2('data')[0];
        let idAlat = dataAlat.id;
        let namaAlat = dataAlat.text;
        let idDetailAlat = dataAlat.detail ?? idAlat;
        let idAlatKotor = dataAlat.detail ?? "";
        let jumlah = $('#jumlah').val();
        $('#tabelDataAlat').find('tfoot').remove();
        let baris = /* html */
            `<tr>
                <td class="align-middle" style="width: 30%"><span data-values="${ruangan}">${namaRuangan}</span></td>
                <td class="align-middle" style="width: 50%"><span data-values="${idAlat}">${namaAlat}</span></td>
                <td class="text-center align-middle" style="width: 10%"><span data-values="${idDetailAlat}">${jumlah}</span></td>
                <td class="text-center align-middle" style="width: 10%">
                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel(this)" data-idalatkotor="${idAlatKotor}"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>`;
        $('#tabelDataAlat tbody').append(baris);
        $('#invalidTabelDataAlat').html('');
        $('#alat').val("").trigger('change');
        $("#jumlah").val("");
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
}

$('#formMonitoringMesinEog').on('submit', function (e) {
    e.preventDefault();
    let tanggalMonitoring = $('#tanggalMonitoring').val();
    let jamMasukAlat = $('#jamMasukAlat').val();
    let shift = $('input[name="shift"]:checked').val();
    let operator = $("#operator").val();
    let siklus = $('#siklus').val();
    let mesin = $('input[name="mesin"]:checked').val();
    let prosesUlang = $('#prosesUlang').val();
    let dataAlat = [];
    let jumlahDataAlatDetail = 0;
    $('#tabelDataAlat tbody tr').each(function (i) {
        let id = $(this).find('td:eq(3) button').attr('values');
        if (!id) {
            dataAlat.push({
                'idAlat': $(this).find('td:eq(1) span').data('values'),
                'alat': $(this).find('td:eq(1) span').text(),
                'idRuangan': $(this).find('td:eq(0) span').data('values'),
                'jumlah': $(this).find('td:eq(2) span').text(),
                'idDetailAlatKotor': $(this).find('td:eq(3) button').data('idalatkotor'),
            });
        }
        jumlahDataAlatDetail++;
    });
    
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
            tanggalMonitoring: tanggalMonitoring,
            jamMasukAlat: jamMasukAlat,
            jamMasuk: tanggalMonitoring + " " + jamMasukAlat,
            shift: shift,
            operator: operator,
            siklus: siklus,
            mesin: mesin,
            prosesUlang: prosesUlang,
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
                if (res.pesan.invalidTanggalMonitoring) {
                    $('#tanggalMonitoring').addClass('is-invalid');
                    $('#invalidTanggalMonitoring').html(res.pesan.invalidTanggalMonitoring);
                }
                if (res.pesan.invalidJamMasukAlat) {
                    $('#jamMasukAlat').addClass('is-invalid');
                    $('#invalidJamMasukAlat').html(res.pesan.invalidJamMasukAlat);
                }
                if (res.pesan.invalidJamMasuk) {
                    $('#jamMasukAlat').addClass('is-invalid');
                    $('#invalidJamMasukAlat').html(res.pesan.invalidJamMasuk);
                }
                if (res.pesan.invalidShift) {
                    $('.form-radio-input[name="shift"]').addClass('is-invalid');
                    $('#invalidShift').html(res.pesan.invalidShift);
                }
                if (res.pesan.invalidOperator) {
                    $('#divOperator').addClass('is-invalid');
                    $('#invalidOperator').html(res.pesan.invalidOperator);
                }
                if (res.pesan.invalidSiklus) {
                    $('#siklus').addClass('is-invalid');
                    $('#invalidSiklus').html(res.pesan.invalidSiklus);
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
                    if (prosesUlang) {
                        window.location.href = url;
                    }
                    else {
                        location.reload();
                    }
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