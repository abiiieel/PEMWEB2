<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Daftar Buku</h1>
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif ?>
            <a href="/books/create" class="btn btn-primary my-3">Tambah Data Buku</a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sampul</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php $i = 1 ?>
                        <?php foreach ($buku as $b) : ?>
                            <th scope="row"><?= $i++; ?></th>
                            <td><img src="/img/<?= $b['sampul']; ?>" alt="" class="sampul"></td>
                            <td><?= $b['judul']; ?></td>
                            <td>
                                <a href="/books/<?= $b['slug']; ?>" class="btn btn-success">Detail Buku</a>
                            </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>