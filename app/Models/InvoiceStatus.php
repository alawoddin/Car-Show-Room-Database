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

    /**
     * Admin invoice total = purchase cost (grand total).
     */
    public function invoiceTotal(): float
    {
        return (float) ($this->purchase?->grand_total ?? 0);
    }

    public function totalPaid(): float
    {
        return round((float) ($this->paid_amount ?? 0), 2);
    }

    public function remainingAmount(): float
    {
        return max(0, round($this->invoiceTotal() - $this->totalPaid(), 2));
    }
}
