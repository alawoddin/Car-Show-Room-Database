<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CustomerLoan;
use App\Models\CustomerLoanPayment;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerLoanController extends Controller
{
    public function index()
    {
        $loans = CustomerLoan::with(['user', 'purchase', 'payments'])
            ->latest()
            ->get();

        return view('admin.Loan.customer_loan', compact('loans'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['admin', 'user'])
            ->orderBy('role')
            ->latest()
            ->get();

        $purchases = Purchase::with(['user', 'users'])
            ->where('status', 'Sold')
            ->whereDoesntHave('customerLoans')
            ->latest()
            ->get();

        return view('admin.Loan.add_customer_loan', compact('users', 'purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'purchase_id' => 'required|exists:purchases,id',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'payment_note' => 'nullable|string|max:255',
        ]);

        if (CustomerLoan::where('purchase_id', $request->purchase_id)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'purchase_id' => 'This sold car already has a customer loan. Open it and add more payments.',
                ]);
        }

        $purchase = Purchase::with('users')->findOrFail($request->purchase_id);

        $buyerIds = $purchase->selectedUserIds();
        if (! in_array((int) $request->user_id, $buyerIds, true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'purchase_id' => 'This sold car does not belong to the selected admin/client.',
                ]);
        }

        $loanTotal = (float) ($purchase->selling_price > 0
            ? $purchase->selling_price
            : $purchase->grand_total);
        $firstPay = (float) ($request->paid_amount ?? 0);

        if ($firstPay > $loanTotal) {
            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than the sale price.',
                ]);
        }

        $loan = CustomerLoan::create([
            'user_id' => $request->user_id,
            'purchase_id' => $request->purchase_id,
            'paid_amount' => 0,
            'due_date' => $request->due_date,
            'status' => 'Open',
        ]);

        if ($firstPay > 0) {
            CustomerLoanPayment::create([
                'customer_loan_id' => $loan->id,
                'amount' => $firstPay,
                'payment_date' => $request->due_date ?? now()->toDateString(),
                'note' => $request->payment_note ?: 'First payment',
            ]);
        }

        $loan->syncPaidAmount();

        return redirect()
            ->route('customer.loan.view', $loan->id)
            ->with([
                'message' => 'Customer loan created. Add more payments when the customer pays.',
                'alert-type' => 'success',
            ]);
    }

    public function show(int $id)
    {
        $loan = CustomerLoan::with(['user', 'purchase', 'payments'])
            ->findOrFail($id);

        return view('admin.Loan.view_customer_loan', compact('loan'));
    }

    public function storePayment(Request $request, int $id)
    {
        $loan = CustomerLoan::with('purchase')->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ]);

        $amount = (float) $request->amount;
        $remaining = $loan->remainingAmount();

        if ($amount > $remaining) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => 'Payment cannot be greater than remaining AED '.number_format($remaining, 2).'.',
                ]);
        }

        CustomerLoanPayment::create([
            'customer_loan_id' => $loan->id,
            'amount' => $amount,
            'payment_date' => $request->payment_date ?? now()->toDateString(),
            'note' => $request->note,
        ]);

        $loan->syncPaidAmount();

        return redirect()
            ->route('customer.loan.view', $loan->id)
            ->with([
                'message' => 'Payment added successfully',
                'alert-type' => 'success',
            ]);
    }

    public function deletePayment(int $loanId, int $paymentId)
    {
        $loan = CustomerLoan::findOrFail($loanId);

        CustomerLoanPayment::where('customer_loan_id', $loan->id)
            ->where('id', $paymentId)
            ->firstOrFail()
            ->delete();

        $loan->syncPaidAmount();

        return redirect()
            ->route('customer.loan.view', $loan->id)
            ->with([
                'message' => 'Payment deleted successfully',
                'alert-type' => 'success',
            ]);
    }

    public function destroy(int $id)
    {
        CustomerLoan::findOrFail($id)->delete();

        return redirect()
            ->route('customer.loan')
            ->with([
                'message' => 'Customer loan deleted successfully',
                'alert-type' => 'success',
            ]);
    }
}
