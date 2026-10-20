@extends('admin.admin_dashboard')

@section('admin')

<<<<<<< HEAD
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
=======

    
>>>>>>> e57198ffc78de35f7e5f96cb936cd6f8660e3717

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
                        Edit Purchase
                    </li>
                </ol>
            </nav>
        </div>
    </div>

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

    <div class="card">
        <div class="card-body p-4">

            <h5 class="mb-4">Edit Purchase</h5>

            <form
                id="myForm"
                action="{{ route('update.purchase') }}"
                method="POST">

                @csrf

                <input type="hidden" name="id" value="{{ $purchase->id }}">

                {{-- =====================================================
                    USER INFORMATION
                ====================================================== --}}

                <h6 class="mb-3">User Information</h6>

                <div class="row">

                    <div class="form-group col-md-6 mb-3">
<<<<<<< HEAD
                        <label for="user_id" class="form-label">
                            User Name
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
                            class="form-select">

                            <option value="">Select User</option>

                            @foreach ($users as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ (string) old('user_id', $purchase->user_id) === (string) $user->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $user->name }}

                                </option>
                            @endforeach

                        </select>
=======

                        <label class="form-label">
                            Users
                        </label>

                        @php
                            $selectedUserIds = old('user_ids', $purchase->selectedUserIds());
                        @endphp

                        <div class="border rounded p-2" style="max-height: 180px; overflow: auto;">
                            @foreach($users as $user)
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

>>>>>>> e57198ffc78de35f7e5f96cb936cd6f8660e3717
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
                            value="{{ old('buying_date', $purchase->buying_date ? \Carbon\Carbon::parse($purchase->buying_date)->format('Y-m-d') : '') }}">
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
                            value="{{ old('lot_number', $purchase->lot_number) }}">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="vin" class="form-label">VIN</label>

                        <input
                            type="text"
                            name="vin"
                            id="vin"
                            class="form-control"
                            value="{{ old('vin', $purchase->vin) }}">
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
                            External vehicle or auction listing URL.
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
                            value="{{ old('cylinder', $purchase->cylinder) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="color" class="form-label">Color</label>

                        <input
                            type="text"
                            name="color"
                            id="color"
                            class="form-control"
                            value="{{ old('color', $purchase->color) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="make" class="form-label">Make</label>

                        <input
                            type="text"
                            name="make"
                            id="make"
                            class="form-control"
                            value="{{ old('make', $purchase->make) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="model" class="form-label">Model</label>

                        <input
                            type="text"
                            name="model"
                            id="model"
                            class="form-control"
                            value="{{ old('model', $purchase->model) }}">
                    </div>

                </div>

                {{-- =====================================================
                    PURCHASE COSTS
                ====================================================== --}}

                <h6 class="mt-4 mb-3">Purchase Cost</h6>

                <div class="row">

                    <div class="form-group col-md-3 mb-3">
                        <label for="buying_fee" class="form-label">
                            Buying Fee (USD)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="buying_fee"
                            id="buying_fee"
                            class="form-control"
                            value="{{ old('buying_fee', $purchase->buying_fee ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="towing_fee" class="form-label">
                            Towing Fee (USD)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="towing_fee"
                            id="towing_fee"
                            class="form-control"
                            value="{{ old('towing_fee', $purchase->towing_fee ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="shipping" class="form-label">
                            Shipping Fee (USD)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="shipping"
                            id="shipping"
                            class="form-control"
                            value="{{ old('shipping', $purchase->shipping ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="commission" class="form-label">
                            Commission (USD)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="commission"
                            id="commission"
                            class="form-control"
                            value="{{ old('commission', $purchase->commission ?? 0) }}">
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
                            value="{{ old('total_aed', $purchase->total_aed ?? 0) }}"
                            readonly>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="clearing" class="form-label">
                            Clearing (AED)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="clearing"
                            id="clearing"
                            class="form-control"
                            value="{{ old('clearing', $purchase->clearing ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="extra_charges" class="form-label">
                            Extra Charges (AED)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="extra_charges"
                            id="extra_charges"
                            class="form-control"
                            value="{{ old('extra_charges', $purchase->extra_charges ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label for="custom_duty" class="form-label">
                            Custom Duty (AED)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="custom_duty"
                            id="custom_duty"
                            class="form-control"
                            value="{{ old('custom_duty', $purchase->custom_duty ?? 0) }}"
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
                            value="{{ old('grand_total', $purchase->grand_total ?? 0) }}"
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
                    PURCHASE AND CONTAINER INFORMATION
                ====================================================== --}}

                <h6 class="mt-4 mb-3">
                    Purchase and Container Information
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
                            value="{{ old('location', $purchase->location ?? '') }}">
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
                            value="{{ old('arrival_date', $purchase->arrival_date ? \Carbon\Carbon::parse($purchase->arrival_date)->format('Y-m-d') : '') }}">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="status" class="form-label">
                            Vehicle Status
                        </label>

                        <select
                            name="status"
                            id="status"
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
                                    {{ old('status', $purchase->status) === $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                </div>

                {{-- =====================================================
                    SALE INFORMATION
                ====================================================== --}}

                <hr class="my-4">

                <h5 class="mb-4">Sale Information</h5>

                <div class="row">

                    <div class="form-group col-md-6 mb-3">
                        <label for="selling_price" class="form-label">
                            Selling Price (AED)
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="selling_price"
                            id="selling_price"
                            class="form-control"
                            value="{{ old('selling_price', $purchase->selling_price ?? 0) }}"
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
                            value="{{ old('profit', $purchase->profit ?? 0) }}"
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
                            value="{{ old('date_of_arriving', $purchase->date_of_arriving ? \Carbon\Carbon::parse($purchase->date_of_arriving)->format('Y-m-d') : '') }}">
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

                </div>

                {{-- Description --}}
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
                            placeholder="Enter description">{{ old('description', $purchase->description ?? '') }}</textarea>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="mt-4 d-flex flex-wrap gap-3">

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save"></i>
                        Update Purchase
                    </button>

                    <a href="{{ route('all.purchases') }}"
                       class="btn btn-light px-4">
                        Back
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

{{-- =====================================================
    PURCHASE CALCULATIONS
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

    const exchangeRate = 3.675;

    function number(value) {
        const parsed = parseFloat(value);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function calculatePurchase() {

        const totalUsd =
            number(buyingFee.value) +
            number(towingFee.value) +
            number(shipping.value) +
            number(commission.value);

        const aed = totalUsd * exchangeRate;

<<<<<<< HEAD
        // Update total AED.
        totalAed.value = aed.toFixed(2);

        // Confirm this duty formula matches your business rules.
        const duty = (aed + 1472) * 0.055;

        customDuty.value = duty.toFixed(2);
=======
        // ==========================================
        // USD → AED
        // No car cost yet: keep AED, duty, and grand total at zero.
        // ==========================================

        const aed = totalUsd > 0
            ? totalUsd * exchangeRate
            : 0;


        totalAed.value =
            aed.toFixed(2);


        // ==========================================
        // CUSTOM DUTY
        // ==========================================

        const duty = totalUsd > 0
            ? (aed + 1472) * 0.055
            : 0;


        customDuty.value =
            duty.toFixed(2);


        // ==========================================
        // GRAND TOTAL
        // ==========================================
>>>>>>> e57198ffc78de35f7e5f96cb936cd6f8660e3717

        const grand =
            aed +
            number(clearing.value) +
            number(extraCharges.value) +
            duty;

        grandTotal.value = grand.toFixed(2);

        // Calculate profit only when a selling price is entered.
        const selling = number(sellingPrice.value);

        if (sellingPrice.value !== '') {
            profit.value = (selling - grand).toFixed(2);
        } else {
            profit.value = '0.00';
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

<<<<<<< HEAD
{{-- =====================================================
    FORM VALIDATION
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
            },

            selling_price: {
                number: true,
                min: 0
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

            buying_fee: {
                required: 'Please enter the buying fee.',
                number: 'Enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            towing_fee: {
                required: 'Please enter the towing fee.',
                number: 'Enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            shipping: {
                required: 'Please enter the shipping fee.',
                number: 'Enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            commission: {
                required: 'Please enter the commission.',
                number: 'Enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            clearing: {
                required: 'Please enter the clearing fee.',
                number: 'Enter a valid amount.',
                min: 'The amount cannot be negative.'
            },

            vin_link: {
                url: 'Please enter a valid URL.'
            },

            selling_price: {
                number: 'Enter a valid selling price.',
                min: 'The selling price cannot be negative.'
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
=======
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
>>>>>>> e57198ffc78de35f7e5f96cb936cd6f8660e3717

@endsection