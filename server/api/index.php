<?php

require_once __DIR__ . '/../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use VictorRenan\PixGenerator\PixGenerator;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (empty($_GET['pix'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode([
        'error' => 'Digite a chave PIX'
    ]);
    return;
} 

try {
    $generator = new PixGenerator($_GET['pix']);

    if (isset($_GET['amount'])) {
        $generator->setTransactionAmount($_GET['amount']);
    }

    if (isset($_GET['txid'])) {
        $generator->setTransactionId($_GET['txid']);
    }

    if (isset($_GET['name'])) {
        $generator->setMerchantName($_GET['name']);
    }

    if (isset($_GET['city'])) {
        $generator->setMerchantCity($_GET['city']);
    }

    if (isset($_GET['info'])) {
        $generator->setAdditionalInfo($_GET['info']);
    }

    $code = $generator->getCode();

    header('HTTP/1.1 200 OK');
    echo json_encode([
        'qr' => (new QRCode())->render($code),
        'code' => $code,
    ]);
} catch (\Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}