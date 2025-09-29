<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Decryption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'key',
        'uuid',
        'hasil_ksa',
        'hasil_pgra',
        'hasil_desimal',
        'name_locker'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
