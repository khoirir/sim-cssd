<?php

namespace App\Controllers\DataControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DataSatuanModel;

class DataSatuanController extends BaseController
{
    // protected $dataSatuanModel;
    protected $valid;
    protected $validation;

    public function __construct()
    {
        // $this->dataSatuanModel = new DataSatuanModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Satuan',
            'header' => 'Data Satuan',
        ];
        return view('datamaster/datasatuan/index_datasatuan_view', $data);
    }

    public function dataSatuan()
    {
        if ($this->request->isAJAX()) {
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];

            $dataSatuanModel = model(DataSatuanModel::class);
            $dataSatuanBerdasarkanFilter = $dataSatuanModel
                ->dataSatuanBerdasarkanFilter($search, $start, $limit)
                ->getResultArray();

            $jumlahDataSatuan = $dataSatuanModel
                ->dataSatuan()
                ->getNumRows();

            $jumlahDataSatuanBerdasarkanFilter = $dataSatuanModel
                ->dataSatuanBerdasarkanPencarian($search)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataSatuanBerdasarkanFilter as $data) {
                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'kodeSatuan' => strtoupper($data['kode_satuan']),
                    'namaSatuan' => $data['nama_satuan'],
                    'id' => $data['id'],
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataSatuan,
                'recordsFiltered' => $jumlahDataSatuanBerdasarkanFilter,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function validasiForm($id = null)
    {
        $rules = 'required|is_unique_soft[cssd_satuan_set_alat.kode_satuan]';
        if ($id) {
            $dataSatuanModel = model(DataSatuanModel::class);
            $dataSatuan = $dataSatuanModel->find($id);
            if ($dataSatuan['kode_satuan'] === $this->request->getVar('kodeSatuan')) {
                $rules = 'required';
            }
        }

        $this->valid = $this->validate([
            'kodeSatuan' => [
                'rules' => $rules,
                'errors' => [
                    'required' => 'Kode satuan harus diisi',
                    'is_unique_soft' => 'Kode satuan sudah tersedia'
                ],
            ],
            'namaSatuan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama satuan harus diisi',
                ],
            ],
        ]);
    }

    public function tambahSatuan()
    {
        $data = [
            'title' => 'Tambah Data Satuan',
            'header' => 'Data Satuan'
        ];
        return view('datamaster/datasatuan/tambah_datasatuan_view', $data);
    }

    public function simpanSatuan()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidKodeSatuan' => $this->validation->getError('kodeSatuan'),
                    'invalidNamaSatuan' => $this->validation->getError('namaSatuan')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $kodeSatuan = $this->request->getPost('kodeSatuan');
            $namaSatuan = $this->request->getPost('namaSatuan');

            $data = [
                'id' => generateUUID(),
                'kode_satuan' => $kodeSatuan,
                'nama_satuan' => $namaSatuan,
            ];
            $dataSatuanModel = model(DataSatuanModel::class);
            $insertDataSatuan = $dataSatuanModel->insert($data);
            $insertDataSatuanLog = ($insertDataSatuan) ? "Insert" : "Gagal insert";
            $insertDataSatuanLog .= " data satuan dengan id " . $insertDataSatuan;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertDataSatuanLog
            ]);
            if (!$insertDataSatuan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertDataSatuanLog
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

    public function editSatuan($id = null)
    {
        $dataSatuanModel = model(DataSatuanModel::class);
        $dataSatuan = $dataSatuanModel->find($id);
        if (!$dataSatuan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Data Satuan',
            'header' => 'Data Satuan',
            'dataSatuan' => $dataSatuan
        ];

        return view('datamaster/datasatuan/edit_datasatuan_view', $data);
    }

    public function updateSatuan($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm($id);
            if (!$this->valid) {
                $pesanError = [
                    'invalidKodeSatuan' => $this->validation->getError('kodeSatuan'),
                    'invalidNamaSatuan' => $this->validation->getError('namaSatuan')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $kodeSatuan = $this->request->getPost('kodeSatuan');
            $namaSatuan = $this->request->getPost('namaSatuan');

            $data = [
                'kode_satuan' => $kodeSatuan,
                'nama_satuan' => $namaSatuan,
            ];
            $dataSatuanModel = model(DataSatuanModel::class);
            $updateDataSatuan = $dataSatuanModel->update($id, $data, false);
            $updateDataSatuanLog = ($updateDataSatuan) ? "Update" : "Gagal update";
            $updateDataSatuanLog .= " data satuan dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateDataSatuanLog
            ]);
            if (!$updateDataSatuan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateDataSatuanLog
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

    public function hapusSatuan($id)
    {
        if ($this->request->isAJAX()) {
            $dataSatuanModel = model(DataSatuanModel::class);
            $deleteDataSatuan = $dataSatuanModel->delete($id, false);
            $deleteDataSatuanLog = ($deleteDataSatuan) ? "Delete" : "Gagal delete";
            $deleteDataSatuanLog .= "data satuan dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteDataSatuanLog
            ]);
            if (!$deleteDataSatuan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteDataSatuanLog
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
