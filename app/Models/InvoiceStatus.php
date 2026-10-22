<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function invoiceTotal(): float
    {
        $amount = (float) ($this->amount ?? 0);

        if ($amount > 0) {
            return $amount;
        }

        return (float) ($this->purchase?->grand_total ?? 0);
    }

    public function displayCsNumber(): string
    {
        return $this->cs_number
            ?: ($this->purchase?->vin ?? '-');
    }

    public function displayLotNumber(): string
    {
        return $this->lot_number
            ?: ($this->purchase?->lot_number ?? '-');
    }

    public function remainingAmount(): float
    {
        return max(0, $this->invoiceTotal() - (float) ($this->paid_amount ?? 0));
    }

    /**
     * Paid when paid_amount >= invoice amount.
     * Overdue when due date passed and not fully paid.
     * Otherwise Open.
     */
    public function resolveStatus(?float $paidAmount = null): string
    {
        $paid = $paidAmount ?? (float) ($this->paid_amount ?? 0);
        $total = $this->invoiceTotal();

        if ($total > 0 && $paid >= $total) {
            return 'Paid';
        }

        if ($paid > 0 && $total > 0 && $paid < $total) {
            // Partially paid still Open (or Overdue if past due)
        }

        if ($this->due_date && Carbon::parse($this->due_date)->startOfDay()->lt(now()->startOfDay())) {
            return 'Overdue';
        }

        return 'Open';
    }

    public function syncStatus(): void
    {
        $status = $this->resolveStatus();

        if ($this->status !== $status) {
            $this->forceFill(['status' => $status])->saveQuietly();
        }
    }
}
