<?php

namespace App\Models\DistribusiModels;

use CodeIgniter\Model;

class PermintaanBmhpSterilDetailModel extends Model
{
    protected $table = 'cssd_permintaan_bmhp_steril_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_master', 'id_monitoring_mesin_steam_detail', 'id_bmhp', 'jumlah', 'harga', 'keterangan', 'deleted_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function dataPermintaanBmhpSterilDetailBerdasarkanIdMaster($idMaster)
    {
        $this->select('
            cssd_permintaan_bmhp_steril_detail.id,
            cssd_permintaan_bmhp_steril_detail.id_bmhp,
            cssd_permintaan_bmhp_steril_detail.id_monitoring_mesin_steam_detail,
            cssd_set_alat.nama_set_alat,
            cssd_permintaan_bmhp_steril_detail.jumlah,
            cssd_permintaan_bmhp_steril_detail.harga,
            cssd_permintaan_bmhp_steril_detail.keterangan
        ');
        $this->join('cssd_set_alat', 'cssd_permintaan_bmhp_steril_detail.id_bmhp = cssd_set_alat.id');
        $this->where('cssd_permintaan_bmhp_steril_detail.id_master', $idMaster);
        $this->where('cssd_permintaan_bmhp_steril_detail.deleted_at', null);
        $this->orderBy('cssd_set_alat.nama_set_alat', 'ASC');

        return $this->get();
    }

    public function dataPermintaanBmhpSterilDetailBerdasarkanId($id)
    {
        $this->select('
            cssd_permintaan_bmhp_steril_detail.id,
            cssd_permintaan_bmhp_steril_detail.id_bmhp,
            cssd_permintaan_bmhp_steril_detail.id_monitoring_mesin_steam_detail,
            cssd_set_alat.nama_set_alat,
            cssd_permintaan_bmhp_steril_detail.jumlah,
            cssd_permintaan_bmhp_steril_detail.harga,
            cssd_permintaan_bmhp_steril_detail.keterangan
        ');
        $this->join('cssd_set_alat', 'cssd_permintaan_bmhp_steril_detail.id_bmhp = cssd_set_alat.id');
        $this->where('cssd_permintaan_bmhp_steril_detail.id', $id);
        $this->where('cssd_permintaan_bmhp_steril_detail.deleted_at', null);

        return $this->get();
    }
}
