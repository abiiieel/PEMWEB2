<?php

namespace App\Models;

use CodeIgniter\Model;

class PenulisModel extends Model
{
    protected $table            = 'penulis';
    protected $allowedFields    = ['name', 'address'];
    protected $useTimestamps = TRUE;
    
    public function search($kataKunci)
    {
        return $this->table('penulis')->like('name', $kataKunci)->orLike('address', $kataKunci);
    }
}
