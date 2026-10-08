@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

            <div class="ps-3">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb mb-0 p-0">

                        <li class="breadcrumb-item">

                            <a href="javascript:;">

                                <i class="bx bx-home-alt"></i>

                            </a>

                        </li>

                        <li class="breadcrumb-item active" aria-current="page">

                            Invoice Status

                        </li>

                    </ol>

                </nav>

            </div>


            <div class="ms-auto">

                <div class="btn-group">

                    <a href="{{ route('invoice.status.add') }}" class="btn btn-primary px-5">

                        Add Invoice Status

                    </a>

                </div>

            </div>

        </div>
        <!--end breadcrumb-->



        {{-- =====================================================
             INVOICE SUMMARY
        ====================================================== --}}

        @php

            /*
            |--------------------------------------------------------------------------
            | TOTAL INVOICES
            |--------------------------------------------------------------------------
            */

            $totalInvoices = $invoiceStatuses->count();

            /*
            |--------------------------------------------------------------------------
            | PAID INVOICES
            |--------------------------------------------------------------------------
            */

            $paidInvoices = $invoiceStatuses->where('status', 'Paid')->count();

            /*
            |--------------------------------------------------------------------------
            | OPEN INVOICES
            |--------------------------------------------------------------------------
            */

            $openInvoices = $invoiceStatuses->where('status', 'Open')->count();

            /*
            |--------------------------------------------------------------------------
            | OVERDUE INVOICES
            |--------------------------------------------------------------------------
            */

            $overdueInvoices = $invoiceStatuses->where('status', 'Overdue')->count();

            /*
            |--------------------------------------------------------------------------
            | OPEN REMAINING AMOUNT
            |--------------------------------------------------------------------------
            */

            $openAmount = $invoiceStatuses->where('status', 'Open')->sum(function ($item) {
                $grandTotal = $item->purchase->grand_total ?? 0;

                $paidAmount = $item->paid_amount ?? 0;

                return max(0, $grandTotal - $paidAmount);
            });

            /*
            |--------------------------------------------------------------------------
            | OVERDUE REMAINING AMOUNT
            |--------------------------------------------------------------------------
            */

            $overdueAmount = $invoiceStatuses->where('status', 'Overdue')->sum(function ($item) {
                $grandTotal = $item->purchase->grand_total ?? 0;

                $paidAmount = $item->paid_amount ?? 0;

                return max(0, $grandTotal - $paidAmount);
            });

            /*
            |--------------------------------------------------------------------------
            | TOTAL GRAND TOTAL
            |--------------------------------------------------------------------------
            */

            $totalAmount = $invoiceStatuses->sum(function ($item) {
                return $item->purchase->grand_total ?? 0;
            });

            /*
            |--------------------------------------------------------------------------
            | TOTAL PAID
            |--------------------------------------------------------------------------
            */

            $totalPaid = $invoiceStatuses->sum(function ($item) {
                return $item->paid_amount ?? 0;
            });

            /*
            |--------------------------------------------------------------------------
            | TOTAL REMAINING
            |--------------------------------------------------------------------------
            */

            $totalRemaining = $invoiceStatuses->sum(function ($item) {
                $grandTotal = $item->purchase->grand_total ?? 0;

                $paidAmount = $item->paid_amount ?? 0;

                return max(0, $grandTotal - $paidAmount);
            });

        @endphp



        {{-- =====================================================
             FINANCIAL SUMMARY
        ====================================================== --}}

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 mb-3">


            {{-- TOTAL GRAND TOTAL --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-primary">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Total Amount
                                </p>

                                <h4 class="my-1 text-primary">

                                    AED {{ number_format($totalAmount, 2) }}

                                </h4>

                                <p class="mb-0 font-13">
                                    Total Grand Total
                                </p>

                            </div>


                            <div class="widgets-icons bg-light-primary text-primary ms-auto">

                                <i class="bx bx-money"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- TOTAL PAID --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-success">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Paid Amount
                                </p>

                                <h4 class="my-1 text-success">

                                    AED {{ number_format($totalPaid, 2) }}

                                </h4>

                                <p class="mb-0 font-13">
                                    Total Paid
                                </p>

                            </div>


                            <div class="widgets-icons bg-light-success text-success ms-auto">

                                <i class="bx bx-check-circle"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- TOTAL REMAINING --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-danger">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Remaining
                                </p>

                                <h4 class="my-1 text-danger">

                                    AED {{ number_format($totalRemaining, 2) }}

                                </h4>

                                <p class="mb-0 font-13">
                                    Total Remaining
                                </p>

                            </div>


                            <div class="widgets-icons bg-light-danger text-danger ms-auto">

                                <i class="bx bx-wallet"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>



        {{-- =====================================================
             INVOICE STATUS SUMMARY
        ====================================================== --}}

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-3">


            {{-- TOTAL INVOICES --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-primary">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Total Invoices
                                </p>

                                <h4 class="my-1 text-primary">

                                    {{ $totalInvoices }}

                                </h4>

                                <p class="mb-0 font-13">
                                    All invoices
                                </p>

                            </div>


                            <div class="widgets-icons bg-light-primary text-primary ms-auto">

                                <i class="bx bx-receipt"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- PAID --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-success">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Paid
                                </p>

                                <h4 class="my-1 text-success">

                                    {{ $paidInvoices }}

                                </h4>

                                <p class="mb-0 font-13">
                                    Paid invoices
                                </p>

                            </div>


                            <div class="widgets-icons bg-light-success text-success ms-auto">

                                <i class="bx bx-check-circle"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- OPEN --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-warning">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Open
                                </p>

                                <h4 class="my-1 text-warning">

                                    {{ $openInvoices }}

                                </h4>

                                <p class="mb-0 font-13">

                                    AED {{ number_format($openAmount, 2) }}

                                </p>

                            </div>


                            <div class="widgets-icons bg-light-warning text-warning ms-auto">

                                <i class="bx bx-time-five"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- OVERDUE --}}

            <div class="col">

                <div class="card radius-10 border-start border-0 border-4 border-danger">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <p class="mb-0 text-secondary">
                                    Overdue
                                </p>

                                <h4 class="my-1 text-danger">

                                    {{ $overdueInvoices }}

                                </h4>

                                <p class="mb-0 font-13">

                                    AED {{ number_format($overdueAmount, 2) }}

                                </p>

                            </div>


                            <div class="widgets-icons bg-light-danger text-danger ms-auto">

                                <i class="bx bx-error-circle"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>



        {{-- =====================================================
             INVOICE TABLE
        ====================================================== --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table id="example" class="table table-striped table-bordered" style="width:100%">

                        <thead>

                            <tr>

                                <th>Sl</th>

                                <th>User Name</th>

                                <th>Vehicle</th>

                                <th>VIN</th>

                                <th>Grand Total</th>

                                <th>Paid</th>

                                <th>Remaining</th>

                                <th>Due Date</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($invoiceStatuses as $key => $item)
                                @php

                                    $grandTotal = $item->purchase->grand_total ?? 0;

                                    $paidAmount = $item->paid_amount ?? 0;

                                    $remaining = max(0, $grandTotal - $paidAmount);

                                @endphp


                                <tr>


                                    {{-- SL --}}

                                    <td>

                                        {{ $key + 1 }}

                                    </td>



                                    {{-- USER --}}

                                    <td>

                                        {{ $item->user->name ?? 'Demo' }}

                                    </td>



                                    {{-- VEHICLE --}}

                                    <td>

                                        @if ($item->purchase)
                                            {{ $item->purchase->make ?? '-' }}

                                            {{ $item->purchase->model ?? '' }}
                                        @else
                                            -
                                        @endif

                                    </td>



                                    {{-- VIN --}}

                                    <td>

                                        {{ $item->purchase->vin ?? '-' }}

                                    </td>



                                    {{-- GRAND TOTAL --}}

                                    <td>

                                        AED
                                        {{ number_format($grandTotal, 2) }}

                                    </td>



                                    {{-- PAID --}}

                                    <td class="text-success">

                                        AED
                                        {{ number_format($paidAmount, 2) }}

                                    </td>



                                    {{-- REMAINING --}}

                                    <td class="text-danger">

                                        AED
                                        {{ number_format($remaining, 2) }}

                                    </td>



                                    {{-- DUE DATE --}}

                                    <td>

                                        {{ $item->due_date ?? '-' }}

                                    </td>



                                    {{-- STATUS --}}

                                    <td>

                                        @if ($item->status == 'Paid')
                                            <span class="badge bg-success">

                                                Paid

                                            </span>
                                        @elseif ($item->status == 'Open')
                                            <span class="badge bg-warning text-dark">

                                                Open

                                            </span>
                                        @elseif ($item->status == 'Overdue')
                                            <span class="badge bg-danger">

                                                Overdue

                                            </span>
                                        @else
                                            <span class="badge bg-secondary">

                                                {{ $item->status }}

                                            </span>
                                        @endif

                                    </td>



                                    {{-- ACTION --}}

                                    <td>

                                        {{-- View --}}
                                        <a href="{{ route('invoice.status.view', $item->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="bx bx-show"></i>
                                            View
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('invoice.status.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="bx bx-edit"></i>
                                            Edit
                                        </a>

                                        {{-- Delete --}}
                                        <a href="{{ route('invoice.status.delete', $item->id) }}"
                                            class="btn btn-danger btn-sm" id="delete">
                                            <i class="bx bx-trash"></i>
                                            Delete
                                        </a>

                                    </td>


                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center">

                                        No Invoice Status Found.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


    </div>
@endsection
