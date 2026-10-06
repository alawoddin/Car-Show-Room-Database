<?php

namespace App\Imports;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchasesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Find user by name
            $user = User::where('name', $row['user'])->first();

            // If user doesn't exist, skip this row
            if (!$user) {
                continue;
            }

            Purchase::create([

                // User
                'user_id' => $user->id,

                // Vehicle Information
                'buying_date' => $row['buying_date'] ?? null,
                'lot_number' => $row['lot_number'] ?? null,
                'vin' => $row['vin'] ?? null,
                'cylinder' => $row['cylinder'] ?? null,
                'color' => $row['color'] ?? null,
                'make' => $row['make'] ?? null,
                'model' => $row['model'] ?? null,

                // Purchase Costs
                'buying_fee' => $row['buying_fee'] ?? 0,
                'towing_fee' => $row['towing_fee'] ?? 0,
                'shipping' => $row['shipping'] ?? 0,

                // Calculated Costs
                'total_aed' => $row['total_aed'] ?? 0,
                'clearing' => $row['clearing'] ?? 0,
                'extra_charges' => $row['extra_charges'] ?? 0,
                'custom_duty' => $row['custom_duty'] ?? 0,
                'grand_total' => $row['grand_total'] ?? 0,

                // Sale Information
                'selling_price' => $row['selling_price'] ?? 0,
                'profit' => $row['profit'] ?? 0,

                // Other Information
                'bill_no' => $row['bill_no'] ?? null,
                'date_of_arriving' => $row['date_of_arriving'] ?? null,
                'location' => $row['location'] ?? null,
                'customer_name' => $row['customer_name'] ?? null,

                // Status
                'status' => $row['status'] ?? 'Purchased',

                // Description
                'description' => $row['description'] ?? null,
            ]);
        }
    }
}