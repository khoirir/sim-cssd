<?php

namespace App\Models\DistribusiModels;

use CodeIgniter\Model;

class PermintaanAlatSterilModel extends Model
{
    protected $table = 'cssd_permintaan_alat_steril';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['tanggal_minta', 'id_petugas_cssd', 'id_petugas_minta', 'id_ruangan', 'deleted_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataAlatSteril($idDetail)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('cssd_penerimaan_alat_kotor');
        $builder->select('
            cssd_penerimaan_alat_kotor.id AS id_master,
            cssd_penerimaan_alat_kotor.tanggal_penerimaan,
            cssd_penerimaan_alat_kotor_detail.id AS id_detail,
            cssd_penerimaan_alat_kotor_detail.id_set_alat,
            cssd_set_alat.nama_set_alat,
            cssd_penerimaan_alat_kotor_detail.jumlah,
            cssd_penerimaan_alat_kotor_detail.sisa,
            cssd_penerimaan_alat_kotor_detail.status_proses,
            cssd_penerimaan_alat_kotor_detail.sisa_distribusi,
            cssd_penerimaan_alat_kotor_detail.status_distribusi,
            cssd_penerimaan_alat_kotor_detail.pemilihan_mesin AS mesin
        ');
        $builder->join('cssd_penerimaan_alat_kotor_detail', 'cssd_penerimaan_alat_kotor.id = cssd_penerimaan_alat_kotor_detail.id_master');
        $builder->join('cssd_set_alat', 'cssd_penerimaan_alat_kotor_detail.id_set_alat = cssd_set_alat.id');
        $builder->whereIn('cssd_penerimaan_alat_kotor_detail.id', $idDetail);
        $builder->where('cssd_penerimaan_alat_kotor_detail.sisa_distribusi >', 0);
        $builder->where('cssd_penerimaan_alat_kotor.deleted_at', null);
        $builder->where('cssd_penerimaan_alat_kotor_detail.deleted_at', null);
        $builder->orderBy('cssd_penerimaan_alat_kotor.tanggal_penerimaan', 'ASC');
        $builder->orderBy('cssd_set_alat.nama_set_alat', 'ASC');
        $query = $builder->get();

        return $query;
    }

    public function dataPermintaanAlatSterilBerdasarkanFilter($tglAwal, $tglAkhir, $start, $limit, $ruangan = 'semua')
    {
        $this->select('
            cssd_permintaan_alat_steril.id,
            cssd_permintaan_alat_steril.tanggal_minta,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_alat_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_alat_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_alat_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_alat_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_alat_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_alat_steril.tanggal_minta <=', $tglAkhir);
        if ($ruangan !== 'semua') {
            $this->where('cssd_permintaan_alat_steril.id_ruangan', $ruangan);
        }
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $this->orderBy('cssd_permintaan_alat_steril.tanggal_minta', 'ASC');
        $this->orderBy('departemen.nama', 'ASC');
        $this->limit($limit, $start);
        $query = $this->get();

        return $query;
    }

    public function dataPermintaanAlatSterilBerdasarkanTanggaldanRuangan($tglAwal, $tglAkhir, $ruangan = 'semua')
    {
        $this->select('
            cssd_permintaan_alat_steril.id,
            cssd_permintaan_alat_steril.tanggal_minta,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_alat_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_alat_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_alat_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_alat_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_alat_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_alat_steril.tanggal_minta <=', $tglAkhir);
        if ($ruangan !== 'semua') {
            $this->where('cssd_permintaan_alat_steril.id_ruangan', $ruangan);
        }
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $this->orderBy('cssd_permintaan_alat_steril.tanggal_minta', 'ASC');
        $this->orderBy('departemen.nama', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataPermintaanAlatSterilBerdasarkanTanggal($tglAwal, $tglAkhir)
    {
        $this->select('
            cssd_permintaan_alat_steril.id,
            cssd_permintaan_alat_steril.tanggal_minta,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_alat_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_alat_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_alat_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_alat_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_alat_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_alat_steril.tanggal_minta <=', $tglAkhir);
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $this->orderBy('cssd_permintaan_alat_steril.tanggal_minta', 'ASC');
        $this->orderBy('departemen.nama', 'ASC');
        $query = $this->get();

        return $query;
    }

    public function dataPermintaanAlatSterilBerdasarkanId($id)
    {
        $this->select('
            cssd_permintaan_alat_steril.id,
            cssd_permintaan_alat_steril.tanggal_minta,
            cssd_permintaan_alat_steril.id_petugas_cssd,
            cssd_permintaan_alat_steril.id_petugas_minta,
            cssd_permintaan_alat_steril.id_ruangan,
            departemen.nama AS ruangan,
            pegawai.nama AS petugas_minta,
            petugas.nama AS petugas_cssd,
            cssd_permintaan_alat_steril.created_at
        ');
        $this->join('departemen', 'cssd_permintaan_alat_steril.id_ruangan = departemen.dep_id');
        $this->join('pegawai', 'cssd_permintaan_alat_steril.id_petugas_minta = pegawai.nik');
        $this->join('pegawai AS petugas', 'cssd_permintaan_alat_steril.id_petugas_cssd = petugas.nik');
        $this->where('cssd_permintaan_alat_steril.id', $id);
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $query = $this->get();

        return $query;
    }

    public function dataLaporanJumlahSterilisasi($bulan)
    {
        $this->select('
            LEFT(cssd_permintaan_alat_steril.tanggal_minta, 7) AS bulan,
            COUNT( DISTINCT cssd_permintaan_alat_steril.id_ruangan ) AS jumlah
        ');
        $this->join('cssd_permintaan_alat_steril_detail', 'cssd_permintaan_alat_steril.id = cssd_permintaan_alat_steril_detail.id_master');
        $this->where('LEFT(cssd_permintaan_alat_steril.tanggal_minta, 7)', $bulan);
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $this->where('cssd_permintaan_alat_steril_detail.deleted_at', null);
        $query = $this->get();

        return $query;
    }

    public function dataLaporanSterilisasiInstrumen($tglAwal, $tglAkhir)
    {
        $this->select('
            LEFT ( cssd_permintaan_alat_steril.tanggal_minta, 7 ) AS bulan,
            cssd_permintaan_alat_steril.id_ruangan,
            departemen.nama AS ruangan,
            SUM(cssd_permintaan_alat_steril_detail.jumlah) AS jumlah
        ');
        $this->join('cssd_permintaan_alat_steril_detail', 'cssd_permintaan_alat_steril.id = cssd_permintaan_alat_steril_detail.id_master');
        $this->join('departemen', 'cssd_permintaan_alat_steril.id_ruangan = departemen.dep_id');
        $this->where('cssd_permintaan_alat_steril.tanggal_minta >=', $tglAwal);
        $this->where('cssd_permintaan_alat_steril.tanggal_minta <=', $tglAkhir);
        $this->where('id_ruangan !=', 'IKO');
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $this->where('cssd_permintaan_alat_steril_detail.deleted_at', null);
        $this->groupBy('cssd_permintaan_alat_steril.id_ruangan');
        $this->groupBy('LEFT ( cssd_permintaan_alat_steril.tanggal_minta, 7 )');
        $this->orderBy('LEFT ( cssd_permintaan_alat_steril.tanggal_minta, 7 )');

        $result = $this->get();
        return $result;
    }

    public function dataLaporanSterilisasiKamarOperasi($bulan)
    {
        $this->select('
	        LEFT ( cssd_permintaan_alat_steril.tanggal_minta, 7 ) AS bulan,
	        SUM(CASE WHEN cssd_set_alat.id_jenis = "9e0dae9d-34ce-11ee-8c2a-14187762d6e2" THEN cssd_permintaan_alat_steril_detail.jumlah ELSE 0 END) AS `set`,
	        SUM(CASE WHEN cssd_set_alat.id_jenis = "1e0dae0d-34ce-11ee-8c2a-14167563d6e2" THEN cssd_permintaan_alat_steril_detail.jumlah ELSE 0 END) AS pouches,
	        SUM(CASE WHEN cssd_set_alat.id_jenis = "8e0dae6d-37ce-13ee-8c2a-14167589d6e2" THEN cssd_permintaan_alat_steril_detail.jumlah ELSE 0 END) AS linen
        ');
        $this->join('cssd_permintaan_alat_steril_detail', 'cssd_permintaan_alat_steril.id = cssd_permintaan_alat_steril_detail.id_master');
        $this->join('cssd_set_alat', 'cssd_permintaan_alat_steril_detail.id_alat = cssd_set_alat.id');
        $this->where('LEFT(cssd_permintaan_alat_steril.tanggal_minta, 7)', $bulan);
        $this->where('cssd_permintaan_alat_steril.id_ruangan', 'IKO');
        $this->where('cssd_permintaan_alat_steril.deleted_at', null);
        $this->where('cssd_permintaan_alat_steril_detail.deleted_at', null);

        return $this->get();
    }
}
