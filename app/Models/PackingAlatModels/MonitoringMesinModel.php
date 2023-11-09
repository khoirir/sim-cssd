<?php

namespace App\Models\PackingAlatModels;

use CodeIgniter\Model;

class MonitoringMesinModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function dataVerifikasiAlatSteril($idRuangan)
    {
        $mesinSteam = $this->db->table('cssd_monitoring_mesin_steam');
        $mesinEog = $this->db->table('cssd_monitoring_mesin_eog');
        $mesinPlasma = $this->db->table('cssd_monitoring_mesin_plasma');

        $mesinSteam->select('
            cssd_monitoring_mesin_steam_detail.id_detail_penerimaan_alat_kotor
        ');
        $mesinSteam->join('cssd_monitoring_mesin_steam_detail', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_detail.id_monitoring_mesin_steam');
        $mesinSteam->join('cssd_monitoring_mesin_steam_verifikasi', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_verifikasi.id_monitoring_mesin_steam');
        $mesinSteam->where('cssd_monitoring_mesin_steam_detail.id_ruangan', $idRuangan);
        $mesinSteam->where('cssd_monitoring_mesin_steam_verifikasi.hasil_verifikasi !=', 'Failed');
        $mesinSteam->where('cssd_monitoring_mesin_steam.deleted_at', null);
        $mesinSteam->where('cssd_monitoring_mesin_steam_detail.deleted_at', null);
        $mesinSteam->where('cssd_monitoring_mesin_steam_verifikasi.deleted_at', null);


        $mesinEog->select('
            cssd_monitoring_mesin_eog_detail.id_detail_penerimaan_alat_kotor
        ');
        $mesinEog->join('cssd_monitoring_mesin_eog_detail', 'cssd_monitoring_mesin_eog.id = cssd_monitoring_mesin_eog_detail.id_monitoring_mesin_eog');
        $mesinEog->join('cssd_monitoring_mesin_eog_verifikasi', 'cssd_monitoring_mesin_eog.id = cssd_monitoring_mesin_eog_verifikasi.id_monitoring_mesin_eog');
        $mesinEog->where('cssd_monitoring_mesin_eog_detail.id_ruangan', $idRuangan);
        $mesinEog->where('cssd_monitoring_mesin_eog_verifikasi.hasil_verifikasi', 'Steril');
        $mesinEog->where('cssd_monitoring_mesin_eog.deleted_at', null);
        $mesinEog->where('cssd_monitoring_mesin_eog_detail.deleted_at', null);
        $mesinEog->where('cssd_monitoring_mesin_eog_verifikasi.deleted_at', null);


        $mesinPlasma->select('
            cssd_monitoring_mesin_plasma_detail.id_detail_penerimaan_alat_kotor
        ');
        $mesinPlasma->join('cssd_monitoring_mesin_plasma_detail', 'cssd_monitoring_mesin_plasma.id = cssd_monitoring_mesin_plasma_detail.id_monitoring_mesin_plasma');
        $mesinPlasma->join('cssd_monitoring_mesin_plasma_verifikasi', 'cssd_monitoring_mesin_plasma.id = cssd_monitoring_mesin_plasma_verifikasi.id_monitoring_mesin_plasma');
        $mesinPlasma->where('cssd_monitoring_mesin_plasma_detail.id_ruangan', $idRuangan);
        $mesinPlasma->where('cssd_monitoring_mesin_plasma_verifikasi.hasil_verifikasi', 'Steril');
        $mesinPlasma->where('cssd_monitoring_mesin_plasma.deleted_at', null);
        $mesinPlasma->where('cssd_monitoring_mesin_plasma_detail.deleted_at', null);
        $mesinPlasma->where('cssd_monitoring_mesin_plasma_verifikasi.deleted_at', null);



        return $mesinSteam->union($mesinEog)->union($mesinPlasma)->get();
    }

    // public function dataVerifikasiBmhpSteril($idRuangan)
    // {
    //     $mesinSteam = $this->db->table('cssd_monitoring_mesin_steam');

    //     $mesinSteam->select('
    //         cssd_monitoring_mesin_steam_detail.id_detail_penerimaan_alat_kotor
    //     ');
    //     $mesinSteam->join('cssd_monitoring_mesin_steam_detail', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_detail.id_monitoring_mesin_steam');
    //     $mesinSteam->join('cssd_monitoring_mesin_steam_verifikasi', 'cssd_monitoring_mesin_steam.id = cssd_monitoring_mesin_steam_verifikasi.id_monitoring_mesin_steam');
    //     $mesinSteam->where('cssd_monitoring_mesin_steam_detail.id_ruangan', $idRuangan);
    //     $mesinSteam->where('cssd_monitoring_mesin_steam_verifikasi.hasil_verifikasi !=', 'Failed');
    //     $mesinSteam->where('cssd_monitoring_mesin_steam.deleted_at', null);
    //     $mesinSteam->where('cssd_monitoring_mesin_steam_detail.deleted_at', null);
    //     $mesinSteam->where('cssd_monitoring_mesin_steam_verifikasi.deleted_at', null);

    //     return $mesinSteam->get();
    // }
}
