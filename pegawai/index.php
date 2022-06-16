<?php 

include '../koneksi.php';

$query = "SELECT * FROM Pegawai";
$exec = mysqli_query($conn, $query);
$data = mysqli_fetch_all($exec, MYSQLI_ASSOC);


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pegawai KSM</title>
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
<nav class="navbar sticky-top navbar-expand-sm bg-light">
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
          <a class="nav-link active" href="../pegawai">Pegawai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="danadesa.php">Dana Desa</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php if (isset($_SESSION['code'])) { ?>
    <div class="container-sm mx-auto">
      <div class="col-12 mt-5">
        <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
          <div class="text-center mb-4">
            <img src="" class="rounded" alt="LOGO KSM">
          </div>
          <form method="GET" action="" class="mx-auto col-8" autocomplete="off">
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email address</label>
              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  <?php } ?>
  <div class="container-sm mx-auto">
    <div class="col-12 mt-5">
      <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
        <div class="text-center mb-4 d-flex justify-content-between">
          <img src="" class="rounded" alt="LOGO KSM">
          <form class="d-flex" autocomplete="off">
            <input class="form-control mx-1" type="text" name="keyword">
            <input class="btn btn-info" type="submit" name="cari" value="Cari Pegawai">
          </form>
        </div>
        <div class="table-responsive text-center">
          <table class="table text-start">
            <thead >
              <tr>
                <th class="col-1">#</th>
                <th class="col-6">Nama Petugas</th>
                <th class="col-1">Nomer Pegawai</th>
                <th class="col-3">Jenis Kelamin</th>
                <th class="col-1">Edit Data Pegawai</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 0 ?>
              <?php foreach($data as $p){ ?>
              <?php $no++ ?>
                <tr>
                  <th scope="row"><?= $no ?></th>
                  <td><?= $p['nama_pegawai'] ?></td>
                  <td>
                    <a class="text-success" target="_blank" href="https://wa.me/62<?= $p['nomer_pegawai']?>" ><svg class="bi" width="30" height="30"><use xlink:href="#wa"/></svg></a>
                  </td>
                  <td><?= ($p['jenis_kelamin'] == 'lk') ? "Laki Laki" : "Perempuan" ; $p['jenis_kelamin'] ?></td>
                  <td><a class="btn col-12 btn-warning text-truncate" href="#">Edit Data</a></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<div class="container">
    <div class="sticky-bottom">
      <footer class="d-flex flex-wrap justify-content-between align-items-center p-4 m-1">
        <div class="col-md-4 d-flex align-items-center">
          <a href="https://github.com/ArRahmaan17/" target="blank" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
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