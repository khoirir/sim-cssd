<?php

namespace App\Controllers\DataControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DataBmhpModel;
use App\Models\DataModels\DataSatuanModel;

class DataBmhpController extends BaseController
{
    // protected $dataBmhpModel;
    // protected $dataSatuanModel;
    protected $valid;
    protected $validation;

    public function __construct()
    {
        // $this->dataBmhpModel = new DataBmhpModel();
        // $this->dataSatuanModel = new DataSatuanModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Data BHMP',
            'header' => 'Data BHMP',
        ];
        return view('datamaster/databmhp/index_databmhp_view', $data);
    }

    public function dataBmhp()
    {
        if ($this->request->isAJAX()) {
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];

            $dataBmhpModel = model(DataBmhpModel::class);
            $dataBmhpBerdasarkanFilter = $dataBmhpModel
                ->dataBmhpBerdasarkanFilter($search, $start, $limit)
                ->getResultArray();

            $jumlahDataBmhp = $dataBmhpModel
                ->dataBmhp()
                ->getNumRows();

            $jumlahDataBmhpBerdasarkanFilter = $dataBmhpModel
                ->dataBmhpBerdasarkanPencarian($search)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataBmhpBerdasarkanFilter as $data) {
                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'namaBmhp' => $data['nama_set_alat'],
                    'harga' => 'Rp ' . number_format($data['harga'], 2, ',', '.'),
                    'satuan' => strtoupper($data['kode_satuan']),
                    'id' => $data['id'],
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataBmhp,
                'recordsFiltered' => $jumlahDataBmhpBerdasarkanFilter,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function tambahBmhp()
    {
        $dataSatuanModel = model(DataSatuanModel::class);
        $data = [
            'title' => 'Tambah BMHP',
            'header' => 'Data BMHP',
            'satuanBmhp' => $dataSatuanModel->orderBy('kode_satuan')->findAll()
        ];
        return view('datamaster/databmhp/tambah_databmhp_view', $data);
    }

    public function validasiForm($id = null)
    {
        $rules = 'required|is_unique_soft[cssd_set_alat.nama_set_alat]';
        if ($id) {
            $dataBmhpModel = model(DataBmhpModel::class);
            $dataBmhp = $dataBmhpModel->find($id);
            if ($dataBmhp['nama_set_alat'] === $this->request->getVar('namaBmhp')) {
                $rules = 'required';
            }
        }

        $this->valid = $this->validate([
            'namaBmhp' => [
                'rules' => $rules,
                'errors' => [
                    'required' => 'Nama BMHP harus diisi',
                    'is_unique_soft' => 'Nama BMHP sudah tersedia'
                ],
            ],
            'hargaBmhp' => [
                'rules' => 'required|regex_match[/^\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?$/]',
                'errors' => [
                    'required' => 'Harga BMHP harus diisi',
                    'decimal' => 'Harga BMHP harus angka'
                ]
            ],
            'satuanBmhp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Satuan BMHP harus diisi',
                ]
            ]
        ]);
    }

    public function simpanBmhp()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidNamaBmhp' => $this->validation->getError('namaBmhp'),
                    'invalidHargaBmhp' => $this->validation->getError('hargaBmhp'),
                    'invalidSatuanBmhp' => $this->validation->getError('satuanBmhp')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $namaBmhp = $this->request->getPost('namaBmhp');
            $hargaBmhp = $this->request->getPost('hargaBmhp');
            $satuanBmhp = $this->request->getPost('satuanBmhp');

            $data = [
                'id' => generateUUID(),
                'nama_set_alat' => $namaBmhp,
                'harga' => str_replace('.', '', $hargaBmhp),
                'id_satuan' => $satuanBmhp,
                'id_jenis' => '0e0dae9d-34ce-11ee-8c2a-14187762d6e2'
            ];

            $dataBmhpModel = model(DataBmhpModel::class);
            $insertDataBmhp = $dataBmhpModel->insert($data);
            $insertDataBmhpLog = ($insertDataBmhp) ? "Insert" : "Gagal insert";
            $insertDataBmhpLog .= " data bmhp dengan id " . $insertDataBmhp;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertDataBmhpLog
            ]);
            if (!$insertDataBmhp) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertDataBmhpLog
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

    public function editBmhp($id = null)
    {
        $dataBmhpModel = model(DataBmhpModel::class);
        $dataBmhp = $dataBmhpModel->find($id);
        if (!$dataBmhp) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $dataSatuanModel = model(DataSatuanModel::class);
        $data = [
            'title' => 'Edit Data BMHP',
            'header' => 'Data BMHP',
            'satuanBmhp' => $dataSatuanModel->orderBy('kode_satuan')->findAll(),
            'dataBmhp' => $dataBmhp
        ];
        return view('datamaster/databmhp/edit_databmhp_view', $data);
    }

    public function updateBmhp($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm($id);
            if (!$this->valid) {
                $pesanError = [
                    'invalidNamaBmhp' => $this->validation->getError('namaBmhp'),
                    'invalidHargaBmhp' => $this->validation->getError('hargaBmhp'),
                    'invalidSatuanBmhp' => $this->validation->getError('satuanBmhp')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $namaBmhp = $this->request->getPost('namaBmhp');
            $hargaBmhp = $this->request->getPost('hargaBmhp');
            $satuanBmhp = $this->request->getPost('satuanBmhp');

            $data = [
                'nama_set_alat' => $namaBmhp,
                'harga' => str_replace('.', '', $hargaBmhp),
                'id_satuan' => $satuanBmhp
            ];
            $dataBmhpModel = model(DataBmhpModel::class);
            $updateBmhpAlat = $dataBmhpModel->update($id, $data, false);
            $updateBmhpAlatLog = ($updateBmhpAlat) ? "Update" : "Gagal update";
            $updateBmhpAlatLog .= " data bmhp dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateBmhpAlatLog
            ]);
            if (!$updateBmhpAlat) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateBmhpAlatLog
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

    public function hapusBmhp($id)
    {
        if ($this->request->isAJAX()) {
            $dataBmhpModel = model(DataBmhpModel::class);
            $deleteDataBmhp = $dataBmhpModel->delete($id, false);
            $deleteDataBmhpLog = ($deleteDataBmhp) ? "Delete" : "Gagal delete";
            $deleteDataBmhpLog .= "data bmhp dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteDataBmhpLog
            ]);
            if (!$deleteDataBmhp) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteDataBmhpLog
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
