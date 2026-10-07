@extends('client.client_dashboard')

@section('client')
    <div class="page-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

            <div class="ps-3">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb mb-0 p-0">

                        <li class="breadcrumb-item">

                            <a href="{{ route('dashboard') }}">

                                <i class="bx bx-home-alt"></i>

                            </a>

                        </li>


                        <li class="breadcrumb-item">

                            <a href="{{ route('client.vehicles') }}">

                                Vehicle List

                            </a>

                        </li>


                        <li class="breadcrumb-item active">

                            Vehicle Details

                        </li>

                    </ol>

                </nav>

            </div>

        </div>


        <!-- Vehicle Details Card -->

        <div class="card">

            <div class="card-body">


                <!-- Header -->

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h4 class="mb-1">

                            {{ $vehicle->make }}
                            {{ $vehicle->model }}

                        </h4>

                        <p class="text-muted mb-0">

                            VIN:
                            <strong>
                                {{ $vehicle->vin }}
                            </strong>

                        </p>

                    </div>


                    <div>

                        @php

                            $statusClass = match ($vehicle->status) {
                                'Purchased' => 'bg-primary',

                                'Loaded' => 'bg-info',

                                'Shipped' => 'bg-warning',

                                'Delivered' => 'bg-success',

                                'On Hand' => 'bg-success',

                                'At UAE' => 'bg-dark',

                                'Sold' => 'bg-danger',

                                default => 'bg-secondary',
                            };

                        @endphp


                        <span class="badge {{ $statusClass }} fs-6">

                            {{ $vehicle->status }}

                        </span>

                    </div>

                </div>


                <hr>


                <!-- Vehicle Information -->

                <h5 class="mb-3">

                    <i class="bx bx-car"></i>

                    Vehicle Information

                </h5>


                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Buying Date
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->buying_date ? \Carbon\Carbon::parse($vehicle->buying_date)->format('d M Y') : '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Lot Number
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->lot_number ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            VIN
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->vin }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Cylinder
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->cylinder ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Color
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->color ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Make
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->make }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Model
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->model }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Location
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->location ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Shipping Company
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->shipping_company ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Date of Arriving
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->date_of_arriving ? \Carbon\Carbon::parse($vehicle->date_of_arriving)->format('d M Y') : '-' }}

                        </div>

                    </div>

                </div>


                <hr>


                <!-- Purchase Costs -->

                <h5 class="mb-3">

                    <i class="bx bx-money"></i>

                    Purchase Information

                </h5>


                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Buying Fee
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->buying_fee, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Towing Fee
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->towing_fee, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Shipping
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->shipping, 2) }}

                        </div>

                    </div>

                    {{-- Commission --}}
                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Commission
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->commission ?? 0, 2) }}

                        </div>

                    </div>



                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Total AED
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->total_aed, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Clearing
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->clearing, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Extra Charges
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->extra_charges, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Custom Duty
                        </label>

                        <div class="fw-bold">

                            {{ number_format($vehicle->custom_duty, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Grand Total
                        </label>

                        <div class="fw-bold fs-5">

                            {{ number_format($vehicle->grand_total, 2) }}

                        </div>

                    </div>

                </div>


                <hr>


                <!-- Other Information -->

                <h5 class="mb-3">

                    <i class="bx bx-file"></i>

                    Other Information

                </h5>


                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="text-muted">
                            Bill Number
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->bill_no ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="text-muted">
                            Description
                        </label>

                        <div class="fw-bold">

                            {{ $vehicle->description ?? '-' }}

                        </div>

                    </div>

                </div>


                <!-- Back -->

                <div class="mt-4">

                    <a href="{{ route('client.vehicles') }}" class="btn btn-secondary">

                        <i class="bx bx-arrow-back"></i>

                        Back to Vehicles

                    </a>

                </div>


            </div>

        </div>

    </div>
@endsection
