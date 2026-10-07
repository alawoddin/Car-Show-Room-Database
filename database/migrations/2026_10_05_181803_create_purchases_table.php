<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // User / Investor
        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        // Vehicle Information
        $table->date('buying_date');
        $table->string('lot_number')->nullable();
        $table->string('vin')->unique();
        $table->string('cylinder')->nullable();
        $table->string('color')->nullable();
        $table->string('make');
        $table->string('model');

        // Purchase Costs
        $table->decimal('buying_fee', 15, 2)->default(0);
        $table->decimal('towing_fee', 15, 2)->default(0);
        $table->decimal('shipping', 15, 2)->default(0);

        // Calculated Costs
        $table->decimal('total_aed', 15, 2)->default(0);
        $table->decimal('clearing', 15, 2)->default(0);
        $table->decimal('extra_charges', 15, 2)->default(0);
        $table->decimal('custom_duty', 15, 2)->default(0);
        $table->decimal('grand_total', 15, 2)->default(0);

        // Sale Information
        $table->decimal('selling_price', 15, 2)->default(0);
        $table->decimal('profit', 15, 2)->default(0);
        $table->string('shipping_company')->nullable();
        $table->string('commission')->nullable();

        // Other Information
        $table->string('bill_no')->nullable();
        $table->date('date_of_arriving')->nullable();
        $table->string('location')->nullable();
        $table->string('customer_name')->nullable();

        // Vehicle Status
        $table->enum('status', [
            'Purchased',
            'Loaded',
            'Shipped',
            'Delivered',
            'On Hand',
            'At UAE',
            'Sold'
        ])->default('Purchased');

        $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
