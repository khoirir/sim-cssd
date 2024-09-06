<?php

namespace App\Controllers\DistribusiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DepartemenModel;
use App\Models\DataModels\PegawaiModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorDetailModel;
use App\Models\DistribusiModels\PermintaanAlatSterilDetailModel;
use App\Models\DistribusiModels\PermintaanAlatSterilModel;
use App\Models\PackingAlatModels\MonitoringMesinModel;

class PermintaanAlatSterilController extends BaseController
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
            'title' => 'Permintaan Alat Steril',
            'header' => 'Dokumentasi Permintaan Alat/Instrumen Steril',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
            'listDepartemen' => $departemenModel->getListDepartemen(),
        ];
        return view('distribusi/permintaanalatsteril/index_permintaanalatsteril_view', $data);
    }

    public function dataPermintaanAlatSterilBerdasarkanFilter()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $ruangan = $this->request->getPost('ruangan');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);

            $dataPermintaanAlatSterilBerdasarkanFilter = $permintaanAlatSterilModel
                ->dataPermintaanAlatSterilBerdasarkanFilter($tglAwal, $tglAkhir, $start, $limit, $ruangan)
                ->getResultArray();

            $jumlahDataPermintaanAlatSterilBerdasarkanTanggal = $permintaanAlatSterilModel
                ->dataPermintaanAlatSterilBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $jumlahDataPermintaanAlatSterilBerdasarkanTanggaldanRuangan = $permintaanAlatSterilModel
                ->dataPermintaanAlatSterilBerdasarkanTanggaldanRuangan($tglAwal, $tglAkhir, $ruangan)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataPermintaanAlatSterilBerdasarkanFilter as $data) {
                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'tanggalPermintaan' => date("d-m-Y", strtotime($data['tanggal_minta'])),
                    'jamPermintaan' => date("H:i", strtotime($data['tanggal_minta'])),
                    'ruangan' => $data['ruangan'],
                    'petugasMinta' => $data['petugas_minta'],
                    'petugasCSSD' => $data['petugas_cssd'],
                    'id' => $data['id'],
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataPermintaanAlatSterilBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataPermintaanAlatSterilBerdasarkanTanggaldanRuangan,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailPermintaanAlatSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
            $dataPermintaanAlatSterilBerdasarkanId = $permintaanAlatSterilModel
                ->dataPermintaanAlatSterilBerdasarkanId($id)
                ->getRowArray();

            if (!$dataPermintaanAlatSterilBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
            $dataPermintaanAlatSterilDetailBerdasarkanIdMaster = $permintaanAlatSterilDetailModel
                ->dataPermintaanAlaSterilDetailBerdasarkanIdMaster($id)
                ->getResultArray();

            $data = [
                'dataPermintaan' => $dataPermintaanAlatSterilBerdasarkanId,
                'dataDetail' => $dataPermintaanAlatSterilDetailBerdasarkanIdMaster
            ];

            $json = [
                'sukses' => true,
                'data' => view('distribusi/permintaanalatsteril/modal_detail_permintaanalatsteril', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function tambahPermintaanAlatSteril()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);
        $data = [
            'title' => 'Tambah Permintaan Alat Steril',
            'header' => 'Dokumentasi Permintaan Alat/Instrumen Steril',
            'tglSekarang' => date('Y-m-d'),
            'jamSekarang' => date('H:i'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listPegawai' => $pegawaiModel->getListPegawai(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
        ];
        return view('distribusi/permintaanalatsteril/tambah_permintaanalatsteril_view', $data);
    }

    public function dataAlatSteril($idRuangan)
    {
        if ($this->request->isAJAX()) {
            $departemenModel = model(DepartemenModel::class);

            $validasiRuangan = $departemenModel->find($idRuangan);
            if (!$validasiRuangan) {
                return $this->response->setJSON(['sukses' => false]);
            }
            $monitoringMesinModel = model(MonitoringMesinModel::class);
            $dataVerifikasi = $monitoringMesinModel
                ->dataVerifikasiAlatSteril($idRuangan)
                ->getResultArray();

            if ($dataVerifikasi) {
                $idDetailAlatKotor = array_column($dataVerifikasi, 'id_detail_penerimaan_alat_kotor');
                $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
                $dataAlatSteril = $permintaanAlatSterilModel
                    ->dataAlatSteril($idDetailAlatKotor)
                    ->getResultArray();

                $optionData = [];
                foreach ($dataAlatSteril as $data) {
                    $idAlat = $data['id_set_alat'];
                    $idDetail = $data['id_detail'];
                    $alat = $data['nama_set_alat'];
                    $tanggalTerima = date("d-m-Y H:i", strtotime($data['tanggal_penerimaan']));
                    $jumlahTerima = (int)$data['jumlah'];
                    $mesin = $data['mesin'];
                    $jumlahDiproses = $jumlahTerima - (int)$data['sisa'];
                    $jumlahDistribusi = $jumlahTerima - (int)$data['sisa_distribusi'];
                    $jumlahTersedia = $data['sisa_distribusi'];
                    $opt = [
                        "id" => $idAlat,
                        "detail" => $idDetail,
                        "jumlah" => $jumlahTersedia,
                        "text" => $alat,
                        "html" => <<<"HTML"
                                <div>
                                    <strong>$alat</strong>
                                    <table class='table table-sm mb-0' style='font-size: 0.8em;'>
                                        <tr>
                                            <td><strong>Mesin:</strong><br>$mesin</td>
                                            <td><strong>Tanggal Terima:</strong><br>$tanggalTerima</td>
                                            <td><strong>Jumlah Terima:</strong><br>$jumlahTerima</td>
                                            <td><strong>Diproses:</strong><br>$jumlahDiproses</td>
                                            <td><strong>Distribusi:</strong><br>$jumlahDistribusi</td>
                                            <td><strong>Tersedia:</strong><br>$jumlahTersedia</td>
                                        </tr>
                                        <tr><td colspan='6'></tr>
                                    </table>
                                </div>
                            HTML
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
    }

    public function validasiForm()
    {
        $this->valid = $this->validate([
            'tanggalPermintaan' => [
                'rules' => 'required|not_future_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                ],
            ],
            'jamPermintaan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam harus diisi',
                ],
            ],
            'jamMinta' => [
                'rules' => 'not_future_time',
            ],
            'petugasCSSD' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Petugas CSSD harus dipilih',
                ]
            ],
            'petugasMinta' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Petugas minta harus dipilih',
                ]
            ],
            'ruangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Ruangan harus dipilih',
                ]
            ],
            'jumlahDataAlatDetail' => [
                'rules' => 'greater_than[0]',
                'errors' => [
                    'greater_than' => 'Tabel alat harus diisi'
                ]
            ]
        ]);
    }

    public function simpanPermintaanAlatSteril()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalPermintaan' => $this->validation->getError('tanggalPermintaan'),
                    'invalidJamPermintaan' => $this->validation->getError('jamPermintaan'),
                    'invalidJamMinta' => $this->validation->getError('jamMinta'),
                    'invalidPetugasCSSD' => $this->validation->getError('petugasCSSD'),
                    'invalidPetugasMinta' => $this->validation->getError('petugasMinta'),
                    'invalidRuangan' => $this->validation->getError('ruangan'),
                    'invalidTabelDataAlat' => $this->validation->getError('jumlahDataAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $tanggalPermintaan = $this->request->getPost('tanggalPermintaan') . " " . $this->request->getVar('jamPermintaan') . ":00";
            $petugasCSSD = $this->request->getPost('petugasCSSD');
            $petugasMinta = $this->request->getPost('petugasMinta');
            $ruangan = $this->request->getPost('ruangan');
            $dataAlat = $this->request->getPost('dataAlat');

            $data = [
                'id' => generateUUID(),
                'tanggal_minta' => $tanggalPermintaan,
                'id_petugas_cssd' => $petugasCSSD,
                'id_petugas_minta' => $petugasMinta,
                'id_ruangan' => $ruangan,
            ];
            $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
            $insertPermintaanAlatSteril = $permintaanAlatSterilModel->insert($data);
            $insertPermintaanAlatSterilLog = ($insertPermintaanAlatSteril) ? "Insert" : "Gagal insert";
            $insertPermintaanAlatSterilLog .= " dokumentasi permintaan alat/instrumen steril dengan id " . $insertPermintaanAlatSteril;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertPermintaanAlatSterilLog
            ]);
            if (!$insertPermintaanAlatSteril) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertPermintaanAlatSterilLog
                        ]
                    ]
                );
            }
            $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            foreach ($dataAlat as $detail) {
                $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                $jumlah = $detail['jumlah'];
                $alat = $detail['alat'];

                $dataAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);

                if ($jumlah > $dataAlatKotorBerdasarkanId['sisa_distribusi']) {
                    $permintaanAlatSterilModel->delete($insertPermintaanAlatSteril);

                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal Simpan',
                                'teks' => 'Jumlah ' . $alat . '<br>Melebihi Jumlah Tersedia !!!',
                                'errorSimpan' => 'Jumlah sisa ' . $alat . 'adalah ' . $dataAlatKotorBerdasarkanId['sisa_distribusi']
                            ]
                        ]
                    );
                }

                $dataDetail = [
                    'id_master' => $insertPermintaanAlatSteril,
                    'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                    'id_alat' => $detail['idAlat'],
                    'jumlah' => $jumlah,
                    'keterangan' => $detail['keterangan'],
                ];

                $insertPermintaanAlatSterilDetail = $permintaanAlatSterilDetailModel
                    ->insert($dataDetail, false);

                if (!$insertPermintaanAlatSterilDetail) {
                    $permintaanAlatSterilModel
                        ->delete($insertPermintaanAlatSteril);
                    $permintaanAlatSterilDetailModel
                        ->where('id_master', $insertPermintaanAlatSteril)
                        ->delete();

                    $insertPermintaanAlatSterilDetailLog = "Gagal insert detail dokumentasi permintaan alat/instrumen steril dengan id master " . $insertPermintaanAlatSteril;
                    $this->logModel->insert([
                        "id_user" => session()->get('id_user'),
                        "log" => $insertPermintaanAlatSterilDetailLog
                    ]);

                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertPermintaanAlatSterilDetailLog
                            ]
                        ]
                    );
                }

                $dataDetailAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel
                    ->find($idDetailPenerimaanAlatKotor);

                $dataUpdateDetailPenerimaanAlatKotor = [
                    'status_distribusi' => 'Distribusi',
                    'sisa_distribusi' => ((int)$dataDetailAlatKotorBerdasarkanId['sisa_distribusi'] - (int)$jumlah)
                ];
                $penerimaanAlatKotorDetailModel
                    ->update($idDetailPenerimaanAlatKotor, $dataUpdateDetailPenerimaanAlatKotor);
            }

            $insertPermintaanAlatSterilDetailLog = "Insert detail dokumentasi permintaan alat/instrumen steril dengan id master " . $insertPermintaanAlatSteril;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertPermintaanAlatSterilDetailLog
            ]);

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

    public function editPermintaanAlatSteril($id = null)
    {

        $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
        $dataPermintaan = $permintaanAlatSterilModel
            ->dataPermintaanAlatSterilBerdasarkanId($id)
            ->getRowArray();
        if (!$dataPermintaan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
        $dataDetailPermintaan = $permintaanAlatSterilDetailModel
            ->dataPermintaanAlaSterilDetailBerdasarkanIdMaster($id);

        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);
        $data = [
            'title' => 'Edit Permintaan Alat Steril',
            'header' => 'Dokumentasi Permintaan Alat/Instrumen Steril',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listPegawai' => $pegawaiModel->getListPegawai(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'dataPermintaan' => $dataPermintaan,
            'dataDetail' => $dataDetailPermintaan
        ];
        return view('distribusi/permintaanalatsteril/edit_permintaanalatsteril_view', $data);
    }

    public function hapusDetailPermintaanAlatSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
            $dataDetail = $permintaanAlatSterilDetailModel->find($id);
            if (!$dataDetail) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'toastr' => [
                            'tipe' => 'error',
                            'teks' => "<div class=\"row\"><div class=\"col align-self-center\"><span>Data Tidak Ditemukan</span></div></div>"
                        ]
                    ]
                );
            }

            $deleteDetail = $permintaanAlatSterilDetailModel->delete($id, false);
            $deleteDetailLog = ($deleteDetail) ? "Delete" : "Gagal delete";
            $deleteDetailLog .= " detail dokumentasi permintaan alat/instrumen steril dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteDetailLog
            ]);
            if (!$deleteDetail) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'toastr' => [
                            'tipe' => 'error',
                            'teks' => "<div class=\"row\"><div class=\"col align-self-center\"><span>Data Tidak Dihapus</span></div></div>"
                        ]
                    ]
                );
            }

            $idAlatKotor = $dataDetail['id_detail_penerimaan_alat_kotor'];
            $jumlah = $dataDetail['jumlah'];
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

            if ((int)$dataAlatKotor['sisa_distribusi'] < (int)$dataAlatKotor['jumlah']) {
                $updateData = [
                    'sisa_distribusi' => (int)$dataAlatKotor['sisa_distribusi'] + (int)$jumlah
                ];

                if ((int)$updateData['sisa_distribusi'] === (int)$dataAlatKotor['jumlah']) {
                    $updateData['status_distribusi'] = '';
                }
                $penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
            }


            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'toastr' => [
                        'tipe' => 'success',
                        'teks' => "<div class=\"row\"><div class=\"col align-self-center\"><span>Data Dihapus</span></div><div class=\"col-auto\"><button type=\"button\" class=\"btn btn-sm btn-outline-light\" onclick=\"batalHapus(this, '" . $dataDetail['id'] . "')\">Batal</button></div></div>"
                    ],
                ]
            );
        }
    }

    public function batalHapusDetailPermintaanAlatSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
            $updateDetail = $permintaanAlatSterilDetailModel->update($id, ['deleted_at' => null], false);
            $updateDetailLog = ($updateDetail) ? "Update" : "Gagal update";
            $updateDetailLog .= " 'Batal Hapus' detail dokumentasi permintaan alat/instrumen steril dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateDetailLog
            ]);
            if (!$updateDetail) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'toastr' => [
                            'tipe' => 'error',
                            'teks' => "<div class=\"row\"><div class=\"col align-self-center\"><span>Data Tidak Dikembalikan</span></div></div>"
                        ]
                    ]
                );
            }
            $dataDetail = $permintaanAlatSterilDetailModel->find($id);
            $idAlatKotor = $dataDetail['id_detail_penerimaan_alat_kotor'];
            $jumlah = $dataDetail['jumlah'];
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

            if ((int)$dataAlatKotor['sisa_distribusi'] > 0) {
                $updateData = [
                    'sisa_distribusi' => (int)$dataAlatKotor['sisa_distribusi'] - (int)$jumlah,
                    'status_distribusi' => 'Distribusi'
                ];
                $penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
            }

            $dataReturn = $permintaanAlatSterilDetailModel
                ->dataPermintaanAlaSterilDetailBerdasarkanId($id)
                ->getRowArray();

            $td = "<td class=\"align-middle\" style=\"width: 50%\"><span data-values=" . $dataReturn['id_alat'] . ">" . $dataReturn['nama_set_alat'] . "</span></td>";
            $td .= "<td class=\"text-center align-middle\" style=\"width: 10%\"><span data-values=" . $dataReturn['id_detail_penerimaan_alat_kotor'] . ">" . $dataReturn['jumlah'] . "</span></td>";
            $td .= "<td class=\"align-middle\" style=\"width: 30%\"><span>" . $dataReturn['keterangan'] . "</span></td>";
            $td .= " <td class=\"text-center align-middle\" style=\"width: 10%\"><button type=\"button\" class=\"btn btn-danger btn-sm border-0\" values=" . $dataReturn['id'] . " onclick=\"hapusBarisTabel(this,'" . $dataReturn['id'] . "')\"><i class=\"far fa-trash-alt\"></i></button></td>";
            $tr = "<tr>" . $td . "</tr>";

            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'toastr' => [
                        'tipe' => 'success',
                        'teks' => "<div class=\"row\"><div class=\"col align-self-center\"><span>Data Batal Dihapus</span></div></div>"
                    ],
                    'data' => $tr
                ]
            );
        }
    }

    public function updatePermintaanAlatSteril($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalPermintaan' => $this->validation->getError('tanggalPermintaan'),
                    'invalidJamPermintaan' => $this->validation->getError('jamPermintaan'),
                    'invalidJamMinta' => $this->validation->getError('jamMinta'),
                    'invalidPetugasCSSD' => $this->validation->getError('petugasCSSD'),
                    'invalidPetugasMinta' => $this->validation->getError('petugasMinta'),
                    'invalidRuangan' => $this->validation->getError('ruangan'),
                    'invalidTabelDataAlat' => $this->validation->getError('jumlahDataAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $tanggalPermintaan = $this->request->getPost('tanggalPermintaan') . " " . $this->request->getVar('jamPermintaan') . ":00";
            $petugasCSSD = $this->request->getPost('petugasCSSD');
            $petugasMinta = $this->request->getPost('petugasMinta');
            $dataAlat = $this->request->getPost('dataAlat');

            $data = [
                'tanggal_minta' => $tanggalPermintaan,
                'id_petugas_cssd' => $petugasCSSD,
                'id_petugas_minta' => $petugasMinta,
            ];
            $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
            $updatePermintaanAlatSteril = $permintaanAlatSterilModel->update($id, $data, false);
            $updatePermintaanAlatSterilLog = ($updatePermintaanAlatSteril) ? "Update" : "Gagal update";
            $updatePermintaanAlatSterilLog .= " dokumentasi permintaan alat/instrumen steril dengan id " . $updatePermintaanAlatSteril;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updatePermintaanAlatSteril
            ]);
            if (!$updatePermintaanAlatSteril) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updatePermintaanAlatSterilLog
                        ]
                    ]
                );
            }
            if ($dataAlat) {
                $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
                $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
                foreach ($dataAlat as $detail) {
                    $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                    $jumlah = $detail['jumlah'];
                    $alat = $detail['alat'];

                    $dataAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);

                    if ($jumlah > $dataAlatKotorBerdasarkanId['sisa_distribusi']) {
                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal Simpan',
                                    'teks' => 'Jumlah ' . $alat . '<br>Melebihi Jumlah Tersedia !!!',
                                    'errorSimpan' => 'Jumlah sisa ' . $alat . 'adalah ' . $dataAlatKotorBerdasarkanId['sisa_distribusi']
                                ]
                            ]
                        );
                    }

                    $dataDetail = [
                        'id_master' => $id,
                        'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                        'id_alat' => $detail['idAlat'],
                        'jumlah' => $jumlah,
                        'keterangan' => $detail['keterangan'],
                    ];

                    $insertPermintaanAlatSterilDetail = $permintaanAlatSterilDetailModel
                        ->insert($dataDetail, false);

                    if (!$insertPermintaanAlatSterilDetail) {
                        $insertPermintaanAlatSterilDetailLog = "Gagal insert detail baru dokumentasi permintaan alat/instrumen steril dengan id master " . $id;
                        $this->logModel->insert([
                            "id_user" => session()->get('id_user'),
                            "log" => $insertPermintaanAlatSterilDetailLog
                        ]);

                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal',
                                    'teks' => 'Data Tidak Diedit',
                                    'errorSimpan' => $insertPermintaanAlatSterilDetailLog
                                ]
                            ]
                        );
                    }

                    // $dataDetailAlatKotorBerdasarkanId = $this->penerimaanAlatKotorDetailModel
                    //     ->find($idDetailPenerimaanAlatKotor);

                    $dataUpdateDetailPenerimaanAlatKotor = [
                        'status_distribusi' => 'Distribusi',
                        'sisa_distribusi' => ((int)$dataAlatKotorBerdasarkanId['sisa_distribusi'] - (int)$jumlah)
                    ];
                    $penerimaanAlatKotorDetailModel
                        ->update($idDetailPenerimaanAlatKotor, $dataUpdateDetailPenerimaanAlatKotor);
                }

                $insertPermintaanAlatSterilDetailLog = "Insert detail baru dokumentasi permintaan alat/instrumen steril dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertPermintaanAlatSterilDetailLog
                ]);
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

    public function hapusPermintaanAlatSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanAlatSterilModel = model(PermintaanAlatSterilModel::class);
            $dataPermintaanAlatSteril = $permintaanAlatSterilModel->find($id);
            if (!$dataPermintaanAlatSteril) {
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

            $deletePermintaanAlatSteril = $permintaanAlatSterilModel->delete($id, false);
            $deletePermintaanAlatSterilLog = ($deletePermintaanAlatSteril) ? "Delete" : "Gagal delete";
            $deletePermintaanAlatSterilLog .= " dokumentasi permintaan alat/instrumen steril dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deletePermintaanAlatSterilLog
            ]);

            if (!$deletePermintaanAlatSteril) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deletePermintaanAlatSterilLog
                        ]
                    ]
                );
            }
            $permintaanAlatSterilDetailModel = model(PermintaanAlatSterilDetailModel::class);
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $dataDetailAlat = $permintaanAlatSterilDetailModel->where('id_master', $id)->findAll();
            foreach ($dataDetailAlat as $detail) {
                $idAlatKotor = $detail['id_detail_penerimaan_alat_kotor'];
                $jumlah = $detail['jumlah'];
                $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

                if ((int)$dataAlatKotor['sisa_distribusi'] < (int)$dataAlatKotor['jumlah']) {
                    $updateData = [
                        'sisa_distribusi' => (int)$dataAlatKotor['sisa_distribusi'] + (int)$jumlah
                    ];

                    if ((int)$updateData['sisa_distribusi'] === (int)$dataAlatKotor['jumlah']) {
                        $updateData['status_distribusi'] = '';
                    }
                    $penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
                }

                $deletePermintaanAlatSterilDetail = $permintaanAlatSterilDetailModel->delete($detail['id']);
                $deletePermintaanAlatSterilDetailLog = ($deletePermintaanAlatSterilDetail) ? "Delete" : "Gagal delete";
                $deletePermintaanAlatSterilDetailLog .= " detail dokumentasi permintaan alat/instrumen steril dengan id " . $detail['id'];
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $deletePermintaanAlatSterilDetailLog
                ]);
                if (!$deletePermintaanAlatSterilDetail) {
                    $permintaanAlatSterilDetailModel->update($id, ['deleted_at' => null]);
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Dihapus',
                                'errorSimpan' => $deletePermintaanAlatSterilDetailLog
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
                        'teks' => 'Data Dihapus'
                    ]
                ]
            );
        }
    }
}
