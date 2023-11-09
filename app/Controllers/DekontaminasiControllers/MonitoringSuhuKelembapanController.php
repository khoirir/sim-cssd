<?php

namespace App\Controllers\DekontaminasiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\PegawaiModel;
use App\Models\DekontaminasiModels\MonitoringSuhuKelembapanModel;

class MonitoringSuhuKelembapanController extends BaseController
{
    protected $valid;
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $tglAwal = date('Y-m-01');;
        $tglSekarang = date('Y-m-d');
        $data = [
            'title' => 'Suhu & Kelembapan',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Dekontaminasi',
            'tglAwal' => $tglAwal,
            'tglSekarang' => $tglSekarang,
        ];
        return view('dekontaminasi/monitoringsuhukelembapan/index_suhu_view', $data);
    }

    public function dataSuhuKelembapanBerdasarkanTanggal()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal');
            $tglAkhir = $this->request->getPost('tglAkhir');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $dataSuhuKelembapanBerdasarkanTanggal = $monitoringSuhuKelembapanModel
                ->dataSuhuKelembapanBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit);
            $jumlahDataSuhuKelembapanBerdasarkanTanggal = $monitoringSuhuKelembapanModel
                ->dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataSuhuKelembapanBerdasarkanTanggal->getResultArray() as $data) {
                $badgeSuhu = ($data['suhu'] < 22) ? 'warning' : (($data['suhu'] > 30) ? 'danger' : 'success');
                $badgeKelembapan = ($data['kelembapan'] < 35) ? 'warning' : (($data['kelembapan'] > 75) ? 'danger' : 'success');
                $tindakan = $data['tindakan'] === 'disabled' ? '<i class="fas fa-minus"></i>' : "<button onclick=\"location.href='" . base_url('/monitoring-suhu-kelembapan/tambah-tindakan/' . $data['id']) . "'\" class=\"btn btn-" . ($data['hasil_tindakan'] == null ? 'primary' : 'success') . " btn-sm border-0\" data-popup='tooltip' title='Tindakan Jika Suhu & Kelembapan di Luar Batas'><i class=\"fas fa-screwdriver-wrench\"></i></button>";
                $td = [
                    "noReferensi" => generateNoReferensi($data['created_at'], $data['id']),
                    "tanggalCatat" => $data['tgl_catat'],
                    "suhu" => "<span class=\"badge badge-" . $badgeSuhu . "\">" . $data['suhu'] . " °C</span>",
                    "kelembapan" => "<span class=\"badge badge-" . $badgeKelembapan . "\">" . $data['kelembapan'] . " %</span>",
                    "petugas" => $data['nama'],
                    "tindakan" => $tindakan,
                    "id" => $data['id']
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataSuhuKelembapanBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataSuhuKelembapanBerdasarkanTanggal,
                'data' => $dataResult
            ];

            return $this->response->setJSON($json);
        }
    }

    public function validasiForm($id = '')
    {
        $this->valid = $this->validate([
            'tanggalCatat' => [
                'rules' => $id === '' ? 'required|is_unique_soft[cssd_monitoring_suhu_kelembapan.tgl_catat]|not_future_date' : 'required',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                    'is_unique_soft' => 'Tanggal terpilih sudah tercatat suhu & kelembapan'
                ],
            ],
            'petugas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Petugas harus diisi',
                ]
            ],
            'suhu' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Suhu harus diisi',
                    'numeric' => 'Suhu harus angka'
                ]
            ],
            'kelembapan' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kelembapan harus diisi',
                    'numeric' => 'Kelembapan harus angka'
                ]
            ]
        ]);
    }

    public function tambahSuhuKelembapan()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Tambah Suhu & Kelembapan',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Dekontaminasi',
            'tanggalSekarang' => date('Y-m-d'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
        ];
        return view('dekontaminasi/monitoringsuhukelembapan/tambah_suhu_view', $data);
    }

    public function simpanSuhuKelembapan()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalCatat' => $this->validation->getError('tanggalCatat'),
                    'invalidPetugas' => $this->validation->getError('petugas'),
                    'invalidSuhu' => $this->validation->getError('suhu'),
                    'invalidKelembapan' => $this->validation->getError('kelembapan'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalCatat = $this->request->getPost('tanggalCatat');
            $idPetugas = $this->request->getPost('petugas');
            $suhu = $this->request->getPost('suhu');
            $kelembapan = $this->request->getPost('kelembapan');
            $tindakan = "disabled";
            if ($suhu < 22 || $suhu > 30 || $kelembapan < 35 || $kelembapan > 75) {
                $tindakan = "";
            }
            $data = [
                'id' => generateUUID(),
                'tgl_catat' => $tanggalCatat,
                'id_petugas' => $idPetugas,
                'suhu' => $suhu,
                'kelembapan' => $kelembapan,
                'tindakan' => $tindakan
            ];
            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $insertMonitoringSuhuKelembapan = $monitoringSuhuKelembapanModel->insert($data);
            $insertMonitoringSuhuKelembapanLog = ($insertMonitoringSuhuKelembapan) ? "Insert" : "Gagal insert";
            $insertMonitoringSuhuKelembapanLog .= " monitoring suhu & kelembapan ruang dekontaminasi dengan id " . $insertMonitoringSuhuKelembapan;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringSuhuKelembapanLog
            ]);
            if (!$insertMonitoringSuhuKelembapan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringSuhuKelembapanLog
                        ]
                    ]
                );
            }

            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Disimpan'
                    ]
                ]
            );
        }
    }

    public function editSuhuKelembapan($id = null)
    {
        $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
        $dataSuhuKelembapanBerdasarkanId = $monitoringSuhuKelembapanModel->dataSuhuKelembapanBerdasarkanId($id);
        if (!$dataSuhuKelembapanBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Edit Suhu & Kelembapan',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Dekontaminasi',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataSuhuKelembapanBerdasarkanId' => $dataSuhuKelembapanBerdasarkanId
        ];
        return view('dekontaminasi/monitoringsuhukelembapan/edit_suhu_view', $data);
    }

    public function updateSuhuKelembapan($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm($id);
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalCatat' => $this->validation->getError('tanggalCatat'),
                    'invalidPetugas' => $this->validation->getError('petugas'),
                    'invalidSuhu' => $this->validation->getError('suhu'),
                    'invalidKelembapan' => $this->validation->getError('kelembapan'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $idPetugas = $this->request->getPost('petugas');
            $suhu = $this->request->getPost('suhu');
            $kelembapan = $this->request->getPost('kelembapan');
            $tindakan = "disabled";
            if ($suhu < 22 || $suhu > 30 || $kelembapan < 35 || $kelembapan > 75) {
                $tindakan = "";
            }
            $data = [
                'id_petugas' => $idPetugas,
                'suhu' => $suhu,
                'kelembapan' => $kelembapan,
                'tindakan' => $tindakan
            ];
            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $updateMonitoringSuhuKelembapan = $monitoringSuhuKelembapanModel->update($id, $data, false);
            $updateMonitoringSuhuKelembapanLog = ($updateMonitoringSuhuKelembapan) ? "Update" : "Gagal update";
            $updateMonitoringSuhuKelembapanLog .= " monitoring suhu & kelembapan ruang dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateMonitoringSuhuKelembapanLog
            ]);
            if (!$updateMonitoringSuhuKelembapan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateMonitoringSuhuKelembapanLog
                        ]
                    ]
                );
            }

            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Diedit'
                    ]
                ]
            );
        }
    }

    public function hapusSuhuKelembapan($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $deleteMonitoringSuhuKelembapan = $monitoringSuhuKelembapanModel->delete($id, false);
            $deleteMonitoringSuhuKelembapanLog = ($deleteMonitoringSuhuKelembapan) ? "Delete" : "Gagal delete";
            $deleteMonitoringSuhuKelembapanLog .= " monitoring suhu & kelembapan ruang dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteMonitoringSuhuKelembapanLog
            ]);
            if (!$deleteMonitoringSuhuKelembapan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteMonitoringSuhuKelembapanLog
                        ]
                    ]
                );
            }
            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Dihapus'
                    ]
                ]
            );
        }
    }

    public function grafikMonitoring()
    {
        $data = [
            'title' => 'Grafik Monitoring Suhu & Kelembapan',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Dekontaminasi',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d')
        ];
        return view('dekontaminasi/monitoringsuhukelembapan/grafik_view', $data);
    }

    public function dataGrafikMonitoring()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal');
            $tglAkhir = $this->request->getPost('tglAkhir');
            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $dataSuhuKelembapanBerdasarkanTanggal = $monitoringSuhuKelembapanModel->dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)->getResultArray();
            $dataGrafikSuhu = [];
            $dataGrafikKelembapan = [];
            foreach ($dataSuhuKelembapanBerdasarkanTanggal as $data) {
                array_push($dataGrafikSuhu, ['x' => date("d/m/y", strtotime($data['tgl_catat'])), 'y' => $data['suhu']]);
                array_push($dataGrafikKelembapan, ['x' => date("d/m/y", strtotime($data['tgl_catat'])), 'y' => $data['kelembapan']]);
            }

            $dataSuhuKelembapan = [
                'dataGrafikSuhu' => $dataGrafikSuhu,
                'dataGrafikKelembapan' => $dataGrafikKelembapan
            ];
            return $this->response->setJSON($dataSuhuKelembapan);
        }
    }

    public function tindakan($idSuhuKelembapan)
    {
        $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
        $dataSuhuKelembapanBerdasarkanId = $monitoringSuhuKelembapanModel
            ->dataSuhuKelembapanBerdasarkanId($idSuhuKelembapan);
        if (!$dataSuhuKelembapanBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ($dataSuhuKelembapanBerdasarkanId['tindakan'] == "disabled") {
            return redirect()->to('/monitoring-suhu-kelembapan');
        }
        $pegawaiModel = model(PegawaiModel::class);
        $namaPetugas = $pegawaiModel->getDetailPegawai($dataSuhuKelembapanBerdasarkanId['id_petugas']);
        $data = [
            'title' => 'Tambah Tindakan Jika Suhu & Kelembapan di Luar Batas',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Dekontaminasi',
            'idSuhuKelembapan' => $dataSuhuKelembapanBerdasarkanId['id'],
            'noReferensi' => generateNoReferensi($dataSuhuKelembapanBerdasarkanId['created_at'], $dataSuhuKelembapanBerdasarkanId['id']),
            'tanggal' => date('d-m-Y', strtotime($dataSuhuKelembapanBerdasarkanId['tgl_catat'])),
            'suhu' => $dataSuhuKelembapanBerdasarkanId['suhu'] . ' °C',
            'kelembapan' => $dataSuhuKelembapanBerdasarkanId['kelembapan'] . ' %',
            'hasilTindakan' => $dataSuhuKelembapanBerdasarkanId['hasil_tindakan'] ?? '',
            'petugas' => $namaPetugas['nama']
        ];
        return view('dekontaminasi/monitoringsuhukelembapan/tambah_tindakan_view', $data);
    }

    public function simpanTindakan($idSuhuKelembapan)
    {
        if ($this->request->isAJAX()) {
            $valid = $this->validate([
                'hasilTindakan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Hasil dan tindakan harus diisi'
                    ],
                ],
            ]);

            if (!$valid) {
                $pesanError = [
                    'invalidHasilTindakan' => $this->validation->getError('hasilTindakan')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $data = [
                'hasil_tindakan' => $this->request->getPost('hasilTindakan')
            ];
            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $updateTindakan = $monitoringSuhuKelembapanModel->update($idSuhuKelembapan, $data, false);
            $updateTindakanLog = ($updateTindakan) ? "Update" : "Gagal update";
            $updateTindakanLog .= " tindakan monitoring suhu & kelembapan ruang dekontaminasi dengan id " . $idSuhuKelembapan;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateTindakanLog
            ]);
            if (!$updateTindakan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $updateTindakanLog
                        ]
                    ]
                );
            }

            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Disimpan'
                    ]
                ]
            );
        }
    }

    public function hapusTindakan($idSuhuKelembapan)
    {
        if ($this->request->isAJAX()) {
            $monitoringSuhuKelembapanModel = model(MonitoringSuhuKelembapanModel::class);
            $deleteTindakan = $monitoringSuhuKelembapanModel->update($idSuhuKelembapan, ["hasil_tindakan" => null], false);
            $deleteTindakanLog = ($deleteTindakan) ? "Delete" : "Gagal delete";
            $deleteTindakanLog .= " tindakan monitoring suhu & kelembapan ruang dekontaminasi dengan id " . $idSuhuKelembapan;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteTindakanLog
            ]);
            if (!$deleteTindakan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteTindakanLog
                        ]
                    ]
                );
            }

            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Dihapus'
                    ]
                ]
            );
        }
    }
}
