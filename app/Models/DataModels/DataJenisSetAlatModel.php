<?php

namespace App\Models\DataModels;

use CodeIgniter\Model;

class DataJenisSetAlatModel extends Model
{
    protected $table = 'cssd_jenis_set_alat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['nama_jenis'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataJenisSetAlat()
    {
        $this->select('
            id, 
            nama_jenis
        ');
        $this->where('id !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('deleted_at', null);
        $this->orderBy('nama_jenis', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataJenisSetAlatBerdasarkanFilter($search, $start, $limit)
    {
        $this->select('
            id, 
            nama_jenis,
            created_at
        ');
        $this->where('id !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('deleted_at', null);
        $this->like('nama_jenis', $search);
        $this->orderBy('nama_jenis', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }

    public function dataJenisSetAlatBerdasarkanPencarian($search)
    {
        $this->select('
            id, 
            nama_jenis
        ');
        $this->where('id !=', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('deleted_at', null);
        $this->like('nama_jenis', $search);
        $query = $this->get();

        return $query;
    }
}
