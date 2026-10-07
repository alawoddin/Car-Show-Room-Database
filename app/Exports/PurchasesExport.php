<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromCollection, WithHeadings
{
    /**
     * Export all purchases.
     */
    public function collection()
    {
        return Purchase::with('user')->get()->map(function ($purchase) {

            return [
                $purchase->user ? $purchase->user->name : '',

                $purchase->buying_date,
                $purchase->lot_number,
                $purchase->vin,
                $purchase->cylinder,
                $purchase->color,
                $purchase->make,
                $purchase->model,
                $purchase->shipping_company,

                $purchase->buying_fee,
                $purchase->towing_fee,
                $purchase->shipping,

                $purchase->total_aed,
                $purchase->clearing,
                $purchase->extra_charges,
                $purchase->custom_duty,
                $purchase->grand_total,

                $purchase->selling_price,
                $purchase->profit,

                $purchase->bill_no,
                $purchase->date_of_arriving,
                $purchase->location,
                $purchase->customer_name,

                $purchase->status,
                $purchase->description,
            ];
        });
    }


    /**
     * Excel column headings.
     */
    public function headings(): array
    {
        return [

            'User',

            'Buying Date',
            'Lot Number',
            'VIN',
            'Cylinder',
            'Color',
            'Make',
            'Model',
            'Shipping Company',

            'Buying Fee',
            'Towing Fee',
            'Shipping',

            'Total AED',
            'Clearing',
            'Extra Charges',
            'Custom Duty',
            'Grand Total',

            'Selling Price',
            'Profit',

            'Bill No',
            'Date Of Arriving',
            'Location',
            'Customer Name',

            'Status',
            'Description',
        ];
    }
}
