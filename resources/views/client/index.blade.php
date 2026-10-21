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

    // Capital & expenses stored in USD → convert to AED
    $userCapitalUsd = (float) (\Illuminate\Support\Facades\DB::table('user_capitals')
        ->where('user_id', $id)
        ->sum('amount') ?? 0);

    $userCapital = \App\Support\Currency::usdToAed($userCapitalUsd);

    $userExpenseUsd = 0;

    if (
        \Illuminate\Support\Facades\Schema::hasTable('expenses') &&
        \Illuminate\Support\Facades\Schema::hasColumn('expenses', 'user_id')
    ) {
        $userExpenseUsd = (float) \Illuminate\Support\Facades\DB::table('expenses')
            ->where('user_id', $id)
            ->sum('amount');
    }

    $userExpense = \App\Support\Currency::usdToAed($userExpenseUsd);

    /*
    |--------------------------------------------------------------------------
    | USER VEHICLES (includes shared purchases) — grand_total already AED
    |--------------------------------------------------------------------------
    */

    $vehicles = App\Models\Purchase::forUser((int) $id)
        ->with('users')
        ->get();

    $carSpend = 0;

    foreach ($vehicles as $vehicle) {
        if (strcasecmp((string) $vehicle->status, 'Sold') === 0) {
            continue;
        }

        $share = $vehicle->buyerShares()
            ->first(fn ($item) => (int) $item['user']->id === (int) $id);

        $carSpend += (float) ($share['amount'] ?? 0);
    }

    // Remaining AED = capital AED − expense AED − cars AED
    $remainingCapital = $userCapital - $userExpense - $carSpend;


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

    <div class="client-hero">
        <div>
            <p class="client-kicker">Welcome back</p>
            <h3>{{ $profileData->name }}</h3>
            <p>Here is your account overview.</p>
        </div>
        <span class="client-hero-note">{{ $totalVehicles }} vehicles</span>
    </div>


    <!-- ================================
         DASHBOARD CARDS
    ================================= -->

    <div class="row">


        <!-- TOTAL CAPITAL -->

        <div class="col-lg-4 responsive-column-half">

            <div class="card card-item dashboard-info-card stat-capital">

                <div class="card-body d-flex align-items-center">

                    <div class="icon-element flex-shrink-0 bg-1 text-white">

                        <i class="la la-money-bill fs-30"></i>

                    </div>

                    <div class="pl-4">

                        <p class="card-text fs-18">
                            Total Capital
                        </p>

                        <h5 class="card-title pt-2 fs-26">

                            AED {{ number_format($userCapital, 2) }}

                        </h5>

                        <small class="text-muted">
                            Remaining after cars &amp; expenses: AED {{ number_format($remainingCapital, 2) }}
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

            <div class="card card-item dashboard-info-card stat-purchased">

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

            <div class="card card-item dashboard-info-card stat-shipped">

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

            <div class="card card-item dashboard-info-card stat-delivered">

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

            <div class="card card-item dashboard-info-card stat-sold">

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