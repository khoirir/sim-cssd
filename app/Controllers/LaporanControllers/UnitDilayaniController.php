<?php

namespace App\Controllers\LaporanControllers;

use DateTime;
use App\Controllers\BaseController;
use App\Models\DistribusiModels\PermintaanAlatSterilModel;
use App\Models\DistribusiModels\PermintaanBmhpSterilModel;

class UnitDilayaniController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan Jumlah Unit Dilayani',
            'header' => 'Laporan Jumlah Unit Dilayani',
            'bulanAwal' => date('Y-01'),
            'bulanSekarang' => date('Y-m')
        ];
        return view('laporan/index_laporanunitdilayani_view', $data);
    }

    public function dataJumlahUnitDilayani()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $tanggalAwal = new DateTime($tglAwal);
            $tanggalAkhir = new DateTime($tglAkhir);

            $arrBulan = [
                1 => 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'
            ];

            $dataResult = [];

            $no = 1;
            while ($tanggalAwal <= $tanggalAkhir) {
                $formatBulan = $arrBulan[$tanggalAwal->format('n')];
                $formatAkhir = $formatBulan . '-' . $tanggalAwal->format('y');

                $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
                $dataSterilisai = $permintaanAlatSterilModel
                    ->dataLaporanJumlahSterilisasi($tanggalAwal->format('Y-m'))
                    ->getRowArray();

                $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
                $dataProduksi = $permintaanBmhpSterilModel
                    ->dataLaporanJumlahProduksi($tanggalAwal->format('Y-m'))
                    ->getRowArray();

                $td['no'] = $no++ . '.';
                $td['bulan'] = $formatAkhir;
                $td['sterilisasi'] = $dataSterilisai['jumlah'] ?? 0;
                $td['produksi'] = $dataProduksi['jumlah'] ?? 0;

                $dataResult[] = $td;

                $tanggalAwal->modify('first day of next month');
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
}
