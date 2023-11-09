<?php

namespace App\Models\LogModels;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'cssd_log_aktifitas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'log'];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

}
