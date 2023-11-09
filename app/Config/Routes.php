<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HalamanUtamaController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(static function () {
    $data = [
        'title' => '404',
        'header' => '404 Error Page'
    ];
    return view('404', $data);
});
$routes->addPlaceholder('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->group('', ['namespace' => 'App\Controllers\AuthControllers'], static function ($routes) {
    $routes->get('/login', 'AuthController');
    $routes->post('/attempt-login', 'AuthController::attemptLogin');
    $routes->get('/logout', 'AuthController::logout', ['filter' => 'loginFilter']);
});
$routes->group('', ['filter' => 'loginFilter'], static function ($routes) {
    $routes->get('/', 'HalamanUtamaController::index');
    $routes->get('/data-grafik/(:segment)', 'HalamanUtamaController::dataGrafik/$1');
});
// $routes->group('', ['namespace' => 'App\Controllers\HalamanUtamaController', 'filter' => 'loginFilter'], static function ($routes){
//     $routes->get('/', 'HalamanUtamaController', ['filter' => 'loginFilter']);
//     $routes->get('/data-suhu/(:segment)', 'HalamanUtamaController::dataSuhu/$1', ['filter' => 'loginFilter']);
// });
$routes->group('', ['namespace' => 'App\Controllers\DataControllers', 'filter' => 'loginFilter'], static function ($routes) {
    $routes->group('data-set-alat', static function ($routes) {
        $routes->get('/', 'DataSetAlatController');
        $routes->post('data', 'DataSetAlatController::dataSetAlat');
        $routes->get('tambah', 'DataSetAlatController::tambahSetAlat');
        $routes->post('simpan', 'DataSetAlatController::simpanSetAlat');
        $routes->get('edit/(:uuid)', 'DataSetAlatController::editSetAlat/$1');
        $routes->post('update/(:uuid)', 'DataSetAlatController::updateSetAlat/$1');
        $routes->delete('hapus/(:uuid)', 'DataSetAlatController::hapusSetAlat/$1');
    });
    $routes->group('data-bmhp', static function ($routes) {
        $routes->get('/', 'DataBmhpController');
        $routes->post('data', 'DataBmhpController::dataBmhp');
        $routes->get('tambah', 'DataBmhpController::tambahBmhp');
        $routes->post('simpan', 'DataBmhpController::simpanBmhp');
        $routes->get('edit/(:uuid)', 'DataBmhpController::editBmhp/$1');
        $routes->post('update/(:uuid)', 'DataBmhpController::updateBmhp/$1');
        $routes->delete('hapus/(:uuid)', 'DataBmhpController::hapusBmhp/$1');
    });
    $routes->group('data-satuan', static function ($routes) {
        $routes->get('/', 'DataSatuanController');
        $routes->post('data', 'DataSatuanController::dataSatuan');
        $routes->get('tambah', 'DataSatuanController::tambahSatuan');
        $routes->post('simpan', 'DataSatuanController::simpanSatuan');
        $routes->get('edit/(:uuid)', 'DataSatuanController::editSatuan/$1');
        $routes->post('update/(:uuid)', 'DataSatuanController::updateSatuan/$1');
        $routes->delete('hapus/(:uuid)', 'DataSatuanController::hapusSatuan/$1');
    });
    $routes->group('data-jenis-set-alat', static function ($routes) {
        $routes->get('/', 'DataJenisSetAlatController');
        $routes->post('data', 'DataJenisSetAlatController::dataJenisSetAlat');
        $routes->get('tambah', 'DataJenisSetAlatController::tambahJenisSetAlat');
        $routes->post('simpan', 'DataJenisSetAlatController::simpanJenisSetAlat');
        $routes->get('edit/(:uuid)', 'DataJenisSetAlatController::editJenisSetAlat/$1');
        $routes->post('update/(:uuid)', 'DataJenisSetAlatController::updateJenisSetAlat/$1');
        $routes->delete('hapus/(:uuid)', 'DataJenisSetAlatController::hapusJenisSetAlat/$1');
    });
});
$routes->group('', ['namespace' => 'App\Controllers\DekontaminasiControllers', 'filter' => 'loginFilter'], static function ($routes) {
    $routes->group('monitoring-suhu-kelembapan', static function ($routes) {
        $routes->get('/', 'MonitoringSuhuKelembapanController');
        $routes->post('suhu-kelembapan-berdasarkan-tanggal', 'MonitoringSuhuKelembapanController::dataSuhuKelembapanBerdasarkanTanggal');
        $routes->get('tambah', 'MonitoringSuhuKelembapanController::tambahSuhuKelembapan');
        $routes->post('validasi-tanggal/(:any)', 'MonitoringSuhuKelembapanController::validasiTanggal/$1');
        $routes->post('simpan', 'MonitoringSuhuKelembapanController::simpanSuhuKelembapan');
        $routes->get('edit/(:uuid)', 'MonitoringSuhuKelembapanController::editSuhuKelembapan/$1');
        $routes->post('update/(:uuid)', 'MonitoringSuhuKelembapanController::updateSuhuKelembapan/$1');
        $routes->delete('hapus/(:uuid)', 'MonitoringSuhuKelembapanController::hapusSuhuKelembapan/$1');
        $routes->get('grafik', 'MonitoringSuhuKelembapanController::grafikMonitoring');
        $routes->post('data-grafik', 'MonitoringSuhuKelembapanController::dataGrafikMonitoring');
        $routes->get('tambah-tindakan/(:uuid)', 'MonitoringSuhuKelembapanController::tindakan/$1');
        $routes->post('simpan-tindakan/(:uuid)', 'MonitoringSuhuKelembapanController::simpanTindakan/$1');
        $routes->delete('hapus-tindakan/(:uuid)', 'MonitoringSuhuKelembapanController::hapusTindakan/$1');
    });
    $routes->group('kepatuhan-apd', static function ($routes) {
        $routes->get('/', 'KepatuhanApdController');
        $routes->post('kepatuhan-apd-berdasarkan-tanggal', 'KepatuhanApdController::dataKepatuhanApdBerdasarkanTanggal');
        $routes->get('tambah', 'KepatuhanApdController::tambahKepatuhanAPD');
        $routes->get('detail/(:uuid)', 'KepatuhanApdController::detailKepatuhanAPD/$1');
        $routes->post('simpan', 'KepatuhanApdController::simpanKepatuhanAPD');
        $routes->get('edit/(:uuid)', 'KepatuhanApdController::editKepatuhanAPD/$1');
        $routes->post('update/(:uuid)', 'KepatuhanApdController::updateKepatuhanAPD/$1');
        $routes->delete('hapus/(:uuid)', 'KepatuhanApdController::hapusKepatuhanAPD/$1');
    });
    $routes->group('penerimaan-alat-kotor', static function ($routes) {
        $routes->get('/', 'PenerimaanAlatKotorController::index');
        $routes->post('data-penerimaan-alat-kotor-berdasarkan-filter', 'PenerimaanAlatKotorController::dataPenerimaanAlatKotorBerdasarkanFilter');
        $routes->post('data-alat-kotor', 'PenerimaanAlatKotorController::dataAlatKotorBerdasarkanIdRuanganDanMesin');
        $routes->post('dokumentasi', 'PenerimaanAlatKotorController::dokumentasi');
        $routes->post('upload', 'PenerimaanAlatKotorController::uploadDokumentasi');
        $routes->delete('hapus-dokumentasi/(:uuid)', 'PenerimaanAlatKotorController::hapusDokumentasi/$1');
        $routes->get('detail/(:uuid)', 'PenerimaanAlatKotorController::detailPenerimaanAlatKotor/$1');
        $routes->get('tambah', 'PenerimaanAlatKotorController::tambahPenerimaanAlatKotor');
        $routes->post('simpan', 'PenerimaanAlatKotorController::simpanPenerimaanAlatKotor');
        $routes->get('edit/(:uuid)', 'PenerimaanAlatKotorController::editPenerimaanAlatKotor/$1');
        $routes->post('update/(:uuid)', 'PenerimaanAlatKotorController::updatePenerimaanAlatKotor/$1');
        $routes->delete('hapus/(:uuid)', 'PenerimaanAlatKotorController::hapusPenerimaanAlatKotor/$1');
    });
    $routes->group('uji-larutan-dtt-alkacyd', static function ($routes) {
        $routes->get('/', 'UjiLarutanController::index');
        $routes->post('data-uji-larutan', 'UjiLarutanController::dataUjiLarutanDenganFilter');
        $routes->get('gambar-uji-larutan/(:uuid)', 'UjiLarutanController::gambarUjiLarutan/$1');
        $routes->get('tambah', 'UjiLarutanController::tambahUjiLarutan');
        $routes->post('validasi-form', 'UjiLarutanController::validasiForm');
        $routes->post('simpan', 'UjiLarutanController::simpanUjiLarutan');
        $routes->get('edit/(:uuid)', 'UjiLarutanController::editUjiLarutan/$1');
        $routes->post('update/(:uuid)', 'UjiLarutanController::updateUjiLarutan/$1');
        $routes->delete('hapus/(:uuid)', 'UjiLarutanController::hapusUjiLarutan/$1');
    });
});
$routes->group('', ['namespace' => 'App\Controllers\PackingalatControllers', 'filter' => 'loginFilter'], static function ($routes) {
    $routes->group('monitoring-packing-alat', static function ($routes) {
        $routes->get('/', 'MonitoringPackingAlatController::index');
        $routes->post('data-berdasarkan-tanggal', 'MonitoringPackingAlatController::dataMonitoringPackingAlatBerdasarkanTanggal');
        $routes->get('detail/(:uuid)', 'MonitoringPackingAlatController::detailMonitoringPackingAlat/$1');
        $routes->get('tambah', 'MonitoringPackingAlatController::tambahMonitoringPackingAlat');
        $routes->post('simpan', 'MonitoringPackingAlatController::simpanMonitoringPackingAlat');
        $routes->get('edit/(:uuid)', 'MonitoringPackingAlatController::editMonitoringPackingAlat/$1');
        $routes->post('update/(:uuid)', 'MonitoringPackingAlatController::updateMonitoringPackingAlat/$1');
        $routes->delete('hapus/(:uuid)', 'MonitoringPackingAlatController::hapusMonitoringPackingAlat/$1');
    });
    $routes->group('monitoring-mesin-steam', static function ($routes) {
        $routes->get('/', 'MonitoringMesinSteamController::index');
        $routes->post('data-monitoring-mesin-steam-berdasarkan-tanggal', 'MonitoringMesinSteamController::dataMonitoringMesinSteamBerdasarkanTanggal');
        $routes->get('detail/(:uuid)', 'MonitoringMesinSteamController::detailMonitoringMesinSteam/$1');
        $routes->get('tambah', 'MonitoringMesinSteamController::tambahMonitoringMesinSteam');
        $routes->post('simpan', 'MonitoringMesinSteamController::simpanMonitoringMesinSteam');
        $routes->get('edit/(:uuid)', 'MonitoringMesinSteamController::editMonitoringMesinSteam/$1');
        $routes->post('update/(:uuid)', 'MonitoringMesinSteamController::updateMonitoringMesinSteam/$1');
        $routes->get('proses-ulang/(:uuid)', 'MonitoringMesinSteamController::prosesUlangMonitoringMesinSteam/$1');
        $routes->delete('hapus/(:uuid)', 'MonitoringMesinSteamController::hapusMonitoringMesinSteam/$1');
        $routes->delete('hapus-detail/(:uuid)', 'MonitoringMesinSteamController::hapusDetailMonitoringMesinSteam/$1');
        $routes->post('batal-hapus-detail/(:uuid)', 'MonitoringMesinSteamController::batalHapusDetailMonitoringMesinSteam/$1');
        $routes->get('verifikasi/(:uuid)', 'MonitoringMesinSteamController::verifikasiMonitoringMesinSteam/$1');
        $routes->post('simpan-verifikasi/(:uuid)', 'MonitoringMesinSteamController::simpanVerifikasi/$1');
        $routes->delete('hapus-verifikasi/(:uuid)', 'MonitoringMesinSteamController::hapusVerifikasi/$1');
    });
    $routes->group('monitoring-mesin-eog', static function ($routes) {
        $routes->get('/', 'MonitoringMesinEogController::index');
        $routes->post('data-monitoring-mesin-eog-berdasarkan-tanggal', 'MonitoringMesinEogController::dataMonitoringMesinEogBerdasarkanTanggal');
        $routes->get('detail/(:uuid)', 'MonitoringMesinEogController::detailMonitoringMesinEog/$1');
        $routes->get('tambah', 'MonitoringMesinEogController::tambahMonitoringMesinEog');
        $routes->post('simpan', 'MonitoringMesinEogController::simpanMonitoringMesinEog');
        $routes->get('edit/(:uuid)', 'MonitoringMesinEogController::editMonitoringMesinEog/$1');
        $routes->post('update/(:uuid)', 'MonitoringMesinEogController::updateMonitoringMesinEog/$1');
        $routes->get('proses-ulang/(:uuid)', 'MonitoringMesinEogController::prosesUlangMonitoringMesinEog/$1');
        $routes->delete('hapus/(:uuid)', 'MonitoringMesinEogController::hapusMonitoringMesinEog/$1');
        $routes->delete('hapus-detail/(:uuid)', 'MonitoringMesinEogController::hapusDetailMonitoringMesinEog/$1');
        $routes->post('batal-hapus-detail/(:uuid)', 'MonitoringMesinEogController::batalHapusDetailMonitoringMesinEog/$1');
        $routes->get('verifikasi/(:uuid)', 'MonitoringMesinEogController::verifikasiMonitoringMesinEog/$1');
        $routes->post('simpan-verifikasi/(:uuid)', 'MonitoringMesinEogController::simpanVerifikasi/$1');
        $routes->delete('hapus-verifikasi/(:uuid)', 'MonitoringMesinEogController::hapusVerifikasi/$1');
    });
    $routes->group('monitoring-mesin-plasma', static function ($routes) {
        $routes->get('/', 'MonitoringMesinPlasmaController::index');
        $routes->post('data-monitoring-mesin-plasma-berdasarkan-tanggal', 'MonitoringMesinPlasmaController::dataMonitoringMesinPlasmaBerdasarkanTanggal');
        $routes->get('detail/(:uuid)', 'MonitoringMesinPlasmaController::detailMonitoringMesinPlasma/$1');
        $routes->get('tambah', 'MonitoringMesinPlasmaController::tambahMonitoringMesinPlasma');
        $routes->post('simpan', 'MonitoringMesinPlasmaController::simpanMonitoringMesinPlasma');
        $routes->get('edit/(:uuid)', 'MonitoringMesinPlasmaController::editMonitoringMesinPlasma/$1');
        $routes->post('update/(:uuid)', 'MonitoringMesinPlasmaController::updateMonitoringMesinPlasma/$1');
        $routes->get('proses-ulang/(:uuid)', 'MonitoringMesinPlasmaController::prosesUlangMonitoringMesinPlasma/$1');
        $routes->delete('hapus/(:uuid)', 'MonitoringMesinPlasmaController::hapusMonitoringMesinPlasma/$1');
        $routes->delete('hapus-detail/(:uuid)', 'MonitoringMesinPlasmaController::hapusDetailMonitoringMesinPlasma/$1');
        $routes->post('batal-hapus-detail/(:uuid)', 'MonitoringMesinPlasmaController::batalHapusDetailMonitoringMesinPlasma/$1');
        $routes->get('verifikasi/(:uuid)', 'MonitoringMesinPlasmaController::verifikasiMonitoringMesinPlasma/$1');
        $routes->post('simpan-verifikasi/(:uuid)', 'MonitoringMesinPlasmaController::simpanVerifikasi/$1');
        $routes->delete('hapus-verifikasi/(:uuid)', 'MonitoringMesinPlasmaController::hapusVerifikasi/$1');
    });
    $routes->group('uji-sealer-pouchs', static function ($routes) {
        $routes->get('/', 'UjiSealerPouchsController::index');
        $routes->post('data-uji-sealer-pouchs', 'UjiSealerPouchsController::dataUjiSealerPouchs');
        $routes->get('tambah', 'UjiSealerPouchsController::tambahUjiSealerPouchs');
        $routes->get('gambar-uji-sealer/(:uuid)', 'UjiSealerPouchsController::gambarUjiSealerPouchs/$1');
        $routes->post('simpan', 'UjiSealerPouchsController::simpanUjiSealerPouchs');
        $routes->post('validasi-form', 'UjiSealerPouchsController::validasiForm');
        $routes->get('edit/(:uuid)', 'UjiSealerPouchsController::editUjiSealerPouchs/$1');
        $routes->post('update/(:uuid)', 'UjiSealerPouchsController::updateUjiSealerPouchs/$1');
        $routes->delete('hapus/(:uuid)', 'UjiSealerPouchsController::hapusUjiSealerPouchs/$1');
    });
});
$routes->group('', ['namespace' => 'App\Controllers\DistribusiControllers', 'filter' => 'loginFilter'], static function ($routes) {
    $routes->group('suhu-dan-kelembapan', static function ($routes) {
        $routes->get('/', 'SuhuDanKelembapanController::index');
        $routes->post('suhu-kelembapan-berdasarkan-tanggal', 'SuhuDanKelembapanController::dataSuhuKelembapanBerdasarkanTanggal');
        $routes->get('tambah', 'SuhuDanKelembapanController::tambahSuhuKelembapan');
        $routes->post('validasi-tanggal/(:any)', 'SuhuDanKelembapanController::validasiTanggal/$1');
        $routes->post('simpan', 'SuhuDanKelembapanController::simpanSuhuKelembapan');
        $routes->get('edit/(:uuid)', 'SuhuDanKelembapanController::editSuhuKelembapan/$1');
        $routes->post('update/(:uuid)', 'SuhuDanKelembapanController::updateSuhuKelembapan/$1');
        $routes->delete('hapus/(:uuid)', 'SuhuDanKelembapanController::hapusSuhuKelembapan/$1');
        $routes->get('grafik', 'SuhuDanKelembapanController::grafikMonitoring');
        $routes->post('data-grafik', 'SuhuDanKelembapanController::dataGrafikMonitoring');
        $routes->get('tambah-tindakan/(:uuid)', 'SuhuDanKelembapanController::tindakan/$1');
        $routes->post('simpan-tindakan/(:uuid)', 'SuhuDanKelembapanController::simpanTindakan/$1');
        $routes->delete('hapus-tindakan/(:uuid)', 'SuhuDanKelembapanController::hapusTindakan/$1');
    });
    $routes->group('permintaan-alat-steril', static function ($routes) {
        $routes->get('/', 'PermintaanAlatSterilController::index');
        $routes->post('data-permintaan-alat-steril', 'PermintaanAlatSterilController::dataPermintaanAlatSterilBerdasarkanFilter');
        $routes->get('detail/(:uuid)', 'PermintaanAlatSterilController::detailPermintaanAlatSteril/$1');
        $routes->get('tambah', 'PermintaanAlatSterilController::tambahPermintaanAlatSteril');
        $routes->get('data-alat-steril/(:segment)', 'PermintaanAlatSterilController::dataAlatSteril/$1');
        $routes->post('simpan', 'PermintaanAlatSterilController::simpanPermintaanAlatSteril');
        $routes->get('edit/(:uuid)', 'PermintaanAlatSterilController::editPermintaanAlatSteril/$1');
        $routes->delete('hapus-detail/(:uuid)', 'PermintaanAlatSterilController::hapusDetailPermintaanAlatSteril/$1');
        $routes->post('batal-hapus-detail/(:uuid)', 'PermintaanAlatSterilController::batalHapusDetailPermintaanAlatSteril/$1');
        $routes->post('update/(:uuid)', 'PermintaanAlatSterilController::updatePermintaanAlatSteril/$1');
        $routes->delete('hapus/(:uuid)', 'PermintaanAlatSterilController::hapusPermintaanAlatSteril/$1');
    });
    $routes->group('permintaan-bmhp-steril', static function ($routes) {
        $routes->get('/', 'PermintaanBmhpSterilController::index');
        $routes->post('data-permintaan-bmhp-steril', 'PermintaanBmhpSterilController::dataPermintaanBmhpSterilBerdasarkanFilter');
        $routes->get('detail/(:uuid)', 'PermintaanBmhpSterilController::detailPermintaanBmhpSteril/$1');
        $routes->get('data-bmhp-steril', 'PermintaanBmhpSterilController::dataBmhpSteril');
        $routes->get('tambah', 'PermintaanBmhpSterilController::tambahPermintaanBmhpSteril');
        $routes->post('simpan', 'PermintaanBmhpSterilController::simpanPermintaanBmhpSteril');
        $routes->get('edit/(:uuid)', 'PermintaanBmhpSterilController::editPermintaanBmhpSteril/$1');
        $routes->post('update/(:uuid)', 'PermintaanBmhpSterilController::updatePermintaanBmhpSteril/$1');
        $routes->delete('hapus/(:uuid)', 'PermintaanBmhpSterilController::hapusPermintaanBmhpSteril/$1');
        $routes->delete('hapus-detail/(:uuid)', 'PermintaanBmhpSterilController::hapusDetailPermintaanBmhpSteril/$1');
        $routes->post('batal-hapus-detail/(:uuid)', 'PermintaanBmhpSterilController::batalHapusDetailPermintaanBmhpSteril/$1');
    });
});
$routes->group('', ['namespace' => 'App\Controllers\LaporanControllers', 'filter' => 'loginFilter'], static function ($routes) {
    $routes->group('laporan-bmhp-steril', static function ($routes) {
        $routes->get('/', 'BmhpSterilController::index');
        $routes->post('data-permintaan/(:segment)', 'BmhpSterilController::dataLaporanPermintaanBmhp/$1');
        $routes->post('data-header-tabel-laporan-bmhp/(:segment)', 'BmhpSterilController::dataHeaderTabelLaporanBmhp/$1');
    });
    $routes->group('laporan-unit-dilayani', static function ($routes) {
        $routes->get('/', 'UnitDilayaniController::index');
        $routes->post('data-jumlah-dilayani', 'UnitDilayaniController::dataJumlahUnitDilayani');
    });
    $routes->group('laporan-sterilisasi-instrumen', static function ($routes) {
        $routes->get('/', 'SterilisasiInstrumenController::index');
        $routes->post('data-header-tabel-laporan-sterilisasi', 'SterilisasiInstrumenController::dataHeaderTabelLaporanSterilisasi');
        $routes->post('data-permintaan', 'SterilisasiInstrumenController::dataLaporanSterilisasiInstrumen');
    });
    $routes->group('laporan-sterilisasi-kamar-operasi', static function ($routes) {
        $routes->get('/', 'SterilisasiKamarOperasiController::index');
        $routes->post('data-permintaan', 'SterilisasiKamarOperasiController::dataLaporanSterilisasiKamarOperasi');
    });
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
