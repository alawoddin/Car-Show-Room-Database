<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InvoiceStatus;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceStatusController extends Controller
{
    public function InvoiceStatus()
    {
        $invoiceStatuses = InvoiceStatus::with([
            'user',
            'purchase'
        ])
            ->latest()
            ->get();

        return view(
            'admin.Invoice.invoice_status',
            compact('invoiceStatuses')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Invoice Status Page
    |--------------------------------------------------------------------------
    */

    public function AddInvoiceStatus()
    {
        $users = User::where('role', 'user')
            ->latest()
            ->get();

        $purchases = Purchase::with('user')
            ->latest()
            ->get();

        return view(
            'admin.Invoice.add_invoice_status',
            compact('users', 'purchases')
        );
    }
}
