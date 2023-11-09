<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <a class="btn btn-primary mb-3" href="<?= base_url('/suhu-dan-kelembapan'); ?>">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="row-md-12">
                <form action="<?= base_url('/suhu-dan-kelembapan/data-grafik'); ?>" method="POST" id="formFilterDataGrafik">
                    <?= csrf_field(); ?>
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Filter Data</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <label>Tanggal</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="tanggalAwal" id="tanggalAwal" value="<?= $tglAwal; ?>">
                                    <div class="invalid-feedback" id="invalidTanggalAwal"></div>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="tanggalAkhir" id="tanggalAkhir" value="<?= $tglSekarang; ?>">
                                    <div class="invalid-feedback" id="invalidTanggalAkhir"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-info float-right" type="submit">
                                <i class="fas fa-eye mr-2"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Monitoring Suhu</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="grafikSuhu"></canvas>
                    </div>
                    <div class="card-footer">
                        <p class="text-danger">Standar suhu ruang CSSD: 22 - 30 °C</p>
                    </div>
                </div>
            </div>
            <div class="row-md-12 mt-4">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Monitoring Kelembapan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="grafikKelembapan"></canvas>
                    </div>
                    <div class="card-footer">
                        <p class="text-danger">Standar kelembapan ruang CSSD: 35 - 75 %</p>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<script src="<?= base_url(); ?>/plugins/chart/chart.umd.min.js"></script>
<script src="<?= base_url(); ?>/js/distribusi/suhudankelembapan/grafik_suhudankelembapan.js"></script>

<?= $this->endSection(); ?>