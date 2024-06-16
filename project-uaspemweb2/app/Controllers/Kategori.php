<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\KategoriModel;



class Kategori extends BaseController
{
    protected $kategori;
    public function __construct()
    {
        $this->kategori = new KategoriModel();
    }

    public function index()
    {
        $tombolcari = $this->request->getPost('tombolcari');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_kategori', $cari);
            redirect()->to('/kategori/index');
        } else {
            $cari = session()->get('cari_kategori');
        }

        $dataKategori = $cari ? $this->kategori->cariData($cari)->paginate(5, 'kategori') : $this->kategori->paginate(5, 'kategori');

        $nohalaman = $this->request->getVar('page_kategori') ? $this->request->getVar('page_kategori') : 1;
        $data = [
            'tampildata' => $dataKategori,
            'pager' => $this->kategori->pager,
            'nohalaman' => $nohalaman,
            'cari' => $cari
        ];
        return view('kategori/v_kategori', $data);
    }

    public function formtambah()
    {
        return view('kategori/formtambah');
    }

    public function simpandata()
    {
        $namakategori = $this->request->getVar('namakategori');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namakategori' => [
                'rules' => 'required|is_unique[kategori.katnama]',
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah tersedia'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/formtambah');
        } else {
            $this->kategori->insert([
                'katnama' => $namakategori
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i>Berhasil</h5>
                Data kategori berhasil ditambahkan.
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->kategori->find($id);
        $data = [
            'id' => $id,
            'nama' => $rowData['katnama']
        ];

        return view('kategori/formedit', $data);

        if ($rowData) {
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idkategori = $this->request->getVar('idkategori');

        $namaLama = $this->kategori->cariData($this->request->getVar('idkategori'));
        if ($namaLama == $this->request->getVar('idkategori')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[kategori.katnama]';
        }

        $namakategori = $this->request->getVar('namakategori');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namakategori' => [
                'rules' => $rule_judul,
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah tersedia'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/formedit/' . $this->request->getVar('idkategori'));
        } else {
            $this->kategori->update($idkategori, [
                'katnama' => $namakategori
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i>Berhasil</h5>
                Data kategori berhasil diupdate.
              </div>'
            ];

            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->kategori->find($id);

        $this->kategori->delete($id);

        $pesan = [
            'sukses' => '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i>Berhasil</h5>
            Data kategori berhasil dihapus.
          </div>'
        ];

        session()->setFlashdata($pesan);
        return redirect()->to('/kategori/index');

        if ($rowData) {
        } else {
            exit('Data tidak ditemukan');
        }
    }
}
