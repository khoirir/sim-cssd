<?php

namespace App\Controllers\PackingalatControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\PegawaiModel;
use App\Models\DataModels\SetAlatModel;
use App\Models\PackingAlatModels\MonitoringPackingAlatDetailModel;
use App\Models\PackingAlatModels\MonitoringPackingAlatModel;

class MonitoringPackingAlatController extends BaseController
{
    protected $valid;
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Monitoring Packing Alat',
            'header' => 'Monitoring Setting Packing Alat',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
        ];
        return view('packingalat/monitoringpackingalat/index_monitoringpackingalat_view', $data);
    }

    public function dataMonitoringPackingAlatBerdasarkanTanggal()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal');
            $tglAkhir = $this->request->getPost('tglAkhir');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $monitoringPackingAlatModel = model(MonitoringPackingAlatModel::class);
            $dataMonitoringPackingAlatBerdasarkanTanggaldanLimit = $monitoringPackingAlatModel
                ->dataMonitoringPackingAlatBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
                ->getResultArray();

            $jumlahDataMonitoringPackingAlatBerdasarkanTanggal = $monitoringPackingAlatModel
                ->dataMonitoringPackingAlatBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataMonitoringPackingAlatBerdasarkanTanggaldanLimit as $data) {
                $td = [
                    "noReferensi" => generateNoReferensi($data['created_at'], $data['id']),
                    "tanggalPacking" => date("d-m-Y", strtotime($data['tanggal_packing'])),
                    "id" => $data['id']
                ];
                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataMonitoringPackingAlatBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataMonitoringPackingAlatBerdasarkanTanggal,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailMonitoringPackingAlat($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringPackingAlatModel = model(MonitoringPackingAlatModel::class);
            $dataPackingAlat = $monitoringPackingAlatModel->find($id);
            if (!$dataPackingAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }
            $monitoringPackingAlatDetailModel = model(MonitoringPackingAlatDetailModel::class);
            $dataDetail = $monitoringPackingAlatDetailModel
                ->dataMonitoringPackingAlatDetailBerdasarkanIdMaster($dataPackingAlat['id'])
                ->getResultArray();

            $data = [
                'dataPackingAlat' => $dataPackingAlat,
                'dataDetail' => $dataDetail
            ];

            $json = [
                'sukses' => true,
                'data' => view('packingalat/monitoringpackingalat/modal_detail_monitoringpackingalat', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function validasiForm()
    {
        $this->valid = $this->validate([
            'tanggalPacking' => [
                'rules' => 'required|not_future_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                ],
            ],
            'jumlahDataSetAlatDetail' => [
                'rules' => 'greater_than[0]',
                'errors' => [
                    'greater_than' => 'Tabel alat harus diisi'
                ]
            ]
        ]);
    }

    public function tambahMonitoringPackingAlat()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $setAlatModel = model(SetAlatModel::class);
        $data = [
            'title' => 'Tambah Monitoring Packing Alat',
            'header' => 'Monitoring Setting Packing Alat',
            'tglSekarang' => date('Y-m-d'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listSetAlat' => $setAlatModel->where('id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')->orderBy('nama_set_alat')->findAll()
        ];
        return view('packingalat/monitoringpackingalat/tambah_monitoringpackingalat_view', $data);
    }

    public function simpanMonitoringPackingAlat()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalPacking' => $this->validation->getError('tanggalPacking'),
                    'invalidTabelDataAlat' => $this->validation->getError('jumlahDataSetAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $tanggalPacking = $this->request->getPost('tanggalPacking');
            $dataAlat = $this->request->getPost('dataSetAlat');

            $data = [
                'id' => generateUUID(),
                'tanggal_packing' => $tanggalPacking,
            ];
            $monitoringPackingAlatModel = model(MonitoringPackingAlatModel::class);
            $insertMonitoringPackingAlat = $monitoringPackingAlatModel->insert($data);
            $insertMonitoringPackingAlatLog = ($insertMonitoringPackingAlat) ? "Insert" : "Gagal insert";
            $insertMonitoringPackingAlatLog .= " monitoring setting packing alat dengan id " . $insertMonitoringPackingAlat;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringPackingAlatLog
            ]);

            if (!$insertMonitoringPackingAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringPackingAlatLog
                        ]
                    ]
                );
            }

            $dataInsertMonitoringPackingAlatDetail = [];
            foreach ($dataAlat as $data) {
                $dataDetail = [
                    'id_master' => $insertMonitoringPackingAlat,
                    'id_alat' => $data['idAlat'],
                    'id_petugas' => $data['idPetugas'],
                    'bersih' => $data['bersih'] ?? '',
                    'tajam' => $data['tajam'] ?? '',
                    'layak' => $data['layak'] ?? '',
                    'indikator' => $data['indikator'] ?? ''
                ];
                array_push($dataInsertMonitoringPackingAlatDetail, $dataDetail);
            }
            $monitoringPackingAlatDetailModel = model(MonitoringPackingAlatDetailModel::class);
            $insertMonitoringPackingAlatDetail = $monitoringPackingAlatDetailModel->insertMultiple($dataInsertMonitoringPackingAlatDetail);
            $insertMonitoringPackingAlatDetailLog = ($insertMonitoringPackingAlatDetail) ? "Insert" : "Gagal insert";
            $insertMonitoringPackingAlatDetailLog .= " detail monitoring setting packing alat dengan id master " . $insertMonitoringPackingAlat;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringPackingAlatDetailLog
            ]);
            if (!$insertMonitoringPackingAlatDetail) {
                $monitoringPackingAlatModel->delete($insertMonitoringPackingAlat);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringPackingAlatDetailLog
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

    public function editMonitoringPackingAlat($id = null)
    {
        $monitoringPackingAlatModel = model(MonitoringPackingAlatModel::class);
        $dataMonitoringPackingAlatBerdasarkanId = $monitoringPackingAlatModel->find($id);
        if (!$dataMonitoringPackingAlatBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $monitoringPackingAlatDetailModel = model(MonitoringPackingAlatDetailModel::class);
        $dataMonitoringPackingAlatDetailBerdasarkanIdMaster = $monitoringPackingAlatDetailModel
            ->dataMonitoringPackingAlatDetailBerdasarkanIdMaster($dataMonitoringPackingAlatBerdasarkanId['id']);

        if (!$dataMonitoringPackingAlatDetailBerdasarkanIdMaster) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pegawaiModel = model(PegawaiModel::class);
        $setAlatModel = model(SetAlatModel::class);
        $data = [
            'title' => 'Edit Monitoring Packing Alat',
            'header' => 'Monitoring Setting Packing Alat',
            'dataMonitoringPackingAlatBerdasarkanId' => $dataMonitoringPackingAlatBerdasarkanId,
            'dataMonitoringPackingAlatDetailBerdasarkanIdMaster' => $dataMonitoringPackingAlatDetailBerdasarkanIdMaster,
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listSetAlat' => $setAlatModel->where('id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')->orderBy('nama_set_alat')->findAll()
        ];
        return view('packingalat/monitoringpackingalat/edit_monitoringpackingalat_view', $data);
    }

    public function updateMonitoringPackingAlat($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalPacking' => $this->validation->getError('tanggalPacking'),
                    'invalidTabelDataAlat' => $this->validation->getError('jumlahDataSetAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalPacking = $this->request->getPost('tanggalPacking');
            $dataAlat = $this->request->getPost('dataSetAlat');
            $idDetailSetAlatHapus = $this->request->getPost('idDetailSetAlatHapus');

            $monitoringPackingAlatDetailModel = model(MonitoringPackingAlatDetailModel::class);
            if ($idDetailSetAlatHapus) {
                $deleteMonitoringPackingAlatDetail = $monitoringPackingAlatDetailModel->delete($idDetailSetAlatHapus);
                $deleteMonitoringPackingAlatDetailLog = ($deleteMonitoringPackingAlatDetail) ? "Delete" : "Gagal delete";
                $deleteMonitoringPackingAlatDetailLog .= " detail monitoring setting packing alat dengan id " . implode("; ", $idDetailSetAlatHapus);
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $deleteMonitoringPackingAlatDetailLog
                ]);
                if (!$deleteMonitoringPackingAlatDetail) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => $deleteMonitoringPackingAlatDetailLog
                        ]
                    );
                }
            }

            $dataUpdateMonitoringPackingAlat = [
                'tanggal_packing' => $tanggalPacking,
            ];
            $monitoringPackingAlatModel = model(MonitoringPackingAlatModel::class);
            $updateMonitoringPackingAlat = $monitoringPackingAlatModel->update($id, $dataUpdateMonitoringPackingAlat, false);
            $updateMonitoringPackingAlatLog = ($updateMonitoringPackingAlat) ? "Update" : "Gagal update";
            $updateMonitoringPackingAlatLog .= " monitoring setting packing alat dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateMonitoringPackingAlatLog
            ]);
            if (!$updateMonitoringPackingAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateMonitoringPackingAlatLog
                        ]
                    ]
                );
            }

            if ($dataAlat) {
                $dataInsertMonitoringPackingAlatBaruDetail = [];
                foreach ($dataAlat as $data) {
                    $dataDetail = [
                        'id_master' => $id,
                        'id_alat' => $data['idAlat'],
                        'id_petugas' => $data['idPetugas'],
                        'bersih' => $data['bersih'] ?? '',
                        'tajam' => $data['tajam'] ?? '',
                        'layak' => $data['layak'] ?? '',
                        'indikator' => $data['indikator'] ?? ''
                    ];
                    array_push($dataInsertMonitoringPackingAlatBaruDetail, $dataDetail);
                }
                $insertMonitoringPackingAlatDetail = $monitoringPackingAlatDetailModel->insertMultiple($dataInsertMonitoringPackingAlatBaruDetail);
                $insertMonitoringPackingAlatDetailLog = ($insertMonitoringPackingAlatDetail) ? "Insert" : "Gagal insert";
                $insertMonitoringPackingAlatDetailLog .= " detail monitoring setting packing alat dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertMonitoringPackingAlatDetailLog
                ]);
                if (!$insertMonitoringPackingAlatDetail) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Diedit',
                                'errorSimpan' => $insertMonitoringPackingAlatDetailLog
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

    public function hapusMonitoringPackingAlat($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringPackingAlatModel = model(MonitoringPackingAlatModel::class);
            $deleteMonitoringPackingAlat = $monitoringPackingAlatModel->delete($id, false);
            $deleteMonitoringPackingAlatLog = ($deleteMonitoringPackingAlat) ? "Delete" : "Gagal delete";
            $deleteMonitoringPackingAlatLog .= " monitoring setting packing alat dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteMonitoringPackingAlatLog
            ]);
            if (!$deleteMonitoringPackingAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorSimpan' => $deleteMonitoringPackingAlatLog
                        ]
                    ]
                );
            }
            $monitoringPackingAlatDetailModel = model(MonitoringPackingAlatDetailModel::class);
            $deleteMonitoringPackingAlatDetail = $monitoringPackingAlatDetailModel->where('id_master', $id)->delete();
            $deleteMonitoringPackingAlatDetailLog = ($deleteMonitoringPackingAlatDetail) ? "Delete" : "Gagal delete";
            $deleteMonitoringPackingAlatDetailLog .= " detail monitoring setting packing alat dengan id master " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteMonitoringPackingAlatDetailLog
            ]);
            if (!$deleteMonitoringPackingAlatDetail) {
                $monitoringPackingAlatModel->update($id, ['deleted_at', null]);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorSimpan' => $deleteMonitoringPackingAlatDetailLog
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
