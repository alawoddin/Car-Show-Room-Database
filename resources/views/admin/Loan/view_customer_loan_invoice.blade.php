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
                        <li class="breadcrumb-item">
                            <a href="{{ route('customer.loan.view', $loan->id) }}">Loan #{{ $loan->id }}</a>
                        </li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('customer.loan.download', $loan->id) }}" class="btn btn-dark px-4">
                    <i class="bx bx-download"></i> Download
                </a>
                <a href="{{ route('customer.loan.view', $loan->id) }}" class="btn btn-outline-secondary px-4">
                    Back to Loan
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-5">
                <div class="row mb-4">
                    <div class="col-md-7">
                        <h3 class="fw-bold mb-2">AL JAZEERA AL HAMRA USED CARS</h3>
                        <p class="mb-1">Enam Ullah Ahmadi</p>
                        <p class="mb-0 text-muted">
                            Al Jubail Street 72, Industrial Area-2,<br>
                            Sharjah, United Arab Emirates
                        </p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <h2 class="fw-bold">INVOICE</h2>
                        <p class="mb-1"><strong>Loan / Invoice ID:</strong> #{{ $loan->id }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $loan->due_date ?? $loan->created_at?->format('Y-m-d') }}</p>
                        @if ($loan->status === 'Paid')
                            <span class="badge bg-success fs-6">PAID</span>
                        @else
                            <span class="badge bg-warning text-dark fs-6">OPEN</span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-uppercase">Customer</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $loan->customerLabel() }}</p>
                        @if ($loan->user)
                            <p class="mb-1"><strong>Owner:</strong> {{ $loan->user->name }}
                                @if ($loan->user->role === 'admin') (Admin) @else (Client) @endif
                            </p>
                            <p class="mb-0"><strong>Email:</strong> {{ $loan->user->email ?? '-' }}</p>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="fw-bold text-uppercase">Due Information</h6>
                        <p class="mb-0"><strong>Due Date:</strong> {{ $loan->due_date ?? 'No Due Date' }}</p>
                    </div>
                </div>

                <h6 class="fw-bold text-uppercase mb-3">Vehicle Information</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="25%">Vehicle</th>
                                <td>{{ $purchase->make ?? '-' }} {{ $purchase->model ?? '' }}</td>
                                <th width="25%">VIN</th>
                                <td>{{ $purchase->vin ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Lot Number</th>
                                <td>{{ $purchase->lot_number ?? '-' }}</td>
                                <th>Status</th>
                                <td>{{ $purchase->status ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="fw-bold text-uppercase mb-3 mt-4">Payment Summary</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Sale Price</th>
                                <td class="text-end"><strong>AED {{ number_format($loanTotal, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td class="text-end text-success"><strong>AED {{ number_format($paidAmount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th class="fs-5">Remaining Amount</th>
                                <td class="text-end text-danger fs-5"><strong>AED {{ number_format($remaining, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="fw-bold text-uppercase mb-3 mt-4">Payment History</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loan->payments as $key => $payment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $payment->payment_date ?? '-' }}</td>
                                    <td>AED {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td>{{ $payment->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No payments yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
