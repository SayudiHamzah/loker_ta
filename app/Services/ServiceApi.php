<?php

namespace App\Services;

use App\Models\ModelLog;
use App\Models\rc4Model;
use Illuminate\Support\Str;

class ServiceApi
{
    public static function  rc4Encrypt($key, $data)
    {
        $s = range(0, 255);
        $j = 0;
        $res = '';
        // Key Scheduling Algorithm (KSA)
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];
        }

        // Pseudo-Random Generation Algorithm (PRGA)
        $i = 0;
        $j = 0;
        for ($y = 0; $y < strlen($data); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];
            $k = $s[($s[$i] + $s[$j]) % 256];
            $res .= chr(ord($data[$y]) ^ $k);
        }

        return $res;
    }

    // public static function generateRc4Uuid()
    // {
    //     $key = 'secret_key';
    //     $uuid = Str::uuid()->toString();
    //     $encrypted = ServiceLoker::rc4Encrypt($key, $uuid); // ✅ akses $this->key
    //     $datafinal = strtr(base64_encode($encrypted), '+/', '-_');
    //     rc4Model::create([
    //         'uuid_rc4' => $uuid,
    //         'uuid_encode' => $datafinal
    //     ]);
    //     dd($datafinal);
    //     return $datafinal;
    // }

    public static function  decryptRc4Uuid($encryptedBase64)
    {
        $key = 'secret_key';
        $encrypted = base64_decode($encryptedBase64);
        $decrypted = ServiceLoker::rc4Encrypt($key, $encrypted); // ✅ akses $this->key
        return $decrypted;
    }
    public static function  history($id)
    {
        $datalog = ModelLog::with('qrcode', 'user')
            ->where('loker_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $datalog;
    }
}
