<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_Undangan extends Model
{
    protected $table      = 'tb_undangan';
    protected $primaryKey = 'ud_id';
    protected $allowedFields = ['ud_nama', 'ud_slug', 'ud_tamu_dari'];

    public function cek($slug)
     {
         return $this->table('tb_undangan')
             ->where('ud_slug', $slug)
             ->countAllResults();
     }

     public function dt_undangan($slug)
     {
         return $this->table('tb_undangan')
             ->where('ud_slug', $slug)
             ->get()->getRow();
     }

}
