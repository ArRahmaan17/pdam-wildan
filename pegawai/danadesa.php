<?php 

include '../koneksi.php';
session_start();

$querydanamasuk = "SELECT * FROM danamasuk";
$querydanakeluar = "SELECT * FROM danakeluar";
$querytotaldanamasuk = "SELECT SUM(nominal) as total FROM danamasuk";
$querytotaldanakeluar = "SELECT SUM(nominal) as total FROM danakeluar";


$execdanamasuk = mysqli_query($conn, $querydanamasuk);
$execdanakeluar = mysqli_query($conn, $querydanakeluar);
$exectotaldanamasuk = mysqli_query($conn, $querytotaldanamasuk);
$exectotaldanakeluar = mysqli_query($conn, $querytotaldanakeluar);

if ($execdanamasuk && $execdanakeluar) {
  $datadanamasuk = mysqli_fetch_all($execdanamasuk, MYSQLI_ASSOC);
  $datadanakeluar = mysqli_fetch_all($execdanakeluar, MYSQLI_ASSOC);
  $totaldanamasuk = mysqli_fetch_all($exectotaldanamasuk);
  $totaldanakeluar = mysqli_fetch_all($exectotaldanakeluar);
}

if (isset($_POST['editdanamasuk'])) {
  $id = $_POST['id_masuk'];
  $nominal = $_POST['nominal'];

  $query = "UPDATE danamasuk SET nominal = $nominal WHERE id_masuk = $id";
  $exec = mysqli_query($conn, $query);
  if ($exec) {
    header("Location:../pegawai/danadesa.php");
  }else{
    echo "<script>alert('Update gagal')</script>";
  }
}

if (isset($_GET['editmasuk'])) {
  $id = $_GET['editmasuk'];
  $query = "SELECT * FROM danamasuk WHERE id_masuk = $id";
  $exec = mysqli_query($conn, $query);
  $data =  mysqli_fetch_array($exec);
}

if (isset($_POST['danakeluar'])) {
  $nama = $_POST['nama'];
  $nominal = $_POST['nominal'];
  $tanggal = date("Y-m-d");
  $query = "INSERT INTO danakeluar VALUES(null, '$nama', $nominal, '$tanggal')";
  $exec = mysqli_query($conn, $query);
  if ($exec) {
    header("Location:../pegawai/danadesa.php");
  }else{
    echo "<script>alert('Penambahan data gagal')</script>";
  }
}

if (isset($_POST['danamasuk'])) {
  $nama = $_POST['nama'];
  $nominal = $_POST['nominal'];
  $tanggal = date("Y-m-d");
  $query = "INSERT INTO danamasuk VALUES(null, '$nama', $nominal, '$tanggal')";
  $exec = mysqli_query($conn, $query);
  if ($exec) {
    header("Location:../pegawai/danadesa.php");
  }else{
    echo "<script>alert('Penambahan data gagal')</script>";
  }
}
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location:../pegawai");
}

 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style type="text/css">
      body{
        height: 95vh;
        background-image: linear-gradient(#F8F9FA, #fff);
        background-size: auto;
        background-repeat: no-repeat;
      }
    </style>
</head>
<body>
<?php include '../icon.html'; ?>
<nav class="navbar sticky-top navbar-expand-sm bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="../anggota">KSM Daya Tirta</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../anggota">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pegawai">Pegawai</a>
        </li>
        <?php if (isset($_SESSION['login'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="../pegawai?logout">Logout</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="../pegawai?login=show">Login</a>
          </li>
        <?php endif ?>
        <li class="nav-item">
          <a class="nav-link active" href="danadesa.php">Dana Desa</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php if (isset($_GET['editmasuk'])): ?>
  <div class="container-sm mx-auto">
    <div class="col-12 mt-5">
      <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
        <div class="text-center mb-4">
          <img src="" class="rounded" alt="LOGO KSM">
        </div>
        <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
          <input type="hidden" name="id_masuk" value="<?= $data['id_masuk'] ?>">
          <div class="mb-3">
            <label for="nama" class="form-label">Sumber Dana Masuk</label>
            <input required disabled type="text" value="<?= $data['pemasukan'] ?>" class="form-control" name="nama" id="nama" aria-describedby="nama">
            <div id="nama" class="form-text">Sumber Dana Masuk Berasal</div>
          </div>
          <div class="mb-3">
            <label for="nominal" class="form-label">Nominal Dana</label>
            <input required type="number" class="form-control" name="nominal" value="<?= $data['nominal']  ?>" min="1" id="nominal" aria-describedby="nominal">
          </div>
          <input type="submit" class="btn btn-warning" name="editdanamasuk" value="Edit Dana Masuk">
        </form>
      </div>
    </div>
  </div>
<?php endif ?>
<?php if (isset($_GET['danakeluar'])): ?>
  <div class="container-sm mx-auto">
    <div class="col-12 mt-5">
      <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
        <div class="text-center mb-4">
          <img src="" class="rounded" alt="LOGO KSM">
        </div>
        <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
          <div class="mb-3">
            <label for="nama" class="form-label">Kebutuhan Dana Keluar</label>
            <input required type="text" class="form-control" name="nama" id="nama" aria-describedby="nama">
            <div id="nama" class="form-text">Pengeluaran Dana Untuk Apa</div>
          </div>
          <div class="mb-3">
            <label for="nominal" class="form-label">Nominal Dana</label>
            <input required type="number" class="form-control" name="nominal" value="50000" min="1" id="nominal" aria-describedby="nominal">
          </div>
          <input type="submit" class="btn btn-info" name="danakeluar" value="Tambah Pengeluaran">
        </form>
      </div>
    </div>
  </div>
<?php endif ?>
<?php if (isset($_GET['danamasuk'])): ?>
  <div class="container-sm mx-auto">
    <div class="col-12 mt-5">
      <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
        <div class="text-center mb-4">
          <img src="" class="rounded" alt="LOGO KSM">
        </div>
        <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
          <div class="mb-3">
            <label for="nama" class="form-label">Sumber Dana Masuk</label>
            <input required type="text" class="form-control" name="nama" id="nama" aria-describedby="nama">
            <div id="nama" class="form-text">Sumber Dana Masuk Berasal</div>
          </div>
          <div class="mb-3">
            <label for="nominal" class="form-label">Nominal Dana</label>
            <input required type="number" class="form-control" name="nominal" value="50000" min="1" id="nominal" aria-describedby="nominal">
          </div>
          <input type="submit" class="btn btn-info" name="danamasuk" value="Tambah Pemasukan">
        </form>
      </div>
    </div>
  </div>
<?php endif ?>
<?php if (!isset($_GET['danamasuk']) && !isset($_GET['danakeluar']) && !isset($_GET['editmasuk'])) : ?>
  <div class="container-sm mx-auto">
    <div class="row m-5">
      <div class="col-lg-6 col-md-12">
        <div class="table-responsive mx-1 my-3 shadow rounded-4 p-5">
          <table class="table">
            <div class="d-flex justify-content-between">
              <h4>Sumber Dana masuk</h4>
              <a class="btn btn-info" href="?danamasuk">Tambah Data Dana</a>
            </div>
            <thead>
              <tr>
                <th class="col-1">No</th>
                <th class="col-4">Sumber pemasukan</th>
                <th class="col-3">Tanggal Masuk</th>
                <th class="col-6">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 0; ?>
                <?php foreach ($datadanamasuk as $m) { ?>
                  <?php $no++ ?>
                  <tr>
                    <td><?= $no ?></td>
                    <td><?= $m['pemasukan'] ?></td>
                    <td><?= $m['tanggal'] ?></td>
                    <td class="text-end">
                      <?php if (isset($_SESSION['login'])): ?>
                        <a class="text-decoration-none" href="?editmasuk=<?= $m['id_masuk'] ?>">Rp <?= number_format($m['nominal'],2,',','.')  ?></a>
                      <?php else : ?>
                        Rp <?= number_format($m['nominal'],2,',','.')  ?>
                      <?php endif ?>
                    </td>
                  </tr>
                <?php } ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td class="text-center" colspan="2">Total Pemasukan</td>
                <td class="text-center" colspan="2">Rp <?= number_format($totaldanamasuk[0][0],2,',','.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="col-lg-6 col-md-12">
        <div class="table-responsive mx-1 my-3 shadow rounded-4 p-5">
          <table class="table">
            <div class="d-flex justify-content-between">
              <h4>Sumber Dana keluar</h4>
              <a class="btn btn-info" href="?danakeluar">Tambah Data Dana</a>
            </div>
              <thead>
              <tr>
                <th class="col-1">No</th>
                <th class="col-4">Dana Keluar</th>
                <th class="col-3">Tanggal Keluar</th>
                <th class="col-5">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 0; ?>
                <?php foreach ($datadanakeluar as $k) { ?>
                  <?php $no++ ?>
                  <tr>
                    <td><?= $no ?></td>
                    <td><?= $k['pengeluaran'] ?></td>
                    <td><?= $k['tanggal'] ?></td>
                    <td class="text-end">
                      <?php if (isset($_SESSION['login'])): ?>
                        <a class="text-decoration-none" href="?editkeluar=<?= $k['id_keluar'] ?>">Rp <?= number_format($k['nominal'],2,',','.')  ?></a>
                      <?php else : ?>
                        Rp <?= number_format($k['nominal'],2,',','.')  ?>
                      <?php endif ?></td>
                  </tr>
                <?php } ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td class="text-center" colspan="2">Total Pengeluaran</td>
                <td class="text-center" colspan="2">Rp <?= number_format($totaldanakeluar[0][0],2,',','.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div> 
<?php endif ?>
<div class="container">
    <div class="sticky-bottom">
      <footer class="d-flex flex-wrap justify-content-between align-items-center p-4 m-1">
        <div class="col-md-4 d-flex align-items-center">
          <a href="https://github.com/ArRahmaan17/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
            <svg class="bi" width="30" height="24"><use xlink:href="#github"/></svg>
          </a>
        </div>
        <div class="text-center">
          &#169; KSM Daya Tirta
        </div>
        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
          <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"/></svg></a></li>
          <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"/></svg></a></li>
          <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"/></svg></a></li>
        </ul>
      </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>