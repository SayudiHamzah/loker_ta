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
// use App\Services\ServiceLoker;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class ModelLokerController extends Controller
{

    public function index()
    {
        $datas = ModelLoker::get()->all();

        return view('loker.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datas = User::pluck('name', 'id')->toArray(); // [id => name]
        $dataUser = ModelLoker::pluck('user_id')->toArray(); // [id, id, id...]

        // Filter agar hanya user yang tidak ada di ModelLoker
        $datas = array_filter($datas, function ($name, $id) use ($dataUser) {
            return !in_array($id, $dataUser);
        }, ARRAY_FILTER_USE_BOTH);
        // dd($datas);
        return view('loker.created', compact('datas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Lebih baik sekalian validasi exist
            'status' => 'required'
        ]);
        $key = TokenModel::where('tokenable_id', $request->user_id)->first();

        if (ModelLoker::count() > 3) {
            // dd("atas");
            return back()->with('error', 'Loker Max 4');
        } else {
            $EnQr =   ServiceLoker::generateRc4Uuid($key->token);
            $uniqCode = 'LOKER-' . Str::upper(Str::random(6));
            // Simpan ke tabel qrcodes
            $dataQr = ModelQRcode::create([
                'user_id' => $request->user_id,
                'qrcode' => $EnQr,
            ]);

            // Simpan ke tabel loker
            $dataLoker =  ModelLoker::create([
                'name_locker' => $uniqCode,
                'status' => $request->status,
                'user_id' => $request->user_id,
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            ]);

            ModelLog::create([
                'user_id' => $request->user_id,
                'loker_id' => $dataLoker->id,
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
                'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
            ]);
            // return view('loker.created', compact('datas'));
            return redirect()->route('loker.index')->with('success', 'Loker created: ' . $uniqCode);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(ModelLoker $modelLoker, $id)
    {

        $data = ModelLoker::with('qrcode')->findOrFail($id);
        // dd($data);
        $datauser = User::findOrFail($data->qrcode->user_id);
        // dd($datauser);

        return view('loker.show', compact('data', 'datauser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $datas = ModelLoker::with('qrcode')->findOrFail($id);
        $dataUser = ModelLoker::pluck('user_id')->toArray(); // [id, id, id...]
        $dataA = User::pluck('name', 'id')->toArray(); // [id => name]
        $dataA = array_filter($dataA, function ($name, $id) use ($dataUser) {
            return !in_array($id, $dataUser);
        }, ARRAY_FILTER_USE_BOTH);


        return view('loker.edit', compact('datas', 'dataUser', 'dataA'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dataloker = ModelLoker::findOrFail($id);
        $key = TokenModel::where('tokenable_id', $request->user_id)->first();

        // Cek apakah ada perubahan
        if (
            $dataloker->user_id == $request->user_id ||
            $dataloker->status == $request->status
        ) {
            // dd("atas");
            return redirect()->back()->with('error', 'Tidak ada perubahan yang disimpan.');
        } else {
            // dd("bwh");

            // dd($dataloker);
            // buat baru qr
            $EnQr =   ServiceLoker::generateRc4Uuid($key->token);

            $dataQr = ModelQRcode::create([
                'user_id' => $request->user_id,
                'qrcode' => $EnQr,
            ]);
            // Simpan ke tabel loker
            $dataloker->update([
                'status' => $request->status,
                'user_id' => $request->user_id,
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            ]);

            ModelLog::create([
                'user_id' => $request->user_id,
                'loker_id' => $request->id,
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
                'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
            ]);

            return redirect()->route('loker.index')->with('success', 'Loker Updated: ' . $dataloker->name_locker);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $da = ModelLoker::with('qrcode')->findOrFail($id);

        // dd($da);
        $data = ModelLoker::destroy($id);

        // $dataQr = ModelQRcode::destroy($da- );
        return redirect()->route('loker.index')->with('success', 'Loker deleted successfully.');
    }

    public function history($id)
    {
        $datalog = ServiceApi::history($id);
        return view('loker.history', compact("datalog"));
    }

    public function updateStatus($id, Request $request)
    {
        // dd($request);
        $key = TokenModel::where('tokenable_id', $request->user_id)->first();

        $dataloker = ModelLoker::findOrFail($id);

        $EnQr =   ServiceLoker::generateRc4Uuid($key->token);

        $dataQr = ModelQRcode::create([
            'user_id' => $request->user_id,
            'qrcode' => $EnQr,
        ]);
        // Simpan ke tabel loker
        $dataloker->update([
            'status' => $request->status,
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
        ]);

        ModelLog::create([
            'user_id' => $request->user_id,
            'loker_id' => $request->id,
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
        ]);
        return redirect()->route('loker.show', $dataloker->id)
            ->with('success', 'Loker Updated: ' . $dataloker->name_locker);
    }
    public function updateStatusDash($id, $user_id)
    {
        // dd($request);
        $key = TokenModel::where('tokenable_id', $user_id)->first();

        $dataloker = ModelLoker::findOrFail($id);
        $EnQr =   ServiceLoker::generateRc4Uuid($key->token);

        $dataQr = ModelQRcode::create([
            'user_id' => $user_id,
            'qrcode' => $EnQr,
        ]);
        // Simpan ke tabel loker
        if ($dataloker->status == "1") {
            $dataloker->update([
                'status' => "0",
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            ]);
        } else {
            $dataloker->update([
                'status' => 1,
                'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            ]);
        }


        ModelLog::create([
            'user_id' => $user_id,
            'loker_id' => $id,
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
        ]);
        // return redirect()->route('loker.show', $dataloker->id)
        //     ->with('success', 'Loker Updated: ' . $dataloker->name_locker);
        return response()->json([
            'success' => true,
            'message' => 'Akses loker berhasil diubah.'
        ]);
    }
    public function deleteStatus(Request $request)
    {
        // dd("masuk");
        $dataloker = ModelLoker::findOrFail($request->id);
        $key = TokenModel::where('tokenable_id', $dataloker->user_id)->first();

        $EnQr =   ServiceLoker::generateRc4Uuid($key->token);

        $dataQr = ModelQRcode::create([
            'user_id' => 1,
            'qrcode' => $EnQr,
        ]);
        // Simpan ke tabel loker
        $dataloker->update([
            'user_id' => 1,
            'status' => "0",
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
        ]);

        ModelLog::create([
            'user_id' => 1,
            'status_activitas' => "1",
            'loker_id' => $request->id,
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Akses loker berhasil dihapus.'
        ]);
    }

    public function deleteStatusApi($loker_id, $user_id)
    {
        // dd("masuk");
        $dataloker = ModelLoker::findOrFail($loker_id);
        $key = TokenModel::where('tokenable_id', $user_id)->first();

        $EnQr =   ServiceLoker::generateRc4Uuid($key->token);

        $dataQr = ModelQRcode::create([
            'user_id' => $user_id,
            'qrcode' => $EnQr,
        ]);
        // Simpan ke tabel loker
        $dataloker->update([
            'user_id' => $user_id,
            'status' => "0",
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
        ]);

        ModelLog::create([
            'user_id' => $user_id,
            'status_activitas' => "1",
            'loker_id' => $loker_id,
            'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
            'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Akses loker berhasil dihapus.'
        ]);
    }
}
