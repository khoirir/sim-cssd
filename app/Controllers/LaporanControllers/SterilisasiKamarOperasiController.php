<?php

namespace App\Controllers\LaporanControllers;

use DateTime;
use App\Controllers\BaseController;
use App\Models\DistribusiModels\PermintaanAlatSterilModel;

class SterilisasiKamarOperasiController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan Sterilisasi Kamar Operasi',
            'header' => 'Laporan Sterilisasi Kamar Operasi',
            'bulanAwal' => date('Y-01'),
            'bulanSekarang' => date('Y-m')
        ];
        return view('laporan/index_laporansterilisasikamaroperasi_view', $data);
    }

    public function dataLaporanSterilisasiKamarOperasi()
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
                $dataSterilisaiKamarOperasi = $permintaanAlatSterilModel
                    ->dataLaporanSterilisasiKamarOperasi($tanggalAwal->format('Y-m'))
                    ->getRowArray();

                $td['no'] = $no++ . '.';
                $td['bulan'] = $formatAkhir;
                $td['set'] = $dataSterilisaiKamarOperasi['set'] ?? 0;
                $td['pouches'] = $dataSterilisaiKamarOperasi['pouches'] ?? 0;
                $td['linen'] = $dataSterilisaiKamarOperasi['linen'] ?? 0;

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
