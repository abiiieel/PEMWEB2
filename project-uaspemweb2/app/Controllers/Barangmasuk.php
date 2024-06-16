<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\TempbarangmasukModel;
use App\Models\BarangmasukModel;
use App\Models\DetailbarangmasukModel;

class Barangmasuk extends BaseController
{
    public function index()
    {
        return view('barangmasuk/forminput');
    }

    function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelTemp = new TempbarangmasukModel();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/datatemp', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new BarangModel();
            $ambilData = $modelBarang->find($kodebarang);

            if ($ambilData == NULL) {
                $json = [
                    'error' => 'Data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $ambilData['brgnama'],
                    'hargajual' => $ambilData['brgharga']
                ];

                $json = [
                    'sukses' => $data
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelTempBarang = new TempbarangmasukModel();
            $modelTempBarang->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjumlah' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli)
            ]);

            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $modelTempBarang = new TempbarangmasukModel();
            $modelTempBarang->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function cariDataBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangmasuk/modalcaribarang')
            ];
            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function detailCariBarang()
    {
        if ($this->request->isAJAX()) {
            $cari = $this->request->getPost('cari');

            $modelBarang = new BarangModel();
            $data = $modelBarang->tampildata_cari($cari)->get();

            if ($data != NULL) {
                $json = [
                    'data' => view('barangmasuk/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];

                echo json_encode($json);
            }
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function selesaiTransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $tglfaktur = $this->request->getPost('tglfaktur');

            $modelTemp = new TempbarangmasukModel();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $faktur]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk faktur ini belum tersedia'
                ];
            } else {
                // menyimpan ke tabel barang masuk
                $modelBarangMasuk = new BarangmasukModel();
                $totalSubTotal = 0;

                foreach ($dataTemp->getResultArray() as $total) :
                    $totalSubTotal += intval($total['detsubtotal']);
                endforeach;

                $modelBarangMasuk->insert([
                    'faktur' => $faktur,
                    'tglfaktur' => $tglfaktur,
                    'totalharga' => $totalSubTotal
                ]);

                //menyimpan ke tabel detail barang masuk
                $modelDetailBarangMasuk = new DetailbarangmasukModel();
                foreach ($dataTemp->getResultArray() as $row) :
                    $modelDetailBarangMasuk->insert([
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'dethargamasuk' => $row['dethargamasuk'],
                        'dethargajual' => $row['dethargajual'],
                        'detjumlah' => $row['detjumlah'],
                        'detsubtotal' => $row['detsubtotal'],
                    ]);
                endforeach;

                //menghapus data di tabel temp
                $modelTemp->emptyTable();

                $json = [
                    'sukses' => 'Transaksi berhasil disimpan'
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    public function data()
    {
        $tombolcari = $this->request->getPost('tombolcari');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_faktur', $cari);
            redirect()->to('/barangmasuk/data');
        } else {
            $cari = session()->get('cari_faktur');
        }
        $modelBarangMasuk = new BarangmasukModel();

        $totaldata = $cari ? $modelBarangMasuk->tampildata_cari($cari)->countAllResults() : $modelBarangMasuk->countAllResults();


        $databarangmasuk = $cari ? $modelBarangMasuk->tampildata_cari($cari)->paginate(10, 'barangmasuk') : $modelBarangMasuk->paginate(10, 'barangmasuk');

        $nohalaman = $this->request->getVar('page_barangmasuk') ? $this->request->getVar('page_barangmasuk') : 1;

        $data = [
            'tampildata' => $databarangmasuk,
            'pager' => $modelBarangMasuk->pager,
            'nohalaman' => $nohalaman,
            'totaldata' => $totaldata,
            'cari' => $cari
        ];

        return view('barangmasuk/v_data', $data);
    }

    function detailItem()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new DetailbarangmasukModel();

            $data = [
                'tampildatadetail' => $modelDetail->dataDetail($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/modaldetailitem', $data)
            ];
            echo json_encode($json);
        }
    }

    function edit($faktur)
    {
        $modelBarangMasuk = new BarangmasukModel();
        $cekFaktur = $modelBarangMasuk->cekFaktur($faktur);

        if ($cekFaktur->getNumRows() > 0) {
            $row = $cekFaktur->getRowArray();

            $data = [
                'nofaktur' => $row['faktur'],
                'tanggal' => $row['tglfaktur']
            ];
            return view('/barangmasuk/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    function dataDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new DetailbarangmasukModel();

            $data = [
                'datadetail' => $modelDetail->dataDetail($faktur)
            ];

            $totalHargaFaktur = number_format($modelDetail->ambilTotalHarga($faktur), 0, ",", ".");
            $json = [
                'data' => view('barangmasuk/datadetail', $data),
                'totalharga' => $totalHargaFaktur
            ];
            echo json_encode($json);
        }
    }

    function editItem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');

            $modelDetail = new DetailbarangmasukModel();
            $ambilData = $modelDetail->ambilDetailID($iddetail);

            $row = $ambilData->getRowArray();

            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'hargajual' => $row['dethargajual'],
                'hargabeli' => $row['dethargamasuk'],
                'jumlah' => $row['detjumlah'],
            ];

            $json = [
                'sukses' => $data
            ];
            echo json_encode($json);
        }
    }

    function simpanDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetail = new DetailbarangmasukModel();
            $modelBarangMasuk = new BarangmasukModel();
            $modelDetail->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjumlah' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli)
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf data tidak ada');
        }
    }

    function updateItem()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');
            $iddetail = $this->request->getPost('iddetail');

            $modelDetail = new DetailbarangmasukModel();
            $modelBarangMasuk = new BarangmasukModel();
            $modelDetail->update($iddetail, [
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjumlah' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli)
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);


            $json = [
                'sukses' => 'Item berhasil diupdate'
            ];
            echo json_encode($json);
        }
    }

    function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new DetailbarangmasukModel();
            $modelBarangMasuk = new BarangmasukModel();

            $modelDetail->delete($id);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    public function hapusTransaksi()
    {
        $faktur = $this->request->getPost('faktur');

        $db = \Config\Database::connect();

        $modelBarangMasuk = new BarangmasukModel();

        $db->table('detail_barangmasuk')->delete(['detfaktur' => $faktur]);
        $modelBarangMasuk->delete($faktur);

        $json = [
            'sukses' => "Transaksi dengan Faktur : $faktur, berhasil dihapus"
        ];

        echo json_encode($json);
    }
}
