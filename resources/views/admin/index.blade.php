@extends('admin.admin_dashboard')

@section('admin')

@php
    use App\Models\Purchase;
    use App\Models\User_Capitals;
    use App\Support\Currency;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    // Capital cards: keep USD as entered (e.g. $10000)
    $totalAdminCapitalUsd = (float) DB::table('capital_transactions')->sum('amount');
    $totalClientCapitalUsd = (float) User_Capitals::sum('amount');

    // Same capital in AED (for remaining calc only)
    $totalAdminCapitalAed = Currency::usdToAed($totalAdminCapitalUsd);
    $totalClientCapitalAed = Currency::usdToAed($totalClientCapitalUsd);

    // Expenses stored in USD
    $totalExpenseUsd = 0.0;
    $adminExpenseUsd = 0.0;
    $clientExpenseUsd = 0.0;

    if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'amount')) {
        $totalExpenseUsd = (float) DB::table('expenses')->sum('amount');

        if (Schema::hasColumn('expenses', 'user_id')) {
            $adminExpenseUsd = (float) DB::table('expenses')
                ->join('users', 'expenses.user_id', '=', 'users.id')
                ->where('users.role', 'admin')
                ->sum('expenses.amount');

            $clientExpenseUsd = (float) DB::table('expenses')
                ->join('users', 'expenses.user_id', '=', 'users.id')
                ->where('users.role', 'user')
                ->sum('expenses.amount');
        }
    }

    $totalExpense = Currency::usdToAed($totalExpenseUsd);
    $adminExpense = Currency::usdToAed($adminExpenseUsd);
    $clientExpense = Currency::usdToAed($clientExpenseUsd);

    // Buying fee held in unsold cars (USD + AED). Sold cars return buying fee to capital.
    $adminBuyingFeeUsd = 0.0;
    $clientBuyingFeeUsd = 0.0;
    $adminCarTotal = 0.0; // buying fee AED (unsold)
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

        $buyingFeeUsd = (float) ($purchase->buying_fee ?? 0);
        $buyingFeeAed = Currency::usdToAed($buyingFeeUsd);

        // Capital uses buying fee only (not grand total)
        $usdShares = $purchase->buyerShares($buyingFeeUsd);
        $aedShares = $purchase->buyerShares($buyingFeeAed);
        $saleShares = $isSold
            ? $purchase->buyerShares((float) $purchase->selling_price)
            : collect();
        $profitShares = $isSold
            ? $purchase->buyerShares((float) $purchase->profit)
            : collect();

        $adminInBuyers = false;
        $clientInBuyers = false;

        $aedByUser = $aedShares->keyBy(fn ($share) => (int) $share['user']->id);

        foreach ($usdShares as $share) {
            $userId = (int) $share['user']->id;
            $role = $share['user']->role ?? 'user';
            $usdAmount = (float) $share['amount'];
            $matchedAed = $aedByUser->get($userId);
            $aedAmount = (float) ($matchedAed['amount'] ?? Currency::usdToAed($usdAmount));

            if ($role === 'admin') {
                $adminInBuyers = true;
                // Unsold: − buying fee from USD and AED capital. Sold: fee returns.
                if (! $isSold) {
                    $adminBuyingFeeUsd += $usdAmount;
                    $adminCarTotal += $aedAmount;
                }
            } else {
                $clientInBuyers = true;
                if (! $isSold) {
                    $clientBuyingFeeUsd += $usdAmount;
                    $clientCarTotal += $aedAmount;
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

    // Remaining USD & AED: capital − buying fee (unsold) − expense
    // When car is Sold, buying fee is not held anymore (= added back to capital)
    $remainingAdminUsd = max(0, $totalAdminCapitalUsd - $adminBuyingFeeUsd - $adminExpenseUsd);
    $remainingClientUsd = max(0, $totalClientCapitalUsd - $clientBuyingFeeUsd - $clientExpenseUsd);
    $remainingAdminAed = max(0, $totalAdminCapitalAed - $adminCarTotal - $adminExpense);
    $remainingClientAed = max(0, $totalClientCapitalAed - $clientCarTotal - $clientExpense);

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


    {{-- Capital: deposited + remaining (USD & AED). Purchase holds buying fee; sale returns it. --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

        <div class="col">
            <div class="card stat-card stat-admin">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Admin Capital</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($totalAdminCapitalUsd, 2) }} / AED {{ number_format($totalAdminCapitalAed, 2) }}">
                                ${{ number_format($totalAdminCapitalUsd, 2) }}
                            </h4>
                            <small class="text-muted money-value" data-value="AED {{ number_format($totalAdminCapitalAed, 2) }}">
                                AED {{ number_format($totalAdminCapitalAed, 2) }}
                            </small>
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
                            <h4 class="my-1 money-value" data-value="${{ number_format($remainingAdminUsd, 2) }} / AED {{ number_format($remainingAdminAed, 2) }}">
                                ${{ number_format($remainingAdminUsd, 2) }}
                            </h4>
                            <small class="text-muted money-value" data-value="AED {{ number_format($remainingAdminAed, 2) }}">
                                AED {{ number_format($remainingAdminAed, 2) }}
                            </small>
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
                            <h4 class="my-1 money-value" data-value="${{ number_format($totalClientCapitalUsd, 2) }} / AED {{ number_format($totalClientCapitalAed, 2) }}">
                                ${{ number_format($totalClientCapitalUsd, 2) }}
                            </h4>
                            <small class="text-muted money-value" data-value="AED {{ number_format($totalClientCapitalAed, 2) }}">
                                AED {{ number_format($totalClientCapitalAed, 2) }}
                            </small>
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
                            <h4 class="my-1 money-value" data-value="${{ number_format($remainingClientUsd, 2) }} / AED {{ number_format($remainingClientAed, 2) }}">
                                ${{ number_format($remainingClientUsd, 2) }}
                            </h4>
                            <small class="text-muted money-value" data-value="AED {{ number_format($remainingClientAed, 2) }}">
                                AED {{ number_format($remainingClientAed, 2) }}
                            </small>
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
                            <p class="mb-0 text-secondary">Admin Buying Fee (held)</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($adminBuyingFeeUsd, 2) }}">
                                ${{ number_format($adminBuyingFeeUsd, 2) }}
                            </h4>
                            <small class="text-muted money-value" data-value="AED {{ number_format($adminCarTotal, 2) }}">
                                AED {{ number_format($adminCarTotal, 2) }}
                            </small>
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
                            <p class="mb-0 text-secondary">Client Buying Fee (held)</p>
                            <h4 class="my-1 money-value" data-value="${{ number_format($clientBuyingFeeUsd, 2) }}">
                                ${{ number_format($clientBuyingFeeUsd, 2) }}
                            </h4>
                            <small class="text-muted money-value" data-value="AED {{ number_format($clientCarTotal, 2) }}">
                                AED {{ number_format($clientCarTotal, 2) }}
                            </small>
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
                            <h4 class="my-1">AED {{ number_format($adminSales, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($clientSales, 2) }}</h4>
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
                            <h4 class="my-1 money-value" data-value="AED {{ number_format($adminProfit, 2) }}">
                                AED {{ number_format($adminProfit, 2) }}
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
                            <h4 class="my-1 money-value" data-value="AED {{ number_format($clientProfit, 2) }}">
                                AED {{ number_format($clientProfit, 2) }}
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
                            <h4 class="my-1">AED {{ number_format($adminLoss, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($clientLoss, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($totalLoss, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($totalExpense, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($adminExpense, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($clientExpense, 2) }}</h4>
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
                            <h4 class="my-1">AED {{ number_format($totalPurchase, 2) }}</h4>
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
                                    <strong>AED {{ number_format((float) ($purchase->grand_total ?? 0), 2) }}</strong>
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
