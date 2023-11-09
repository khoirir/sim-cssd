<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinEogVerifikasiModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_eog_verifikasi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['id_monitoring_mesin_eog', 'waktu_keluar_alat', 'data_print', 'indikator_eksternal', 'indikator_internal', 'indikator_biologi', 'id_petugas_verifikator', 'hasil_verifikasi'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataVerifikasiMonitoringMesinEogBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_mesin_eog_verifikasi');
        $builder->select('
            cssd_monitoring_mesin_eog_verifikasi.id,
            cssd_monitoring_mesin_eog_verifikasi.id_monitoring_mesin_eog,
            cssd_monitoring_mesin_eog_verifikasi.waktu_keluar_alat,
            cssd_monitoring_mesin_eog_verifikasi.data_print,
            cssd_monitoring_mesin_eog_verifikasi.indikator_eksternal,
            cssd_monitoring_mesin_eog_verifikasi.indikator_internal,
            cssd_monitoring_mesin_eog_verifikasi.indikator_biologi,
            pegawai.nama AS verifikator,
            cssd_monitoring_mesin_eog_verifikasi.hasil_verifikasi
        ');
        $builder->join('pegawai', 'cssd_monitoring_mesin_eog_verifikasi.id_petugas_verifikator = pegawai.nik');
        $builder->where('cssd_monitoring_mesin_eog_verifikasi.id_monitoring_mesin_eog', $idMaster);
        $builder->where('cssd_monitoring_mesin_eog_verifikasi.deleted_at', null);
        $query = $builder->get();

        return $query;
    }
}
