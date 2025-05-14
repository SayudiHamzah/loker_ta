<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelQRcode;
class ModelLoker extends Model
{
    use HasFactory;
    protected $fillable = ['name_locker', 'status', 'qrcode_id', 'user_id'];

    public function qrcode()
    {
        return $this->belongsTo(ModelQRcode::class, 'qrcode_id');
    }
}
