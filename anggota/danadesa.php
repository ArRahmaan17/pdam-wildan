<?php

include '../koneksi.php';

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

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Desa</title>
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
  <nav class="navbar sticky-top navbar-expand-sm bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="../anggota">KSM Daya Tirta</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../anggota">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../pegawai">Pegawai</a>
          </li>
          <?php if (isset($_SESSION['login'])) : ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Fitur Pegawai
              </a>
              <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="../pegawai?logout">Logout</a></li>
                <li><a class="dropdown-item" href="../pegawai/pembayaran.php">Pembayaran PDAM</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
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
    <div class="row m-5">
      <div class="col-lg-6 col-md-12">
        <div class="table-responsive mx-1 my-3 shadow rounded-4 p-5">
          <table class="table">
            <h4>Sumber Dana masuk</h4>
            <thead>
              <tr>
                <th>No</th>
                <th>Sumber pemasukan</th>
                <th width="2">Tanggal Masuk</th>
                <th>Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 0; ?>
              <?php foreach ($datadanamasuk as $m) { ?>
                <?php $no++ ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= $m['pemasukan'] ?></td>
                  <td>tanggal</td>
                  <td>Rp <?= number_format($m['nominal'], 2, ',', '.')  ?></td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td class="text-center" colspan="2">Total Pemasukan</td>
                <td class="text-center" colspan="2">Rp <?= number_format($totaldanamasuk[0][0], 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="col-lg-6 col-md-12">
        <div class="table-responsive mx-1 my-3 shadow rounded-4 p-5">
          <table class="table">
            <h4>Sumber Dana keluar</h4>
            <thead>
              <tr>
                <th>No</th>
                <th>Dana Keluar</th>
                <th width="2">Tanggal Keluar</th>
                <th>Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 0; ?>
              <?php foreach ($datadanakeluar as $m) { ?>
                <?php $no++ ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= $m['pengeluaran'] ?></td>
                  <td>tanggal</td>
                  <td>Rp <?= number_format($m['nominal'], 2, ',', '.')  ?></td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td class="text-center" colspan="2">Total Pengeluaran</td>
                <td class="text-center" colspan="2">Rp <?= number_format($totaldanakeluar[0][0], 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="sticky-bottom">
      <footer class="d-flex flex-wrap justify-content-between align-items-center p-4 m-1">
        <div class="col-md-4 d-flex align-items-center">
          <a href="https://github.com/ArRahmaan17/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
            <svg class="bi" width="30" height="24">
              <use xlink:href="#github" />
            </svg>
          </a>
        </div>
        <div class="text-center">
          &#169; KSM Daya Tirta
        </div>
        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
          <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
                <use xlink:href="#twitter" />
              </svg></a></li>
          <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
                <use xlink:href="#instagram" />
              </svg></a></li>
          <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
                <use xlink:href="#facebook" />
              </svg></a></li>
        </ul>
      </footer>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>