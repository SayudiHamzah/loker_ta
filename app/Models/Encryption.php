<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Encryption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'key',
        'uuid',
        'hasil_ksa',
        'hasil_pgra',
        'hasil_desimal',
        'hasil_enkripsi',
        'name_locker'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
