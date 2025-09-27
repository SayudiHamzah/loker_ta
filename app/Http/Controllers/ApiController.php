<?php

namespace App\Http\Controllers;

use App\Models\ModelLog;
use App\Models\ModelLoker;
use App\Models\ModelQRcode;
use App\Models\TokenModel;
use App\Models\User;
use App\Services\ServiceApi;
use App\Services\ServiceLoker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Models\User;

class ApiController extends Controller
{

    public function historyDatalog($id, $user_id)
    {

        $response = ServiceApi::history($id);
        // dd($response);
        $datalog = collect($response)
            ->where('user_id', $user_id)
            ->sortByDesc('created_at')
            ->values();



        return response()->json([
            'success' => true,
            'message' => 'Data history berhasil diambil',
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
            'expires_at' => Carbon::now()->addDays(30),
        ]);
        $data_created = $user->created_at;
        $data_hak = $user->status;
        $gabung = $data_hak . $data_created;
        $resultKey = str_replace(['-', ' ', ':'], '', $gabung);

        $modelLokerId = ModelLoker::where('user_id', $user->id)->value('id');

        return response()->json([
            'status_access' => $modelLokerId ? true : false,
            'access_token' => $plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->expires_at,
            'key' => $resultKey,
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
        $lastId = ModelLoker::where('user_id', Auth::id())
            ->latest('id')
            ->value('id');
        $qrcode = ModelLoker::where('user_id', Auth::id())
            ->with('qrcode')
            ->latest('id')
            ->first()?->qrcode?->qrcode;


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
            'data' => $datas[0],
            'lastId'=>$lastId,
            "qrcode"=>$qrcode
        ]);
    }
    public function show($id, $qrcode_id)
    {
        $data = ModelLoker::with('qrcode')->findOrFail($id);
        $response = ModelLoker::with('qrcode')->findOrFail($id)->makeHidden(['qrcode_id']);
        // dd($response->qrcode->qrcode);
        $datauser = User::findOrFail($response->user_id);
        $data_hak = $datauser->status;
        $data_created = $datauser->created_at;
        $gabung = $data_hak . $data_created;
        $resultKey = str_replace(['-', ' ', ':'], '', $gabung);
        $a = ServiceApi::history($id);
        // dd($response->qrcode->id);
        // $resultMode =  ModelLog::where($response->qrcode->id, "qrcode_id");
        $resultMode = ModelLog::where("qrcode_id", $qrcode_id)->get();


        // dd($resultMode);

        // dd($response->qrcode->qrcode );
        $qr = ServiceLoker::decryptRc4Uuid($response->qrcode->qrcode, $resultKey);
        // dd($qr);
        $datauser = User::findOrFail($data->qrcode->user_id);
        return response()->json([
            'success' => true,
            'message' => 'Data loker milik user berhasil diambil.',
            'data_loker' => $response,
            'datauser' => $datauser,
            'qrcode' => $qr,
            "datalog" =>  $resultMode[0]
        ]);
    }



    public function updateStatusByCode($user_id, $code)
    {
        // $allLoker = ModelLoker::with('qrcode')->get();
        // dd($code);
        $datauser = User::findOrFail($user_id);
        // dd($datauser);
        $data_hak = $datauser->status;
        $data_created = $datauser->created_at;
        $gabung = $data_hak . $data_created;
        $resultKey = str_replace(['-', ' ', ':'], '', $gabung);
        $matchedLoker = ModelLoker::with('qrcode')
            ->where('user_id', $user_id)
            ->get()
            ->first(function ($loker) use ($code, $resultKey) {
                if (!$loker->qrcode) return false;
                $decryptedDb = ServiceLoker::decryptRc4Uuid($loker->qrcode->qrcode, $resultKey);
                $decryptedUser = ServiceLoker::decryptRc4Uuid($code, $resultKey);
                // dd($decrypted);
                return $decryptedDb === $decryptedUser;
            });

        // Jika tidak ditemukan, kembalikan respons error
        if (!$matchedLoker) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR tidak ditemukan atau tidak valid.',
            ], 404);
        }

        // dd($matchedLoker->user_id);

        // Jika cocok, buat qrcode baru dan update status
        // dd("sss");
        $newQrCode = ServiceLoker::generateRc4Uuid($user_id);
        // dd($newQrCode);
        $dataQr = ModelQRcode::create([
            'user_id' => $matchedLoker->user_id,
            'qrcode' => $newQrCode,
        ]);
        // dd($dataQr);

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
            'status' => $matchedLoker->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data loker berhasil diupdate.',
            'data_loker' => $matchedLoker,
            'status' => $matchedLoker->status,
        ]);
    }
}
