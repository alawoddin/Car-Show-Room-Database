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
}
