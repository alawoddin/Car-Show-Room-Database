<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class User_Capitals extends Model
{
    protected $table = 'user_capitals';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}