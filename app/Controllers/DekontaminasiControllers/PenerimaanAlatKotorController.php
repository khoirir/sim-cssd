<?php

namespace App\Controllers\DekontaminasiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DataBmhpModel;
use App\Models\DataModels\DepartemenModel;
use App\Models\DataModels\PegawaiModel;
use App\Models\DataModels\SetAlatModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorDetailModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorModel;

class PenerimaanAlatKotorController extends BaseController
{
    protected $valid;
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $departemenModel = model(DepartemenModel::class);
        $data = [
            'title' => 'Penerimaan Alat Kotor',
            'header' => 'Penerimaan Alat/Instrumen Kotor dan Monitoring Dekontaminasi',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
            'listDepartemen' => $departemenModel->getListDepartemen(),
        ];
        return view('dekontaminasi/penerimaanalatkotor/index_penerimaanalatkotor_view', $data);
    }

    public function dataAlatKotorBerdasarkanIdRuanganDanMesin()
    {
        if ($this->request->isAJAX()) {
            $idRuangan = $this->request->getPost('idRuangan');
            $departemenModel = model(DepartemenModel::class);
            $validasiRuangan = $departemenModel->find($idRuangan);
            if (!$validasiRuangan) {
                return $this->response->setJSON(['sukses' => false]);
            }

            $mesin = $this->request->getPost('mesin');
            $daftarMesin = [
                'Steam',
                'EOG',
                'Plasma'
            ];
            $validasiMesin = in_array($mesin, $daftarMesin);
            if (!$validasiMesin) {
                return $this->response->setJSON(['sukses' => false]);
            }

            if ($idRuangan === 'CSSD') {
                $setAlatModel = model(SetAlatModel::class);
                $dataAlatKotor = $setAlatModel
                    ->where('id_jenis', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')
                    ->where('deleted_at', null)
                    ->orderBy('nama_set_alat')
                    ->findAll();

                foreach ($dataAlatKotor as $data) {
                    $opt = [];
                    $opt = [
                        "id" => $data['id'],
                        "text" => $data['nama_set_alat'],
                        "html" => "<div>" . $data['nama_set_alat'] . "</div>"
                    ];
                    $optionData[] = $opt;
                }

                $json = [
                    'sukses' => true,
                    'data' => $optionData
                ];

                return $this->response->setJSON($json);
            }
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $dataAlatKotor = $penerimaanAlatKotorModel
                ->dataAlatKotorBerdasarkanIdRuanganDanMesin($idRuangan, $mesin)
                ->getResultArray();

            $optionData = [];
            foreach ($dataAlatKotor as $data) {
                $diproses = (int)$data['jumlah'] - (int)$data['sisa'];
                $opt = [
                    "id" => $data['id_set_alat'],
                    "detail" => $data['id_detail'],
                    "jumlah" => $data['sisa'],
                    "text" => $data['nama_set_alat'],
                    "html" => "<div>
                            <strong>" . $data['nama_set_alat'] . "</strong>
                            <table class='table table-sm mb-0' style='font-size: 0.8em;'>
                                <tr>
                                    <td><strong>Petugas Penyetor:</strong><br>" . $data['petugas_penyetor'] . "</td>
                                    <td><strong>Tanggal Terima:</strong><br>" . date("d-m-Y H:i", strtotime($data['tanggal_penerimaan'])) . "</td>
                                    <td><strong>Jumlah Terima:</strong><br>" . $data['jumlah'] . "</td>
                                    <td><strong>Diproses:</strong><br>" . $diproses . "</td>
                                    <td><strong>Tersedia:</strong><br>" . $data['sisa'] . "</td>
                                </tr>
                                <tr><td colspan='5'/></tr>
                            </table>
                        </div>"
                ];
                $optionData[] = $opt;
            }

            $json = [
                'sukses' => true,
                'data' => $optionData
            ];

            return $this->response->setJSON($json);
        }
    }

    public function dataPenerimaanAlatKotorBerdasarkanFilter()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $ruangan = $this->request->getPost('ruangan');
            $dokumentasi = $this->request->getPost('dokumentasi');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);

            $dataPenerimaanAlatKotorBerdasarkanFilter = $penerimaanAlatKotorModel
                ->dataPenerimaanAlatKotorBerdasarkanFilter($tglAwal, $tglAkhir, $start, $limit, $ruangan, $dokumentasi)
                ->getResultArray();

            $jumlahDataPenerimaanAlatKotorBerdasarkanTanggal = $penerimaanAlatKotorModel
                ->dataPenerimaanAlatKotorBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $jumlahDataPenerimaanAlatKotorBerdasarkanTanggalFilter = $penerimaanAlatKotorModel
                ->dataPenerimaanAlatKotorBerdasarkanTanggalFilter($tglAwal, $tglAkhir, $ruangan, $dokumentasi)
                ->getNumRows();

            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $dataResult = [];
            foreach ($dataPenerimaanAlatKotorBerdasarkanFilter as $data) {
                $statusAlatKotor = $penerimaanAlatKotorDetailModel
                    ->dataStatusAlatKotor($data['id'])
                    ->getRowArray();

                $statusProses = empty(trim($statusAlatKotor['status_proses'])) ? "" : "<span class='badge badge-info'>Diproses</span>";
                $statusDistribusi = empty(trim($statusAlatKotor['status_distribusi'])) ? "" : " <span class='badge badge-success'>Distribusi</span>";

                $statusAlat = $statusProses . $statusDistribusi;

                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'tanggalPenerimaan' => date("d-m-Y", strtotime($data['tanggal_penerimaan'])),
                    'jamPenerimaan' => date("H:i", strtotime($data['tanggal_penerimaan'])),
                    'ruangan' => $data['ruangan'],
                    'petugasPenyetor' => $data['petugas_penyetor'],
                    'petugasCSSD' => $data['petugas_cssd'],
                    'dokumentasi' => $data['upload_dokumentasi'],
                    'id' => $data['id'],
                    'statusAlat' => $statusAlat
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataPenerimaanAlatKotorBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataPenerimaanAlatKotorBerdasarkanTanggalFilter,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailPenerimaanAlatKotor($id)
    {
        if ($this->request->isAJAX()) {
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $dataPenerimaanAlatKotorBerdasarkanId = $penerimaanAlatKotorModel
                ->dataPenerimaanAlatKotorBerdasarkanId($id)
                ->getFirstRow('array');

            if (!$dataPenerimaanAlatKotorBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $dataPenerimaanAlatKotorDetailBerdasarkanIdMaster = $penerimaanAlatKotorDetailModel
                ->dataPenerimaanAlatKotorDetailBerdasarkanIdMaster($id);

            $data = [
                'dataPenerimaan' => $dataPenerimaanAlatKotorBerdasarkanId,
                'dataDetail' => $dataPenerimaanAlatKotorDetailBerdasarkanIdMaster
            ];

            $json = [
                'sukses' => true,
                'data' => view('dekontaminasi/penerimaanalatkotor/modal_detail_penerimaan_alat_kotor', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function dokumentasi()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            if (!$id) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $dataPenerimaanAlatKotor = $penerimaanAlatKotorModel->find($id);
            if (!$dataPenerimaanAlatKotor) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $data = [
                'id' => implode(";", $id)
            ];

            $json = [
                'sukses' => true,
                'data' => view('dekontaminasi/penerimaanalatkotor/modal_upload_dokumentasi_penerimaan_alat_kotor', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function uploadDokumentasi()
    {
        if ($this->request->isAJAX()) {
            $valid = $this->validate([
                'uploadDokumentasi' => [
                    'rules' => 'uploaded[uploadDokumentasi]|max_size[uploadDokumentasi,1024]|is_image[uploadDokumentasi]|mime_in[uploadDokumentasi,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                    'errors' => [
                        'uploaded' => 'Gambar harus diupload',
                        'max_size' => 'Ukuran gambar dokumentasi terlalu besar (Maks. 1MB)',
                        'is_image' => 'Format gambar dokumentasi harus *jpg, jpeg, png*',
                        'mime_in' => 'Format gambar dokumentasi harus *jpg, jpeg, png*'
                    ]
                ]
            ]);
            if (!$valid) {
                $pesanError = [
                    'invalidUploadDokumentasi' => $this->validation->getError('uploadDokumentasi'),
                ];
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $idPenerimaanAlatKotor = $this->request->getVar('idPenerimaanAlatKotor');
            $id = explode(';', $idPenerimaanAlatKotor);
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $dataPenerimaanAlatKotor = $penerimaanAlatKotorModel->find($id);
            if (!$dataPenerimaanAlatKotor) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }
            $uploadDokumentasi = $this->request->getFile('uploadDokumentasi');

            $data = [
                'upload_dokumentasi' => $uploadDokumentasi->getRandomName(),
            ];

            $updateDokumentasiPenerimaanAlatKotor = $penerimaanAlatKotorModel->update($id, $data);
            $updateDokumentasiPenerimaanAlatKotorLog = ($updateDokumentasiPenerimaanAlatKotor) ? "Update" : "Gagal update";
            $updateDokumentasiPenerimaanAlatKotorLog .= " dokumentasi penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id " . $idPenerimaanAlatKotor;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateDokumentasiPenerimaanAlatKotorLog
            ]);
            if (!$updateDokumentasiPenerimaanAlatKotor) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diupload',
                            'errorSimpan' => $updateDokumentasiPenerimaanAlatKotorLog
                        ]
                    ]
                );
            }
            $uploadDokumentasi->move('img/penerimaanalatkotor', $data['upload_dokumentasi']);
            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Diupload'
                    ]
                ]
            );
        }
    }

    public function hapusDokumentasi($id)
    {
        if ($this->request->isAJAX()) {
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $dataPenerimaanAlatKotorBerdasarkanId = $penerimaanAlatKotorModel->find($id);
            if (!$dataPenerimaanAlatKotorBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Ditemukan',
                        ]
                    ]
                );
            }
            $dataDokumentasi = $dataPenerimaanAlatKotorBerdasarkanId['upload_dokumentasi'];
            $jumlahDokumentasiSama = $penerimaanAlatKotorModel->where('upload_dokumentasi', $dataDokumentasi)->get()->getNumRows();
            if ($jumlahDokumentasiSama === 1) {
                unlink('img/penerimaanalatkotor/' . $dataDokumentasi);
            }

            $deleteDokumentasi = $penerimaanAlatKotorModel->update($id, ["upload_dokumentasi" => null]);
            $deleteDokumentasiLog = ($deleteDokumentasi) ? "Delete" : "Gagal delete";
            $deleteDokumentasiLog .= " dokumentasi penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteDokumentasiLog
            ]);
            if (!$deleteDokumentasi) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteDokumentasiLog
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

    public function validasiForm()
    {
        $this->valid = $this->validate([
            'tanggalPenerimaan' => [
                'rules' => 'required|not_future_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                ],
            ],
            'jamPenerimaan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam harus diisi',
                ],
            ],
            'jamTerima' => [
                'rules' => 'not_future_time',
            ],
            'petugasCSSD' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Petugas CSSD harus diisi',
                ]
            ],
            'petugasPenyetor' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Petugas Penyetor harus diisi',
                ]
            ],
            'ruangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Ruangan harus diisi',
                ]
            ],
            'jumlahDataSetAlatDetail' => [
                'rules' => 'greater_than[0]',
                'errors' => [
                    'greater_than' => 'Tabel Set/Alat harus diisi'
                ]
            ]
        ]);
    }

    public function tambahPenerimaanAlatKotor()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);
        $setAlatModel = model(SetAlatModel::class);
        $data = [
            'title' => 'Tambah Penerimaan Alat Kotor',
            'header' => 'Penerimaan Alat/Instrumen Kotor dan Monitoring Dekontaminasi',
            'tglSekarang' => date('Y-m-d'),
            'jamSekarang' => date('H:i'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listPegawai' => $pegawaiModel->getListPegawai(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'listSetAlat' => $setAlatModel->where('id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')
                ->orderBy('nama_set_alat')
                ->findAll()
        ];
        return view('dekontaminasi/penerimaanalatkotor/tambah_penerimaanalatkotor_view', $data);
    }

    public function simpanPenerimaanAlatKotor()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalPenerimaan' => $this->validation->getError('tanggalPenerimaan'),
                    'invalidJamPenerimaan' => $this->validation->getError('jamPenerimaan'),
                    'invalidJamTerima' => $this->validation->getError('jamTerima'),
                    'invalidPetugasCSSD' => $this->validation->getError('petugasCSSD'),
                    'invalidPetugasPenyetor' => $this->validation->getError('petugasPenyetor'),
                    'invalidRuangan' => $this->validation->getError('ruangan'),
                    'invalidTabelDataSetAlat' => $this->validation->getError('jumlahDataSetAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalPenerimaan = date('Y-m-d H:i:s', strtotime($this->request->getVar('jamPenerimaan')));
            $petugasCSSD = $this->request->getPost('petugasCSSD');
            $petugasPenyetor = $this->request->getPost('petugasPenyetor');
            $ruangan = $this->request->getPost('ruangan');
            $dataPenerimaanAlatKotorDetail = $this->request->getPost('dataSetAlat');

            $data = [
                'id' => generateUUID(),
                'tanggal_penerimaan' => $tanggalPenerimaan,
                'id_petugas_cssd' => $petugasCSSD,
                'id_petugas_penyetor' => $petugasPenyetor,
                'id_ruangan' => $ruangan,
            ];
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $insertPenerimaanAlatKotor = $penerimaanAlatKotorModel->insert($data);
            $insertPenerimaanAlatKotorLog = ($insertPenerimaanAlatKotor) ? "Insert" : "Gagal insert";
            $insertPenerimaanAlatKotorLog .= " penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id master " . $insertPenerimaanAlatKotor;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertPenerimaanAlatKotorLog
            ]);
            if (!$insertPenerimaanAlatKotor) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertPenerimaanAlatKotorLog
                        ]
                    ]
                );
            }

            $dataInsertPenerimaanAlatKotorDetail = [];
            foreach ($dataPenerimaanAlatKotorDetail as $data) {
                $dataDetail = [
                    'id_master' => $insertPenerimaanAlatKotor,
                    'id_set_alat' => $data['idSetAlat'],
                    'jumlah' => $data['jumlah'],
                    'sisa' => $data['jumlah'],
                    'sisa_distribusi' => $data['jumlah'],
                    'enzym' => $data['enzym'] ?? '',
                    'dtt' => $data['dtt'] ?? '',
                    'ultrasonic' => $data['ultrasonic'] ?? '',
                    'bilas' => $data['bilas'] ?? '',
                    'washer' => $data['washer'] ?? '',
                    'pemilihan_mesin' => $data['pemilihanMesin'],
                ];
                array_push($dataInsertPenerimaanAlatKotorDetail, $dataDetail);
            }
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $insertPenerimaanAlatKotorDetail = $penerimaanAlatKotorDetailModel->insertMultiple($dataInsertPenerimaanAlatKotorDetail);
            $insertPenerimaanAlatKotorDetailLog = ($insertPenerimaanAlatKotorDetail) ? "Insert" : "Gagal insert";
            $insertPenerimaanAlatKotorDetailLog .= " detail penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id master " . $insertPenerimaanAlatKotor;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertPenerimaanAlatKotorDetailLog
            ]);
            if (!$insertPenerimaanAlatKotorDetail) {
                $penerimaanAlatKotorModel->delete($insertPenerimaanAlatKotor);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertPenerimaanAlatKotorDetailLog
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

    public function editPenerimaanAlatKotor($id = null)
    {
        $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
        $dataPenerimaanAlatKotorBerdasarkanId = $penerimaanAlatKotorModel->find($id);
        if (!$dataPenerimaanAlatKotorBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
        $jumlahDiproses = $penerimaanAlatKotorDetailModel
            ->where('id_master', $id)
            ->where('status_proses', 'Diproses')
            ->get()
            ->getNumRows();

        if ($jumlahDiproses > 0) {
            return redirect()->to('/penerimaan-alat-kotor');
        }

        $dataPenerimaanAlatKotorDetailBerdasarkanIdMaster = $penerimaanAlatKotorDetailModel
            ->dataPenerimaanAlatKotorDetailBerdasarkanIdMaster($dataPenerimaanAlatKotorBerdasarkanId['id']);
        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);
        $setAlatModel = model(SetAlatModel::class);
        $data = [
            'title' => 'Edit Penerimaan Alat Kotor',
            'header' => 'Penerimaan Alat/Instrumen Kotor dan Monitoring Dekontaminasi',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listPegawai' => $pegawaiModel->getListPegawai(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'listSetAlat' => $setAlatModel->where('id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')->orderBy('nama_set_alat')->findAll(),
            'dataPenerimaanAlatKotorBerdasarkanId' => $dataPenerimaanAlatKotorBerdasarkanId,
            'dataPenerimaanAlatKotorDetailBerdasarkanIdMaster' => $dataPenerimaanAlatKotorDetailBerdasarkanIdMaster
        ];
        return view('dekontaminasi/penerimaanalatkotor/edit_penerimaanalatkotor_view', $data);
    }

    public function updatePenerimaanAlatKotor($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalPenerimaan' => $this->validation->getError('tanggalPenerimaan'),
                    'invalidJamPenerimaan' => $this->validation->getError('jamPenerimaan'),
                    'invalidPetugasCSSD' => $this->validation->getError('petugasCSSD'),
                    'invalidPetugasPenyetor' => $this->validation->getError('petugasPenyetor'),
                    'invalidRuangan' => $this->validation->getError('ruangan'),
                    'invalidTabelDataSetAlat' => $this->validation->getError('jumlahDataSetAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }
            $tanggalPenerimaan = date('Y-m-d H:i:s', strtotime($this->request->getVar('jamPenerimaan')));
            $petugasCSSD = $this->request->getPost('petugasCSSD');
            $petugasPenyetor = $this->request->getPost('petugasPenyetor');
            $ruangan = $this->request->getPost('ruangan');
            $dataPenerimaanAlatKotorDetail = $this->request->getPost('dataSetAlat');
            $idDetailSetAlatHapus = $this->request->getPost('idDetailSetAlatHapus');

            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            if ($idDetailSetAlatHapus) {
                $deletePenerimaanAlatKotorDetail = $penerimaanAlatKotorDetailModel->delete($idDetailSetAlatHapus);
                $deletePenerimaanAlatKotorDetailLog = ($deletePenerimaanAlatKotorDetail) ? "Delete" : "Gagal delete";
                $deletePenerimaanAlatKotorDetailLog .= " detail penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id " . implode("; ", $idDetailSetAlatHapus);
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $deletePenerimaanAlatKotorDetailLog
                ]);
                if (!$deletePenerimaanAlatKotorDetail) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => $deletePenerimaanAlatKotorDetailLog
                        ]
                    );
                }
            }

            $dataUpdatePenerimaanAlatKotor = [
                'tanggal_penerimaan' => $tanggalPenerimaan,
                'id_petugas_cssd' => $petugasCSSD,
                'id_petugas_penyetor' => $petugasPenyetor,
                'id_ruangan' => $ruangan,
            ];
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $updatePenerimaanAlatKotor = $penerimaanAlatKotorModel->update($id, $dataUpdatePenerimaanAlatKotor, false);
            $updatePenerimaanAlatKotorLog = ($updatePenerimaanAlatKotor) ? "Update" : "Gagal update";
            $updatePenerimaanAlatKotorLog .= " penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updatePenerimaanAlatKotorLog
            ]);
            if (!$updatePenerimaanAlatKotor) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updatePenerimaanAlatKotorLog
                        ]
                    ]
                );
            }

            if ($dataPenerimaanAlatKotorDetail) {
                $dataInsertPenerimaanAlatKotorBaruDetail = [];
                foreach ($dataPenerimaanAlatKotorDetail as $data) {
                    $dataDetail = [
                        'id_master' => $id,
                        'id_set_alat' => $data['idSetAlat'],
                        'jumlah' => $data['jumlah'],
                        'sisa' => $data['jumlah'],
                        'sisa_distribusi' => $data['jumlah'],
                        'enzym' => $data['enzym'] ?? '',
                        'dtt' => $data['dtt'] ?? '',
                        'ultrasonic' => $data['ultrasonic'] ?? '',
                        'bilas' => $data['bilas'] ?? '',
                        'washer' => $data['washer'] ?? '',
                        'pemilihan_mesin' => $data['pemilihanMesin'],
                    ];
                    array_push($dataInsertPenerimaanAlatKotorBaruDetail, $dataDetail);
                }
                $insertPenerimaanAlatKotorDetail = $penerimaanAlatKotorDetailModel->insertMultiple($dataInsertPenerimaanAlatKotorBaruDetail);
                $insertPenerimaanAlatKotorDetailLog = ($insertPenerimaanAlatKotorDetail) ? "Insert" : "Gagal insert";
                $insertPenerimaanAlatKotorDetailLog .= " detail baru penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertPenerimaanAlatKotorDetailLog
                ]);
                if (!$insertPenerimaanAlatKotorDetail) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Diedit',
                                'errorSimpan' => $insertPenerimaanAlatKotorDetailLog
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

    public function hapusPenerimaanAlatKotor($id)
    {
        if ($this->request->isAJAX()) {
            $penerimaanAlatKotorModel = model(PenerimaanAlatKotorModel::class);
            $dataPenerimaanAlatKotorBerdasarkanId = $penerimaanAlatKotorModel->find($id);
            if (!$dataPenerimaanAlatKotorBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Ditemukan',
                        ]
                    ]
                );
            }
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $jumlahDiproses = $penerimaanAlatKotorDetailModel
                ->where('id_master', $id)
                ->where('status_proses', 'Diproses')
                ->get()
                ->getNumRows();

            if ($jumlahDiproses > 0) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Status Sudah Diproses',
                        ]
                    ]
                );
            }
            $deletePenerimaanAlatKotor = $penerimaanAlatKotorModel->delete($id, false);
            $deletePenerimaanAlatKotorLog = ($deletePenerimaanAlatKotor) ? "Delete" : "Gagal delete";
            $deletePenerimaanAlatKotorLog .= " penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deletePenerimaanAlatKotorLog
            ]);
            if (!$deletePenerimaanAlatKotor) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorSimpan' => $deletePenerimaanAlatKotorLog
                        ]
                    ]
                );
            }

            $deletePenerimaanAlatKotorDetail = $penerimaanAlatKotorDetailModel->where('id_master', $id)->delete();
            $deletePenerimaanAlatKotorDetailLog = ($deletePenerimaanAlatKotorDetail) ? "Delete" : "Gagal delete";
            $deletePenerimaanAlatKotorDetailLog .= " detail penerimaan alat/instrumen kotor dan monitoring dekontaminasi dengan id master " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deletePenerimaanAlatKotorDetailLog
            ]);
            if (!$deletePenerimaanAlatKotorDetail) {
                $penerimaanAlatKotorModel->update($id, ['deleted_at' => null]);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorSimpan' => $deletePenerimaanAlatKotorDetailLog
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
