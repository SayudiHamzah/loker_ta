<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rc4Model extends Model
{
    protected $table = 'rc4';
    protected $fillable = ['uuid','uuid_rc4', 'uuid_encode'];
}
