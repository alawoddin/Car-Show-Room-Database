<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Vehicle Invoice #{{ $vehicle->id }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #222;
        }

        .invoice {
            max-width: 900px;
            margin: auto;
            background: #ffffff;
            padding: 45px;
        }

        /* ================================
           HEADER
        ================================= */

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #222;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

        .company-name {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .company-owner {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .company-info {
            color: #777;
            line-height: 1.6;
            font-size: 13px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0 0 10px;
            font-size: 25px;
        }

        .invoice-title p {
            margin: 4px 0;
            font-size: 13px;
        }


        /* ================================
           SECTION
        ================================= */

        .section-title {
            font-size: 16px;
            font-weight: bold;
            padding-bottom: 8px;
            margin-top: 28px;
            margin-bottom: 12px;
            border-bottom: 1px solid #ddd;
        }


        /* ================================
           TABLE
        ================================= */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 11px;
            text-align: left;
            font-size: 13px;
        }

        th {
            font-weight: bold;
        }

        .vehicle-table th {
            width: 20%;
        }

        .amount {
            text-align: right;
        }


        /* ================================
           GRAND TOTAL
        ================================= */

        .grand-total {
            font-size: 18px;
            font-weight: bold;
        }

        .grand-total th,
        .grand-total td {
            padding: 15px;
        }


        /* ================================
           FOOTER
        ================================= */

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #777;
            font-size: 13px;
            line-height: 1.7;
        }

        .footer-company {
            font-weight: bold;
            color: #222;
            font-size: 14px;
        }


        /* ================================
           PRINT
        ================================= */

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .invoice {
                max-width: 100%;
                padding: 20px;
            }

        }

    </style>

</head>


<body>

<div class="invoice">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="invoice-header">

        <div>

            <div class="company-name">
                AL JAZEERA AL HAMRA USED CARS
            </div>

            <div class="company-owner">
                Enam Ullah Ahmadi
            </div>

            <div class="company-info">
                Used Cars Purchase & Sales
            </div>

        </div>


        <div class="invoice-title">

            <h1>
                VEHICLE INVOICE
            </h1>

            <p>

                <strong>
                    Invoice #:
                </strong>

                {{ $vehicle->id }}

            </p>

            <p>

                <strong>
                    Date:
                </strong>

                {{ $vehicle->created_at?->format('Y-m-d') }}

            </p>

        </div>

    </div>



    {{-- =====================================================
         CUSTOMER INFORMATION
    ====================================================== --}}

    <div class="section-title">

        CUSTOMER INFORMATION

    </div>


    <table>

        <tr>

            <th>
                Customer
            </th>

            <td>
                {{ $vehicle->user->name ?? 'Demo' }}
            </td>

        </tr>

    </table>



    {{-- =====================================================
         VEHICLE INFORMATION
    ====================================================== --}}

    <div class="section-title">

        VEHICLE INFORMATION

    </div>


    <table class="vehicle-table">

        <tr>

            <th>
                VIN
            </th>

            <td>
                {{ $vehicle->vin ?? '-' }}
            </td>

            <th>
                Make
            </th>

            <td>
                {{ $vehicle->make ?? '-' }}
            </td>

        </tr>


        <tr>

            <th>
                Model
            </th>

            <td>
                {{ $vehicle->model ?? '-' }}
            </td>

            <th>
                Color
            </th>

            <td>
                {{ $vehicle->color ?? '-' }}
            </td>

        </tr>


        <tr>

            <th>
                Lot Number
            </th>

            <td>
                {{ $vehicle->lot_number ?? '-' }}
            </td>

            <th>
                Cylinder
            </th>

            <td>
                {{ $vehicle->cylinder ?? '-' }}
            </td>

        </tr>


        <tr>

            <th>
                Location
            </th>

            <td>
                {{ $vehicle->location ?? '-' }}
            </td>

            <th>
                Status
            </th>

            <td>
                {{ $vehicle->status ?? '-' }}
            </td>

        </tr>

    </table>



    {{-- =====================================================
         PURCHASE INFORMATION
    ====================================================== --}}

    <div class="section-title">

        PURCHASE INFORMATION

    </div>


    <table>

        <tr>

            <th>
                Buying Date
            </th>

            <td>
                {{ $vehicle->buying_date ?? '-' }}
            </td>

            <th>
                Buying Fee
            </th>

            <td class="amount">
                ${{ number_format($vehicle->buying_fee ?? 0, 2) }}
            </td>

        </tr>


        <tr>

            <th>
                Towing Fee
            </th>

            <td class="amount">
                ${{ number_format($vehicle->towing_fee ?? 0, 2) }}
            </td>

            <th>
                Shipping
            </th>

            <td class="amount">
                ${{ number_format($vehicle->shipping ?? 0, 2) }}
            </td>

        </tr>


        {{-- COMMISSION --}}

        <tr>

            <th>
                Commission
            </th>

            <td class="amount">

                ${{ number_format($vehicle->commission ?? 0, 2) }}

            </td>


            <th>
                Shipping Company
            </th>

            <td>

                {{ $vehicle->shipping_company ?? '-' }}

            </td>

        </tr>


        {{-- TOTAL AED --}}

        <tr>

            <th>
                Total AED
            </th>

            <td colspan="3" class="amount">

                <strong>

                    AED
                    {{ number_format($vehicle->total_aed ?? 0, 2) }}

                </strong>

            </td>

        </tr>

    </table>



    {{-- =====================================================
         ADDITIONAL COSTS
    ====================================================== --}}

    <div class="section-title">

        ADDITIONAL COSTS

    </div>


    <table>

        <tr>

            <th>
                Clearing
            </th>

            <td class="amount">

                AED
                {{ number_format($vehicle->clearing ?? 0, 2) }}

            </td>

        </tr>


        <tr>

            <th>
                Extra Charges
            </th>

            <td class="amount">

                AED
                {{ number_format($vehicle->extra_charges ?? 0, 2) }}

            </td>

        </tr>


        <tr>

            <th>
                Custom Duty
            </th>

            <td class="amount">

                AED
                {{ number_format($vehicle->custom_duty ?? 0, 2) }}

            </td>

        </tr>


        {{-- GRAND TOTAL --}}

        <tr class="grand-total">

            <th>
                GRAND TOTAL
            </th>

            <td class="amount">

                AED
                {{ number_format($vehicle->grand_total ?? 0, 2) }}

            </td>

        </tr>

    </table>



    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        <div class="footer-company">

            AL JAZEERA AL HAMRA USED CARS

        </div>

        <div>

            Enam Ullah Ahmadi

        </div>

        <div>

            Al Jubail Street 72,
            Industrial Area-2,
            Sharjah,
            United Arab Emirates

        </div>

        <br>

        Thank you for your business.

    </div>


</div>

</body>

</html>