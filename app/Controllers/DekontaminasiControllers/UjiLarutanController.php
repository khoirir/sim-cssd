<?php

namespace App\Controllers\DekontaminasiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\PegawaiModel;
use App\Models\DekontaminasiModels\UjiLarutanModel;

class UjiLarutanController extends BaseController
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
            'title' => 'Uji Larutan DTT Alkacyd',
            'header' => 'Uji Efektifitas Larutan DTT Alkacyd',
            'tglAwal' => $tglAwal,
            'tglSekarang' => $tglSekarang,
        ];
        return view('dekontaminasi/ujilarutan/index_ujilarutan_view', $data);
    }

    public function dataUjiLarutanDenganFilter()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal');
            $tglAkhir = $this->request->getPost('tglAkhir');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $hasil = $this->request->getPost('hasil');

            $ujiLarutanModel = model(UjiLarutanModel::class);
            $dataUjiLarutanDenganFilter = $ujiLarutanModel
                ->dataUjiLarutanDenganFilter($tglAwal, $tglAkhir, $start, $limit, $hasil)
                ->getResultArray();

            $jumlahDataUjiLarutanBerdasarkanTanggal = $ujiLarutanModel
                ->jumlahDataUjiLarutanBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $jumlahDataUjiLarutanBerdasarkanTanggalDanHasil = $ujiLarutanModel
                ->jumlahDataUjiLarutanBerdasarkanTanggalDanHasil($tglAwal, $tglAkhir, $hasil)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataUjiLarutanDenganFilter as $data) {
                $hasilWarna = $data['hasil_warna'];
                $keterangan = $data['keterangan'] ?: ($hasilWarna === 'ungu' ? 'Passed' : 'Failed');
                $span = "<span class=\"badge badge-" . ($hasilWarna === 'ungu' ? 'success' : 'danger') . "\">" . $keterangan . "</span>";
                $td = [
                    "noReferensi" => generateNoReferensi($data['created_at'], $data['id']),
                    "tanggalUji" => date("d-m-Y", strtotime($data['tanggal_uji'])),
                    "metracid" => $data['metracid_1_ml'] === 'checked' ? '<i class="fas fa-check"></i>' : '',
                    "alkacid" => $data['alkacid_10_ml'] === 'checked' ? '<i class="fas fa-check"></i>' : '',
                    "ungu" => $hasilWarna === 'ungu' ? '<i class="fas fa-check"></i>' : '',
                    "pink" => $hasilWarna === 'pink' ? '<i class="fas fa-check"></i>' : '',
                    "petugas" => $data['nama'],
                    "larutan" => $data['upload_larutan'],
                    "keterangan" => $span,
                    "id" => $data['id']
                ];
                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataUjiLarutanBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataUjiLarutanBerdasarkanTanggalDanHasil,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function gambarUjiLarutan($id)
    {
        if ($this->request->isAJAX()) {
            $ujiLarutanModel = model(UjiLarutanModel::class);
            $dataUjiLarutan = $ujiLarutanModel->find($id);
            if (!$dataUjiLarutan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $data = [
                'noReferensi' => generateNoReferensi($dataUjiLarutan['created_at'], $dataUjiLarutan['id']),
                'larutan' => $dataUjiLarutan['upload_larutan']
            ];

            $json = [
                'sukses' => true,
                'data' => view('dekontaminasi/ujilarutan/modal_gambar_ujilarutan', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function tambahUjiLarutan()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Tambah Uji Larutan DTT Alkacyd',
            'header' => 'Uji Efektifitas Larutan DTT Alkacyd',
            'tanggalSekarang' => date('Y-m-d'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
        ];
        return view('dekontaminasi/ujilarutan/tambah_ujilarutan_view', $data);
    }

    public function validasiForm()
    {
        $this->valid = $this->validate(
            [
                'tanggalUji' => [
                    'rules' => 'required|not_future_date',
                    'errors' => [
                        'required' => 'Tanggal harus diisi'
                    ]
                ],
                'petugas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Petugas harus diisi'
                    ]
                ],
                'namaFile' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Gambar larutan harus diupload',
                    ]
                ],
                'uploadLarutan' => [
                    'rules' => 'max_size[uploadLarutan,512]|is_image[uploadLarutan]|mime_in[uploadLarutan,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                    'errors' => [
                        'max_size' => 'Ukuran gambar larutan terlalu besar (Maks. 512KB)',
                        'is_image' => 'Format gambar larutan harus *jpg, jpeg, png*',
                        'mime_in' => 'Format gambar larutan harus *jpg, jpeg, png*'
                    ]
                ],
                'checkboxMetracid' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'METRACID 1 ml harus dipilih'
                    ]
                ],
                'checkboxAlkacid' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'ALKACID 10 ml harus dipilih'
                    ]
                ],
                'radioHasilWarna' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Hasil harus dipilih'
                    ]
                ],
            ]
        );
    }

    public function simpanUjiLarutan()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalUji' => $this->validation->getError('tanggalUji'),
                    'invalidPetugas' => $this->validation->getError('petugas'),
                    'invalidNamaLarutan' => $this->validation->getError('namaFile'),
                    'invalidUploadLarutan' => $this->validation->getError('uploadLarutan'),
                    'invalidCheckboxMetracid' => $this->validation->getError('checkboxMetracid'),
                    'invalidCheckboxAlkacid' => $this->validation->getError('checkboxAlkacid'),
                    'invalidHasilWarna' => $this->validation->getError('radioHasilWarna'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalUji = $this->request->getVar('tanggalUji');
            $idPetugas = $this->request->getVar('petugas');
            $metracid1Ml = $this->request->getVar('checkboxMetracid');
            $alkacid10Ml = $this->request->getVar('checkboxAlkacid');
            $hasilWarna = $this->request->getVar('radioHasilWarna');
            $keterangan = $this->request->getVar('keterangan');
            $uploadLarutan = $this->request->getFile('uploadLarutan');

            $data = [
                'id' => generateUUID(),
                'tanggal_uji' => $tanggalUji,
                'id_petugas' => $idPetugas,
                'metracid_1_ml' => $metracid1Ml,
                'alkacid_10_ml' => $alkacid10Ml,
                'hasil_warna' => $hasilWarna,
                'upload_larutan' => $uploadLarutan->getRandomName(),
                'keterangan' => $keterangan,
            ];
            $ujiLarutanModel = model(UjiLarutanModel::class);
            $insertUjiLarutanDttAlkacid = $ujiLarutanModel->insert($data);
            $insertUjiLarutanDttAlkacidLog = ($insertUjiLarutanDttAlkacid) ? "Insert" : "Gagal insert";
            $insertUjiLarutanDttAlkacidLog .= " uji efektifitas larutan dtt alkacid dengan id " . $insertUjiLarutanDttAlkacid;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertUjiLarutanDttAlkacidLog
            ]);
            if (!$insertUjiLarutanDttAlkacid) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertUjiLarutanDttAlkacidLog
                        ]
                    ]
                );
            }
            $uploadLarutan->move('img/ujilarutan', $data['upload_larutan']);
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

    public function editUjiLarutan($id)
    {
        $ujiLarutanModel = model(UjiLarutanModel::class);
        $dataUjiLarutanBerdasarkanId = $ujiLarutanModel->find($id);
        if (!$dataUjiLarutanBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Edit Uji Larutan DTT Alkacyd',
            'header' => 'Uji Efektifitas Larutan DTT Alkacyd',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataUjiLarutanBerdasarkanId' => $dataUjiLarutanBerdasarkanId
        ];
        return view('dekontaminasi/ujilarutan/edit_ujilarutan_view', $data);
    }

    public function updateUjiLarutan($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalUji' => $this->validation->getError('tanggalUji'),
                    'invalidPetugas' => $this->validation->getError('petugas'),
                    'invalidNamaLarutan' => $this->validation->getError('namaFile'),
                    'invalidUploadLarutan' => $this->validation->getError('uploadLarutan'),
                    'invalidCheckboxMetracid' => $this->validation->getError('checkboxMetracid'),
                    'invalidCheckboxAlkacid' => $this->validation->getError('checkboxAlkacid'),
                    'invalidHasilWarna' => $this->validation->getError('radioHasilWarna'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $ujiLarutanModel = model(UjiLarutanModel::class);
            $dataUjiLarutanBerdasarkanId = $ujiLarutanModel->find($id);
            if (!$dataUjiLarutanBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal Edit',
                            'teks' => 'Data tidak ditemukan',
                            'errorSimpan' => 'Data tidak ditemukan'
                        ]
                    ]
                );
            }

            $tanggalUji = $this->request->getVar('tanggalUji');
            $idPetugas = $this->request->getVar('petugas');
            $metracid1Ml = $this->request->getVar('checkboxMetracid');
            $alkacid10Ml = $this->request->getVar('checkboxAlkacid');
            $hasilWarna = $this->request->getVar('radioHasilWarna');
            $keterangan = $this->request->getVar('keterangan');
            $uploadLarutan = $this->request->getFile('uploadLarutan');

            if ($uploadLarutan->getError() == 4) {
                $larutanUpload = $dataUjiLarutanBerdasarkanId['upload_larutan'];
            } else {
                $larutanUpload = $uploadLarutan->getRandomName();
                $uploadLarutan->move('img/ujilarutan', $larutanUpload);
                unlink('img/ujilarutan/' . $dataUjiLarutanBerdasarkanId['upload_larutan']);
            }

            $data = [
                'tanggal_uji' => $tanggalUji,
                'id_petugas' => $idPetugas,
                'metracid_1_ml' => $metracid1Ml,
                'alkacid_10_ml' => $alkacid10Ml,
                'hasil_warna' => $hasilWarna,
                'upload_larutan' => $larutanUpload,
                'keterangan' => $keterangan,
            ];

            $updateUjiLarutan = $ujiLarutanModel->update($id, $data, false);
            $updateUjiLarutanLog = ($updateUjiLarutan) ? "Update" : "Gagal update";
            $updateUjiLarutanLog .= " uji efektifitas larutan dtt alkacid dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateUjiLarutanLog
            ]);
            if (!$updateUjiLarutan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateUjiLarutanLog
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

    public function hapusUjiLarutan($id)
    {
        if ($this->request->isAJAX()) {
            $ujiLarutanModel = model(UjiLarutanModel::class);
            $dataUjiLarutanBerdasarkanId = $ujiLarutanModel->find($id);
            if (!$dataUjiLarutanBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Ditemukan'
                        ]
                    ]
                );
            }

            $deleteUjiLarutan = $ujiLarutanModel->delete($id, false);
            $deleteUjiLarutanLog = ($deleteUjiLarutan) ? "Delete" : "Gagal delete";
            $deleteUjiLarutanLog .= " uji efektifitas larutan dtt alkacid dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteUjiLarutanLog
            ]);
            if (!$deleteUjiLarutan) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteUjiLarutanLog
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
