<?php

namespace App\Controllers\PackingalatControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DepartemenModel;
use App\Models\DataModels\PegawaiModel;
use App\Models\DataModels\SetAlatModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorDetailModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorModel;
use App\Models\PackingAlatModels\MonitoringMesinEogDetailModel;
use App\Models\PackingAlatModels\MonitoringMesinEogModel;
use App\Models\PackingAlatModels\MonitoringMesinEogOperatorModel;
use App\Models\PackingAlatModels\MonitoringMesinEogVerifikasiModel;

class MonitoringMesinEogController extends BaseController
{

    protected $pegawaiModel;
    protected $departemenModel;
    protected $setAlatModel;
    protected $penerimaanAlatKotorModel;
    protected $penerimaanAlatKotorDetailModel;
    protected $monitoringMesinEogModel;
    protected $monitoringMesinEogDetailModel;
    protected $monitoringMesinEogOperatorModel;
    protected $monitoringMesinEogVerifikasiModel;
    protected $valid;
    protected $validation;

    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
        $this->departemenModel = new DepartemenModel();
        $this->penerimaanAlatKotorModel = new PenerimaanAlatKotorModel();
        $this->penerimaanAlatKotorDetailModel = new PenerimaanAlatKotorDetailModel();
        $this->monitoringMesinEogModel = new MonitoringMesinEogModel();
        $this->monitoringMesinEogDetailModel = new MonitoringMesinEogDetailModel();
        $this->monitoringMesinEogOperatorModel = new MonitoringMesinEogOperatorModel();
        $this->monitoringMesinEogVerifikasiModel = new MonitoringMesinEogVerifikasiModel();
        $this->setAlatModel = new SetAlatModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Monitoring Mesin EOG',
            'header' => 'Monitoring Mesin EOG (Gas Ethylen Oxyd)',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
        ];
        return view('packingalat/monitoringmesineog/index_monitoringmesineog_view', $data);
    }

    public function tambahMonitoringMesinEog()
    {
        $jamSekarang = date('H:i');
        $shift = '';
        if ($jamSekarang >= '07:00' && $jamSekarang <= '14:00') {
            $shift = 'pagi';
        } elseif ($jamSekarang >= '14:01' && $jamSekarang <= '21:00') {
            $shift = 'sore';
        }
        $data = [
            'title' => 'Tambah Monitoring Mesin EOG',
            'header' => 'Monitoring Mesin EOG (Gas Ethylen Oxyd)',
            'tglSekarang' => date('Y-m-d'),
            'jamSekarang' => $jamSekarang,
            'shift' => $shift,
            'listPegawaiCSSD' => $this->pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $this->departemenModel->getListDepartemen(),
        ];
        return view('packingalat/monitoringmesineog/tambah_monitoringmesineog_view', $data);
    }

    public function dataMonitoringMesinEogBerdasarkanTanggal()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');
            $dataMonitoringMesinEogBerdasarkanTanggaldanLimit = $this->monitoringMesinEogModel
                ->dataMonitoringMesinEogBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
                ->getResultArray();

            $jumlahDataMonitoringMesinEogBerdasarkanTanggal = $this->monitoringMesinEogModel
                ->dataMonitoringMesinEogBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataMonitoringMesinEogBerdasarkanTanggaldanLimit as $data) {
                $tombolDetail = "<button data-popup='tooltip' title='Detail Monitoring Mesin Eog' class=\"btn btn-info btn-sm border-0\" onclick=\"detailMonitoringMesinEog(this,'" . $data['id'] . "')\"><i class=\"fas fa-file-lines\"></i></button>";

                $a = $data['proses_ulang'] ? "" : "<a href=\"" . base_url('monitoring-mesin-eog/edit/' . $data['id']) . "\" class=\"btn btn-warning btn-sm border-0\" data-popup='tooltip' title='Edit Data'><i class=\"fas fa-edit\"></i></a>";
                $form = "<form action=\"" . base_url('monitoring-mesin-eog/hapus/' . $data['id']) . "\" method=\"POST\" class=\"formHapus\"><input type=\"hidden\" name=\"" . csrf_token() . "\" value=\"" . csrf_hash() . "\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\"><button type=\"submit\" class=\"ml-1 btn btn-danger btn-sm border-0\" data-popup='tooltip' title='Hapus Data'><i class=\"far fa-trash-alt\"></i></button></form>";
                $tombolAksi = "<div class=\"d-flex justify-content-center\">" . $a . $form . "</div>";
                $aksi = $tombolAksi;

                $dataVerifikasi = $this->monitoringMesinEogVerifikasiModel
                    ->dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster($data['id'])
                    ->getFirstRow('array');
                $prosesDari = "";
                $keterangan = "<i class=\"fa-solid fa-minus\"></i>";
                if ($data['proses_ulang']) {
                    $prosesDari = "<span class=\"badge badge-info\">Proses dari " . generateNoReferensi($data['created_proses_ulang'], $data['proses_ulang']) . "</span>";
                    $keterangan = "";
                }
                $verifikasi = "<a href=\"" . base_url('monitoring-mesin-eog/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Eog\" class=\"btn btn-primary btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";
                $keterangan .= $prosesDari;

                if ($dataVerifikasi) {
                    $hasilVerifikasi = $dataVerifikasi['hasil_verifikasi'];
                    $keterangan = $prosesDari . " <span class=\"badge badge-success\">" . $hasilVerifikasi . "</span>";
                    $verifikasi = "<a href=\"" . base_url('monitoring-mesin-eog/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Eog\" class=\"btn btn-success btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";
                    $aksi = "<i class=\"fa-solid fa-minus\"></i>";

                    if (strtolower($hasilVerifikasi) === 'tidak steril') {
                        $keterangan = $prosesDari . " <span class=\"badge badge-danger\">" . $hasilVerifikasi . "</span>";
                        $verifikasi = "<a href=\"" . base_url('monitoring-mesin-eog/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Eog\" class=\"btn btn-danger btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";

                        $aksi = "<a href=\"" . base_url('monitoring-mesin-eog/proses-ulang/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Proses Ulang\" class=\"btn btn-primary btn-sm border-0 ml-1\"><i class=\"fa-solid fa-repeat\"></i></a>";

                        $dataProsesUlang = $this->monitoringMesinEogModel
                            ->where('proses_ulang', $data['id'])
                            ->where('deleted_at', null)
                            ->get()->getNumRows();

                        if ($dataProsesUlang > 0) {
                            // $verifikasi = "<i class=\"fa-solid fa-minus\"></i>";
                            $keterangan .= " <span class=\"badge badge-secondary\">Proses Ulang</span>";
                            $aksi = "<i class=\"fa-solid fa-minus\"></i>";
                        }
                    }
                }


                $td = [
                    'noReferensi' => generateNoReferensi($data['created_at'], $data['id']),
                    'tanggalMonitoring' => date("d-m-Y", strtotime($data['tanggal_monitoring'])),
                    'jamMasukAlat' => date("H:i", strtotime($data['tanggal_monitoring'])),
                    'siklus' => $data['siklus'],
                    'verifikasi' => $verifikasi,
                    'keterangan' => $keterangan,
                    'detail' => $tombolDetail,
                    'aksi' => $aksi,
                ];

                $dataResult[] = $td;
            }

            $json = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => $jumlahDataMonitoringMesinEogBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataMonitoringMesinEogBerdasarkanTanggal,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailMonitoringMesinEog($id)
    {
        if ($this->request->isAJAX()) {
            $dataMonitoringMesinEogBerdasarkanId = $this->monitoringMesinEogModel->find($id);
            if (!$dataMonitoringMesinEogBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $operatorMonitoringMesinEogBerdasarkanIdMaster = $this->monitoringMesinEogOperatorModel
                ->operatorMonitoringMesinEogBerdasarkanIdMaster($id)
                ->getResultArray();

            $dataDetailMonitoringMesinEogBerdasarkanIdMaster = $this->monitoringMesinEogDetailModel
                ->dataDetailMonitoringMesinEogBerdasarkanIdMaster($id);

            $dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster = $this->monitoringMesinEogVerifikasiModel
                ->dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster($id)
                ->getFirstRow('array');

            $data = [
                'dataMesinEog' => $dataMonitoringMesinEogBerdasarkanId,
                'operator' => $operatorMonitoringMesinEogBerdasarkanIdMaster,
                'detailAlat' => $dataDetailMonitoringMesinEogBerdasarkanIdMaster,
                'dataVerifikasi' => $dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster,
            ];

            $json = [
                'sukses' => true,
                'data' => view('packingalat/monitoringmesineog/modal_detail_monitoring_mesin_eog', $data)
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
            'jumlahDataAlatDetail' => [
                'rules' => 'greater_than[0]',
                'errors' => [
                    'greater_than' => 'Tabel alat harus diisi'
                ]
            ]
        ]);
    }

    public function simpanMonitoringMesinEog()
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
            $prosesUlang = $this->request->getPost('prosesUlang');
            $dataAlat = $this->request->getPost('dataAlat');

            $data = [
                'id' => generateUUID(),
                'tanggal_monitoring' => $tanggalMonitoring,
                'shift' => $shift,
                'siklus' => $siklus,
            ];

            if ($prosesUlang) {
                $dataMesinEog = $this->monitoringMesinEogModel->find($prosesUlang);
                if (!$dataMesinEog) {
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

                $dataProsesUlang = $this->monitoringMesinEogModel
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

                $data['created_proses_ulang'] = $dataMesinEog['created_at'];
                $data['proses_ulang'] = $prosesUlang;
            }

            $insertMonitoringMesinEog = $this->monitoringMesinEogModel->insert($data);
            $insertMonitoringMesinEogLog = ($insertMonitoringMesinEog) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinEogLog .= " monitoring mesin eog dengan id " . $insertMonitoringMesinEog;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinEogLog
            ]);
            if (!$insertMonitoringMesinEog) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringMesinEogLog
                        ]
                    ]
                );
            }

            $dataInsertMonitoringMesinEogOperator = [];
            foreach ($operator as $op) {
                $dataDetail = [
                    'id_monitoring_mesin_eog' => $insertMonitoringMesinEog,
                    'id_operator' => $op
                ];
                array_push($dataInsertMonitoringMesinEogOperator, $dataDetail);
            }
            $insertMonitoringMesinEogOperator = $this->monitoringMesinEogOperatorModel->insertMultiple($dataInsertMonitoringMesinEogOperator);
            $insertMonitoringMesinEogOperatorLog = ($insertMonitoringMesinEogOperator) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinEogOperatorLog .= " operator monitoring mesin eog dengan id master " . $insertMonitoringMesinEog;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinEogOperatorLog
            ]);
            if (!$insertMonitoringMesinEogOperator) {
                $this->monitoringMesinEogModel->delete($insertMonitoringMesinEog);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringMesinEogOperatorLog
                        ]
                    ]
                );
            }

            foreach ($dataAlat as $detail) {
                $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                $jumlah = $detail['jumlah'];
                $alat = $detail['alat'];

                if ($idDetailPenerimaanAlatKotor && !$prosesUlang) {
                    $dataAlatKotorBerdasarkanId = $this->penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);

                    if ($jumlah > $dataAlatKotorBerdasarkanId['sisa']) {
                        $this->monitoringMesinEogModel->delete($insertMonitoringMesinEog);
                        $this->monitoringMesinEogOperatorModel->where('id_monitoring_mesin_eog', $insertMonitoringMesinEog)->delete();

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
                    'id_monitoring_mesin_eog' => $insertMonitoringMesinEog,
                    'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                    'id_alat' => $detail['idAlat'],
                    'id_ruangan' => $detail['idRuangan'],
                    'jumlah' => $jumlah,
                ];

                $insertMonitoringMesinEogDetail = $this->monitoringMesinEogDetailModel->insert($dataDetail, false);
                if (!$insertMonitoringMesinEogDetail) {
                    $this->monitoringMesinEogModel->delete($insertMonitoringMesinEog);
                    $this->monitoringMesinEogOperatorModel->where('id_monitoring_mesin_eog', $insertMonitoringMesinEog)->delete();
                    $this->monitoringMesinEogDetailModel->where('id_monitoring_mesin_eog', $insertMonitoringMesinEog)->delete();
                    $insertMonitoringMesinEogDetailLog = "Gagal insert detail monitoring mesin eog dengan id master " . $insertMonitoringMesinEog;
                    $this->logModel->insert([
                        "id_user" => session()->get('id_user'),
                        "log" => $insertMonitoringMesinEogDetailLog
                    ]);

                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertMonitoringMesinEogDetailLog
                            ]
                        ]
                    );
                }

                if ($idDetailPenerimaanAlatKotor && !$prosesUlang) {
                    $dataDetailAlatKotorBerdasarkanId = $this->penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);
                    $dataUpdateDetailPenerimaanAlatKotor = [
                        'status_proses' => 'Diproses',
                        'sisa' => ((int)$dataDetailAlatKotorBerdasarkanId['sisa'] - (int)$jumlah)
                    ];
                    $this->penerimaanAlatKotorDetailModel->update($idDetailPenerimaanAlatKotor, $dataUpdateDetailPenerimaanAlatKotor);
                }
            }

            $insertMonitoringMesinEogDetailLog = "Insert detail monitoring mesin eog dengan id master " . $insertMonitoringMesinEog;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinEogDetailLog
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

    public function editMonitoringMesinEog($id = null)
    {
        $dataMonitoringMesinEogBerdasarkanId = $this->monitoringMesinEogModel->find($id);
        if (!$dataMonitoringMesinEogBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($dataMonitoringMesinEogBerdasarkanId['proses_ulang']) {
            return redirect()->to('/monitoring-mesin-eog');
        }

        $dataOperator = $this->monitoringMesinEogOperatorModel
            ->operatorMonitoringMesinEogBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataDetail = $this->monitoringMesinEogDetailModel
            ->dataDetailMonitoringMesinEogBerdasarkanIdMaster($id);

        $data = [
            'title' => 'Edit Monitoring Mesin EOG',
            'header' => 'Monitoring Mesin EOG (Gas Ethylen Oxyd)',
            'listPegawaiCSSD' => $this->pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $this->departemenModel->getListDepartemen(),
            'dataMesinEog' => $dataMonitoringMesinEogBerdasarkanId,
            'dataOperator' => $dataOperator,
            'dataDetail' => $dataDetail
        ];
        return view('packingalat/monitoringmesineog/edit_monitoringmesineog_view', $data);
    }

    public function hapusDetailMonitoringMesinEog($id)
    {
        if ($this->request->isAJAX()) {
            $dataDetail = $this->monitoringMesinEogDetailModel->find($id);
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

            $deleteDetail = $this->monitoringMesinEogDetailModel->delete($id, false);
            $deleteDetailLog = ($deleteDetail) ? "Delete" : "Gagal delete";
            $deleteDetailLog .= " detail monitoring mesin eog dengan id " . $id;
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
                $dataAlatKotor = $this->penerimaanAlatKotorDetailModel->find($idAlatKotor);

                $updateData = [
                    'sisa' => (int)$dataAlatKotor['sisa'] + (int)$jumlah
                ];

                if ((int)$updateData['sisa'] === (int)$dataAlatKotor['jumlah']) {
                    $updateData['status_proses'] = '';
                }
                $this->penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
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

    public function batalHapusDetailMonitoringMesinEog($id)
    {
        if ($this->request->isAJAX()) {
            $updateDetail = $this->monitoringMesinEogDetailModel->update($id, ['deleted_at' => null], false);
            $updateDetailLog = ($updateDetail) ? "Update" : "Gagal update";
            $updateDetailLog .= " 'Batal Hapus' detail monitoring mesin eog dengan id " . $id;
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
            $dataDetail = $this->monitoringMesinEogDetailModel->find($id);
            $idAlatKotor = $dataDetail['id_detail_penerimaan_alat_kotor'];
            $jumlah = $dataDetail['jumlah'];
            if ($idAlatKotor) {
                $dataAlatKotor = $this->penerimaanAlatKotorDetailModel->find($idAlatKotor);

                $updateData = [
                    'sisa' => (int)$dataAlatKotor['sisa'] - (int)$jumlah,
                    'status_proses' => 'Diproses'
                ];
                $this->penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
            }

            $dataReturn = $this->monitoringMesinEogDetailModel
                ->dataDetailMonitoringMesinEogBerdasarkanId($id)
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

    public function updateMonitoringMesinEog($id = null)
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
            $dataAlat = $this->request->getPost('dataAlat');

            $data = [
                'tanggal_monitoring' => $tanggalMonitoring,
                'shift' => $shift,
                'siklus' => $siklus,
            ];

            $updateMonitoringMesinEog = $this->monitoringMesinEogModel->update($id, $data, false);
            $updateMonitoringMesinEogLog = ($updateMonitoringMesinEog) ? "Update" : "Gagal update";
            $updateMonitoringMesinEogLog .= " monitoring mesin eog dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateMonitoringMesinEogLog
            ]);
            if (!$updateMonitoringMesinEog) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateMonitoringMesinEogLog
                        ]
                    ]
                );
            }

            $deleteOperator = $this->monitoringMesinEogOperatorModel
                ->where('id_monitoring_mesin_eog', $id)
                ->delete();

            $dataInsertMonitoringMesinEogOperator = [];
            foreach ($operator as $op) {
                $dataDetail = [
                    'id_monitoring_mesin_eog' => $id,
                    'id_operator' => $op
                ];
                array_push($dataInsertMonitoringMesinEogOperator, $dataDetail);
            }
            $insertMonitoringMesinEogOperator = $this->monitoringMesinEogOperatorModel
                ->insertMultiple($dataInsertMonitoringMesinEogOperator);
            $insertMonitoringMesinEogOperatorLog = ($insertMonitoringMesinEogOperator) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinEogOperatorLog .= " operator monitoring mesin eog dengan id master " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinEogOperatorLog
            ]);
            if (!$insertMonitoringMesinEogOperator) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $insertMonitoringMesinEogOperatorLog
                        ]
                    ]
                );
            }

            if ($dataAlat) {
                foreach ($dataAlat as $detail) {
                    $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                    $jumlah = $detail['jumlah'];
                    $alat = $detail['alat'];

                    if ($idDetailPenerimaanAlatKotor) {
                        $dataAlatKotorBerdasarkanId = $this->penerimaanAlatKotorDetailModel
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
                        'id_monitoring_mesin_eog' => $id,
                        'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                        'id_alat' => $detail['idAlat'],
                        'id_ruangan' => $detail['idRuangan'],
                        'jumlah' => $jumlah,
                    ];

                    $insertMonitoringMesinEogDetail = $this->monitoringMesinEogDetailModel
                        ->insert($dataDetail, false);

                    if (!$insertMonitoringMesinEogDetail) {
                        $insertMonitoringMesinEogDetailLog = "Gagal insert detail baru monitoring mesin eog dengan id master " . $id;
                        $this->logModel->insert([
                            "id_user" => session()->get('id_user'),
                            "log" => $insertMonitoringMesinEogDetailLog
                        ]);

                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal',
                                    'teks' => 'Data Tidak Diedit',
                                    'errorSimpan' => $insertMonitoringMesinEogDetailLog
                                ]
                            ]
                        );
                    }

                    if ($idDetailPenerimaanAlatKotor) {
                        $dataDetailAlatKotorBerdasarkanId = $this->penerimaanAlatKotorDetailModel
                            ->find($idDetailPenerimaanAlatKotor);

                        $dataUpdateDetailPenerimaanAlatKotor = [
                            'status_proses' => 'Diproses',
                            'sisa' => ((int)$dataDetailAlatKotorBerdasarkanId['sisa'] - (int)$jumlah)
                        ];
                        $this->penerimaanAlatKotorDetailModel->update($idDetailPenerimaanAlatKotor, $dataUpdateDetailPenerimaanAlatKotor);
                    }
                }

                $insertMonitoringMesinEogDetailLog = "Insert detail monitoring mesin eog dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertMonitoringMesinEogDetailLog
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

    public function prosesUlangMonitoringMesinEog($id = null)
    {
        $dataMonitoringMesinEogBerdasarkanId = $this->monitoringMesinEogModel->find($id);
        if (!$dataMonitoringMesinEogBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $dataDetail = $this->monitoringMesinEogDetailModel->dataDetailMonitoringMesinEogBerdasarkanIdMaster($id);

        $jamSekarang = date('H:i');
        $shift = '';
        if ($jamSekarang >= '07:00' && $jamSekarang <= '14:00') {
            $shift = 'pagi';
        } elseif ($jamSekarang >= '14:01' && $jamSekarang <= '21:00') {
            $shift = 'sore';
        }

        $data = [
            'title' => 'Proses Ulang Monitoring Mesin EOG',
            'header' => 'Monitoring Mesin EOG (Gas Ethylen Oxyd)',
            'listPegawaiCSSD' => $this->pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $this->departemenModel->getListDepartemen(),
            'dataMesinEog' => $dataMonitoringMesinEogBerdasarkanId,
            'shift' => $shift,
            'tanggalSekarang' => date('Y-m-d'),
            'jamSekarang' => $jamSekarang,
            'dataDetail' => $dataDetail
        ];

        return view('packingalat/monitoringmesineog/proses_ulang_monitoringmesineog_view', $data);
    }

    public function hapusMonitoringMesinEog($id)
    {
        if ($this->request->isAJAX()) {
            $dataMonitoringMesinEogBerdasarkanId = $this->monitoringMesinEogModel->find($id);
            if (!$dataMonitoringMesinEogBerdasarkanId) {
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

            $deleteMonitoringMesinEog = $this->monitoringMesinEogModel->delete($id, false);
            $deleteMonitoringMesinEogLog = ($deleteMonitoringMesinEog) ? "Delete" : "Gagal delete";
            $deleteMonitoringMesinEogLog .= " monitoring mesin eog dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteMonitoringMesinEogLog
            ]);
            if (!$deleteMonitoringMesinEog) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteMonitoringMesinEogLog
                        ]
                    ]
                );
            }
            $dataDetailAlat = $this->monitoringMesinEogDetailModel->where('id_monitoring_mesin_eog', $id)->findAll();

            foreach ($dataDetailAlat as $detail) {
                $idAlatKotor = $detail['id_detail_penerimaan_alat_kotor'];
                $jumlah = $detail['jumlah'];
                if ($idAlatKotor && !$dataMonitoringMesinEogBerdasarkanId['proses_ulang']) {
                    $dataAlatKotor = $this->penerimaanAlatKotorDetailModel->find($idAlatKotor);

                    $updateData = [
                        'sisa' => (int)$dataAlatKotor['sisa'] + (int)$jumlah
                    ];

                    if ((int)$updateData['sisa'] === (int)$dataAlatKotor['jumlah']) {
                        $updateData['status_proses'] = '';
                    }
                    $this->penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
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

    public function verifikasiMonitoringMesinEog($id)
    {
        $dataMonitoringMesinEogBerdasarkanId = $this->monitoringMesinEogModel->find($id);
        if (!$dataMonitoringMesinEogBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $operatorMonitoringMesinEogBerdasarkanIdMaster = $this->monitoringMesinEogOperatorModel
            ->operatorMonitoringMesinEogBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataDetailMonitoringMesinEogBerdasarkanIdMaster = $this->monitoringMesinEogDetailModel
            ->dataDetailMonitoringMesinEogBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster = $this->monitoringMesinEogVerifikasiModel
            ->where('id_monitoring_mesin_eog', $id)
            ->first();

        $dataProsesUlang = $this->monitoringMesinEogModel
            ->where('proses_ulang', $id)
            ->where('deleted_at', null)
            ->get()
            ->getNumRows();

        $idAlatKotor = array_column($dataDetailMonitoringMesinEogBerdasarkanIdMaster, 'id_detail_penerimaan_alat_kotor');
        $dataDistribusi = $this->penerimaanAlatKotorDetailModel
            ->dataAlatKotorDistribusiBerdasarkanId($idAlatKotor)
            ->getNumRows();

        $data = [
            'title' => 'Verifikasi Monitoring Mesin EOG',
            'header' => 'Monitoring Mesin EOG (Gas Ethylen Oxyd)',
            'dataMesinEog' => $dataMonitoringMesinEogBerdasarkanId,
            'operator' => $operatorMonitoringMesinEogBerdasarkanIdMaster,
            'detailAlat' => $dataDetailMonitoringMesinEogBerdasarkanIdMaster,
            'listPegawaiCSSD' => $this->pegawaiModel->getListPegawaiCSSD(),
            'dataVerifikasi' => $dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster,
            'prosesUlang' => $dataProsesUlang,
            'dataDistribusi' => $dataDistribusi
        ];

        return view('packingalat/monitoringmesineog/verifikasi_monitoringmesineog', $data);
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

            $dataVerifikasiBerdasarkanId = $this->monitoringMesinEogVerifikasiModel->find($id);
            if (!$dataVerifikasiBerdasarkanId) {
                $dataId = [
                    'id' => generateUUID(),
                    'id_monitoring_mesin_eog' => $id
                ];
                $dataInsert = array_merge($dataId, $data);
                $insertVerifikasiMonitoringMesinEog = $this->monitoringMesinEogVerifikasiModel->insert($dataInsert);
                $insertVerifikasiMonitoringMesinEogLog = ($insertVerifikasiMonitoringMesinEog) ? "Insert" : "Gagal insert";
                $insertVerifikasiMonitoringMesinEogLog .= " verifikasi monitoring mesin eog dengan id " . $insertVerifikasiMonitoringMesinEog;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertVerifikasiMonitoringMesinEogLog
                ]);

                if (!$insertVerifikasiMonitoringMesinEog) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertVerifikasiMonitoringMesinEogLog
                            ]
                        ]
                    );
                }
                $dataPrint->move('img/monitoringmesineog', $data['data_print']);
                $indikatorEksternal->move('img/monitoringmesineog', $data['indikator_eksternal']);
                $indikatorInternal->move('img/monitoringmesineog', $data['indikator_internal']);
                $indikatorBiologi->move('img/monitoringmesineog', $data['indikator_biologi']);

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
                $dataPrint->move('img/monitoringmesineog', $print);
                unlink('img/monitoringmesineog/' . $dataVerifikasiBerdasarkanId['data_print']);
            }

            if ($indikatorEksternal->getError() == 4) {
                $eksternal = $dataVerifikasiBerdasarkanId['indikator_eksternal'];
            } else {
                $eksternal = $indikatorEksternal->getRandomName();
                $indikatorEksternal->move('img/monitoringmesineog', $eksternal);
                unlink('img/monitoringmesineog/' . $dataVerifikasiBerdasarkanId['indikator_eksternal']);
            }

            if ($indikatorInternal->getError() == 4) {
                $internal = $dataVerifikasiBerdasarkanId['indikator_internal'];
            } else {
                $internal = $indikatorInternal->getRandomName();
                $indikatorInternal->move('img/monitoringmesineog', $internal);
                unlink('img/monitoringmesineog/' . $dataVerifikasiBerdasarkanId['indikator_internal']);
            }

            if ($indikatorBiologi->getError() == 4) {
                $biologi = $dataVerifikasiBerdasarkanId['indikator_biologi'];
            } else {
                $biologi = $indikatorBiologi->getRandomName();
                $indikatorBiologi->move('img/monitoringmesineog', $biologi);
                unlink('img/monitoringmesineog/' . $dataVerifikasiBerdasarkanId['indikator_biologi']);
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

            $updateVerifikasiMonitoringMesinEog = $this->monitoringMesinEogVerifikasiModel->update($id, $dataUpdate);
            $updateVerifikasiMonitoringMesinEogLog = ($updateVerifikasiMonitoringMesinEog) ? "Update" : "Gagal update";
            $updateVerifikasiMonitoringMesinEogLog .= " verifikasi monitoring mesin eog dengan id " . $updateVerifikasiMonitoringMesinEog;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateVerifikasiMonitoringMesinEogLog
            ]);

            if (!$updateVerifikasiMonitoringMesinEog) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateVerifikasiMonitoringMesinEogLog
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
            $dataVerifikasiBerdasarkanId = $this->monitoringMesinEogVerifikasiModel->find($id);
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

            $deleteVerifikasiMonitoringMesinEog = $this->monitoringMesinEogVerifikasiModel->delete($id, false);
            $deleteVerifikasiMonitoringMesinEogLog = ($deleteVerifikasiMonitoringMesinEog) ? "Delete" : "Gagal delete";
            $deleteVerifikasiMonitoringMesinEogLog .= " verifikasi monitoring mesin eog dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteVerifikasiMonitoringMesinEogLog
            ]);
            if (!$deleteVerifikasiMonitoringMesinEog) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteVerifikasiMonitoringMesinEogLog
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
