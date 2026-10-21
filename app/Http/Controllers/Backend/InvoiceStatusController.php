<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InvoiceStatus;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
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

    public function AddInvoiceStatus()
    {
        $users = User::whereIn('role', ['admin', 'user'])
            ->orderBy('role')
            ->latest()
            ->get();

        // Admin invoice = payment tracking on purchased cars (cost / grand total)
        $purchases = Purchase::with('user')
            ->latest()
            ->get();

        return view(
            'admin.Invoice.add_invoice_status',
            compact('users', 'purchases')
        );
    }

    public function StoreInvoiceStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'purchase_id' => 'required|exists:purchases,id',
            'paid_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $purchase = Purchase::findOrFail($request->purchase_id);
        $grandTotal = (float) $purchase->grand_total;
        $paidAmount = (float) $request->paid_amount;

        if ($paidAmount > $grandTotal) {
            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than the Grand Total.',
                ]);
        }

        $status = $this->resolveStatus($paidAmount, $grandTotal, $request->due_date);

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

    public function EditInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::with([
            'user',
            'purchase',
        ])->findOrFail($id);

        $users = User::whereIn('role', ['admin', 'user'])
            ->orderBy('role')
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

    public function UpdateInvoiceStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:invoice_statuses,id',
            'user_id' => 'required|exists:users,id',
            'purchase_id' => 'required|exists:purchases,id',
            'paid_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $invoiceStatus = InvoiceStatus::findOrFail($request->id);
        $purchase = Purchase::findOrFail($request->purchase_id);
        $grandTotal = (float) $purchase->grand_total;
        $paidAmount = (float) $request->paid_amount;

        if ($paidAmount > $grandTotal) {
            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than the Grand Total.',
                ]);
        }

        $status = $this->resolveStatus($paidAmount, $grandTotal, $request->due_date);

        $invoiceStatus->update([
            'user_id' => $request->user_id,
            'purchase_id' => $request->purchase_id,
            'paid_amount' => $paidAmount,
            'due_date' => $request->due_date,
            'status' => $status,
        ]);

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice Status Updated Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function DeleteInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::findOrFail($id);
        $invoiceStatus->delete();

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice Status Deleted Successfully',
                'alert-type' => 'success',
            ]);
    }

    private function resolveStatus(float $paidAmount, float $grandTotal, ?string $dueDate): string
    {
        if ($paidAmount >= $grandTotal && $grandTotal > 0) {
            return 'Paid';
        }

        if (
            $dueDate &&
            now()->startOfDay()->gt(Carbon::parse($dueDate)->startOfDay())
        ) {
            return 'Overdue';
        }

        return 'Open';
    }
}
