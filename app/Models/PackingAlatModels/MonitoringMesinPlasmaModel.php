<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinPlasmaModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_plasma';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_monitoring', 'shift', 'siklus', 'proses_ulang', 'created_proses_ulang'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataMonitoringMesinPlasmaBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
    {
        $builder = $this->table('cssd_monitoring_mesin_plasma');
        $builder->select('
            id,
            tanggal_monitoring,
            siklus,
            proses_ulang,
            created_proses_ulang,
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

    public function dataMonitoringMesinPlasmaBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_monitoring_mesin_plasma');
        $builder->select('
            id,
            tanggal_monitoring,
            siklus,
            created_at
        ');
        $builder->where('tanggal_monitoring >=', $tglAwal);
        $builder->where('tanggal_monitoring <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('tanggal_monitoring');
        $query = $builder->get();

        return $query;
    }
}
