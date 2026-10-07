@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    {{-- =========================
        Breadcrumb
    ========================== --}}
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
                        <a href="{{ route('vehicle.status') }}">
                            Vehicle Status
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Vehicle Invoice
                    </li>

                </ol>

            </nav>
        </div>

      

    </div>


    {{-- =========================
        Invoice Card
    ========================== --}}
    <div class="card invoice-card">

        <div class="card-body p-5">


            {{-- =========================
                Invoice Header
            ========================== --}}
            <div class="row align-items-center mb-4">

                <div class="col-md-6">

                    <h2 class="fw-bold mb-1">
                        CAR SHOW ROOM
                    </h2>

                    <p class="text-muted mb-0">
                        Vehicle Management System
                    </p>

                    <p class="text-muted mb-0">
                        Purchase & Sales Invoice
                    </p>

                </div>


                <div class="col-md-6 text-md-end">

                    <h3 class="fw-bold mb-2">
                        VEHICLE INVOICE
                    </h3>

                    <p class="mb-1">
                        <strong>Invoice ID:</strong>
                        #{{ $vehicle->id }}
                    </p>

                    <p class="mb-0">
                        <strong>Date:</strong>
                        {{ $vehicle->created_at?->format('Y-m-d') }}
                    </p>

                </div>

            </div>


            <hr>


            {{-- =========================
                Vehicle Information
            ========================== --}}
            <div class="section-title">

                <h5 class="fw-bold mb-3">
                    <i class="bx bx-car"></i>
                    Vehicle Information
                </h5>

            </div>


            <div class="row mb-4">

                {{-- User --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        User
                    </small>

                    <div class="fw-bold">
                        {{ $vehicle->user->name ?? 'Demo' }}
                    </div>

                </div>


                {{-- VIN --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        VIN
                    </small>

                    <div class="fw-bold">
                        {{ $vehicle->vin }}
                    </div>

                </div>


                {{-- Make --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Make
                    </small>

                    <div class="fw-bold">
                        {{ $vehicle->make }}
                    </div>

                </div>


                {{-- Model --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Model
                    </small>

                    <div class="fw-bold">
                        {{ $vehicle->model }}
                    </div>

                </div>


                {{-- Color --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Color
                    </small>

                    <div>
                        {{ $vehicle->color ?? '-' }}
                    </div>

                </div>


                {{-- Cylinder --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Cylinder
                    </small>

                    <div>
                        {{ $vehicle->cylinder ?? '-' }}
                    </div>

                </div>


                {{-- Lot Number --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Lot Number
                    </small>

                    <div>
                        {{ $vehicle->lot_number ?? '-' }}
                    </div>

                </div>


                {{-- Location --}}
                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Location
                    </small>

                    <div>
                        {{ $vehicle->location ?? '-' }}
                    </div>

                </div>

            </div>


            {{-- =========================
                Purchase Information
            ========================== --}}
            <div class="section-title">

                <h5 class="fw-bold mb-3">
                    <i class="bx bx-cart"></i>
                    Purchase Information
                </h5>

            </div>


            <div class="table-responsive mb-4">

                <table class="table table-bordered invoice-table">

                    <tbody>

                        {{-- Buying Date / Buying Fee --}}
                        <tr>

                            <th width="25%">
                                Buying Date
                            </th>

                            <td>
                                {{ $vehicle->buying_date ?? '-' }}
                            </td>

                            <th width="25%">
                                Buying Fee
                            </th>

                            <td>
                                ${{ number_format($vehicle->buying_fee ?? 0, 2) }}
                            </td>

                        </tr>


                        {{-- Towing / Shipping --}}
                        <tr>

                            <th>
                                Towing Fee
                            </th>

                            <td>
                                ${{ number_format($vehicle->towing_fee ?? 0, 2) }}
                            </td>

                            <th>
                                Shipping
                            </th>

                            <td>
                                ${{ number_format($vehicle->shipping ?? 0, 2) }}
                            </td>

                        </tr>


                        {{-- Shipping Company --}}
                        <tr>

                            <th>
                                Shipping Company
                            </th>

                            <td colspan="3">
                                {{ $vehicle->shipping_company ?? '-' }}
                            </td>

                        </tr>


                        {{-- Total AED --}}
                        <tr class="total-row">

                            <th>
                                Total AED
                            </th>

                            <td colspan="3">

                                <strong>
                                    AED
                                    {{ number_format($vehicle->total_aed ?? 0, 2) }}
                                </strong>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- =========================
                Additional Costs
            ========================== --}}
            <div class="section-title">

                <h5 class="fw-bold mb-3">
                    <i class="bx bx-calculator"></i>
                    Additional Costs
                </h5>

            </div>


            <div class="table-responsive mb-4">

                <table class="table table-bordered invoice-table">

                    <tbody>

                        <tr>

                            <th width="25%">
                                Clearing
                            </th>

                            <td>
                                AED
                                {{ number_format($vehicle->clearing ?? 0, 2) }}
                            </td>

                            <th width="25%">
                                Extra Charges
                            </th>

                            <td>
                                AED
                                {{ number_format($vehicle->extra_charges ?? 0, 2) }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Custom Duty
                            </th>

                            <td>
                                AED
                                {{ number_format($vehicle->custom_duty ?? 0, 2) }}
                            </td>

                            <th>
                                Grand Total
                            </th>

                            <td class="grand-total">

                                AED
                                {{ number_format($vehicle->grand_total ?? 0, 2) }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- =========================
                Sale Information
            ========================== --}}
            <div class="section-title">

                <h5 class="fw-bold mb-3">
                    <i class="bx bx-money"></i>
                    Sale Information
                </h5>

            </div>


            <div class="table-responsive mb-4">

                <table class="table table-bordered invoice-table">

                    <tbody>

                        {{-- Selling Price / Profit --}}
                        <tr>

                            <th width="25%">
                                Selling Price
                            </th>

                            <td>
                                AED
                                {{ number_format($vehicle->selling_price ?? 0, 2) }}
                            </td>

                            <th width="25%">
                                Profit
                            </th>

                            <td>

                                <strong>
                                    AED
                                    {{ number_format($vehicle->profit ?? 0, 2) }}
                                </strong>

                            </td>

                        </tr>


                        {{-- Bill / Customer --}}
                        <tr>

                            <th>
                                Bill No
                            </th>

                            <td>
                                {{ $vehicle->bill_no ?? '-' }}
                            </td>

                            <th>
                                Customer Name
                            </th>

                            <td>
                                {{ $vehicle->customer_name ?? '-' }}
                            </td>

                        </tr>


                        {{-- Arrival / Status --}}
                        <tr>

                            <th>
                                Date Of Arriving
                            </th>

                            <td>
                                {{ $vehicle->date_of_arriving ?? '-' }}
                            </td>

                            <th>
                                Status
                            </th>

                            <td>

                                @php

                                    $statusClass = match($vehicle->status) {

                                        'Purchased' => 'bg-primary',

                                        'Loaded' => 'bg-info',

                                        'Shipped' => 'bg-warning text-dark',

                                        'Delivered' => 'bg-success',

                                        'On Hand' => 'bg-secondary',

                                        'At UAE' => 'bg-dark',

                                        'Sold' => 'bg-danger',

                                        default => 'bg-secondary',

                                    };

                                @endphp


                                <span class="badge {{ $statusClass }} px-3 py-2">

                                    {{ $vehicle->status }}

                                </span>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- =========================
                Description
            ========================== --}}
            <div class="section-title">

                <h5 class="fw-bold mb-3">
                    <i class="bx bx-note"></i>
                    Description
                </h5>

            </div>


            <div class="description-box mb-4">

                {{ $vehicle->description ?? 'No description available.' }}

            </div>


            {{-- =========================
                Invoice Footer
            ========================== --}}
            <hr>

            <div class="row mt-4">

                <div class="col-md-7">

                    <h6 class="fw-bold">
                        Thank you for your business.
                    </h6>

                    <p class="text-muted mb-0">
                        This invoice was generated by
                        Car Show Room Management System.
                    </p>

                </div>


                <div class="col-md-5 text-md-end">

                    <p class="mb-1">

                        <strong>
                            VIN:
                        </strong>

                        {{ $vehicle->vin }}

                    </p>

                    <p class="mb-0">

                        <strong>
                            Status:
                        </strong>

                        {{ $vehicle->status }}

                    </p>

                </div>

            </div>


        </div>

    </div>

</div>


{{-- =====================================================
     INVOICE STYLE
====================================================== --}}
<style>

.invoice-card {

    border-radius: 8px;

    box-shadow:
        0 3px 15px rgba(0, 0, 0, 0.08);

}


.section-title {

    border-left: 4px solid #0d6efd;

    padding-left: 12px;

    margin-bottom: 15px;

}


.invoice-table th {

    background: #f8f9fa;

    font-weight: 600;

}


.invoice-table td,
.invoice-table th {

    padding: 12px;

    vertical-align: middle;

}


.total-row {

    background: #f8f9fa;

}


.grand-total {

    font-size: 18px;

    font-weight: 700;

}


.description-box {

    min-height: 80px;

    border: 1px solid #dee2e6;

    border-radius: 6px;

    padding: 15px;

    background: #f8f9fa;

}


/* =====================================================
   PRINT STYLE
===================================================== */

@media print {

    body {

        background: #ffffff !important;

    }


    .page-content {

        padding: 0 !important;

    }


    .page-breadcrumb,
    .sidebar-wrapper,
    .header-wrapper,
    .btn {

        display: none !important;

    }


    .card {

        border: none !important;

        box-shadow: none !important;

    }


    .card-body {

        padding: 20px !important;

    }


    .invoice-table {

        width: 100% !important;

    }

}

</style>

@endsection