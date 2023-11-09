<?php

namespace App\Models\DekontaminasiModels;

use CodeIgniter\Model;

class PenerimaanAlatKotorModel extends Model
{
    protected $table = 'cssd_penerimaan_alat_kotor';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_penerimaan', 'id_petugas_cssd', 'id_petugas_penyetor', 'id_ruangan', 'upload_dokumentasi'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataPenerimaanAlatKotorBerdasarkanFilter($tglAwal, $tglAkhir, $start, $limit, $ruangan = 'semua', $dokumentasi = 'semua')
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor');
        $builder->select('cssd_penerimaan_alat_kotor.tanggal_penerimaan, departemen.nama AS ruangan, petugas.nama AS petugas_cssd, penyetor.nama AS petugas_penyetor, cssd_penerimaan_alat_kotor.upload_dokumentasi, cssd_penerimaan_alat_kotor.id, cssd_penerimaan_alat_kotor.created_at');
        $builder->join('pegawai AS petugas', 'cssd_penerimaan_alat_kotor.id_petugas_cssd = petugas.nik');
        $builder->join('pegawai AS penyetor', 'cssd_penerimaan_alat_kotor.id_petugas_penyetor = penyetor.nik');
        $builder->join('departemen', 'cssd_penerimaan_alat_kotor.id_ruangan = departemen.dep_id');
        $builder->where('cssd_penerimaan_alat_kotor.tanggal_penerimaan >=', $tglAwal);
        $builder->where('cssd_penerimaan_alat_kotor.tanggal_penerimaan <=', $tglAkhir);
        if ($ruangan !== 'semua') {
            $builder->where('cssd_penerimaan_alat_kotor.id_ruangan', $ruangan);
        }
        if ($dokumentasi !== 'semua') {
            if ($dokumentasi === 'belum') {
                $builder->where('cssd_penerimaan_alat_kotor.upload_dokumentasi', null);
            } else {
                $builder->where('cssd_penerimaan_alat_kotor.upload_dokumentasi !=', null);
            }
        }
        $builder->where('cssd_penerimaan_alat_kotor.deleted_at', null);
        $builder->orderBy('cssd_penerimaan_alat_kotor.tanggal_penerimaan', 'ASC');
        $builder->limit($limit, $start);
        $query = $builder->get();

        return $query;
    }

    public function dataPenerimaanAlatKotorBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor');
        $builder->select('cssd_penerimaan_alat_kotor.tanggal_penerimaan, departemen.nama AS ruangan, petugas.nama AS petugas_cssd, penyetor.nama AS petugas_penyetor, cssd_penerimaan_alat_kotor.upload_dokumentasi, cssd_penerimaan_alat_kotor.id');
        $builder->join('pegawai AS petugas', 'cssd_penerimaan_alat_kotor.id_petugas_cssd = petugas.nik');
        $builder->join('pegawai AS penyetor', 'cssd_penerimaan_alat_kotor.id_petugas_penyetor = penyetor.nik');
        $builder->join('departemen', 'cssd_penerimaan_alat_kotor.id_ruangan = departemen.dep_id');
        $builder->where('cssd_penerimaan_alat_kotor.tanggal_penerimaan >=', $tglAwal);
        $builder->where('cssd_penerimaan_alat_kotor.tanggal_penerimaan <=', $tglAkhir);
        $builder->where('cssd_penerimaan_alat_kotor.deleted_at', null);
        $builder->orderBy('cssd_penerimaan_alat_kotor.tanggal_penerimaan', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataPenerimaanAlatKotorBerdasarkanTanggalFilter($tglAwal, $tglAkhir, $ruangan = 'semua', $dokumentasi = 'semua')
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor');
        $builder->select('cssd_penerimaan_alat_kotor.tanggal_penerimaan, departemen.nama AS ruangan, petugas.nama AS petugas_cssd, penyetor.nama AS petugas_penyetor, cssd_penerimaan_alat_kotor.upload_dokumentasi, cssd_penerimaan_alat_kotor.id');
        $builder->join('pegawai AS petugas', 'cssd_penerimaan_alat_kotor.id_petugas_cssd = petugas.nik');
        $builder->join('pegawai AS penyetor', 'cssd_penerimaan_alat_kotor.id_petugas_penyetor = penyetor.nik');
        $builder->join('departemen', 'cssd_penerimaan_alat_kotor.id_ruangan = departemen.dep_id');
        $builder->where('cssd_penerimaan_alat_kotor.tanggal_penerimaan >=', $tglAwal);
        $builder->where('cssd_penerimaan_alat_kotor.tanggal_penerimaan <=', $tglAkhir);
        if ($ruangan !== 'semua') {
            $builder->where('cssd_penerimaan_alat_kotor.id_ruangan', $ruangan);
        }
        if ($dokumentasi !== 'semua') {
            if ($dokumentasi === 'belum') {
                $builder->where('cssd_penerimaan_alat_kotor.upload_dokumentasi', null);
            } else {
                $builder->where('cssd_penerimaan_alat_kotor.upload_dokumentasi !=', null);
            }
        }
        $builder->where('cssd_penerimaan_alat_kotor.deleted_at', null);
        $builder->orderBy('cssd_penerimaan_alat_kotor.tanggal_penerimaan', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataPenerimaanAlatKotorBerdasarkanId($id)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor');
        $builder->select('cssd_penerimaan_alat_kotor.tanggal_penerimaan, departemen.nama AS ruangan, petugas.nama AS petugas_cssd, penyetor.nama AS petugas_penyetor, cssd_penerimaan_alat_kotor.upload_dokumentasi, cssd_penerimaan_alat_kotor.id, cssd_penerimaan_alat_kotor.created_at');
        $builder->join('pegawai AS petugas', 'cssd_penerimaan_alat_kotor.id_petugas_cssd = petugas.nik');
        $builder->join('pegawai AS penyetor', 'cssd_penerimaan_alat_kotor.id_petugas_penyetor = penyetor.nik');
        $builder->join('departemen', 'cssd_penerimaan_alat_kotor.id_ruangan = departemen.dep_id');
        $builder->where('cssd_penerimaan_alat_kotor.id', $id);
        $builder->where('cssd_penerimaan_alat_kotor.deleted_at', null);
        $query = $builder->get();

        return $query;
    }

    public function dataAlatKotorBerdasarkanIdRuanganDanMesin($idRuangan, $mesin)
    {
        $builder = $this->table('cssd_penerimaan_alat_kotor');
        $builder->select('
            cssd_penerimaan_alat_kotor.id AS id_master,
            cssd_penerimaan_alat_kotor.tanggal_penerimaan,
            penyetor.nama AS petugas_penyetor,
            cssd_penerimaan_alat_kotor_detail.id AS id_detail, 
            cssd_penerimaan_alat_kotor_detail.id_set_alat,
            cssd_set_alat.nama_set_alat,
            cssd_penerimaan_alat_kotor_detail.jumlah, 
            cssd_penerimaan_alat_kotor_detail.sisa,
            cssd_penerimaan_alat_kotor_detail.status_proses
        ');
        $builder->join('pegawai AS penyetor', 'cssd_penerimaan_alat_kotor.id_petugas_penyetor = penyetor.nik');
        $builder->join('cssd_penerimaan_alat_kotor_detail', 'cssd_penerimaan_alat_kotor.id = cssd_penerimaan_alat_kotor_detail.id_master');
        $builder->join('cssd_set_alat', 'cssd_penerimaan_alat_kotor_detail.id_set_alat = cssd_set_alat.id');
        $builder->where('cssd_penerimaan_alat_kotor.id_ruangan', $idRuangan);
        $builder->where('cssd_penerimaan_alat_kotor.deleted_at', null);
        $builder->where('cssd_penerimaan_alat_kotor_detail.sisa >', 0);
        $builder->where('cssd_penerimaan_alat_kotor_detail.pemilihan_mesin', $mesin);
        $builder->where('cssd_penerimaan_alat_kotor_detail.deleted_at', null);
        $builder->orderBy('cssd_penerimaan_alat_kotor.tanggal_penerimaan', 'ASC');
        $builder->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $query = $builder->get();

        return $query;
    }

}
