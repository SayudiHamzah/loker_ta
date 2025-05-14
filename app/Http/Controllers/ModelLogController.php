<?php

namespace App\Http\Controllers;

use App\Models\ModelLog;
use Illuminate\Http\Request;

class ModelLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datalog = ModelLog::with('qrcode', 'user', 'loker')->get();
        // dd($datalog);
        return view('datalog.index', compact("datalog"));

        // return view('datalog.index', compact('datas'));
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
    public function show(ModelLog $modelLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModelLog $modelLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModelLog $modelLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelLog $modelLog)
    {
        //
    }
}
