<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\SatuanModel;

class Satuan extends BaseController
{
    protected $satuan;
    public function __construct()
    {
        $this->satuan = new SatuanModel();
    }

    public function index()
    {
        $tombolcari = $this->request->getPost('tombolcari');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_satuan', $cari);
            redirect()->to('/satuan/index');
        } else {
            $cari = session()->get('cari_satuan');
        }

        $dataSatuan = $cari ? $this->satuan->cariData($cari)->paginate(5, 'satuan') : $this->satuan->paginate(5, 'satuan');

        $nohalaman = $this->request->getVar('page_satuan') ? $this->request->getVar('page_satuan') : 1;
        $data = [
            'tampildata' => $dataSatuan,
            'pager' => $this->satuan->pager,
            'nohalaman' => $nohalaman,
            'cari' => $cari
        ];

        return view('satuan/v_satuan', $data);
    }

    public function formtambah()
    {
        return view('satuan/formtambah');
    }

    public function simpandata()
    {
        $namasatuan = $this->request->getVar('namasatuan');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required|is_unique[satuan.satnama]',
                'label' => 'Nama Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah tersedia'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formtambah');
        } else {
            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i>Berhasil</h5>
                Data satuan berhasil ditambahkan.
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->satuan->find($id);
        $data = [
            'id' => $id,
            'nama' => $rowData['satnama']
        ];

        return view('satuan/formedit', $data);

        if ($rowData) {
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idsatuan = $this->request->getVar('idsatuan');

        $namaLama = $this->satuan->cariData($this->request->getVar('idsatuan'));
        if ($namaLama == $this->request->getVar('idsatuan')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[kategori.katnama]';
        }

        $namasantuan = $this->request->getVar('namasatuan');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => $rule_judul,
                'label' => 'Nama Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah tersedia'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaSantuan' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formedit/' . $this->request->getVar('idsatuan'));
        } else {
            $this->satuan->update($idsatuan, [
                'katnama' => $namasantuan
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i>Berhasil</h5>
                Data satuan berhasil diupdate.
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->satuan->find($id);

        $this->satuan->delete($id);

        $pesan = [
            'sukses' => '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i>Berhasil</h5>
            Data satuan berhasil dihapus.
          </div>'
        ];

        session()->setFlashdata($pesan);
        return redirect()->to('/satuan/index');

        if ($rowData) {
        } else {
            exit('Data tidak ditemukan');
        }
    }
}
