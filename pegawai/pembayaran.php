<?php
include '../koneksi.php';
if (isset($_SESSION['login'])) {
    $querysetting = "SELECT * FROM setting_pembayaran";
    $execsetting = mysqli_query($conn, $querysetting);
    $datasetting = mysqli_fetch_array($execsetting);
    $tanggal = date("Y-m-d");
    $bulan = date("m");
    $queryselectinformasi = "SELECT * FROM informasi WHERE status = 1";
    $execinformasi = mysqli_query($conn, $queryselectinformasi);
    $datainformasi = mysqli_fetch_all($execinformasi, MYSQLI_ASSOC);
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $querycarianggota = "SELECT * FROM anggota LEFT JOIN pembayaran ON pembayaran.kode_rumah = anggota.kode_rumah WHERE anggota.nama_anggota = '$keyword' OR anggota.kode_rumah = '$keyword' OR anggota.nomer_anggota = '$keyword'";
        $execcarianggota = mysqli_query($conn, $querycarianggota);
        $data = mysqli_fetch_all($execcarianggota, MYSQLI_ASSOC);
    } else {
        $querycarianggota = "SELECT * FROM anggota WHERE anggota.bulan != $bulan";
        $execcarianggota = mysqli_query($conn, $querycarianggota);
        $data = mysqli_fetch_all($execcarianggota, MYSQLI_ASSOC);
    }
    if (isset($_GET['laporan'])) {
        // var_dump($_POST['filter']);
        if (isset($_POST['filter'])) {
            $bulanawal = $_POST['bulan-awal'];
            $bulanakhir = $_POST['bulan-akhir'];
            $queryanggotasudahbayar = "SELECT * FROM anggota JOIN pembayaran ON anggota.kode_rumah = pembayaran.kode_rumah WHERE MONTH(pembayaran.tanggal_bayar) BETWEEN $bulanawal AND $bulanakhir";
            $queryhitungpendapatan = "SELECT sum(total_bayar) as pendapatan FROM pembayaran WHERE MONTH(pembayaran.tanggal_bayar) BETWEEN $bulanawal AND $bulanakhir";
            // var_dump($queryanggotasudahbayar);
        } else {
            $queryanggotasudahbayar = "SELECT * FROM anggota JOIN pembayaran ON anggota.kode_rumah = pembayaran.kode_rumah WHERE anggota.bulan = $bulan AND MONTH(pembayaran.tanggal_bayar) = $bulan";
            $queryhitungpendapatan = "SELECT sum(total_bayar) as pendapatan FROM pembayaran WHERE MONTH(tanggal_bayar) = $bulan";
            // var_dump($queryanggotasudahbayar);
        }
        $execanggotasudahbayar = mysqli_query($conn, $queryanggotasudahbayar);
        $exechitungpendapatan = mysqli_query($conn, $queryhitungpendapatan);
        $pendapatan = mysqli_fetch_array($exechitungpendapatan, MYSQLI_ASSOC);
        $datasudahbayar = mysqli_fetch_all($execanggotasudahbayar, MYSQLI_ASSOC);
        $print = 'yes';
    }
    if (isset($_GET['pembayaran'])) {
        $koderumah = $_GET['pembayaran'];
        $querycektransaksi = "SELECT * FROM anggota WHERE kode_rumah = '$koderumah'";
        $execcektransaksi = mysqli_query($conn, $querycektransaksi);
        $dataanggota = mysqli_fetch_array($execcektransaksi, MYSQLI_ASSOC);

        if (isset($_POST['kalkulasi'])) {
            $_SESSION['meteran-awal'] = $_POST['meteran-awal'];
            $_SESSION['meteran-akhir'] = $_POST['meteran-akhir'];
            $_SESSION['pemakaian-air'] = $_POST['meteran-akhir'] - $_POST['meteran-awal'];
            $querysetting = "SELECT * FROM setting_pembayaran";
            $execsetting = mysqli_query($conn, $querysetting);
            $datasetting = mysqli_fetch_array($execsetting);
            $_SESSION['total-biaya'] =  $_SESSION['pemakaian-air'] * $datasetting['biaya_beban'] + $datasetting['PPN'];
        }
        if (isset($_POST['selesaikan'])) {
            $koderumah = $_GET['pembayaran'];
            // var_dump($_FILES['foto-meteran']);
            // die();
            $filename = $_FILES['foto-meteran']['name'];
            $filesize = $_FILES['foto-meteran']['size'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $xx = "Pembayaran-" . $koderumah . "." . $ext;
            move_uploaded_file($_FILES['foto-meteran']['tmp_name'], "../assets/foto-meteran/" . $xx);
            $querypembayaran = "INSERT INTO pembayaran VALUES (null, '$koderumah', '" . $_SESSION['meteran-awal'] . "','" . $_SESSION['meteran-akhir'] . "', '" . $_SESSION['pemakaian-air'] . "', '" . $xx . "','" . $_SESSION['total-biaya'] . "', '$tanggal')";
            // var_dump($querypembayaran);
            // die();
            $execpembayaran = mysqli_query($conn, $querypembayaran);
            if ($execpembayaran) {
                $queryupdatemeterananggota = "UPDATE anggota SET meteran_terakhir = '" . $_SESSION['meteran-akhir'] . "',meteran_bulanlalu = '" . $_SESSION['meteran-awal'] . "', bulan = $bulan  WHERE kode_rumah = '$koderumah'";
                // var_dump($queryupdatemeterananggota);
                $execupdatemeteran = mysqli_query($conn, $queryupdatemeterananggota);
                if ($execupdatemeteran) {
                    unset($_SESSION['meteran-awal']);
                    unset($_SESSION['meteran-akhir']);
                    unset($_SESSION['pemakaian-air']);
                    unset($_SESSION['total-biaya']);
                    $selesai = '1';
                }
            } else {
                header("location:?pembayaran=$koderumah&pesan=gagal");
            }
        }
    }
    if (isset($_GET['delete'])) {
        $koderumah = $_GET['delete'];
        $bulan = date('m') - 1;
        if (mysqli_query($conn, "DELETE FROM pembayaran WHERE kode_rumah = '$koderumah'")) {
            if (mysqli_query($conn, "UPDATE anggota SET bulan = $bulan WHERE kode_rumah = '$koderumah'")) {
                header("location:pembayaran.php");
            } else {
                header("location:pembayaran.php?pesan=gagal");
            }
        }
    }
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pencarian Data Anggota</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <style type="text/css">
            body {
                height: 95vh;
                background-image: linear-gradient(#F8F9FA, #fff);
                background-size: auto;
                background-repeat: no-repeat;
            }

            @media print {

                .navbar,
                .aksi,
                div.sticky-bottom,
                marquee {
                    display: none;
                }
            }
        </style>
    </head>

    <body>
        <?php include '../icon.html'; ?>
        <nav class="navbar sticky-top navbar-expand-sm bg-light">
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
                                <ul class="dropdown-menu">
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
            <?php if (isset($_GET['laporan'])) { ?>
                <div class="container-sm mx-auto">
                    <div class="aksi">
                        <div class="mt-5">
                            <form action="" method="POST">
                                <div class="row">
                                    <div class="col-2">
                                        <select class="form-select" name="bulan-awal" aria-label="Default select example">
                                            <option selected>Bulan Awal</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select class="form-select" name="bulan-akhir" aria-label="Default select example">
                                            <option selected>Bulan Akhir</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <input type="submit" class="btn btn-success" name="filter" value="Filter Laporan">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="container-sm mx-auto">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h5 class="d-inline-block">Anggota Yang Sudah Melakukan Pembayaran</h5>
                            <!-- <a href="../anggota"><span class="btn btn-secondary p-2 m-1 col-1">Back</span></a> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped caption-top">
                                    <caption>Total Pendapatan : Rp.<?= number_format($pendapatan['pendapatan'], 0, ',', '.') ?></caption>
                                    <thead>
                                        <tr>
                                            <th scope=" col">No</th>
                                            <th scope="col">Nama Anggota</th>
                                            <th scope="col">Nomer Anggota</th>
                                            <th scope="col">Kode Rumah</th>
                                            <th scope="col">Meteran Bulan Lalu</th>
                                            <th scope="col">Meteran Bulan Ini</th>
                                            <th scope="col">Pembayaran Terakhir</th>
                                            <th scope="col" class="aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?Php $no = 0 ?>
                                        <?php if (count($datasudahbayar) > 0) { ?>
                                            <?php foreach ($datasudahbayar as $a) : ?>
                                                <?php $no++;
                                                //var_dump($datasudahbayar); 
                                                ?>
                                                <tr>
                                                    <th scope=" row"><?= $no ?></th>
                                                    <td><?= $a['nama_anggota'] ?></td>
                                                    <td><?= $a['nomer_anggota'] ?></td>
                                                    <td><?= $a['kode_rumah'] ?></td>
                                                    <td><?= $a['meteran_bulanlalu'] ?></td>
                                                    <td><?= $a['meteran_terakhir'] ?></td>
                                                    <td><?= $a['total_bayar'] ?></td>
                                                    <td class="aksi">
                                                        <div class="d-flex">
                                                            <a class="btn btn-info col-5 mx-1 fw-lighter" href="#" data-bs-toggle="modal" data-bs-target="#modalfotometeran<?= $a['kode_rumah'] ?>">Buka</a>
                                                            <?php if ($a['bulan'] == date('m')) { ?>
                                                                <a class="btn btn-warning col-5 mx-1 fw-lighter" href="?delete=<?= $a['kode_rumah'] ?>">Hapus</a>
                                                            <?php } else { ?>
                                                                <a class="btn btn-warning col-5 mx-1 fw-lighter" href="?pembayaran=<?= $a['kode_rumah'] ?>">Belum Bayar</a>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="modalfotometeran<?= $a['kode_rumah'] ?>" tabindex="-1" aria-labelledby="modalfotometeran" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <!-- <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div> -->
                                                            <div class="modal-body">
                                                                <img src="../assets/foto-meteran/<?= $a['foto-meteran'] ?>" class="img-fluid" alt="">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_GET['pembayaran'])) { ?>
                <div class="col-12 mt-5">
                    <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
                        <div class="col-md-8 mx-auto">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <a class="text-decoration-none d-block my-2 text-end" href="../pegawai/">Back</a>
                                    <div class="row shadow-sm py-3">
                                        <div class="col-sm-6">
                                            <h6 class="mb-0">Kode Rumah</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?= $dataanggota['kode_rumah'] ?>
                                        </div>
                                    </div>
                                    <div class="row shadow-sm py-3">
                                        <div class="col-sm-6">
                                            <h6 class="mb-0">Nama Anggota</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?= $dataanggota['nama_anggota'] ?>
                                        </div>
                                    </div>
                                    <div class="row shadow-sm py-3">
                                        <div class="col-sm-6">
                                            <h6 class="mb-0">Nomer Anggota</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?= $dataanggota['nomer_anggota'] ?>
                                        </div>
                                    </div>
                                    <div class="row shadow-sm py-3">
                                        <div class="col-sm-6">
                                            <h6 class="mb-0">Nomer Rukun Tetangga</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?= $dataanggota['rt'] ?>
                                        </div>
                                    </div>
                                    <div class="row shadow-sm py-3">
                                        <div class="col-sm-6">
                                            <h6 class="mb-0">Jenis Kelamin</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?php if ($dataanggota['jenis_kelamin'] == 'lk') { ?>
                                                Laki Laki
                                            <?php } else { ?>
                                                Perempuan
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <a target="blank" href="../cetak.php?kode=<?= $koderumah ?>" class="btn btn-warning col-12 <?= (isset($selesai)) ? '' : 'd-none' ?>">Cetak</a>
                                    <div class="col-12 mt-4 <?= (isset($selesai)) ? 'collapse' : '' ?>">
                                        <form action="" method="post">
                                            <div class="d-flex justify-content-center mb-3">
                                                <div class="col-5 mx-2">
                                                    <label for="meteranawal" class="form-label">Meteran Awal</label>
                                                    <input type="number" class="form-control" id="meteranawal" value="<?= $dataanggota['meteran_terakhir'] ?>" name="meteran-awal" readonly>
                                                </div>
                                                <div class="col-5 mx-2">
                                                    <label for="exampleFormControlInput1" class="form-label">Meteran Akhir</label>
                                                    <input required type="number" class="form-control" id="exampleFormControlInput1" value="<?= (isset($_SESSION['meteran-akhir'])) ? $_SESSION['meteran-akhir'] : ''; ?>" min="<?= $dataanggota['meteran_terakhir'] ?>" name="meteran-akhir" <?= (isset($_SESSION['total-biaya'])) ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="submit" class="btn btn-success col-12 <?= (isset($_SESSION['total-biaya'])) ? 'd-none' : '' ?>" name="kalkulasi" value="Kalkulasi Biaya">
                                        </form>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="d-flex justify-content-center mb-3">
                                                <div class="col-5 mx-2">
                                                    <label for="exampleFormControlInput1" class="form-label">Pemakaian Air</label>
                                                    <input type="number" class="form-control" id="exampleFormControlInput1" value="<?= (isset($_SESSION['pemakaian-air'])) ? $_SESSION['pemakaian-air'] : ''; ?>" name="pemakaian-air" readonly>
                                                </div>
                                                <div class="col-5 mx-2">
                                                    <label for="exampleFormControlInput1" class="form-label">Total Biaya</label>
                                                    <input type="text" class="form-control" id="exampleFormControlInput1" value="<?= (isset($_SESSION['total-biaya'])) ? number_format($_SESSION['total-biaya'], 2, ',', '.') : ''; ?>" name="total-biaya" readonly>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="foto-meteran" class="form-label">Foto Meteran</label>
                                                <input class="form-control" type="file" id="foto-meteran" name="foto-meteran">
                                            </div>
                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2" aria-describedby="keterangan"></textarea>
                                                <div id="keterangan" class="form-text">keterangan untuk transaksi ini</div>
                                            </div>
                                            <input type="submit" class="btn btn-warning col-12 <?= (isset($selesai)) ? 'd-none' : '' ?>" name="selesaikan" value="Selesaikan Pembayaran">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!isset($_GET['pembayaran']) && !isset($_GET['laporan'])) { ?>
                <div class="col-12 mt-5">
                    <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
                        <div class="text-center mb-4">
                            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
                        </div>
                        <form method="GET" action="" class="mx-auto col-8" autocomplete="off">
                            <div class="mb-3">
                                <label for="keyword" class="form-label">Masukan Kata Kunci</label>
                                <input required type="text" name="keyword" class="form-control" id="keyword" aria-describedby="keyword">
                                <div id="keyword" class="form-text">Kata kunci bisa menggunakan Kode Rumah</div>
                            </div>
                            <input type="submit" class="btn btn-primary" name="keysearch" value="Cari">
                        </form>
                    </div>
                </div>
                <div class="container-sm mx-auto">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h5 class="d-inline-block">Anggota Yang Belum Melakukan Pembayaran</h5>
                            <!-- <a href="../anggota"><span class="btn btn-secondary p-2 m-1 col-1">Back</span></a> -->
                        </div>
                        <div class="card-body h-25">
                            <div class="table-responsive">
                                <table class="table table-striped" style="overflow-y:scroll;">
                                    <thead>
                                        <tr>
                                            <th scope=" col">#</th>
                                            <th scope="col">Nama Anggota</th>
                                            <th scope="col">Nomer Anggota</th>
                                            <th scope="col">Kode Rumah</th>
                                            <th scope="col">Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($data) > 0) { ?>
                                            <?php foreach ($data as $a) : ?>
                                                <tr>
                                                    <th scope=" row">1</th>
                                                    <td><?= $a['nama_anggota'] ?></td>
                                                    <td><?= $a['nomer_anggota'] ?></td>
                                                    <td><?= $a['kode_rumah'] ?></td>
                                                    <td>
                                                        <?php if (isset($data[0]['id_pembayaran'])) { ?>
                                                            <a class="btn btn-info col-12" href="?cek=<?= $a['kode_rumah'] ?>">Cek</a>
                                                        <?php } else { ?>
                                                            <a class="btn btn-warning col-12" href="?pembayaran=<?= $a['kode_rumah'] ?>">Bayar</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- footer -->
            <div class="container">
                <div class="sticky-bottom">
                    <footer class="d-flex flex-wrap justify-content-center align-items-center p-4 m-1">
                        <div class="col-12 col-md-4 mt-2 mt-md-2 d-flex justify-content-center justify-content-md-start align-items-center">
                            <a href="https://github.com/ArRahmaan17/" class="text-muted text-decoration-none">
                                <svg class="bi" width="30" height="24">
                                    <use xlink:href="#github" />
                                </svg>
                            </a>
                        </div>
                        <div class="text-center col-md-4 col-sm-12 mt-2 mt-md-2">
                            &#169; KSM Daya Tirta
                        </div>
                        <ul class="nav col-12 col-md-4 mt-2 mt-md-2 justify-content-center justify-content-md-end list-unstyled d-flex">
                            <li class="ms-3">
                                <a class="mx-2 mb-md-0 text-muted" href="#">
                                    <svg class="bi" width="24" height="24">
                                        <use xlink:href="#twitter" />
                                    </svg>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="mx-2 mb-md-0 text-muted" href="#"><svg class="bi" width="24" height="24">
                                        <use xlink:href="#instagram" />
                                    </svg>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="mx-2 mb-md-0 text-muted" href="#"><svg class="bi" width="24" height="24">
                                        <use xlink:href="#facebook" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </footer>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        </div>
        <?php if (isset($print)) { ?>
            <script>
                $(document).ready(function() {
                    window.print();
                })
            </script>
        <?php } ?>
    </body>

    </html>
<?php } else {
    header("location:../pegawai");
} ?>