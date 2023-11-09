<?php

namespace App\Controllers\DistribusiControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DataBmhpModel;
use App\Models\DataModels\DepartemenModel;
use App\Models\DataModels\PegawaiModel;
use App\Models\DistribusiModels\PermintaanBmhpSterilDetailModel;
use App\Models\DistribusiModels\PermintaanBmhpSterilModel;
use App\Models\PackingAlatModels\MonitoringMesinSteamDetailModel;

class PermintaanBmhpSterilController extends BaseController
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
            'title' => 'Permintaan BMHP Steril',
            'header' => 'Dokumentasi Permintaan BMHP/Kasa Steril',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
            'listDepartemen' => $departemenModel->getListDepartemen(),
        ];
        return view('distribusi/permintaanbmhpsteril/index_permintaanbmhpsteril_view', $data);
    }

    public function dataPermintaanBmhpSterilBerdasarkanFilter()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $ruangan = $this->request->getPost('ruangan');
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $dataPermintaanBmhpSterilBerdasarkanFilter = $permintaanBmhpSterilModel
                ->dataPermintaanBmhpSterilBerdasarkanFilter($tglAwal, $tglAkhir, $start, $limit, $ruangan)
                ->getResultArray();

            $jumlahDataPermintaanBmhpSterilBerdasarkanTanggal = $permintaanBmhpSterilModel
                ->dataPermintaanBmhpSterilBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $jumlahDataPermintaanBmhpSterilBerdasarkanTanggaldanRuangan = $permintaanBmhpSterilModel
                ->dataPermintaanBmhpSterilBerdasarkanTanggaldanRuangan($tglAwal, $tglAkhir, $ruangan)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataPermintaanBmhpSterilBerdasarkanFilter as $data) {
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
                'recordsTotal' => $jumlahDataPermintaanBmhpSterilBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataPermintaanBmhpSterilBerdasarkanTanggaldanRuangan,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailPermintaanBmhpSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $dataPermintaanBmhpSterilBerdasarkanId = $permintaanBmhpSterilModel
                ->dataPermintaanBmhpSterilBerdasarkanId($id)
                ->getRowArray();

            if (!$dataPermintaanBmhpSterilBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }
            $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
            $dataPermintaanBmhpSterilDetailBerdasarkanIdMaster = $permintaanBmhpSterilDetailModel
                ->dataPermintaanBmhpSterilDetailBerdasarkanIdMaster($id)
                ->getResultArray();

            $data = [
                'dataPermintaan' => $dataPermintaanBmhpSterilBerdasarkanId,
                'dataDetail' => $dataPermintaanBmhpSterilDetailBerdasarkanIdMaster
            ];

            $json = [
                'sukses' => true,
                'data' => view('distribusi/permintaanbmhpsteril/modal_detail_permintaanbmhpsteril', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function dataBmhpSteril()
    {
        if ($this->request->isAJAX()) {
            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $dataBmhpSteril = $permintaanBmhpSterilModel
                ->dataBmhpSteril()
                ->getResultArray();

            if ($dataBmhpSteril) {
                $optionData = [];
                foreach ($dataBmhpSteril as $data) {
                    $idAlat = $data['id_alat'];
                    $idDetail = $data['id_detail_steam'];
                    $alat = $data['nama_set_alat'];
                    $tanggalSteril = date("d-m-Y H:i", strtotime($data['tanggal_monitoring']));
                    $harga = number_format($data['harga'], 2, ',', '.');
                    $jumlah = (int)$data['jumlah'];
                    $jumlahTersedia = (int)$data['sisa_distribusi'];
                    $jumlahDistribusi = $jumlah - $jumlahTersedia;
                    $opt = [
                        "id" => $idAlat,
                        "detail" => $idDetail,
                        "jumlah" => $jumlahTersedia,
                        "harga" => $harga,
                        "text" => $alat,
                        "html" => "<div>
                                    <strong>" . $alat . " | </strong> <small><b>Harga:</b> " . $harga . "</small>
                                    <table class='table table-sm mb-0' style='font-size: 0.8em;'>
                                        <tr>
                                            <td><strong>Tanggal Steril:</strong><br>" . $tanggalSteril . "</td>
                                            <td><strong>Jumlah Steril:</strong><br>" . $jumlah . "</td>
                                            <td><strong>Distribusi:</strong><br>" . $jumlahDistribusi . "</td>
                                            <td><strong>Tersedia:</strong><br>" . $jumlahTersedia . "</td>
                                        </tr>
                                        <tr><td colspan='6'></tr>
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
    }

    public function tambahPermintaanBmhpSteril()
    {
        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);
        $data = [
            'title' => 'Tambah Permintaan BMHP Steril',
            'header' => 'Dokumentasi Permintaan BMHP/Kasa Steril',
            'tglSekarang' => date('Y-m-d'),
            'jamSekarang' => date('H:i'),
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listPegawai' => $pegawaiModel->getListPegawai(),
            'listDepartemen' => $departemenModel->getListDepartemen()
        ];
        return view('distribusi/permintaanbmhpsteril/tambah_permintaanbmhpsteril_view', $data);
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
            'jumlahDataBmhpDetail' => [
                'rules' => 'greater_than[0]',
                'errors' => [
                    'greater_than' => 'Tabel BMHP harus diisi'
                ]
            ]
        ]);
    }

    public function simpanPermintaanBmhpSteril()
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
                    'invalidTabelDataBmhp' => $this->validation->getError('jumlahDataBmhpDetail'),
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
            $dataBmhp = $this->request->getPost('dataBmhp');

            $data = [
                'id' => generateUUID(),
                'tanggal_minta' => $tanggalPermintaan,
                'id_petugas_cssd' => $petugasCSSD,
                'id_petugas_minta' => $petugasMinta,
                'id_ruangan' => $ruangan,
            ];
            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $insertPermintaanBmhpSteril = $permintaanBmhpSterilModel->insert($data);
            $insertPermintaanBmhpSterilLog = ($insertPermintaanBmhpSteril) ? "Insert" : "Gagal insert";
            $insertPermintaanBmhpSterilLog .= " dokumentasi permintaan bmhp/kasa steril dengan id " . $insertPermintaanBmhpSteril;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertPermintaanBmhpSterilLog
            ]);
            if (!$insertPermintaanBmhpSteril) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertPermintaanBmhpSterilLog
                        ]
                    ]
                );
            }

            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
            $dataBmhpModel = model(DataBmhpModel::class);
            foreach ($dataBmhp as $detail) {
                $idBmhp = $detail['idBmhp'];
                $bmhp = $detail['bmhp'];
                $jumlah = $detail['jumlah'];
                $idDetailSteam = $detail['idDetailSteam'];

                $dataBmhp = $dataBmhpModel->find($idBmhp);
                if (!$dataBmhp) {
                    $permintaanBmhpSterilModel->delete($insertPermintaanBmhpSteril);
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal Simpan',
                                'teks' => 'BMHP ' . $bmhp . '<br>tidak tersedia !!!',
                                'errorSimpan' => 'BMHP ' . $bmhp . 'dengan id ' . $idBmhp . ' tidak tersedia'
                            ]
                        ]
                    );
                }

                $dataDetailSteam = $monitoringMesinSteamDetailModel->find($idDetailSteam);

                if ($jumlah > $dataDetailSteam['sisa_distribusi']) {
                    $permintaanBmhpSterilModel->delete($insertPermintaanBmhpSteril);
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal Simpan',
                                'teks' => 'Jumlah ' . $bmhp . '<br>Melebihi Jumlah Tersedia !!!',
                                'errorSimpan' => 'Jumlah sisa ' . $bmhp . 'adalah ' . $dataDetailSteam['sisa_distribusi']
                            ]
                        ]
                    );
                }

                $dataDetail = [
                    'id_master' => $insertPermintaanBmhpSteril,
                    'id_monitoring_mesin_steam_detail' => $idDetailSteam,
                    'id_bmhp' => $idBmhp,
                    'jumlah' => $jumlah,
                    'harga' => $dataBmhp['harga'],
                    'keterangan' => $detail['keterangan'],
                ];

                $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
                $insertPermintaanBmhpSterilDetail = $permintaanBmhpSterilDetailModel
                    ->insert($dataDetail, false);

                if (!$insertPermintaanBmhpSterilDetail) {
                    $permintaanBmhpSterilModel->delete($insertPermintaanBmhpSteril);
                    $permintaanBmhpSterilDetailModel
                        ->where('id_master', $insertPermintaanBmhpSteril)
                        ->delete();

                    $insertPermintaanBmhpSterilDetailLog = "Gagal insert detail dokumentasi permintaan bmhp/kasa steril dengan id master " . $insertPermintaanBmhpSteril;
                    $this->logModel->insert([
                        "id_user" => session()->get('id_user'),
                        "log" => $insertPermintaanBmhpSterilDetailLog
                    ]);

                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertPermintaanBmhpSterilDetailLog
                            ]
                        ]
                    );
                }

                $dataUpdateDetailSteam = [
                    'sisa_distribusi' => ((int)$dataDetailSteam['sisa_distribusi'] - (int)$jumlah)
                ];
                $monitoringMesinSteamDetailModel
                    ->update($idDetailSteam, $dataUpdateDetailSteam);
            }

            $insertPermintaanBmhpSterilDetailLog = "Insert detail dokumentasi permintaan bmhp/kasa steril dengan id master " . $insertPermintaanBmhpSteril;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertPermintaanBmhpSterilDetailLog
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

    public function editPermintaanBmhpSteril($id = null)
    {
        $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
        $dataPermintaan = $permintaanBmhpSterilModel
            ->dataPermintaanBmhpSterilBerdasarkanId($id)
            ->getRowArray();
        if (!$dataPermintaan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
        $dataDetailPermintaan = $permintaanBmhpSterilDetailModel
            ->dataPermintaanBmhpSterilDetailBerdasarkanIdMaster($id);
        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);
        $data = [
            'title' => 'Edit Permintaan BMHP Steril',
            'header' => 'Dokumentasi Permintaan BMHP/Kasa Steril',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listPegawai' => $pegawaiModel->getListPegawai(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'dataPermintaan' => $dataPermintaan,
            'dataDetail' => $dataDetailPermintaan
        ];
        return view('distribusi/permintaanbmhpsteril/edit_permintaanbmhpsteril_view', $data);
    }

    public function hapusDetailPermintaanBmhpSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
            $dataDetail = $permintaanBmhpSterilDetailModel->find($id);
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

            $deleteDetail = $permintaanBmhpSterilDetailModel->delete($id, false);
            $deleteDetailLog = ($deleteDetail) ? "Delete" : "Gagal delete";
            $deleteDetailLog .= " detail dokumentasi permintaan bmhp/kasa steril dengan id " . $id;
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

            $idDetailSteam = $dataDetail['id_monitoring_mesin_steam_detail'];
            $jumlah = $dataDetail['jumlah'];
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
            $dataDetailSteam = $monitoringMesinSteamDetailModel->find($idDetailSteam);

            if ((int)$dataDetailSteam['sisa_distribusi'] < (int)$dataDetailSteam['jumlah']) {
                $updateData = [
                    'sisa_distribusi' => (int)$dataDetailSteam['sisa_distribusi'] + (int)$jumlah
                ];

                $monitoringMesinSteamDetailModel->update($idDetailSteam, $updateData, false);
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

    public function batalHapusDetailPermintaanBmhpSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
            $updateDetail = $permintaanBmhpSterilDetailModel
                ->update($id, ['deleted_at' => null], false);
            $updateDetailLog = ($updateDetail) ? "Update" : "Gagal update";
            $updateDetailLog .= " 'Batal Hapus' detail dokumentasi permintaan bmhp/kasa steril dengan id " . $id;
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
            $dataDetail = $permintaanBmhpSterilDetailModel->find($id);
            $idDetailSteam = $dataDetail['id_monitoring_mesin_steam_detail'];
            $jumlah = $dataDetail['jumlah'];
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
            $dataDetailSteam = $monitoringMesinSteamDetailModel->find($idDetailSteam);

            if ((int)$dataDetailSteam['sisa_distribusi'] > 0) {
                $updateData = [
                    'sisa_distribusi' => (int)$dataDetailSteam['sisa_distribusi'] - (int)$jumlah
                ];
                $monitoringMesinSteamDetailModel->update($idDetailSteam, $updateData, false);
            }

            $dataReturn = $permintaanBmhpSterilDetailModel
                ->dataPermintaanBmhpSterilDetailBerdasarkanId($id)
                ->getRowArray();

            $subtotal = $dataReturn['harga'] * $dataReturn['jumlah'];
            $td = "<td class=\"align-middle\" style=\"width: 30%\"><span data-values=" . $dataReturn['id_bmhp'] . ">" . $dataReturn['nama_set_alat'] . "</span></td>";
            $td .= "<td class=\"text-right align-middle pr-3\" style=\"width: 10%\">" . number_format($dataReturn['harga'], 2, ',', '.') . "</td>";
            $td .= "<td class=\"text-center align-middle\" style=\"width: 10%\"><span data-values=" . $dataReturn['id_monitoring_mesin_steam_detail'] . ">" . $dataReturn['jumlah'] . "</span></td>";
            $td .= "<td class=\"text-right align-middle pr-3\" style=\"width: 15%\">" . number_format($subtotal, 2, ',', '.') . "</td>";
            $td .= "<td class=\"align-middle pl-3\" style=\"width: 12%\">" . $dataReturn['keterangan'] . "</td>";
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

    public function updatePermintaanBmhpSteril($id)
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
                    'invalidTabelDataBmhp' => $this->validation->getError('jumlahDataBmhpDetail'),
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
            $dataBmhp = $this->request->getPost('dataBmhp');

            $data = [
                'tanggal_minta' => $tanggalPermintaan,
                'id_petugas_cssd' => $petugasCSSD,
                'id_petugas_minta' => $petugasMinta,
                'id_ruangan' => $ruangan,
            ];
            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $updatePermintaanBmhpSteril = $permintaanBmhpSterilModel->update($id, $data, false);
            $updatePermintaanBmhpSterilLog = ($updatePermintaanBmhpSteril) ? "Update" : "Gagal update";
            $updatePermintaanBmhpSterilLog .= " dokumentasi permintaan bmhp/kasa steril dengan id " . $updatePermintaanBmhpSteril;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updatePermintaanBmhpSterilLog
            ]);
            if (!$updatePermintaanBmhpSteril) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updatePermintaanBmhpSterilLog
                        ]
                    ]
                );
            }
            if ($dataBmhp) {
                $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
                $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
                $dataBmhpModel = model(DataBmhpModel::class);
                foreach ($dataBmhp as $detail) {
                    $idBmhp = $detail['idBmhp'];
                    $bmhp = $detail['bmhp'];
                    $jumlah = $detail['jumlah'];
                    $idDetailSteam = $detail['idDetailSteam'];

                    $dataBmhp = $dataBmhpModel->find($idBmhp);
                    if (!$dataBmhp) {
                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal Simpan',
                                    'teks' => 'BMHP ' . $bmhp . '<br>tidak tersedia !!!',
                                    'errorSimpan' => 'BMHP ' . $bmhp . 'dengan id ' . $idBmhp . ' tidak tersedia'
                                ]
                            ]
                        );
                    }

                    $dataDetailSteam = $monitoringMesinSteamDetailModel->find($idDetailSteam);

                    if ($jumlah > $dataDetailSteam['sisa_distribusi']) {
                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal Simpan',
                                    'teks' => 'Jumlah ' . $bmhp . '<br>Melebihi Jumlah Tersedia !!!',
                                    'errorSimpan' => 'Jumlah sisa ' . $bmhp . 'adalah ' . $dataDetailSteam['sisa_distribusi']
                                ]
                            ]
                        );
                    }

                    $dataDetail = [
                        'id_master' => $id,
                        'id_monitoring_mesin_steam_detail' => $idDetailSteam,
                        'id_bmhp' => $idBmhp,
                        'jumlah' => $jumlah,
                        'harga' => $dataBmhp['harga'],
                        'keterangan' => $detail['keterangan'],
                    ];

                    $insertPermintaanBmhpSterilDetail = $permintaanBmhpSterilDetailModel
                        ->insert($dataDetail, false);

                    if (!$insertPermintaanBmhpSterilDetail) {
                        $insertPermintaanBmhpSterilDetailLog = "Gagal insert detail baru dokumentasi permintaan bmhp/kasa steril dengan id master " . $id;
                        $this->logModel->insert([
                            "id_user" => session()->get('id_user'),
                            "log" => $insertPermintaanBmhpSterilDetailLog
                        ]);

                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal',
                                    'teks' => 'Data Tidak Disimpan',
                                    'errorSimpan' => $insertPermintaanBmhpSterilDetailLog
                                ]
                            ]
                        );
                    }

                    $dataUpdateDetailSteam = [
                        'sisa_distribusi' => ((int)$dataDetailSteam['sisa_distribusi'] - (int)$jumlah)
                    ];
                    $monitoringMesinSteamDetailModel
                        ->update($idDetailSteam, $dataUpdateDetailSteam);
                }

                $insertPermintaanBmhpSterilDetailLog = "Insert detail baru dokumentasi permintaan bmhp/kasa steril dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertPermintaanBmhpSterilDetailLog
                ]);
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

    public function hapusPermintaanBmhpSteril($id)
    {
        if ($this->request->isAJAX()) {
            $permintaanBmhpSterilModel = model(PermintaanBmhpSterilModel::class);
            $dataPermintaanBmhpSteril = $permintaanBmhpSterilModel->find($id);
            if (!$dataPermintaanBmhpSteril) {
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

            $deletePermintaanBmhpSteril = $permintaanBmhpSterilModel->delete($id, false);
            $deletePermintaanBmhpSterilLog = ($deletePermintaanBmhpSteril) ? "Delete" : "Gagal delete";
            $deletePermintaanBmhpSterilLog .= " dokumentasi permintaan bmhp/kasa steril dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deletePermintaanBmhpSterilLog
            ]);

            if (!$deletePermintaanBmhpSteril) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deletePermintaanBmhpSterilLog
                        ]
                    ]
                );
            }
            $permintaanBmhpSterilDetailModel = model(PermintaanBmhpSterilDetailModel::class);
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
            $dataDetailBmhp = $permintaanBmhpSterilDetailModel->where('id_master', $id)->findAll();
            foreach ($dataDetailBmhp as $detail) {
                $idDetailSteam = $detail['id_monitoring_mesin_steam_detail'];
                $jumlah = $detail['jumlah'];

                $dataDetailSteam = $monitoringMesinSteamDetailModel->find($idDetailSteam);

                if ((int)$dataDetailSteam['sisa_distribusi'] < (int)$dataDetailSteam['jumlah']) {
                    $updateData = [
                        'sisa_distribusi' => (int)$dataDetailSteam['sisa_distribusi'] + (int)$jumlah
                    ];

                    $monitoringMesinSteamDetailModel->update($idDetailSteam, $updateData, false);
                }

                $deletePermintaanBmhpSterilDetail = $permintaanBmhpSterilDetailModel->delete($detail['id']);
                $deletePermintaanBmhpSterilDetailLog = ($deletePermintaanBmhpSterilDetail) ? "Delete" : "Gagal delete";
                $deletePermintaanBmhpSterilDetailLog .= " detail dokumentasi permintaan bmhp/kasa steril dengan id " . $detail['id'];
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $deletePermintaanBmhpSterilDetailLog
                ]);
                if (!$deletePermintaanBmhpSterilDetail) {
                    $permintaanBmhpSterilDetailModel->update($id, ['deleted_at' => null]);
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Dihapus',
                                'errorSimpan' => $deletePermintaanBmhpSterilDetailLog
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
