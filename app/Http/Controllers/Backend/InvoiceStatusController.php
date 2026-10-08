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
            'purchase',
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

    /*
|--------------------------------------------------------------------------
| Store Invoice Status
|--------------------------------------------------------------------------
*/

    public function StoreInvoiceStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'purchase_id' => 'required|exists:purchases,id',
            'paid_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        // Check duplicate invoice
        $existingInvoice = InvoiceStatus::where(
            'purchase_id',
            $request->purchase_id
        )->first();

        if ($existingInvoice) {

            return back()
                ->withInput()
                ->withErrors([
                    'purchase_id' => 'This vehicle already has an invoice status.',
                ]);
        }

        // Get purchase
        $purchase = Purchase::findOrFail($request->purchase_id);

        $grandTotal = (float) $purchase->grand_total;

        $paidAmount = (float) $request->paid_amount;

        // Paid cannot be greater than Grand Total
        if ($paidAmount > $grandTotal) {

            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than Grand Total.',
                ]);
        }

        // Determine status
        $status = $paidAmount >= $grandTotal
            ? 'Paid'
            : 'Open';

        InvoiceStatus::create([
            'user_id' => $request->user_id,
            'purchase_id' => $request->purchase_id,
            'paid_amount' => $paidAmount,
            'due_date' => $request->due_date,
            'status' => $status,
        ]);

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice Status Added Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function ViewInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::with([
            'user',
            'purchase',
        ])->findOrFail($id);

        return view(
            'admin.Invoice.view_invoice_status',
            compact('invoiceStatus')
        );
    }

    /*
|--------------------------------------------------------------------------
| Edit Invoice Status
|--------------------------------------------------------------------------
*/

    public function EditInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::with([
            'user',
            'purchase',
        ])->findOrFail($id);

        $users = User::where('role', 'user')
            ->latest()
            ->get();

        $purchases = Purchase::with('user')
            ->latest()
            ->get();

        return view(
            'admin.Invoice.edit_invoice_status',
            compact(
                'invoiceStatus',
                'users',
                'purchases'
            )
        );
    }

    /*
|--------------------------------------------------------------------------
| Update Invoice Status
|--------------------------------------------------------------------------
*/

    public function UpdateInvoiceStatus(Request $request)
    {
        $request->validate([

            'id' => 'required|exists:invoice_statuses,id',

            'user_id' => 'required|exists:users,id',

            'purchase_id' => 'required|exists:purchases,id',

            'paid_amount' => 'required|numeric|min:0',

            'due_date' => 'nullable|date',

        ]);

        /*
    |--------------------------------------------------------------------------
    | Find Invoice
    |--------------------------------------------------------------------------
    */

        $invoiceStatus = InvoiceStatus::findOrFail(
            $request->id
        );

        /*
    |--------------------------------------------------------------------------
    | Get Purchase
    |--------------------------------------------------------------------------
    */

        $purchase = Purchase::findOrFail(
            $request->purchase_id
        );

        /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

        $grandTotal = (float) $purchase->grand_total;

        /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

        $paidAmount = (float) $request->paid_amount;

        /*
    |--------------------------------------------------------------------------
    | Prevent Paid > Grand Total
    |--------------------------------------------------------------------------
    */

        if ($paidAmount > $grandTotal) {

            return back()
                ->withInput()
                ->with([
                    'message' => 'Paid amount cannot be greater than Grand Total.',
                    'alert-type' => 'error',
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Determine Status
    |--------------------------------------------------------------------------
    */

        if ($paidAmount >= $grandTotal) {

            $status = 'Paid';
        } else {

            $status = 'Open';
        }

        /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

        $invoiceStatus->update([

            'user_id' => $request->user_id,

            'purchase_id' => $request->purchase_id,

            'paid_amount' => $paidAmount,

            'due_date' => $request->due_date,

            'status' => $status,

        ]);

        $notification = [

            'message' => 'Invoice Status Updated Successfully',

            'alert-type' => 'success',

        ];

        return redirect()
            ->route('invoice.status')
            ->with($notification);
    }

    /*
|--------------------------------------------------------------------------
| Delete Invoice Status
|--------------------------------------------------------------------------
*/

    public function DeleteInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::findOrFail($id);

        $invoiceStatus->delete();
        $notification = [

            'message' => 'Invoice Status Deleted Successfully',

            'alert-type' => 'success',

        ];
        return redirect()
            ->route('invoice.status')
            ->with($notification);
    }
}
