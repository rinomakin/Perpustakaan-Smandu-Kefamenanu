<?php

namespace App\Helpers;

use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class BarcodeHelper
{
    /**
     * Generate barcode untuk buku
     */
    public static function generateBookBarcode($code)
    {
        $barcode = new DNS1D();
        return $barcode->getBarcodeHTML($code, 'C128', 2, 50);
    }

    /**
     * Generate barcode untuk anggota
     */
    public static function generateMemberBarcode($code)
    {
        $barcode = new DNS1D();
        return $barcode->getBarcodeHTML($code, 'C128', 2, 50);
    }

    /**
     * Generate QR Code
     */
    public static function generateQRCode($data)
    {
        $qrcode = new DNS2D();
        return $qrcode->getBarcodeHTML($data, 'QRCODE', 5, 5);
    }

    /**
     * Generate barcode sebagai gambar
     */
    public static function generateBarcodeImage($code, $type = 'C128')
    {
        $barcode = new DNS1D();
        return $barcode->getBarcodePNG($code, $type, 3, 100);
    }

    /**
     * Generate QR Code sebagai gambar
     */
    public static function generateQRCodeImage($data)
    {
        $qrcode = new DNS2D();
        return $qrcode->getBarcodePNG($data, 'QRCODE', 10, 10);
    }
} 