<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Detail Buku</h1>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/img/<?= $buku['sampul']; ?>" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $buku['judul']; ?></h5>
                            <p class="card-text">Penulis : <b><?= $buku['penulis']; ?></b></p>
                            <p class="card-text"><small class="text-body-secondary">Penerbit : <b><?= $buku['penerbit']; ?></b></small></p>

                            <a href="" class="btn btn-warning">Edit</a>
                            <a href="" class="btn btn-danger">Hapus</a>
                            <br><br>
                            <a href="/books">Kembali ke Daftar Buku</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>