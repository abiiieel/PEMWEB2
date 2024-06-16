<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailbarangkeluarModel extends Model
{
    protected $table            = 'detail_barangkeluar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['detfaktur', 'detbrgkode', 'dethargajual', 'detjumlah', 'detsubtotal'];

    public function tampilDataTemp($nofaktur)
    {
        return $this->table('detail_barangkeluar')->join('barang', 'detbrgkode=brgkode')->join('satuan', 'brgsatid = satid')->where('detfaktur', $nofaktur)->get();
    }
}
