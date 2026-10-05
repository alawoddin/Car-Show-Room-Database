<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\User;
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

    public function StorePurchase(Request $request)
    {
        Purchase::create([
            'user_id' => $request->user_id,
            'buying_date' => $request->buying_date,
            'lot_number' => $request->lot_number,
            'vin' => $request->vin,
            'cylinder' => $request->cylinder,
            'color' => $request->color,
            'make' => $request->make,
            'model' => $request->model,

            'buying_fee' => $request->buying_fee ?? 0,
            'towing_fee' => $request->towing_fee ?? 0,
            'shipping' => $request->shipping ?? 0,

            'total_aed' => $request->total_aed ?? 0,

            'clearing' => $request->clearing ?? 0,
            'surcharge' => $request->surcharge ?? 0,
            'custom_duty' => $request->custom_duty ?? 0,

            'grand_total' => $request->grand_total ?? 0,

            'selling_price' => $request->selling_price ?? 0,
            'profit' => $request->profit ?? 0,

            'bill_no' => $request->bill_no,
            'date_of_arriving' => $request->date_of_arriving,
            'location' => $request->location,
            'customer_name' => $request->customer_name,

            'status' => $request->status ?? 'Purchased',

            'description' => $request->description,
        ]);

        $notification = array(
                'message' => 'Purchase Added Successfully',
                'alert-type' => 'success'
            );

        return redirect()->route('all.purchases')->with($notification);



        
    }
}
