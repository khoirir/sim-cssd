<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinSteamModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_steam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_monitoring', 'shift', 'siklus', 'mesin', 'proses_ulang', 'created_proses_ulang'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataMonitoringMesinSteamBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam');
        $builder->select('
            id,
            tanggal_monitoring,
            siklus,
            proses_ulang,
            created_proses_ulang,
            mesin,
            created_at
        ');
        $builder->where('tanggal_monitoring >=', $tglAwal);
        $builder->where('tanggal_monitoring <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('tanggal_monitoring');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }

    public function dataMonitoringMesinSteamBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam');
        $builder->select('
            id,
            tanggal_monitoring,
            siklus,
            mesin,
            created_at
        ');
        $builder->where('tanggal_monitoring >=', $tglAwal);
        $builder->where('tanggal_monitoring <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('tanggal_monitoring');
        $query = $builder->get();

        return $query;
    }

    // public function dataVerifikasiAlatSteril($idDetailKotor)
    // {
    //     $this->select('
    //         cssd_monitoring_mesin_steam.id,
    //         cssd_monitoring_mesin_steam.tanggal_monitoring,
    //         cssd_monitoring_mesin_steam_verifikasi.hasil_verifikasi
    //     ');
    //     $this->join('cssd_monitoring_mesin_steam_detail', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_detail.id_monitoring_mesin_steam');
    //     $this->join('cssd_monitoring_mesin_steam_verifikasi', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_verifikasi.id_monitoring_mesin_steam');
    //     $this->like('cssd_monitoring_mesin_steam_verifikasi.hasil_verifikasi', 'Passed', 'before');
    //     // $this->where('cssd_monitoring_mesin_steam_detail.id_detail_penerimaan_alat_kotor', $idDetailKotor);
    //     $this->where('cssd_monitoring_mesin_steam.deleted_at', null);
    //     $this->where('cssd_monitoring_mesin_steam_detail.deleted_at', null);
    //     $this->where('cssd_monitoring_mesin_steam_verifikasi.deleted_at', null);
    //     $query = $this->get();

    //     return $query;
    // }
}
