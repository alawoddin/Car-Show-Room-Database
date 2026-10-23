@extends('admin.admin_dashboard')

@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


    
<div class="page-content">

    <!-- Breadcrumb -->
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


            {{-- =====================================================
                FORM
            ====================================================== --}}

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

                <h6 class="mt-2 mb-3">
                    User Information
                </h6>


                <div class="row">

                    <div class="form-group col-md-6 mb-3">

                        <label class="form-label">
                            Users (select 1 to 3, admin allowed)
                        </label>

                        @php
                            $selectedUserIds = old(
                                'user_ids',
                                isset($purchase) && $purchase->exists
                                    ? $purchase->selectedUserIds()
                                    : []
                            );
                        @endphp

                        <div class="border rounded p-3">
                            @foreach ($users as $user)
                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input purchase-user-check"
                                        type="checkbox"
                                        name="user_ids[]"
                                        id="user_{{ $user->id }}"
                                        value="{{ $user->id }}"
                                        {{ in_array($user->id, array_map('intval', (array) $selectedUserIds), true) ? 'checked' : '' }}
                                        {{ isset($purchase) && $purchase->exists ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="user_{{ $user->id }}">
                                        {{ $user->name }}
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('user_ids')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if(isset($purchase) && $purchase->exists)
                            @foreach ($purchase->selectedUserIds() as $uid)
                                <input type="hidden" name="user_ids[]" value="{{ $uid }}">
                            @endforeach
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
                            value="{{ $purchase->buying_date ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>

                </div>


                {{-- =====================================================
                    VEHICLE INFORMATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Vehicle Information
                </h6>


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
                            value="{{ $purchase->lot_number ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-6 mb-3">

                        <label for="vin" class="form-label">
                            VIN
                        </label>

                        <input
                            type="text"
                            name="vin"
                            id="vin"
                            class="form-control"
                            value="{{ $purchase->vin ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

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
                            value="{{ $purchase->cylinder ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="color" class="form-label">
                            Color
                        </label>

                        <input
                            type="text"
                            name="color"
                            id="color"
                            class="form-control"
                            value="{{ $purchase->color ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="make" class="form-label">
                            Make
                        </label>

                        <input
                            type="text"
                            name="make"
                            id="make"
                            class="form-control"
                            value="{{ $purchase->make ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="model" class="form-label">
                            Model
                        </label>

                        <input
                            type="text"
                            name="model"
                            id="model"
                            class="form-control"
                            value="{{ $purchase->model ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>

                </div>


                {{-- =====================================================
                    PURCHASE COST
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Purchase Cost
                </h6>
                <p class="text-muted small mb-3">
                    Only <strong>Buying / Towing / Shipping</strong> are in <strong>USD $</strong>
                    (1 USD = {{ rtrim(rtrim(number_format((float) config('purchase.usd_to_aed'), 4, '.', ''), '0'), '.') }} AED).
                    Clearing / Commission / Sale are in <strong>AED</strong>.
                    Grand Total = Total AED + Clearing + Commission + Custom Duty.
                </p>


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
                            placeholder="0.00"
                            value="{{ $purchase->buying_fee ?? '' }}"
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
                            placeholder="0.00"
                            value="{{ $purchase->towing_fee ?? '' }}"
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
                            placeholder="0.00"
                            value="{{ $purchase->shipping ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    {{-- COMMISSION --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="commission" class="form-label">
                            Commission (AED)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="commission"
                            id="commission"
                            class="form-control"
                            placeholder="0.00"
                            value="{{ $purchase->commission ?? '0' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>

                </div>


                {{-- =====================================================
                    UAE COSTS
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    UAE Costs (AED)
                </h6>


                <div class="row">

                    <div class="form-group col-md-3 mb-3">

                        <label for="total_aed" class="form-label">
                            Total AED
                        </label>

                        <input
                            type="text"
                            name="total_aed"
                            id="total_aed"
                            class="form-control"
                            value="{{ $purchase->total_aed ?? '' }}"
                            readonly>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="clearing" class="form-label">
                            Clearing (AED)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="clearing"
                            id="clearing"
                            class="form-control"
                            placeholder="0.00"
                            value="{{ $purchase->clearing ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="extra_charges" class="form-label">
                            Surcharge (AED)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="extra_charges"
                            id="extra_charges"
                            class="form-control"
                            placeholder="0.00"
                            value="{{ $purchase->extra_charges ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="custom_duty" class="form-label">
                            Custom Duty (AED)
                        </label>

                        <input
                            type="text"
                            name="custom_duty"
                            id="custom_duty"
                            class="form-control"
                            value="{{ $purchase->custom_duty ?? '' }}"
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
                            value="{{ $purchase->grand_total ?? '' }}"
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
                            value="{{ $purchase->shipping_company ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                </div>


                {{-- =====================================================
                    PURCHASE LOCATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Purchase Information
                </h6>


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
                            value="{{ $purchase->location ?? '' }}"
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
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>

                    <div class="form-group col-md-6 mb-3">

                        <label for="arrival_date" class="form-label">
                            Arrival Date
                        </label>

                        <input
                            type="date"
                            name="arrival_date"
                            id="arrival_date"
                            class="form-control"
                            value="{{ old('arrival_date', $purchase->arrival_date ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>

                    <div class="form-group col-md-6 mb-3">

                        <label for="vin_link" class="form-label">
                            VIN Link (optional)
                        </label>

                        <input
                            type="url"
                            name="vin_link"
                            id="vin_link"
                            class="form-control"
                            placeholder="https://..."
                            value="{{ old('vin_link', $purchase->vin_link ?? '') }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-6 mb-3">

                        <label for="purchase_status" class="form-label">
                            Status
                        </label>


                        @if(!isset($purchase) || !$purchase->exists)

                            <select
                                name="status"
                                id="purchase_status"
                                class="form-select">

                                <option value="Purchased">
                                    Purchased
                                </option>

                                <option value="Loaded">
                                    Loaded
                                </option>

                                <option value="Shipped">
                                    Shipped
                                </option>

                                <option value="Delivered">
                                    Delivered
                                </option>

                                <option value="On Hand">
                                    On Hand
                                </option>

                                <option value="At UAE">
                                    At UAE
                                </option>

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

                @if(isset($purchase) && $purchase->exists)

                    <hr class="my-4">

                    <h5 class="mb-4">
                        Sale Information
                    </h5>


                    <div class="row">

                        <div class="form-group col-md-6 mb-3">

                            <label for="selling_price" class="form-label">
                                Selling Price (AED)
                            </label>

                            <input
                                type="text"
                                name="selling_price"
                                id="selling_price"
                                class="form-control"
                                value="{{ $purchase->selling_price ?? '' }}"
                                placeholder="Enter selling price">

                        </div>


                        <div class="form-group col-md-6 mb-3">

                            <label for="profit" class="form-label">
                                Profit
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="profit"
                                id="profit"
                                class="form-control"
                                value="{{ $purchase->profit ?? '' }}"
                                readonly>

                        </div>


                        <div class="form-group col-md-4 mb-3">

                            <label for="bill_no" class="form-label">
                                Bill No
                            </label>

                            <input
                                type="text"
                                name="bill_no"
                                id="bill_no"
                                class="form-control"
                                value="{{ $purchase->bill_no ?? '' }}"
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
                                value="{{ $purchase->date_of_arriving ?? '' }}">

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
                                value="{{ $purchase->customer_name ?? '' }}"
                                placeholder="Enter customer name">

                        </div>


                        <div class="form-group col-md-6 mb-3">

                            <label for="status" class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select">

                                <option value="">
                                    Select Status
                                </option>

                                <option value="Purchased"
                                    {{ ($purchase->status ?? '') == 'Purchased' ? 'selected' : '' }}>
                                    Purchased
                                </option>

                                <option value="Loaded"
                                    {{ ($purchase->status ?? '') == 'Loaded' ? 'selected' : '' }}>
                                    Loaded
                                </option>

                                <option value="Shipped"
                                    {{ ($purchase->status ?? '') == 'Shipped' ? 'selected' : '' }}>
                                    Shipped
                                </option>

                                <option value="Delivered"
                                    {{ ($purchase->status ?? '') == 'Delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>

                                <option value="On Hand"
                                    {{ ($purchase->status ?? '') == 'On Hand' ? 'selected' : '' }}>
                                    On Hand
                                </option>

                                <option value="At UAE"
                                    {{ ($purchase->status ?? '') == 'At UAE' ? 'selected' : '' }}>
                                    At UAE
                                </option>

                                <option value="Sold"
                                    {{ ($purchase->status ?? '') == 'Sold' ? 'selected' : '' }}>
                                    Sold
                                </option>

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
                                placeholder="Sale description">{{ $purchase->description ?? '' }}</textarea>

                        </div>

                    </div>

                @else

                    {{-- PURCHASE DESCRIPTION --}}

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
                                placeholder="Purchase description"></textarea>

                        </div>

                    </div>

                @endif


                {{-- =====================================================
                    SUBMIT
                ====================================================== --}}

                <div class="col-md-12 mt-4">

                    <div class="d-md-flex d-grid align-items-center gap-3">

                        <button
                            type="submit"
                            class="btn btn-primary px-4">

                            {{ isset($purchase) && $purchase->exists
                                ? 'Save Sale'
                                : 'Save Purchase' }}

                        </button>


                        <a
                            href="{{ route('all.purchases') }}"
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
    JAVASCRIPT CALCULATION
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


    // Same rates as config/purchase.php (server recalculates on save)
    const exchangeRate = {{ (float) config('purchase.usd_to_aed') }};
    const dutyBase = {{ (float) config('purchase.duty_base') }};
    const dutyRate = {{ (float) config('purchase.duty_rate') }};


    function number(value)
    {
        return parseFloat(value) || 0;
    }


    function calculatePurchase()
    {

        if (!buyingFee || !towingFee || !shipping) {
            return;
        }


        // 1. Only buying + towing + shipping are USD
        const buying = number(buyingFee.value);
        const towing = number(towingFee.value);
        const ship = number(shipping.value);
        const clearingValue = number(clearing?.value);
        const commissionValue = number(commission?.value);

        // Excel: buying + towing + shipping (USD only)
        const totalUsd = buying + towing + ship;

        // USD → AED
        const aed = totalUsd * exchangeRate;

        if (totalAed) {
            totalAed.value = aed > 0 ? aed.toFixed(2) : '';
        }

        // Custom duty only when USD fees exist
        const duty = aed > 0 ? (aed + dutyBase) * dutyRate : 0;

        if (customDuty) {
            customDuty.value = duty > 0 ? duty.toFixed(2) : '';
        }

        // Grand Total = Total AED + Clearing + Commission + Custom Duty
        const grand = aed + clearingValue + commissionValue + duty;

        if (grandTotal) {
            grandTotal.value = grand > 0 ? grand.toFixed(2) : '';
        }

        // 5. Profit (sale in AED)
        if (sellingPrice && profit) {
            const selling = number(sellingPrice.value);
            if (selling > 0 || grand > 0) {
                profit.value = (selling - grand).toFixed(2);
            } else {
                profit.value = '';
            }
        }

    }


    // ==========================================
    // Purchase inputs
    // ==========================================

    if (buyingFee) {

        buyingFee.addEventListener(
            'input',
            calculatePurchase
        );

    }


    if (towingFee) {

        towingFee.addEventListener(
            'input',
            calculatePurchase
        );

    }


    if (shipping) {

        shipping.addEventListener(
            'input',
            calculatePurchase
        );

    }


    // ==========================================
    // COMMISSION
    // ==========================================

    if (commission) {

        commission.addEventListener(
            'input',
            calculatePurchase
        );

    }


    if (clearing) {

        clearing.addEventListener(
            'input',
            calculatePurchase
        );

    }


    if (extraCharges) {

        extraCharges.addEventListener(
            'input',
            calculatePurchase
        );

    }


    // ==========================================
    // Sale selling price
    // ==========================================

    if (sellingPrice) {

        sellingPrice.addEventListener(
            'input',
            calculatePurchase
        );

    }


    // Calculate immediately when page loads

    calculatePurchase();

});

</script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    'user_ids[]': {
                        required: true,
                    },
                    lot_number: {
                        required: true,
                    },
                    vin: {
                        required: true,
                    },
                    Cylinder: {
                        required: true,
                    },
                    buying_fee: {
                        required: true,
                    },
                    towing_fee: {
                        required: true,
                    },
                    shipping: {
                        required: true,
                    },
                    commission: {
                        required: true,
                    },
                    Clearing: {
                        required: true,
                    },

                },
                messages: {
                    'user_ids[]': {
                        required: 'Please select at least 1 user',
                    },
                    lot_number: {
                        required: 'Please Enter  lot_number',
                    },
                    vin: {
                        required: 'Please Enter vin',
                    },
                    Cylinder: {
                        required: 'Please Enter Cylinder Description',
                    },
                    buying_fee: {
                        required: 'Please Enter buying_fee ',
                    },
                    towing_fee: {
                        required: 'Please Enter towing_fee',
                    },
                    shipping: {
                        required: 'Please Enter shipping',
                    },
                    commission: {
                        required: 'Please Enter commission',
                    },
                    Clearing: {
                        required: 'Please Enter Clearing',
                    },
                    
                },
               
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });

            $(document).on('change', '.purchase-user-check', function () {
                if ($('.purchase-user-check:checked').length > 3) {
                    this.checked = false;
                    alert('You can select up to 3 users.');
                }
            });
        });
    </script>




@endsection