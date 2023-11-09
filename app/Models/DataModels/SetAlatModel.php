<?php

namespace App\Models\DataModels;

use CodeIgniter\Model;

class SetAlatModel extends Model
{
    protected $table = 'cssd_set_alat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['nama_set_alat', 'id_jenis', 'id_satuan'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataSetAlat()
    {
        $this->select('
            cssd_set_alat.id, 
            cssd_set_alat.nama_set_alat, 
            cssd_jenis_set_alat.nama_jenis,
            cssd_satuan_set_alat.nama_satuan
        ');
        $this->join('cssd_jenis_set_alat', 'cssd_set_alat.id_jenis = cssd_jenis_set_alat.id');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataSetAlatBerdasarkanFilter($search, $start, $limit)
    {
        $this->select('
            cssd_set_alat.id,
            cssd_set_alat.created_at,
            cssd_set_alat.nama_set_alat, 
            cssd_jenis_set_alat.nama_jenis,
            cssd_satuan_set_alat.kode_satuan,
            cssd_satuan_set_alat.nama_satuan
        ');
        $this->join('cssd_jenis_set_alat', 'cssd_set_alat.id_jenis = cssd_jenis_set_alat.id');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->groupStart();
        $this->like('cssd_set_alat.nama_set_alat', $search);
        $this->orLike('cssd_jenis_set_alat.nama_jenis', $search);
        $this->groupEnd();
        $this->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }

    public function dataSetAlatBerdasarkanPencarian($search)
    {
        $this->select('
            cssd_set_alat.id,
            cssd_set_alat.created_at,
            cssd_set_alat.nama_set_alat, 
            cssd_jenis_set_alat.nama_jenis,
            cssd_satuan_set_alat.nama_satuan
        ');
        $this->join('cssd_jenis_set_alat', 'cssd_set_alat.id_jenis = cssd_jenis_set_alat.id');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->groupStart();
        $this->like('cssd_set_alat.nama_set_alat', $search);
        $this->orLike('cssd_jenis_set_alat.nama_jenis', $search);
        $this->groupEnd();
        $query = $this->get();

        return $query;
    }
}
