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
                            Users
                        </label>

                        @php
                            $selectedUserIds = old('user_ids', isset($purchase) ? $purchase->selectedUserIds() : []);
                        @endphp

                        @if(isset($purchase) && $purchase->exists)
                            <div class="form-control bg-light">
                                {{ $purchase->buyerNames() }}
                            </div>
                            @foreach ($selectedUserIds as $selectedUserId)
                                <input type="hidden" name="user_ids[]" value="{{ $selectedUserId }}">
                            @endforeach
                        @else
                            <div class="border rounded p-2" style="max-height: 180px; overflow: auto;">
                                @foreach ($users as $user)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input purchase-user"
                                            type="checkbox"
                                            name="user_ids[]"
                                            id="purchase-user-{{ $user->id }}"
                                            value="{{ $user->id }}"
                                            {{ in_array($user->id, array_map('intval', (array) $selectedUserIds), true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="purchase-user-{{ $user->id }}">
                                            {{ $user->name }}
                                            @if($user->role === 'admin')
                                                <span class="badge bg-dark ms-1">Admin</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Select 1, 2, or 3 accounts, including admin. The car cost is taken from each selected account.</small>
                            @error('user_ids')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
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
                            value="{{ isset($purchase) && $purchase->buying_fee !== null && $purchase->buying_fee !== '' ? $purchase->buying_fee : 0 }}"
                            onchange="calculatePurchase()"
                            onkeyup="calculatePurchase()"
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
                            value="{{ isset($purchase) && $purchase->towing_fee !== null && $purchase->towing_fee !== '' ? $purchase->towing_fee : 0 }}"
                            onchange="calculatePurchase()"
                            onkeyup="calculatePurchase()"
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
                            value="{{ isset($purchase) && $purchase->shipping !== null && $purchase->shipping !== '' ? $purchase->shipping : 0 }}"
                            onchange="calculatePurchase()"
                            onkeyup="calculatePurchase()"
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
                            value="{{ isset($purchase) && $purchase->commission !== null && $purchase->commission !== '' ? $purchase->commission : 0 }}"
                            onchange="calculatePurchase()"
                            onkeyup="calculatePurchase()"
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
                            value="0"
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
                            value="{{ isset($purchase) && $purchase->clearing !== null && $purchase->clearing !== '' ? $purchase->clearing : 0 }}"
                            onchange="calculatePurchase()"
                            onkeyup="calculatePurchase()"
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
                            value="{{ isset($purchase) && $purchase->extra_charges !== null && $purchase->extra_charges !== '' ? $purchase->extra_charges : 0 }}"
                            onchange="calculatePurchase()"
                            onkeyup="calculatePurchase()"
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
                            value="{{ isset($purchase) && $purchase->custom_duty !== null && $purchase->custom_duty !== '' ? $purchase->custom_duty : 0 }}"
                            onchange="calculatePurchase(true)"
                            onkeyup="calculatePurchase(true)">

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
                            value="0"
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
                                onchange="calculatePurchase()"
                                onkeyup="calculatePurchase()"
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
function calculatePurchase(keepDuty) {
    var buyingFee = document.getElementById('buying_fee');
    var towingFee = document.getElementById('towing_fee');
    var shipping = document.getElementById('shipping');
    var commission = document.getElementById('commission');
    var totalAed = document.getElementById('total_aed');
    var clearing = document.getElementById('clearing');
    var extraCharges = document.getElementById('extra_charges');
    var customDuty = document.getElementById('custom_duty');
    var grandTotal = document.getElementById('grand_total');
    var sellingPrice = document.getElementById('selling_price');
    var profit = document.getElementById('profit');
    var exchangeRate = 3.675;

    function number(value) {
        return parseFloat(value) || 0;
    }

    if (!buyingFee || !towingFee || !shipping) {
        return;
    }

    var totalUsd = number(buyingFee.value) + number(towingFee.value) + number(shipping.value) + number(commission ? commission.value : 0);
    var aed = totalUsd > 0 ? totalUsd * exchangeRate : 0;
    var duty = 0;

    if (keepDuty) {
        duty = number(customDuty ? customDuty.value : 0);
    } else if (totalUsd > 0) {
        duty = (aed + 1472) * 0.055;
    }

    var grand = aed + number(clearing ? clearing.value : 0) + number(extraCharges ? extraCharges.value : 0) + duty;

    if (totalAed) {
        totalAed.value = aed.toFixed(2);
    }

    if (customDuty && !keepDuty) {
        customDuty.value = duty.toFixed(2);
    }

    if (grandTotal) {
        grandTotal.value = grand.toFixed(2);
    }

    if (sellingPrice && profit) {
        profit.value = (number(sellingPrice.value) - grand).toFixed(2);
    }
}

calculatePurchase();
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
                        required: 'Select 1, 2, or 3 users',
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
        });
    </script>

    <script>
        document.querySelectorAll('.purchase-user').forEach(function (box) {
            box.addEventListener('change', function () {
                if (document.querySelectorAll('.purchase-user:checked').length > 3) {
                    box.checked = false;
                    alert('You can select up to 3 users.');
                }
            });
        });
    </script>

@endsection