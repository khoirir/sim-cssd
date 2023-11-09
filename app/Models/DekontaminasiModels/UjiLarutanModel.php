<?php

namespace App\Models\DekontaminasiModels;

use CodeIgniter\Model;

class UjiLarutanModel extends Model
{
    protected $table = 'cssd_uji_larutan_dtt_alkacyd';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_uji', 'id_petugas', 'metracid_1_ml', 'alkacid_10_ml', 'hasil_warna', 'upload_larutan', 'keterangan'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    public function dataUjiLarutanDenganFilter($tglAwal, $tglAkhir, $start, $limit, $hasil = '')
    {
        $builder = $this->table('cssd_uji_larutan_dtt_alkacyd');
        $builder->select('cssd_uji_larutan_dtt_alkacyd.tanggal_uji, cssd_uji_larutan_dtt_alkacyd.metracid_1_ml, cssd_uji_larutan_dtt_alkacyd.alkacid_10_ml, pegawai.nama, cssd_uji_larutan_dtt_alkacyd.hasil_warna, cssd_uji_larutan_dtt_alkacyd.upload_larutan, cssd_uji_larutan_dtt_alkacyd.keterangan, cssd_uji_larutan_dtt_alkacyd.id, cssd_uji_larutan_dtt_alkacyd.created_at');
        $builder->join('pegawai', 'cssd_uji_larutan_dtt_alkacyd.id_petugas = pegawai.nik');
        $builder->where('cssd_uji_larutan_dtt_alkacyd.tanggal_uji >=', $tglAwal);
        $builder->where('cssd_uji_larutan_dtt_alkacyd.tanggal_uji <=', $tglAkhir);
        if ($hasil !== '') {
            $builder->where('cssd_uji_larutan_dtt_alkacyd.hasil_warna', $hasil);
        }
        $builder->where('cssd_uji_larutan_dtt_alkacyd.deleted_at', null);
        $builder->orderBy('cssd_uji_larutan_dtt_alkacyd.tanggal_uji', 'ASC');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }

    public function jumlahDataUjiLarutanBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_uji_larutan_dtt_alkacyd');
        $builder->select('id');
        $builder->where('tanggal_uji >=', $tglAwal);
        $builder->where('tanggal_uji <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }

    public function jumlahDataUjiLarutanBerdasarkanTanggalDanHasil($tglAwal, $tglAkhir, $hasil = '')
    {
        $builder = $this->table('cssd_uji_larutan_dtt_alkacyd');
        $builder->select('id');
        $builder->where('tanggal_uji >=', $tglAwal);
        $builder->where('tanggal_uji <=', $tglAkhir);
        if ($hasil !== '') {
            $builder->where('hasil_warna', $hasil);
        }
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }
}
