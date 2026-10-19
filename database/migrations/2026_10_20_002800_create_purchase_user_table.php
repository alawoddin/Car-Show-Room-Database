<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['purchase_id', 'user_id']);
        });

        $now = now();

        DB::table('purchases')
            ->select('id', 'user_id')
            ->orderBy('id')
            ->chunkById(100, function ($purchases) use ($now) {
                $rows = [];

                foreach ($purchases as $purchase) {
                    if (! $purchase->user_id) {
                        continue;
                    }

                    $rows[] = [
                        'purchase_id' => $purchase->id,
                        'user_id' => $purchase->user_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($rows !== []) {
                    DB::table('purchase_user')->insert($rows);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_user');
    }
};
