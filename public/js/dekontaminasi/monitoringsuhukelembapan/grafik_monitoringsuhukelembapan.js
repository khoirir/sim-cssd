$(function () {
    let tanggal = sessionStorage.getItem('monitoringsuhukelembapan');
    if (tanggal) {
        let data = JSON.parse(tanggal);
        $('#tanggalAwal').val(data.tglAwal);
        $('#tanggalAkhir').val(data.tglAkhir);
    }
    tampilGrafik();
});

$(window).on('resize', function () {
    location.reload();
});

$('#formFilterDataGrafik').on('submit', function (e) {
    e.preventDefault();
    tampilGrafik();
});

function tampilGrafik() {
    let tglAwal = $('#tanggalAwal').val();
    let tglAkhir = $('#tanggalAkhir').val();
    let pesanError = [];
    if (tglAwal === "") {
        pesanError.push({
            idFeedback: "#invalidTanggalAwal",
            pesan: "Tanggal awal harus diisi",
            invalidElement: "#tanggalAwal"
        });
    }
    if (tglAkhir === "") {
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

        let form = $('#formFilterDataGrafik');
        let startDate = new Date(tglAwal);
        let endDate = new Date(tglAkhir);

        let dateRange = [];

        while (startDate <= endDate) {
            let day = startDate.getDate();
            let month = startDate.getMonth() + 1;
            let year = startDate.getFullYear().toString().slice(-2);

            let formattedDate = `${day < 10 ? "0" + day : day}/${month < 10 ? "0" + month : month}/${year}`;
            dateRange.push(formattedDate);

            startDate.setDate(startDate.getDate() + 1);
        }
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: {
                tglAwal: tglAwal,
                tglAkhir: tglAkhir
            },
            dataType: "JSON",
            success: function (res) {
                let existingChartSuhu = Chart.getChart('grafikSuhu');
                if (existingChartSuhu) {
                    existingChartSuhu.destroy();
                }
                new Chart(grafikSuhu, {
                    type: 'line',
                    data: {
                        labels: dateRange,
                        datasets: [{
                            label: 'Suhu',
                            data: res.dataGrafikSuhu,
                            borderWidth: 1,
                            borderColor: function (context) {
                                return 'rgba(128, 128, 128, 0.5)';
                            },
                            backgroundColor: function (context) {
                                let index = context.dataIndex;
                                let data = context.dataset.data;
                                if (data[index] && data[index].y) {
                                    let value = data[index].y;
                                    if (value > 30) {
                                        return 'red';
                                    } else if (value < 22) {
                                        return 'yellow';
                                    }
                                }
                                return 'green';
                            }
                        }]
                    },
                    options: {
                        aspectRatio: 4,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 50,
                                ticks: {
                                    stepSize: 5,
                                    callback: function (value, index, values) {
                                        return value + " °C";
                                    }
                                }
                            }
                        }
                    }
                });

                let existingChartKelembapan = Chart.getChart('grafikKelembapan');
                if (existingChartKelembapan) {
                    existingChartKelembapan.destroy();
                }
                new Chart(grafikKelembapan, {
                    type: 'line',
                    data: {
                        labels: dateRange,
                        datasets: [{
                            label: 'Kelembapan',
                            data: res.dataGrafikKelembapan,
                            borderWidth: 1,
                            borderColor: function (context) {
                                return 'rgba(128, 128, 128, 0.5)';
                            },
                            backgroundColor: function (context) {
                                let index = context.dataIndex;
                                let data = context.dataset.data;
                                if (data[index] && data[index].y) {
                                    let value = data[index].y;
                                    if (value > 75) {
                                        return 'red';
                                    } else if (value < 35) {
                                        return 'yellow';
                                    }
                                }
                                return 'green';
                            }
                        }]
                    },
                    options: {
                        aspectRatio: 4,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                                ticks: {
                                    stepSize: 10,
                                    callback: function (value, index, values) {
                                        return value + " %";
                                    }
                                }
                            }
                        }
                    }
                });
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
}