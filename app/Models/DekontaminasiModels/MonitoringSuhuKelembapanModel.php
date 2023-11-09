<?php

namespace App\Models\DekontaminasiModels;

use CodeIgniter\Model;

class MonitoringSuhuKelembapanModel extends Model
{
    protected $table = 'cssd_monitoring_suhu_kelembapan';
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
        $builder = $this->table('cssd_monitoring_suhu_kelembapan');
        $builder->select('DATE_FORMAT(cssd_monitoring_suhu_kelembapan.tgl_catat, "%d-%m-%Y") AS tgl_catat, cssd_monitoring_suhu_kelembapan.suhu, cssd_monitoring_suhu_kelembapan.kelembapan, pegawai.nama, cssd_monitoring_suhu_kelembapan.tindakan, cssd_monitoring_suhu_kelembapan.hasil_tindakan, cssd_monitoring_suhu_kelembapan.id, cssd_monitoring_suhu_kelembapan.created_at');
        $builder->join('pegawai', 'cssd_monitoring_suhu_kelembapan.id_petugas = pegawai.nik');
        $builder->where('cssd_monitoring_suhu_kelembapan.tgl_catat >=', $tglAwal);
        $builder->where('cssd_monitoring_suhu_kelembapan.tgl_catat <=', $tglAkhir);
        $builder->where('cssd_monitoring_suhu_kelembapan.deleted_at', null);
        $builder->orderBy('cssd_monitoring_suhu_kelembapan.tgl_catat', 'ASC');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }

    public function dataSuhuKelembapanBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_monitoring_suhu_kelembapan');
        $builder->select('DATE_FORMAT(cssd_monitoring_suhu_kelembapan.tgl_catat, "%d-%m-%Y") AS tgl_catat, cssd_monitoring_suhu_kelembapan.suhu, cssd_monitoring_suhu_kelembapan.kelembapan, pegawai.nama, cssd_monitoring_suhu_kelembapan.tindakan, cssd_monitoring_suhu_kelembapan.hasil_tindakan, cssd_monitoring_suhu_kelembapan.id');
        $builder->join('pegawai', 'cssd_monitoring_suhu_kelembapan.id_petugas = pegawai.nik');
        $builder->where('cssd_monitoring_suhu_kelembapan.tgl_catat >=', $tglAwal);
        $builder->where('cssd_monitoring_suhu_kelembapan.tgl_catat <=', $tglAkhir);
        $builder->where('cssd_monitoring_suhu_kelembapan.deleted_at', null);
        $builder->orderBy('cssd_monitoring_suhu_kelembapan.tgl_catat', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataSuhuKelembapanBerdasarkanId($id)
    {
        return $this->find($id);
    }
}
