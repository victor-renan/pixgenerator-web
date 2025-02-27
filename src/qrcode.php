<?php

require __DIR__ . '/../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use VictorRenan\PixGenerator\PixGenerator;

try {
    $code = new PixGenerator($_GET['pix-key']);

    if ($_GET['amount']) {
        $code->setTransactionAmount($_GET['amount']);
    }

    if ($_GET['transaction-id']) {
        $code->setTransactionId($_GET['transaction-id']);
    }

    if ($_GET['name']) {
        $code->setMerchantName($_GET['name']);
    }

    if ($_GET['city']) {
        $code->setMerchantCity($_GET['city']);
    }

    if ($_GET['additional-info']) {
        $code->setAdditionalInfo($_GET['additional-info']);
    }

    $mCode = $code->getCode();

    echo json_encode([
        'qrcode' => (new QRCode())->render($mCode),
        'code' => $mCode,
        'error' => null
    ]);

} catch (Exception $e) {
    echo json_encode([
        'qrcode' => null,
        'code' => null,
        'error' => $e->getMessage()
    ]);
}