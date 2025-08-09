<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenModel extends Model
{
    //
    protected $table = 'personal_access_tokens';
    protected $fillable = ['token','tokenable_id'];
}
