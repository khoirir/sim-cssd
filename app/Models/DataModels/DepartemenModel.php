<?php

namespace App\Models\DataModels;

use CodeIgniter\Model;

class DepartemenModel extends Model
{
    protected $table = 'departemen';
    protected $primaryKey = 'dep_id';

    public function getDepartemenUser($dep_id)
    {
        return $this->find($dep_id);
    }

    public function getListDepartemen()
    {
        return $this
            ->where('dep_id !=', '-')->findAll();
    }
}
