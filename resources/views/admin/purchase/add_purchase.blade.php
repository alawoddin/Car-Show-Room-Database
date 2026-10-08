@extends('admin.admin_dashboard')

@section('admin')

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

                        <label for="user_id" class="form-label">
                            User Name
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
                            class="form-select"
                            {{ isset($purchase) && $purchase->exists ? 'disabled' : '' }}>

                            <option value="">
                                Select User
                            </option>

                            @foreach ($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    {{ isset($purchase) && $purchase->user_id == $user->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>


                        @if(isset($purchase) && $purchase->exists)

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


                <div class="row">

                    <div class="form-group col-md-3 mb-3">

                        <label for="buying_fee" class="form-label">
                            Buying Fee
                        </label>

                        <input
                            type="text"
                            name="buying_fee"
                            id="buying_fee"
                            class="form-control"
                            value="{{ $purchase->buying_fee ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="towing_fee" class="form-label">
                            Towing Fee
                        </label>

                        <input
                            type="text"
                            name="towing_fee"
                            id="towing_fee"
                            class="form-control"
                            value="{{ $purchase->towing_fee ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="shipping" class="form-label">
                            Shipping Fee
                        </label>

                        <input
                            type="text"
                            name="shipping"
                            id="shipping"
                            class="form-control"
                            value="{{ $purchase->shipping ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    {{-- COMMISSION --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="commission" class="form-label">
                            Commission
                        </label>

                        <input
                            type="text"
                            name="commission"
                            id="commission"
                            class="form-control"
                            value="{{ $purchase->commission ?? '0' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>

                </div>


                {{-- =====================================================
                    UAE COSTS
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    UAE Costs
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
                            Clearing
                        </label>

                        <input
                            type="text"
                            name="clearing"
                            id="clearing"
                            class="form-control"
                            value="{{ $purchase->clearing ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="extra_charges" class="form-label">
                            Extra Charges
                        </label>

                        <input
                            type="text"
                            name="extra_charges"
                            id="extra_charges"
                            class="form-control"
                            value="{{ $purchase->extra_charges ?? '' }}"
                            {{ isset($purchase) && $purchase->exists ? 'readonly' : '' }}>

                    </div>


                    <div class="form-group col-md-3 mb-3">

                        <label for="custom_duty" class="form-label">
                            Custom Duty
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
                            Grand Total
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

                        <label for="grand_total" class="form-label">
                            shipping_company
                        </label>

                        <input
                            type="text"
                            name="shipping_company"
                            class="form-control"
                            value="{{ $purchase->shipping_company ?? '' }}"
                            >

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
                                Selling Price
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


    // USD → AED

    const exchangeRate = 3.675;


    function number(value)
    {
        return parseFloat(value) || 0;
    }


    function calculatePurchase()
    {

        if (!buyingFee || !towingFee || !shipping) {
            return;
        }


        // ==========================================
        // 1. Total USD
        // ==========================================

        const buying = number(buyingFee.value);

        const towing = number(towingFee.value);

        const ship = number(shipping.value);

        const commissionValue = number(
            commission ? commission.value : 0
        );


        /*
        |--------------------------------------------------------------------------
        | Buying Fee + Towing + Shipping + Commission
        |--------------------------------------------------------------------------
        */

        const totalUsd =
            buying +
            towing +
            ship +
            commissionValue;


        // ==========================================
        // 2. USD → AED
        // ==========================================

        const aed =
            totalUsd *
            exchangeRate;


        if (totalAed) {

            totalAed.value =
                aed.toFixed(2);

        }


        // ==========================================
        // 3. Custom Duty
        // ==========================================

        const duty =
            (aed + 1472) *
            0.055;


        if (customDuty) {

            customDuty.value =
                duty.toFixed(2);

        }


        // ==========================================
        // 4. Grand Total
        // ==========================================

        const clearingValue =
            number(clearing?.value);


        const extraChargesValue =
            number(extraCharges?.value);


        const grand =
            aed +
            clearingValue +
            extraChargesValue +
            duty;


        if (grandTotal) {

            grandTotal.value =
                grand.toFixed(2);

        }


        // ==========================================
        // 5. Profit
        // Only on Sale
        // ==========================================

        if (sellingPrice && profit) {

            const selling =
                number(sellingPrice.value);


            const calculatedProfit =
                selling -
                grand;


            profit.value =
                calculatedProfit.toFixed(2);

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


@endsection