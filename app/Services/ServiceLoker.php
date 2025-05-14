<?php

namespace App\Services;

use Illuminate\Support\Str;

class ServiceLoker
{
    protected static string $key = 'secret_key';

    /**
     * RC4 Encrypt or Decrypt
     */
    public static function rc4Encrypt(string $key, string $data): string
    {
        $s = range(0, 255);
        $j = 0;
        $res = '';

        // Key Scheduling Algorithm (KSA)
        $keyLength = strlen($key);
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $keyLength])) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];
        }

        // Pseudo-Random Generation Algorithm
        $i = $j = 0;
        $dataLength = strlen($data);
        for ($y = 0; $y < $dataLength; $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];
            $k = $s[($s[$i] + $s[$j]) % 256];
            $res .= chr(ord($data[$y]) ^ $k);
        }

        return $res;
    }

    /**
     * Generate RC4 Encrypted UUID (Base64-encoded, URL-safe)
     */
    public static function generateRc4Uuid(): string
    {
        $uuid = Str::uuid()->toString();
        $encrypted = self::rc4Encrypt(self::$key, $uuid);

        // Base64 URL-safe
        return strtr(base64_encode($encrypted), '+/', '-_');
    }

    /**
     * Decrypt Base64 (URL-safe) Encoded RC4 UUID
     */
    public static function decryptRc4Uuid(string $encoded): string
    {
        // Balikkan base64 URL-safe ke standar
        $base64 = strtr($encoded, '-_', '+/');
        $encrypted = base64_decode($base64);

        return self::rc4Encrypt(self::$key, $encrypted);
    }
}
