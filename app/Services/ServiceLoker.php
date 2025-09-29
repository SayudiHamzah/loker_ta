<?php

namespace App\Services;

use App\Models\Decryption;
use App\Models\Encryption;
use App\Models\ModelLoker;
use App\Models\rc4Model;
use App\Models\User;
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

        // dd($res);

        return $res;
    }

    /**
     * Generate RC4 Encrypted UUID (Base64-encoded, URL-safe)
     */
    public static function generateRc4Uuid($user_id, string $namaLOK = ''): string
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
        $result_enkrip_proses = ServiceLoker::rc4EncryptProses($resultKey, $uuidNoDash);
        $nama_loker = ModelLoker::where('user_id', $user_id)->first();
        $result_dekripsi_proses = ServiceLoker::decryptRc4UuidProses($datafinal, $resultKey);
        $decryptedByteArray = array_values(unpack('C*', $result_dekripsi_proses['hasil_dekripsi']));
        if($nama_loker != null){
            Encryption::create([
                'user_id'        => $user_id,
                'name_locker'        =>$nama_loker->name_locker,
                'key'            => $resultKey,
                'uuid'           => $uuidNoDash,
                'hasil_ksa'      => $result_enkrip_proses['hasil_ksa'],
                'hasil_pgra'     => $result_enkrip_proses['hasil_pgra'],
                'hasil_desimal'  => json_encode($byteArray),
                'hasil_enkripsi' => strtr(base64_encode($result_enkrip_proses['hasil_enkripsi']), '+/', '-_'),
            ]);
            Decryption::create([
                'user_id' => $user_id,
                'name_locker'=>$nama_loker->name_locker,
                'key' => $resultKey,
                'uuid' => $uuidNoDash,
                'hasil_ksa' => $result_dekripsi_proses['hasil_ksa'],
                'hasil_pgra' => $result_dekripsi_proses['hasil_pgra'],
                'hasil_desimal' => json_encode($decryptedByteArray),
            ]);
        }else{
            Encryption::create([
                'user_id'        => $user_id,
                'name_locker'        =>$namaLOK,
                'key'            => $resultKey,
                'uuid'           => $uuidNoDash,
                'hasil_ksa'      => $result_enkrip_proses['hasil_ksa'],
                'hasil_pgra'     => $result_enkrip_proses['hasil_pgra'],
                'hasil_desimal'  => json_encode($byteArray),
                'hasil_enkripsi' => strtr(base64_encode($result_enkrip_proses['hasil_enkripsi']), '+/', '-_'),
            ]);
            Decryption::create([
                'user_id' => $user_id,
                'name_locker'        =>$namaLOK,

                'key' => $resultKey,
                'uuid' => $uuidNoDash,
                'hasil_ksa' => $result_dekripsi_proses['hasil_ksa'],
                'hasil_pgra' => $result_dekripsi_proses['hasil_pgra'],
                'hasil_desimal' => json_encode($decryptedByteArray),
            ]);
        }
        $result_dekripsi_proses = ServiceLoker::decryptRc4UuidProses($datafinal, $resultKey);
        $decryptedByteArray = array_values(unpack('C*', $result_dekripsi_proses['hasil_dekripsi']));
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



    public static function rc4EncryptProses(string $key, string $data): array
    {
        $s = range(0, 255);
        $j = 0;
        $res = '';
        $hasil_desimal = [];

        // --- Key Scheduling Algorithm (KSA) ---
        $keyLength = strlen($key);
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $keyLength])) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];
        }

        $hasilKSA = json_encode($s);

        // --- Pseudo-Random Generation Algorithm (PGRA) ---
        $i = $j = 0;
        $pgraStream = [];
        $dataLength = strlen($data);

        for ($y = 0; $y < $dataLength; $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];

            $k = $s[($s[$i] + $s[$j]) % 256];
            $pgraStream[] = $k;

            $xor = ord($data[$y]) ^ $k;   // hasil XOR dalam bentuk desimal
            $hasil_desimal[] = $xor;      // simpan nilai desimalnya
            $res .= chr($xor);            // hasil XOR jadi string terenkripsi
        }

        $hasilPGRA = json_encode($pgraStream);

        return [
            'hasil_ksa' => $hasilKSA,
            'hasil_pgra' => $hasilPGRA,
            'hasil_desimal' => $hasil_desimal,  // simpan array, bukan hex
            'hasil_enkripsi' => $res,           // hasil enkripsi dalam string
        ];
    }


    public static function decryptRc4UuidProses(string $encoded, string $key): array
    {
        // Ubah base64 URL-safe ke format standar
        $base64 = strtr($encoded, '-_', '+/');
        $encrypted = base64_decode($base64);

        $s = range(0, 255);
        $j = 0;
        $res = '';
        $hasil_desimal = [];

        // --- Key Scheduling Algorithm (KSA) ---
        $keyLength = strlen($key);
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $keyLength])) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];
        }

        $hasilKSA = json_encode($s);

        // --- Pseudo-Random Generation Algorithm (PGRA) ---
        $i = $j = 0;
        $pgraStream = [];
        $dataLength = strlen($encrypted);

        for ($y = 0; $y < $dataLength; $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            [$s[$i], $s[$j]] = [$s[$j], $s[$i]];

            $k = $s[($s[$i] + $s[$j]) % 256];
            $pgraStream[] = $k;

            $xor = ord($encrypted[$y]) ^ $k;
            $hasil_desimal[] = $xor;  // simpan nilai desimal hasil XOR
            $res .= chr($xor);
        }

        $hasilPGRA = json_encode($pgraStream);

        return [
            'hasil_ksa' => $hasilKSA,
            'hasil_pgra' => $hasilPGRA,
            'hasil_desimal' => $hasil_desimal,  // array angka desimal
            'hasil_dekripsi' => $res,           // hasil teks dekripsi asli
        ];
    }


}
