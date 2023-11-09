<?php

namespace App\Controllers\DataControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DataJenisSetAlatModel;
use App\Models\DataModels\DataSatuanModel;
use App\Models\DataModels\SetAlatModel;

class DataSetAlatController extends BaseController
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
            'title' => 'Data Set Alat',
            'header' => 'Data Set Alat',
        ];
        return view('datamaster/datasetalat/index_datasetalat_view', $data);
    }

    public function dataSetAlat()
    {
        if ($this->request->isAJAX()) {
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];

            $setAlatModel = model(SetAlatModel::class);
            $dataSetAlatBerdasarkanFilter = $setAlatModel
                ->dataSetAlatBerdasarkanFilter($search, $start, $limit)
                ->getResultArray();

            $jumlahDataSetAlat = $setAlatModel
                ->dataSetAlat()
                ->getNumRows();

            $jumlahDataSetAlatBerdasarkanFilter = $setAlatModel
                ->dataSetAlatBerdasarkanPencarian($search)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataSetAlatBerdasarkanFilter as $data) {
                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'namaSetAlat' => $data['nama_set_alat'],
                    'jenis' => strtoupper($data['nama_jenis']),
                    'satuan' => strtoupper($data['kode_satuan']),
                    'id' => $data['id'],
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataSetAlat,
                'recordsFiltered' => $jumlahDataSetAlatBerdasarkanFilter,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function validasiForm($id = null)
    {
        $rules = 'required|is_unique_soft[cssd_set_alat.nama_set_alat]';
        if ($id) {
            $setAlatModel = model(SetAlatModel::class);
            $dataSetAlat = $setAlatModel->find($id);
            if ($dataSetAlat['nama_set_alat'] === $this->request->getVar('namaSetAlat')) {
                $rules = 'required';
            }
        }

        $this->valid = $this->validate([
            'namaSetAlat' => [
                'rules' => $rules,
                'errors' => [
                    'required' => 'Nama Set Alat harus diisi',
                    'is_unique_soft' => 'Nama Set Alat sudah tersedia'
                ],
            ],
            'jenisSetAlat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenis Set Alat harus diisi',
                ]
            ],
            'satuanSetAlat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Satuan Set Alat harus diisi',
                ]
            ]
        ]);
    }

    public function tambahSetAlat()
    {
        $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
        $dataSatuanModel = model(DataSatuanModel::class);
        $data = [
            'title' => 'Tambah Data Set Alat',
            'header' => 'Data Set Alat',
            'jenisSetAlat' => $dataJenisSetAlatModel
                ->where('id !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')
                ->orderBy('nama_jenis')
                ->findAll(),
            'satuanSetAlat' => $dataSatuanModel->orderBy('kode_satuan')->findAll()
        ];
        return view('datamaster/datasetalat/tambah_datasetalat_view', $data);
    }

    public function simpanSetAlat()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidNamaSetAlat' => $this->validation->getError('namaSetAlat'),
                    'invalidJenisSetAlat' => $this->validation->getError('jenisSetAlat'),
                    'invalidSatuanSetAlat' => $this->validation->getError('satuanSetAlat')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $namaSetAlat = $this->request->getPost('namaSetAlat');
            $jenisSetAlat = $this->request->getPost('jenisSetAlat');
            $satuanSetAlat = $this->request->getPost('satuanSetAlat');

            $data = [
                'id' => generateUUID(),
                'nama_set_alat' => $namaSetAlat,
                'id_jenis' => $jenisSetAlat,
                'id_satuan' => $satuanSetAlat
            ];
            $setAlatModel = model(SetAlatModel::class);
            $insertDataSetAlat = $setAlatModel->insert($data);
            $insertDataSetAlatLog = ($insertDataSetAlat) ? "Insert" : "Gagal insert";
            $insertDataSetAlatLog .= " data set alat dengan id " . $insertDataSetAlat;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertDataSetAlatLog
            ]);
            if (!$insertDataSetAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertDataSetAlatLog
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

    public function editSetAlat($id = null)
    {
        $setAlatModel = model(SetAlatModel::class);
        $dataSetAlat = $setAlatModel->find($id);
        if (!$dataSetAlat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
        $dataSatuanModel = model(DataSatuanModel::class);
        $data = [
            'title' => 'Edit Data Set Alat',
            'header' => 'Data Set Alat',
            'jenisSetAlat' => $dataJenisSetAlatModel
                ->where('id !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')
                ->orderBy('nama_jenis')
                ->findAll(),
            'satuanSetAlat' => $dataSatuanModel->orderBy('kode_satuan')->findAll(),
            'dataSetAlat' => $dataSetAlat
        ];
        return view('datamaster/datasetalat/edit_datasetalat_view', $data);
    }

    public function updateSetAlat($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm($id);
            if (!$this->valid) {
                $pesanError = [
                    'invalidNamaSetAlat' => $this->validation->getError('namaSetAlat'),
                    'invalidJenisSetAlat' => $this->validation->getError('jenisSetAlat'),
                    'invalidSatuanSetAlat' => $this->validation->getError('satuanSetAlat')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $namaSetAlat = $this->request->getPost('namaSetAlat');
            $jenisSetAlat = $this->request->getPost('jenisSetAlat');
            $satuanSetAlat = $this->request->getPost('satuanSetAlat');

            $data = [
                'nama_set_alat' => $namaSetAlat,
                'id_jenis' => $jenisSetAlat,
                'id_satuan' => $satuanSetAlat
            ];
            $setAlatModel = model(SetAlatModel::class);
            $updateDataSetAlat = $setAlatModel->update($id, $data, false);
            $updateDataSetAlatLog = ($updateDataSetAlat) ? "Update" : "Gagal update";
            $updateDataSetAlatLog .= " data set alat dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateDataSetAlatLog
            ]);
            if (!$updateDataSetAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateDataSetAlatLog
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

    public function hapusSetAlat($id)
    {
        if ($this->request->isAJAX()) {
            $setAlatModel = model(SetAlatModel::class);
            $deleteDataSetAlat = $setAlatModel->delete($id, false);
            $deleteDataSetAlatLog = ($deleteDataSetAlat) ? "Delete" : "Gagal delete";
            $deleteDataSetAlatLog .= "data set alat dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteDataSetAlatLog
            ]);
            if (!$deleteDataSetAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteDataSetAlatLog
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
