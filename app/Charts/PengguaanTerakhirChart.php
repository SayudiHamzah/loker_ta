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

        // Buat label hari dan jam (7 hari × 24 jam = 168 label)
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $labels = [];

        for ($d = 1; $d <= 7; $d++) { // DAYOFWEEK: 1=Sunday, ..., 7=Saturday
            for ($h = 0; $h <= 23; $h++) {
                $labels[] = $days[$d % 7] . ' ' . str_pad($h, 2, '0', STR_PAD_LEFT);
            }
        }

        $this->labels($labels);

        foreach ($users as $user) {
            // Ambil jumlah penggunaan per hari dan jam
            $data = DB::table('model_logs')
                ->select(
                    DB::raw('DAYOFWEEK(waktu_penggunaan) as hari'),
                    DB::raw('HOUR(waktu_penggunaan) as jam'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('user_id', $user->id)
                ->groupBy('hari', 'jam')
                ->get();

            // Bentuk array data akses
            $userData = array_fill(0, 168, 0); // 7 hari x 24 jam

            foreach ($data as $row) {
                $index = (($row->hari % 7) * 24) + $row->jam;
                $userData[$index] = $row->total;
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
