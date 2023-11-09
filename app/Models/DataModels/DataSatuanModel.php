<?php

namespace App\Models\DataModels;

use CodeIgniter\Model;

class DataSatuanModel extends Model
{
    protected $table = 'cssd_satuan_set_alat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['kode_satuan', 'nama_satuan'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataSatuan()
    {
        $this->select('
            id, 
            kode_satuan, 
            nama_satuan
        ');
        $this->where('deleted_at', null);
        $this->orderBy('kode_satuan', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataSatuanBerdasarkanFilter($search, $start, $limit)
    {
        $this->select('
            id, 
            kode_satuan, 
            nama_satuan,
            created_at
        ');
        $this->where('deleted_at', null);
        $this->groupStart();
        $this->like('kode_satuan', $search);
        $this->orLike('nama_satuan', $search);
        $this->groupEnd();
        $this->orderBy('kode_satuan', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }

    public function dataSatuanBerdasarkanPencarian($search)
    {
        $this->select('
            id, 
            kode_satuan,
            nama_satuan
        ');
        $this->where('deleted_at', null);
        $this->groupStart();
        $this->like('kode_satuan', $search);
        $this->orLike('nama_satuan', $search);
        $this->groupEnd();
        $query = $this->get();

        return $query;
    }
}
