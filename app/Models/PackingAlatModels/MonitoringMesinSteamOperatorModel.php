<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinSteamOperatorModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_steam_operator';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_monitoring_mesin_steam', 'id_operator'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam_operator');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function operatorMonitoringMesinSteamBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_mesin_steam_operator');
        $builder->select('
            cssd_monitoring_mesin_steam_operator.id,
            cssd_monitoring_mesin_steam_operator.id_operator,
            pegawai.nama
        ');
        $builder->join('pegawai', 'cssd_monitoring_mesin_steam_operator.id_operator = pegawai.nik');
        $builder->where('cssd_monitoring_mesin_steam_operator.id_monitoring_mesin_steam', $idMaster);
        $builder->where('cssd_monitoring_mesin_steam_operator.deleted_at', null);
        $builder->orderBy('pegawai.nama');
        $query = $builder->get();

        return $query;
    }
}
