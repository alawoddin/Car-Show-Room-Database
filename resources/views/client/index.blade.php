@extends('client.client_dashboard')

@section('client')
@php

    $id = Auth::user()->id;

    $profileData = App\Models\User::find($id);


    /*
    |--------------------------------------------------------------------------
    | USER CAPITAL
    |--------------------------------------------------------------------------
    */

    $userCapital = \Illuminate\Support\Facades\DB::table('user_capitals')
        ->where('user_id', $id)
        ->value('amount');

    $userCapital = $userCapital ?? 0;


    /*
    |--------------------------------------------------------------------------
    | USER VEHICLES
    |--------------------------------------------------------------------------
    */

    $vehicles = App\Models\Purchase::where('user_id', $id)->get();


    /*
    |--------------------------------------------------------------------------
    | VEHICLE STATUS
    |--------------------------------------------------------------------------
    */

    $totalVehicles = $vehicles->count();

    $purchased = $vehicles->where('status', 'Purchased')->count();

    $loaded = $vehicles->where('status', 'Loaded')->count();

    $shipped = $vehicles->where('status', 'Shipped')->count();

    $delivered = $vehicles->where('status', 'Delivered')->count();

    $onHand = $vehicles->where('status', 'On Hand')->count();

    $atUae = $vehicles->where('status', 'At UAE')->count();

    $sold = $vehicles->where('status', 'Sold')->count();

@endphp




<div class="page-content">

    <!-- ================================
         WELCOME
    ================================= -->

    <div class="row">

        <div class="col-12">

            <div class="dashboard-heading mb-4">

                <h3 class="fw-bold">
                    Welcome, {{ $profileData->name }}
                </h3>

                <p class="text-muted mb-0">
                    Here is your account overview.
                </p>

            </div>

        </div>

    </div>


    <!-- ================================
         DASHBOARD CARDS
    ================================= -->

    <div class="row">


        <!-- TOTAL CAPITAL -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-1 text-white">

                        <i class="la la-money-bill fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Total Capital
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            ${{ number_format($userCapital, 2) }}

                        </h5>

                        <small class="text-muted">
                            USD
                        </small>

                    </div>

                </div>

            </div>

        </div>


        <!-- TOTAL VEHICLES -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-2 text-white">

                        <i class="la la-car fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Total Vehicles
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $totalVehicles }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- PURCHASED -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-3 text-white">

                        <i class="la la-shopping-cart fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Purchased
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $purchased }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- LOADED -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-4 text-white">

                        <i class="la la-cube fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Loaded
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $loaded }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- SHIPPED -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-5 text-white">

                        <i class="la la-ship fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Shipped
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $shipped }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- DELIVERED -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-6 text-white">

                        <i class="la la-check-circle fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Delivered
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $delivered }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- ON HAND -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-7 text-white">

                        <i class="la la-home fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            On Hand
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $onHand }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- AT UAE -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-8 text-white">

                        <i class="la la-map-marker fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            At UAE
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $atUae }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- SOLD -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-9 text-white">

                        <i class="la la-check-double fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Sold
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            {{ $sold }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


    </div>



</div>

@endsection