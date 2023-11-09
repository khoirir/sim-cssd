$(function () {
    getDataGrafik('suhu_dekontaminasi');
    getDataGrafik('kelembapan_dekontaminasi');
    $("ul.nav-grafik a.nav-link").click(function () {
        if ($(this).attr("data") === 'nav-link-suhu') {
            $("ul#nav-pills-suhu a.nav-link, .tab-pane-suhu").removeClass("active");
        } else if ($(this).attr("data") === 'nav-link-kelembapan') {
            $("ul#nav-pills-kelembapan a.nav-link, .tab-pane-kelembapan").removeClass("active");
        }

        $(this).addClass("active");
        let selectedTab = $(this).attr("href");
        $(selectedTab).addClass("active");
        let tabAktif = $(selectedTab).attr('id');

        getDataGrafik(tabAktif);
    });
});

$(window).on('resize', function () {
    location.reload();
});

function getDataGrafik(dataTab) {
    const dataParam = {
        'suhu_dekontaminasi': {
            'idGrafik': 'grafikSuhuDekontaminasi',
            'label': 'Suhu Ruang Dekontaminasi',
            'nilaiMaks': 30,
            'nilaiMin': 22,
            'skalaMaksY': 50,
            'stepSize': 5,
            'satuan': ' °C'
        },
        'suhu_distribusi': {
            'idGrafik': 'grafikSuhuDistribusi',
            'label': 'Suhu Ruang Distribusi',
            'nilaiMaks': 30,
            'nilaiMin': 22,
            'skalaMaksY': 50,
            'stepSize': 5,
            'satuan': ' °C'
        },
        'kelembapan_dekontaminasi': {
            'idGrafik': 'grafikKelembapanDekontaminasi',
            'label': 'Kelembapan Ruang Dekontaminasi',
            'nilaiMaks': 75,
            'nilaiMin': 35,
            'skalaMaksY': 100,
            'stepSize': 10,
            'satuan': ' %'
        },
        'kelembapan_distribusi': {
            'idGrafik': 'grafikKelembapanDistribusi',
            'label': 'Kelembapan Ruang Distribusi',
            'nilaiMaks': 75,
            'nilaiMin': 35,
            'skalaMaksY': 100,
            'stepSize': 10,
            'satuan': ' %'
        }
    }
    $.ajax({
        type: 'GET',
        url: url + '/' + dataTab,
        dataType: "JSON",
        success: function (res) {
            if (res.sukses) {
                let existingChart = Chart.getChart(dataParam[dataTab].idGrafik);
                if (existingChart) {
                    existingChart.destroy();
                }
                new Chart(dataParam[dataTab].idGrafik, {
                    type: 'line',
                    data: {
                        labels: res.data.tanggal,
                        datasets: [{
                            label: dataParam[dataTab].label,
                            data: res.data.nilai,
                            borderWidth: 1,
                            borderColor: function (context) {
                                return 'rgba(128, 128, 128, 0.5)';
                            },
                            backgroundColor: function (context) {
                                let index = context.dataIndex;
                                let data = context.dataset.data;
                                if (data[index] && data[index].y) {
                                    let value = data[index].y;
                                    if (value > dataParam[dataTab].nilaiMaks) {
                                        return 'red';
                                    } else if (value < dataParam[dataTab].nilaiMin) {
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
                                max: dataParam[dataTab].skalaMaksY,
                                ticks: {
                                    stepSize: dataParam[dataTab].stepSize,
                                    callback: function (value, index, values) {
                                        return value + dataParam[dataTab].satuan;
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                swalWithBootstrapButtons.fire(
                    res.pesan.judul,
                    res.pesan.teks + "<br>Silahkan Hubungi Tim IT",
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