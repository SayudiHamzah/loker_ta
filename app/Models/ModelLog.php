<?php

namespace App\Models;

use App\Models\ModelQRcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ModelLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'loker_id', 'waktu_penggunaan', 'qrcode_id'];

    public function qrcode()
    {
        return $this->belongsTo(ModelQRcode::class, 'qrcode_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function loker()
    {
        return $this->belongsTo(ModelLoker::class, 'loker_id');
    }
}
