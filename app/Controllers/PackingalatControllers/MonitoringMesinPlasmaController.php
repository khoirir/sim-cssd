<?php

namespace App\Controllers\PackingalatControllers;

use App\Controllers\BaseController;
use App\Models\DataModels\DepartemenModel;
use App\Models\DataModels\PegawaiModel;
use App\Models\DekontaminasiModels\PenerimaanAlatKotorDetailModel;
use App\Models\PackingAlatModels\MonitoringMesinPlasmaDetailModel;
use App\Models\PackingAlatModels\MonitoringMesinPlasmaModel;
use App\Models\PackingAlatModels\MonitoringMesinPlasmaOperatorModel;
use App\Models\PackingAlatModels\MonitoringMesinPlasmaVerifikasiModel;

class MonitoringMesinPlasmaController extends BaseController
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
            'title' => 'Monitoring Mesin Plasma',
            'header' => 'Monitoring Mesin Plasma (H2O2)',
            'tglAwal' => date('Y-m-01'),
            'tglSekarang' => date('Y-m-d'),
        ];
        return view('packingalat/monitoringmesinplasma/index_monitoringmesinplasma_view', $data);
    }
    
    public function tambahMonitoringMesinPlasma()
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
            'title' => 'Tambah Monitoring Mesin Plasma',
            'header' => 'Monitoring Mesin Plasma (H2O2)',
            'tglSekarang' => date('Y-m-d'),
            'jamSekarang' => $jamSekarang,
            'shift' => $shift,
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
        ];
        return view('packingalat/monitoringmesinplasma/tambah_monitoringmesinplasma_view', $data);
    }

    public function dataMonitoringMesinPlasmaBerdasarkanTanggal()
    {
        if ($this->request->isAJAX()) {
            $tglAwal = $this->request->getPost('tglAwal') . " 00:00:00";
            $tglAkhir = $this->request->getPost('tglAkhir') . " 23:59:59";
            $start = $this->request->getPost('start');
            $limit = $this->request->getPost('length');

            $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
            $monitoringMesinPlasmaVerifikasiModel = model(MonitoringMesinPlasmaVerifikasiModel::class);

            $dataMonitoringMesinPlasmaBerdasarkanTanggaldanLimit = $monitoringMesinPlasmaModel
                ->dataMonitoringMesinPlasmaBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
                ->getResultArray();

            $jumlahDataMonitoringMesinPlasmaBerdasarkanTanggal = $monitoringMesinPlasmaModel
                ->dataMonitoringMesinPlasmaBerdasarkanTanggal($tglAwal, $tglAkhir)
                ->getNumRows();

            $dataResult = [];
            foreach ($dataMonitoringMesinPlasmaBerdasarkanTanggaldanLimit as $data) {
                $tombolDetail = "<button data-popup='tooltip' title='Detail Monitoring Mesin Plasma' class=\"btn btn-info btn-sm border-0\" onclick=\"detailMonitoringMesinPlasma(this,'" . $data['id'] . "')\"><i class=\"fas fa-file-lines\"></i></button>";

                $a = $data['proses_ulang'] ? "" : "<a href=\"" . base_url('monitoring-mesin-plasma/edit/' . $data['id']) . "\" class=\"btn btn-warning btn-sm border-0\" data-popup='tooltip' title='Edit Data'><i class=\"fas fa-edit\"></i></a>";
                $form = "<form action=\"" . base_url('monitoring-mesin-plasma/hapus/' . $data['id']) . "\" method=\"POST\" class=\"formHapus\"><input type=\"hidden\" name=\"" . csrf_token() . "\" value=\"" . csrf_hash() . "\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\"><button type=\"submit\" class=\"ml-1 btn btn-danger btn-sm border-0\" data-popup='tooltip' title='Hapus Data'><i class=\"far fa-trash-alt\"></i></button></form>";
                $tombolAksi = "<div class=\"d-flex justify-content-center\">" . $a . $form . "</div>";
                $aksi = $tombolAksi;

                $dataVerifikasi = $monitoringMesinPlasmaVerifikasiModel
                    ->dataVerifikasiMonitoringMesinPlasmaBerdasarkanIdMaster($data['id'])
                    ->getFirstRow('array');
                $prosesDari = "";
                $keterangan = "<i class=\"fa-solid fa-minus\"></i>";
                if ($data['proses_ulang']) {
                    $prosesDari = "<span class=\"badge badge-info\">Proses dari " . generateNoReferensi($data['created_proses_ulang'], $data['proses_ulang']) . "</span>";
                    $keterangan = "";
                }
                $verifikasi = "<a href=\"" . base_url('monitoring-mesin-plasma/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Plasma\" class=\"btn btn-primary btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";
                $keterangan .= $prosesDari;

                if ($dataVerifikasi) {
                    $hasilVerifikasi = $dataVerifikasi['hasil_verifikasi'];
                    $keterangan = $prosesDari . " <span class=\"badge badge-success\">" . $hasilVerifikasi . "</span>";
                    $verifikasi = "<a href=\"" . base_url('monitoring-mesin-plasma/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Plasma\" class=\"btn btn-success btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";
                    $aksi = "<i class=\"fa-solid fa-minus\"></i>";

                    if (strtolower($hasilVerifikasi) === 'tidak steril') {
                        $keterangan = $prosesDari . " <span class=\"badge badge-danger\">" . $hasilVerifikasi . "</span>";
                        $verifikasi = "<a href=\"" . base_url('monitoring-mesin-plasma/verifikasi/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Verifikasi Monitoring Mesin Plasma\" class=\"btn btn-danger btn-sm border-0\"><i class=\"fas fa-file-pen\"></i></a>";

                        $aksi = "<a href=\"" . base_url('monitoring-mesin-plasma/proses-ulang/' . $data['id']) . "\" data-popup=\"tooltip\" title=\"Proses Ulang\" class=\"btn btn-primary btn-sm border-0 ml-1\"><i class=\"fa-solid fa-repeat\"></i></a>";

                        $dataProsesUlang = $monitoringMesinPlasmaModel
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
                'recordsTotal' => $jumlahDataMonitoringMesinPlasmaBerdasarkanTanggal,
                'recordsFiltered' => $jumlahDataMonitoringMesinPlasmaBerdasarkanTanggal,
                'data' => $dataResult
            ];
            return $this->response->setJSON($json);
        }
    }

    public function detailMonitoringMesinPlasma($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
            $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);
            $monitoringMesinPlasmaOperatorModel = model(MonitoringMesinPlasmaOperatorModel::class);
            $monitoringMesinPlasmaVerifikasiModel = model(MonitoringMesinPlasmaVerifikasiModel::class);

            $dataMonitoringMesinPlasmaBerdasarkanId = $monitoringMesinPlasmaModel->find($id);
            if (!$dataMonitoringMesinPlasmaBerdasarkanId) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => 'Data tidak ditemukan'
                    ]
                );
            }

            $operatorMonitoringMesinPlasmaBerdasarkanIdMaster = $monitoringMesinPlasmaOperatorModel
                ->operatorMonitoringMesinPlasmaBerdasarkanIdMaster($id)
                ->getResultArray();

            $dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster = $monitoringMesinPlasmaDetailModel
                ->dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster($id);

            $dataVerifikasiMonitoringMesinPlasmaBerdasarkanIdMaster = $monitoringMesinPlasmaVerifikasiModel
                ->dataVerifikasiMonitoringMesinPlasmaBerdasarkanIdMaster($id)
                ->getFirstRow('array');

            $data = [
                'dataMesinPlasma' => $dataMonitoringMesinPlasmaBerdasarkanId,
                'operator' => $operatorMonitoringMesinPlasmaBerdasarkanIdMaster,
                'detailAlat' => $dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster,
                'dataVerifikasi' => $dataVerifikasiMonitoringMesinPlasmaBerdasarkanIdMaster,
            ];

            $json = [
                'sukses' => true,
                'data' => view('packingalat/monitoringmesinplasma/modal_detail_monitoring_mesin_plasma', $data)
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

    public function simpanMonitoringMesinPlasma()
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

            $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
            if ($prosesUlang) {
                $dataMesinPlasma = $monitoringMesinPlasmaModel->find($prosesUlang);
                if (!$dataMesinPlasma) {
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

                $dataProsesUlang = $monitoringMesinPlasmaModel
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

                $data['created_proses_ulang'] = $dataMesinPlasma['created_at'];
                $data['proses_ulang'] = $prosesUlang;
            }

            $insertMonitoringMesinPlasma = $monitoringMesinPlasmaModel->insert($data);
            $insertMonitoringMesinPlasmaLog = ($insertMonitoringMesinPlasma) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinPlasmaLog .= " monitoring mesin plasma dengan id " . $insertMonitoringMesinPlasma;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinPlasmaLog
            ]);
            if (!$insertMonitoringMesinPlasma) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringMesinPlasmaLog
                        ]
                    ]
                );
            }

            $dataInsertMonitoringMesinPlasmaOperator = [];
            foreach ($operator as $op) {
                $dataDetail = [
                    'id_monitoring_mesin_plasma' => $insertMonitoringMesinPlasma,
                    'id_operator' => $op
                ];
                array_push($dataInsertMonitoringMesinPlasmaOperator, $dataDetail);
            }
            $monitoringMesinPlasmaOperatorModel = model(MonitoringMesinPlasmaOperatorModel::class);
            $insertMonitoringMesinPlasmaOperator = $monitoringMesinPlasmaOperatorModel->insertMultiple($dataInsertMonitoringMesinPlasmaOperator);
            $insertMonitoringMesinPlasmaOperatorLog = ($insertMonitoringMesinPlasmaOperator) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinPlasmaOperatorLog .= " operator monitoring mesin plasma dengan id master " . $insertMonitoringMesinPlasma;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinPlasmaOperatorLog
            ]);
            if (!$insertMonitoringMesinPlasmaOperator) {
                $monitoringMesinPlasmaModel->delete($insertMonitoringMesinPlasma);
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Disimpan',
                            'errorSimpan' => $insertMonitoringMesinPlasmaOperatorLog
                        ]
                    ]
                );
            }

            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);

            foreach ($dataAlat as $detail) {
                $idDetailPenerimaanAlatKotor = $detail['idDetailAlatKotor'];
                $jumlah = $detail['jumlah'];
                $alat = $detail['alat'];

                if ($idDetailPenerimaanAlatKotor && !$prosesUlang) {
                    $dataAlatKotorBerdasarkanId = $penerimaanAlatKotorDetailModel->find($idDetailPenerimaanAlatKotor);

                    if ($jumlah > $dataAlatKotorBerdasarkanId['sisa']) {
                        $monitoringMesinPlasmaModel->delete($insertMonitoringMesinPlasma);
                        $monitoringMesinPlasmaOperatorModel->where('id_monitoring_mesin_plasma', $insertMonitoringMesinPlasma)->delete();

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
                    'id_monitoring_mesin_plasma' => $insertMonitoringMesinPlasma,
                    'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                    'id_alat' => $detail['idAlat'],
                    'id_ruangan' => $detail['idRuangan'],
                    'jumlah' => $jumlah,
                ];

                $insertMonitoringMesinPlasmaDetail = $monitoringMesinPlasmaDetailModel->insert($dataDetail, false);
                if (!$insertMonitoringMesinPlasmaDetail) {
                    $monitoringMesinPlasmaModel->delete($insertMonitoringMesinPlasma);
                    $monitoringMesinPlasmaOperatorModel->where('id_monitoring_mesin_plasma', $insertMonitoringMesinPlasma)->delete();
                    $monitoringMesinPlasmaDetailModel->where('id_monitoring_mesin_plasma', $insertMonitoringMesinPlasma)->delete();
                    $insertMonitoringMesinPlasmaDetailLog = "Gagal insert detail monitoring mesin plasma dengan id master " . $insertMonitoringMesinPlasma;
                    $this->logModel->insert([
                        "id_user" => session()->get('id_user'),
                        "log" => $insertMonitoringMesinPlasmaDetailLog
                    ]);

                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertMonitoringMesinPlasmaDetailLog
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

            $insertMonitoringMesinPlasmaDetailLog = "Insert detail monitoring mesin plasma dengan id master " . $insertMonitoringMesinPlasma;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinPlasmaDetailLog
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

    public function editMonitoringMesinPlasma($id=null)
    {
        $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
        $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);
        $monitoringMesinPlasmaOperatorModel = model(MonitoringMesinPlasmaOperatorModel::class);

        $dataMonitoringMesinPlasmaBerdasarkanId = $monitoringMesinPlasmaModel->find($id);
        if (!$dataMonitoringMesinPlasmaBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($dataMonitoringMesinPlasmaBerdasarkanId['proses_ulang']) {
            return redirect()->to('/monitoring-mesin-plasma');
        }

        $dataOperator = $monitoringMesinPlasmaOperatorModel
            ->operatorMonitoringMesinPlasmaBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataDetail = $monitoringMesinPlasmaDetailModel
            ->dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster($id);

        $pegawaiModel = model(PegawaiModel::class);
        $departemenModel = model(DepartemenModel::class);

        $data = [
            'title' => 'Edit Monitoring Mesin Plasma',
            'header' => 'Monitoring Mesin Plasma (H2O2)',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'dataMesinPlasma' => $dataMonitoringMesinPlasmaBerdasarkanId,
            'dataOperator' => $dataOperator,
            'dataDetail' => $dataDetail
        ];
        return view('packingalat/monitoringmesinplasma/edit_monitoringmesinplasma_view', $data);
    }

    public function hapusDetailMonitoringMesinPlasma($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);

            $dataDetail = $monitoringMesinPlasmaDetailModel->find($id);
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

            $deleteDetail = $monitoringMesinPlasmaDetailModel->delete($id, false);
            $deleteDetailLog = ($deleteDetail) ? "Delete" : "Gagal delete";
            $deleteDetailLog .= " detail monitoring mesin plasma dengan id " . $id;
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
            if ($idAlatKotor) {
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

    public function batalHapusDetailMonitoringMesinPlasma($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);

            $updateDetail = $monitoringMesinPlasmaDetailModel->update($id, ['deleted_at' => null], false);
            $updateDetailLog = ($updateDetail) ? "Update" : "Gagal update";
            $updateDetailLog .= " 'Batal Hapus' detail monitoring mesin plasma dengan id " . $id;
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
            $dataDetail = $monitoringMesinPlasmaDetailModel->find($id);
            $idAlatKotor = $dataDetail['id_detail_penerimaan_alat_kotor'];
            $jumlah = $dataDetail['jumlah'];
            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
            if ($idAlatKotor) {
                $dataAlatKotor = $penerimaanAlatKotorDetailModel->find($idAlatKotor);

                $updateData = [
                    'sisa' => (int)$dataAlatKotor['sisa'] - (int)$jumlah,
                    'status_proses' => 'Diproses'
                ];
                $penerimaanAlatKotorDetailModel->update($idAlatKotor, $updateData, false);
            }

            $dataReturn = $monitoringMesinPlasmaDetailModel
                ->dataDetailMonitoringMesinPlasmaBerdasarkanId($id)
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

    public function updateMonitoringMesinPlasma($id = null)
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

            $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);

            $updateMonitoringMesinPlasma = $monitoringMesinPlasmaModel->update($id, $data, false);
            $updateMonitoringMesinPlasmaLog = ($updateMonitoringMesinPlasma) ? "Update" : "Gagal update";
            $updateMonitoringMesinPlasmaLog .= " monitoring mesin plasma dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateMonitoringMesinPlasmaLog
            ]);
            if (!$updateMonitoringMesinPlasma) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateMonitoringMesinPlasmaLog
                        ]
                    ]
                );
            }

            $monitoringMesinPlasmaOperatorModel = model(MonitoringMesinPlasmaOperatorModel::class);
            $deleteOperator = $monitoringMesinPlasmaOperatorModel
                ->where('id_monitoring_mesin_plasma', $id)
                ->delete();

            $dataInsertMonitoringMesinPlasmaOperator = [];
            foreach ($operator as $op) {
                $dataDetail = [
                    'id_monitoring_mesin_plasma' => $id,
                    'id_operator' => $op
                ];
                array_push($dataInsertMonitoringMesinPlasmaOperator, $dataDetail);
            }
            $insertMonitoringMesinPlasmaOperator = $monitoringMesinPlasmaOperatorModel
                ->insertMultiple($dataInsertMonitoringMesinPlasmaOperator);
            $insertMonitoringMesinPlasmaOperatorLog = ($insertMonitoringMesinPlasmaOperator) ? "Insert" : "Gagal insert";
            $insertMonitoringMesinPlasmaOperatorLog .= " operator monitoring mesin plasma dengan id master " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $insertMonitoringMesinPlasmaOperatorLog
            ]);
            if (!$insertMonitoringMesinPlasmaOperator) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $insertMonitoringMesinPlasmaOperatorLog
                        ]
                    ]
                );
            }

            if ($dataAlat) {
                $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
                $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);

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
                        'id_monitoring_mesin_plasma' => $id,
                        'id_detail_penerimaan_alat_kotor' => $idDetailPenerimaanAlatKotor,
                        'id_alat' => $detail['idAlat'],
                        'id_ruangan' => $detail['idRuangan'],
                        'jumlah' => $jumlah,
                    ];

                    $insertMonitoringMesinPlasmaDetail = $monitoringMesinPlasmaDetailModel
                        ->insert($dataDetail, false);

                    if (!$insertMonitoringMesinPlasmaDetail) {
                        $insertMonitoringMesinPlasmaDetailLog = "Gagal insert detail baru monitoring mesin plasma dengan id master " . $id;
                        $this->logModel->insert([
                            "id_user" => session()->get('id_user'),
                            "log" => $insertMonitoringMesinPlasmaDetailLog
                        ]);

                        return $this->response->setJSON(
                            [
                                'sukses' => false,
                                'pesan' => [
                                    'judul' => 'Gagal',
                                    'teks' => 'Data Tidak Diedit',
                                    'errorSimpan' => $insertMonitoringMesinPlasmaDetailLog
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

                $insertMonitoringMesinPlasmaDetailLog = "Insert detail monitoring mesin plasma dengan id master " . $id;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertMonitoringMesinPlasmaDetailLog
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

    public function prosesUlangMonitoringMesinPlasma($id = null)
    {
        $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
        $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);

        $dataMonitoringMesinPlasmaBerdasarkanId = $monitoringMesinPlasmaModel->find($id);
        if (!$dataMonitoringMesinPlasmaBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $dataDetail = $monitoringMesinPlasmaDetailModel->dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster($id);

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
            'title' => 'Proses Ulang Monitoring Mesin Plasma',
            'header' => 'Monitoring Mesin Plasma (H2O2)',
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'listDepartemen' => $departemenModel->getListDepartemen(),
            'dataMesinPlasma' => $dataMonitoringMesinPlasmaBerdasarkanId,
            'shift' => $shift,
            'tanggalSekarang' => date('Y-m-d'),
            'jamSekarang' => $jamSekarang,
            'dataDetail' => $dataDetail
        ];

        return view('packingalat/monitoringmesinplasma/proses_ulang_monitoringmesinplasma_view', $data);
    }

    public function hapusMonitoringMesinPlasma($id)
    {
        if ($this->request->isAJAX()) {
            $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
            $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);

            $dataMonitoringMesinPlasmaBerdasarkanId = $monitoringMesinPlasmaModel->find($id);
            if (!$dataMonitoringMesinPlasmaBerdasarkanId) {
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

            $deleteMonitoringMesinPlasma = $monitoringMesinPlasmaModel->delete($id, false);
            $deleteMonitoringMesinPlasmaLog = ($deleteMonitoringMesinPlasma) ? "Delete" : "Gagal delete";
            $deleteMonitoringMesinPlasmaLog .= " monitoring mesin plasma dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteMonitoringMesinPlasmaLog
            ]);
            if (!$deleteMonitoringMesinPlasma) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteMonitoringMesinPlasmaLog
                        ]
                    ]
                );
            }
            $dataDetailAlat = $monitoringMesinPlasmaDetailModel->where('id_monitoring_mesin_plasma', $id)->findAll();

            $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);

            foreach ($dataDetailAlat as $detail) {
                $idAlatKotor = $detail['id_detail_penerimaan_alat_kotor'];
                $jumlah = $detail['jumlah'];
                if ($idAlatKotor && !$dataMonitoringMesinPlasmaBerdasarkanId['proses_ulang']) {
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

    public function verifikasiMonitoringMesinPlasma($id)
    {
        $monitoringMesinPlasmaModel = model(MonitoringMesinPlasmaModel::class);
        $monitoringMesinPlasmaDetailModel = model(MonitoringMesinPlasmaDetailModel::class);
        $monitoringMesinPlasmaOperatorModel = model(MonitoringMesinPlasmaOperatorModel::class);
        $monitoringMesinPlasmaVerifikasiModel = model(MonitoringMesinPlasmaVerifikasiModel::class);

        $dataMonitoringMesinPlasmaBerdasarkanId = $monitoringMesinPlasmaModel->find($id);
        if (!$dataMonitoringMesinPlasmaBerdasarkanId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $operatorMonitoringMesinPlasmaBerdasarkanIdMaster = $monitoringMesinPlasmaOperatorModel
            ->operatorMonitoringMesinPlasmaBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster = $monitoringMesinPlasmaDetailModel
            ->dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster($id)
            ->getResultArray();

        $dataVerifikasiMonitoringMesinPlasmaBerdasarkanIdMaster = $monitoringMesinPlasmaVerifikasiModel
            ->where('id_monitoring_mesin_plasma', $id)
            ->first();

        $dataProsesUlang = $monitoringMesinPlasmaModel
            ->where('proses_ulang', $id)
            ->where('deleted_at', null)
            ->get()
            ->getNumRows();

        $idAlatKotor = array_column($dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster, 'id_detail_penerimaan_alat_kotor');
        $penerimaanAlatKotorDetailModel = model(PenerimaanAlatKotorDetailModel::class);
        $dataDistribusi = $penerimaanAlatKotorDetailModel
            ->dataAlatKotorDistribusiBerdasarkanId($idAlatKotor)
            ->getNumRows();

        $pegawaiModel = model(PegawaiModel::class);
            
        $data = [
            'title' => 'Verifikasi Monitoring Mesin Plasma',
            'header' => 'Monitoring Mesin Plasma (H2O2)',
            'dataMesinPlasma' => $dataMonitoringMesinPlasmaBerdasarkanId,
            'operator' => $operatorMonitoringMesinPlasmaBerdasarkanIdMaster,
            'detailAlat' => $dataDetailMonitoringMesinPlasmaBerdasarkanIdMaster,
            'listPegawaiCSSD' => $pegawaiModel->getListPegawaiCSSD(),
            'dataVerifikasi' => $dataVerifikasiMonitoringMesinPlasmaBerdasarkanIdMaster,
            'prosesUlang' => $dataProsesUlang,
            'dataDistribusi' => $dataDistribusi
        ];

        return view('packingalat/monitoringmesinplasma/verifikasi_monitoringmesinplasma', $data);
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

            $monitoringMesinPlasmaVerifikasiModel = model(MonitoringMesinPlasmaVerifikasiModel::class);
            $dataVerifikasiBerdasarkanId = $monitoringMesinPlasmaVerifikasiModel->find($id);
            if (!$dataVerifikasiBerdasarkanId) {
                $dataId = [
                    'id' => generateUUID(),
                    'id_monitoring_mesin_plasma' => $id
                ];
                $dataInsert = array_merge($dataId, $data);
                $insertVerifikasiMonitoringMesinPlasma = $monitoringMesinPlasmaVerifikasiModel->insert($dataInsert);
                $insertVerifikasiMonitoringMesinPlasmaLog = ($insertVerifikasiMonitoringMesinPlasma) ? "Insert" : "Gagal insert";
                $insertVerifikasiMonitoringMesinPlasmaLog .= " verifikasi monitoring mesin plasma dengan id " . $insertVerifikasiMonitoringMesinPlasma;
                $this->logModel->insert([
                    "id_user" => session()->get('id_user'),
                    "log" => $insertVerifikasiMonitoringMesinPlasmaLog
                ]);

                if (!$insertVerifikasiMonitoringMesinPlasma) {
                    return $this->response->setJSON(
                        [
                            'sukses' => false,
                            'pesan' => [
                                'judul' => 'Gagal',
                                'teks' => 'Data Tidak Disimpan',
                                'errorSimpan' => $insertVerifikasiMonitoringMesinPlasmaLog
                            ]
                        ]
                    );
                }
                $dataPrint->move('public/img/monitoringmesinplasma', $data['data_print']);
                $indikatorEksternal->move('public/img/monitoringmesinplasma', $data['indikator_eksternal']);
                $indikatorInternal->move('public/img/monitoringmesinplasma', $data['indikator_internal']);
                $indikatorBiologi->move('public/img/monitoringmesinplasma', $data['indikator_biologi']);

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
                $dataPrint->move('public/img/monitoringmesinplasma', $print);
                unlink('public/img/monitoringmesinplasma/' . $dataVerifikasiBerdasarkanId['data_print']);
            }

            if ($indikatorEksternal->getError() == 4) {
                $eksternal = $dataVerifikasiBerdasarkanId['indikator_eksternal'];
            } else {
                $eksternal = $indikatorEksternal->getRandomName();
                $indikatorEksternal->move('public/img/monitoringmesinplasma', $eksternal);
                unlink('public/img/monitoringmesinplasma/' . $dataVerifikasiBerdasarkanId['indikator_eksternal']);
            }

            if ($indikatorInternal->getError() == 4) {
                $internal = $dataVerifikasiBerdasarkanId['indikator_internal'];
            } else {
                $internal = $indikatorInternal->getRandomName();
                $indikatorInternal->move('public/img/monitoringmesinplasma', $internal);
                unlink('public/img/monitoringmesinplasma/' . $dataVerifikasiBerdasarkanId['indikator_internal']);
            }

            if ($indikatorBiologi->getError() == 4) {
                $biologi = $dataVerifikasiBerdasarkanId['indikator_biologi'];
            } else {
                $biologi = $indikatorBiologi->getRandomName();
                $indikatorBiologi->move('public/img/monitoringmesinplasma', $biologi);
                unlink('public/img/monitoringmesinplasma/' . $dataVerifikasiBerdasarkanId['indikator_biologi']);
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

            $updateVerifikasiMonitoringMesinPlasma = $monitoringMesinPlasmaVerifikasiModel->update($id, $dataUpdate);
            $updateVerifikasiMonitoringMesinPlasmaLog = ($updateVerifikasiMonitoringMesinPlasma) ? "Update" : "Gagal update";
            $updateVerifikasiMonitoringMesinPlasmaLog .= " verifikasi monitoring mesin plasma dengan id " . $updateVerifikasiMonitoringMesinPlasma;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $updateVerifikasiMonitoringMesinPlasmaLog
            ]);

            if (!$updateVerifikasiMonitoringMesinPlasma) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Diedit',
                            'errorSimpan' => $updateVerifikasiMonitoringMesinPlasmaLog
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
            $monitoringMesinPlasmaVerifikasiModel = model(MonitoringMesinPlasmaVerifikasiModel::class);
            $dataVerifikasiBerdasarkanId = $monitoringMesinPlasmaVerifikasiModel->find($id);
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

            $deleteVerifikasiMonitoringMesinPlasma = $monitoringMesinPlasmaVerifikasiModel->delete($id, false);
            $deleteVerifikasiMonitoringMesinPlasmaLog = ($deleteVerifikasiMonitoringMesinPlasma) ? "Delete" : "Gagal delete";
            $deleteVerifikasiMonitoringMesinPlasmaLog .= " verifikasi monitoring mesin plasma dengan id " . $id;
            $this->logModel->insert([
                "id_user" => session()->get('id_user'),
                "log" => $deleteVerifikasiMonitoringMesinPlasmaLog
            ]);
            if (!$deleteVerifikasiMonitoringMesinPlasma) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'judul' => 'Gagal',
                            'teks' => 'Data Tidak Dihapus',
                            'errorHapus' => $deleteVerifikasiMonitoringMesinPlasmaLog
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
