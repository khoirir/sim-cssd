<?php

namespace App\Models\DistribusiModels;

use CodeIgniter\Model;

class PermintaanBmhpSterilModel extends Model
{
    protected $table = 'cssd_permintaan_bmhp_steril';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_minta', 'id_petugas_cssd', 'id_petugas_minta', 'id_ruangan', 'deleted_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataBmhpSteril()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('cssd_monitoring_mesin_steam');
        $builder->select('
            cssd_monitoring_mesin_steam.id,
            cssd_monitoring_mesin_steam.tanggal_monitoring,
            cssd_monitoring_mesin_steam_detail.id AS id_detail_steam,
            cssd_monitoring_mesin_steam_detail.id_alat,
            cssd_monitoring_mesin_steam_detail.jumlah,
            cssd_monitoring_mesin_steam_detail.sisa_distribusi,
            cssd_set_alat.nama_set_alat,
            cssd_set_alat.harga
        ');
        $builder->join('cssd_monitoring_mesin_steam_verifikasi', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_verifikasi.id_monitoring_mesin_steam');
        $builder->join('cssd_monitoring_mesin_steam_detail', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_detail.id_monitoring_mesin_steam');
        $builder->join('cssd_set_alat', 'cssd_monitoring_mesin_steam_detail.id_alat = cssd_set_alat.id');
        $builder->where('cssd_monitoring_mesin_steam.deleted_at', null);
        $builder->where('cssd_monitoring_mesin_steam_verifikasi.deleted_at', null);
        $builder->where('cssd_monitoring_mesin_steam_verifikasi.hasil_verifikasi !=', 'Failed');
        $builder->where('cssd_monitoring_mesin_steam_detail.id_ruangan', 'CSSD');
        $builder->where('cssd_monitoring_mesin_steam_detail.sisa_distribusi >', 0);
        $builder->where('cssd_monitoring_mesin_steam_detail.deleted_at', null);
        $builder->orderBy('cssd_monitoring_mesin_steam.tanggal_monitoring', 'ASC');
        $builder->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataPermintaanBmhpSterilBerdasarkanFilter($tglAwal, $tglAkhir, $start, $limit, $ruangan = 'semua')
    {
        $this->select('
            cssd_permintaan_bmhp_steril.id,
            cssd_permintaan_bmhp_steril.tanggal_minta,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_bmhp_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_bmhp_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_bmhp_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_bmhp_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta <=', $tglAkhir);
        if ($ruangan !== 'semua') {
            $this->where('cssd_permintaan_bmhp_steril.id_ruangan', $ruangan);
        }
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $this->orderBy('cssd_permintaan_bmhp_steril.tanggal_minta', 'ASC');
        $this->orderBy('departemen.nama', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }

    public function dataPermintaanBmhpSterilBerdasarkanTanggaldanRuangan($tglAwal, $tglAkhir, $ruangan = 'semua')
    {
        $this->select('
            cssd_permintaan_bmhp_steril.id,
            cssd_permintaan_bmhp_steril.tanggal_minta,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_bmhp_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_bmhp_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_bmhp_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_bmhp_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta <=', $tglAkhir);
        if ($ruangan !== 'semua') {
            $this->where('cssd_permintaan_bmhp_steril.id_ruangan', $ruangan);
        }
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $this->orderBy('cssd_permintaan_bmhp_steril.tanggal_minta', 'ASC');
        $this->orderBy('departemen.nama', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataPermintaanBmhpSterilBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $this->select('
            cssd_permintaan_bmhp_steril.id,
            cssd_permintaan_bmhp_steril.tanggal_minta,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_bmhp_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_bmhp_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_bmhp_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_bmhp_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta <=', $tglAkhir);
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $this->orderBy('cssd_permintaan_bmhp_steril.tanggal_minta', 'ASC');
        $this->orderBy('departemen.nama', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataPermintaanBmhpSterilBerdasarkanId($id)
    {
        $this->select('
            cssd_permintaan_bmhp_steril.id,
            cssd_permintaan_bmhp_steril.tanggal_minta,
            cssd_permintaan_bmhp_steril.id_petugas_cssd,
            cssd_permintaan_bmhp_steril.id_petugas_minta,
            cssd_permintaan_bmhp_steril.id_ruangan,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_bmhp_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_bmhp_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_bmhp_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_bmhp_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_bmhp_steril.id', $id);
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $query = $this->get();

        return $query;
    }

    public function dataLaporanPermintaanBmhpPerRuangan($tglAwal, $tglAkhir)
    {
        $this->select('
            cssd_permintaan_bmhp_steril.id,
            cssd_permintaan_bmhp_steril.id_ruangan,
            departemen.nama AS ruangan,
            cssd_permintaan_bmhp_steril_detail.id_bmhp,
            SUM(cssd_permintaan_bmhp_steril_detail.jumlah) AS jumlah,
            cssd_permintaan_bmhp_steril_detail.harga
        ');
        $this->join('departemen', 'cssd_permintaan_bmhp_steril.id_ruangan = departemen.dep_id');
        $this->join('cssd_permintaan_bmhp_steril_detail', 'cssd_permintaan_bmhp_steril.id = cssd_permintaan_bmhp_steril_detail.id_master');
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta <=', $tglAkhir);
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $this->where('cssd_permintaan_bmhp_steril_detail.deleted_at', null);
        $this->groupBy('cssd_permintaan_bmhp_steril.id_ruangan');
        $this->groupBy('cssd_permintaan_bmhp_steril_detail.id_bmhp');
        $this->orderBy('departemen.nama', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataLaporanPermintaanBmhpPerBulan($tglAwal, $tglAkhir)
    {
        $this->select('
            LEFT(cssd_permintaan_bmhp_steril.tanggal_minta, 7) AS bulan,
            cssd_permintaan_bmhp_steril_detail.id_bmhp,
            SUM(cssd_permintaan_bmhp_steril_detail.jumlah) AS jumlah,
            cssd_permintaan_bmhp_steril_detail.harga
        ');
        $this->join('departemen', 'cssd_permintaan_bmhp_steril.id_ruangan = departemen.dep_id');
        $this->join('cssd_permintaan_bmhp_steril_detail', 'cssd_permintaan_bmhp_steril.id = cssd_permintaan_bmhp_steril_detail.id_master');
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_bmhp_steril.tanggal_minta <=', $tglAkhir);
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $this->where('cssd_permintaan_bmhp_steril_detail.deleted_at', null);
        $this->groupBy('LEFT(cssd_permintaan_bmhp_steril.tanggal_minta, 7)');
        $this->groupBy('cssd_permintaan_bmhp_steril_detail.id_bmhp');
        $this->orderBy('LEFT(cssd_permintaan_bmhp_steril.tanggal_minta, 7)');
        $query = $this->get();

        return $query;
    }

    public function dataLaporanJumlahProduksi($bulan)
    {
        $this->select('
            LEFT(cssd_permintaan_bmhp_steril.tanggal_minta, 7) AS bulan,
            COUNT( DISTINCT cssd_permintaan_bmhp_steril_detail.id_bmhp ) AS jumlah
        ');
        $this->join('cssd_permintaan_bmhp_steril_detail', 'cssd_permintaan_bmhp_steril.id = cssd_permintaan_bmhp_steril_detail.id_master');
        $this->where('LEFT(cssd_permintaan_bmhp_steril.tanggal_minta, 7)', $bulan);
        $this->where('cssd_permintaan_bmhp_steril.deleted_at', null);
        $this->where('cssd_permintaan_bmhp_steril_detail.deleted_at', null);
        $query = $this->get();

        return $query;
    }
}
