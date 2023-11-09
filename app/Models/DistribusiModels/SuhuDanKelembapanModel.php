<?php

namespace App\Models\DistribusiModels;

use CodeIgniter\Model;

class SuhuDanKelembapanModel extends Model
{
    protected $table = 'cssd_suhu_kelembapan_distribusi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tgl_catat', 'id_petugas', 'suhu', 'kelembapan', 'tindakan', 'hasil_tindakan'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    public function dataSuhuKelembapanBerdasarkanTanggaldanLimit($tglAwal, $tglAkhir, $start, $limit)
    {
        $builder = $this->table('cssd_suhu_kelembapan_distribusi');
        $builder->select('cssd_suhu_kelembapan_distribusi.tgl_catat, cssd_suhu_kelembapan_distribusi.suhu, cssd_suhu_kelembapan_distribusi.kelembapan, pegawai.nama, cssd_suhu_kelembapan_distribusi.tindakan, cssd_suhu_kelembapan_distribusi.hasil_tindakan, cssd_suhu_kelembapan_distribusi.id, cssd_suhu_kelembapan_distribusi.created_at');
        $builder->join('pegawai', 'cssd_suhu_kelembapan_distribusi.id_petugas = pegawai.nik');
        $builder->where('cssd_suhu_kelembapan_distribusi.tgl_catat >=', $tglAwal);
        $builder->where('cssd_suhu_kelembapan_distribusi.tgl_catat <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('cssd_suhu_kelembapan_distribusi.tgl_catat', 'ASC');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }

    public function dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_suhu_kelembapan_distribusi');
        $builder->select('cssd_suhu_kelembapan_distribusi.tgl_catat, cssd_suhu_kelembapan_distribusi.suhu, cssd_suhu_kelembapan_distribusi.kelembapan, pegawai.nama, cssd_suhu_kelembapan_distribusi.tindakan, cssd_suhu_kelembapan_distribusi.hasil_tindakan, cssd_suhu_kelembapan_distribusi.id');
        $builder->join('pegawai', 'cssd_suhu_kelembapan_distribusi.id_petugas = pegawai.nik');
        $builder->where('cssd_suhu_kelembapan_distribusi.tgl_catat >=', $tglAwal);
        $builder->where('cssd_suhu_kelembapan_distribusi.tgl_catat <=', $tglAkhir);
        $builder->where('deleted_at', null);
        $builder->orderBy('cssd_suhu_kelembapan_distribusi.tgl_catat', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataSuhuKelembapanBerdasarkanId($id)
    {
        return $this->find($id);
    }
}
