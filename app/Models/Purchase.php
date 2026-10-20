<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::created(function (Purchase $purchase) {
            if ($purchase->user_id && ! $purchase->users()->exists()) {
                $purchase->users()->syncWithoutDetaching([$purchase->user_id]);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($purchases) use ($userId) {
            $purchases->where('user_id', $userId)
                ->orWhereHas('users', function ($users) use ($userId) {
                    $users->where('users.id', $userId);
                });
        });
    }

    public function selectedUserIds(): array
    {
        $ids = $this->relationLoaded('users')
            ? $this->users->pluck('id')
            : $this->users()->pluck('users.id');

        if ($ids->isEmpty() && $this->user_id) {
            return [(int) $this->user_id];
        }

        return $ids->map(fn ($id) => (int) $id)->all();
    }

    public function buyerShares(?float $amount = null)
    {
        $buyers = $this->relationLoaded('users')
            ? $this->users
            : $this->users()->get();

        if ($buyers->isEmpty() && $this->user) {
            $buyers = collect([$this->user]);
        }

        $buyers = $buyers->values();
        $count = $buyers->count();

        if ($count === 0) {
            return collect();
        }

        $total = round($amount ?? (float) $this->grand_total, 2);
        $base = round($total / $count, 2);

        return $buyers->map(function ($buyer, $index) use ($count, $total, $base) {
            $amount = $index === $count - 1
                ? round($total - ($base * ($count - 1)), 2)
                : $base;

            return [
                'user' => $buyer,
                'amount' => $amount,
            ];
        });
    }

    public function buyerNames(): string
    {
        $names = $this->relationLoaded('users')
            ? $this->users->pluck('name')
            : $this->users()->pluck('name');

        if ($names->isEmpty()) {
            return $this->user?->name ?? 'Unknown User';
        }

        return $names->implode(', ');
    }

    public function invoiceStatuses()
    {
        return $this->hasMany(InvoiceStatus::class);
    }
}
