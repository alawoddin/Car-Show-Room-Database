<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InvoicePayment;
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
            'payments',
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

        // Sold cars without a loan/invoice yet
        $purchases = Purchase::with('user')
            ->where('status', 'Sold')
            ->whereDoesntHave('invoiceStatuses')
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
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'payment_note' => 'nullable|string|max:255',
        ]);

        $existingInvoice = InvoiceStatus::where(
            'purchase_id',
            $request->purchase_id
        )->first();

        if ($existingInvoice) {
            return back()
                ->withInput()
                ->withErrors([
                    'purchase_id' => 'This vehicle already has a customer loan. Open it and add more payments.',
                ]);
        }

        $purchase = Purchase::findOrFail($request->purchase_id);
        $invoiceTotal = (float) ($purchase->selling_price > 0
            ? $purchase->selling_price
            : $purchase->grand_total);
        $firstPay = (float) ($request->paid_amount ?? 0);

        if ($firstPay > $invoiceTotal) {
            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than the sale / invoice total.',
                ]);
        }

        $invoice = InvoiceStatus::create([
            'user_id' => $request->user_id,
            'purchase_id' => $request->purchase_id,
            'paid_amount' => 0,
            'due_date' => $request->due_date,
            'status' => 'Open',
        ]);

        if ($firstPay > 0) {
            InvoicePayment::create([
                'invoice_status_id' => $invoice->id,
                'amount' => $firstPay,
                'payment_date' => $request->due_date ?? now()->toDateString(),
                'note' => $request->payment_note ?: 'First payment',
            ]);
        }

        $invoice->syncPaidAmount();

        return redirect()
            ->route('invoice.status.view', $invoice->id)
            ->with([
                'message' => 'Customer loan created. You can add more payments any time.',
                'alert-type' => 'success',
            ]);
    }

    public function ViewInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::with([
            'user',
            'purchase',
            'payments',
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
            'payments',
        ])->findOrFail($id);

        $users = User::whereIn('role', ['admin', 'user'])
            ->orderBy('role')
            ->latest()
            ->get();

        $purchases = Purchase::with('user')
            ->where(function ($query) use ($invoiceStatus) {
                $query->where('status', 'Sold')
                    ->whereDoesntHave('invoiceStatuses')
                    ->orWhere('id', $invoiceStatus->purchase_id);
            })
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
            'due_date' => 'nullable|date',
        ]);

        $invoiceStatus = InvoiceStatus::findOrFail($request->id);

        $duplicate = InvoiceStatus::where('purchase_id', $request->purchase_id)
            ->where('id', '!=', $invoiceStatus->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->with([
                    'message' => 'This vehicle already has another customer loan.',
                    'alert-type' => 'error',
                ]);
        }

        $invoiceStatus->update([
            'user_id' => $request->user_id,
            'purchase_id' => $request->purchase_id,
            'due_date' => $request->due_date,
        ]);

        $invoiceStatus->syncPaidAmount();

        return redirect()
            ->route('invoice.status.view', $invoiceStatus->id)
            ->with([
                'message' => 'Customer loan updated successfully',
                'alert-type' => 'success',
            ]);
    }

    public function StoreInvoicePayment(Request $request, int $id)
    {
        $invoiceStatus = InvoiceStatus::with('purchase')->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ]);

        $amount = (float) $request->amount;
        $remaining = $invoiceStatus->remainingAmount();

        if ($amount > $remaining) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => 'Payment cannot be greater than remaining balance (AED '.number_format($remaining, 2).').',
                ]);
        }

        InvoicePayment::create([
            'invoice_status_id' => $invoiceStatus->id,
            'amount' => $amount,
            'payment_date' => $request->payment_date ?? now()->toDateString(),
            'note' => $request->note,
        ]);

        $invoiceStatus->syncPaidAmount();

        return redirect()
            ->route('invoice.status.view', $invoiceStatus->id)
            ->with([
                'message' => 'Payment added successfully',
                'alert-type' => 'success',
            ]);
    }

    public function DeleteInvoicePayment(int $invoiceId, int $paymentId)
    {
        $invoiceStatus = InvoiceStatus::findOrFail($invoiceId);

        $payment = InvoicePayment::where('invoice_status_id', $invoiceStatus->id)
            ->where('id', $paymentId)
            ->firstOrFail();

        $payment->delete();
        $invoiceStatus->syncPaidAmount();

        return redirect()
            ->route('invoice.status.view', $invoiceStatus->id)
            ->with([
                'message' => 'Payment deleted successfully',
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
                'message' => 'Customer loan deleted successfully',
                'alert-type' => 'success',
            ]);
    }
}
