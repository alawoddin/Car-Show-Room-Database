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

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class)->latest('payment_date')->latest('id');
    }

    /**
     * Customer loan total = sale price when sold, otherwise purchase cost.
     */
    public function invoiceTotal(): float
    {
        $purchase = $this->purchase;

        if (! $purchase) {
            return 0.0;
        }

        $selling = (float) ($purchase->selling_price ?? 0);

        if ($selling > 0) {
            return $selling;
        }

        return (float) ($purchase->grand_total ?? 0);
    }

    public function totalPaid(): float
    {
        if ($this->relationLoaded('payments')) {
            return round((float) $this->payments->sum('amount'), 2);
        }

        return round((float) $this->payments()->sum('amount'), 2);
    }

    public function remainingAmount(): float
    {
        return max(0, round($this->invoiceTotal() - $this->totalPaid(), 2));
    }

    public function syncPaidAmount(): void
    {
        $paid = $this->totalPaid();
        $total = $this->invoiceTotal();

        $this->forceFill([
            'paid_amount' => $paid,
            'status' => $paid >= $total && $total > 0 ? 'Paid' : 'Open',
        ])->save();
    }

    public function customerLabel(): string
    {
        return $this->purchase?->customer_name
            ?: $this->user?->name
            ?: 'Unknown Customer';
    }
}
