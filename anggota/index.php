<?php
include '../koneksi.php';
if (isset($_GET['keyword'])) {
	$keyword = $_GET['keyword'];
	$querycarianggota = "SELECT * FROM anggota WHERE nama_anggota = '$keyword' OR kode_rumah = '$keyword' OR nomer_anggota = '$keyword'";
	$execcarianggota = mysqli_query($conn, $querycarianggota);
	$data = mysqli_fetch_all($execcarianggota, MYSQLI_ASSOC);
} else {
	$querycarianggota = "SELECT * FROM anggota";
	$execcarianggota = mysqli_query($conn, $querycarianggota);
	$data = mysqli_fetch_all($execcarianggota, MYSQLI_ASSOC);
}
if (isset($_GET['cektransaksi'])) {
	$koderumah = $_GET['cektransaksi'];
	$querycektransaksi = "SELECT * FROM anggota WHERE kode_rumah = '$koderumah'";
	$execcektransaksi = mysqli_query($conn, $querycarianggota);
	$dataanggota = mysqli_fetch_array($execcektransaksi, MYSQLI_ASSOC);
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
						<a class="nav-link" href="../pegawai">Pegawai</a>
					</li>
					<?php if (isset($_SESSION['login'])) : ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Fitur Pegawai
							</a>
							<ul class="dropdown-menu dropdown-menu-dark">
								<li><a class="dropdown-item" href="../pegawai?logout">Logout</a></li>
								<li><a class="dropdown-item" href="pembayaran.php">Pembayaran PDAM</a></li>
								<li><a class="dropdown-item" href="../pegawai/?tambahanggota">Tambah Anggota</a></li>
								<li><a class="dropdown-item" href="../pegawai/dataanggota.php">Data Anggota</a></li>
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
		<?php if (isset($_GET['cektransaksi'])) { ?>
			<div class="col-12 mt-5">
				<div class="h-100 p-5 bg-light border rounded-4 shadow-sm">
					<div class="col-md-8 mx-auto">
						<div class="card mb-3">
							<div class="card-body">
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
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if (!isset($_GET['cektransaksi'])) { ?>
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
			<div class="container-sm mx-auto">
				<div class="card mt-5">
					<div class="card-header">
						<h5 class="d-inline-block">Data Anggota KSM Daya Tirta</h5>
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
										<th scope="col">Cek Pembayaran</th>
									</tr>
								</thead>
								<tbody>
									<?php if (count($data) > 1) { ?>
										<?php foreach ($data as $a) : ?>
											<tr>
												<th scope=" row">1</th>
												<td><?= $a['nama_anggota'] ?></td>
												<td><?= $a['nomer_anggota'] ?></td>
												<td><?= $a['kode_rumah'] ?></td>
												<td><a class="btn btn-info col-12" href="?keyword=<?= $a['kode_rumah'] ?>">Pakai</a></td>
											</tr>
										<?php endforeach; ?>
									<?php  } else { ?>
										<?php foreach ($data as $a) : ?>
											<tr>
												<th scope=" row">1</th>
												<td><?= $a['nama_anggota'] ?></td>
												<td><?= $a['nomer_anggota'] ?></td>
												<td><?= $a['kode_rumah'] ?></td>
												<td><a class="btn btn-info col-12" href="?cektransaksi=<?= $a['kode_rumah'] ?>">Cek</a></td>
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
		<div class=" container">
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
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>