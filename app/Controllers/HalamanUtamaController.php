<?php

namespace App\Controllers;

use App\Models\DekontaminasiModels\MonitoringSuhuKelembapanModel;
use App\Models\DistribusiModels\SuhuDanKelembapanModel;
use DateTime;

class HalamanUtamaController extends BaseController
{
    public function index()
    {
        $arrBulan = [
            1 => 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'
        ];
        $tanggal = new DateTime(date("Y-m-d"));
        $formatBulan = $arrBulan[$tanggal->format('n')];
        $formatAkhir = $formatBulan . ' ' . $tanggal->format('2023');
        $data = [
            'title' => 'Halaman Utama',
            'header' => 'Halaman Utama',
            'bulan' => $formatAkhir
        ];
        return view('index', $data);
    }

    public function dataGrafik($dataParam)
    {
        if ($this->request->isAJAX()) {
            $listParam = [
                "suhu_dekontaminasi",
                "suhu_distribusi",
                "kelembapan_dekontaminasi",
                "kelembapan_distribusi"
            ];

            if (in_array($dataParam, $listParam)) {
                $tglAwal = date("Y-m-01");
                $tglAkhir = date("Y-m-t", strtotime($tglAwal));

                $tanggalAwal = new DateTime($tglAwal);
                $tanggalAkhir = new DateTime($tglAkhir);

                $dataTanggal = [];
                while ($tanggalAwal <= $tanggalAkhir) {
                    $formatAkhir = $tanggalAwal->format('d/m/y');
                    $dataTanggal[] = $formatAkhir;
                    $tanggalAwal->modify('+1 day');
                }

                $param = explode("_", $dataParam);
                $ruangan = $param[1];
                $jenis = $param[0];

                $dataSuhuKelembapanBerdasarkanTanggal = [];

                if ($ruangan === 'dekontaminasi') {
                    $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
                    $dataSuhuKelembapanBerdasarkanTanggal = $monitoringSuhuKelembapanModel
                        ->dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)
                        ->getResultArray();
                }
                if ($ruangan === 'distribusi') {
                    $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
                    $dataSuhuKelembapanBerdasarkanTanggal = $suhuDanKelembapanModel
                        ->dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)
                        ->getResultArray();
                }
                $dataNilai = [];
                foreach ($dataSuhuKelembapanBerdasarkanTanggal as $data) {
                    if ($jenis === 'suhu') {
                        array_push($dataNilai, ['x' => date("d/m/y", strtotime($data['tgl_catat'])), 'y' => $data['suhu']]);
                    }
                    if ($jenis === 'kelembapan') {
                        array_push($dataNilai, ['x' => date("d/m/y", strtotime($data['tgl_catat'])), 'y' => $data['kelembapan']]);
                    }
                }
                return $this->response->setJSON([
                    "sukses" => true,
                    "data" => [
                        "tanggal" => $dataTanggal,
                        "nilai" => $dataNilai
                    ]
                ]);
            }
            return $this->response->setJSON(
                [
                    'sukses' => false,
                    'pesan' => [
                        'judul' => 'Gagal',
                        'teks' => 'Data Parameter Tidak Tersedia'
                    ]
                ]
            );
        }
    }
}
