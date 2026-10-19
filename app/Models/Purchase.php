<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

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
