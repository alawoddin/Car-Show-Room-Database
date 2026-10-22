<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InvoiceStatus;
use Illuminate\Http\Request;

class InvoiceStatusController extends Controller
{
    public function InvoiceStatus()
    {
        $invoiceStatuses = InvoiceStatus::with(['user', 'purchase'])
            ->latest()
            ->get();

        foreach ($invoiceStatuses as $invoice) {
            $invoice->syncStatus();
        }

        return view('admin.Invoice.invoice_status', compact('invoiceStatuses'));
    }

    public function AddInvoiceStatus()
    {
        return view('admin.Invoice.add_invoice_status');
    }

    public function StoreInvoiceStatus(Request $request)
    {
        $request->validate([
            'cs_number' => 'required|string|max:255',
            'lot_number' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        $amount = (float) $request->amount;

        $invoice = InvoiceStatus::create([
            'user_id' => null,
            'purchase_id' => null,
            'cs_number' => $request->cs_number,
            'lot_number' => $request->lot_number,
            'amount' => $amount,
            'paid_amount' => 0,
            'due_date' => $request->due_date,
            'status' => 'Open',
        ]);

        $invoice->syncStatus();

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice created. Use Paid button when payment is received.',
                'alert-type' => 'success',
            ]);
    }

    public function ViewInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::with(['user', 'purchase'])->findOrFail($id);
        $invoiceStatus->syncStatus();

        return view('admin.Invoice.view_invoice_status', compact('invoiceStatus'));
    }

    public function EditInvoiceStatus(int $id)
    {
        $invoiceStatus = InvoiceStatus::findOrFail($id);

        return view('admin.Invoice.edit_invoice_status', compact('invoiceStatus'));
    }

    public function UpdateInvoiceStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:invoice_statuses,id',
            'cs_number' => 'required|string|max:255',
            'lot_number' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        $invoiceStatus = InvoiceStatus::findOrFail($request->id);
        $amount = (float) $request->amount;
        $paidAmount = (float) ($invoiceStatus->paid_amount ?? 0);

        // Keep paid in sync if already paid
        if ($invoiceStatus->status === 'Paid') {
            $paidAmount = $amount;
        }

        $invoiceStatus->update([
            'cs_number' => $request->cs_number,
            'lot_number' => $request->lot_number,
            'amount' => $amount,
            'paid_amount' => $paidAmount,
            'due_date' => $request->due_date,
        ]);

        $invoiceStatus->forceFill([
            'status' => $invoiceStatus->resolveStatus($paidAmount),
        ])->save();

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice updated successfully',
                'alert-type' => 'success',
            ]);
    }

    public function MarkPaid(int $id)
    {
        $invoice = InvoiceStatus::findOrFail($id);
        $amount = $invoice->invoiceTotal();

        if ($amount <= 0) {
            return redirect()
                ->route('invoice.status')
                ->with([
                    'message' => 'Invoice amount is missing.',
                    'alert-type' => 'error',
                ]);
        }

        $invoice->update([
            'paid_amount' => $amount,
            'status' => 'Paid',
        ]);

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice marked as Paid',
                'alert-type' => 'success',
            ]);
    }

    public function DeleteInvoiceStatus(int $id)
    {
        InvoiceStatus::findOrFail($id)->delete();

        return redirect()
            ->route('invoice.status')
            ->with([
                'message' => 'Invoice Deleted Successfully',
                'alert-type' => 'success',
            ]);
    }
}
