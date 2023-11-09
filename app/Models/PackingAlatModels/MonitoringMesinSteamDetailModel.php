<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinSteamDetailModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_steam_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_monitoring_mesin_steam', 'id_detail_penerimaan_alat_kotor', 'id_alat', 'id_ruangan', 'jumlah', 'sisa_distribusi', 'deleted_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam_detail');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function dataDetailMonitoringMesinSteamBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam_detail');
        $builder->select('
            cssd_monitoring_mesin_steam_detail.id,
            cssd_monitoring_mesin_steam_detail.id_detail_penerimaan_alat_kotor,
            cssd_monitoring_mesin_steam_detail.jumlah,
            cssd_monitoring_mesin_steam_detail.id_alat,
            cssd_set_alat.nama_set_alat,
            cssd_monitoring_mesin_steam_detail.id_ruangan,
            departemen.nama AS ruangan
        ');
        $builder->join('cssd_set_alat', 'cssd_monitoring_mesin_steam_detail.id_alat = cssd_set_alat.id');
        $builder->join('departemen', 'cssd_monitoring_mesin_steam_detail.id_ruangan = departemen.dep_id');
        $builder->where('cssd_monitoring_mesin_steam_detail.id_monitoring_mesin_steam', $idMaster);
        $builder->where('cssd_monitoring_mesin_steam_detail.deleted_at', null);
        $builder->orderBy('departemen.nama');
        $builder->orderBy('cssd_set_alat.nama_set_alat');
        $query = $builder->get();

        return $query;
    }

    public function dataDetailMonitoringMesinSteamBerdasarkanId($id)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam_detail');
        $builder->select('
            cssd_monitoring_mesin_steam_detail.id,
            cssd_monitoring_mesin_steam_detail.id_detail_penerimaan_alat_kotor,
            cssd_monitoring_mesin_steam_detail.jumlah,
            cssd_monitoring_mesin_steam_detail.id_alat,
            cssd_set_alat.nama_set_alat,
            cssd_monitoring_mesin_steam_detail.id_ruangan,
            departemen.nama AS ruangan
        ');
        $builder->join('cssd_set_alat', 'cssd_monitoring_mesin_steam_detail.id_alat = cssd_set_alat.id');
        $builder->join('departemen', 'cssd_monitoring_mesin_steam_detail.id_ruangan = departemen.dep_id');
        $builder->where('cssd_monitoring_mesin_steam_detail.id', $id);
        $builder->where('cssd_monitoring_mesin_steam_detail.deleted_at', null);
        $query = $builder->get();

        return $query;
    }
}
