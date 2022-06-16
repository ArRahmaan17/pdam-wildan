<?php
include '../koneksi.php';

if (isset($_GET['keysearch'])) {
	$keyword = $_GET['keyword'];
	$sql = "SELECT * FROM anggota WHERE nama_anggota = '$keyword' OR kode_rumah = '$keyword' OR nomer_anggota = '$keyword'";
	$exec = mysqli_query($conn, $sql);
	$data = mysqli_fetch_all($exec, MYSQLI_ASSOC);
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
          <a class="nav-link active" href="../anggota">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pegawai">Pegawai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="danadesa.php">Dana Desa</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php if (isset($data)) : ?>
	<div class="container-sm mx-auto">
		<div class="card mt-5">
			<div class="card-header">
				<h5 class="d-inline-block">Data Anggota KSM Daya Tirta</h5>
				<a href="../anggota"><span class="btn btn-secondary p-2 m-1 col-1">Back</span></a>
			</div>
		    <div class="card-body">
			    <table class="table table-striped">
				  <thead>
				    <tr>
				      <th scope="col">#</th>
				      <th scope="col">Nama Anggota</th>
				      <th scope="col">Nomer Anggota</th>
				      <th scope="col">Kode Rumah</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php foreach ($data as $a) : ?>
				  		<tr>
					      <th scope="row">1</th>
					      <td><?= $a['nama_anggota'] ?></td>
					      <td><?= $a['nomer_anggota'] ?></td>
					      <td><?= $a['kode_rumah'] ?></td>
					    </tr>
				  	<?php endforeach; ?>
				  </tbody>
				</table>
			</div>
		</div>
	</div>	
<?php endif; ?>

<?php if (!isset($data)) : ?>
	<div class="container-sm mx-auto">
		<div class="col-12 mt-5">
	    <div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
	      <div class="text-center mb-4">
					<img src="" class="rounded" alt="LOGO KSM">
				</div>
	      <form method="GET" action="" class="mx-auto col-8" autocomplete="off">
				  <div class="mb-3">
				    <label for="keyword" class="form-label">Masukan Kata Kunci</label>
				    <input required type="text" name="keyword" class="form-control" id="keyword" aria-describedby="keyword">
				    <div id="keyword" class="form-text">Kata kunci bisa menggunakan Nomer HP, Nama, Kode Rumah</div>
				  </div>
				  <input type="submit" class="btn btn-primary" name="keysearch" value="Cari">
				</form>
	    </div>
	  </div>
	</div>
<?php endif; ?>
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