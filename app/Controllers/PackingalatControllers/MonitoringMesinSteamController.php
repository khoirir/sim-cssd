<?php

namespace App\Controllers\PackingalatControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DepartemenModel;
use App\Models\DataModels\PegawaiModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorDetailModel;
use App\Models\PackingAlatModels\MonitoringMesinSteamDetailModel;
use App\Models\PackingAlatModels\MonitoringMesinSteamModel;
use App\Models\PackingAlatModels\MonitoringMesinSteamOperatorModel;
use App\Models\PackingAlatModels\MonitoringMesinSteamVerifikasiModel;
use CodeIgniter\Database\RawSql;

class MonitoringMesinSteamController extends BaseController
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
            'title' => 'Monitoring Mesin Steam',
            'header' => 'Monitoring Mesin Steam',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
        ];
        return view('packingalat/monitoringmesinsteam/index_monitoringmesinsteam_view', $data);
    }

    public function tambahMonitoringMesinSteam()
    {
        $jamSekarang = date('H:i');
        $shift = '';
        if ($jamSekarang >= '07:00' && $jamSekarang <= '14:00') {
            $shift = 'pagi';
        } elseif ($jamSekarang >= '14:01' && $jamSekarang <= '21:00') {
            $shift = 'sore';
        }

        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);

        $data = [
            'title' => 'Tambah Monitoring Mesin Steam',
            'header' => 'Monitoring Mesin Steam',
            'tglSekarang' => date('Y-m-d'),
            'jamSekarang' => $jamSekarang,
            'shift' => $shift,
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
        ];
        return view('packingalat/monitoringmesinsteam/tambah_monitoringmesinsteam_view', $data);
    }

    public function dataMonitoringMesinSteamBerdasarkanTanggal()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
            $monitoringMesinSteamVerifikasiModel = model(MonitoringMesinSteamVerifikasiModel::class);

            $dataMonitoringMesinSteamBerdasarkanTanggaldanLimit = $monitoringMesinSteamModel
                ->dataMonitoringMesinSteamBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
                ->getResultArray();

            $jumlahDataMonitoringMesinSteamBerdasarkanTanggal = $monitoringMesinSteamModel
                ->dataMonitoringMesinSteamBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataMonitoringMesinSteamBerdasarkanTanggaldanLimit as $data) {
                $tombolDetail = "<button data-popup='tooltip' title='Detail Monitoring Mesin Steam' class='btn btn-info btn-sm border-0' onclick=\"detailMonitoringMesinSteam(this,'" . $data['id'] . "')\"><i class='fas fa-file-lines'></i></button>";

                $a = $data['proses_ulang'] ? "" : "<a href=\"" . base_url('monitoring-mesin-steam/edit/' . $data['id']) . "\" class=\"btn btn-warning btn-sm border-0\" data-popup='tooltip' title='Edit Data'><i class=\"fas fa-edit\"></i></a>";
                $form = "<form action=\"" . base_url('monitoring-mesin-steam/hapus/' . $data['id']) . "\" method=\"POST\" class=\"formHapus\"><input type=\"hidden\" name=\"" . csrf_token() . "\" value=\"" . csrf_hash() . "\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\"><button type=\"submit\" class=\"ml-1 btn btn-danger btn-sm border-0\" data-popup='tooltip' title='Hapus Data'><i class=\"far fa-trash-alt\"></i></button></form>";
                $tombolAksi = "<div class='d-flex justify-content-center'>" . $a . $form . "</div>";
                $aksi = $tombolAksi;

                $dataVerifikasi = $monitoringMesinSteamVerifikasiModel
                    ->dataVerifikasiMonitoringMesinSteamBerdasarkanIdMaster($data['id'])
                    ->getRowArray();
                $prosesDari = "";
                $keterangan = "<i class='fa-solid fa-minus'></i>";
                if ($data['proses_ulang']) {
                    $prosesDari = "<span class='badge badge-info'>Proses dari " . generateNoReferensi($data['created_proses_ulang'], $data['proses_ulang']) . "</span>";
                    $keterangan = "";
                }
                $verifikasi = "<a href=\"" . base_url('monitoring-mesin-steam/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Steam\" class=\"btn btn-primary btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";
                $keterangan .= $prosesDari;

                if ($dataVerifikasi) {
                    $hasilVerifikasi = $dataVerifikasi['hasil_verifikasi'];
                    $keterangan = $prosesDari . " <span class='badge badge-success'>" . $hasilVerifikasi . "</span>";
                    $verifikasi = "<a href=\"" . base_url('monitoring-mesin-steam/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Steam\" class=\"btn btn-success btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";
                    $aksi = "<i class=\"fa-solid fa-minus\"></i>";

                    if ($hasilVerifikasi === 'Failed') {
                        $keterangan = $prosesDari . " <span class=\"badge badge-danger\">" . $hasilVerifikasi . "</span>";
                        $verifikasi = "<a href=\"" . base_url('monitoring-mesin-steam/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Steam\" class=\"btn btn-danger btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";

                        $aksi = "<a href=\"" . base_url('monitoring-mesin-steam/proses-ulang/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Proses Ulang\" class=\"btn btn-primary btn-sm border-0 ml-1\"><i class=\"fa-solid fa-repeat\"></i></a>";

                        $dataProsesUlang = $monitoringMesinSteamModel
                            ->where('proses_ulang', $data['id'])
                            ->where('deleted_at', null)
                            ->get()->getNumRows();

                        if ($dataProsesUlang > 0) {
                            // $verifikasi = "<i class='fa-solid fa-minus'></i>";
                            $keterangan .= " <span class=\"badge badge-secondary\">Proses Ulang</span>";
                            $aksi = "<i class='fa-solid fa-minus'></i>";
                        }
                    }
                }


                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'tanggalMonitoring' => date("d-m-Y", strtotime($data['tanggal_monitoring'])),
                    'jamMasukAlat' => date("H:i", strtotime($data['tanggal_monitoring'])),
                    'siklus' => $data['siklus'],
                    'mesin' => 'Mesin ' . $data['mesin'],
                    'verifikasi' => $verifikasi,
                    'keterangan' => $keterangan,
                    'detail' => $tombolDetail,
                    'aksi' => $aksi,
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataMonitoringMesinSteamBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataMonitoringMesinSteamBerdasarkanTanggal,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailMonitoringMesinSteam($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
            $monitoringMesinSteamOperatorModel = model(MonitoringMesinSteamOperatorModel::class);
            $monitoringMesinSteamVerifikasiModel = model(MonitoringMesinSteamVerifikasiModel::class);

            $dataMonitoringMesinSteamBerdasarkanId = $monitoringMesinSteamModel->find($id);
            if (!$dataMonitoringMesinSteamBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $operatorMonitoringMesinSteamBerdasarkanIdMaster = $monitoringMesinSteamOperatorModel
                ->operatorMonitoringMesinSteamBerdasarkanIdMaster($id)
                ->getResultArray();

            $dataDetailMonitoringMesinSteamBerdasarkanIdMaster = $monitoringMesinSteamDetailModel
                ->dataDetailMonitoringMesinSteamBerdasarkanIdMaster($id);

            $dataVerifikasiMonitoringMesinSteamBerdasarkanIdMaster = $monitoringMesinSteamVerifikasiModel
                ->dataVerifikasiMonitoringMesinSteamBerdasarkanIdMaster($id)
                ->getFirstRow('array');

            $data = [
                'dataMesinSteam' => $dataMonitoringMesinSteamBerdasarkanId,
                'operator' => $operatorMonitoringMesinSteamBerdasarkanIdMaster,
                'detailAlat' => $dataDetailMonitoringMesinSteamBerdasarkanIdMaster,
                'dataVerifikasi' => $dataVerifikasiMonitoringMesinSteamBerdasarkanIdMaster,
            ];

            $json = [
                'sukses' => true,
                'data' => view('packingalat/monitoringmesinsteam/modal_detail_monitoring_mesin_steam', $data)
            ];

            return $this->response->setJSON($json);
        }
    }

    public function validasiForm()
    {
        $this->valid = $this->validate([
            'tanggalMonitoring' => [
                'rules' => 'required|not_future_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                ],
            ],
            'jamMasukAlat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam harus diisi',
                ],
            ],
            'jamMasuk' => [
                'rules' => 'not_future_time',
            ],
            'shift' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Shift harus dipilih',
                ]
            ],
            'operator' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Operator harus diisi',
                ]
            ],
            'siklus' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Siklus harus diisi',
                ]
            ],
            'mesin' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Mesin harus dipilih',
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

    public function simpanMonitoringMesinSteam()
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalMonitoring' => $this->validation->getError('tanggalMonitoring'),
                    'invalidJamMasukAlat' => $this->validation->getError('jamMasukAlat'),
                    'invalidJamMasuk' => $this->validation->getError('jamMasuk'),
                    'invalidShift' => $this->validation->getError('shift'),
                    'invalidOperator' => $this->validation->getError('operator'),
                    'invalidSiklus' => $this->validation->getError('siklus'),
                    'invalidMesin' => $this->validation->getError('mesin'),
                    'invalidTabelDataAlat' => $this->validation->getError('jumlahDataAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $tanggalMonitoring = $this->request->getPost('tanggalMonitoring') . " " . $this->request->getVar('jamMasukAlat') . ":00";
            $shift = $this->request->getPost('shift');
            $operator = $this->request->getPost('operator');
            $siklus = $this->request->getPost('siklus');
            $mesin = $this->request->getPost('mesin');
            $prosesUlang = $this->request->getPost('prosesUlang');
            $dataAlat = $this->request->getPost('dataAlat');

            $data = [
                'id' => generateUUID(),
                'tanggal_monitoring' => $tanggalMonitoring,
                'shift' => $shift,
                'siklus' => $siklus,
                'mesin' => $mesin,
            ];

            $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
            $monitoringMesinSteamOperatorModel = model(MonitoringMesinSteamOperatorModel::class);

            if ($prosesUlang) {
                $dataMesinSteam = $monitoringMesinSteamModel->find($prosesUlang);
                if (!$dataMesinSteam) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Ditemukan',
                                'errorSimpan' => 'Data yang diproses ulang tidak ditemukan'
                            ]
                        ]
                    );
                }

                $dataProsesUlang = $monitoringMesinSteamModel
                    ->where('proses_ulang', $prosesUlang)
                    ->where('deleted_at', null)
                    ->get()->getNumRows();

                if ($dataProsesUlang > 0) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Sudah Diproses Ulang',
                                'errorSimpan' => 'Data yang diproses sudah diproses ulang sebelumnya'
                            ]
                        ]
                    );
                }

                $data['created_proses_ulang'] = $dataMesinSteam['created_at'];
                $data['proses_ulang'] = $prosesUlang;
            }

            $insertMonitoringMesinSteam = $monitoringMesinSteamModel->insert($data);
            $insertMonitoringMesinSteamLog = ($insertMonitoringMesinSteam) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinSteamLog .= " monitoring mesin steam dengan id " . $insertMonitoringMesinSteam;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinSteamLog
            ]);
            if (!$insertMonitoringMesinSteam) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringMesinSteamLog
                        ]
                    ]
                );
            }

            $dataInsertMonitoringMesinSteamOperator = [];
            foreach ($operator as $op) {
                $dataDetail = [
                    'id_monitoring_mesin_steam' => $insertMonitoringMesinSteam,
                    'id_operator' => $op
                ];
                array_push($dataInsertMonitoringMesinSteamOperator, $dataDetail);
            }
            $insertMonitoringMesinSteamOperator = $monitoringMesinSteamOperatorModel->insertMultiple($dataInsertMonitoringMesinSteamOperator);
            $insertMonitoringMesinSteamOperatorLog = ($insertMonitoringMesinSteamOperator) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinSteamOperatorLog .= " operator monitoring mesin steam dengan id master " . $insertMonitoringMesinSteam;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinSteamOperatorLog
            ]);
            if (!$insertMonitoringMesinSteamOperator) {
                $monitoringMesinSteamModel->delete($insertMonitoringMesinSteam);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringMesinSteamOperatorLog
                        ]
                    ]
                );
            }

            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);

            foreach ($dataAlat as $detail) {
                $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                $jumlah = $detail['jumlah'];
                $alat = $detail['alat'];

                if ($idDetailPenerimaanAlatKotor && !$prosesUlang) {
                    $dataAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);

                    if ($jumlah > $dataAlatKotorBerdasarkanId['sisa']) {
                        $monitoringMesinSteamModel->delete($insertMonitoringMesinSteam);
                        $monitoringMesinSteamOperatorModel->where('id_monitoring_mesin_steam', $insertMonitoringMesinSteam)->delete();

                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal Simpan',
                                    'teks' => 'Jumlah ' . $alat . '<br>Melebihi Jumlah Tersedia !!!',
                                    'errorSimpan' => 'Jumlah sisa ' . $alat . 'adalah ' . $dataAlatKotorBerdasarkanId['sisa']
                                ]
                            ]
                        );
                    }
                }

                $dataDetail = [
                    'id_monitoring_mesin_steam' => $insertMonitoringMesinSteam,
                    'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                    'id_alat' => $detail['idAlat'],
                    'id_ruangan' => $detail['idRuangan'],
                    'jumlah' => $jumlah,
                    'sisa_distribusi' => $jumlah
                ];

                $insertMonitoringMesinSteamDetail = $monitoringMesinSteamDetailModel->insert($dataDetail, false);
                if (!$insertMonitoringMesinSteamDetail) {
                    $monitoringMesinSteamModel->delete($insertMonitoringMesinSteam);
                    $monitoringMesinSteamOperatorModel->where('id_monitoring_mesin_steam', $insertMonitoringMesinSteam)->delete();
                    $monitoringMesinSteamDetailModel->where('id_monitoring_mesin_steam', $insertMonitoringMesinSteam)->delete();
                    $insertMonitoringMesinSteamDetailLog = "Gagal insert detail monitoring mesin steam dengan id master " . $insertMonitoringMesinSteam;
                    $this->logModel->insert([
                        "id_user" => session()->get('id_user'),
                        "log" => $insertMonitoringMesinSteamDetailLog
                    ]);

                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertMonitoringMesinSteamDetailLog
                            ]
                        ]
                    );
                }

                if ($idDetailPenerimaanAlatKotor && !$prosesUlang) {
                    $dataDetailAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);
                    $dataUpdateDetailPenerimaanAlatKotor = [
                        'status_proses' => 'Diproses',
                        'sisa' => ((int)$dataDetailAlatKotorBerdasarkanId['sisa'] - (int)$jumlah)
                    ];
                    $penerimaanAlatKotorDetailModel->update($idDetailPenerimaanAlatKotor, $dataUpdateDetailPenerimaanAlatKotor);
                }
            }

            $insertMonitoringMesinSteamDetailLog = "Insert detail monitoring mesin steam dengan id master " . $insertMonitoringMesinSteam;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinSteamDetailLog
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

    public function editMonitoringMesinSteam($id = null)
    {
        $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
        $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
        $monitoringMesinSteamOperatorModel = model(MonitoringMesinSteamOperatorModel::class);

        $dataMonitoringMesinSteamBerdasarkanId = $monitoringMesinSteamModel->find($id);
        if (!$dataMonitoringMesinSteamBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($dataMonitoringMesinSteamBerdasarkanId['proses_ulang']) {
            return redirect()->to('/monitoring-mesin-steam');
        }

        $dataOperator = $monitoringMesinSteamOperatorModel
            ->operatorMonitoringMesinSteamBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataDetail = $monitoringMesinSteamDetailModel
            ->dataDetailMonitoringMesinSteamBerdasarkanIdMaster($id);

        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);

        $data = [
            'title' => 'Edit Monitoring Mesin Steam',
            'header' => 'Monitoring Mesin Steam',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'dataMesinSteam' => $dataMonitoringMesinSteamBerdasarkanId,
            'dataOperator' => $dataOperator,
            'dataDetail' => $dataDetail
        ];

        return view('packingalat/monitoringmesinsteam/edit_monitoringmesinsteam_view', $data);
    }

    public function hapusDetailMonitoringMesinSteam($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);

            $dataDetail = $monitoringMesinSteamDetailModel->find($id);
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

            $deleteDetail = $monitoringMesinSteamDetailModel->delete($id, false);
            $deleteDetailLog = ($deleteDetail) ? "Delete" : "Gagal delete";
            $deleteDetailLog .= " detail monitoring mesin steam dengan id " . $id;
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
            if ($idAlatKotor) {
                $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
                $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

                $updateData = [
                    'sisa' => (int)$dataAlatKotor['sisa'] + (int)$jumlah
                ];

                if ((int)$updateData['sisa'] === (int)$dataAlatKotor['jumlah']) {
                    $updateData['status_proses'] = '';
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

    public function batalHapusDetailMonitoringMesinSteam($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);

            $updateDetail = $monitoringMesinSteamDetailModel->update($id, ['deleted_at' => null], false);
            $updateDetailLog = ($updateDetail) ? "Update" : "Gagal update";
            $updateDetailLog .= " 'Batal Hapus' detail monitoring mesin steam dengan id " . $id;
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
            $dataDetail = $monitoringMesinSteamDetailModel->find($id);
            $idAlatKotor = $dataDetail['id_detail_penerimaan_alat_kotor'];
            $jumlah = $dataDetail['jumlah'];
            if ($idAlatKotor) {
                $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
                $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

                $updateData = [
                    'sisa' => (int)$dataAlatKotor['sisa'] - (int)$jumlah,
                    'status_proses' => 'Diproses'
                ];
                $penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
            }

            $dataReturn = $monitoringMesinSteamDetailModel
                ->dataDetailMonitoringMesinSteamBerdasarkanId($id)
                ->getFirstRow('array');

            $td = "<td class=\"align-middle\" style=\"width: 30%\"><span data-values=" . $dataReturn['id_ruangan'] . ">" . $dataReturn['ruangan'] . "</span></td>";
            $td .= "<td class=\"align-middle\" style=\"width: 50%\"><span data-values=" . $dataReturn['id_alat'] . ">" . $dataReturn['nama_set_alat'] . "</span></td>";
            $td .= "<td class=\"text-center align-middle\" style=\"width: 10%\"><span data-values=" . ($dataReturn['id_detail_penerimaan_alat_kotor'] ?: $dataReturn['id_alat']) . ">" . $dataReturn['jumlah'] . "</span></td>";
            $td .= " <td class=\"text-center align-middle\" style=\"width: 10%\"><button type=\"button\" class=\"btn btn-danger btn-sm border-0\" onclick=\"hapusBarisTabel(this,'" . $dataReturn['id'] . "')\" data-idalatkotor=" . ($dataReturn['id_detail_penerimaan_alat_kotor'] ?: "") . " values=" . $dataReturn['id'] . "><i class=\"far fa-trash-alt\"></i></button></td>";
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

    public function updateMonitoringMesinSteam($id = null)
    {
        if ($this->request->isAJAX()) {
            $this->validasiForm();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalMonitoring' => $this->validation->getError('tanggalMonitoring'),
                    'invalidJamMasukAlat' => $this->validation->getError('jamMasukAlat'),
                    'invalidJamMasuk' => $this->validation->getError('jamMasuk'),
                    'invalidShift' => $this->validation->getError('shift'),
                    'invalidOperator' => $this->validation->getError('operator'),
                    'invalidSiklus' => $this->validation->getError('siklus'),
                    'invalidMesin' => $this->validation->getError('mesin'),
                    'invalidTabelDataAlat' => $this->validation->getError('jumlahDataAlatDetail'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $tanggalMonitoring = $this->request->getPost('tanggalMonitoring') . " " . $this->request->getVar('jamMasukAlat') . ":00";
            $shift = $this->request->getPost('shift');
            $operator = $this->request->getPost('operator');
            $siklus = $this->request->getPost('siklus');
            $mesin = $this->request->getPost('mesin');
            $dataAlat = $this->request->getPost('dataAlat');

            $data = [
                'tanggal_monitoring' => $tanggalMonitoring,
                'shift' => $shift,
                'siklus' => $siklus,
                'mesin' => $mesin,
            ];
            $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
            $updateMonitoringMesinSteam = $monitoringMesinSteamModel->update($id, $data, false);
            $updateMonitoringMesinSteamLog = ($updateMonitoringMesinSteam) ? "Update" : "Gagal update";
            $updateMonitoringMesinSteamLog .= " monitoring mesin steam dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateMonitoringMesinSteamLog
            ]);
            if (!$updateMonitoringMesinSteam) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateMonitoringMesinSteamLog
                        ]
                    ]
                );
            }
            $monitoringMesinSteamOperatorModel = model(MonitoringMesinSteamOperatorModel::class);
            $deleteOperator = $monitoringMesinSteamOperatorModel
                ->where('id_monitoring_mesin_steam', $id)
                ->delete();

            $dataInsertMonitoringMesinSteamOperator = [];
            foreach ($operator as $op) {
                $dataDetail = [
                    'id_monitoring_mesin_steam' => $id,
                    'id_operator' => $op
                ];
                array_push($dataInsertMonitoringMesinSteamOperator, $dataDetail);
            }
            $insertMonitoringMesinSteamOperator = $monitoringMesinSteamOperatorModel
                ->insertMultiple($dataInsertMonitoringMesinSteamOperator);
            $insertMonitoringMesinSteamOperatorLog = ($insertMonitoringMesinSteamOperator) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinSteamOperatorLog .= " operator monitoring mesin steam dengan id master " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinSteamOperatorLog
            ]);
            if (!$insertMonitoringMesinSteamOperator) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $insertMonitoringMesinSteamOperatorLog
                        ]
                    ]
                );
            }

            if ($dataAlat) {
                $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
                $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);

                foreach ($dataAlat as $detail) {
                    $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                    $jumlah = $detail['jumlah'];
                    $alat = $detail['alat'];

                    if ($idDetailPenerimaanAlatKotor) {
                        $dataAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel
                            ->find($idDetailPenerimaanAlatKotor);

                        if ($jumlah > $dataAlatKotorBerdasarkanId['sisa']) {
                            return $this->response->setJSON(
                                [
                                    'sukses' => false,
                                    'pesan' => [
                                        'judul' => 'Gagal Simpan',
                                        'teks' => 'Jumlah ' . $alat . '<br>Melebihi Jumlah Tersedia !!!',
                                        'errorSimpan' => 'Jumlah sisa ' . $alat . 'adalah ' . $dataAlatKotorBerdasarkanId['sisa']
                                    ]
                                ]
                            );
                        }
                    }

                    $dataDetail = [
                        'id_monitoring_mesin_steam' => $id,
                        'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                        'id_alat' => $detail['idAlat'],
                        'id_ruangan' => $detail['idRuangan'],
                        'jumlah' => $jumlah,
                        'sisa_distribusi' => $jumlah
                    ];

                    $insertMonitoringMesinSteamDetail = $monitoringMesinSteamDetailModel
                        ->insert($dataDetail, false);

                    if (!$insertMonitoringMesinSteamDetail) {
                        $insertMonitoringMesinSteamDetailLog = "Gagal insert detail baru monitoring mesin steam dengan id master " . $id;
                        $this->logModel->insert([
                            "id_user" => session()->get('id_user'),
                            "log" => $insertMonitoringMesinSteamDetailLog
                        ]);

                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal',
                                    'teks' => 'Data Tidak Diedit',
                                    'errorSimpan' => $insertMonitoringMesinSteamDetailLog
                                ]
                            ]
                        );
                    }

                    if ($idDetailPenerimaanAlatKotor) {
                        $dataDetailAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel
                            ->find($idDetailPenerimaanAlatKotor);

                        $dataUpdateDetailPenerimaanAlatKotor = [
                            'status_proses' => 'Diproses',
                            'sisa' => ((int)$dataDetailAlatKotorBerdasarkanId['sisa'] - (int)$jumlah)
                        ];
                        $penerimaanAlatKotorDetailModel->update($idDetailPenerimaanAlatKotor, $dataUpdateDetailPenerimaanAlatKotor);
                    }
                }

                $insertMonitoringMesinSteamDetailLog = "Insert detail monitoring mesin steam dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertMonitoringMesinSteamDetailLog
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

    public function prosesUlangMonitoringMesinSteam($id = null)
    {
        $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
        $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);

        $dataMonitoringMesinSteamBerdasarkanId = $monitoringMesinSteamModel->find($id);
        if (!$dataMonitoringMesinSteamBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $dataDetail = $monitoringMesinSteamDetailModel->dataDetailMonitoringMesinSteamBerdasarkanIdMaster($id);

        $jamSekarang = date('H:i');
        $shift = '';
        if ($jamSekarang >= '07:00' && $jamSekarang <= '14:00') {
            $shift = 'pagi';
        } elseif ($jamSekarang >= '14:01' && $jamSekarang <= '21:00') {
            $shift = 'sore';
        }

        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);

        $data = [
            'title' => 'Proses Ulang Monitoring Mesin Steam',
            'header' => 'Monitoring Mesin Steam',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'dataMesinSteam' => $dataMonitoringMesinSteamBerdasarkanId,
            'shift' => $shift,
            'tanggalSekarang' => date('Y-m-d'),
            'jamSekarang' => $jamSekarang,
            'dataDetail' => $dataDetail
        ];

        return view('packingalat/monitoringmesinsteam/proses_ulang_monitoringmesinsteam_view', $data);
    }

    public function hapusMonitoringMesinSteam($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
            $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);

            $dataMonitoringMesinSteamBerdasarkanId = $monitoringMesinSteamModel->find($id);
            if (!$dataMonitoringMesinSteamBerdasarkanId) {
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

            $deleteMonitoringMesinSteam = $monitoringMesinSteamModel->delete($id, false);
            $deleteMonitoringMesinSteamLog = ($deleteMonitoringMesinSteam) ? "Delete" : "Gagal delete";
            $deleteMonitoringMesinSteamLog .= " monitoring mesin steam dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteMonitoringMesinSteamLog
            ]);
            if (!$deleteMonitoringMesinSteam) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteMonitoringMesinSteamLog
                        ]
                    ]
                );
            }
            $dataDetailAlat = $monitoringMesinSteamDetailModel->where('id_monitoring_mesin_steam', $id)->findAll();
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            foreach ($dataDetailAlat as $detail) {
                $idAlatKotor = $detail['id_detail_penerimaan_alat_kotor'];
                $jumlah = $detail['jumlah'];
                if ($idAlatKotor && !$dataMonitoringMesinSteamBerdasarkanId['proses_ulang']) {
                    $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

                    $updateData = [
                        'sisa' => (int)$dataAlatKotor['sisa'] + (int)$jumlah
                    ];

                    if ((int)$updateData['sisa'] === (int)$dataAlatKotor['jumlah']) {
                        $updateData['status_proses'] = '';
                    }
                    $penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
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

    public function verifikasiMonitoringMesinSteam($id)
    {
        $monitoringMesinSteamModel = model(MonitoringMesinSteamModel::class);
        $monitoringMesinSteamDetailModel = model(MonitoringMesinSteamDetailModel::class);
        $monitoringMesinSteamOperatorModel = model(MonitoringMesinSteamOperatorModel::class);
        $monitoringMesinSteamVerifikasiModel = model(MonitoringMesinSteamVerifikasiModel::class);

        $dataMonitoringMesinSteamBerdasarkanId = $monitoringMesinSteamModel->find($id);
        if (!$dataMonitoringMesinSteamBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $operatorMonitoringMesinSteamBerdasarkanIdMaster = $monitoringMesinSteamOperatorModel
            ->operatorMonitoringMesinSteamBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataDetailMonitoringMesinSteamBerdasarkanIdMaster = $monitoringMesinSteamDetailModel
            ->dataDetailMonitoringMesinSteamBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataVerifikasiMonitoringMesinSteamBerdasarkanIdMaster = $monitoringMesinSteamVerifikasiModel
            ->where('id_monitoring_mesin_steam', $id)
            ->first();

        $dataProsesUlang = $monitoringMesinSteamModel
            ->where('proses_ulang', $id)
            ->where('deleted_at', null)
            ->get()
            ->getNumRows();

        $idAlatKotor = array_column($dataDetailMonitoringMesinSteamBerdasarkanIdMaster, 'id_detail_penerimaan_alat_kotor');
        $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
        $dataDistribusi = $penerimaanAlatKotorDetailModel
            ->dataAlatKotorDistribusiBerdasarkanId($idAlatKotor)
            ->getNumRows();

        $where = "sisa_distribusi < jumlah";
        $distribusiBmhp = $monitoringMesinSteamDetailModel
            ->where('id_monitoring_mesin_steam', $id)
            ->where('id_ruangan', 'CSSD')
            ->where(new RawSql($where))
            ->where('deleted_at', null)
            ->get()
            ->getNumRows();

        $pegawaiModel = model(PegawaiModel::class);

        $data = [
            'title' => 'Verifikasi Monitoring Mesin Steam',
            'header' => 'Monitoring Mesin Steam',
            'dataMesinSteam' => $dataMonitoringMesinSteamBerdasarkanId,
            'operator' => $operatorMonitoringMesinSteamBerdasarkanIdMaster,
            'detailAlat' => $dataDetailMonitoringMesinSteamBerdasarkanIdMaster,
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataVerifikasi' => $dataVerifikasiMonitoringMesinSteamBerdasarkanIdMaster,
            'prosesUlang' => $dataProsesUlang,
            'dataDistribusi' => $dataDistribusi,
            'distribusiBmhp' => $distribusiBmhp
        ];

        return view('packingalat/monitoringmesinsteam/verifikasi_monitoringmesinsteam', $data);
    }

    public function validasiFormVerifikasi()
    {
        $this->valid = $this->validate([
            'tanggalKeluarAlat' => [
                'rules' => 'required|not_future_date|is_date_less_than[tanggalMonitoring]',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                    'is_date_less_than' => 'Tanggal harus lebih dari tanggal masuk alat'
                ],
            ],
            'jamKeluarAlat' => [
                'rules' => 'required|is_time_less_than[tanggalKeluarAlat,tanggalMonitoring]|is_time_greater_than[tanggalKeluarAlat]',
                'errors' => [
                    'required' => 'Jam harus diisi',
                    'is_time_less_than' => 'Jam harus lebih dari jam masuk alat',
                    'is_time_greater_than' => 'Jam tidak boleh lebih dari jam sekarang'
                ],
            ],
            'namaFileDataPrint' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gambar data print harus diupload',
                ]
            ],
            'dataPrint' => [
                'rules' => 'max_size[dataPrint,512]|is_image[dataPrint]|mime_in[dataPrint,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                'errors' => [
                    'max_size' => 'Ukuran gambar data print terlalu besar (Maks. 512KB)',
                    'is_image' => 'Format gambar data print harus *jpg, jpeg, png*',
                    'mime_in' => 'Format gambar data print harus *jpg, jpeg, png*'
                ]
            ],
            'namaFileIndikatorKimiaEksternal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gambar indikator kimia eksternal harus diupload',
                ]
            ],
            'indikatorKimiaEksternal' => [
                'rules' => 'max_size[indikatorKimiaEksternal,512]|is_image[indikatorKimiaEksternal]|mime_in[indikatorKimiaEksternal,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                'errors' => [
                    'max_size' => 'Ukuran gambar indikator kimia eksternal terlalu besar (Maks. 512KB)',
                    'is_image' => 'Format gambar indikator kimia eksternal harus *jpg, jpeg, png*',
                    'mime_in' => 'Format gambar indikator kimia eksternal harus *jpg, jpeg, png*'
                ]
            ],
            'namaFileIndikatorKimiaInternal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gambar indikator kimia internal harus diupload',
                ]
            ],
            'indikatorKimiaInternal' => [
                'rules' => 'max_size[indikatorKimiaInternal,512]|is_image[indikatorKimiaInternal]|mime_in[indikatorKimiaInternal,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                'errors' => [
                    'max_size' => 'Ukuran gambar indikator kimia internal terlalu besar (Maks. 512KB)',
                    'is_image' => 'Format gambar indikator kimia internal harus *jpg, jpeg, png*',
                    'mime_in' => 'Format gambar indikator kimia internal harus *jpg, jpeg, png*'
                ]
            ],
            'namaFileIndikatorBiologi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gambar indikator biologi harus diupload',
                ]
            ],
            'indikatorBiologi' => [
                'rules' => 'max_size[indikatorBiologi,512]|is_image[indikatorBiologi]|mime_in[indikatorBiologi,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                'errors' => [
                    'max_size' => 'Ukuran gambar indikator biologi terlalu besar (Maks. 512KB)',
                    'is_image' => 'Format gambar indikator biologi harus *jpg, jpeg, png*',
                    'mime_in' => 'Format gambar indikator biologi harus *jpg, jpeg, png*'
                ]
            ],
            'verifikator' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Verifikator harus dipilih',
                ]
            ],
            'hasilVerifikasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Hasil verifikasi harus dipilih',
                ]
            ]
        ]);
    }

    public function simpanVerifikasi($id)
    {
        if ($this->request->isAJAX()) {
            $this->validasiFormVerifikasi();
            if (!$this->valid) {
                $pesanError = [
                    'invalidTanggalKeluarAlat' => $this->validation->getError('tanggalKeluarAlat'),
                    'invalidJamKeluarAlat' => $this->validation->getError('jamKeluarAlat'),
                    'invalidNamaFileDataPrint' => $this->validation->getError('namaFileDataPrint'),
                    'invalidDataPrint' => $this->validation->getError('dataPrint'),
                    'invalidNamaFileIndikatorKimiaEksternal' => $this->validation->getError('namaFileIndikatorKimiaEksternal'),
                    'invalidIndikatorKimiaEksternal' => $this->validation->getError('indikatorKimiaEksternal'),
                    'invalidNamaFileIndikatorKimiaInternal' => $this->validation->getError('namaFileIndikatorKimiaInternal'),
                    'invalidIndikatorKimiaInternal' => $this->validation->getError('indikatorKimiaInternal'),
                    'invalidNamaFileIndikatorBiologi' => $this->validation->getError('namaFileIndikatorBiologi'),
                    'invalidIndikatorBiologi' => $this->validation->getError('indikatorBiologi'),
                    'invalidVerifikator' => $this->validation->getError('verifikator'),
                    'invalidHasilVerifikasi' => $this->validation->getError('hasilVerifikasi'),
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $pesanError
                    ]
                );
            }

            $waktukeluarAlat = $this->request->getPost('tanggalKeluarAlat') . " " . $this->request->getVar('jamKeluarAlat') . ":00";
            $dataPrint = $this->request->getFile('dataPrint');
            $indikatorEksternal = $this->request->getFile('indikatorKimiaEksternal');
            $indikatorInternal = $this->request->getFile('indikatorKimiaInternal');
            $indikatorBiologi = $this->request->getFile('indikatorBiologi');
            $verifikator = $this->request->getPost('verifikator');
            $hasilVerifikasi = $this->request->getVar('hasilVerifikasi');

            $data = [
                'waktu_keluar_alat' => $waktukeluarAlat,
                'data_print' => $dataPrint->getRandomName(),
                'indikator_eksternal' => $indikatorEksternal->getRandomName(),
                'indikator_internal' => $indikatorInternal->getRandomName(),
                'indikator_biologi' => $indikatorBiologi->getRandomName(),
                'id_petugas_verifikator' => $verifikator,
                'hasil_verifikasi' => $hasilVerifikasi,
            ];

            $monitoringMesinSteamVerifikasiModel = model(MonitoringMesinSteamVerifikasiModel::class);
            $dataVerifikasiBerdasarkanId = $monitoringMesinSteamVerifikasiModel->find($id);
            if (!$dataVerifikasiBerdasarkanId) {
                $dataId = [
                    'id' => generateUUID(),
                    'id_monitoring_mesin_steam' => $id
                ];
                $dataInsert = array_merge($dataId, $data);
                $insertVerifikasiMonitoringMesinSteam = $monitoringMesinSteamVerifikasiModel->insert($dataInsert);
                $insertVerifikasiMonitoringMesinSteamLog = ($insertVerifikasiMonitoringMesinSteam) ? "Insert" : "Gagal insert";
                $insertVerifikasiMonitoringMesinSteamLog .= " verifikasi monitoring mesin steam dengan id " . $insertVerifikasiMonitoringMesinSteam;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertVerifikasiMonitoringMesinSteamLog
                ]);

                if (!$insertVerifikasiMonitoringMesinSteam) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertVerifikasiMonitoringMesinSteamLog
                            ]
                        ]
                    );
                }
                $dataPrint->move('public/img/monitoringmesinsteam', $data['data_print']);
                $indikatorEksternal->move('public/img/monitoringmesinsteam', $data['indikator_eksternal']);
                $indikatorInternal->move('public/img/monitoringmesinsteam', $data['indikator_internal']);
                $indikatorBiologi->move('public/img/monitoringmesinsteam', $data['indikator_biologi']);

                return $this->response->setJSON(
                    [
                        'sukses' => true,
                        'pesan' => [
                            'judul' => 'Berhasil',
                            'teks' => 'Data Disimpan',
                        ]
                    ]
                );
            }

            if ($dataPrint->getError() == 4) {
                $print = $dataVerifikasiBerdasarkanId['data_print'];
            } else {
                $print = $dataPrint->getRandomName();
                $dataPrint->move('public/img/monitoringmesinsteam', $print);
                unlink('public/img/monitoringmesinsteam/' . $dataVerifikasiBerdasarkanId['data_print']);
            }

            if ($indikatorEksternal->getError() == 4) {
                $eksternal = $dataVerifikasiBerdasarkanId['indikator_eksternal'];
            } else {
                $eksternal = $indikatorEksternal->getRandomName();
                $indikatorEksternal->move('public/img/monitoringmesinsteam', $eksternal);
                unlink('public/img/monitoringmesinsteam/' . $dataVerifikasiBerdasarkanId['indikator_eksternal']);
            }

            if ($indikatorInternal->getError() == 4) {
                $internal = $dataVerifikasiBerdasarkanId['indikator_internal'];
            } else {
                $internal = $indikatorInternal->getRandomName();
                $indikatorInternal->move('public/img/monitoringmesinsteam', $internal);
                unlink('public/img/monitoringmesinsteam/' . $dataVerifikasiBerdasarkanId['indikator_internal']);
            }

            if ($indikatorBiologi->getError() == 4) {
                $biologi = $dataVerifikasiBerdasarkanId['indikator_biologi'];
            } else {
                $biologi = $indikatorBiologi->getRandomName();
                $indikatorBiologi->move('public/img/monitoringmesinsteam', $biologi);
                unlink('public/img/monitoringmesinsteam/' . $dataVerifikasiBerdasarkanId['indikator_biologi']);
            }

            $dataUpdate = [
                'waktu_keluar_alat' => $waktukeluarAlat,
                'data_print' => $print,
                'indikator_eksternal' => $eksternal,
                'indikator_internal' => $internal,
                'indikator_biologi' => $biologi,
                'id_petugas_verifikator' => $verifikator,
                'hasil_verifikasi' => $hasilVerifikasi,
            ];

            $updateVerifikasiMonitoringMesinSteam = $monitoringMesinSteamVerifikasiModel->update($id, $dataUpdate);
            $updateVerifikasiMonitoringMesinSteamLog = ($updateVerifikasiMonitoringMesinSteam) ? "Update" : "Gagal update";
            $updateVerifikasiMonitoringMesinSteamLog .= " verifikasi monitoring mesin steam dengan id " . $updateVerifikasiMonitoringMesinSteam;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateVerifikasiMonitoringMesinSteamLog
            ]);

            if (!$updateVerifikasiMonitoringMesinSteam) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateVerifikasiMonitoringMesinSteamLog
                        ]
                    ]
                );
            }

            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'judul' => 'Berhasil',
                        'teks' => 'Data Diedit',
                    ]
                ]
            );
        }
    }

    public function hapusVerifikasi($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinSteamVerifikasiModel = model(MonitoringMesinSteamVerifikasiModel::class);
            $dataVerifikasiBerdasarkanId = $monitoringMesinSteamVerifikasiModel->find($id);
            if (!$dataVerifikasiBerdasarkanId) {
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

            $deleteVerifikasiMonitoringMesinSteam = $monitoringMesinSteamVerifikasiModel->delete($id, false);
            $deleteVerifikasiMonitoringMesinSteamLog = ($deleteVerifikasiMonitoringMesinSteam) ? "Delete" : "Gagal delete";
            $deleteVerifikasiMonitoringMesinSteamLog .= " verifikasi monitoring mesin steam dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteVerifikasiMonitoringMesinSteamLog
            ]);
            if (!$deleteVerifikasiMonitoringMesinSteam) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteVerifikasiMonitoringMesinSteamLog
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
