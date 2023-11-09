<?php

namespace App\Models\DekontaminasiModels;

use CodeIgniter\Model;

class KepatuhanApdModel extends Model
{
    protected $table = 'cssd_kepatuhan_apd';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_cek', 'shift'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // public function dataKepatuhanApdBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
    // {
    //     $builder = $this->table('cssd_kepatuhan_apd');
    //     $builder->select('
    //             cssd_kepatuhan_apd.id, 
    //             cssd_kepatuhan_apd.tanggal_cek, 
    //             cssd_kepatuhan_apd.shift,
    //             cssd_kepatuhan_apd_detail.handschoen, 
    //             cssd_kepatuhan_apd_detail.masker, 
    //             cssd_kepatuhan_apd_detail.apron, 
    //             cssd_kepatuhan_apd_detail.goggle, 
    //             cssd_kepatuhan_apd_detail.sepatu_boot, 
    //             cssd_kepatuhan_apd_detail.penutup_kepala, 
    //             cssd_kepatuhan_apd_detail.keterangan, 
    //             pegawai.nama
    //     ');
    //     $builder->join('cssd_kepatuhan_apd_detail', 'cssd_kepatuhan_apd.id = cssd_kepatuhan_apd_detail.id_master');
    //     $builder->join('pegawai', 'cssd_kepatuhan_apd_detail.id_petugas = pegawai.nik');
    //     $builder->where('cssd_kepatuhan_apd.tanggal_cek >=', $tglAwal);
    //     $builder->where('cssd_kepatuhan_apd.tanggal_cek <=', $tglAkhir);
    //     $builder->where('cssd_kepatuhan_apd.deleted_at', null);
    //     $builder->where('cssd_kepatuhan_apd_detail.deleted_at', null);
    //     $builder->orderBy('cssd_kepatuhan_apd.tanggal_cek');
    //     $builder->orderBy('cssd_kepatuhan_apd.shift');
    //     $builder->limit($limit, $start);
    //     $query = $builder->get();

    //     return $query;
    // }

    // public function dataKepatuhanApdBerdasarkanTanggal($tglAwal, $tglAkhir)
    // {
    //     $builder = $this->table('cssd_kepatuhan_apd');
    //     $builder->select('
    //             cssd_kepatuhan_apd.id, 
    //             cssd_kepatuhan_apd.tanggal_cek, 
    //             cssd_kepatuhan_apd.shift,
    //             cssd_kepatuhan_apd_detail.handschoen, 
    //             cssd_kepatuhan_apd_detail.masker, 
    //             cssd_kepatuhan_apd_detail.apron, 
    //             cssd_kepatuhan_apd_detail.goggle, 
    //             cssd_kepatuhan_apd_detail.sepatu_boot, 
    //             cssd_kepatuhan_apd_detail.penutup_kepala, 
    //             cssd_kepatuhan_apd_detail.keterangan, 
    //             pegawai.nama
    //     ');
    //     $builder->join('cssd_kepatuhan_apd_detail', 'cssd_kepatuhan_apd.id = cssd_kepatuhan_apd_detail.id_master');
    //     $builder->join('pegawai', 'cssd_kepatuhan_apd_detail.id_petugas = pegawai.nik');
    //     $builder->where('cssd_kepatuhan_apd.tanggal_cek >=', $tglAwal);
    //     $builder->where('cssd_kepatuhan_apd.tanggal_cek <=', $tglAkhir);
    //     $builder->where('cssd_kepatuhan_apd.deleted_at', null);
    //     $builder->where('cssd_kepatuhan_apd_detail.deleted_at', null);
    //     $query = $builder->get();

    //     return $query;
    // }

    public function dataKepatuhanApdBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_kepatuhan_apd');
        $builder->select('id, tanggal_cek, shift');
        $builder->where('tanggal_cek >=', $tglAwal);
        $builder->where('tanggal_cek <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }

    public function dataKepatuhanApdBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
    {
        $builder = $this->table('cssd_kepatuhan_apd');
        $builder->select('id, tanggal_cek, shift, created_at');
        $builder->where('tanggal_cek >=', $tglAwal);
        $builder->where('tanggal_cek <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('tanggal_cek');
        $builder->orderBy('shift');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }
}
