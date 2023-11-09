<?php

namespace App\Models\DataModels;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id';

    public function getDetailPegawai($nik)
    {
        $builder = $this->table('pegawai');
        $builder->select('pegawai.nik, pegawai.nama, pegawai.photo, departemen.nama AS departemen');
        $builder->join('departemen', 'departemen.dep_id = pegawai.departemen');
        $builder->where('pegawai.nik', $nik);
        $query = $builder->get()->getFirstRow('array');

        return $query;
    }

    public function getListPegawaiCSSD()
    {
        return $this
            ->where("departemen", "CSSD")
            ->where("stts_aktif", "AKTIF")
            ->findAll();
    }

    public function getListPegawai()
    {
        return $this
            ->where("stts_aktif", "AKTIF")
            ->findAll();
    }
}
