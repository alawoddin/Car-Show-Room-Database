<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_loans')) {
            Schema::create('customer_loans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->date('due_date')->nullable();
                $table->enum('status', ['Paid', 'Open', 'Overdue'])->default('Open');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('customer_loan_payments')) {
            Schema::create('customer_loan_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_loan_id')
                    ->constrained('customer_loans')
                    ->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->date('payment_date')->nullable();
                $table->string('note')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_loan_payments');
        Schema::dropIfExists('customer_loans');
    }
};
