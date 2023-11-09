<?php

namespace App\Models\DekontaminasiModels;

use CodeIgniter\Model;

class KepatuhanApdDetailModel extends Model
{
    protected $table = 'cssd_kepatuhan_apd_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_master', 'id_petugas', 'handschoen', 'masker', 'apron', 'goggle', 'sepatu_boot', 'penutup_kepala', 'keterangan'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_kepatuhan_apd_detail');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function dataKepatuhanApdDetailBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_kepatuhan_apd_detail');
        $builder->select('cssd_kepatuhan_apd_detail.id, cssd_kepatuhan_apd_detail.id_master, cssd_kepatuhan_apd_detail.id_petugas, cssd_kepatuhan_apd_detail.handschoen, cssd_kepatuhan_apd_detail.masker, cssd_kepatuhan_apd_detail.apron, cssd_kepatuhan_apd_detail.goggle, cssd_kepatuhan_apd_detail.sepatu_boot, cssd_kepatuhan_apd_detail.penutup_kepala, cssd_kepatuhan_apd_detail.keterangan, pegawai.nama');
        $builder->join('pegawai', 'cssd_kepatuhan_apd_detail.id_petugas = pegawai.nik');
        $builder->where('cssd_kepatuhan_apd_detail.id_master', $idMaster);
        $builder->where('deleted_at', null);
        $builder->orderBy('cssd_kepatuhan_apd_detail.id_petugas');
        $query = $builder->get();

        return $query;
    }

    public function dataGroupKepatuhanApdDetailBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_kepatuhan_apd_detail');
        $builder->select("
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.handschoen) SEPARATOR '') AS handschoen, 
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.masker) SEPARATOR '') AS masker, 
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.apron) SEPARATOR '') AS apron,
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.goggle) SEPARATOR '') AS goggle, 
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.sepatu_boot) SEPARATOR '') AS sepatu_boot, 
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.penutup_kepala) SEPARATOR '') AS penutup_kepala, 
            GROUP_CONCAT(CONCAT('#',cssd_kepatuhan_apd_detail.keterangan) SEPARATOR '') AS keterangan, 
            GROUP_CONCAT(CONCAT('#',pegawai.nama) SEPARATOR '') AS nama");
        $builder->join('pegawai', 'cssd_kepatuhan_apd_detail.id_petugas = pegawai.nik');
        $builder->where('cssd_kepatuhan_apd_detail.id_master', $idMaster);
        $builder->where('deleted_at', null);
        $builder->orderBy('cssd_kepatuhan_apd_detail.id_petugas');
        $query = $builder->get();

        return $query;
    }
}
