<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('invoice_payments')) {
            Schema::create('invoice_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_status_id')
                    ->constrained('invoice_statuses')
                    ->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->date('payment_date')->nullable();
                $table->string('note')->nullable();
                $table->timestamps();
            });
        }

        // Move existing single paid_amount into payment history.
        if (Schema::hasTable('invoice_payments')) {
            $rows = DB::table('invoice_statuses')
                ->where('paid_amount', '>', 0)
                ->get(['id', 'paid_amount', 'due_date', 'created_at']);

            foreach ($rows as $row) {
                $exists = DB::table('invoice_payments')
                    ->where('invoice_status_id', $row->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                DB::table('invoice_payments')->insert([
                    'invoice_status_id' => $row->id,
                    'amount' => $row->paid_amount,
                    'payment_date' => $row->due_date ?? now()->toDateString(),
                    'note' => 'Initial payment',
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
