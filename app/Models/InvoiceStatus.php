<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
