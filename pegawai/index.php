<?php

include '../koneksi.php';

$query = "SELECT * FROM Pegawai";
$exec = mysqli_query($conn, $query);
$data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
$queryselectinformasi = "SELECT * FROM informasi WHERE status = 1";
$execinformasi = mysqli_query($conn, $queryselectinformasi);
$datainformasi = mysqli_fetch_all($execinformasi, MYSQLI_ASSOC);
if (isset($_GET['delete'])) {
  if (mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai=" . $_GET['delete'])) {
    header("location:../pegawai/");
  }
}
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location:../pegawai");
}
if (isset($_POST['prosestambahpegawai'])) {
  $nama = $_POST['nama'];
  $password = $_POST['password'];
  $nomer = $_POST['nomer_pegawai'];
  $jk = $_POST['jabatan'];
  $role = $_POST['role'];
  $query = "INSERT INTO pegawai VALUES(null,'$nama','$password', '$nomer', '$jk', '$role',null)";
  var_dump($query);
  $exec = mysqli_query($conn, $query);

  if ($exec) {
    header("Location:../pegawai");
  } else {
    echo "<script>alert('Request Tidak Dikenali, Mohon Diulang')</script>";
  }
}
if (isset($_GET['editpegawai'])) {
  $param =  $_GET['editpegawai'];
  $query = "SELECT * FROM pegawai WHERE id_pegawai = $param";
  $exec = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($exec, MYSQLI_ASSOC);
}

if (isset($_GET['editanggota'])) {
  $param =  $_GET['editanggota'];
  $query = "SELECT * FROM anggota WHERE id_anggota = $param";
  $exec = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($exec, MYSQLI_ASSOC);
}

if (isset($_POST['proseseditanggota'])) {
  $id = $_POST['id_anggota'];
  $nama = $_POST['nama_anggota'];
  $nomer = $_POST['nomer_anggota'];
  $jk = $_POST['jenis_kelamin'];
  $query = "UPDATE anggota SET nama_anggota= '$nama', nomer_anggota= '$nomer', jenis_kelamin = '$jk' WHERE id_anggota= $id";
  $exec = mysqli_query($conn, $query);
  if ($exec) {
    header("Location:../pegawai/dataanggota.php");
  }
}

if (isset($_POST['proseseditpegawai'])) {
  $id = $_POST['id_pegawai'];
  $nama = $_POST['nama_pegawai'];
  $password = $_POST['password'];
  $jabatan = $_POST['jabatan'];
  $nomer = $_POST['nomer_pegawai'];
  $role = $_POST['role'];


  $query = "UPDATE pegawai SET nama_pegawai = '$nama', password = '$password', nomer_pegawai = '$nomer', jabatan = '$jabatan', role = '$role' WHERE id_pegawai = $id";
  $exec = mysqli_query($conn, $query);

  if ($exec) {
    header("Location:../pegawai");
  }
}
if (isset($_GET['login'])) {
  if (isset($_POST['masuk'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $query = "SELECT * FROM pegawai WHERE nama_pegawai like '$nama%' AND password = '$password' AND role = '$role'";
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
if (isset($_GET['cari'])) {
  $keyword = $_GET['keyword'];
  if ($keyword == null) {
    header("location:index.php");
  } else {
    $query = "SELECT * FROM pegawai WHERE nama_pegawai LIKE '%$keyword%'";
  }

  $exec = mysqli_query($conn, $query);
  $data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
}
if (isset($_GET['tambahanggota'])) {
  $querykoderumah = "SELECT * FROM anggota";
  $execkoderumah = mysqli_query($conn, $querykoderumah);
  $jumlahanggota = mysqli_num_rows($execkoderumah) + 1;
  $bulan = date('m') - 1;
  $koderumah = "DT" . str_pad($jumlahanggota, 3, '0', STR_PAD_LEFT);
  if (isset($_POST['prosestambahanggota'])) {
    $querytambahanggota = "INSERT INTO anggota VALUES (null, '$koderumah', '" . $_POST['nama'] . "', '" . $_POST['nomer'] . "', '" . $_POST['rt'] . "', '" . $_POST['jenis_kelamin'] . "', 0, 0, $bulan )";
    $exectambahanggota = mysqli_query($conn, $querytambahanggota);
    if ($exectambahanggota) {
      header("location:../pegawai/cetakqr.php?kode=$koderumah");
    } else {
      header("location:../pegawai?tambahanggota&pesan=gagal");
    }
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
  <?php if (isset($_GET['editanggota'])) : ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4">
            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
          </div>
          <form method="POST" action="" class="mx-auto col-12 col-lg-10" autocomplete="off">
            <input type="hidden" name="id_anggota" value="<?= $data['id_anggota'] ?>">
            <div class="mb-3">
              <label for="nama" class="form-label">Kode Rumah Anggota</label>
              <input type="text" value="<?= $data['kode_rumah'] ?>" class="form-control" name="kode_anggota" id="kode" readonly>
            </div>
            <div class="row">
              <div class="col">
                <div class="mb-3">
                  <label for="nama" class="form-label">Nama Petugas</label>
                  <input type="text" value="<?= $data['nama_anggota'] ?>" class="form-control" name="nama_anggota" id="nama">
                </div>
              </div>
              <div class="col">
                <label for="basic-url" class="form-label">Nomer Petugas</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon3">+62</span>
                  <input type="number" required value="<?= $data['nomer_anggota'] ?>" class="form-control" name="nomer_anggota" id="basic-url" aria-describedby="basic-addon3">
                </div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label class="form-label">Jenis Kelamin</label>
                  <select class="form-select" name="jenis_kelamin">
                    <option <?php ($data['jenis_kelamin'] == "lk") ? 'selected' : ''; ?> value="lk">Laki Laki</option>
                    <option <?php ($data['jenis_kelamin'] == "pr") ? 'selected' : ''; ?> value="pr">Perempuan</option>
                  </select>
                </div>
              </div>
            </div>
            <input type="submit" class="btn btn-danger form-control" name="proseseditanggota" value="Edit Data">
          </form>
        </div>
      </div>
    </div>
  <?php endif ?>

  <?php if (isset($_GET['tambahanggota'])) : ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4">
            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
          </div>
          <form method="POST" action="" class="mx-auto col-10" autocomplete="off">
            <div class="mb-3">
              <label for="kode" class="form-label">Kode Rumah</label>
              <input required type="text" value="<?= $koderumah ?>" class="form-control" name="kode" id="kode" readonly>
            </div>
            <div class="row">
              <div class="col">
                <div class="mb-3">
                  <label for="nama" class="form-label">Nama Anggota</label>
                  <input required type="text" class="form-control" name="nama" id="nama">
                </div>
              </div>
              <div class="col">
                <label for="nomer" class="form-label">Nomer Anggota</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon3">+62</span>
                  <input type="number" class="form-control" name="nomer" id="nomer">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="rt" class="form-label">RT Anggota</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon3">Rukun Tetangga</span>
                  <input required type="number" class="form-control" name="rt" id="rt">
                </div>
              </div>
              <div class="col">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" aria-label="Default select example">
                  <option value="lk">Laki Laki</option>
                  <option value="pr">Perempuan</option>
                </select>
              </div>
            </div>
            <input type="submit" class="btn btn-info form-control" name="prosestambahanggota" value="Tambah Anggota">
          </form>
        </div>
      </div>
    </div>
  <?php endif ?>

  <?php if (isset($_GET['tambahpegawai'])) : ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4">
            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
          </div>
          <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Pegawai</label>
              <input required type="text" class="form-control" name="nama" id="nama">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password Pegawai</label>
              <input required type="password" class="form-control" name="password" id="password">
            </div>
            <label for="nomer_pegawai" class="form-label">Nomer Petugas</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon3">+62</span>
              <input type="number" class="form-control" name="nomer_pegawai" id="nomer_pegawai">
            </div>
            <div class="row mb-3">
              <div class="col-6">
                <label>Jabatan Petugas</label>
                <input required type="text" class="form-control" name="jabatan" id="jabatan">
              </div>
              <div class="col-6">
                <label>Role Pegawai</label>
                <select class="form-select" name="role" aria-label="Default select example">
                  <option value="pengurus">Pengurus</option>
                  <option value="petugas">Petugas</option>
                </select>
              </div>
            </div>
            <input type="submit" class="btn btn-info" name="prosestambahpegawai" value="Tambah Pegawai">
          </form>
        </div>
      </div>
    </div>
  <?php endif ?>

  <?php if (isset($_GET['editpegawai'])) : ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4">
            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
          </div>
          <form method="POST" action="" class="mx-auto col-12 col-lg-10" autocomplete="off">
            <input type="hidden" name="id_pegawai" value="<?= $data['id_pegawai'] ?>">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Petugas</label>
              <input type="text" value="<?= $data['nama_pegawai'] ?>" class="form-control" name="nama_pegawai" id="nama">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password Petugas</label>
              <input type="text" value="<?= $data['password'] ?>" class="form-control" name="password" id="password">
            </div>
            <label for="basic-url" class="form-label">Nomer Petugas</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon3">+62</span>
              <input type="number" required value="<?= $data['nomer_pegawai'] ?>" class="form-control" name="nomer_pegawai" id="basic-url" aria-describedby="basic-addon3">
            </div>
            <div class="mb-3">
              <label for="jabatan" class="form-label">Jabatan Petugas</label>
              <input type="text" value="<?= $data['jabatan'] ?>" class="form-control" name="jabatan" id="jabatan">
            </div>
            <div class="mb-3">
              <label class="form-label">Bagian Tugas</label>
              <select class="form-select" name="role">
                <option <?php ($data['role'] == "pengurus") ? 'selected' : ''; ?> value="pengurus">Pengurus</option>
                <option <?php ($data['role'] == "petugas") ? 'selected' : ''; ?> value="petugas">Petugas</option>
              </select>
            </div>
            <input type="submit" class="btn btn-danger form-control" name="proseseditpegawai" value="Edit Data">
          </form>
        </div>
      </div>
    </div>
  <?php endif ?>

  <?php if (isset($_GET['login'])) { ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4">
            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
          </div>
          <form method="POST" action="" class="mx-auto col-10" autocomplete="off">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Petugas</label>
              <input type="text" class="form-control" name="nama" id="nama" aria-describedby="nama">
              <div id="nama" class="form-text">Masukan Nama Anda</div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password Petugas</label>
              <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="mb-3">
              <select class="form-select" name="role">
                <option selected>Pilih salah Satu yang ada di list</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
              </select>
            </div>
            <input type="submit" class="btn btn-info" name="masuk" value="Login">
          </form>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if (!isset($_GET['login']) && !isset($_GET['editpegawai']) && !isset($_GET['editanggota']) && !isset($_GET['tambahpegawai']) && !isset($_GET['tambahanggota'])) { ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-75 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4 d-flex justify-content-between">
            <img src="../assets/logo.jpeg" width="70px" class="rounded" alt="LOGO KSM">
            <?php if (isset($_SESSION['login'])) : ?>
              <a class="btn btn-info" href="?tambahpegawai">Tambah Pegawai</a>
            <?php endif ?>
          </div>
          <div class="table-responsive text-center ">
            <table class="table text-start">
              <thead>
                <tr>
                  <th class="col-1">#</th>
                  <th class="col-6">Nama Petugas</th>
                  <th class="col-1">Nomer Pegawai</th>
                  <th class="col-2">Jabatan</th>
                  <?php if (isset($_SESSION['login'])) : ?>
                    <th class="col-2">Edit Data</th>
                  <?php endif ?>
                </tr>
              </thead>
              <tbody>
                <?php if ($data != null) : ?>
                  <?php $no = 0 ?>
                  <?php foreach ($data as $p) { ?>
                    <?php $no++ ?>
                    <tr class="align-middle">
                      <th scope="row"><?= $no ?></th>
                      <td><?= $p['nama_pegawai'] ?></td>
                      <td>
                        <a class="text-success" target="_blank" href="https://wa.me/6262<?= $p['nomer_pegawai'] ?>"><svg class="bi" width="30" height="30">
                            <use xlink:href="#wa" />
                          </svg></a>
                      </td>
                      <td><?= $p['jabatan'] ?></td>
                      <?php if (isset($_SESSION['login'])) : ?>
                        <td>
                          <a class="btn btn-warning my-1" href="?editpegawai=<?= $p['id_pegawai'] ?>">Edit</a>
                          <a class="btn btn-danger my-1" href="?delete=<?= $p['id_pegawai'] ?>">Delete</a>
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
  <?php } ?>

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
</body>

</html>