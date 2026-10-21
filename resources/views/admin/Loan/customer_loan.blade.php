@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Loan</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('customer.loan.add') }}" class="btn btn-primary px-5">Add Loan</a>
                </div>
            </div>
        </div>

        @php
            $totalLoans = $loans->count();
            $paidLoans = $loans->where('status', 'Paid')->count();
            $openLoans = $loans->where('status', 'Open')->count();
            $openAmount = $loans->where('status', 'Open')->sum(fn ($item) => $item->remainingAmount());
            $totalSale = $loans->sum(fn ($item) => $item->loanTotal());
            $totalPaid = $loans->sum(fn ($item) => $item->totalPaid());
        @endphp

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Total Loans</p>
                        <h4 class="my-1">{{ $totalLoans }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Open Remaining</p>
                        <h4 class="my-1 text-danger">AED {{ number_format($openAmount, 2) }}</h4>
                        <p class="mb-0 font-13">{{ $openLoans }} open / {{ $paidLoans }} paid</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Total Sale</p>
                        <h4 class="my-1">AED {{ number_format($totalSale, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Total Collected</p>
                        <h4 class="my-1 text-success">AED {{ number_format($totalPaid, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>VIN</th>
                                <th>Sale Price</th>
                                <th>Paid</th>
                                <th>Payments</th>
                                <th>Remaining</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loans as $key => $item)
                                @php
                                    $loanTotal = $item->loanTotal();
                                    $paidAmount = $item->totalPaid();
                                    $remaining = $item->remainingAmount();
                                    $paymentCount = $item->payments->count();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->customerLabel() }}</td>
                                    <td>
                                        @if ($item->purchase)
                                            {{ $item->purchase->make ?? '-' }} {{ $item->purchase->model ?? '' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->purchase->vin ?? '-' }}</td>
                                    <td>AED {{ number_format($loanTotal, 2) }}</td>
                                    <td class="text-success">AED {{ number_format($paidAmount, 2) }}</td>
                                    <td>{{ $paymentCount }}</td>
                                    <td class="text-danger">AED {{ number_format($remaining, 2) }}</td>
                                    <td>{{ $item->due_date ?? '-' }}</td>
                                    <td>
                                        @if ($item->status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif ($item->status == 'Open')
                                            <span class="badge bg-warning text-dark">Open</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="{{ route('customer.loan.view', $item->id) }}" class="text-primary bg-light-primary border-0">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('customer.loan.delete', $item->id) }}" class="ms-3 text-danger bg-light-danger border-0" id="delete">
                                                <i class="bx bxs-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No customer loans yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
