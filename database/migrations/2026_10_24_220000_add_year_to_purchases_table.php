<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('purchases', 'year')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->string('year', 10)->nullable()->after('model');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('purchases', 'year')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropColumn('year');
            });
        }
    }
};
