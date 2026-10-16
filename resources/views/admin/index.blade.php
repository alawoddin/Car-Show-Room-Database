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


    // 2. Total Client Capital
    $totalClientCapital = User_Capitals::sum('amount');


    // 3. Total Expenses
    $totalExpense = 0;

    if (
        Schema::hasTable('expenses') &&
        Schema::hasColumn('expenses', 'amount')
    ) {
        $totalExpense = DB::table('expenses')->sum('amount');
    }


    // 4. Total Vehicles
    $totalVehicles = Purchase::count();


    // 5. Total Purchase Value
    $totalPurchase = Purchase::sum('grand_total');


    // 6. Total Sales
    $totalSales = 0;

    if (Schema::hasColumn('purchases', 'selling_price')) {
        $totalSales = Purchase::where('status', 'Sold')
            ->sum('selling_price');
    }


    // 7. Total Profit
    $totalProfit = 0;

    if (Schema::hasColumn('purchases', 'profit')) {
        $totalProfit = Purchase::where('status', 'Sold')
            ->sum('profit');
    }


    // 8. Remaining Admin Capital
    // Subtract purchase value only. Do not subtract expenses here.
    $remainingAdminCapital = $totalAdminCapital - $totalPurchase;


    // Recent Purchases
    $purchases = Purchase::with('user')
        ->latest()
        ->take(10)
        ->get();

@endphp


<div class="page-content">

    {{-- Dashboard Heading --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">Car Showroom Dashboard</h4>

            <p class="text-secondary">
                Financial overview and recent purchases
            </p>
        </div>
    </div>


    {{-- =====================================================
         FIRST ROW: CAPITAL AND EXPENSES
    ====================================================== --}}

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        {{-- 1. Total Admin Capital --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Admin Capital
                            </p>

                            <h4 class="my-1 text-info">
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
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Client Capital
                            </p>

                            <h4 class="my-1 text-success">
                                ${{ number_format($totalClientCapital, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-group"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 3. Total Expenses --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Expenses
                            </p>

                            <h4 class="my-1 text-danger">
                                ${{ number_format($totalExpense, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-money"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 4. Remaining Admin Capital --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Remaining Admin Capital
                            </p>

                            <h4 class="my-1 text-warning">
                                ${{ number_format($remainingAdminCapital, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                            <i class="bx bx-piggy-bank"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =====================================================
         SECOND ROW: VEHICLES, PURCHASES, SALES AND PROFIT
    ====================================================== --}}

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        {{-- 5. Total Vehicles --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Vehicles
                            </p>

                            <h4 class="my-1 text-primary">
                                {{ number_format($totalVehicles) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                            <i class="bx bx-car"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 6. Total Purchase Value --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Purchase Value
                            </p>

                            <h4 class="my-1 text-dark">
                                ${{ number_format($totalPurchase, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-light text-dark ms-auto">
                            <i class="bx bx-cart"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 7. Total Sales --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Sales
                            </p>

                            <h4 class="my-1 text-success">
                                ${{ number_format($totalSales, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-trending-up"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- 8. Total Profit --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div>
                            <p class="mb-0 text-secondary">
                                Total Profit
                            </p>

                            <h4 class="my-1 text-danger">
                                ${{ number_format($totalProfit, 2) }}
                            </h4>
                        </div>

                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-dollar-circle"></i>
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
                                    {{ $purchase->user?->name ?? 'Unknown User' }}
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