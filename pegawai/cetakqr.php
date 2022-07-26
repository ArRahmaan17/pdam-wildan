<?php

declare(strict_types=1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require_once('./../vendor/autoload.php');

if (isset($_GET['kode'])) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $full_url = $protocol . "://$_SERVER[HTTP_HOST]/PDAM/pegawai/pembayaran.php?pembayaran=" . $_GET['kode'];
}

$options = new QROptions(
    [
        'eccLevel' => QRCode::ECC_L,
        'outputType' => QRCode::OUTPUT_MARKUP_SVG,
        'version' => 5,
    ]
);

$qrcode = (new QRCode($options))->render($full_url);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Create QR Codes in PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body class="text-center">
    <div class="container mt-5">
        <h1>Mohon Untuk lakukan Penangkapan Layar</h1>
        <div class="container">
            <img src='<?= $qrcode ?>' alt='QR Code' width='300' height='300'>
            <p class="display-1"><?= $_GET['kode'] ?></p>
        </div>

    </div>

</body>

</html>