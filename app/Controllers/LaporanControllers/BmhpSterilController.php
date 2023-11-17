<?php

namespace App\Controllers\LaporanControllers;

use DateTime;
use App\Controllers\BaseController;
use App\Models\DataModels\DataBmhpModel;
use App\Models\DistribusiModels\PermintaanBmhpSterilModel;

class BmhpSterilController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan Permintaan BMHP Steril',
            'header' => 'Laporan Permintaan BMHP/Kasa Steril',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
            'bulanAwal' => date('Y-01'),
            'bulanSekarang' => date('Y-m')
        ];
        return view('laporan/index_laporanbmhpsteril_view', $data);
    }

    public function dataLaporanPermintaanBmhp($jenisLaporan)
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $dataBmhpModel = model(DataBmhpModel::class);
            $dataBmhp = $dataBmhpModel
            ->dataBmhpBerdasarkanLimit($start, $limit)
            ->getResultArray();
            
            $jumlahDataBmhp = $dataBmhpModel
            ->dataBmhp()
            ->getNumRows();


            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $dataResult = [];
            $no = $start + 1;
            foreach ($dataBmhp as $bmhp) {
                $td['no'] = $no++ . ".";
                $td['bmhp'] = $bmhp['nama_set_alat'];
                if ($jenisLaporan === "tab_per_ruangan") {
                    $td['harga'] = "Rp" . number_format($bmhp['harga'], 2, ',', '.');
                    $td['-'] = "-";
                    $dataLaporanPermintaanBmhp = $permintaanBmhpSterilModel
                        ->dataLaporanPermintaanBmhpPerRuangan($tglAwal, $tglAkhir)
                        ->getResultArray();

                    if ($dataLaporanPermintaanBmhp) {
                        $tempData = [];
                        foreach ($dataLaporanPermintaanBmhp as $data) {
                            if (!isset($tempData[$data['id_ruangan']])) {
                                $tempData[$data['id_ruangan']] = [
                                    'jumlah' => 0,
                                    'total' => 0.00
                                ];
                            }
                            if ($data['id_bmhp'] === $bmhp['id']) {
                                if ($tempData[$data['id_ruangan']]['jumlah'] === 0) {
                                    $tempData[$data['id_ruangan']] = [
                                        'jumlah' => $data['jumlah'],
                                        'total' => $data['jumlah'] * (float)$data['harga']
                                    ];
                                }
                            }
                        }

                        foreach ($tempData as $idRuangan => $data) {
                            $td['jumlah' . $idRuangan] = $data['jumlah'];
                            $td[$idRuangan] = "Rp" . number_format($data['total'], 2, ',', '.');
                        }
                    }
                }
                if ($jenisLaporan === "tab_per_bulan") {
                    $td['satuan'] = $bmhp['kode_satuan'];
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
                    $tempData = array_combine($dataBulan, array_fill_keys($dataBulan, 0));

                    $dataLaporanPermintaanBmhpPerBulan = $permintaanBmhpSterilModel
                        ->dataLaporanPermintaanBmhpPerBulan($tglAwal, $tglAkhir)
                        ->getResultArray();

                    if ($dataLaporanPermintaanBmhpPerBulan) {
                        foreach ($dataLaporanPermintaanBmhpPerBulan as $data) {
                            $dataBulan = new DateTime($data['bulan']);
                            $formatBulan = $arrBulan[$dataBulan->format('n')];
                            $bulan = $formatBulan . '-' . $dataBulan->format('y');

                            if ($data['id_bmhp'] === $bmhp['id']) {
                                if ($tempData[$bulan] === 0) {
                                    $tempData[$bulan] = $data['jumlah'];
                                }
                            }
                        }
                    }
                    foreach ($tempData as $bulan => $data) {
                        $td[$bulan] = $data;
                    }
                }

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataBmhp,
                'recordsFiltered' => $jumlahDataBmhp,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function dataHeaderTabelLaporanBmhp($jenisLaporan)
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            if ($jenisLaporan === "tab_per_ruangan") {
                $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
                $dataRuangan = $permintaanBmhpSterilModel
                    ->dataLaporanPermintaanBmhpPerRuangan($tglAwal, $tglAkhir)
                    ->getResultArray();

                if (!$dataRuangan) {
                    return $this->response->setJSON(
                        [
                            'sukses' => true,
                            'data' => ["-" => "-"]
                        ],
                    );
                }

                $dataResult = [];
                foreach ($dataRuangan as $ruangan) {
                    if (!array_key_exists($ruangan['id_ruangan'], $dataResult)) {
                        $dataResult['jumlah' . $ruangan['id_ruangan']] = "Jumlah";
                        $dataResult[$ruangan['id_ruangan']] = $ruangan['ruangan'];
                    }
                }
                $json = [
                    'sukses' => true,
                    'data' => $dataResult
                ];
                return $this->response->setJSON($json);
            }

            if ($jenisLaporan === "tab_per_bulan") {
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

            return $this->response->setJSON(
                [
                    'sukses' => false,
                    'pesan' => [
                        'judul' => 'Gagal',
                        'teks' => 'Laporan Tidak Tersedia'
                    ]
                ]
            );
        }
    }
}
