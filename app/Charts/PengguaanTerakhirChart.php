<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class PengguaanTerakhirChart extends Chart
{
    /**
     * Initia
     *
     * @return void
     */
    public function __construct()
{
    parent::__construct();

    $users = DB::table('users')->get();

    // Label 7 hari saja
    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $this->labels($days);

    foreach ($users as $user) {
        // Ambil log terakhir per user
        $latestLog = DB::table('model_logs')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->first();

        // Siapkan array data 7 hari (0)
        $userData = array_fill(0, 7, 0);

        if ($latestLog) {
            // Ambil hari dari tanggal akses terakhir (0 = Minggu, 6 = Sabtu)
            $dayIndex = \Carbon\Carbon::parse($latestLog->created_at)->dayOfWeek;
            $userData[$dayIndex] = 1;
        }

        $this->dataset($user->name, 'bar', $userData)
            ->backgroundColor($this->randomColor())
            ->color('rgba(0, 0, 0, 1)');
    }
}


    protected function randomColor()
    {
        return 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ',0.6)';
    }
}
