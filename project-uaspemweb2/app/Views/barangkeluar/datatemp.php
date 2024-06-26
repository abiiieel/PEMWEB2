<table class="table table-sm table-hover table-bordered" style="width: 100%;">
    <thead>
        <tr>
            <th colspan="2"></th>
            <th colspan="5" style="text-align: right;">
                <?php
                $totalHarga = 0;
                foreach ($tampildata->getResultArray() as $row) :
                    $totalHarga += $row['detsubtotal'];
                endforeach;
                ?>
                <h1><b>Total Harga : <?= number_format($totalHarga, 0, ",", ".") ?></b></h1>
                <input type="hidden" id="totalharga" value="<?= $totalHarga; ?>">
            </th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Jual</th>
            <th>Jumlah</th>
            <th>Sub.Total</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata->getResultArray() as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['detbrgkode']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td style="text-align: right;"><?= number_format($row['dethargajual'], 0, ",", ".") ?></td>
                <td style="text-align: center;"><?= number_format($row['detjumlah'], 0, ",", ".") ?></td>
                <td style="text-align: right;"><?= number_format($row['detsubtotal'], 0, ",", ".") ?></td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem('<?= $row['id'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    function hapusItem(id) {
        Swal.fire({
            title: "Hapus Item",
            text: "Apakah anda yakin ingin menghapus item ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/barangkeluar/hapusItem",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire('Berhasil', response.sukses, 'success');
                        tampilDataTemp();
                        kosong();
                    }
                });
            }
        });
    }
</script>