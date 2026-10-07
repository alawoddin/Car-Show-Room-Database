@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    {{-- =====================================================
        PAGE BREADCRUMB
    ====================================================== --}}

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
                        Edit Purchase
                    </li>

                </ol>

            </nav>

        </div>

    </div>


    {{-- =====================================================
        MAIN CARD
    ====================================================== --}}

    <div class="card">

        <div class="card-body p-4">

            <h5 class="mb-4">
                Edit Purchase
            </h5>


            {{-- =====================================================
                FORM
            ====================================================== --}}

            <form
                id="myForm"
                action="{{ route('update.purchase') }}"
                method="POST">

                @csrf

                <input
                    type="hidden"
                    name="id"
                    value="{{ $purchase->id }}">


                {{-- =====================================================
                    USER INFORMATION
                ====================================================== --}}

                <h6 class="mb-3">
                    User Information
                </h6>


                <div class="row">

                    {{-- USER --}}

                    <div class="form-group col-md-6 mb-3">

                        <label for="user_id" class="form-label">
                            User Name
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
                            class="form-select">

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    {{ $purchase->user_id == $user->id ? 'selected' : '' }}>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUYING DATE --}}

                    <div class="form-group col-md-6 mb-3">

                        <label for="buying_date" class="form-label">
                            Buying Date
                        </label>

                        <input
                            type="date"
                            name="buying_date"
                            id="buying_date"
                            class="form-control"
                            value="{{ $purchase->buying_date }}">

                    </div>

                </div>


                {{-- =====================================================
                    VEHICLE INFORMATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Vehicle Information
                </h6>


                <div class="row">

                    {{-- LOT NUMBER --}}

                    <div class="form-group col-md-6 mb-3">

                        <label for="lot_number" class="form-label">
                            Lot Number
                        </label>

                        <input
                            type="text"
                            name="lot_number"
                            id="lot_number"
                            class="form-control"
                            value="{{ $purchase->lot_number }}">

                    </div>


                    {{-- VIN --}}

                    <div class="form-group col-md-6 mb-3">

                        <label for="vin" class="form-label">
                            VIN
                        </label>

                        <input
                            type="text"
                            name="vin"
                            id="vin"
                            class="form-control"
                            value="{{ $purchase->vin }}">

                    </div>

                </div>


                <div class="row">

                    {{-- CYLINDER --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="cylinder" class="form-label">
                            Cylinder
                        </label>

                        <input
                            type="text"
                            name="cylinder"
                            id="cylinder"
                            class="form-control"
                            value="{{ $purchase->cylinder }}">

                    </div>


                    {{-- COLOR --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="color" class="form-label">
                            Color
                        </label>

                        <input
                            type="text"
                            name="color"
                            id="color"
                            class="form-control"
                            value="{{ $purchase->color }}">

                    </div>


                    {{-- MAKE --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="make" class="form-label">
                            Make
                        </label>

                        <input
                            type="text"
                            name="make"
                            id="make"
                            class="form-control"
                            value="{{ $purchase->make }}">

                    </div>


                    {{-- MODEL --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="model" class="form-label">
                            Model
                        </label>

                        <input
                            type="text"
                            name="model"
                            id="model"
                            class="form-control"
                            value="{{ $purchase->model }}">

                    </div>

                </div>


                {{-- =====================================================
                    PURCHASE COST
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Purchase Cost
                </h6>


                <div class="row">

                    {{-- BUYING FEE --}}

                    <div class="form-group col-md-4 mb-3">

                        <label for="buying_fee" class="form-label">
                            Buying Fee
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="buying_fee"
                            id="buying_fee"
                            class="form-control"
                            value="{{ $purchase->buying_fee }}">

                    </div>


                    {{-- TOWING FEE --}}

                    <div class="form-group col-md-4 mb-3">

                        <label for="towing_fee" class="form-label">
                            Towing Fee
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="towing_fee"
                            id="towing_fee"
                            class="form-control"
                            value="{{ $purchase->towing_fee }}">

                    </div>


                    {{-- SHIPPING --}}

                    <div class="form-group col-md-4 mb-3">

                        <label for="shipping" class="form-label">
                            Shipping
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="shipping"
                            id="shipping"
                            class="form-control"
                            value="{{ $purchase->shipping }}">

                    </div>

                </div>


                {{-- =====================================================
                    UAE COSTS
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    UAE Costs
                </h6>


                <div class="row">

                    {{-- TOTAL AED --}}

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
                            value="{{ $purchase->total_aed }}"
                            readonly>

                    </div>


                    {{-- CLEARING --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="clearing" class="form-label">
                            Clearing
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="clearing"
                            id="clearing"
                            class="form-control"
                            value="{{ $purchase->clearing }}">

                    </div>


                    {{-- EXTRA CHARGES --}}

                    <div class="form-group col-md-3 mb-3">

                        <label for="extra_charges" class="form-label">
                            Extra Charges
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="extra_charges"
                            id="extra_charges"
                            class="form-control"
                            value="{{ $purchase->extra_charges }}">

                    </div>


                    {{-- CUSTOM DUTY --}}

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
                            value="{{ $purchase->custom_duty }}"
                            readonly>

                    </div>

                </div>


                {{-- GRAND TOTAL --}}

                <div class="row">

                    <div class="form-group col-md-4 mb-3">

                        <label for="grand_total" class="form-label">
                            Grand Total
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="grand_total"
                            id="grand_total"
                            class="form-control"
                            value="{{ $purchase->grand_total }}"
                            readonly>

                    </div>

                    <div class="form-group col-md-4 mb-3">

                        <label for="grand_total" class="form-label">
                            shipping_company
                        </label>

                        <input
                            type="text"
                            step="0.01"
                            name="shipping_company"
                            class="form-control"
                            value="{{ $purchase->shipping_company ?? '' }}"
                            >

                    </div>


                     <div class="form-group col-md-4 mb-3">

                        <label for="grand_total" class="form-label">
                            
                        </label>

                        <input
                            type="text"
                            
                            name="commission"
                            class="form-control"
                            value="{{ $purchase->commission ?? '' }}"
                            >

                    </div>




                </div>


                {{-- =====================================================
                    PURCHASE INFORMATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Purchase Information
                </h6>


                <div class="row">

                    {{-- LOCATION --}}

                    <div class="form-group col-md-6 mb-3">

                        <label for="location" class="form-label">
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            id="location"
                            class="form-control"
                            value="{{ $purchase->location }}">

                    </div>


                    {{-- STATUS --}}

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

                            <option
                                value="Purchased"
                                {{ $purchase->status == 'Purchased' ? 'selected' : '' }}>
                                Purchased
                            </option>

                            <option
                                value="Loaded"
                                {{ $purchase->status == 'Loaded' ? 'selected' : '' }}>
                                Loaded
                            </option>

                            <option
                                value="Shipped"
                                {{ $purchase->status == 'Shipped' ? 'selected' : '' }}>
                                Shipped
                            </option>

                            <option
                                value="Delivered"
                                {{ $purchase->status == 'Delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>

                            <option
                                value="On Hand"
                                {{ $purchase->status == 'On Hand' ? 'selected' : '' }}>
                                On Hand
                            </option>

                            <option
                                value="At UAE"
                                {{ $purchase->status == 'At UAE' ? 'selected' : '' }}>
                                At UAE
                            </option>

                            <option
                                value="Sold"
                                {{ $purchase->status == 'Sold' ? 'selected' : '' }}>
                                Sold
                            </option>

                        </select>

                    </div>

                </div>


                {{-- =====================================================
                    SALE INFORMATION
                ====================================================== --}}

                <hr class="my-4">

                <h5 class="mb-4">
                    Sale Information
                </h5>


                <div class="row">

                    {{-- SELLING PRICE --}}

                    <div class="form-group col-md-6 mb-3">

                        <label for="selling_price" class="form-label">
                            Selling Price
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="selling_price"
                            id="selling_price"
                            class="form-control"
                            value="{{ $purchase->selling_price }}"
                            placeholder="Enter selling price">

                    </div>


                    {{-- PROFIT --}}

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
                            value="{{ $purchase->profit }}"
                            readonly>

                    </div>


                    {{-- BILL NO --}}

                    <div class="form-group col-md-4 mb-3">

                        <label for="bill_no" class="form-label">
                            Bill No
                        </label>

                        <input
                            type="text"
                            name="bill_no"
                            id="bill_no"
                            class="form-control"
                            value="{{ $purchase->bill_no }}"
                            placeholder="Enter bill number">

                    </div>


                    {{-- DATE OF ARRIVING --}}

                    <div class="form-group col-md-4 mb-3">

                        <label for="date_of_arriving" class="form-label">
                            Date of Arriving
                        </label>

                        <input
                            type="date"
                            name="date_of_arriving"
                            id="date_of_arriving"
                            class="form-control"
                            value="{{ $purchase->date_of_arriving }}">

                    </div>


                    {{-- CUSTOMER NAME --}}

                    <div class="form-group col-md-4 mb-3">

                        <label for="customer_name" class="form-label">
                            Customer Name
                        </label>

                        <input
                            type="text"
                            name="customer_name"
                            id="customer_name"
                            class="form-control"
                            value="{{ $purchase->customer_name }}"
                            placeholder="Enter customer name">

                    </div>

                </div>


                {{-- =====================================================
                    DESCRIPTION
                ====================================================== --}}

                <div class="row">

                    <div class="form-group col-md-12 mb-3">

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            class="form-control"
                            rows="4"
                            placeholder="Description">{{ $purchase->description }}</textarea>

                    </div>

                </div>


                {{-- =====================================================
                    BUTTONS
                ====================================================== --}}

                <div class="col-md-12 mt-4">

                    <div class="d-md-flex d-grid align-items-center gap-3">

                        <button
                            type="submit"
                            class="btn btn-primary px-4">

                            Update Purchase

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

    const totalAed = document.getElementById('total_aed');

    const clearing = document.getElementById('clearing');
    const extraCharges = document.getElementById('extra_charges');

    const customDuty = document.getElementById('custom_duty');
    const grandTotal = document.getElementById('grand_total');

    const sellingPrice = document.getElementById('selling_price');
    const profit = document.getElementById('profit');


    // Exchange Rate
    const exchangeRate = 3.675;


    function number(value)
    {
        return parseFloat(value) || 0;
    }


    function calculatePurchase()
    {

        const buying = number(
            buyingFee.value
        );

        const towing = number(
            towingFee.value
        );

        const ship = number(
            shipping.value
        );


        // =========================
        // Total USD
        // =========================

        const totalUsd =
            buying +
            towing +
            ship;


        // =========================
        // USD → AED
        // =========================

        const aed =
            totalUsd *
            exchangeRate;


        totalAed.value =
            aed.toFixed(2);


        // =========================
        // Custom Duty
        // =========================

        const duty =
            (aed + 1472) *
            0.055;


        customDuty.value =
            duty.toFixed(2);


        // =========================
        // Grand Total
        // =========================

        const clearingValue =
            number(clearing.value);

        const extraChargesValue =
            number(extraCharges.value);


        const grand =
            aed +
            clearingValue +
            extraChargesValue +
            duty;


        grandTotal.value =
            grand.toFixed(2);


        // =========================
        // Profit
        // =========================

        const selling =
            number(sellingPrice.value);


        const calculatedProfit =
            selling -
            grand;


        profit.value =
            calculatedProfit.toFixed(2);

    }


    // =========================
    // Events
    // =========================

    buyingFee.addEventListener(
        'input',
        calculatePurchase
    );

    towingFee.addEventListener(
        'input',
        calculatePurchase
    );

    shipping.addEventListener(
        'input',
        calculatePurchase
    );

    clearing.addEventListener(
        'input',
        calculatePurchase
    );

    extraCharges.addEventListener(
        'input',
        calculatePurchase
    );

    sellingPrice.addEventListener(
        'input',
        calculatePurchase
    );


    // Calculate on page load
    calculatePurchase();

});

</script>

@endsection