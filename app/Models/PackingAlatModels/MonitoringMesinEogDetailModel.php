<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinEogDetailModel extends Model
{
    protected $table = 'cssd_monitoring_mesin_eog_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_monitoring_mesin_eog', 'id_detail_penerimaan_alat_kotor', 'id_alat', 'id_ruangan', 'jumlah', 'deleted_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_monitoring_mesin_eog_detail');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function dataDetailMonitoringMesinEogBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_mesin_eog_detail');
        $builder->select('
            cssd_monitoring_mesin_eog_detail.id,
            cssd_monitoring_mesin_eog_detail.id_detail_penerimaan_alat_kotor,
            cssd_monitoring_mesin_eog_detail.jumlah,
            cssd_monitoring_mesin_eog_detail.id_alat,
            cssd_set_alat.nama_set_alat,
            cssd_monitoring_mesin_eog_detail.id_ruangan,
            departemen.nama AS ruangan
        ');
        $builder->join('cssd_set_alat', 'cssd_monitoring_mesin_eog_detail.id_alat = cssd_set_alat.id');
        $builder->join('departemen', 'cssd_monitoring_mesin_eog_detail.id_ruangan = departemen.dep_id');
        $builder->where('cssd_monitoring_mesin_eog_detail.id_monitoring_mesin_eog', $idMaster);
        $builder->where('cssd_monitoring_mesin_eog_detail.deleted_at', null);
        $builder->orderBy('departemen.nama');
        $builder->orderBy('cssd_set_alat.nama_set_alat');
        $query = $builder->get();

        return $query;
    }

    public function dataDetailMonitoringMesinEogBerdasarkanId($id)
    {
        $builder = $this->table('cssd_monitoring_mesin_eog_detail');
        $builder->select('
            cssd_monitoring_mesin_eog_detail.id,
            cssd_monitoring_mesin_eog_detail.id_detail_penerimaan_alat_kotor,
            cssd_monitoring_mesin_eog_detail.jumlah,
            cssd_monitoring_mesin_eog_detail.id_alat,
            cssd_set_alat.nama_set_alat,
            cssd_monitoring_mesin_eog_detail.id_ruangan,
            departemen.nama AS ruangan
        ');
        $builder->join('cssd_set_alat', 'cssd_monitoring_mesin_eog_detail.id_alat = cssd_set_alat.id');
        $builder->join('departemen', 'cssd_monitoring_mesin_eog_detail.id_ruangan = departemen.dep_id');
        $builder->where('cssd_monitoring_mesin_eog_detail.id', $id);
        $builder->where('cssd_monitoring_mesin_eog_detail.deleted_at', null);
        $query = $builder->get();

        return $query;
    }
}
