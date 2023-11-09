<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinEogOperatorModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_eog_operator';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_monitoring_mesin_eog', 'id_operator'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_monitoring_mesin_eog_operator');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function operatorMonitoringMesinEogBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_mesin_eog_operator');
        $builder->select('
            cssd_monitoring_mesin_eog_operator.id,
            cssd_monitoring_mesin_eog_operator.id_operator,
            pegawai.nama
        ');
        $builder->join('pegawai', 'cssd_monitoring_mesin_eog_operator.id_operator = pegawai.nik');
        $builder->where('cssd_monitoring_mesin_eog_operator.id_monitoring_mesin_eog', $idMaster);
        $builder->where('cssd_monitoring_mesin_eog_operator.deleted_at', null);
        $builder->orderBy('pegawai.nama');
        $query = $builder->get();

        return $query;
    }
}
