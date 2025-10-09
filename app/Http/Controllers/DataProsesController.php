<?php

namespace App\Http\Controllers;

use App\Models\rc4Model;
use App\Models\Decryption;
use App\Models\Encryption;
use Illuminate\Http\Request;

class DataProsesController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $searchUser = $request->search_user;
        $searchLocker = $request->search_locker;

        // Filter ENKRIPSI
        $encryptions = Encryption::with('user')
            ->when($searchUser, function ($query, $searchUser) {
                $query->whereHas('user', function ($q) use ($searchUser) {
                    $q->where('name', 'like', '%' . $searchUser . '%');
                });
            })
            ->when($searchLocker, function ($query, $searchLocker) {
                $query->where('name_locker', 'like', '%' . $searchLocker . '%');
            })
            ->latest()
            ->get();

        // Filter DEKRIPSI
        $decryptions = Decryption::with('user')
            ->when($searchUser, function ($query, $searchUser) {
                $query->whereHas('user', function ($q) use ($searchUser) {
                    $q->where('name', 'like', '%' . $searchUser . '%');
                });
            })
            ->when($searchLocker, function ($query, $searchLocker) {
                $query->where('name_locker', 'like', '%' . $searchLocker . '%');
            })
            ->latest()
            ->get();

        return view('data-proses.index', compact('encryptions', 'decryptions', 'searchUser', 'searchLocker'));
    }

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
    public function show($type, $id)
    {
        try {
            if ($type === 'encryption') {
                $item = Encryption::with('user')->findOrFail($id);
                // dd($item);
                $title = 'Detail Enkripsi';
                // $uuidEncode = rc4Model::where('uuid')findOrFail($item->uuid);
                $uuidEncode = rc4Model::where('uuid', $item->uuid)->firstOrFail();

                // dd($uuidEncode->uuid_encode);
                $encodeVal = $uuidEncode->uuid_encode;
            } elseif ($type === 'decryption') {
                $item = Decryption::with('user')->findOrFail($id);
                $title = 'Detail Dekripsi';
                $uuidEncode = rc4Model::where('uuid', $item->uuid)->firstOrFail();
                $encodeVal = $uuidEncode->uuid_encode;

            } else {
                abort(404);
            }

            return view('data-proses.detail', compact('item', 'type', 'title', 'encodeVal'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Data tidak ditemukan');
        }
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
