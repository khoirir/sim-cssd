<?php

namespace App\Models\DekontaminasiModels;

use CodeIgniter\Model;

class PenerimaanAlatKotorDetailModel extends Model
{
    protected $table = 'cssd_penerimaan_alat_kotor_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_master', 'id_set_alat', 'jumlah', 'enzym', 'dtt', 'ultrasonic', 'bilas', 'washer', 'pemilihan_mesin', 'status_proses', 'sisa', 'status_distribusi', 'sisa_distribusi'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function insertMultiple($data)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor_detail');
        $result = $builder->insertBatch($data);

        return $result;
    }

    public function dataPenerimaanAlatKotorDetailBerdasarkanIdMaster($idMaster)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor_detail');
        $builder->select(
            'cssd_penerimaan_alat_kotor_detail.id, 
            cssd_penerimaan_alat_kotor_detail.id_master, 
            cssd_penerimaan_alat_kotor_detail.id_set_alat, 
            cssd_set_alat.nama_set_alat, 
            cssd_penerimaan_alat_kotor_detail.jumlah, 
            cssd_penerimaan_alat_kotor_detail.enzym, 
            cssd_penerimaan_alat_kotor_detail.dtt, 
            cssd_penerimaan_alat_kotor_detail.ultrasonic, 
            cssd_penerimaan_alat_kotor_detail.bilas, 
            cssd_penerimaan_alat_kotor_detail.washer, 
            cssd_penerimaan_alat_kotor_detail.pemilihan_mesin, 
            cssd_penerimaan_alat_kotor_detail.status_proses,
            cssd_penerimaan_alat_kotor_detail.sisa,
            cssd_penerimaan_alat_kotor_detail.status_distribusi,
            cssd_penerimaan_alat_kotor_detail.sisa_distribusi'
        );
        $builder->join('cssd_set_alat', 'cssd_penerimaan_alat_kotor_detail.id_set_alat = cssd_set_alat.id');
        $builder->where('cssd_penerimaan_alat_kotor_detail.id_master', $idMaster);
        $builder->where('cssd_penerimaan_alat_kotor_detail.deleted_at', null);
        $builder->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataStatusAlatKotor($idMaster)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor_detail');
        $builder->select("GROUP_CONCAT(CONCAT('',status_proses) SEPARATOR '') AS status_proses");
        $builder->select("GROUP_CONCAT(CONCAT('',status_distribusi) SEPARATOR '') AS status_distribusi");
        $builder->where('id_master', $idMaster);
        $builder->where('CONCAT(status_proses,status_distribusi) !=', '');
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }

    public function dataAlatKotorDistribusiBerdasarkanId($id)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor_detail');
        $builder->select("status_distribusi");
        $builder->whereIn('id', $id);
        $builder->where('status_distribusi', 'Distribusi');
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }
}
