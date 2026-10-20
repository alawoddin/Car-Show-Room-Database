@extends('admin.admin_dashboard')

@section('admin')

@php
    use App\Models\Purchase;
    use App\Models\User_Capitals;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    /*
    |--------------------------------------------------------------------------
    | Dashboard Calculations
    |--------------------------------------------------------------------------
    */

    // 1. Total Admin Capital
    $totalAdminCapital = DB::table('capital_transactions')
        ->sum('amount');


    // 2. Total Client Capital (user_capitals is the client capital table)
    $totalClientCapital = User_Capitals::sum('amount');


    // 3. Total Expenses
    $totalExpense = 0;

    if (
        Schema::hasTable('expenses') &&
        Schema::hasColumn('expenses', 'amount')
    ) {
        $totalExpense = DB::table('expenses')->sum('amount');
    }


    // Cars, sales, and profit are kept on the account the car was bought for.
    // One account pays the full car price. A shared car is split evenly.
    $purchaseRows = Purchase::with(['user', 'users'])->get();
    $adminCarTotal = 0;
    $clientCarTotal = 0;
    $adminVehicles = 0;
    $clientVehicles = 0;
    $adminSales = 0;
    $clientSales = 0;
    $adminProfit = 0;
    $clientProfit = 0;

    foreach ($purchaseRows as $purchaseRow) {
        $shares = $purchaseRow->buyerShares();
        $isSold = strcasecmp((string) $purchaseRow->status, 'Sold') === 0;
        $hasAdmin = $shares->contains(fn ($share) => $share['user']->role === 'admin');
        $hasClient = $shares->contains(fn ($share) => $share['user']->role !== 'admin');

        // A sold car is a sale, not a car still held by that account.
        if (! $isSold && $hasAdmin) {
            $adminVehicles++;
        }

        if (! $isSold && $hasClient) {
            $clientVehicles++;
        }

        if (! $isSold) {
            foreach ($shares as $share) {
                if ($share['user']->role === 'admin') {
                    $adminCarTotal += $share['amount'];
                } else {
                    $clientCarTotal += $share['amount'];
                }
            }
        }

        if (! $isSold) {
            continue;
        }

        foreach ($purchaseRow->buyerShares((float) $purchaseRow->selling_price) as $share) {
            if ($share['user']->role === 'admin') {
                $adminSales += $share['amount'];
            } else {
                $clientSales += $share['amount'];
            }
        }

        foreach ($purchaseRow->buyerShares((float) $purchaseRow->profit) as $share) {
            if ($share['user']->role === 'admin') {
                $adminProfit += $share['amount'];
            } else {
                $clientProfit += $share['amount'];
            }
        }
    }

    $totalVehicles = $purchaseRows->count();
    $remainingAdminCapital = $totalAdminCapital - $adminCarTotal;
    $remainingClientCapital = $totalClientCapital - $clientCarTotal;


    // Recent Purchases
    $purchases = Purchase::with(['user', 'users'])
        ->latest()
        ->take(10)
        ->get();

@endphp


<div class="page-content">

    <div class="showroom-hero mb-4">
        <div>
            <p class="showroom-kicker">Welcome back</p>
            <h4>{{ Auth::user()->name }}</h4>
            <p>Financial overview and recent purchases</p>
        </div>
        <div class="showroom-hero-meta">
            <span>{{ number_format($totalVehicles) }} vehicles</span>
            <span>{{ now()->format('M d, Y') }}</span>
        </div>
    </div>


    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-admin">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Admin Capital
                            </p>

                            <h4 class="my-1">
                                ${{ number_format($totalAdminCapital, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                            <i class="bx bx-wallet"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 2. Total Client Capital --}}
        <div class="col">
            <div class="card stat-card stat-purchase">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Admin Cars
                            </p>

                            <h4 class="my-1">
                                ${{ number_format($adminCarTotal, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle ms-auto">
                            <i class="bx bx-car"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 3. Total Expenses --}}
        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Remaining Admin Capital
                            </p>

                            <h4 class="my-1">
                                ${{ number_format($remainingAdminCapital, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-money"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card stat-card stat-vehicles">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Admin Vehicles</p>
                            <h4 class="my-1">{{ number_format($adminVehicles) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                            <i class="bx bx-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-client">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Client Capital</p>
                            <h4 class="my-1">${{ number_format($totalClientCapital, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-group"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-client">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Cars</p>
                            <h4 class="my-1">${{ number_format($clientCarTotal, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle ms-auto">
                            <i class="bx bx-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-balance">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Remaining Client Capital</p>
                            <h4 class="my-1">${{ number_format($remainingClientCapital, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                            <i class="bx bx-coin-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-vehicles">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Vehicles</p>
                            <h4 class="my-1">{{ number_format($clientVehicles) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-sales">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Admin Sales</p>
                            <h4 class="my-1">${{ number_format($adminSales, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-sales">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Sales</p>
                            <h4 class="my-1">${{ number_format($clientSales, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-profit">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Admin Profit</p>
                            <h4 class="my-1">${{ number_format($adminProfit, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-dollar-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-profit">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Profit</p>
                            <h4 class="my-1">${{ number_format($clientProfit, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-dollar-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Expenses</p>
                            <h4 class="my-1">${{ number_format($totalExpense, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-money"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =====================================================
         RECENT PURCHASES TABLE
    ====================================================== --}}

    <div class="card radius-10">

        <div class="card-header">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0">Recent Purchases</h6>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>User Name</th>
                            <th>Buying Date</th>
                            <th>Lot Number</th>
                            <th>Status</th>
                            <th>Model</th>
                            <th>Shipping Company</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($purchases as $purchase)

                            @php
                                $status = strtolower(
                                    $purchase->status ?? 'pending'
                                );

                                $badgeClass = match ($status) {
                                    'pending' => 'bg-gradient-blooker',
                                    'failed', 'cancelled' => 'bg-gradient-bloody',
                                    'delivered' => 'bg-success',
                                    'shipped' => 'bg-info',
                                    'loaded' => 'bg-primary',
                                    default => 'bg-secondary',
                                };
                            @endphp


                            <tr>

                                {{-- User Name --}}
                                <td>
                                    {{ $purchase->buyerNames() }}
                                </td>


                                {{-- Buying Date --}}
                                <td>
                                    {{ $purchase->buying_date
                                        ? \Carbon\Carbon::parse($purchase->buying_date)->format('Y/m/d')
                                        : 'N/A' }}
                                </td>


                                {{-- Lot Number --}}
                                <td>
                                    #{{ $purchase->lot_number ?? 'N/A' }}
                                </td>


                                {{-- Status --}}
                                <td>
                                    <span class="badge {{ $badgeClass }} text-white shadow-sm">
                                        {{ ucfirst($purchase->status ?? 'Pending') }}
                                    </span>
                                </td>


                                {{-- Vehicle Model --}}
                                <td>
                                    {{ $purchase->model ?? 'N/A' }}
                                </td>


                                {{-- Shipping Company --}}
                                <td>
                                    {{ $purchase->shipping_company ?? 'N/A' }}
                                </td>


                                {{-- Grand Total --}}
                                <td>
                                    <strong>
                                        ${{ number_format((float) ($purchase->grand_total ?? 0), 2) }}
                                    </strong>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-car fs-3 d-block mb-2"></i>
                                    No purchases found.
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