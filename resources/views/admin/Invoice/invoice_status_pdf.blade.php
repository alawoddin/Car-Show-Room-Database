<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoiceStatus->id }}</title>
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
        .badge-overdue { background: #dc2626; }
        h4 { margin: 18px 0 8px; font-size: 13px; text-transform: uppercase; }
        table.info, table.summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.info th, table.info td,
        table.summary th, table.summary td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.info th, table.summary th { background: #f5f5f5; width: 30%; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 11px; color: #666; }
    </style>
</head>
<body>
@php
    $amount = $invoiceStatus->invoiceTotal();
    $paid = (float) ($invoiceStatus->paid_amount ?? 0);
    $remaining = $invoiceStatus->remainingAmount();
    $statusClass = match ($invoiceStatus->status) {
        'Paid' => 'badge-paid',
        'Overdue' => 'badge-overdue',
        default => 'badge-open',
    };
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
            <p style="margin:0;"><strong>Invoice ID:</strong> #{{ $invoiceStatus->id }}</p>
            <p style="margin:4px 0;"><strong>Date:</strong> {{ $invoiceStatus->due_date ?? $invoiceStatus->created_at?->format('Y-m-d') }}</p>
            <p style="margin:8px 0 0;">
                <span class="badge {{ $statusClass }}">{{ strtoupper($invoiceStatus->status ?? 'OPEN') }}</span>
            </p>
        </td>
    </tr>
</table>

<h4>Customer</h4>
<table class="info">
    <tr>
        <th>Name</th>
        <td>{{ $invoiceStatus->user->name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $invoiceStatus->user->email ?? '-' }}</td>
    </tr>
</table>

<h4>Vehicle / Invoice Details</h4>
<table class="info">
    <tr>
        <th>Chassis Number</th>
        <td>{{ $invoiceStatus->displayCsNumber() }}</td>
    </tr>
    <tr>
        <th>Lot Number</th>
        <td>{{ $invoiceStatus->displayLotNumber() }}</td>
    </tr>
    <tr>
        <th>Due Date</th>
        <td>{{ $invoiceStatus->due_date ?? '-' }}</td>
    </tr>
</table>

<h4>Payment Summary</h4>
<table class="summary">
    <tr>
        <th>Amount</th>
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

<p class="footer">
    Generated on {{ now()->format('Y-m-d H:i') }} — AL JAZEERA AL HAMRA USED CARS
</p>
</body>
</html>
