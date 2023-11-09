<?php

namespace App\Models\AuthModels;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $USER_KEY;
    protected $PASSWORD_KEY;

    public function __construct()
    {
        $this->USER_KEY = getenv('USER_KEY');
        $this->PASSWORD_KEY = getenv('PASSWORD_KEY');
    }

    public function cekUser($username)
    {
        $query = $this->query("SELECT AES_DECRYPT(id_user, ?) AS id_user FROM `user` WHERE id_user = AES_ENCRYPT(?, ?)", array($this->USER_KEY, $username, $this->USER_KEY));
        $row = $query->getFirstRow('array');

        return $row;
    }

    public function cekPassword($username, $password)
    {
        $query = $this->query("SELECT AES_DECRYPT(id_user, ?) AS id_user FROM `user` WHERE id_user = AES_ENCRYPT(?, ?) AND `password` = AES_ENCRYPT(?, ?)", array($this->USER_KEY, $username, $this->USER_KEY, $password, $this->PASSWORD_KEY));
        $row = $query->getFirstRow('array');

        return $row;
    }
}
