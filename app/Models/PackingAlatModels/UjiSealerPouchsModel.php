<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class UjiSealerPouchsModel extends Model
{
    protected $table = 'cssd_uji_sealer_pouchs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_uji', 'id_petugas', 'suhu_mesin_200', 'speed_sedang', 'hasil_uji', 'upload_bukti_uji', 'keterangan'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    public function dataUjiSealerPouchsDenganFilter($tglAwal, $tglAkhir, $start, $limit, $hasil = '')
    {
        $builder = $this->table('cssd_uji_sealer_pouchs');
        $builder->select('cssd_uji_sealer_pouchs.tanggal_uji, cssd_uji_sealer_pouchs.suhu_mesin_200, cssd_uji_sealer_pouchs.speed_sedang, pegawai.nama, cssd_uji_sealer_pouchs.hasil_uji, cssd_uji_sealer_pouchs.upload_bukti_uji, cssd_uji_sealer_pouchs.keterangan, cssd_uji_sealer_pouchs.id, cssd_uji_sealer_pouchs.created_at');
        $builder->join('pegawai', 'cssd_uji_sealer_pouchs.id_petugas = pegawai.nik');
        $builder->where('cssd_uji_sealer_pouchs.tanggal_uji >=', $tglAwal);
        $builder->where('cssd_uji_sealer_pouchs.tanggal_uji <=', $tglAkhir);
        if ($hasil !== '') {
            $builder->where('cssd_uji_sealer_pouchs.hasil_uji', $hasil);
        }
        $builder->where('cssd_uji_sealer_pouchs.deleted_at', null);
        $builder->orderBy('cssd_uji_sealer_pouchs.tanggal_uji', 'ASC');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }

    public function jumlahDataUjiSealerPouchsBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_uji_sealer_pouchs');
        $builder->select('id');
        $builder->where('tanggal_uji >=', $tglAwal);
        $builder->where('tanggal_uji <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }

    public function jumlahDataUjiSealerPouchsBerdasarkanTanggalDanHasil($tglAwal, $tglAkhir, $hasil = '')
    {
        $builder = $this->table('cssd_uji_sealer_pouchs');
        $builder->select('id');
        $builder->where('tanggal_uji >=', $tglAwal);
        $builder->where('tanggal_uji <=', $tglAkhir);
        if ($hasil !== '') {
            $builder->where('hasil_uji', $hasil);
        }
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return $query;
    }
}
