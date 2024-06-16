<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Input Barang Masuk
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>

<button type="button" class="btn btn-sm btn-warning" onclick="location.href=('/barangmasuk/data')">
    <i class="fa fa-backward"> Kembali</i>
</button>

<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="">Input Faktur Barang Masuk</label>
        <input type="text" class="form-control" placeholder="No. Faktur" name="faktur" id="faktur">
    </div>
    <div class="form-group col-md-6">
        <label for="">Tanggal Faktur</label>
        <input type="date" class="form-control" name="tglfaktur" id="tglfaktur" value="<?= date('Y-m-d') ?>">
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary">
        Input Barang
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="">Kode Barang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Kode barang" name="kdbarang" id="kdbarang">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="tombolCariBarang">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="">Nama Barang</label>
                <input type="text" class="form-control" name="namabarang" id="namabarang" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Harga Jual</label>
                <input type="text" class="form-control" name="hargajual" id="hargajual" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Harga Beli</label>
                <input type="number" class="form-control" name="hargabeli" id="hargabeli">
            </div>
            <div class="form-group col-md-1">
                <label for="">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah">
            </div>
            <div class="form-group col-md-1">
                <label for="">Aksi</label>
                <div class="input-group">
                    <button type="button" class="btn btn-sm btn-info" title="Tambah Item" id="tombolTambahItem">
                        <i class="fa fa-plus-square"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-sm btn-warning" title="Reload Data" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row" id="tampilDataTemp"></div>
        <div class="row justify-content-end">
            <button type="button" class="btn btn-success" id="tombolSelesaiTransaksi" name="tombolSelesaiTransaksi">
                <i class="fa fa-save"></i> Selesai Transaksi
            </button>
        </div>
    </div>
</div>
<div class="modalcaribarang" style="display: none;"></div>
<script>
    function dataTemp() {
        let faktur = $('#faktur').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/dataTemp",
            data: {
                faktur: faktur
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#tampilDataTemp').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function kosong() {
        $('#kdbarang').val('');
        $('#namabarang').val('');
        $('#hargajual').val('');
        $('#hargabeli').val('');
        $('#jumlah').val('');
        $('#kdbarang').focus();
    }

    function ambilDataBarang() {
        let kodebarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/ambilDataBarang",
            data: {
                kodebarang: kodebarang
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#hargajual').val(data.hargajual);
                    $('#hargabeli').focus();
                }

                if (response.error) {
                    kosong();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.error
                    });
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    $(document).ready(function() {
        dataTemp();

        $('#kdbarang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                ambilDataBarang();
            }
        });

        $('#tombolTambahItem').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();
            let kodebarang = $('#kdbarang').val();
            let hargajual = $('#hargajual').val();
            let hargabeli = $('#hargabeli').val();
            let jumlah = $('#jumlah').val();

            if (faktur.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Maaf, Faktur tidak boleh kosong"
                });
            } else if (kodebarang.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Maaf, Kode barang tidak boleh kosong"
                });
            } else if (hargabeli.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Maaf, Harga beli tidak boleh kosong"
                });
            } else if (jumlah.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Maaf, Jumlah tidak boleh kosong"
                });
            } else {
                $.ajax({
                    type: "post",
                    url: "/barangmasuk/simpanTemp",
                    data: {
                        faktur: faktur,
                        kodebarang: kodebarang,
                        hargajual: hargajual,
                        hargabeli: hargabeli,
                        jumlah: jumlah
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            kosong();
                            dataTemp();
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil",
                                text: response.sukses,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }
                });
            }
        });

        $('#tombolReload').click(function(e) {
            e.preventDefault();
            dataTemp();
        });

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/barangmasuk/cariDataBarang",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.modalcaribarang').html(response.data).show();
                        $('#modalcaribarang').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });

        $('#tombolSelesaiTransaksi').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();

            if (faktur.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Pesan",
                    text: "Maaf, Faktur tidak boleh kosong",
                });
            } else {
                Swal.fire({
                    title: "Selesai Transaksi",
                    text: "Apakah anda yakin ingin menyimpan transaksi?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Simpan"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "post",
                            url: "/barangmasuk/selesaiTransaksi",
                            data: {
                                faktur: faktur,
                                tglfaktur: $('#tglfaktur').val()
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.error) {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: response.error
                                    });
                                }

                                if (response.sukses) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Berhasil",
                                        text: response.sukses
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    })
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + '\n' + thrownError);
                            }
                        });
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>