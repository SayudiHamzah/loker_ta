<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\PengguaanTerakhirChart;
use App\Models\ModelLoker;
use App\Models\User;
use Carbon\Carbon;
// use DB;
use Illuminate\Support\Facades\DB;

class ManualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $chart = new PengguaanTerakhirChart();
        $jumlahUser = DB::table('users')->count();
        $penggunaanHarian = DB::table('model_logs')
            ->whereDate('waktu_penggunaan', Carbon::today())
            ->count();
        $totalLoker = 4;
        $terpakaiOlehUser1 = DB::table('model_lokers')
            ->where('user_id', 1)
            ->count();
        $penggunaanLoker = $totalLoker - $terpakaiOlehUser1;
        $lockers = ModelLoker::select('id', 'user_id', 'status', 'name_locker')
            ->get()
            // ->keyBy('user_id')
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'name_locker' => $item->name_locker,
                    'user_id' => $item->user_id
                ];
            })
            ->toArray();
        $haskAkses = ModelLoker::select('id', 'user_id', 'status', 'name_locker')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'name_locker' => $item->name_locker,
                    'user_id' => $item->user_id
                ];
            })
            ->toArray();

        // dd($haskAkses);

        return view('manual.index', compact('chart', 'jumlahUser', 'penggunaanHarian', 'penggunaanLoker', 'lockers', 'haskAkses'));
    }

    // public function updateStatus($id, Request $request)
    // {
    //     // dd($request);
    //     $dataloker = ModelLoker::findOrFail($id);
    //     $EnQr =   ServiceLoker::generateRc4Uuid();

    //     $dataQr = ModelQRcode::create([
    //         'user_id' => $request->user_id,
    //         'qrcode' => $EnQr,
    //     ]);
    //     // Simpan ke tabel loker
    //     $dataloker->update([
    //         'status' => $request->status,
    //         'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
    //     ]);

    //     ModelLog::create([
    //         'user_id' => $request->user_id,
    //         'loker_id' => $request->id,
    //         'qrcode_id' => $dataQr->id, // Gunakan qrcode_id jika field kamu foreign key
    //         'waktu_penggunaan' => Carbon::now()->format('H:i:s'),
    //     ]);
    //     return redirect()->route('loker.show', $dataloker->id)
    //         ->with('success', 'Loker Updated: ' . $dataloker->name_locker);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
