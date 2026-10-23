@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">
        @php
            $purchase = $loan->purchase;
            $loanTotal = $loan->loanTotal();
            $paidAmount = $loan->totalPaid();
            $remaining = $loan->remainingAmount();
        @endphp

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('customer.loan') }}">Customer Loan</a>
                        </li>
                        <li class="breadcrumb-item active">Loan Details</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('customer.loan.invoice', $loan->id) }}" class="btn btn-info px-4">
                    <i class="bx bx-show"></i> Show Invoice
                </a>
                <a href="{{ route('customer.loan.download', $loan->id) }}" class="btn btn-dark px-4">
                    <i class="bx bx-download"></i> Download
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="mb-1">Customer Loan #{{ $loan->id }}</h4>
                        <p class="mb-0 text-muted">
                            Customer: <strong>{{ $loan->customerLabel() }}</strong>
                        </p>
                    </div>
                    @if ($loan->status === 'Paid')
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-warning text-dark">Open</span>
                    @endif
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-1">
                            <strong>Vehicle:</strong>
                            {{ $purchase->make ?? '-' }} {{ $purchase->model ?? '' }}
                        </p>
                        <p class="mb-1"><strong>VIN:</strong> {{ $purchase->vin ?? '-' }}</p>
                        <p class="mb-0"><strong>Due Date:</strong> {{ $loan->due_date ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1"><strong>Sale Price:</strong> AED {{ number_format($loanTotal, 2) }}</p>
                        <p class="mb-1 text-success"><strong>Paid:</strong> AED {{ number_format($paidAmount, 2) }}</p>
                        <p class="mb-0 text-danger"><strong>Remaining:</strong> AED {{ number_format($remaining, 2) }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Payment History</h5>
                    @if ($remaining > 0)
                        <span class="badge bg-warning text-dark">
                            Remaining AED {{ number_format($remaining, 2) }}
                        </span>
                    @else
                        <span class="badge bg-success">Fully paid</span>
                    @endif
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loan->payments as $key => $payment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ optional($payment->payment_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td class="text-success">AED {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td>{{ $payment->note ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('customer.loan.payment.delete', [$loan->id, $payment->id]) }}"
                                           class="btn btn-sm btn-danger" id="delete">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No payments yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($remaining > 0)
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="mb-3">Add Next Payment</h6>
                            <form method="POST" action="{{ route('customer.loan.payment.store', $loan->id) }}"
                                  class="row g-3">
                                @csrf
                                <div class="col-md-3">
                                    <label class="form-label">Amount (AED)</label>
                                    <input type="number" step="0.01" min="0.01" max="{{ $remaining }}"
                                           name="amount"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Payment Date</label>
                                    <input type="date" name="payment_date" class="form-control"
                                           value="{{ old('payment_date', now()->toDateString()) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Note</label>
                                    <input type="text" name="note" class="form-control"
                                           placeholder="e.g. second pay, third pay"
                                           value="{{ old('note') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Add Payment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('customer.loan') }}" class="btn btn-light">Back to Loans</a>
                </div>
            </div>
        </div>
    </div>
@endsection
