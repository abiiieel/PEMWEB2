<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <div class="row">
                        <div class="col">   
                            <h1 class="mt-2">Daftar Penulis</h1>
                            <form action="" method="post">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Masukkan kata kunci" aria-label="Recipient's username" name="keyword">
                                    <button class="btn btn-primary" type="submit" name="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php $i = 1 + (10*($pageSekarang - 1  )) ?>
                    <?php foreach($penulis as $p) : ?>
                        <tr>
                            <th scope="row"><?=$i++; ?></th>
                            <td><?= $p['name'];?></td>
                            <td><?= $p['address'];?></td>
                            <td><a href="" class="btn btn-success">Detail</a></td>
                        </tr>    
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $pager->links('penulis', 'penulis_pager'); ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>