@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Orders</p>
                                <h4 class="my-1 text-info">4805</h4>
                                <p class="mb-0 font-13">+2.5% from last week</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                    class='bx bxs-cart'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Revenue</p>
                                <h4 class="my-1 text-danger">$84,245</h4>
                                <p class="mb-0 font-13">+5.4% from last week</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i
                                    class='bx bxs-wallet'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Bounce Rate</p>
                                <h4 class="my-1 text-success">34.6%</h4>
                                <p class="mb-0 font-13">-4.5% from last week</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                    class='bx bxs-bar-chart-alt-2'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Customers</p>
                                <h4 class="my-1 text-warning">8.4K</h4>
                                <p class="mb-0 font-13">+8.4% from last week</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
{{-- 
        <!--end row-->

        <div class="row">

            <div class="col-12 col-lg-12 d-flex">

                <div class="card radius-10 w-100">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <div>
                                <h6 class="mb-0">Purchase Overview</h6>
                            </div>

                            <div class="dropdown ms-auto">

                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">

                                    <i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>

                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="javascript:;">
                                            Current Year
                                        </a>
                                    </li>
                                </ul>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">

                            <span class="border px-1 rounded">
                                <i class="bx bxs-circle me-1" style="color: #14abef"></i>
                                Purchases
                            </span>

                        </div>

                        <div class="chart-container-1">
                            <canvas id="chart1"></canvas>
                        </div>

                    </div>


                    {{-- Summary Statistics --}}
                    <div class="row row-cols-1 row-cols-md-3 g-0 row-group text-center border-top">

                        <div class="col">
                            <div class="p-3">

                                <h5 class="mb-0">
                                    ${{ number_format($totalPurchases, 2) }}
                                </h5>

                                <small class="mb-0">Total Purchase Amount</small>

                            </div>
                        </div>

                        <div class="col">
                            <div class="p-3">

                                <h5 class="mb-0">
                                    {{ number_format($totalVehicles) }}
                                </h5>

                                <small class="mb-0">Total Vehicles Purchased</small>

                            </div>
                        </div>

                        <div class="col">
                            <div class="p-3">

                                <h5 class="mb-0">
                                    {{ date('Y') }}
                                </h5>

                                <small class="mb-0">Reporting Year</small>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div> --}}

        <div class="card radius-10">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Recent Orders</h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i
                                class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @php
                use App\Models\Purchase;

                $purchases = Purchase::with('user')->latest()->take(10)->get();
            @endphp

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
                                        {{ $purchase->buying_date ? \Carbon\Carbon::parse($purchase->buying_date)->format('Y/m/d') : 'N/A' }}
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
