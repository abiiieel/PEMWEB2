<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangmasukModel extends Model
{
    protected $table            = 'barangmasuk';
    protected $primaryKey       = 'faktur';
    protected $allowedFields    = ['faktur', 'tglfaktur', 'totalharga'];

    public function tampildata_cari($cari)
    {
        return $this->table('barangmasuk')->like('faktur', $cari);
    }

    public function cekFaktur($faktur)
    {
        return $this->table('barangmasuk')->getWhere([
            'sha1(faktur)' => $faktur
        ]);
    }
}
