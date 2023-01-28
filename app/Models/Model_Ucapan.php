<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_Ucapan extends Model
{
    protected $table      = 'tb_ucapan';
    protected $primaryKey = 'uc_id';
    protected $allowedFields = ['uc_nama', 'uc_date', 'uc_isi', 'uc_hadir'];

    public function list()
    {
        return $this->table('tb_ucapan')
            ->orderBy('uc_id', 'DESC')
            ->get()->getResultArray();
    }

}
