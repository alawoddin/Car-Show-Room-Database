@extends('admin.admin_dashboard')

@section('admin')

@php
    use App\Models\Purchase;
    use App\Models\User_Capitals;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    // Capital totals
    $totalAdminCapital = (float) DB::table('capital_transactions')->sum('amount');
    $totalClientCapital = (float) User_Capitals::sum('amount');

    // Expenses
    $totalExpense = 0.0;
    $adminExpense = 0.0;
    $clientExpense = 0.0;

    if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'amount')) {
        $totalExpense = (float) DB::table('expenses')->sum('amount');

        if (Schema::hasColumn('expenses', 'user_id')) {
            $adminExpense = (float) DB::table('expenses')
                ->join('users', 'expenses.user_id', '=', 'users.id')
                ->where('users.role', 'admin')
                ->sum('expenses.amount');

            $clientExpense = (float) DB::table('expenses')
                ->join('users', 'expenses.user_id', '=', 'users.id')
                ->where('users.role', 'user')
                ->sum('expenses.amount');
        }
    }

    // Purchase shares (admin vs client)
    $adminCarTotal = 0.0;
    $clientCarTotal = 0.0;
    $adminVehicles = 0;
    $clientVehicles = 0;
    $adminSales = 0.0;
    $clientSales = 0.0;
    $adminProfit = 0.0;
    $clientProfit = 0.0;
    $adminLoss = 0.0;
    $clientLoss = 0.0;

    $allPurchases = Purchase::with(['user', 'users'])->get();

    foreach ($allPurchases as $purchase) {
        $isSold = strcasecmp((string) $purchase->status, 'Sold') === 0;
        $costShares = $purchase->buyerShares((float) $purchase->grand_total);
        $saleShares = $isSold
            ? $purchase->buyerShares((float) $purchase->selling_price)
            : collect();
        $profitShares = $isSold
            ? $purchase->buyerShares((float) $purchase->profit)
            : collect();

        $adminInBuyers = false;
        $clientInBuyers = false;

        foreach ($costShares as $share) {
            $role = $share['user']->role ?? 'user';
            $amount = (float) $share['amount'];

            if ($role === 'admin') {
                $adminInBuyers = true;
                if (! $isSold) {
                    $adminCarTotal += $amount;
                }
            } else {
                $clientInBuyers = true;
                if (! $isSold) {
                    $clientCarTotal += $amount;
                }
            }
        }

        if (! $isSold) {
            if ($adminInBuyers) {
                $adminVehicles++;
            }
            if ($clientInBuyers) {
                $clientVehicles++;
            }
        }

        foreach ($saleShares as $share) {
            $role = $share['user']->role ?? 'user';
            $amount = (float) $share['amount'];

            if ($role === 'admin') {
                $adminSales += $amount;
            } else {
                $clientSales += $amount;
            }
        }

        // Profit cards only count gains; loss cards count sold cars with no profit.
        foreach ($profitShares as $share) {
            $role = $share['user']->role ?? 'user';
            $amount = (float) $share['amount'];

            if ($role === 'admin') {
                if ($amount > 0) {
                    $adminProfit += $amount;
                } elseif ($amount < 0) {
                    $adminLoss += abs($amount);
                }
            } else {
                if ($amount > 0) {
                    $clientProfit += $amount;
                } elseif ($amount < 0) {
                    $clientLoss += abs($amount);
                }
            }
        }
    }

    $totalVehicles = $allPurchases->count();
    $totalPurchase = (float) $allPurchases->sum('grand_total');
    $totalSales = $adminSales + $clientSales;
    $totalProfit = $adminProfit + $clientProfit;
    $totalLoss = $adminLoss + $clientLoss;

    // Remaining = capital - unsold cars - expenses
    $remainingAdminCapital = $totalAdminCapital - $adminCarTotal - $adminExpense;
    $remainingClientCapital = $totalClientCapital - $clientCarTotal - $clientExpense;

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
            <button type="button" id="toggleCapitalVisibility" class="btn btn-sm btn-light capital-toggle-btn" title="Hide / Show capital">
                <i class="bx bx-hide" id="capitalToggleIcon"></i>
            </button>
        </div>
    </div>


    {{-- Capital --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-admin">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Admin Capital</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($totalAdminCapital, 2) }}">
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

        <div class="col">
            <div class="card stat-card stat-balance">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Remaining Admin Capital</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($remainingAdminCapital, 2) }}">
                                ${{ number_format($remainingAdminCapital, 2) }}
                            </h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                            <i class="bx bx-coin-stack"></i>
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
                            <p class="mb-0 text-secondary">Total Client Capital</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($totalClientCapital, 2) }}">
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

        <div class="col">
            <div class="card stat-card stat-client">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Remaining Client Capital</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($remainingClientCapital, 2) }}">
                                ${{ number_format($remainingClientCapital, 2) }}
                            </h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bx bx-coin-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Cars (purchase cost still held) --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-purchase">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Admin Cars</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($adminCarTotal, 2) }}">
                                ${{ number_format($adminCarTotal, 2) }}
                            </h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light text-dark ms-auto">
                            <i class="bx bx-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-purchase">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Cars</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($clientCarTotal, 2) }}">
                                ${{ number_format($clientCarTotal, 2) }}
                            </h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light text-dark ms-auto">
                            <i class="bx bx-car"></i>
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

        <div class="col">
            <div class="card stat-card stat-vehicles">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Vehicles</p>
                            <h4 class="my-1">{{ number_format($clientVehicles) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                            <i class="bx bx-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Sales and Profit --}}
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
                            <h4 class="my-1 money-value" data-value="${{ number_format($adminProfit, 2) }}">
                                ${{ number_format($adminProfit, 2) }}
                            </h4>
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
                            <h4 class="my-1 money-value" data-value="${{ number_format($clientProfit, 2) }}">
                                ${{ number_format($clientProfit, 2) }}
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


    {{-- Loss (sold cars with no profit / negative profit) --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Admin Total Loss</p>
                            <h4 class="my-1">${{ number_format($adminLoss, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class="bx bx-trending-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Total Loss</p>
                            <h4 class="my-1">${{ number_format($clientLoss, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class="bx bx-trending-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Loss</p>
                            <h4 class="my-1">${{ number_format($totalLoss, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class="bx bx-error-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Expenses --}}
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

        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Admin Expenses</p>
                            <h4 class="my-1">${{ number_format($adminExpense, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-money"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-expense">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Client Expenses</p>
                            <h4 class="my-1">${{ number_format($clientExpense, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                            <i class="bx bx-money"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card stat-card stat-purchase">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Purchase Value</p>
                            <h4 class="my-1">${{ number_format($totalPurchase, 2) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light text-dark ms-auto">
                            <i class="bx bx-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Recent Purchases --}}
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
                                $status = strtolower($purchase->status ?? 'pending');
                                $badgeClass = match ($status) {
                                    'pending' => 'bg-gradient-blooker',
                                    'failed', 'cancelled' => 'bg-gradient-bloody',
                                    'delivered' => 'bg-success',
                                    'shipped' => 'bg-info',
                                    'loaded' => 'bg-primary',
                                    'sold' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td>{{ $purchase->buyerNames() }}</td>
                                <td>
                                    {{ $purchase->buying_date
                                        ? \Carbon\Carbon::parse($purchase->buying_date)->format('Y/m/d')
                                        : 'N/A' }}
                                </td>
                                <td>#{{ $purchase->lot_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }} text-white shadow-sm">
                                        {{ ucfirst($purchase->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td>{{ $purchase->model ?? 'N/A' }}</td>
                                <td>{{ $purchase->shipping_company ?? 'N/A' }}</td>
                                <td>
                                    <strong>${{ number_format((float) ($purchase->grand_total ?? 0), 2) }}</strong>
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

<style>
    .capital-toggle-btn {
        border: 0;
        line-height: 1;
        padding: 0.35rem 0.55rem;
    }
    .capital-toggle-btn i {
        font-size: 1.1rem;
        vertical-align: middle;
    }
</style>

<script>
    (function () {
        var button = document.getElementById('toggleCapitalVisibility');
        var icon = document.getElementById('capitalToggleIcon');
        if (!button || !icon) {
            return;
        }

        var hidden = localStorage.getItem('admin-capital-hidden') === '1';

        function applyState() {
            document.querySelectorAll('.money-value').forEach(function (el) {
                if (hidden) {
                    el.textContent = '••••••';
                } else {
                    el.textContent = el.getAttribute('data-value') || el.textContent;
                }
            });

            icon.className = hidden ? 'bx bx-show' : 'bx bx-hide';
            button.title = hidden ? 'Show capital' : 'Hide capital';
        }

        applyState();

        button.addEventListener('click', function () {
            hidden = !hidden;
            localStorage.setItem('admin-capital-hidden', hidden ? '1' : '0');
            applyState();
        });
    })();
</script>

@endsection
