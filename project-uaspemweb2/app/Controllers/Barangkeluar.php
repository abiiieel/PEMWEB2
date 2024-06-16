<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangkeluarModel;
use App\Models\BarangModel;
use App\Models\DatabarangkeluarModel;
use App\Models\DatabarangModel;
use App\Models\DetailbarangkeluarModel;
use App\Models\PelangganModel;
use App\Models\TempbarangkeluarModel;
use Config\Services;
use App\Models\TempbarangmasukModel;
use CodeIgniter\HTTP\ResponseInterface;

class Barangkeluar extends BaseController
{
    private function buatFaktur()
    {
        $tanggalSekarang = date('Y-m-d');
        $modelBarangKeluar = new BarangkeluarModel();

        $hasil = $modelBarangKeluar->noFaktur($tanggalSekarang)->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = substr($data, -4);
        //nomor urut ditambah 1
        $nextNoUrut = intval($lastNoUrut) + 1;
        //membuat format nomor transaksi berikutnya
        $noFaktur = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        return $noFaktur;
    }
    public function data()
    {
        return view('barangkeluar/v_data');
    }

    public function buatNoFaktur()
    {
        $tanggalSekarang = $this->request->getPost('tanggal');
        $modelBarangKeluar = new BarangkeluarModel();

        $hasil = $modelBarangKeluar->noFaktur($tanggalSekarang)->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = intval($data, -4);
        //nomor urut ditambah 1
        $nextNoUrut = intval($lastNoUrut) + 1;
        //membuat format nomor transaksi berikutnya
        $noFaktur = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);

        $json = [
            'nofaktur' => $noFaktur
        ];
        echo json_encode($json);
    }

    public function input()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('barangkeluar/forminput', $data);
    }

    public function tampilDataTemp()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');

            $modelTempBarangKeluar = new TempbarangkeluarModel();
            $dataTemp = $modelTempBarangKeluar->tampilDataTemp($nofaktur);

            $data = [
                'tampildata' => $dataTemp
            ];

            $json = [
                'data' => view('barangkeluar/datatemp', $data)
            ];
            echo json_encode($json);
        }
    }

    function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new BarangModel();
            $cekData = $modelBarang->find($kodebarang);
            if ($cekData == null) {
                $json = [
                    'error' => 'Maaf data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $cekData['brgnama'],
                    'hargajual' => $cekData['brgharga'],
                ];
                $json = [
                    'sukses' => $data
                ];
            }
            echo json_encode($json);
        }
    }

    function simpanItem()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $kodebarang = $this->request->getPost('kodebarang');
            $namabarang = $this->request->getPost('namabarang');
            $hargajual = $this->request->getPost('hargajual');
            $jml = $this->request->getPost('jml');

            $modelTempBarangKeluar = new TempbarangkeluarModel();
            $modelBarang = new BarangModel();

            $ambilDataBarang = $modelBarang->find($kodebarang);
            $stokBarang = $ambilDataBarang['brgstok'];

            if ($jml > intval($stokBarang)) {
                $json = [
                    'error' => 'Stok barang tidak mencukupi'
                ];
            } else {
                $modelTempBarangKeluar->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'dethargajual' => $hargajual,
                    'detjumlah' => $jml,
                    'detsubtotal' => intval($jml) * intval($hargajual),
                ]);
                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }

            echo json_encode($json);
        }
    }

    function hapusItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $modelTempBarangKeluar = new TempbarangkeluarModel();
            $modelTempBarangKeluar->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    public function modalCariBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangkeluar/modalcaribarang')
            ];
            echo json_encode($json);
        }
    }

    function listDataBarang()
    {
        $request = Services::request();
        $datamodel = new DatabarangModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->brgkode . "')\">
                <i class='fa fa-sync-alt'></i>
                </button>&nbsp;";

                $row[] = $no;
                $row[] = $list->brgkode;
                $row[] = $list->brgnama;
                $row[] = number_format($list->brgharga, 0, ",", ".");
                $row[] = number_format($list->brgstok, 0, ",", ".");
                $row[] = $tombolPilih;
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

    function modalPembayaran()
    {
        $nofaktur = $this->request->getPost('nofaktur');
        $tglfaktur = $this->request->getPost('tglfaktur');
        $idpelanggan = $this->request->getPost('idpelanggan');
        $totalharga = $this->request->getPost('totalharga');

        $modelTemp = new TempbarangkeluarModel();
        $cekData = $modelTemp->tampilDataTemp($nofaktur);

        if ($cekData->getNumRows() > 0) {
            $data = [
                'nofaktur' => $nofaktur,
                'tglfaktur' => $tglfaktur,
                'idpelanggan' => $idpelanggan,
                'totalharga' => $totalharga,
            ];
            $json = [
                'data' => view('barangkeluar/modalpembayaran', $data)
            ];
        } else {
            $json = [
                'error' => 'Maaf item belum diisi'
            ];
        }
        echo json_encode($json);
    }

    function simpanPembayaran()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $idpelanggan = $this->request->getPost('idpelanggan');
            $totalbayar = str_replace(".", "", $this->request->getPost('totalbayar'));
            $jumlahuang = str_replace(".", "", $this->request->getPost('jumlahuang'));
            $sisauang = str_replace(".", "", $this->request->getPost('sisauang'));

            $modelBarangKeluar = new BarangkeluarModel();
            //simpan ke table barang keluar
            $modelBarangKeluar->insert([
                'faktur' => $nofaktur,
                'tglfaktur' => $tglfaktur,
                'idpel' => $idpelanggan,
                'totalharga' => $totalbayar,
                'jumlahuang' => $jumlahuang,
                'sisauang' => $sisauang,
            ]);

            $modelTemp = new TempbarangkeluarModel();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $nofaktur]);

            $fieldDetail = [];
            foreach ($dataTemp->getResultArray() as $row) {
                $fieldDetail[] = [
                    'detfaktur' => $row['detfaktur'],
                    'detbrgkode' => $row['detbrgkode'],
                    'dethargajual' => $row['dethargajual'],
                    'detjumlah' => $row['detjumlah'],
                    'detsubtotal' => $row['detsubtotal']
                ];
            }

            $modelDetail = new DetailbarangkeluarModel();
            $modelDetail->insertBatch($fieldDetail);

            //menghapus temp
            $modelTemp->hapusData($nofaktur);

            $json = [
                'sukses' => 'Transaksi berhasil disimpan',
                'cetakfaktur' => site_url('barangkeluar/cetakfaktur/' . $nofaktur)
            ];

            echo json_encode($json);
        }
    }

    public function cetakfaktur($faktur)
    {
        $modelBarangKeluar = new BarangkeluarModel();
        $modelDetail = new DetailbarangkeluarModel();
        $modelPelanggan = new PelangganModel();

        $cekData = $modelBarangKeluar->find($faktur);
        $dataPelanggan = $modelPelanggan->find($cekData['idpel']);

        $namaPelanggan = ($dataPelanggan != null) ? $dataPelanggan['pelnama'] : '-';

        if ($cekData != null) {
            $data = [
                'faktur' => $faktur,
                'tanggal' => $cekData['tglfaktur'],
                'namapelanggan' => $namaPelanggan,
                'detailbarang' => $modelDetail->tampilDataTemp($faktur),
                'jumlahuang' => $cekData['jumlahuang'],
                'sisauang' => $cekData['sisauang'],
            ];
            return view('barangkeluar/cetakfaktur', $data);
        } else {
            return redirect()->to(site_url('barangkeluar/input'));
        }
    }

    function listData()
    {
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');
        $request = Services::request();
        $datamodel = new DatabarangkeluarModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($tglawal, $tglakhir);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolCetak = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"cetak('" . $list->faktur . "')\">
                <i class='fa fa-print'></i>
                </button>&nbsp;";
                $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->faktur . "')\">
                <i class='fa fa-trash-alt'></i>
                </button>";

                $row[] = $no;
                $row[] = $list->faktur;
                $row[] = $list->tglfaktur;
                $row[] = $list->pelnama;
                $row[] = number_format($list->totalharga, 0, ",", ".");
                $row[] = $tombolCetak . "" . $tombolHapus;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    function hapustransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelBarangKeluar = new BarangkeluarModel();

            $db = \Config\Database::connect();
            $db->table('detail_barangkeluar')->delete(['detfaktur' => $faktur]);
            $modelBarangKeluar->delete($faktur);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }
}
