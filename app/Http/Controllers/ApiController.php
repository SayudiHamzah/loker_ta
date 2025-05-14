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

    public function historyDatalog($id)
    {
        $datalog = ServiceApi::history($id);
        return response()->json([
            'success' => true,
            'message' => 'Data history berhasil diambil.',
            'data' => $datalog
        ]);
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

    public function updateStatus($id, $code)
    {
        // dd($request);
        // $dataloker = ModelLoker::findOrFail($id);
        $dataloker = ModelLoker::with('qrcode')->findOrFail($id);

        // dd($dataloker->qrcode->qrcode);

        //kode dari user
        $DekrU = ServiceLoker::decryptRc4Uuid($code);
        $DekrD = ServiceLoker::decryptRc4Uuid($dataloker->qrcode->qrcode);
        // dd($DekrU);

        if ($DekrD === $DekrU) {
            $EnQr =   ServiceLoker::generateRc4Uuid();
            $dataQr = ModelQRcode::create([
                'user_id' => $dataloker->user_id,
                'qrcode' => $EnQr,
            ]);
            // Simpan ke tabel loker
            if ($dataloker->status == '1') {
                $dataloker->update([
                    'status' => '0',
                    'qrcode_id' => $dataQr->id,
                ]);
            } else {
                $dataloker->update([
                    'status' => '1',
                    'qrcode_id' => $dataQr->id,
                ]);
            }

            ModelLog::create([
                'user_id' => $dataloker->user_id,
                'loker_id' => $id,
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
                'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data loker berhasil di update',
                'data_loker' => $dataloker,
                'status' => $dataloker->status,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data error',
                // 'data_loker' => $dataloker,
                // 'datauser' => $datauser,
            ]);
        }
    }
}
