<?php

namespace App\Services;

use App\Models\User;
use App\Models\rc4Model;
use Illuminate\Support\Str;

class ServiceLoker
{
    // protected static string $key = 'secret_key';

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
    public static function generateRc4Uuid($user_id): string
    {
        $uuid = Str::uuid()->toString();
        $uuidNoDash = str_replace('-', '', $uuid);
        $datauser = User::findOrFail($user_id);
        $data_hak = $datauser->status;
        $data_created = $datauser->created_at;
        $gabung = $data_hak . $data_created;
        $resultKey = str_replace(['-', ' ', ':'], '', $gabung);
        $encrypted = self::rc4Encrypt($resultKey, $uuidNoDash);
        $byteArray = array_values(unpack('C*', $encrypted));
        $datafinal = strtr(base64_encode($encrypted), '+/', '-_');
        rc4Model::create([
            'uuid' => $uuidNoDash,
            'uuid_rc4' => $encrypted,
            'uuid_encode' => $datafinal,
            'key' => $resultKey,
            'data_byte' => json_encode($byteArray)
        ]);
        return $datafinal;
    }

    /**
     * Decrypt Base64 (URL-safe) Encoded RC4 UUID
     */
    public static function decryptRc4Uuid(string $encoded, $key): string
    {
        // Balikkan base64 URL-safe ke standar
        $base64 = strtr($encoded, '-_', '+/');
        // dd($base64);

        $encrypted = base64_decode($base64);
        // dd($encrypted);
        return self::rc4Encrypt($key, $encrypted);
    }
}
