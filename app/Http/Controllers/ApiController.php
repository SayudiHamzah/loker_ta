<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ServiceLoker;
use App\Services\ServiceApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ModelLoker;
use App\Models\ModelLog;
use App\Models\ModelQRcode;
// use App\Models\User;

class ApiController extends Controller
{

    public function historyDatalog($id, $user_id)
    {

        $response = ServiceApi::history($id);
        $datalog = collect($response)
            ->where('user_id', $user_id)
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Data history berhasil diambil.',
            'data' => $datalog
        ]);
    }
    public function inforelay()
    {

        // $response = ModelLoker::all();/


        $response = ModelLoker::all()->makeHidden(['qrcode_id', 'user_id', 'created_at', 'updated_at']);
        return response()->json($response);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Optional: revoke old tokens
        $user->tokens()->delete();

        // Create new token
        $plainTextToken = bin2hex(random_bytes(32));
        $tokenResult = $user->tokens()->create([
            'name' => 'auth_token',
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        $modelLokerId = ModelLoker::where('user_id', $user->id)->value('id');

        return response()->json([
            'status_access' => $modelLokerId ? true : false,
            'access_token' => $plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->expires_at,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'loker_id' => $modelLokerId ?? "-",
            ],
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    public function loker_akses(Request $request)
    {
        $datas = ModelLoker::where('user_id', Auth::id())->get();

        if ($datas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data loker untuk user ini.',
                // 'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data loker milik user berhasil diambil.',
            'data' => $datas[0]
        ]);
    }
    public function show($id)
    {
        $data = ModelLoker::with('qrcode')->findOrFail($id);
        $datauser = User::findOrFail($data->qrcode->user_id);
        return response()->json([
            'success' => true,
            'message' => 'Data loker milik user berhasil diambil.',
            'data_loker' => $data,
            'datauser' => $datauser,

        ]);
    }

    public function updateStatusByCode($code)
    {
        // Dekripsi kode yang dikirim user
        $decryptedUserCode = ServiceLoker::decryptRc4Uuid($code);

        // Ambil semua data loker dengan relasi qrcode
        $allLoker = ModelLoker::with('qrcode')->get();

        // Temukan data loker yang memiliki qrcode match setelah dekripsi
        $matchedLoker = null;

        foreach ($allLoker as $loker) {
            if (!$loker->qrcode) continue;

            $decryptedDbCode = ServiceLoker::decryptRc4Uuid($loker->qrcode->qrcode);

            if ($decryptedDbCode === $decryptedUserCode) {
                $matchedLoker = $loker;
                break;
            }
        }

        // Jika tidak ditemukan, kembalikan respons error
        if (!$matchedLoker) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR tidak ditemukan atau tidak valid.',
            ], 404);
        }

        // Jika cocok, buat qrcode baru dan update status
        $newQrCode = ServiceLoker::generateRc4Uuid();
        $dataQr = ModelQRcode::create([
            'user_id' => $matchedLoker->user_id,
            'qrcode' => $newQrCode,
        ]);

        $newStatus = $matchedLoker->status == '1' ? '0' : '1';

        $matchedLoker->update([
            'status' => $newStatus,
            'qrcode_id' => $dataQr->id,
        ]);

        ModelLog::create([
            'user_id' => $matchedLoker->user_id,
            'loker_id' => $matchedLoker->id,
            'qrcode_id' => $dataQr->id,
            'waktu_penggunaan' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data loker berhasil diupdate.',
            'data_loker' => $matchedLoker,
            'status' => $matchedLoker->status,
        ]);
    }
}
