<?php

namespace App\Controllers\DistribusiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\PegawaiModel;
use App\Models\DistribusiModels\SuhuDanKelembapanModel;

class SuhuDanKelembapanController extends BaseController
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
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Distribusi',
            'tglAwal' => $tglAwal,
            'tglSekarang' => $tglSekarang,
        ];
        return view('distribusi/suhudankelembapan/index_suhudankelembapan_view', $data);
    }

    public function dataSuhuKelembapanBerdasarkanTanggal()
    {
        $tglAwal = $this->request->getPost('tglAwal');
        $tglAkhir = $this->request->getPost('tglAkhir');
        $start = $this->request->getPost('start');
        $limit = $this->request->getPost('length');

        $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);

        $dataSuhuKelembapanBerdasarkanTanggal = $suhuDanKelembapanModel->dataSuhuKelembapanBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit);
        $jumlahDataSuhuKelembapanBerdasarkanTanggal = $suhuDanKelembapanModel->dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)->getNumRows();

        $dataResult = [];
        foreach ($dataSuhuKelembapanBerdasarkanTanggal->getResultArray() as $data) {
            $badgeSuhu = ($data['suhu'] < 22) ? 'warning' : (($data['suhu'] > 30) ? 'danger' : 'success');
            $badgeKelembapan = ($data['kelembapan'] < 35) ? 'warning' : (($data['kelembapan'] > 75) ? 'danger' : 'success');
            $tindakan = $data['tindakan'] === 'disabled' ? '<i class="fas fa-minus"></i>' : "<button onclick=\"location.href='" . base_url('/suhu-dan-kelembapan/tambah-tindakan/' . $data['id']) . "'\" class=\"btn btn-" . ($data['hasil_tindakan'] == null ? 'primary' : 'success') . " btn-sm border-0\" data-popup='tooltip' title='Tindakan Jika Suhu & Kelembapan di Luar Batas'><i class=\"fas fa-screwdriver-wrench\"></i></button>";
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

    public function validasiForm($id = '')
    {
        $this->valid = $this->validate([
            'tanggalCatat' => [
                'rules' => $id === '' ? 'required|is_unique_soft[cssd_suhu_kelembapan_distribusi.tgl_catat]|not_future_date' : 'required',
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
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Distribusi',
            'tanggalSekarang' => date('Y-m-d'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
        ];
        return view('distribusi/suhudankelembapan/tambah_suhudankelembapan_view', $data);
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
            $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
            $insertSuhuDanKelembapan = $suhuDanKelembapanModel->insert($data);
            $insertSuhuDanKelembapanLog = ($insertSuhuDanKelembapan) ? "Insert" : "Gagal insert";
            $insertSuhuDanKelembapanLog .= " monitoring suhu & kelembapan ruang distribusi dengan id " . $insertSuhuDanKelembapan;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertSuhuDanKelembapanLog
            ]);
            if (!$insertSuhuDanKelembapan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertSuhuDanKelembapanLog
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
        $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
        $dataSuhuKelembapanBerdasarkanId = $suhuDanKelembapanModel->dataSuhuKelembapanBerdasarkanId($id);
        if (!$dataSuhuKelembapanBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Edit Suhu & Kelembapan',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Distribusi',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataSuhuKelembapanBerdasarkanId' => $dataSuhuKelembapanBerdasarkanId
        ];
        return view('distribusi/suhudankelembapan/edit_suhudankelembapan_view', $data);
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
            $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
            $updateSuhuDanKelembapan = $suhuDanKelembapanModel->update($id, $data, false);
            $updateSuhuDanKelembapanLog = ($updateSuhuDanKelembapan) ? "Update" : "Gagal update";
            $updateSuhuDanKelembapanLog .= " monitoring suhu & kelembapan ruang distribusi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateSuhuDanKelembapanLog
            ]);
            if (!$updateSuhuDanKelembapan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateSuhuDanKelembapanLog
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
            $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
            $deleteSuhuDanKelembapan = $suhuDanKelembapanModel->delete($id, false);
            $deleteSuhuDanKelembapanLog = ($deleteSuhuDanKelembapan) ? "Delete" : "Gagal delete";
            $deleteSuhuDanKelembapanLog .= " monitoring suhu & kelembapan ruang distribusi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteSuhuDanKelembapanLog
            ]);
            if (!$deleteSuhuDanKelembapan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteSuhuDanKelembapanLog
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
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Distribusi',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d')
        ];
        return view('distribusi/suhudankelembapan/grafik_suhudankelembapan_view', $data);
    }

    public function dataGrafikMonitoring()
    {
        $tglAwal = $this->request->getPost('tglAwal');
        $tglAkhir = $this->request->getPost('tglAkhir');
        $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
        $dataSuhuKelembapanBerdasarkanTanggal = $suhuDanKelembapanModel
            ->dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)
            ->getResultArray();
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

    public function tindakan($idSuhuKelembapan)
    {
        $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
        $dataSuhuKelembapanBerdasarkanId = $suhuDanKelembapanModel
            ->dataSuhuKelembapanBerdasarkanId($idSuhuKelembapan);
        if (!$dataSuhuKelembapanBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ($dataSuhuKelembapanBerdasarkanId['tindakan'] == "disabled") {
            session()->setFlashdata(['alert' => 'danger', 'pesan' => 'Tidak Dapat Tambah Tindakan']);
            return redirect()->to('/suhu-dan-kelembapan');
        }
        $pegawaiModel = model(PegawaiModel::class);
        $namaPetugas = $pegawaiModel->getDetailPegawai($dataSuhuKelembapanBerdasarkanId['id_petugas']);
        $data = [
            'title' => 'Tambah Tindakan Jika Suhu & Kelembapan di Luar Batas',
            'header' => 'Monitoring Suhu dan Kelembapan Ruang Distribusi',
            'idSuhuKelembapan' => $dataSuhuKelembapanBerdasarkanId['id'],
            'noReferensi' => generateNoReferensi($dataSuhuKelembapanBerdasarkanId['created_at'], $dataSuhuKelembapanBerdasarkanId['id']),
            'tanggal' => date('d-m-Y', strtotime($dataSuhuKelembapanBerdasarkanId['tgl_catat'])),
            'suhu' => $dataSuhuKelembapanBerdasarkanId['suhu'] . ' °C',
            'kelembapan' => $dataSuhuKelembapanBerdasarkanId['kelembapan'] . ' %',
            'hasilTindakan' => $dataSuhuKelembapanBerdasarkanId['hasil_tindakan'] ?? '',
            'petugas' => $namaPetugas['nama']
        ];
        return view('distribusi/suhudankelembapan/tambah_tindakan_view', $data);
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
            $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
            $updateTindakan = $suhuDanKelembapanModel->update($idSuhuKelembapan, $data, false);
            $updateTindakanLog = ($updateTindakan) ? "Update" : "Gagal update";
            $updateTindakanLog .= " tindakan monitoring suhu & kelembapan ruang distribusi dengan id " . $idSuhuKelembapan;
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
            $suhuDanKelembapanModel = model(SuhuDanKelembapanModel::class);
            $deleteTindakan = $suhuDanKelembapanModel->update($idSuhuKelembapan, ["hasil_tindakan" => null], false);
            $deleteTindakanLog = ($deleteTindakan) ? "Delete" : "Gagal delete";
            $deleteTindakanLog .= " tindakan monitoring suhu & kelembapan ruang distribusi dengan id " . $idSuhuKelembapan;
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
