<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk</title>
</head>

<body onload="window.print();">
    <table border=0; style="text-align: center; width: 100%">
        <tr>
            <td colspan="2">
                <h3 style="height: 2px;">Toko Grasir Barokah</h3>
                <h5 style="height: 3px;">Jl.Unipdu Jombang | Telp: 085858585858585</h5>
                <hr style="border: none; border-top: 2px solid #000;">
            </td>
        </tr>
        <tr style="text-align: left;">
            <td>Faktur :</td>
            <td><?= $faktur ?></td>
        </tr>
        <tr style="text-align: left;">
            <td>Tanggal :</td>
            <td><?= date('d-m-Y', strtotime($tanggal)) ?></td>
        </tr>
        <tr style="text-align: left;">
            <td>Nama Pelanggan :</td>
            <td><?= $namapelanggan ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="border: none; border-top: 1px solid #000;">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%; text-align:left; font-size:10pt;">
                    <?php
                    $totalItem = 0;
                    $jmlItem = 0;
                    $totalHarga = 0;
                    foreach ($detailbarang->getResultArray() as $row) :
                        $totalItem += $row['detjumlah'];
                        $jmlItem++;
                        $totalHarga += $row['detsubtotal'];
                    ?>
                        <tr>
                            <td colspan="3"><?= $row['brgnama']; ?></td>
                        </tr>
                        <tr>
                            <td>
                                <?= number_format($row['detjumlah'], 0, ",", ".") . ' ' . $row['satnama'] ?>
                            </td>
                            <td style="text-align: right;">
                                <?= number_format($row['dethargajual'], 0, ",", ".") ?>
                            </td>
                            <td style="text-align: right;">
                                <?= number_format($row['detsubtotal'], 0, ",", ".") ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">
                            <hr style="border: none; border-top: 1px dashed #000;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            Jml.Item : <?= number_format($jmlItem, 0, ",", ".") . ' (' . number_format($totalItem, 0, ",", ".") . ')' ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr style="border: none; border-top: 1px dashed #000;">
                        </td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Total : </td>
                        <td>
                            <?= number_format($totalHarga, 0, ",", ".") ?>
                        </td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Jml.Uang : </td>
                        <td>
                            <?= number_format($jumlahuang, 0, ",", ".") ?>
                        </td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Kembalian : </td>
                        <td>
                            <?= number_format($sisauang, 0, ",", ".") ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr style="border: none; border-top: 1px solid #000;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            Terima kasih telah berbelanja di toko kami
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>