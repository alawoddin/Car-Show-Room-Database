@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                    <h4 class="mb-sm-0">Three-Month Financial Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Reports</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Three-Month Report
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>


        <!-- Report Period -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Report Period</h4>

                        <p class="mb-0">
                            From:
                            <strong>{{ $startDate->format('d M Y') }}</strong>

                            To:
                            <strong>
                                {{ $endDate->copy()->subDay()->format('d M Y') }}
                            </strong>
                        </p>

                    </div>
                </div>
            </div>
        </div>


        <!-- Daily Expenses -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">Expenses Information</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Expense Name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($expenses as $key => $expense)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $expense->expense_name }}</td>
                                            <td>{{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->date }}</td>
                                            <td>{{ $expense->description ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No expenses found for this period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total Expenses</th>
                                        <th colspan="3">
                                            {{ number_format($expenses->sum('amount'), 2) }}
                                        </th>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Purchase Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">Purchase Information</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>VIN</th>
                                        <th>Lot Number</th>
                                        <th>Cylinder</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Buying Date</th>
                                        <th>Grand Total</th>
                                        <th>Profit</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($purchases as $key => $purchase)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $purchase->vin }}</td>
                                            <td>{{ $purchase->lot_number }}</td>
                                            <td>{{ $purchase->cylinder }}</td>
                                            <td>{{ $purchase->make }}</td>
                                            <td>{{ $purchase->model }}</td>
                                            <td>{{ $purchase->buying_date }}</td>

                                            <td>
                                                {{ number_format($purchase->grand_total ?? 0, 2) }}
                                            </td>

                                            <td>
                                                {{ number_format($purchase->profit ?? 0, 2) }}
                                            </td>

                                            <td>
                                                {{ $purchase->status ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                No purchases found for this period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-end">
                                            Total:
                                        </th>

                                        <th>
                                            {{ number_format($purchases->sum('grand_total'), 2) }}
                                        </th>

                                        <th>
                                            {{ number_format($purchases->sum('profit'), 2) }}
                                        </th>

                                        <th></th>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Sale Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">Sale Information</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>VIN</th>
                                        <th>Lot Number</th>
                                        <th>Cylinder</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Buying Date</th>
                                        <th>Grand Total</th>
                                        <th>Profit</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($sales as $key => $sale)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $sale->vin }}</td>
                                            <td>{{ $sale->lot_number }}</td>
                                            <td>{{ $sale->cylinder }}</td>
                                            <td>{{ $sale->make }}</td>
                                            <td>{{ $sale->model }}</td>
                                            <td>{{ $sale->buying_date }}</td>

                                            <td>
                                                {{ number_format($sale->grand_total ?? 0, 2) }}
                                            </td>

                                            <td>
                                                {{ number_format($sale->profit ?? 0, 2) }}
                                            </td>

                                            <td>
                                                {{ $sale->status ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                No sold vehicles found for this period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-end">
                                            Total:
                                        </th>

                                        <th>
                                            {{ number_format($sales->sum('grand_total'), 2) }}
                                        </th>

                                        <th>
                                            {{ number_format($sales->sum('profit'), 2) }}
                                        </th>

                                        <th></th>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- User Capital -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">User Capital Information</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($userCapitals as $key => $capital)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $capital->user->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($capital->amount, 2) }}</td>
                                            <td>{{ $capital->date }}</td>
                                            <td>{{ $capital->description ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No user capital found for this period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total User Capital</th>
                                        <th colspan="3">
                                            {{ number_format($userCapitals->sum('amount'), 2) }}
                                        </th>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection