<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('purchase_user')) {
            Schema::create('purchase_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['purchase_id', 'user_id']);
            });
        }

        Schema::table('purchases', function (Blueprint $table) {
            if (! Schema::hasColumn('purchases', 'container_number')) {
                $table->string('container_number')->nullable()->after('location');
            }

            if (! Schema::hasColumn('purchases', 'arrival_date')) {
                $table->date('arrival_date')->nullable()->after('container_number');
            }

            if (! Schema::hasColumn('purchases', 'vin_link')) {
                $table->string('vin_link')->nullable()->after('arrival_date');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_user');

        Schema::table('purchases', function (Blueprint $table) {
            foreach (['container_number', 'arrival_date', 'vin_link'] as $column) {
                if (Schema::hasColumn('purchases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
