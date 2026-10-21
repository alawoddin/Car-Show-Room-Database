@extends('admin.admin_dashboard')

@section('admin')

    <div class="page-content">

        @php
            $purchase = $invoiceStatus->purchase;
            $grandTotal = $invoiceStatus->invoiceTotal();
            $paidAmount = $invoiceStatus->totalPaid();
            $remaining = $invoiceStatus->remainingAmount();
        @endphp


        <!-- Breadcrumb -->

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

            <div class="ps-3">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb mb-0 p-0">

                        <li class="breadcrumb-item">

                            <a href="javascript:;">

                                <i class="bx bx-home-alt"></i>

                            </a>

                        </li>


                        <li class="breadcrumb-item">

                            <a href="{{ route('invoice.status') }}">

                                Customer Loan

                            </a>

                        </li>


                        <li class="breadcrumb-item active">

                            Loan Details

                        </li>

                    </ol>

                </nav>

            </div>


          

        </div>



        {{-- =====================================================
             INVOICE CARD
        ====================================================== --}}

        <div class="card">

            <div class="card-body p-5">


                {{-- =================================================
                     HEADER
                ================================================== --}}

                <div class="row mb-4">

                    <div class="col-md-7">

                        <h3 class="fw-bold mb-2">

                            AL JAZEERA AL HAMRA USED CARS

                        </h3>


                        <p class="mb-1">

                            Enam Ullah Ahmadi

                        </p>


                        <p class="mb-0 text-muted">

                            Al Jubail Street 72,
                            Industrial Area-2,
                            Sharjah,
                            United Arab Emirates

                        </p>

                    </div>


                    <div class="col-md-5 text-md-end">

                        <h2 class="fw-bold">

                            CUSTOMER LOAN

                        </h2>


                        <p class="mb-1">

                            <strong>

                                Invoice ID:

                            </strong>

                            #{{ $invoiceStatus->id }}

                        </p>


                        <p class="mb-1">

                            <strong>

                                Date:

                            </strong>

                            {{ $invoiceStatus->created_at?->format('Y-m-d') }}

                        </p>


                        @if ($invoiceStatus->status == 'Paid')

                            <span class="badge bg-success fs-6">

                                PAID

                            </span>

                        @elseif ($invoiceStatus->status == 'Open')

                            <span class="badge bg-warning text-dark fs-6">

                                OPEN

                            </span>

                        @elseif ($invoiceStatus->status == 'Overdue')

                            <span class="badge bg-danger fs-6">

                                OVERDUE

                            </span>

                        @endif

                    </div>

                </div>



                <hr>



                {{-- =================================================
                     CUSTOMER
                ================================================== --}}

                <div class="row mt-4 mb-4">

                    <div class="col-md-6">

                        <h6 class="fw-bold text-uppercase">

                            Customer

                        </h6>


                        <p class="mb-1">

                            <strong>

                                Name:

                            </strong>

                            {{ $invoiceStatus->customerLabel() }}

                        </p>


                        @if ($invoiceStatus->user)

                            <p class="mb-1">

                                <strong>

                                    Account:

                                </strong>

                                {{ $invoiceStatus->user->name ?? '-' }}

                                ({{ $invoiceStatus->user->email ?? '-' }})

                            </p>

                        @endif

                    </div>


                    <div class="col-md-6 text-md-end">

                        <h6 class="fw-bold text-uppercase">

                            Due Information

                        </h6>


                        <p class="mb-1">

                            <strong>

                                Due Date:

                            </strong>

                            {{ $invoiceStatus->due_date ?? 'No Due Date' }}

                        </p>

                    </div>

                </div>



                {{-- =================================================
                     VEHICLE INFORMATION
                ================================================== --}}

                <h6 class="fw-bold text-uppercase mb-3">

                    Vehicle Information

                </h6>


                <div class="table-responsive">

                    <table class="table table-bordered">

                        <tbody>

                            <tr>

                                <th width="25%">

                                    Vehicle

                                </th>

                                <td>

                                    {{ $purchase->make ?? '-' }}

                                    {{ $purchase->model ?? '' }}

                                </td>


                                <th width="25%">

                                    VIN

                                </th>

                                <td>

                                    {{ $purchase->vin ?? '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    Lot Number

                                </th>

                                <td>

                                    {{ $purchase->lot_number ?? '-' }}

                                </td>


                                <th>

                                    Status

                                </th>

                                <td>

                                    {{ $purchase->status ?? '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    Buying Date

                                </th>

                                <td>

                                    {{ $purchase->buying_date ?? '-' }}

                                </td>


                                <th>

                                    Location

                                </th>

                                <td>

                                    {{ $purchase->location ?? '-' }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                     PAYMENT SUMMARY
                ================================================== --}}

                <h6 class="fw-bold text-uppercase mb-3 mt-4">

                    Payment Summary

                </h6>


                <div class="table-responsive">

                    <table class="table table-bordered">

                        <tbody>

                            <tr>

                                <th>

                                    Grand Total

                                </th>

                                <td class="text-end">

                                    <strong>

                                        AED
                                        {{ number_format($grandTotal, 2) }}

                                    </strong>

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    Paid Amount

                                </th>

                                <td class="text-end text-success">

                                    <strong>

                                        AED
                                        {{ number_format($paidAmount, 2) }}

                                    </strong>

                                </td>

                            </tr>


                            <tr>

                                <th class="fs-5">

                                    Remaining Amount

                                </th>

                                <td class="text-end text-danger fs-5">

                                    <strong>

                                        AED
                                        {{ number_format($remaining, 2) }}

                                    </strong>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                     PAYMENT STATUS
                ================================================== --}}

                <div class="mt-4 p-3 border rounded">

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <strong>

                                Payment Status:

                            </strong>


                            @if ($invoiceStatus->status == 'Paid')

                                <span class="badge bg-success ms-2">

                                    Paid

                                </span>

                            @elseif ($invoiceStatus->status == 'Open')

                                <span class="badge bg-warning text-dark ms-2">

                                    Open

                                </span>

                            @elseif ($invoiceStatus->status == 'Overdue')

                                <span class="badge bg-danger ms-2">

                                    Overdue

                                </span>

                            @endif

                        </div>


                        <div class="col-md-6 text-md-end">

                            <strong>

                                Remaining:

                            </strong>


                            <span class="text-danger fw-bold">

                                AED {{ number_format($remaining, 2) }}

                            </span>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     INSTALLMENT PAYMENTS (like cash book first/second/last pay)
                ================================================== --}}

                <div class="mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Payment History</h5>
                        @if ($remaining > 0)
                            <span class="badge bg-warning text-dark">Loan open — remaining AED {{ number_format($remaining, 2) }}</span>
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
                                @forelse ($invoiceStatus->payments as $key => $payment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ optional($payment->payment_date)->format('Y-m-d') ?? '-' }}</td>
                                        <td class="text-success">AED {{ number_format((float) $payment->amount, 2) }}</td>
                                        <td>{{ $payment->note ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('invoice.status.payment.delete', [$invoiceStatus->id, $payment->id]) }}"
                                               class="btn btn-sm btn-danger"
                                               id="delete">
                                                Delete
                                            </a>
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
                                <form method="POST" action="{{ route('invoice.status.payment.store', $invoiceStatus->id) }}" class="row g-3">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label">Amount (AED)</label>
                                        <input type="number" step="0.01" min="0.01" max="{{ $remaining }}" name="amount"
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
                                               placeholder="e.g. second pay, cash, transfer"
                                               value="{{ old('note') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Add Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- =================================================
                     FOOTER
                ================================================== --}}

                <div class="text-center mt-5 pt-4 border-top">

                    <h6 class="fw-bold">

                        AL JAZEERA AL HAMRA USED CARS

                    </h6>


                    <p class="mb-1">

                        Enam Ullah Ahmadi

                    </p>


                    <p class="text-muted mb-2">

                        Al Jubail Street 72,
                        Industrial Area-2,
                        Sharjah,
                        United Arab Emirates

                    </p>


                    <small class="text-muted">

                        Thank you for your business.

                    </small>

                </div>


            </div>

        </div>

    </div>



    {{-- =========================================================
         PRINT STYLE
    ========================================================== --}}

    <style>

        @media print {

            body {

                background: #fff !important;

            }

            .page-breadcrumb,
            .sidebar-wrapper,
            .top-header,
            .btn-group {

                display: none !important;

            }

            .page-content {

                margin: 0 !important;

                padding: 0 !important;

            }

            .card {

                border: none !important;

                box-shadow: none !important;

            }

            .card-body {

                padding: 20px !important;

            }

            .table {

                border-color: #000 !important;

            }

        }

    </style>

@endsection