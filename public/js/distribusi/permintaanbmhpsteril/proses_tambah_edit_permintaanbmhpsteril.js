$(function () {
    dataBmhpSteril();
});

function dataBmhpSteril() {
    $.ajax({
        type: "GET",
        url: url + '/data-bmhp-steril',
        beforeSend: function () {
            $('select#namaBmhp').html("<option></option>");
        },
        dataType: "JSON",
        success: function (res) {
            if (res.sukses) {
                let data = res.data;
                $("#namaBmhp").select2({
                    placeholder: 'Pilih BMHP',
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
                $('select#namaBmhp').html("<option></option>");
            }
        },
        error: function () {
            $('select#namaBmhp').html("<option></option>");
        }
    });
}

$("#namaBmhp").change(function () {
    $('.is-invalid-custom').removeClass('is-invalid-custom');
    $(".invalid-feedback-custom").text('');
    let dataBmhp = $(this).select2('data')[0];
    $("#harga").val(dataBmhp.harga);
    $("#jumlah").val(dataBmhp.jumlah);
});

$("#jumlah").on("input", function (e) {
    let dataBmhp = $("#namaBmhp").select2('data')[0];
    let jumlah = parseInt(dataBmhp.jumlah);
    if (parseInt($(this).val()) > jumlah) {
        $(this).addClass('is-invalid-custom');
        $("#invalidJumlah").text('Jumlah melebihi jumlah BMHP yang tersedia');
    } else {
        $(this).removeClass('is-invalid-custom');
        $("#invalidJumlah").text('');
    }
});

function validasiTambah() {
    let pesanError = [];
    if ($('#namaBmhp').val() === "") {
        pesanError.push({
            idFeedback: "#invalidBmhp",
            pesan: "BMHP harus dipilih",
            invalidElement: "#divNamaBmhp"
        });
    }
    if ($('#harga').val() === "" || parseInt($('#harga').val()) === 0) {
        pesanError.push({
            idFeedback: "#invalidHarga",
            pesan: "Harga harus diisi",
            invalidElement: "#harga"
        });
    }
    if ($('#jumlah').val() === "" || parseInt($('#jumlah').val()) === 0) {
        pesanError.push({
            idFeedback: "#invalidJumlah",
            pesan: "Jumlah harus diisi",
            invalidElement: "#jumlah"
        });
    }
    if (parseInt($('#jumlah').val()) > parseInt($("#namaBmhp").select2('data')[0].jumlah)) {
        pesanError.push({
            idFeedback: "#invalidJumlah",
            pesan: "Jumlah melebihi jumlah BMHP yang tersedia",
            invalidElement: "#jumlah"
        });
    }
    if (validasiInputTabel(
        '#tabelDataBmhp',
        $("#namaBmhp").select2('data')[0].detail,
        2) > 0
    ) {
        pesanError.push({
            idFeedback: "#invalidBmhp",
            pesan: "BMHP sudah dipilih",
            invalidElement: "#divNamaBmhp"
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

function formatCurrency(angka) {
    let f_rp = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
    let r_simbol = f_rp.substring(2);
    return r_simbol;
}

function removeFormatCurrency(angka) {
    return angka.toString().replace(/\./g, '').replace(/,/, '.');
}

function hitungTotal() {
    let total = 0.00;
    $('#tabelDataBmhp tbody tr').each(function (i) {
        let harga = parseFloat(removeFormatCurrency($(this).find('td:eq(1)').text()));
        let jumlah = parseFloat($(this).find('td:eq(2) span').text());
        let subtotal = parseFloat((harga * jumlah).toFixed(2));
        total += subtotal;
    });
    return total;
}


$('#btnTambah').click(function () {
    if (validasiTambah()) {
        $(".invalid-feedback-custom").html("");
        $('.is-invalid-custom').removeClass('is-invalid-custom');
        let dataBmhp = $("#namaBmhp").select2('data')[0];
        let idBmhp = dataBmhp.id;
        let namaBmhp = dataBmhp.text;
        let idDetailSteam = dataBmhp.detail;
        let harga = dataBmhp.harga;
        let jumlah = $('#jumlah').val();
        let keterangan = $('#keterangan').val();
        let subtotal = parseFloat((removeFormatCurrency(harga) * jumlah).toFixed(2));
        $('#tabelDataBmhp tbody tr.data-kosong').remove();
        let baris = /* html */
            `<tr>
                <td class="align-middle" style="width: 30%"><span data-values="${idBmhp}">${namaBmhp}</span></td>
                <td class="text-right align-middle pr-3" style="width: 15%">${harga}</td>
                <td class="text-center align-middle" style="width: 10%"><span data-values="${idDetailSteam}">${jumlah}</span></td>
                <td class="text-right align-middle pr-3" style="width: 15%">${formatCurrency(subtotal)}</td>
                <td class="align-middle pl-3" style="width: 12%">${keterangan}</td>
                <td class="text-center align-middle" style="width: 10%">
                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="hapusBarisTabel(this)"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>`;
        $('#tabelDataBmhp tbody').append(baris);
        $('#tabelDataBmhp tfoot th.total').html(formatCurrency(hitungTotal()));
        $('#invalidTabelDataBmhp').html('');
        $('#namaBmhp').val("").trigger('change');
        $("#jumlah").val("");
        $("#harga").val("");
        $("#keterangan").val("");
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
                    $('#tabelDataBmhp tbody').find('tr').eq(baris).remove();
                    $('#tabelDataBmhp tfoot th.total').html(formatCurrency(hitungTotal()));
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
        $('#tabelDataBmhp tbody').find('tr').eq(baris).remove();
        $('#tabelDataBmhp tfoot th.total').html(formatCurrency(hitungTotal()));
    }
    let jmlBaris = $('#tabelDataBmhp tbody tr td').length;
    if (jmlBaris === 0) {
        $('#tabelDataBmhp tbody').append(`<tr class='data-kosong'><td colspan="6" class="text-center align-middle">DATA TIDAK ADA</td></tr>`);
    }
    dataBmhpSteril();
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
                $('#tabelDataBmhp tbody').append(res.data);
                $('#tabelDataBmhp tfoot th.total').html(formatCurrency(hitungTotal()));
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
    dataBmhpSteril();
}

$('#formPermintaanBmhpSteril').on('submit', function (e) {
    e.preventDefault();
    let tanggalPermintaan = $('#tanggalPermintaan').val();
    let jamPermintaan = $('#jamPermintaan').val();
    let petugasCSSD = $("#petugasCSSD").val();
    let petugasMinta = $("#petugasMinta").val();
    let ruangan = $('#ruangan').val();
    let dataBmhp = [];
    let jumlahDataBmhpDetail = 0;
    $('#tabelDataBmhp tbody tr').each(function (i) {
        let id = $(this).find('td:eq(5) button').attr('values');
        let idBmhp = $(this).find('td:eq(0) span').data('values');
        if (!id && idBmhp) {
            dataBmhp.push({
                'idBmhp': idBmhp,
                'bmhp': $(this).find('td:eq(0) span').text(),
                'jumlah': $(this).find('td:eq(2) span').text(),
                'keterangan': $(this).find('td:eq(4)').text(),
                'idDetailSteam': $(this).find('td:eq(2) span').data('values'),
            });
            jumlahDataBmhpDetail++;
        }
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
            dataBmhp: dataBmhp,
            jumlahDataBmhpDetail: jumlahDataBmhpDetail
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
                if (res.pesan.invalidTabelDataBmhp) {
                    $('#tabelDataBmhp').addClass('is-invalid');
                    $('#invalidTabelDataBmhp').html(res.pesan.invalidTabelDataBmhp);
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