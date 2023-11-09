<?php

namespace App\Controllers\DekontaminasiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\PegawaiModel;
use App\Models\DekontaminasiModels\KepatuhanApdDetailModel;
use App\Models\DekontaminasiModels\KepatuhanApdModel;

class KepatuhanApdController extends BaseController
{
    protected $valid;
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $tglAwal = date('Y-m-01');
        $tglSekarang = date('Y-m-d');
        $data = [
            'title' => 'Kepatuhan APD',
            'header' => 'Kepatuhan Petugas dalam Penggunaan APD di Ruang Dekontaminasi',
            'tglAwal' => $tglAwal,
            'tglSekarang' => $tglSekarang
        ];
        return view('dekontaminasi/kepatuhanapd/index_kepatuhanapd_view', $data);
    }

    public function dataKepatuhanApdBerdasarkanTanggal()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal');
            $tglAkhir = $this->request->getPost('tglAkhir');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $kepatuhanApdModel = model(KepatuhanApdModel::class);
            $dataKepatuhanApdBerdasarkanTanggaldanLimit = $kepatuhanApdModel
                ->dataKepatuhanApdBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
                ->getResultArray();
            $jumlahDataKepatuhanApdBerdasarkanTanggal = $kepatuhanApdModel
                ->dataKepatuhanApdBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataKepatuhanApdBerdasarkanTanggaldanLimit as $data) {
                $td = [
                    "noReferensi" => generateNoReferensi($data['created_at'], $data['id']),
                    'tanggalCek' => date('d-m-Y', strtotime($data['tanggal_cek'])),
                    "shift" => ucfirst($data['shift']),
                    "id" => $data['id'],
                ];
                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataKepatuhanApdBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataKepatuhanApdBerdasarkanTanggal,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailKepatuhanAPD($id)
    {
        if ($this->request->isAJAX()) {
            $kepatuhanApdModel = model(KepatuhanApdModel::class);
            $dataKepatuhanApd = $kepatuhanApdModel->find($id);
            if (!$dataKepatuhanApd) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }
            $kepatuhanApdDetailModel = model(KepatuhanApdDetailModel::class);
            $dataDetail = $kepatuhanApdDetailModel
                ->dataKepatuhanApdDetailBerdasarkanIdMaster($dataKepatuhanApd['id'])
                ->getResultArray();

            $data = [
                'dataKepatuhanApd' => $dataKepatuhanApd,
                'dataDetail' => $dataDetail
            ];

            $json = [
                'sukses' => true,
                'data' => view('dekontaminasi/kepatuhanapd/modal_detail_kepatuhanapd', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function validasiForm()
    {
        $this->valid = $this->validate([
            'tanggalCek' => [
                'rules' => 'required|not_future_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                    'is_unique_soft' => 'Tanggal terpilih sudah tercatat suhu & kelembapan'
                ],
            ],
            'shift' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Shift harus dipilih',
                ]
            ],
            'jumlahDataKepatuhanApdDetail' => [
                'rules' => 'greater_than[0]',
                'errors' => [
                    'greater_than' => 'Tabel APD harus diisi'
                ]
            ]
        ]);
    }

    public function tambahKepatuhanAPD()
    {
        $jamSekarang = date('H:i');
        $shift = '';
        if ($jamSekarang >= '07:00' && $jamSekarang <= '14:00') {
            $shift = 'pagi';
        } elseif ($jamSekarang >= '14:01' && $jamSekarang <= '21:00') {
            $shift = 'sore';
        }
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Tambah Kepatuhan APD',
            'header' => 'Kepatuhan Petugas dalam Penggunaan APD di Ruang Dekontaminasi',
            'tglSekarang' => date('Y-m-d'),
            'shift' => $shift,
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
        ];
        return view('dekontaminasi/kepatuhanapd/tambah_kepatuhanapd_view', $data);
    }

    public function simpanKepatuhanAPD()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalCek' => $this->validation->getError('tanggalCek'),
                    'invalidShift' => $this->validation->getError('shift'),
                    'invalidTabelDataApd' => $this->validation->getError('jumlahDataKepatuhanApdDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalCek = $this->request->getPost('tanggalCek');
            $shift = $this->request->getPost('shift');
            $dataKepatuhanApdDetail = $this->request->getPost('dataKepatuhanApdDetail');

            $data = [
                'id' => generateUUID(),
                'tanggal_cek' => $tanggalCek,
                'shift' => $shift
            ];
            $kepatuhanApdModel = model(KepatuhanApdModel::class);
            $insertKepatuhanApd = $kepatuhanApdModel->insert($data);
            $insertKepatuhanApdLog = ($insertKepatuhanApd) ? "Insert" : "Gagal insert";
            $insertKepatuhanApdLog .= " kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id master " . $insertKepatuhanApd;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertKepatuhanApdLog
            ]);
            if (!$insertKepatuhanApd) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertKepatuhanApdLog
                        ]
                    ]
                );
            }

            $dataInsertKepatuhanApdDetail = [];
            foreach ($dataKepatuhanApdDetail as $data) {
                $dataDetail = [
                    'id_master' => $insertKepatuhanApd,
                    'id_petugas' => $data['idPetugas'],
                    'handschoen' => $data['handschoen'] ?? '',
                    'masker' => $data['masker'] ?? '',
                    'apron' => $data['apron'] ?? '',
                    'goggle' => $data['goggle'] ?? '',
                    'sepatu_boot' => $data['sepatuBoot'] ?? '',
                    'penutup_kepala' => $data['penutupKepala'] ?? '',
                    'keterangan' => $data['keterangan']
                ];
                array_push($dataInsertKepatuhanApdDetail, $dataDetail);
            }
            $kepatuhanApdDetailModel = model(KepatuhanApdDetailModel::class);
            $insertKepatuhanApdDetail = $kepatuhanApdDetailModel->insertMultiple($dataInsertKepatuhanApdDetail);
            $insertKepatuhanApdDetailLog = ($insertKepatuhanApdDetail) ? "Insert" : "Gagal insert";
            $insertKepatuhanApdDetailLog .= " detail baru kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id " . $insertKepatuhanApd;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertKepatuhanApdDetailLog
            ]);
            if (!$insertKepatuhanApdDetail) {
                $kepatuhanApdModel->delete($insertKepatuhanApd);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertKepatuhanApdDetailLog
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

    public function editKepatuhanAPD($id = null)
    {
        $kepatuhanApdModel = model(KepatuhanApdModel::class);
        $dataKepatuhanApdBerdasarkanId = $kepatuhanApdModel->find($id);
        if (!$dataKepatuhanApdBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pegawaiModel = model(PegawaiModel::class);
        $kepatuhanApdDetailModel = model(KepatuhanApdDetailModel::class);
        $dataKepatuhanApdDetailBerdasarkanIdMaster = $kepatuhanApdDetailModel->dataKepatuhanApdDetailBerdasarkanIdMaster($id);
        $data = [
            'title' => 'Edit Kepatuhan APD',
            'header' => 'Kepatuhan Petugas dalam Penggunaan APD di Ruang Dekontaminasi',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataKepatuhanApdBerdasarkanId' => $dataKepatuhanApdBerdasarkanId,
            'dataKepatuhanApdDetailBerdasarkanIdMaster' => $dataKepatuhanApdDetailBerdasarkanIdMaster
        ];
        return view('dekontaminasi/kepatuhanapd/edit_kepatuhanapd_view', $data);
    }

    public function updateKepatuhanAPD($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalCek' => $this->validation->getError('tanggalCek'),
                    'invalidShift' => $this->validation->getError('shift'),
                    'invalidTabelDataApd' => $this->validation->getError('jumlahDataKepatuhanApdDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalCek = $this->request->getPost('tanggalCek');
            $shift = $this->request->getPost('shift');
            $dataKepatuhanApdBaruDetail = $this->request->getPost('dataKepatuhanApdDetail');
            $idKepatuhanApdHapus = $this->request->getPost('idKepatuhanApdHapus');

            $kepatuhanApdDetailModel = model(KepatuhanApdDetailModel::class);
            if ($idKepatuhanApdHapus) {
                $deleteKepatuhanApdDetail = $kepatuhanApdDetailModel->delete($idKepatuhanApdHapus);
                $deleteKepatuhanApdDetailLog = ($deleteKepatuhanApdDetail) ? "Delete" : "Gagal delete";
                $deleteKepatuhanApdDetailLog .= " detail kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id " . implode("; ", $idKepatuhanApdHapus);
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $deleteKepatuhanApdDetailLog
                ]);
                if (!$deleteKepatuhanApdDetail) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => $deleteKepatuhanApdDetailLog
                        ]
                    );
                }
            }

            $dataUpdateApd = [
                'tanggal_cek' => $tanggalCek,
                'shift' => $shift
            ];
            $kepatuhanApdModel = model(KepatuhanApdModel::class);
            $updateKepatuhanApd = $kepatuhanApdModel->update($id, $dataUpdateApd, false);
            $updateKepatuhanApdLog = ($updateKepatuhanApd) ? "Update" : "Gagal update";
            $updateKepatuhanApdLog .= " kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateKepatuhanApdLog
            ]);
            if (!$updateKepatuhanApd) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateKepatuhanApdLog
                        ]
                    ]
                );
            }

            if ($dataKepatuhanApdBaruDetail) {
                $dataInsertKepatuhanApdBaruDetail = [];
                foreach ($dataKepatuhanApdBaruDetail as $data) {
                    $dataDetail = [
                        'id_master' => $id,
                        'id_petugas' => $data['idPetugas'],
                        'handschoen' => $data['handschoen'] ?? '',
                        'masker' => $data['masker'] ?? '',
                        'apron' => $data['apron'] ?? '',
                        'goggle' => $data['goggle'] ?? '',
                        'sepatu_boot' => $data['sepatuBoot'] ?? '',
                        'penutup_kepala' => $data['penutupKepala'] ?? '',
                        'keterangan' => $data['keterangan']
                    ];
                    array_push($dataInsertKepatuhanApdBaruDetail, $dataDetail);
                }
                $insertKepatuhanApdDetail = $kepatuhanApdDetailModel->insertMultiple($dataInsertKepatuhanApdBaruDetail);
                $insertKepatuhanApdDetailLog = ($insertKepatuhanApdDetail) ? "Insert" : "Gagal insert";
                $insertKepatuhanApdDetailLog .= " detail baru kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertKepatuhanApdDetailLog
                ]);
                if (!$insertKepatuhanApdDetail) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Diedit',
                                'errorSimpan' => $insertKepatuhanApdDetailLog
                            ]
                        ]
                    );
                }
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

    public function hapusKepatuhanAPD($id)
    {
        if ($this->request->isAJAX()) {
            $kepatuhanApdModel = model(KepatuhanApdModel::class);
            $deleteKepatuhanApd = $kepatuhanApdModel->delete($id, false);
            $deleteKepatuhanApdLog = ($deleteKepatuhanApd) ? "Delete" : "Gagal delete";
            $deleteKepatuhanApdLog .= " kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteKepatuhanApdLog
            ]);
            if (!$deleteKepatuhanApd) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorSimpan' => $deleteKepatuhanApdLog
                        ]
                    ]
                );
            }
            $kepatuhanApdDetailModel = model(KepatuhanApdDetailModel::class);
            $deleteKepatuhanApdDetail = $kepatuhanApdDetailModel->where('id_master', $id)->delete();
            $deleteKepatuhanApdDetailLog = ($deleteKepatuhanApdDetail) ? "Delete" : "Gagal delete";
            $deleteKepatuhanApdDetailLog .= " detail kepatuhan petugas dalam penggunaan APD di ruang dekontaminasi dengan id master " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteKepatuhanApdDetailLog
            ]);
            if (!$deleteKepatuhanApdDetail) {
                $kepatuhanApdModel->update($id, ['deleted_at', null]);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorSimpan' => $deleteKepatuhanApdDetailLog
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
