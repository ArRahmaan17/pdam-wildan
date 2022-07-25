<?php
include 'koneksi.php';
// var_dump($_GET);
if (isset($_GET['kode'])) {
  $koderumah = $_GET['kode'];
  $bulan = date("m");
  $querysettingpembayaran = "SELECT * FROM setting_pembayaran";
  $execsetting = mysqli_query($conn, $querysettingpembayaran);
  $datasetting = mysqli_fetch_array($execsetting);
  $queryselectdetailpembayaran = "SELECT * FROM anggota LEFT JOIN pembayaran ON pembayaran.kode_rumah = anggota.kode_rumah WHERE MONTH(pembayaran.tanggal_bayar) = $bulan AND anggota.kode_rumah = '$koderumah'";
  $execselectdetailpembayaran = mysqli_query($conn, $queryselectdetailpembayaran);
  $datadetailpembayaran = mysqli_fetch_array($execselectdetailpembayaran, MYSQLI_ASSOC);
  $tagihanair = $datadetailpembayaran['total_bayar'] - $datasetting['PPN'];
  // var_dump($queryselectdetailpembayaran);
  // var_dump($datasetting);
  // die();
}
// die();
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 58]]);
// $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Folio']);


// $mpdf = new \Mpdf\Mpdf('utf-8', 'A4',  0, '', 0, 0, 0, 0, 0, 'L');
$mpdf->WriteHTML('
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    *{
      margin:0 !important;
      padding:0 !important;
      box-sizing:border-box;
    }
    body{

      font-size:5px;
        margin:0;
        padding:-200px;
    }
    p{
        margin:0;
        padding:0;
    }
    table
    {
      padding:-200px;
        width:100%;
        border-spacing:0;
        border-collapse: collapse; 
        margin:0 !important;
    }
    </style>
</head>
<body>
  <div style="margin:0;padding:-50px;box-sizing:border-box">
      <table>
      <tr>
          <td><img src="./assets/img/logoweb.png" width="40px" alt="" srcset=""></td>
          <td>
              <h6>KELOMPOK SWADAYA MASYARAKAT</h6>
              <h4>DAYA TIRTA</h4>
              <p>RW 19 GULON JEBRES SURAKARTA</p>
              <p>Akta Notaris nomer : 01 tanggal 06 November 2021</p>
              <p>Sekertariat : Jalan Kartika III Depan Asrama UNS RT.01 RW.19</p>
          </td>
      </tr>
    </table>
      <hr>
    <table>
      <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td>' . $datadetailpembayaran['tanggal_bayar'] . '</td>
        <td>Stan Meter Awal</td>
        <td>:</td>
        <td>' . $datadetailpembayaran['meter_awal'] . '</td>
      </tr>
      <tr>
        <td>Kode Rumah</td>
        <td>:</td>
        <td>' . $datadetailpembayaran['kode_rumah'] . '</td>
        <td>Stan Meter Akhir</td>
        <td>:</td>
        <td>' . $datadetailpembayaran['meter_akhir'] . '</td>
      </tr>
      <tr>
        <td>Nama Anggota</td>
        <td>:</td>
        <td>' . $datadetailpembayaran['nama_anggota'] . '</td>
        <td>Pemakaian Air</td>
        <td>:</td>
        <td>' . $datadetailpembayaran['pemakaian_air'] . 'm3</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>Gulon RT.' . $datadetailpembayaran['rt'] . '</td>
      </tr>
    </table>
    <table style="margin-top:30px;">
      <tr>
        <td style="text-align: right;">Tagihan Air</td>
        <td>:</td>
        <td>Rp.' . number_format($tagihanair, 2, ',', '.') . '</td>
      </tr>
      <tr>
        <td style="text-align: right;">Biaya Beban</td>
        <td>:</td>
        <td>Rp.' . number_format($datasetting['biaya_beban'], 2, ',', '.') . '</td>
      </tr>
      <tr border="1">
        <td style="text-align: right;">Total Biaya</td>
        <td>:</td>
        <td>' . "Rp." . number_format($datadetailpembayaran['total_bayar'], 2, ',', '.') . '</td>
      </tr>
    </table>
  </div>

</body>
</html>
');
$mpdf->Output();
