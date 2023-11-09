<?php

namespace App\Models\DataModels;

use CodeIgniter\Model;

class DataBmhpModel extends Model
{
    protected $table = 'cssd_set_alat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['nama_set_alat', 'id_jenis', 'id_satuan', 'harga'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataBmhpBerdasarkanId($id)
    {
        return $this->where('id_jenis', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2')
            ->find($id);
    }

    public function dataBmhp()
    {
        $this->select('
            cssd_set_alat.id, 
            cssd_set_alat.nama_set_alat,
            cssd_set_alat.harga,
            cssd_satuan_set_alat.nama_satuan
        ');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataBmhpBerdasarkanFilter($search, $start, $limit)
    {
        $this->select('
            cssd_set_alat.id, 
            cssd_set_alat.nama_set_alat,
            cssd_set_alat.harga,
            cssd_set_alat.created_at,
            cssd_satuan_set_alat.kode_satuan
        ');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->like('cssd_set_alat.nama_set_alat', $search);
        $this->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }

    public function dataBmhpBerdasarkanPencarian($search)
    {
        $this->select('
            cssd_set_alat.id,
            cssd_set_alat.nama_set_alat,
            cssd_set_alat.harga,
            cssd_set_alat.created_at,
            cssd_satuan_set_alat.nama_satuan
        ');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->like('cssd_set_alat.nama_set_alat', $search);
        $query = $this->get();

        return $query;
    }

    public function dataBmhpBerdasarkanLimit($start, $limit)
    {
        $this->select('
            cssd_set_alat.id, 
            cssd_set_alat.nama_set_alat,
            cssd_set_alat.harga,
            cssd_set_alat.created_at,
            cssd_satuan_set_alat.kode_satuan
        ');
        $this->join('cssd_satuan_set_alat', 'cssd_set_alat.id_satuan = cssd_satuan_set_alat.id');
        $this->where('cssd_set_alat.id_jenis', '0e0dae9d-34ce-11ee-8c2a-14187762d6e2');
        $this->where('cssd_set_alat.deleted_at', null);
        $this->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }
}
