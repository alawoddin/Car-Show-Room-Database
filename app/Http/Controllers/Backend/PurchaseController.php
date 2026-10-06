<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\User;
use App\Exports\PurchasesExport;
use App\Imports\PurchasesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function AllPurchases()
    {
        $purchases = Purchase::with('user')->latest()->get();

        return view('admin.purchase.all_purchases', compact('purchases'));
    }

    public function AddPurchase()
    {
        $users = User::where('role', 'user')->latest()->get();

        return view('admin.purchase.add_purchase', compact('users'));
    }
    // =========================================================
    // Store Purchase
    // =========================================================

    public function StorePurchase(Request $request)
    {
        Purchase::create([

            // =========================
            // User
            // =========================
            'user_id' => $request->user_id,

            // =========================
            // Vehicle Information
            // =========================
            'buying_date' => $request->buying_date,
            'lot_number' => $request->lot_number,
            'vin' => $request->vin,
            'cylinder' => $request->cylinder,
            'color' => $request->color,
            'make' => $request->make,
            'model' => $request->model,

            // =========================
            // Purchase Costs
            // =========================
            'buying_fee' => $request->buying_fee ?? 0,
            'towing_fee' => $request->towing_fee ?? 0,
            'shipping' => $request->shipping ?? 0,

            // =========================
            // Calculated Costs
            // =========================
            'total_aed' => $request->total_aed ?? 0,
            'clearing' => $request->clearing ?? 0,
            'extra_charges' => $request->extra_charges ?? 0,
            'custom_duty' => $request->custom_duty ?? 0,
            'grand_total' => $request->grand_total ?? 0,

            // =========================
            // Purchase Information
            // =========================
            'location' => $request->location,

            // Admin selects the status
            'status' => $request->status ?? 'Purchased',

            'description' => $request->description,

            // =========================
            // Sale Information
            // Empty until vehicle is sold
            // =========================
            'selling_price' => 0,
            'profit' => 0,
            'bill_no' => null,
            'date_of_arriving' => null,
            'customer_name' => null,
        ]);


        // Notification
        $notification = [
            'message' => 'Purchase Added Successfully',
            'alert-type' => 'success',
        ];


        return redirect()
            ->route('all.purchases')
            ->with($notification);
    }


    // =========================================================
    // Open Sale Page
    // =========================================================

    public function SalePurchase(int $id)
    {
        // Find existing purchase
        $purchase = Purchase::findOrFail($id);

        // Get users
        $users = User::where('role', 'user')->get();


        return view(
            'admin.purchase.add_purchase',
            compact(
                'purchase',
                'users'
            )
        );
    }


    // =========================================================
    // Store Sale Information
    // =========================================================

    public function StoreSale(Request $request, int $id)
    {
        // Find the existing purchase
        $purchase = Purchase::findOrFail($id);


        // =========================
        // Sale Information
        // =========================

        $purchase->selling_price = $request->selling_price ?? 0;

        $purchase->profit = $request->profit ?? 0;

        $purchase->bill_no = $request->bill_no;

        $purchase->date_of_arriving = $request->date_of_arriving;

        $purchase->customer_name = $request->customer_name;


        // =========================
        // Status
        // =========================

        // IMPORTANT:
        // Do NOT use:
        //
        // $purchase->status = 'Sold';
        //
        // Instead, save the status selected
        // by the admin from the Sale form.

        $purchase->status = $request->status;


        // =========================
        // Description
        // =========================

        $purchase->description = $request->description;


        // =========================
        // Save
        // =========================

        $purchase->save();


        // =========================
        // Notification
        // =========================

        $notification = [
            'message' => 'Sale Information Updated Successfully',
            'alert-type' => 'success',
        ];


        return redirect()
            ->route('all.purchases')
            ->with($notification);
    }

    public function EditPurchase(int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $users = User::where('role', 'user')->latest()->get();

        return view('admin.purchase.edit_purchase', compact('purchase', 'users'));
    }

    public function UpdatePurchase(Request $request)
    {
        $purchase = Purchase::findOrFail($request->id);

        // User
        $purchase->user_id = $request->user_id;

        // Vehicle Information
        $purchase->buying_date = $request->buying_date;
        $purchase->lot_number = $request->lot_number;
        $purchase->vin = $request->vin;
        $purchase->cylinder = $request->cylinder;
        $purchase->color = $request->color;
        $purchase->make = $request->make;
        $purchase->model = $request->model;

        // Purchase Costs
        $purchase->buying_fee = $request->buying_fee ?? 0;
        $purchase->towing_fee = $request->towing_fee ?? 0;
        $purchase->shipping = $request->shipping ?? 0;

        // Calculated Costs
        $purchase->total_aed = $request->total_aed ?? 0;
        $purchase->clearing = $request->clearing ?? 0;
        $purchase->extra_charges = $request->extra_charges ?? 0;
        $purchase->custom_duty = $request->custom_duty ?? 0;
        $purchase->grand_total = $request->grand_total ?? 0;

        // Purchase Information
        $purchase->location = $request->location;
        $purchase->status = $request->status;
        $purchase->description = $request->description;

        // Sale Information
        $purchase->selling_price = $request->selling_price ?? 0;
        $purchase->profit = $request->profit ?? 0;
        $purchase->bill_no = $request->bill_no;
        $purchase->date_of_arriving = $request->date_of_arriving;
        $purchase->customer_name = $request->customer_name;

        // Save
        $purchase->save();

        $notification = [
            'message' => 'Purchase Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()
            ->route('all.purchases')
            ->with($notification);
    }

    public function DeletePurchase(int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $purchase->delete();

        $notification = [
            'message' => 'Purchase Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()
            ->route('all.purchases')
            ->with($notification);
    }

    ///the export function
    public function ExportPurchases()
{
    return Excel::download(
        new PurchasesExport,
        'purchases.xlsx'
    );
}   

    public function ImportPurchases(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
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
