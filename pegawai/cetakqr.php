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
    <link rel="stylesheet" href="/css/styles.min.css">
</head>

<body>
    <h1>Creating QR Codes in PHP</h1>
    <div class="container">
        <img src='<?= $qrcode ?>' alt='QR Code' width='800' height='800'>
    </div>
</body>

</html>