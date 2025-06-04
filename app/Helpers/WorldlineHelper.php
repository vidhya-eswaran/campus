<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class WorldlineHelper
{
    public static function encryptPayload($plainText, $encryptionKey, $iv)
    {
        $cipher = "AES-256-CBC";
        $encrypted = openssl_encrypt($plainText, $cipher, $encryptionKey, 0, $iv);
        return $encrypted;
    }

    public static function decryptPayload($encryptedText, $encryptionKey, $iv)
    {
        $cipher = "AES-256-CBC";
        $decrypted = openssl_decrypt($encryptedText, $cipher, $encryptionKey, 0, $iv);
        return $decrypted;
    }
}
