<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('/'); ?>" class="brand-link">
        <img src="<?= base_url(); ?>/public/img/logo-karsa.jpg" alt="SIM CSSD" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SIM CSSD</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if (session()->get('id_user')) : ?>
                    <li class="nav-header">MENU</li>
                    <li class="nav-item">
                        <a href="<?= base_url('/'); ?>" class="nav-link <?= (getCurrentUri() == '') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Halaman Utama
                            </p>
                        </a>
                    </li>
                    <?php
                    $dataMasterOpen = '';
                    $dataMasterActive = '';
                    $ruangDekontaminasiOpen = '';
                    $ruangDekontaminasiActive = '';
                    $ruangSettingPackingAlatOpen = '';
                    $ruangSettingPackingAlatActive = '';
                    $ruangDistribusiOpen = '';
                    $ruangDistribusiActive = '';
                    $laporanOpen = '';
                    $laporanActive = '';

                    if (((getCurrentUri() == 'data-set-alat') || (getCurrentUri() == 'data-bmhp') || (getCurrentUri() == 'data-satuan') || (getCurrentUri() == 'data-jenis-set-alat'))) {
                        $dataMasterOpen = 'menu-open';
                        $dataMasterActive = 'active';
                    }
                    if (((getCurrentUri() == 'monitoring-suhu-kelembapan') || (getCurrentUri() == 'kepatuhan-apd') || (getCurrentUri() == 'penerimaan-alat-kotor') || (getCurrentUri() == 'uji-larutan-dtt-alkacyd'))) {
                        $ruangDekontaminasiOpen = 'menu-open';
                        $ruangDekontaminasiActive = 'active';
                    }
                    if (((getCurrentUri() == 'monitoring-packing-alat') || (getCurrentUri() == 'monitoring-mesin-steam') || (getCurrentUri() == 'monitoring-mesin-eog') || (getCurrentUri() == 'monitoring-mesin-plasma' || (getCurrentUri() == 'uji-sealer-pouchs')))) {
                        $ruangSettingPackingAlatOpen = 'menu-open';
                        $ruangSettingPackingAlatActive = 'active';
                    }
                    if (((getCurrentUri() == 'suhu-dan-kelembapan') || (getCurrentUri() == 'permintaan-alat-steril') || (getCurrentUri() == 'permintaan-bmhp-steril'))) {
                        $ruangDistribusiOpen = 'menu-open';
                        $ruangDistribusiActive = 'active';
                    }
                    if (((getCurrentUri() == 'laporan-bmhp-steril') || (getCurrentUri() == 'laporan-unit-dilayani') || (getCurrentUri() == 'laporan-sterilisasi-instrumen') || (getCurrentUri() == 'laporan-sterilisasi-kamar-operasi'))) {
                        $laporanOpen = 'menu-open';
                        $laporanActive = 'active';
                    }
                    ?>
                    <li class="nav-item <?= $dataMasterOpen; ?>">
                        <a href="#" class="nav-link <?= $dataMasterActive; ?>">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                                Data Master
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/data-set-alat'); ?>" class="nav-link <?= (getCurrentUri() == 'data-set-alat') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>Data Set Alat</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/data-bmhp'); ?>" class="nav-link <?= (getCurrentUri() == 'data-bmhp') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>Data BMHP</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/data-satuan'); ?>" class="nav-link <?= (getCurrentUri() == 'data-satuan') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>Data Satuan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/data-jenis-set-alat'); ?>" class="nav-link <?= (getCurrentUri() == 'data-jenis-set-alat') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>Data Jenis Set Alat</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-header">RUANG DEKONTAMINASI</li> -->
                    <li class="nav-item <?= $ruangDekontaminasiOpen; ?>">
                        <a href="#" class="nav-link <?= $ruangDekontaminasiActive; ?>">
                            <i class="nav-icon fas fa-shield-virus"></i>
                            <p>
                                Dekontaminasi
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/monitoring-suhu-kelembapan'); ?>" class="nav-link <?= (getCurrentUri() == 'monitoring-suhu-kelembapan') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <!-- <i class="nav-icon fas fa-thermometer-half"></i> -->
                                    <p>
                                        Suhu & Kelembapan
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/kepatuhan-apd'); ?>" class="nav-link <?= (getCurrentUri() == 'kepatuhan-apd') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fas fa-head-side-mask"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Kepatuhan APD
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/penerimaan-alat-kotor'); ?>" class="nav-link <?= (getCurrentUri() == 'penerimaan-alat-kotor') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-solid fa-kit-medical"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Penerimaan Alat Kotor
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/uji-larutan-dtt-alkacyd'); ?>" class="nav-link <?= (getCurrentUri() == 'uji-larutan-dtt-alkacyd') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-solid fa-vial-circle-check"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Uji Larutan DTT Alkacyd
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-header">RUANG SETTING PACKING ALAT</li> -->
                    <li class="nav-item <?= $ruangSettingPackingAlatOpen; ?>">
                        <a href="#" class="nav-link <?= $ruangSettingPackingAlatActive; ?>">
                            <i class="nav-icon fas fa-suitcase-medical"></i>
                            <p>
                                Setting Packing Alat
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/monitoring-packing-alat'); ?>" class="nav-link <?= (getCurrentUri() == 'monitoring-packing-alat') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-solid fa-suitcase-medical"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Monitoring Packing Alat
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/monitoring-mesin-steam'); ?>" class="nav-link <?= (getCurrentUri() == 'monitoring-mesin-steam') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-brands fa-steam-symbol"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Monitoring Mesin Steam
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/monitoring-mesin-eog'); ?>" class="nav-link <?= (getCurrentUri() == 'monitoring-mesin-eog') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-solid fa-fire-flame-simple"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Monitoring Mesin EOG
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/monitoring-mesin-plasma'); ?>" class="nav-link <?= (getCurrentUri() == 'monitoring-mesin-plasma') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa fa-bolt"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Monitoring Mesin Plasma
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/uji-sealer-pouchs'); ?>" class="nav-link <?= (getCurrentUri() == 'uji-sealer-pouchs') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-solid fa-file-circle-check"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Uji Sealer Pouchs
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-header">RUANG DISTRIBUSI</li> -->
                    <li class="nav-item <?= $ruangDistribusiOpen; ?>">
                        <a href="#" class="nav-link <?= $ruangDistribusiActive; ?>">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>
                                Distribusi
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/suhu-dan-kelembapan'); ?>" class="nav-link <?= (getCurrentUri() == 'suhu-dan-kelembapan') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fas fa-thermometer"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Suhu & Kelembapan
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/permintaan-alat-steril'); ?>" class="nav-link <?= (getCurrentUri() == 'permintaan-alat-steril') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fa-solid fa-briefcase-medical"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Permintaan Alat Steril
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/permintaan-bmhp-steril'); ?>" class="nav-link <?= (getCurrentUri() == 'permintaan-bmhp-steril') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fas fa-clipboard-list"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Permintaan BMHP Steril
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item <?= $laporanOpen; ?>">
                        <a href="#" class="nav-link <?= $laporanActive; ?>">
                            <i class="nav-icon fas fa-file-lines"></i>
                            <p>
                                Laporan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/laporan-bmhp-steril'); ?>" class="nav-link <?= (getCurrentUri() == 'laporan-bmhp-steril') ? 'active' : '' ?>">
                                    <!-- <i class="nav-icon fas fa-thermometer"></i> -->
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        BMHP Steril
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/laporan-unit-dilayani'); ?>" class="nav-link <?= (getCurrentUri() == 'laporan-unit-dilayani') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Unit Dilayani
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/laporan-sterilisasi-instrumen'); ?>" class="nav-link <?= (getCurrentUri() == 'laporan-sterilisasi-instrumen') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Sterilisasi Instrumen
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/laporan-sterilisasi-kamar-operasi'); ?>" class="nav-link <?= (getCurrentUri() == 'laporan-sterilisasi-kamar-operasi') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon" style="font-size: smaller;"></i>
                                    <p>
                                        Sterilisasi Kamar Operasi
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>