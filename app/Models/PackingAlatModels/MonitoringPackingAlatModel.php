<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringPackingAlatModel extends Model
{
    protected $table = 'cssd_monitoring_packing_alat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_packing'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // public function dataMonitoringPackingAlatBerdasarkanTanggal($tglAwal, $tglAkhir)
    // {
    //     $builder = $this->table('cssd_monitoring_packing_alat');
    //     $builder->select('
    //         cssd_monitoring_packing_alat.id,
    //         cssd_monitoring_packing_alat.tanggal_packing,
    //         cssd_set_alat.nama_set_alat,
    //         cssd_monitoring_packing_alat_detail.bersih,
    //         cssd_monitoring_packing_alat_detail.tajam,
    //         cssd_monitoring_packing_alat_detail.layak,
    //         cssd_monitoring_packing_alat_detail.indikator,
    //         pegawai.nama
    //     ');
    //     $builder->join('cssd_monitoring_packing_alat_detail', 'cssd_monitoring_packing_alat.id = cssd_monitoring_packing_alat_detail.id_master');
    //     $builder->join('cssd_set_alat', 'cssd_monitoring_packing_alat_detail.id_alat = cssd_set_alat.id');
    //     $builder->join('pegawai', 'cssd_monitoring_packing_alat_detail.id_petugas = pegawai.nik');
    //     $builder->where('cssd_monitoring_packing_alat.tanggal_packing >=', $tglAwal);
    //     $builder->where('cssd_monitoring_packing_alat.tanggal_packing <=', $tglAkhir);
    //     $builder->where('cssd_monitoring_packing_alat.deleted_at', null);
    //     $builder->where('cssd_monitoring_packing_alat_detail.deleted_at', null);
    //     $builder->orderBy('cssd_monitoring_packing_alat.tanggal_packing');
    //     $query = $builder->get();

    //     return $query;
    // }
    public function dataMonitoringPackingAlatBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_monitoring_packing_alat');
        $builder->select('
            id, tanggal_packing, created_at
        ');
        $builder->where('tanggal_packing >=', $tglAwal);
        $builder->where('tanggal_packing <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('tanggal_packing');
        $query = $builder->get();

        return $query;
    }

    public function dataMonitoringPackingAlatBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
    {
        $builder = $this->table('cssd_monitoring_packing_alat');
        $builder->select('
            id, tanggal_packing, created_at
        ');
        $builder->where('tanggal_packing >=', $tglAwal);
        $builder->where('tanggal_packing <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('tanggal_packing');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }
}
