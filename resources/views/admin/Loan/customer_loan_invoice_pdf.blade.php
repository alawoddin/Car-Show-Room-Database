<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Invoice #{{ $loan->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { width: 100%; margin-bottom: 20px; }
        .header td { vertical-align: top; }
        .company { font-size: 18px; font-weight: bold; margin: 0 0 6px; }
        .muted { color: #666; }
        .title { font-size: 22px; font-weight: bold; text-align: right; margin: 0 0 8px; }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: #fff;
        }
        .badge-paid { background: #16a34a; }
        .badge-open { background: #ca8a04; color: #111; }
        h4 { margin: 18px 0 8px; font-size: 13px; text-transform: uppercase; }
        table.info, table.summary, table.payments {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.info th, table.info td,
        table.summary th, table.summary td,
        table.payments th, table.payments td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.info th, table.summary th { background: #f5f5f5; width: 30%; }
        table.payments th { background: #f5f5f5; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 11px; color: #666; }
    </style>
</head>
<body>
@php
    $purchase = $loan->purchase;
    $amount = $loan->loanTotal();
    $paid = $loan->totalPaid();
    $remaining = $loan->remainingAmount();
    $statusClass = $loan->status === 'Paid' ? 'badge-paid' : 'badge-open';
@endphp

<table class="header">
    <tr>
        <td width="60%">
            <p class="company">AL JAZEERA AL HAMRA USED CARS</p>
            <p class="muted" style="margin:0;">Enam Ullah Ahmadi</p>
            <p class="muted" style="margin:4px 0 0;">
                Al Jubail Street 72, Industrial Area-2,<br>
                Sharjah, United Arab Emirates
            </p>
        </td>
        <td width="40%" class="text-right">
            <p class="title">INVOICE</p>
            <p style="margin:0;"><strong>Loan ID:</strong> #{{ $loan->id }}</p>
            <p style="margin:4px 0;"><strong>Date:</strong> {{ $loan->due_date ?? $loan->created_at?->format('Y-m-d') }}</p>
            <p style="margin:8px 0 0;">
                <span class="badge {{ $statusClass }}">{{ strtoupper($loan->status ?? 'OPEN') }}</span>
            </p>
        </td>
    </tr>
</table>

<h4>Customer</h4>
<table class="info">
    <tr>
        <th>Name</th>
        <td>{{ $loan->customerLabel() }}</td>
    </tr>
    <tr>
        <th>Owner</th>
        <td>
            {{ $loan->user->name ?? '-' }}
            @if (($loan->user->role ?? '') === 'admin')
                (Admin)
            @elseif ($loan->user)
                (Client)
            @endif
        </td>
    </tr>
</table>

<h4>Vehicle</h4>
<table class="info">
    <tr>
        <th>Vehicle</th>
        <td>{{ $purchase->make ?? '-' }} {{ $purchase->model ?? '' }}</td>
    </tr>
    <tr>
        <th>VIN</th>
        <td>{{ $purchase->vin ?? '-' }}</td>
    </tr>
    <tr>
        <th>Lot Number</th>
        <td>{{ $purchase->lot_number ?? '-' }}</td>
    </tr>
</table>

<h4>Payment Summary</h4>
<table class="summary">
    <tr>
        <th>Sale Price</th>
        <td class="text-right"><strong>AED {{ number_format($amount, 2) }}</strong></td>
    </tr>
    <tr>
        <th>Paid Amount</th>
        <td class="text-right">AED {{ number_format($paid, 2) }}</td>
    </tr>
    <tr>
        <th>Remaining Amount</th>
        <td class="text-right"><strong>AED {{ number_format($remaining, 2) }}</strong></td>
    </tr>
</table>

<h4>Payment History</h4>
<table class="payments">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($loan->payments as $key => $payment)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $payment->payment_date ?? '-' }}</td>
                <td>AED {{ number_format((float) $payment->amount, 2) }}</td>
                <td>{{ $payment->note ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center;">No payments yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<p class="footer">
    Generated on {{ now()->format('Y-m-d H:i') }} — AL JAZEERA AL HAMRA USED CARS
</p>
</body>
</html>
