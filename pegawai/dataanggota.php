<?php

include '../koneksi.php';

$query = "SELECT * FROM anggota";
$exec = mysqli_query($conn, $query);
$data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
$queryselectinformasi = "SELECT * FROM informasi WHERE status = 1";
$execinformasi = mysqli_query($conn, $queryselectinformasi);
$datainformasi = mysqli_fetch_all($execinformasi, MYSQLI_ASSOC);
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location:../pegawai");
}
if (isset($_GET['delete'])) {
    $koderumah = $_GET['delete'];
    if (mysqli_query($conn, "DELETE FROM anggota WHERE kode_rumah = '$koderumah'")) {
        mysqli_query($conn, "DELETE FROM pembayaran WHERE kode_rumah = '$koderumah'");
        header("location:dataanggota.php");
    } else {
        header("location:dataanggota.php?pesan=gagal");
    }
}



?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pegawai KSM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style type="text/css">
        body {
            height: 95vh;
            background-image: linear-gradient(#F8F9FA, #fff);
            background-size: auto;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <?php include '../icon.html'; ?>
    <nav class="navbar sticky-top navbar-expand-sm shadow-sm bg-light">
        <div class="container">
            <a class="navbar-brand" href="../">KSM Daya Tirta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../anggota">Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pegawai">Pegawai</a>
                    </li>
                    <?php if (isset($_SESSION['login'])) : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= (isset($_SESSION['nama'])) ? $_SESSION['nama'] : 'Fitur Pegawai' ?>
                            </a>
                            <ul class="dropdown-menu ">
                                <li><a class="dropdown-item" href="../pegawai?logout">Logout</a></li>
                                <li><a class="dropdown-item" href="pembayaran.php">Pembayaran PDAM</a></li>
                                <li><a class="dropdown-item" href="../pegawai/dataanggota.php">Data Anggota</a></li>
                                <li><a class="dropdown-item" href="../pegawai/pembayaran.php?laporan">Laporan Pembayaran</a></li>
                                <li><a class="dropdown-item" href="../pegawai/informasi.php">Informasi Sistem</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../pegawai?login=show">Login</a>
                        </li>
                    <?php endif ?>
                    <li class="nav-item">
                        <a class="nav-link" href="danadesa.php">Dana Desa</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="col-12 bg-warning fw-bold py-2">
        <marquee>Berita Hari Ini <?= $datainformasi[0]['isi_informasi'] ?></marquee>
    </div>
    <div class="container-sm mx-auto">
        <div class="col-12 mt-5">
            <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
                <div class="text-center mb-4 d-flex justify-content-between">
                    <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
                    <a class="btn btn-info" href="index.php?tambahanggota">Tambah Anggota</a>
                </div>
                <div class="table-responsive col-12">
                    <table class="table text-center w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="col">Nama Anggota</th>
                                <th class="col">Nomer Anggota</th>
                                <th class="col">Alamat</th>
                                <th class="col">Jenis Kelamin</th>
                                <?php if (isset($_SESSION['login'])) : ?>
                                    <th class="col">Edit Data</th>
                                <?php endif ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($data != null) : ?>
                                <?php $no = 0 ?>
                                <?php foreach ($data as $p) { ?>
                                    <?php $no++ ?>
                                    <tr class="align-middle">
                                        <th><?= $no ?></th>
                                        <td><?= $p['nama_anggota'] ?></td>
                                        <td>
                                            <a class="text-success" target="_blank" href="https://wa.me/62<?= $p['nomer_anggota'] ?>">
                                                <svg class="bi" width="30" height="30">
                                                    <use xlink:href="#wa" />
                                                </svg>
                                            </a>
                                        </td>
                                        <td><?= $p['rt'] ?></td>
                                        <td><?= ($p['jenis_kelamin'] == 'lk') ? "Laki Laki" : "Perempuan"; ?></td>
                                        <?php if (isset($_SESSION['login'])) : ?>
                                            <td>
                                                <!-- <div class="row"> -->
                                                <a class="btn btn-danger text-truncate col my-1" href="dataanggota.php?delete=<?= $p['kode_rumah'] ?>">Delete</a>
                                                <a class="btn btn-warning text-truncate col my-1" href="../pegawai/?editanggota=<?= $p['id_anggota'] ?>">Edit</a>
                                                <a class="btn btn-success text-truncate col my-1" target="blank" href="cetakqr.php?kode=<?= $p['kode_rumah'] ?>">Cetak Qr</a>
                                                <!-- </div> -->
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                <?php } ?>
                            <?php else : ?>
                                <tr>
                                    <th colspan="5" class="text-center h1">Data Tidak Dapat ditemukan</th>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>