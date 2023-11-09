<?php

namespace App\Controllers\DataControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DataJenisSetAlatModel;

class DataJenisSetAlatController extends BaseController
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
            'title' => 'Data Jenis Set Alat',
            'header' => 'Data Jenis Set Alat',
        ];
        return view('datamaster/datajenissetalat/index_datajenissetalat_view', $data);
    }

    public function dataJenisSetAlat()
    {
        if ($this->request->isAJAX()) {
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];

            $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
            $dataJenisSetAlatBerdasarkanFilter = $dataJenisSetAlatModel
                ->dataJenisSetAlatBerdasarkanFilter($search, $start, $limit)
                ->getResultArray();

            $jumlahDataJenisSetAlat = $dataJenisSetAlatModel
                ->dataJenisSetAlat()
                ->getNumRows();

            $jumlahDataJenisSetAlatBerdasarkanFilter = $dataJenisSetAlatModel
                ->dataJenisSetAlatBerdasarkanPencarian($search)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataJenisSetAlatBerdasarkanFilter as $data) {
                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'namaJenis' => strtoupper($data['nama_jenis']),
                    'id' => $data['id'],
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataJenisSetAlat,
                'recordsFiltered' => $jumlahDataJenisSetAlatBerdasarkanFilter,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function tambahJenisSetAlat()
    {
        $data = [
            'title' => 'Tambah Data Jenis Set Alat',
            'header' => 'Data Jenis Set Alat'
        ];
        return view('datamaster/datajenissetalat/tambah_datajenissetalat_view', $data);
    }

    public function validasiForm($id = null)
    {
        $rules = 'required|is_unique_soft[cssd_jenis_set_alat.nama_jenis]';
        if ($id) {
            $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
            $dataJenis = $dataJenisSetAlatModel->find($id);
            if ($dataJenis['nama_jenis'] === $this->request->getVar('namaJenis')) {
                $rules = 'required';
            }
        }

        $this->valid = $this->validate([
            'namaJenis' => [
                'rules' => $rules,
                'errors' => [
                    'required' => 'Nama jenis harus diisi',
                    'is_unique_soft' => 'Nama jenis sudah tersedia'
                ],
            ],
        ]);
    }

    public function simpanJenisSetAlat()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidNamaJenis' => $this->validation->getError('namaJenis')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $namaJenis = $this->request->getPost('namaJenis');

            $data = [
                'id' => generateUUID(),
                'nama_jenis' => $namaJenis,
            ];
            $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
            $insertDataJenisSetAlat = $dataJenisSetAlatModel->insert($data);
            $insertDataJenisSetAlatLog = ($insertDataJenisSetAlat) ? "Insert" : "Gagal insert";
            $insertDataJenisSetAlatLog .= " data jenis set alat dengan id " . $insertDataJenisSetAlat;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertDataJenisSetAlatLog
            ]);
            if (!$insertDataJenisSetAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertDataJenisSetAlatLog
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

    public function editJenisSetAlat($id = null)
    {
        $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
        $dataJenis = $dataJenisSetAlatModel->find($id);
        if (!$dataJenis) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Data Jenis Set Alat',
            'header' => 'Data Jenis Set Alat',
            'dataJenis' => $dataJenis
        ];

        return view('datamaster/datajenissetalat/edit_datajenissetalat_view', $data);
    }

    public function updateJenisSetAlat($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm($id);
            if (!$this->valid) {
                $pesanError = [
                    'invalidNamaJenis' => $this->validation->getError('namaJenis')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $namaJenis = $this->request->getPost('namaJenis');

            $data = [
                'nama_jenis' => $namaJenis,
            ];
            $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
            $updateDataJenisSetAlat = $dataJenisSetAlatModel->update($id, $data, false);
            $updateDataJenisSetAlatLog = ($updateDataJenisSetAlat) ? "Update" : "Gagal update";
            $updateDataJenisSetAlatLog .= " data jenis set alat dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateDataJenisSetAlatLog
            ]);
            if (!$updateDataJenisSetAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateDataJenisSetAlatLog
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

    public function hapusJenisSetAlat($id)
    {
        if ($this->request->isAJAX()) {
            $dataJenisSetAlatModel = model(DataJenisSetAlatModel::class);
            $deleteDataJenisSetAlat = $dataJenisSetAlatModel->delete($id, false);
            $deleteDataJenisSetAlatLog = ($deleteDataJenisSetAlat) ? "Delete" : "Gagal delete";
            $deleteDataJenisSetAlatLog .= "data jenis set alat dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteDataJenisSetAlatLog
            ]);
            if (!$deleteDataJenisSetAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteDataJenisSetAlatLog
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
