<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PenulisModel;

class Penulis extends BaseController
{
    protected $PenulisModel;
    public function __construct()
    {
        $this->PenulisModel = new PenulisModel();
    }
    public function index()
    {
        $pageSekarang = $this->request->getVar('page_penulis') ? $this->request->getVar('page_penulis') : 1;
        
        $kataKunci = $this->request->getVar('keyword');
        if($kataKunci) {
            $penulis = $this->PenulisModel->search($kataKunci);
            } else {
                $penulis = $this->PenulisModel;
            }
                

        $data = [

            'title' => 'Daftar Penulis',
            'penulis' => $penulis->paginate(10,'penulis'),
            'pager' => $this->PenulisModel->pager,
            'pageSekarang' => $pageSekarang
        ];

        return view('penulis/index', $data);
    }


}
