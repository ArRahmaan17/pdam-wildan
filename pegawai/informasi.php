<?php

include '../koneksi.php';

$query = "SELECT * FROM informasi";
$exec = mysqli_query($conn, $query);
$data = mysqli_fetch_all($exec, MYSQLI_ASSOC);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location:../pegawai");
}

if (isset($_GET['login'])) {
    if (isset($_POST['masuk'])) {
        $nama = $_POST['nama'];
        $role = $_POST['role'];
        $query = "SELECT * FROM pegawai WHERE nama_pegawai like '$nama%' AND role = '$role'";
        $exec = mysqli_query($conn, $query);
        $data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
        if (count($data) == 1) {
            $_SESSION['login'] = true;
            $_SESSION['kode'] = "KSM-" . date("d");
            $_SESSION['nama'] = $data[0]['nama_pegawai'];
            $_SESSION['role'] = $data[0]['role'];
            header("Location:../pegawai");
        } else {
            echo "<script>alert('Request Tidak Dikenali, Mohon Diulang')</script>";
        }
    }
}
if (isset($_GET['edit'])) {
    $id_informasi = $_GET['edit'];
    $queryselectinformasi = "SELECT * FROM informasi WHERE id_informasi = $id_informasi";
    $execselectinformasi = mysqli_query($conn, $queryselectinformasi);
    $datainformasi = mysqli_fetch_array($execselectinformasi, MYSQLI_ASSOC);
    if (isset($_POST['editinformasi'])) {
        // var_dump($_POST['status']);
        // die();
        $isi_informasi = $_POST['informasi'];
        $status = $_POST['status'];
        if (mysqli_query($conn, "UPDATE informasi SET isi_informasi = '$isi_informasi', status = $status WHERE id_informasi = " . $_GET['edit'])) {
            header("location:informasi.php");
        }
    }
}
if (isset($_GET['tambah'])) {
    if (isset($_POST['tambahinformasi'])) {
        $isi_informasi = $_POST['informasi'];
        $status = $_POST['status'];
        if (mysqli_query($conn, "INSERT INTO informasi VALUES (null, '$isi_informasi', $status)")) {
            header("location:informasi.php");
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style type="text/css">
        body {
            height: 95vh;
            background-image: linear-gradient(#F8F9FA, #fff);
            background-size: auto;
            background-repeat: no-repeat;
        }
    </style>
    <title>Data Informasi</title>
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
    <div class="container-sm mx-auto">
        <div class="card mt-5">
            <div class="card-header">
                <h5 class="d-inline-block">Informasi Sistem</h5>
                <!-- <a href="?tambah"><span class="btn btn-secondary p-2 m-1 col-2 text-truncate">Tambah Informasi</span></a> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">informasi Sistem</th>
                                <th scope="col">Status Informasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?Php $no = 0 ?>
                            <?php if (count($data) > 0) { ?>
                                <?php foreach ($data as $a) : ?>
                                    <?php $no++ ?>
                                    <tr>
                                        <th scope=" row"><?= $no ?></th>
                                        <td><?= $a['isi_informasi'] ?></td>
                                        <td>
                                            <?php if ($a['status'] == 1) { ?>
                                                <a class="btn btn-info disabled col-12 my-1">Aktif</a>
                                                <a class="btn btn-warning col-12 my-1" href="?edit=<?= $a['id_informasi'] ?>">Edit</a>
                                            <?php } else { ?>
                                                <a class=" btn btn-info disabled col-12 my-1">Off</a>
                                                <a class=" btn btn-warning col-12 my-1" href="?edit=<?= $a['id_informasi'] ?>">Edit</a>
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
        <?php if (isset($_GET['edit'])) { ?>
            <div class="col-12 mt-5">
                <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
                    <div class="text-center mb-4">
                        <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
                    </div>
                    <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
                        <div class="mb-3">
                            <label for="informasi" class="form-label">Isi Informasi</label>
                            <textarea class="form-control" aria-describedby="informasi" name="informasi" id="informasi" rows="10"><?= $datainformasi['isi_informasi']; ?></textarea>
                            <div id="informasi" class="form-text">Isikan Informasi Sesingkat Mungkin Namun Mudah Dimengerti</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Informasi</label>
                            <select class="form-select" name="status">
                                <option <?= ($datainformasi['status'] == 1) ? 'selected' : ''; ?> value="1">Aktif</option>
                                <option <?= ($datainformasi['status'] == 0) ? 'selected' : ''; ?> value="0">Off</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-warning" name="editinformasi" value="Edit Informasi">
                    </form>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($_GET['tambah'])) { ?>
            <div class="col-12 mt-5">
                <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
                    <div class="text-center mb-4">
                        <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
                    </div>
                    <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
                        <div class="mb-3">
                            <label for="informasi" class="form-label">Isi Informasi</label>
                            <textarea class="form-control" aria-describedby="informasi" name="informasi" id="informasi" rows="10"></textarea>
                            <div id="informasi" class="form-text">Isikan Informasi Sesingkat Mungkin Namun Mudah Dimengerti</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="status">
                                <option selected value="1">Aktif</option>
                                <option value="0">Off</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-warning" name="tambahinformasi" value="Tambah Infomasi">
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>