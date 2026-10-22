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
                        <li class="breadcrumb-item active" aria-current="page">Invoice Status</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('invoice.status.add') }}" class="btn btn-primary px-5">
                        Add Invoice Status
                    </a>
                </div>
            </div>
        </div>

        @php
            $totalInvoices = $invoiceStatuses->count();
            $paidInvoices = $invoiceStatuses->where('status', 'Paid')->count();
            $openInvoices = $invoiceStatuses->where('status', 'Open')->count();
            $overdueInvoices = $invoiceStatuses->where('status', 'Overdue')->count();

            $openAmount = $invoiceStatuses->where('status', 'Open')->sum(fn ($item) => $item->remainingAmount());
            $overdueAmount = $invoiceStatuses->where('status', 'Overdue')->sum(fn ($item) => $item->remainingAmount());

            $paidPct = $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100, 2) : 0;
            $overduePct = $totalInvoices > 0 ? round(($overdueInvoices / $totalInvoices) * 100, 2) : 0;
            $donutBg = $totalInvoices > 0
                ? "conic-gradient(#22c55e 0 {$paidPct}%, #f59e0b {$paidPct}% ".($paidPct + $overduePct)."%, #ef4444 ".($paidPct + $overduePct)."% 100%)"
                : 'conic-gradient(#22c55e 0 100%)';
        @endphp

        <style>
            .dubai-invoice-card {
                background: #fff;
                border-radius: 18px;
                border: 1px solid #eef0f3;
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
                padding: 1.25rem 1.5rem 1.5rem;
                margin-bottom: 1.25rem;
            }
            .dubai-invoice-head {
                display: flex;
                align-items: center;
                gap: .65rem;
                margin-bottom: 1.15rem;
            }
            .dubai-invoice-head .icon {
                width: 34px;
                height: 34px;
                border-radius: 9px;
                background: #e11d48;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.05rem;
            }
            .dubai-invoice-head h5 {
                margin: 0;
                font-weight: 700;
                color: #111827;
            }
            .dubai-invoice-body {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 1.5rem 2rem;
            }
            .dubai-donut {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                flex-shrink: 0;
                position: relative;
            }
            .dubai-donut::before {
                content: '';
                position: absolute;
                inset: 14px;
                background: #fff;
                border-radius: 50%;
            }
            .dubai-donut-center {
                position: relative;
                z-index: 1;
                text-align: center;
            }
            .dubai-donut-center .num {
                display: block;
                font-size: 2rem;
                font-weight: 800;
                line-height: 1;
                color: #111827;
            }
            .dubai-donut-center .label {
                display: block;
                margin-top: .35rem;
                font-size: .8rem;
                color: #6b7280;
            }
            .dubai-status-list {
                flex: 1;
                min-width: 220px;
                display: flex;
                flex-direction: column;
                gap: .7rem;
            }
            .dubai-status-row {
                display: flex;
                align-items: center;
                gap: .75rem;
                border-radius: 12px;
                padding: .7rem .9rem;
            }
            .dubai-status-row.paid { background: #ecfdf5; }
            .dubai-status-row.overdue { background: #fff7ed; }
            .dubai-status-row.open { background: #fef2f2; }
            .dubai-status-bar {
                width: 8px;
                height: 34px;
                border-radius: 999px;
                flex-shrink: 0;
            }
            .dubai-status-row.paid .dubai-status-bar { background: #16a34a; }
            .dubai-status-row.overdue .dubai-status-bar { background: #ea580c; }
            .dubai-status-row.open .dubai-status-bar { background: #dc2626; }
            .dubai-status-meta { flex: 1; min-width: 0; }
            .dubai-status-meta .name {
                margin: 0;
                font-weight: 600;
                color: #111827;
                font-size: .95rem;
            }
            .dubai-status-meta .amount {
                margin: .1rem 0 0;
                font-size: .8rem;
                color: #9ca3af;
            }
            .dubai-status-count {
                min-width: 36px;
                height: 36px;
                border-radius: 10px;
                background: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 1rem;
                box-shadow: 0 1px 2px rgba(0,0,0,.04);
            }
            .dubai-status-row.paid .dubai-status-count { color: #16a34a; }
            .dubai-status-row.overdue .dubai-status-count { color: #ea580c; }
            .dubai-status-row.open .dubai-status-count { color: #dc2626; }
        </style>

        <div class="dubai-invoice-card">
            <div class="dubai-invoice-head">
                <span class="icon"><i class="bx bx-receipt"></i></span>
                <h5>Invoice Status</h5>
            </div>

            <div class="dubai-invoice-body">
                <div class="dubai-donut" style="background: {{ $donutBg }};">
                    <div class="dubai-donut-center">
                        <span class="num">{{ $totalInvoices }}</span>
                        <span class="label">Total Invoices</span>
                    </div>
                </div>

                <div class="dubai-status-list">
                    <div class="dubai-status-row paid">
                        <span class="dubai-status-bar"></span>
                        <div class="dubai-status-meta">
                            <p class="name">Paid</p>
                        </div>
                        <span class="dubai-status-count">{{ $paidInvoices }}</span>
                    </div>

                    <div class="dubai-status-row overdue">
                        <span class="dubai-status-bar"></span>
                        <div class="dubai-status-meta">
                            <p class="name">Overdue</p>
                            <p class="amount">AED {{ number_format($overdueAmount, 2) }}</p>
                        </div>
                        <span class="dubai-status-count">{{ $overdueInvoices }}</span>
                    </div>

                    <div class="dubai-status-row open">
                        <span class="dubai-status-bar"></span>
                        <div class="dubai-status-meta">
                            <p class="name">Open</p>
                            <p class="amount">AED {{ number_format($openAmount, 2) }}</p>
                        </div>
                        <span class="dubai-status-count">{{ $openInvoices }}</span>
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
                                <th>User Name</th>
                                <th>Chassis Number</th>
                                <th>Lot Number</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoiceStatuses as $key => $item)
                                @php
                                    $grandTotal = $item->invoiceTotal();
                                    $paidAmount = (float) ($item->paid_amount ?? 0);
                                    $remaining = $item->remainingAmount();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $item->user->name ?? '-' }}
                                        @if (($item->user->role ?? '') === 'admin')
                                            <small class="text-muted">(Admin)</small>
                                        @elseif ($item->user)
                                            <small class="text-muted">(Client)</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->displayCsNumber() }}</td>
                                    <td>{{ $item->displayLotNumber() }}</td>
                                    <td>AED {{ number_format($grandTotal, 2) }}</td>
                                    <td class="text-success">AED {{ number_format($paidAmount, 2) }}</td>
                                    <td class="text-danger">AED {{ number_format($remaining, 2) }}</td>
                                    <td>{{ $item->due_date ?? '-' }}</td>
                                    <td>
                                        @if ($item->status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif ($item->status == 'Open')
                                            <span class="badge bg-warning text-dark">Open</span>
                                        @elseif ($item->status == 'Overdue')
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status !== 'Paid')
                                            <a href="{{ route('invoice.status.paid', $item->id) }}"
                                                class="btn btn-success btn-sm"
                                                onclick="return confirm('Mark this invoice as Paid?');">
                                                <i class="bx bx-check"></i> Paid
                                            </a>
                                        @endif
                                        <a href="{{ route('invoice.status.view', $item->id) }}" class="btn btn-info btn-sm">
                                            <i class="bx bx-show"></i> View
                                        </a>
                                        <a href="{{ route('invoice.status.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('invoice.status.delete', $item->id) }}" class="btn btn-danger btn-sm" id="delete">
                                            <i class="bx bx-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No Invoice Status Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
