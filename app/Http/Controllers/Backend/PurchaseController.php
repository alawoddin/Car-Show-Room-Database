<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PurchasesExport;
use App\Http\Controllers\Controller;
use App\Imports\PurchasesImport;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    /**
     * Display all purchases.
     */
    public function AllPurchases()
    {
        $purchases = Purchase::with('user')
            ->latest()
            ->get();

        return view(
            'admin.purchase.all_purchases',
            compact('purchases')
        );
    }

    /**
     * Display the add purchase form.
     */
    public function AddPurchase()
    {
        $users = User::orderBy('name')->get();

        return view(
            'admin.purchase.add_purchase',
            compact('users')
        );
    }

    /**
     * Store a new purchase.
     */
    public function StorePurchase(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'buying_date' => [
                'required',
                'date',
            ],

            'lot_number' => [
                'required',
                'string',
                'max:255',
            ],

            'vin' => [
                'required',
                'string',
                'max:255',
            ],

            'vin_link' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'cylinder' => [
                'nullable',
                'string',
                'max:255',
            ],

            'color' => [
                'nullable',
                'string',
                'max:255',
            ],

            'make' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shipping_company' => [
                'nullable',
                'string',
                'max:255',
            ],

            'commission' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'buying_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'towing_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'shipping' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'clearing' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'extra_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'container_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'arrival_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Purchased',
                    'Loaded',
                    'Shipped',
                    'Delivered',
                    'On Hand',
                    'At UAE',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        /*
         * Calculate purchase totals on the server.
         * Do not trust totals submitted by the browser.
         *
         * Assumption:
         * Buying fee, towing fee, shipping and commission
         * are entered in USD.
         */
        $exchangeRate = 3.675;

        $buyingFee = (float) ($validated['buying_fee'] ?? 0);
        $towingFee = (float) ($validated['towing_fee'] ?? 0);
        $shipping = (float) ($validated['shipping'] ?? 0);
        $commission = (float) ($validated['commission'] ?? 0);

        $clearing = (float) ($validated['clearing'] ?? 0);
        $extraCharges = (float) ($validated['extra_charges'] ?? 0);

        $totalUsd = $buyingFee
            + $towingFee
            + $shipping
            + $commission;

        $totalAed = $totalUsd * $exchangeRate;

        /*
         * Keep this formula only if it matches your
         * showroom's actual customs duty calculation.
         */
        $customDuty = ($totalAed + 1472) * 0.055;

        $grandTotal = $totalAed
            + $clearing
            + $extraCharges
            + $customDuty;

        /*
         * Store the purchase.
         */
        DB::transaction(function () use (
            $validated,
            $buyingFee,
            $towingFee,
            $shipping,
            $commission,
            $clearing,
            $extraCharges,
            $totalAed,
            $customDuty,
            $grandTotal
        ) {
            Purchase::create([
                // User
                'user_id' => $validated['user_id'],

                // Vehicle information
                'buying_date' => $validated['buying_date'],
                'lot_number' => $validated['lot_number'],
                'vin' => $validated['vin'],
                'vin_link' => $validated['vin_link'] ?? null,
                'cylinder' => $validated['cylinder'] ?? null,
                'color' => $validated['color'] ?? null,
                'make' => $validated['make'] ?? null,
                'model' => $validated['model'] ?? null,

                // Shipping information
                'shipping_company' => $validated['shipping_company'] ?? null,
                'container_number' => $validated['container_number'] ?? null,
                'arrival_date' => $validated['arrival_date'] ?? null,

                // Purchase costs
                'buying_fee' => $buyingFee,
                'towing_fee' => $towingFee,
                'shipping' => $shipping,
                'commission' => $commission,

                // Calculated costs
                'total_aed' => round($totalAed, 2),
                'clearing' => $clearing,
                'extra_charges' => $extraCharges,
                'custom_duty' => round($customDuty, 2),
                'grand_total' => round($grandTotal, 2),

                // Purchase information
                'location' => $validated['location'] ?? null,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,

                // Sale information
                'selling_price' => 0,
                'profit' => 0,
                'bill_no' => null,
                'date_of_arriving' => null,
                'customer_name' => null,
            ]);
        });

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchase Added Successfully',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Open the sale page.
     */
    public function SalePurchase(int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $users = User::orderBy('name')->get();

        return view(
            'admin.purchase.add_purchase',
            compact('purchase', 'users')
        );
    }

    /**
     * Store sale information.
     */
    public function StoreSale(Request $request, int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $validated = $request->validate([
            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'bill_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'date_of_arriving' => [
                'nullable',
                'date',
            ],

            'customer_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Purchased',
                    'Loaded',
                    'Shipped',
                    'Delivered',
                    'On Hand',
                    'At UAE',
                    'Sold',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $sellingPrice = (float) $validated['selling_price'];

        /*
         * Assumption:
         * selling_price and grand_total are both in AED.
         */
        $profit = $sellingPrice - (float) $purchase->grand_total;

        $purchase->selling_price = $sellingPrice;
        $purchase->profit = round($profit, 2);
        $purchase->bill_no = $validated['bill_no'] ?? null;
        $purchase->date_of_arriving = $validated['date_of_arriving'] ?? null;
        $purchase->customer_name = $validated['customer_name'] ?? null;
        $purchase->status = $validated['status'];
        $purchase->description = $validated['description'] ?? null;

        $purchase->save();

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Sale Information Updated Successfully',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Display the purchase edit page.
     */
    public function EditPurchase(int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $users = User::orderBy('name')->get();

        return view(
            'admin.purchase.edit_purchase',
            compact('purchase', 'users')
        );
    }

    /**
     * Update an existing purchase.
     */
    public function UpdatePurchase(Request $request)
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'exists:purchases,id',
            ],

            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'buying_date' => [
                'required',
                'date',
            ],

            'lot_number' => [
                'required',
                'string',
                'max:255',
            ],

            'vin' => [
                'required',
                'string',
                'max:255',
            ],

            'vin_link' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'cylinder' => [
                'nullable',
                'string',
                'max:255',
            ],

            'color' => [
                'nullable',
                'string',
                'max:255',
            ],

            'make' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shipping_company' => [
                'nullable',
                'string',
                'max:255',
            ],

            'container_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'arrival_date' => [
                'nullable',
                'date',
            ],

            'commission' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'buying_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'towing_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'shipping' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'clearing' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'extra_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Purchased',
                    'Loaded',
                    'Shipped',
                    'Delivered',
                    'On Hand',
                    'At UAE',
                    'Sold',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $purchase = Purchase::findOrFail($validated['id']);

        $exchangeRate = 3.675;

        $buyingFee = (float) ($validated['buying_fee'] ?? 0);
        $towingFee = (float) ($validated['towing_fee'] ?? 0);
        $shipping = (float) ($validated['shipping'] ?? 0);
        $commission = (float) ($validated['commission'] ?? 0);

        $clearing = (float) ($validated['clearing'] ?? 0);
        $extraCharges = (float) ($validated['extra_charges'] ?? 0);

        $totalUsd = $buyingFee
            + $towingFee
            + $shipping
            + $commission;

        $totalAed = $totalUsd * $exchangeRate;
        $customDuty = ($totalAed + 1472) * 0.055;

        $grandTotal = $totalAed
            + $clearing
            + $extraCharges
            + $customDuty;

        $purchase->user_id = $validated['user_id'];

        $purchase->buying_date = $validated['buying_date'];
        $purchase->lot_number = $validated['lot_number'];
        $purchase->vin = $validated['vin'];
        $purchase->vin_link = $validated['vin_link'] ?? null;

        $purchase->cylinder = $validated['cylinder'] ?? null;
        $purchase->color = $validated['color'] ?? null;
        $purchase->make = $validated['make'] ?? null;
        $purchase->model = $validated['model'] ?? null;

        $purchase->shipping_company = $validated['shipping_company'] ?? null;
        $purchase->container_number = $validated['container_number'] ?? null;
        $purchase->arrival_date = $validated['arrival_date'] ?? null;

        $purchase->buying_fee = $buyingFee;
        $purchase->towing_fee = $towingFee;
        $purchase->shipping = $shipping;
        $purchase->commission = $commission;

        $purchase->total_aed = round($totalAed, 2);
        $purchase->clearing = $clearing;
        $purchase->extra_charges = $extraCharges;
        $purchase->custom_duty = round($customDuty, 2);
        $purchase->grand_total = round($grandTotal, 2);

        $purchase->location = $validated['location'] ?? null;
        $purchase->status = $validated['status'];
        $purchase->description = $validated['description'] ?? null;

        /*
         * Do not overwrite existing sale information here.
         * Sale information should be updated through StoreSale().
         */

        $purchase->save();

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchase Updated Successfully',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Delete a purchase.
     */
    public function DeletePurchase(int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $purchase->delete();

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchase Deleted Successfully',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Export purchases.
     */
    public function ExportPurchases()
    {
        return Excel::download(
            new PurchasesExport,
            'purchases.xlsx'
        );
    }

    /**
     * Import purchases.
     */
    public function ImportPurchases(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
            ],
        ]);

        Excel::import(
            new PurchasesImport,
            $request->file('file')
        );

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchases Imported Successfully',
                'alert-type' => 'success',
            ]);
    }
}