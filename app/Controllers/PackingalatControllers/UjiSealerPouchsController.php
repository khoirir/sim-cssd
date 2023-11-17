<?php

namespace App\Controllers\PackingalatControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\PegawaiModel;
use App\Models\PackingAlatModels\UjiSealerPouchsModel;

class UjiSealerPouchsController extends BaseController
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
            'title' => 'Uji Sealer Pouchs',
            'header' => 'Uji Efektivitas Sealer Pouchs',
            'tglAwal' => $tglAwal,
            'tglSekarang' => $tglSekarang,
        ];
        return view('packingalat/ujisealerpouchs/index_ujisealerpouchs_view', $data);
    }

    public function tambahUjiSealerPouchs()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Uji Sealer Pouchs',
            'header' => 'Uji Efektivitas Sealer Pouchs',
            'tanggalSekarang' => date('Y-m-d'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
        ];
        return view('packingalat/ujisealerpouchs/tambah_ujisealerpouchs_view', $data);
    }

    public function dataUjiSealerPouchs()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal');
            $tglAkhir = $this->request->getPost('tglAkhir');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $hasil = $this->request->getPost('hasil');

            $ujiSealerPouchsModel = model(UjiSealerPouchsModel::class);
            $dataUjiSealerPouchsDenganFilter = $ujiSealerPouchsModel
                ->dataUjiSealerPouchsDenganFilter($tglAwal, $tglAkhir, $start, $limit, $hasil)
                ->getResultArray();
            $jumlahDataUjiSealerPouchsBerdasarkanTanggal = $ujiSealerPouchsModel
                ->jumlahDataUjiSealerPouchsBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $jumlahDataUjiSealerPouchsBerdasarkanTanggalDanHasil = $ujiSealerPouchsModel
                ->jumlahDataUjiSealerPouchsBerdasarkanTanggalDanHasil($tglAwal, $tglAkhir, $hasil)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataUjiSealerPouchsDenganFilter as $data) {
                $hasilUji = $data['hasil_uji'];
                $keterangan = $data['keterangan'] ?: $hasilUji;
                $warnaBadge = (strtolower($hasilUji) === 'bocor') ? 'danger' : 'success';
                $badgeKeterangan = "<span class=\"badge badge-" . $warnaBadge . "\">" . ucfirst($keterangan) . "</span>";
                $td = [
                    "noReferensi" => generateNoReferensi($data['created_at'], $data['id']),
                    "tanggalUji" => date("d-m-Y", strtotime($data['tanggal_uji'])),
                    "suhu" => $data['suhu_mesin_200'] === 'checked' ? '<i class="fas fa-check"></i>' : '',
                    "speed" => $data['speed_sedang'] === 'checked' ? '<i class="fas fa-check"></i>' : '',
                    "bocor" => strtolower($hasilUji === 'bocor') ? '<i class="fas fa-check"></i>' : '',
                    "tidak" => strtolower($hasilUji === 'tidak') ? '<i class="fas fa-check"></i>' : '',
                    "petugas" => $data['nama'],
                    "keterangan" => $badgeKeterangan,
                    "uploadBuktiUji" => $data['upload_bukti_uji'],
                    "id" => $data['id']
                ];
                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataUjiSealerPouchsBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataUjiSealerPouchsBerdasarkanTanggalDanHasil,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function gambarUjiSealerPouchs($id)
    {
        if ($this->request->isAJAX()) {
            $ujiSealerPouchsModel = model(UjiSealerPouchsModel::class);
            $dataUjiSealer = $ujiSealerPouchsModel->find($id);
            if (!$dataUjiSealer) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $data = [
                'noReferensi' => generateNoReferensi($dataUjiSealer['created_at'], $dataUjiSealer['id']),
                'sealer' => $dataUjiSealer['upload_bukti_uji']
            ];

            $json = [
                'sukses' => true,
                'data' => view('packingalat/ujisealerpouchs/modal_gambar_ujisealerpouchs', $data)
            ];

            return $this->response->setJSON($json);
        }
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
                        'required' => 'Gambar bukti uji sealer harus diupload',
                    ]
                ],
                'uploadBuktiUjiSealer' => [
                    'rules' => 'max_size[uploadBuktiUjiSealer,512]|is_image[uploadBuktiUjiSealer]|mime_in[uploadBuktiUjiSealer,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                    'errors' => [
                        'max_size' => 'Ukuran gambar bukti uji sealer terlalu besar (Maks. 512KB)',
                        'is_image' => 'Format gambar bukti uji sealer harus *jpg, jpeg, png*',
                        'mime_in' => 'Format gambar bukti uji sealer harus *jpg, jpeg, png*'
                    ]
                ],
                'checkboxSuhuMesin' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Suhu Mesin 200 harus dipilih'
                    ]
                ],
                'checkboxSpeedSedang' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Speed Sedang harus dipilih'
                    ]
                ],
                'radioHasilUji' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Hasil harus dipilih'
                    ]
                ],
            ]
        );
    }

    public function simpanUjiSealerPouchs()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalUji' => $this->validation->getError('tanggalUji'),
                    'invalidPetugas' => $this->validation->getError('petugas'),
                    'invalidNamaBuktiUjiSealer' => $this->validation->getError('namaFile'),
                    'invalidUploadBuktiUjiSealer' => $this->validation->getError('uploadBuktiUjiSealer'),
                    'invalidCheckboxSuhuMesin' => $this->validation->getError('checkboxSuhuMesin'),
                    'invalidCheckboxSpeedSedang' => $this->validation->getError('checkboxSpeedSedang'),
                    'invalidHasilUji' => $this->validation->getError('radioHasilUji'),
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
            $suhuMesin200 = $this->request->getVar('checkboxSuhuMesin');
            $speedSedang = $this->request->getVar('checkboxSpeedSedang');
            $hasilUji = $this->request->getVar('radioHasilUji');
            $keterangan = $this->request->getVar('keterangan');
            $uploadBuktiUjiSealer = $this->request->getFile('uploadBuktiUjiSealer');

            $data = [
                'id' => generateUUID(),
                'tanggal_uji' => $tanggalUji,
                'id_petugas' => $idPetugas,
                'suhu_mesin_200' => $suhuMesin200,
                'speed_sedang' => $speedSedang,
                'hasil_uji' => $hasilUji,
                'upload_bukti_uji' => $uploadBuktiUjiSealer->getRandomName(),
                'keterangan' => $keterangan,
            ];
            $ujiSealerPouchsModel = model(UjiSealerPouchsModel::class);
            $insertUjiSealerPouchs = $ujiSealerPouchsModel->insert($data);
            $insertUjiSealerPouchsLog = ($insertUjiSealerPouchs) ? "Insert" : "Gagal insert";
            $insertUjiSealerPouchsLog .= " uji efektifitas sealer pouchs dengan id " . $insertUjiSealerPouchs;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertUjiSealerPouchsLog
            ]);
            if (!$insertUjiSealerPouchs) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertUjiSealerPouchsLog
                        ]
                    ]
                );
            }
            $uploadBuktiUjiSealer->move('public/img/ujisealerpouchs', $data['upload_bukti_uji']);
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

    public function editUjiSealerPouchs($id)
    {
        $ujiSealerPouchsModel = model(UjiSealerPouchsModel::class);
        $dataUjiSealerPouchsBerdasarkanId = $ujiSealerPouchsModel->find($id);
        if (!$dataUjiSealerPouchsBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pegawaiModel = model(PegawaiModel::class);
        $data = [
            'title' => 'Uji Sealer Pouchs',
            'header' => 'Uji Efektivitas Sealer Pouchs',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataUjiSealerPouchsBerdasarkanId' => $dataUjiSealerPouchsBerdasarkanId
        ];
        return view('packingalat/ujisealerpouchs/edit_ujisealerpouchs_view', $data);
    }

    public function updateUjiSealerPouchs($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalUji' => $this->validation->getError('tanggalUji'),
                    'invalidPetugas' => $this->validation->getError('petugas'),
                    'invalidNamaBuktiUjiSealer' => $this->validation->getError('namaFile'),
                    'invalidUploadBuktiUjiSealer' => $this->validation->getError('uploadBuktiUjiSealer'),
                    'invalidCheckboxSuhuMesin' => $this->validation->getError('checkboxSuhuMesin'),
                    'invalidCheckboxSpeedSedang' => $this->validation->getError('checkboxSpeedSedang'),
                    'invalidHasilUji' => $this->validation->getError('radioHasilUji'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $ujiSealerPouchsModel = model(UjiSealerPouchsModel::class);
            $dataSealerPouchsBerdasarkanId = $ujiSealerPouchsModel->find($id);
            if (!$dataSealerPouchsBerdasarkanId) {
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
            $suhuMesin200 = $this->request->getVar('checkboxSuhuMesin');
            $speedSedang = $this->request->getVar('checkboxSpeedSedang');
            $hasilUji = $this->request->getVar('radioHasilUji');
            $keterangan = $this->request->getVar('keterangan');
            $uploadBuktiUjiSealer = $this->request->getFile('uploadBuktiUjiSealer');

            if ($uploadBuktiUjiSealer->getError() == 4) {
                $buktiUjiUpload = $dataSealerPouchsBerdasarkanId['upload_bukti_uji'];
            } else {
                $buktiUjiUpload = $uploadBuktiUjiSealer->getRandomName();
                $uploadBuktiUjiSealer->move('public/img/ujisealerpouchs', $buktiUjiUpload);
                unlink('public/img/ujisealerpouchs/' . $dataSealerPouchsBerdasarkanId['upload_bukti_uji']);
            }

            $data = [
                'tanggal_uji' => $tanggalUji,
                'id_petugas' => $idPetugas,
                'suhu_mesin_200' => $suhuMesin200,
                'speed_sedang' => $speedSedang,
                'hasil_uji' => $hasilUji,
                'upload_bukti_uji' => $buktiUjiUpload,
                'keterangan' => $keterangan,
            ];

            $updateUjiSealerPouchs = $ujiSealerPouchsModel->update($id, $data, false);
            $updateUjiSealerPouchsLog = ($updateUjiSealerPouchs) ? "Update" : "Gagal update";
            $updateUjiSealerPouchsLog .= " uji efektifitas sealer pouchs dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateUjiSealerPouchsLog
            ]);
            if (!$updateUjiSealerPouchs) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateUjiSealerPouchsLog
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

    public function hapusUjiSealerPouchs($id)
    {
        if ($this->request->isAJAX()) {
            $ujiSealerPouchsModel = model(UjiSealerPouchsModel::class);
            $deleteUjiSealerPouchs = $ujiSealerPouchsModel->delete($id, false);
            $deleteUjiSealerPouchsLog = ($deleteUjiSealerPouchs) ? "Delete" : "Gagal delete";
            $deleteUjiSealerPouchsLog .= " uji efektifitas sealer pouchs dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteUjiSealerPouchsLog
            ]);
            if (!$deleteUjiSealerPouchs) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteUjiSealerPouchsLog
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
