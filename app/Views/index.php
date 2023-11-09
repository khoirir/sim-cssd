<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="row row-cols-lg-2 row-cols-md-1 row-cols-sm-1">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3" style="font-size: 0.9rem;"><i class="fa-solid fa-chart-line mr-2"></i>Monitoring Suhu /<br><small><?= $bulan; ?></small></h3>
                            <ul class="nav nav-pills nav-grafik ml-auto p-3" id="nav-pills-suhu">
                                <li class="nav-item"><a class="nav-link active" data="nav-link-suhu" href="#suhu_dekontaminasi" data-toggle="tab">Ruang Dekontaminasi</a></li>
                                <li class="nav-item"><a class="nav-link" data="nav-link-suhu" href="#suhu_distribusi" data-toggle="tab">Ruang Distribusi</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane tab-pane-suhu active" id="suhu_dekontaminasi">
                                    <canvas id="grafikSuhuDekontaminasi"></canvas>
                                </div>
                                <div class="tab-pane tab-pane-suhu" id="suhu_distribusi">
                                    <canvas id="grafikSuhuDistribusi"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-danger">Standar suhu ruang CSSD: 22 - 30 °C</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3" style="font-size: 0.9rem;"><i class="fa-solid fa-chart-line mr-2"></i>Monitoring Kelembapan /<br><small><?= $bulan; ?></small></h3>
                            <ul class="nav nav-pills nav-grafik ml-auto p-3" id="nav-pills-kelembapan">
                                <li class="nav-item"><a class="nav-link active" data="nav-link-kelembapan" href="#kelembapan_dekontaminasi" data-toggle="tab">Ruang Dekontaminasi</a></li>
                                <li class="nav-item"><a class="nav-link" data="nav-link-kelembapan" href="#kelembapan_distribusi" data-toggle="tab">Ruang Distribusi</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane tab-pane-kelembapan active" id="kelembapan_dekontaminasi">
                                    <canvas id="grafikKelembapanDekontaminasi"></canvas>
                                </div>
                                <div class="tab-pane tab-pane-kelembapan" id="kelembapan_distribusi">
                                    <canvas id="grafikKelembapanDistribusi"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-danger">Standar kelembapan ruang CSSD: 35 - 75 %</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<script>
    let csrfToken = "<?= csrf_token(); ?>";
    let csrfHash = "<?= csrf_hash(); ?>";
    let url = "<?= base_url(); ?>/data-grafik";
</script>
<script src="<?= base_url(); ?>/plugins/chart/chart.umd.min.js"></script>
<script src="<?= base_url(); ?>/js/index_halaman_utama.js"></script>
<!-- /.content -->
<?= $this->endSection(); ?>