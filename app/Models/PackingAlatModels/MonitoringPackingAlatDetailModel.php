<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringPackingAlatDetailModel extends Model
{
    protected $table = 'cssd_monitoring_packing_alat_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_master', 'id_alat', 'id_petugas', 'bersih', 'tajam', 'layak', 'indikator'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_monitoring_packing_alat_detail');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function dataMonitoringPackingAlatDetailBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_packing_alat_detail');
        $builder->select('
            cssd_monitoring_packing_alat_detail.id,
            cssd_monitoring_packing_alat_detail.id_alat,
            cssd_set_alat.nama_set_alat,
            cssd_monitoring_packing_alat_detail.bersih, 
            cssd_monitoring_packing_alat_detail.tajam, 
            cssd_monitoring_packing_alat_detail.layak, 
            cssd_monitoring_packing_alat_detail.indikator, 
            cssd_monitoring_packing_alat_detail.id_petugas, 
            pegawai.nama');
        $builder->join('pegawai', 'cssd_monitoring_packing_alat_detail.id_petugas = pegawai.nik');
        $builder->join('cssd_set_alat', 'cssd_monitoring_packing_alat_detail.id_alat = cssd_set_alat.id');
        $builder->where('cssd_monitoring_packing_alat_detail.id_master', $idMaster);
        $builder->where('cssd_monitoring_packing_alat_detail.deleted_at', null);
        $builder->orderBy('pegawai.nama');
        $builder->orderBy('cssd_set_alat.nama_set_alat');
        $query = $builder->get();

        return $query;
    }

    public function dataGroupMonitoringPackingAlatDetailBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_monitoring_packing_alat_detail');
        $builder->select("
            GROUP_CONCAT(CONCAT('#',cssd_set_alat.nama_set_alat) SEPARATOR '') AS nama_set_alat, 
            GROUP_CONCAT(CONCAT('#',cssd_monitoring_packing_alat_detail.bersih) SEPARATOR '') AS bersih, 
            GROUP_CONCAT(CONCAT('#',cssd_monitoring_packing_alat_detail.tajam) SEPARATOR '') AS tajam, 
            GROUP_CONCAT(CONCAT('#',cssd_monitoring_packing_alat_detail.layak) SEPARATOR '') AS layak,
            GROUP_CONCAT(CONCAT('#',cssd_monitoring_packing_alat_detail.indikator) SEPARATOR '') AS indikator,
            GROUP_CONCAT(CONCAT('#',pegawai.nama) SEPARATOR '') AS nama");
        $builder->join('pegawai', 'cssd_monitoring_packing_alat_detail.id_petugas = pegawai.nik');
        $builder->join('cssd_set_alat', 'cssd_monitoring_packing_alat_detail.id_alat = cssd_set_alat.id');
        $builder->where('cssd_monitoring_packing_alat_detail.id_master', $idMaster);
        $builder->where('cssd_monitoring_packing_alat_detail.deleted_at', null);
        $builder->orderBy('cssd_set_alat.nama_set_alat');
        $query = $builder->get();

        return $query;
    }
}
