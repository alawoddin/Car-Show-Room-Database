@extends('admin.admin_dashboard')

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">

    {{-- Breadcrumb --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        {{ isset($purchase) && $purchase->exists
                            ? 'Sale Vehicle'
                            : 'Add Purchase' }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">

            <h5 class="mb-4">
                {{ isset($purchase) && $purchase->exists
                    ? 'Sale Vehicle'
                    : 'Add Purchase' }}
            </h5>

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please correct the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                id="myForm"
                action="{{ isset($purchase) && $purchase->exists
                    ? route('store.sale', $purchase->id)
                    : route('store.purchase') }}"
                method="POST">

                @csrf

                {{-- =====================================================
                    USER INFORMATION
                ====================================================== --}}

                <h6 class="mt-2 mb-3">User Information</h6>

                <div class="row">

                    <div class="form-group col-md-6 mb-3">
                        <label for="user_id" class="form-label">
                            User Name
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
                            class="form-select"
                            {{ isset($purchase) && $purchase->exists ? 'disabled' : '' }}>

                            <option value="">Select User</option>

                            @foreach ($users as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ (string) old('user_id', $purchase->user_id ?? '') === (string) $user->id
                                        ? 'selected'
                                        : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>

                        @if (isset($purchase) && $purchase->exists)
                            <input
                                type="hidden"
                                name="user_id"
                                value="{{ $purchase->user_id }}">
                        @endif
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="buying_date" class="form-label">
                            Buying Date
                        </label>

                        <input
                            type="date"
                            name="buying_date"
                            id="buying_date"
                            class="form-control"
                            value="{{ old('buying_date', isset($purchase->buying_date) ? \Carbon\Carbon::parse($purchase->buying_date)->format('Y-m-d') : '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                </div>

                {{-- =====================================================
                    VEHICLE INFORMATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">Vehicle Information</h6>

                <div class="row">

                    <div class="form-group col-md-6 mb-3">
                        <label for="lot_number" class="form-label">
                            Lot Number
                        </label>

                        <input
                            type="text"
                            name="lot_number"
                            id="lot_number"
                            class="form-control"
                            value="{{ old('lot_number', $purchase->lot_number ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="vin" class="form-label">VIN</label>

                        <input
                            type="text"
                            name="vin"
                            id="vin"
                            class="form-control"
                            value="{{ old('vin', $purchase->vin ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="vin_link" class="form-label">
                            VIN Link (Optional)
                        </label>

                        <input
                            type="url"
                            name="vin_link"
                            id="vin_link"
                            class="form-control"
                            value="{{ old('vin_link', $purchase->vin_link ?? '') }}"
                            placeholder="https://example.com/vehicle">

                        <small class="text-muted">
                            Enter the external vehicle or auction listing URL.
                        </small>
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-3 mb-3">
                        <label for="cylinder" class="form-label">
                            Cylinder
                        </label>

                        <input
                            type="text"
                            name="cylinder"
                            id="cylinder"
                            class="form-control"
                            value="{{ old('cylinder', $purchase->cylinder ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="color" class="form-label">Color</label>

                        <input
                            type="text"
                            name="color"
                            id="color"
                            class="form-control"
                            value="{{ old('color', $purchase->color ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="make" class="form-label">Make</label>

                        <input
                            type="text"
                            name="make"
                            id="make"
                            class="form-control"
                            value="{{ old('make', $purchase->make ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="model" class="form-label">Model</label>

                        <input
                            type="text"
                            name="model"
                            id="model"
                            class="form-control"
                            value="{{ old('model', $purchase->model ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                </div>

                {{-- =====================================================
                    PURCHASE COST
                ====================================================== --}}

                <h6 class="mt-4 mb-3">Purchase Cost</h6>

                <div class="row">

                    <div class="form-group col-md-3 mb-3">
                        <label for="buying_fee" class="form-label">
                            Buying Fee (USD)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="buying_fee"
                            id="buying_fee"
                            class="form-control"
                            value="{{ old('buying_fee', $purchase->buying_fee ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="towing_fee" class="form-label">
                            Towing Fee (USD)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="towing_fee"
                            id="towing_fee"
                            class="form-control"
                            value="{{ old('towing_fee', $purchase->towing_fee ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="shipping" class="form-label">
                            Shipping Fee (USD)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="shipping"
                            id="shipping"
                            class="form-control"
                            value="{{ old('shipping', $purchase->shipping ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="commission" class="form-label">
                            Commission (USD)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="commission"
                            id="commission"
                            class="form-control"
                            value="{{ old('commission', $purchase->commission ?? '0') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                </div>

                {{-- =====================================================
                    UAE COSTS
                ====================================================== --}}

                <h6 class="mt-4 mb-3">UAE Costs</h6>

                <div class="row">

                    <div class="form-group col-md-3 mb-3">
                        <label for="total_aed" class="form-label">
                            Total AED
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="total_aed"
                            id="total_aed"
                            class="form-control"
                            value="{{ old('total_aed', $purchase->total_aed ?? '') }}"
                            readonly>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="clearing" class="form-label">
                            Clearing
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="clearing"
                            id="clearing"
                            class="form-control"
                            value="{{ old('clearing', $purchase->clearing ?? '0') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="extra_charges" class="form-label">
                            Extra Charges
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="extra_charges"
                            id="extra_charges"
                            class="form-control"
                            value="{{ old('extra_charges', $purchase->extra_charges ?? '0') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="custom_duty" class="form-label">
                            Custom Duty
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="custom_duty"
                            id="custom_duty"
                            class="form-control"
                            value="{{ old('custom_duty', $purchase->custom_duty ?? '') }}"
                            readonly>
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-6 mb-3">
                        <label for="grand_total" class="form-label">
                            Grand Total (AED)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="grand_total"
                            id="grand_total"
                            class="form-control"
                            value="{{ old('grand_total', $purchase->grand_total ?? '') }}"
                            readonly>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="shipping_company" class="form-label">
                            Shipping Company
                        </label>

                        <input
                            type="text"
                            name="shipping_company"
                            id="shipping_company"
                            class="form-control"
                            value="{{ old('shipping_company', $purchase->shipping_company ?? '') }}"
                            placeholder="Enter shipping company">
                    </div>

                </div>

                {{-- =====================================================
                    PURCHASE INFORMATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">Purchase Information</h6>

                <div class="row">

                    <div class="form-group col-md-6 mb-3">
                        <label for="location" class="form-label">
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            id="location"
                            class="form-control"
                            value="{{ old('location', $purchase->location ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="container_number" class="form-label">
                            Container Number
                        </label>

                        <input
                            type="text"
                            name="container_number"
                            id="container_number"
                            class="form-control"
                            value="{{ old('container_number', $purchase->container_number ?? '') }}"
                            placeholder="Enter container number">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="arrival_date" class="form-label">
                            Container Arrival Date
                        </label>

                        <input
                            type="date"
                            name="arrival_date"
                            id="arrival_date"
                            class="form-control"
                            value="{{ old('arrival_date', isset($purchase->arrival_date) ? \Carbon\Carbon::parse($purchase->arrival_date)->format('Y-m-d') : '') }}">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="purchase_status" class="form-label">
                            Status
                        </label>

                        @if (!isset($purchase) || !$purchase->exists)

                            <select
                                name="status"
                                id="purchase_status"
                                class="form-select">

                                @foreach ([
                                    'Purchased',
                                    'Loaded',
                                    'Shipped',
                                    'Delivered',
                                    'On Hand',
                                    'At UAE'
                                ] as $status)
                                    <option
                                        value="{{ $status }}"
                                        {{ old('status', 'Purchased') === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach

                            </select>

                        @else

                            <input
                                type="text"
                                class="form-control"
                                value="Sold"
                                readonly>

                            <input
                                type="hidden"
                                name="status"
                                value="Sold">

                        @endif
                    </div>

                </div>

                {{-- =====================================================
                    SALE INFORMATION
                ====================================================== --}}

                @if (isset($purchase) && $purchase->exists)

                    <hr class="my-4">

                    <h5 class="mb-4">Sale Information</h5>

                    <div class="row">

                        <div class="form-group col-md-6 mb-3">
                            <label for="selling_price" class="form-label">
                                Selling Price (AED)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="selling_price"
                                id="selling_price"
                                class="form-control"
                                value="{{ old('selling_price', $purchase->selling_price ?? '') }}"
                                placeholder="Enter selling price">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="profit" class="form-label">
                                Profit (AED)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="profit"
                                id="profit"
                                class="form-control"
                                value="{{ old('profit', $purchase->profit ?? '') }}"
                                readonly>
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            <label for="bill_no" class="form-label">
                                Bill Number
                            </label>

                            <input
                                type="text"
                                name="bill_no"
                                id="bill_no"
                                class="form-control"
                                value="{{ old('bill_no', $purchase->bill_no ?? '') }}"
                                placeholder="Enter bill number">
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            <label for="date_of_arriving" class="form-label">
                                Date of Arriving
                            </label>

                            <input
                                type="date"
                                name="date_of_arriving"
                                id="date_of_arriving"
                                class="form-control"
                                value="{{ old('date_of_arriving', isset($purchase->date_of_arriving) ? \Carbon\Carbon::parse($purchase->date_of_arriving)->format('Y-m-d') : '') }}">
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            <label for="customer_name" class="form-label">
                                Customer Name
                            </label>

                            <input
                                type="text"
                                name="customer_name"
                                id="customer_name"
                                class="form-control"
                                value="{{ old('customer_name', $purchase->customer_name ?? '') }}"
                                placeholder="Enter customer name">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="sale_status" class="form-label">
                                Sale Status
                            </label>

                            <select
                                name="status"
                                id="sale_status"
                                class="form-select">

                                @foreach ([
                                    'Purchased',
                                    'Loaded',
                                    'Shipped',
                                    'Delivered',
                                    'On Hand',
                                    'At UAE',
                                    'Sold'
                                ] as $status)
                                    <option
                                        value="{{ $status }}"
                                        {{ old('status', $purchase->status ?? '') === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="3"
                                placeholder="Sale description">{{ old('description', $purchase->description ?? '') }}</textarea>
                        </div>

                    </div>

                @else

                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="3"
                                placeholder="Purchase description">{{ old('description') }}</textarea>
                        </div>
                    </div>

                @endif

                {{-- =====================================================
                    SUBMIT
                ====================================================== --}}

                <div class="col-md-12 mt-4">
                    <div class="d-md-flex d-grid align-items-center gap-3">

                        <button type="submit" class="btn btn-primary px-4">
                            {{ isset($purchase) && $purchase->exists
                                ? 'Save Sale'
                                : 'Save Purchase' }}
                        </button>

                        <a href="{{ route('all.purchases') }}"
                           class="btn btn-light px-4">
                            Back
                        </a>

                    </div>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- =====================================================
    JAVASCRIPT CALCULATIONS
====================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const buyingFee = document.getElementById('buying_fee');
    const towingFee = document.getElementById('towing_fee');
    const shipping = document.getElementById('shipping');
    const commission = document.getElementById('commission');

    const totalAed = document.getElementById('total_aed');
    const clearing = document.getElementById('clearing');
    const extraCharges = document.getElementById('extra_charges');
    const customDuty = document.getElementById('custom_duty');
    const grandTotal = document.getElementById('grand_total');

    const sellingPrice = document.getElementById('selling_price');
    const profit = document.getElementById('profit');

    // USD to AED exchange rate.
    const exchangeRate = 3.675;

    function number(value) {
        const parsed = parseFloat(value);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function calculatePurchase() {

        if (!buyingFee || !towingFee || !shipping) {
            return;
        }

        // Calculate total purchase cost in USD.
        const buying = number(buyingFee.value);
        const towing = number(towingFee.value);
        const ship = number(shipping.value);
        const commissionValue = number(commission?.value);

        const totalUsd = buying + towing + ship + commissionValue;

        // Convert USD to AED.
        const aed = totalUsd * exchangeRate;

        if (totalAed) {
            totalAed.value = aed.toFixed(2);
        }

        // Customs duty formula: confirm this matches your business rules.
        const duty = (aed + 1472) * 0.055;

        if (customDuty) {
            customDuty.value = duty.toFixed(2);
        }

        const clearingValue = number(clearing?.value);
        const extraChargesValue = number(extraCharges?.value);

        const grand = aed + clearingValue + extraChargesValue + duty;

        if (grandTotal) {
            grandTotal.value = grand.toFixed(2);
        }

        // Calculate profit when the sale price is available.
        if (sellingPrice && profit) {
            const selling = number(sellingPrice.value);
            profit.value = (selling - grand).toFixed(2);
        }
    }

    [
        buyingFee,
        towingFee,
        shipping,
        commission,
        clearing,
        extraCharges,
        sellingPrice
    ].forEach(function (element) {
        if (element) {
            element.addEventListener('input', calculatePurchase);
        }
    });

    calculatePurchase();
});
</script>

{{-- =====================================================
    JQUERY VALIDATION
====================================================== --}}

<script>
$(document).ready(function () {

    $('#myForm').validate({

        rules: {
            user_id: {
                required: true
            },

            buying_date: {
                required: true
            },

            lot_number: {
                required: true
            },

            vin: {
                required: true
            },

            cylinder: {
                required: true
            },

            buying_fee: {
                required: true,
                number: true,
                min: 0
            },

            towing_fee: {
                required: true,
                number: true,
                min: 0
            },

            shipping: {
                required: true,
                number: true,
                min: 0
            },

            commission: {
                required: true,
                number: true,
                min: 0
            },

            clearing: {
                required: true,
                number: true,
                min: 0
            },

            vin_link: {
                url: true
            }
        },

        messages: {
            user_id: {
                required: 'Please select a user.'
            },

            buying_date: {
                required: 'Please select the buying date.'
            },

            lot_number: {
                required: 'Please enter the lot number.'
            },

            vin: {
                required: 'Please enter the VIN.'
            },

            cylinder: {
                required: 'Please enter the cylinder description.'
            },

            buying_fee: {
                required: 'Please enter the buying fee.',
                number: 'Please enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            towing_fee: {
                required: 'Please enter the towing fee.',
                number: 'Please enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            shipping: {
                required: 'Please enter the shipping fee.',
                number: 'Please enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            commission: {
                required: 'Please enter the commission.',
                number: 'Please enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            clearing: {
                required: 'Please enter the clearing fee.',
                number: 'Please enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            vin_link: {
                url: 'Please enter a valid URL.'
            }
        },

        errorElement: 'span',

        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },

        highlight: function (element) {
            $(element).addClass('is-invalid');
        },

        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });

});
</script>

@endsection