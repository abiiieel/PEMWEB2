<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DatapelangganModel;
use App\Models\PelangganModel;
use Config\Services;

class Pelanggan extends BaseController
{
    public function formtambah()
    {
        $json = ['data' => view('pelanggan/modaltambah')];
        echo json_encode($json);
    }

    public function simpan()
    {
        $namapelanggan = $this->request->getPost('namapel');
        $telp = $this->request->getPost('telp');

        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namapel' => [
                'rules' => 'required',
                'label' => 'Nama Pelanggan',
                'errors' => [
                    'required' => '{field} belum diisi'
                ]
            ],
            'telp' => [
                'rules' => 'required|is_unique[pelanggan.peltelp]',
                'label' => 'No.Telp/HP',
                'errors' => [
                    'required' => '{field} belum diisi',
                    'is_unique' => '{field} sudah ada',
                ]
            ]
        ]);

        if (!$valid) {
            $json = [
                'error' => [
                    'errorNamaPel' => $validation->getError('namapel'),
                    'errorTelp' => $validation->getError('telp'),
                ]
            ];
        } else {
            $modelPelanggan = new PelangganModel();

            $modelPelanggan->insert([
                'pelnama' => $namapelanggan,
                'peltelp' => $telp
            ]);

            $rowData = $modelPelanggan->ambilDataTerakhir()->getRowArray();

            $json = [
                'sukses' => 'Data pelanggan berhasil disimpan, ambil data terkahir?',
                'namapelanggan' => $rowData['pelnama'],
                'idpelanggan' => $rowData['pelid']
            ];
        }
        echo json_encode($json);
    }

    public function modalData()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('pelanggan/modaldata')
            ];
            echo json_encode($json);
        }
    }

    public function listData()
    {
        $request = Services::request();
        $datamodel = new DatapelangganModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->pelid . "','" . $list->pelnama . "')\">
                <i class='fa fa-sync-alt'></i>
                </button>&nbsp;";

                $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->pelid . "','" . $list->pelnama . "')\">
                <i class='fa fa-trash-alt'></i>
                </button>";

                $row[] = $no;
                $row[] = $list->pelnama;
                $row[] = $list->peltelp;
                $row[] = $tombolPilih . "" . $tombolHapus;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $modelPelanggan = new PelangganModel();
            $modelPelanggan->delete($id);

            $json = [
                'sukses' => 'Data pelanggan berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }
}
