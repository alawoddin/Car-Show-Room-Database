<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Vehicle Invoice #{{ $vehicle->id }}</title>

    <style>

        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #ffffff;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #222222;
        }

        * {
            box-sizing: border-box;
        }


        /* =====================================================
           A4 PAGE
        ===================================================== */

        .page {
            width: 210mm;
            height: 297mm;
            padding: 25px;
            margin: 0;
            background: #ffffff;
            overflow: hidden;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {
            width: 100%;
            border-bottom: 3px solid #1f2937;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 62%;
            vertical-align: top;
        }

        .header-right {
            width: 38%;
            vertical-align: top;
            text-align: right;
        }

        .company-name {
            font-size: 19px;
            font-weight: bold;
            color: #111827;
            line-height: 1.3;
        }

        .company-owner {
            margin-top: 5px;
            font-size: 10px;
            font-weight: bold;
            color: #374151;
        }

        .company-subtitle {
            margin-top: 4px;
            font-size: 8px;
            color: #6b7280;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 7px;
        }

        .invoice-meta {
            font-size: 8.5px;
            color: #4b5563;
            line-height: 1.7;
        }

        .invoice-meta strong {
            color: #111827;
        }


        /* =====================================================
           CUSTOMER AREA
        ===================================================== */

        .customer-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .customer-cell {
            width: 50%;
            border: 1px solid #d1d5db;
            padding: 10px;
            vertical-align: top;
        }

        .customer-cell:first-child {
            border-right: none;
        }

        .customer-label {
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .customer-name {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
        }

        .customer-description {
            margin-top: 3px;
            font-size: 8px;
            color: #6b7280;
        }


        /* =====================================================
           SECTION
        ===================================================== */

        .section {
            margin-bottom: 14px;
        }

        .section-heading {
            width: 100%;
            background: #1f2937;
            color: #ffffff;
            padding: 7px 9px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }


        /* =====================================================
           GENERAL TABLE
        ===================================================== */

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #d1d5db;
            padding: 6px 7px;
            font-size: 8px;
            vertical-align: middle;
        }

        .table th {
            background: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }

        .label {
            width: 18%;
            background: #f8f9fa;
            font-weight: bold;
            color: #4b5563;
        }

        .value {
            width: 32%;
            color: #111827;
        }


        /* =====================================================
           VEHICLE STATUS
        ===================================================== */

        .status {
            font-weight: bold;
            color: #111827;
        }


        /* =====================================================
           PURCHASE TABLE
        ===================================================== */

        .purchase-table {
            width: 100%;
            border-collapse: collapse;
        }

        .purchase-table th {
            background: #f3f4f6;
            color: #374151;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #d1d5db;
            padding: 7px;
        }

        .purchase-table td {
            border: 1px solid #d1d5db;
            padding: 7px;
            font-size: 8px;
        }

        .purchase-description {
            width: 43%;
        }

        .purchase-details {
            width: 27%;
        }

        .purchase-amount {
            width: 30%;
            text-align: right;
            white-space: nowrap;
        }

        .total-row td {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 9px;
            padding: 8px;
        }


        /* =====================================================
           ADDITIONAL COSTS
        ===================================================== */

        .additional-table {
            width: 55%;
            margin-left: 45%;
            border-collapse: collapse;
        }

        .additional-table th,
        .additional-table td {
            border: 1px solid #d1d5db;
            padding: 6px;
            font-size: 8px;
        }

        .additional-table th {
            background: #f3f4f6;
            text-align: left;
        }

        .money {
            text-align: right;
            white-space: nowrap;
        }


        /* =====================================================
           GRAND TOTAL
        ===================================================== */

        .grand-total {
            width: 55%;
            margin-left: 45%;
            margin-top: 8px;
            border: 2px solid #111827;
            border-collapse: collapse;
        }

        .grand-total td {
            padding: 10px;
            border: none;
        }

        .grand-total-label {
            width: 55%;
            font-size: 10px;
            font-weight: bold;
            color: #111827;
        }

        .grand-total-amount {
            width: 45%;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            white-space: nowrap;
        }


        /* =====================================================
           NOTES
        ===================================================== */

        .notes {
            border: 1px solid #d1d5db;
            padding: 8px;
            margin-top: 12px;
        }

        .notes-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #374151;
            margin-bottom: 4px;
        }

        .notes-content {
            font-size: 8px;
            color: #6b7280;
            line-height: 1.4;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            border-top: 1px solid #d1d5db;
            margin-top: 16px;
            padding-top: 9px;
            text-align: center;
        }

        .footer-company {
            font-size: 9px;
            font-weight: bold;
            color: #111827;
        }

        .footer-owner {
            font-size: 8px;
            color: #4b5563;
            margin-top: 2px;
        }

        .footer-address {
            font-size: 7.5px;
            color: #6b7280;
            margin-top: 3px;
            line-height: 1.4;
        }

        .footer-thanks {
            margin-top: 5px;
            font-size: 7.5px;
            color: #6b7280;
        }

    </style>

</head>


<body>

<div class="page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td class="header-left"
                    style="border: none;">

                    <div class="company-name">
                        AL JAZEERA AL HAMRA USED CARS
                    </div>

                    <div class="company-owner">
                        Enam Ullah Ahmadi
                    </div>

                    <div class="company-subtitle">
                        Used Cars Purchase & Sales
                    </div>

                </td>


                <td class="header-right"
                    style="border: none;">

                    <div class="invoice-title">
                        VEHICLE INVOICE
                    </div>

                    <div class="invoice-meta">

                        <strong>Invoice #:</strong>
                        {{ $vehicle->id }}

                        <br>

                        <strong>Date:</strong>
                        {{ $vehicle->created_at?->format('Y-m-d') }}

                        <br>

                        <strong>Status:</strong>
                        {{ $vehicle->status ?? '-' }}

                    </div>

                </td>

            </tr>

        </table>

    </div>



    {{-- =====================================================
         CUSTOMER
    ====================================================== --}}

    <table class="customer-table">

        <tr>

            <td class="customer-cell">

                <div class="customer-label">
                    Customer
                </div>

                <div class="customer-name">

                    {{ $vehicle->user->name ?? 'Demo' }}

                </div>

                <div class="customer-description">

                    Vehicle Purchase Customer

                </div>

            </td>


            <td class="customer-cell">

                <div class="customer-label">
                    Vehicle
                </div>

                <div class="customer-name">

                    {{ $vehicle->make ?? '-' }}
                    {{ $vehicle->model ?? '' }}

                </div>

                <div class="customer-description">

                    VIN:
                    {{ $vehicle->vin ?? '-' }}

                </div>

            </td>

        </tr>

    </table>



    {{-- =====================================================
         VEHICLE INFORMATION
    ====================================================== --}}

    <div class="section">

        <div class="section-heading">
            Vehicle Information
        </div>

        <table class="table">

            <tr>

                <td class="label">
                    VIN
                </td>

                <td class="value">
                    {{ $vehicle->vin ?? '-' }}
                </td>

                <td class="label">
                    Make
                </td>

                <td class="value">
                    {{ $vehicle->make ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Model
                </td>

                <td class="value">
                    {{ $vehicle->model ?? '-' }}
                </td>

                <td class="label">
                    Color
                </td>

                <td class="value">
                    {{ $vehicle->color ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Lot Number
                </td>

                <td class="value">
                    {{ $vehicle->lot_number ?? '-' }}
                </td>

                <td class="label">
                    Cylinder
                </td>

                <td class="value">
                    {{ $vehicle->cylinder ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Location
                </td>

                <td class="value">
                    {{ $vehicle->location ?? '-' }}
                </td>

                <td class="label">
                    Status
                </td>

                <td class="value status">
                    {{ $vehicle->status ?? '-' }}
                </td>

            </tr>

        </table>

    </div>



    {{-- =====================================================
         PURCHASE INFORMATION
    ====================================================== --}}

    <div class="section">

        <div class="section-heading">
            Purchase Information
        </div>

        <table class="purchase-table">

            <thead>

                <tr>

                    <th class="purchase-description">
                        Description
                    </th>

                    <th class="purchase-details">
                        Details
                    </th>

                    <th class="purchase-amount">
                        Amount
                    </th>

                </tr>

            </thead>


            <tbody>


                {{-- BUYING FEE --}}

                <tr>

                    <td>
                        Buying Fee
                    </td>

                    <td>
                        Vehicle Purchase
                    </td>

                    <td class="purchase-amount">

                        ${{ number_format($vehicle->buying_fee ?? 0, 2) }}

                    </td>

                </tr>


                {{-- TOWING --}}

                <tr>

                    <td>
                        Towing Fee
                    </td>

                    <td>
                        Vehicle Towing
                    </td>

                    <td class="purchase-amount">

                        ${{ number_format($vehicle->towing_fee ?? 0, 2) }}

                    </td>

                </tr>


                {{-- SHIPPING --}}

                <tr>

                    <td>
                        Shipping
                    </td>

                    <td>

                        {{ $vehicle->shipping ?? 'Shipping' }}

                    </td>

                    <td class="purchase-amount">

                        ${{ number_format($vehicle->shipping ?? 0, 2) }}

                    </td>

                </tr>


                {{-- COMMISSION --}}

                <tr>

                    <td>
                        <strong>
                            Commission
                        </strong>
                    </td>

                    <td>
                        Purchase Commission
                    </td>

                    <td class="purchase-amount">

                        <strong>

                            ${{ number_format($vehicle->commission ?? 0, 2) }}

                        </strong>

                    </td>

                </tr>


                {{-- SHIPPING COMPANY --}}

                <tr>

                    <td>
                        Shipping Company
                    </td>

                    <td colspan="2">

                        {{ $vehicle->shipping_company ?? '-' }}

                    </td>

                </tr>


                {{-- TOTAL AED --}}

                <tr class="total-row">

                    <td colspan="2">

                        TOTAL AED

                    </td>

                    <td class="purchase-amount">

                        AED
                        {{ number_format($vehicle->total_aed ?? 0, 2) }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    {{-- =====================================================
         ADDITIONAL COSTS
    ====================================================== --}}

    <div class="section">

        <div class="section-heading">
            Additional Costs
        </div>


       

        {{-- GRAND TOTAL --}}

        <table class="grand-total">

            <tr>

                <td class="grand-total-label">

                    GRAND TOTAL

                </td>

                <td class="grand-total-amount">

                    AED
                    {{ number_format($vehicle->grand_total ?? 0, 2) }}

                </td>

            </tr>

        </table>

    </div>



    {{-- =====================================================
         NOTES
    ====================================================== --}}

    @if(!empty($vehicle->description))

        <div class="notes">

            <div class="notes-title">
                Notes
            </div>

            <div class="notes-content">

                {{ $vehicle->description }}

            </div>

        </div>

    @endif



    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        <div class="footer-company">
            AL JAZEERA AL HAMRA USED CARS
        </div>

        <div class="footer-owner">
            Enam Ullah Ahmadi
        </div>

        <div class="footer-address">

            Al Jubail Street 72,
            Industrial Area-2,
            Sharjah,
            United Arab Emirates

        </div>

        <div class="footer-thanks">

            Thank you for your business.

        </div>

    </div>


</div>

</body>

</html>