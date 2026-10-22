<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_statuses', function (Blueprint $table) {
            if (! Schema::hasColumn('invoice_statuses', 'cs_number')) {
                $table->string('cs_number')->nullable()->after('purchase_id');
            }
            if (! Schema::hasColumn('invoice_statuses', 'lot_number')) {
                $table->string('lot_number')->nullable()->after('cs_number');
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Drop FKs if present, then make columns nullable
            $this->dropForeignIfExists('invoice_statuses', 'user_id');
            $this->dropForeignIfExists('invoice_statuses', 'purchase_id');

            DB::statement('ALTER TABLE invoice_statuses MODIFY user_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE invoice_statuses MODIFY purchase_id BIGINT UNSIGNED NULL');

            Schema::table('invoice_statuses', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('purchase_id')->references('id')->on('purchases')->nullOnDelete();
            });
        } elseif ($driver === 'sqlite') {
            // SQLite: columns already exist; nullability is loose enough for inserts with null
        }
    }

    public function down(): void
    {
        Schema::table('invoice_statuses', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_statuses', 'lot_number')) {
                $table->dropColumn('lot_number');
            }
            if (Schema::hasColumn('invoice_statuses', 'cs_number')) {
                $table->dropColumn('cs_number');
            }
        });
    }

    private function dropForeignIfExists(string $table, string $column): void
    {
        $database = DB::getDatabaseName();
        $constraints = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$database, $table, $column]
        );

        foreach ($constraints as $row) {
            $name = $row->CONSTRAINT_NAME ?? null;
            if ($name) {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$name}`");
            }
        }
    }
};
