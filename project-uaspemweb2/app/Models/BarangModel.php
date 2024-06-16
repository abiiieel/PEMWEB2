<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'brgkode';
    protected $allowedFields    = ['brgkode', 'brgnama', 'brgkatid', 'brgsatid', 'brgharga', 'brggmbar', 'brgstok'];

    public function tampildata()
    {
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid');
    }

    public function tampildata_cari($cari)
    {
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->orlike('brgkode', $cari)->orlike('brgnama', $cari)->orlike('katnama', $cari);
    }
}
