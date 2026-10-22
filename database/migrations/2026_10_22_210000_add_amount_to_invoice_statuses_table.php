<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('invoice_statuses', 'amount')) {
            Schema::table('invoice_statuses', function (Blueprint $table) {
                $table->decimal('amount', 15, 2)->default(0)->after('purchase_id');
            });
        }

        // Backfill from purchase grand_total where amount is still 0
        if (Schema::hasColumn('invoice_statuses', 'amount')) {
            $rows = DB::table('invoice_statuses')
                ->leftJoin('purchases', 'invoice_statuses.purchase_id', '=', 'purchases.id')
                ->where(function ($q) {
                    $q->whereNull('invoice_statuses.amount')
                        ->orWhere('invoice_statuses.amount', 0);
                })
                ->select('invoice_statuses.id', 'purchases.grand_total')
                ->get();

            foreach ($rows as $row) {
                DB::table('invoice_statuses')
                    ->where('id', $row->id)
                    ->update([
                        'amount' => (float) ($row->grand_total ?? 0),
                    ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('invoice_statuses', 'amount')) {
            Schema::table('invoice_statuses', function (Blueprint $table) {
                $table->dropColumn('amount');
            });
        }
    }
};
