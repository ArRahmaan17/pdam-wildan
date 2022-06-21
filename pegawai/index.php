<?php 

include '../koneksi.php';
session_start();

$query = "SELECT * FROM Pegawai";
$exec = mysqli_query($conn, $query);
$data = mysqli_fetch_all($exec, MYSQLI_ASSOC);

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location:../pegawai");
}
if (isset($_GET['edit'])) {
  $param =  $_GET['edit'];
  $query = "SELECT * FROM Pegawai WHERE id_pegawai = $param";
  $exec = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($exec, MYSQLI_ASSOC);
}

if (isset($_POST['prosesedit'])) {
  $id = $_POST['id_pegawai'];
  $nama = $_POST['nama_pegawai'];
  $nomer = $_POST['nomer_pegawai'];
  $jk = $_POST['jenis_kelamin'];
  $role = $_POST['role'];


  $query = "UPDATE Pegawai SET nama_pegawai = '$nama', nomer_pegawai = '$nomer', jenis_kelamin = '$jk', role = '$role' WHERE id_pegawai = $id";
  $exec = mysqli_query($conn, $query);

  if ($exec) {
    header("Location:../pegawai");
  }else{

  }

}
if (isset($_GET['login'])) {
  $_SESSION['kode'] = "KSM-".date("d");
  if (isset($_POST['masuk'])) {
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $query = "SELECT * FROM Pegawai WHERE nama_pegawai LIKE '%$nama%' AND role = '$role'";
    $exec = mysqli_query($conn, $query);
    $data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
    if (count($data) == 1) {
      $_SESSION['login'] = true;
      header("Location:../pegawai");
    }else{
      echo "<script>alert('Request Tidak Dikenali, Mohon Diulang')</script>";
    }
  }
}
if (isset($_GET['cari'])) {
  $keyword = $_GET['keyword'];
  if ($keyword == null) {
    $query = "SELECT * FROM Pegawai";
  }else{
    $query = "SELECT * FROM Pegawai WHERE nama_pegawai LIKE '%__$keyword%'";
  }

  $exec = mysqli_query($conn, $query);
  $data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
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
<nav class="navbar sticky-top navbar-expand-sm shadow-sm bg-light">
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
          <a class="nav-link" href="danadesa.php">Dana Desa</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php if (isset($_GET['edit'])): ?>
  <div class="container-sm mx-auto">
    <div class="col-12 mt-5">
      <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
        <div class="text-center mb-4">
          <img src="" class="rounded" alt="LOGO KSM">
        </div>
        <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
          <input type="hidden" name="id_pegawai" value="<?= $data['id_pegawai'] ?>">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Petugas</label>
            <input type="text" value="<?= $data['nama_pegawai'] ?>" class="form-control" name="nama_pegawai" id="nama">
          </div>
          <label for="basic-url" class="form-label">Nomer Petugas</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon3">+62</span>
            <input type="number" value="<?php $data['nomer_pegawai'] ?>" class="form-control" name="nomer_pegawai" id="basic-url" aria-describedby="basic-addon3">
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin">
              <option <?php ($data['jenis_kelamin'] == "lk") ? 'selected' : '' ; ?> value="lk">Laki Laki</option>
              <option <?php ($data['jenis_kelamin'] == "pr") ? 'selected' : '' ; ?> value="pr">Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Bagian Tugas</label>
            <select class="form-select" name="role">
              <option <?php ($data['role'] == "pengurus") ? 'selected' : '' ; ?> value="pengurus">Pengurus</option>
              <option <?php ($data['role'] == "petugas") ? 'selected' : '' ; ?> value="petugas">Petugas</option>
            </select>
          </div>
          <input type="submit" class="btn btn-danger form-control" name="prosesedit" value="Edit Data">
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
          <img src="" class="rounded" alt="LOGO KSM">
        </div>
        <form method="POST" action="" class="mx-auto col-8" autocomplete="off">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Petugas</label>
            <input type="text" class="form-control" name="nama" id="nama" aria-describedby="nama">
          <div id="nama" class="form-text">Masukan Nama Anda</div>
          </div>
          <div class="mb-3">
            <select class="form-select" name="role">
              <option selected>Pilih salah Satu yang ada di list</option>
              <option value="pengurus">Pengurus</option>
              <option value="petugas">Petugas</option>
            </select>
          </div>
          <input type="submit" class="btn btn-info" name="masuk" value="Login">
        </form>
      </div>
    </div>
  </div>
<?php } ?>
<?php if (!isset($_GET['login']) && !isset($_GET['edit'])) { ?>
  <div class="container-sm mx-auto">
    <div class="col-12 mt-5">
      <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
        <div class="text-center mb-4 d-flex justify-content-between">
          <img src="" class="rounded" alt="LOGO KSM">
          <form class="d-flex" autocomplete="off">
            <input class="form-control mx-1" type="text" name="keyword">
            <input class="btn btn-info" type="submit" name="cari" value="Cari">
          </form>
        </div>
        <div class="table-responsive text-center">
        <table class="table text-start">
          <thead >
            <tr>
              <th class="col-1">#</th>
              <th class="col-6">Nama Petugas</th>
              <th class="col-1">Nomer Pegawai</th>
              <th class="col-2">Jenis Kelamin</th>
            <?php if (isset($_SESSION['login'])): ?>
              <th class="col-2">Edit Data</th>
            <?php endif ?>
            </tr>
            </thead>
            <tbody>
              <?php if ($data != null): ?>
                <?php $no = 0 ?>
                <?php foreach($data as $p){ ?>
                <?php $no++ ?>
              <tr>
                <th scope="row"><?= $no ?></th>
                  <td><?= $p['nama_pegawai'] ?></td>
                  <td>
                    <a class="text-success" target="_blank" href="https://wa.me/62<?= $p['nomer_pegawai']?>" ><svg class="bi" width="30" height="30"><use xlink:href="#wa"/></svg></a>
                  </td>
                  <td><?= ($p['jenis_kelamin'] == 'lk') ? "Laki Laki" : "Perempuan" ; ?></td>
                    <?php if (isset($_SESSION['login'])): ?>
                      <td>
                        <a class="btn btn-primary" href="?edit=<?= $p['id_pegawai'] ?>">Link</a>
                      </td>
                    <?php endif ?>    
                </tr>
                <?php } ?>
                <?php else: ?>
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