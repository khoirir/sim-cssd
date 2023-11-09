<?php

namespace App\Controllers\LaporanControllers;

use DateTime;
use App\Controllers\BaseController;
use App\Models\DistribusiModels\PermintaanAlatSterilModel;

class SterilisasiInstrumenController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan Sterilisasi Instrumen',
            'header' => 'Laporan Sterilisasi Instrumen',
            'bulanAwal' => date('Y-01'),
            'bulanSekarang' => date('Y-m')
        ];
        return view('laporan/index_laporansterilisasiinstrumen_view', $data);
    }

    public function dataLaporanSterilisasiInstrumen()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
            $dataSterilisasi = $permintaanAlatSterilModel->dataLaporanSterilisasiInstrumen($tglAwal, $tglAkhir)->getResultArray();

            $arrBulan = [
                1 => 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'
            ];

            $tempData = [];
            foreach ($dataSterilisasi as $data) {
                $tanggalAwal = new DateTime($tglAwal);
                $tanggalAkhir = new DateTime($tglAkhir);

                while ($tanggalAwal <= $tanggalAkhir) {
                    $formatBulan = $arrBulan[$tanggalAwal->format('n')];
                    $formatAkhir = $formatBulan . '-' . $tanggalAwal->format('y');

                    if (!isset($tempData[$data['ruangan']][$formatAkhir])) {
                        $tempData[$data['ruangan']][$formatAkhir] = 0;
                    }

                    if ($data['bulan'] === $tanggalAwal->format('Y-m')) {
                        $tempData[$data['ruangan']][$formatAkhir] = $data['jumlah'];
                    }
                    $tanggalAwal->modify('first day of next month');
                }
            }

            $dataResult = [];
            $no = 1;
            foreach ($tempData AS $ruangan => $data) {
                $td['no'] = $no++ . '.';
                $td['ruangan'] = $ruangan;
                foreach ($data AS $bulan => $jumlah) {
                    $td[$bulan] = $jumlah;
                }
                $dataResult[] = $td;
            }

            $data = array_slice($dataResult, (int)$start, (int)$limit);

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => count($dataResult),
                'recordsFiltered' => count($dataResult),
                'data' => $data
            ];
            return $this->response->setJSON($json);
        }
    }

    public function dataHeaderTabelLaporanSterilisasi()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";

            $tanggalAwal = new DateTime($tglAwal);
            $tanggalAkhir = new DateTime($tglAkhir);

            $arrBulan = [
                1 => 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'
            ];

            $dataBulan = [];

            while ($tanggalAwal <= $tanggalAkhir) {
                $formatBulan = $arrBulan[$tanggalAwal->format('n')];
                $formatAkhir = $formatBulan . '-' . $tanggalAwal->format('y');
                $dataBulan[$formatAkhir] = $formatAkhir;
                $tanggalAwal->modify('first day of next month');
            }

            $json = [
                'sukses' => true,
                'data' => $dataBulan
            ];

            return $this->response->setJSON($json);
        }
    }
}
