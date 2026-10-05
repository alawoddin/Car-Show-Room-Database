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

                        <li class="breadcrumb-item active" aria-current="page">
                            Add Purchase
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->


        <div class="card">
            <div class="card-body p-4">

                <h5 class="mb-4">Add Purchase</h5>

                <form id="myForm" action="{{ route('store.purchase') }}" method="POST">

                    @csrf


                    {{-- =========================
                    USER INFORMATION
                ========================== --}}

                    <div class="row">

                        <div class="form-group col-md-6 mb-3">

                            <label for="user_id" class="form-label">
                                User Name
                            </label>

                            <select name="user_id" id="user_id" class="form-select">

                                <option value="">
                                    Select User
                                </option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="form-group col-md-6 mb-3">

                            <label for="buying_date" class="form-label">
                                Buying Date
                            </label>

                            <input type="date" name="buying_date" id="buying_date" class="form-control">

                        </div>

                    </div>


                    {{-- =========================
                    VEHICLE INFORMATION
                ========================== --}}

                    <h6 class="mt-4 mb-3">
                        Vehicle Information
                    </h6>

                    <div class="row">

                        <div class="form-group col-md-6 mb-3">

                            <label for="lot_number" class="form-label">
                                Lot Number
                            </label>

                            <input type="text" name="lot_number" id="lot_number" class="form-control">

                        </div>


                        <div class="form-group col-md-6 mb-3">

                            <label for="vin" class="form-label">
                                VIN
                            </label>

                            <input type="text" name="vin" id="vin" class="form-control">

                        </div>

                    </div>


                    <div class="row">

                        <div class="form-group col-md-3 mb-3">

                            <label for="cylinder" class="form-label">
                                Cylinder
                            </label>

                            <input type="text" name="cylinder" id="cylinder" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="color" class="form-label">
                                Color
                            </label>

                            <input type="text" name="color" id="color" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="make" class="form-label">
                                Make
                            </label>

                            <input type="text" name="make" id="make" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="model" class="form-label">
                                Model
                            </label>

                            <input type="text" name="model" id="model" class="form-control">

                        </div>

                    </div>


                    {{-- =========================
                    PURCHASE COST
                ========================== --}}

                    <h6 class="mt-4 mb-3">
                        Purchase Cost
                    </h6>

                    <div class="row">

                        <div class="form-group col-md-4 mb-3">

                            <label for="buying_fee" class="form-label">
                                Buying Fee
                            </label>

                            <input type="text" step="0.01" name="buying_fee" id="buying_fee" class="form-control">

                        </div>


                        <div class="form-group col-md-4 mb-3">

                            <label for="towing_fee" class="form-label">
                                Towing Fee
                            </label>

                            <input type="text" step="0.01" name="towing_fee" id="towing_fee" class="form-control">

                        </div>


                        <div class="form-group col-md-4 mb-3">

                            <label for="shipping" class="form-label">
                                Shipping Fee
                            </label>

                            <input type="text" step="0.01" name="shipping" id="shipping" class="form-control">

                        </div>

                    </div>


                    {{-- =========================
                    UAE COST
                ========================== --}}

                    <h6 class="mt-4 mb-3">
                        UAE Costs
                    </h6>

                    <div class="row">

                        <div class="form-group col-md-3 mb-3">

                            <label for="total_aed" class="form-label">
                                Total AED
                            </label>

                            <input type="text"  name="total_aed" id="total_aed" class="form-control"
                                readonly>

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="clearing" class="form-label">
                                Clearing
                            </label>

                            <input type="text" name="clearing" id="clearing" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="surcharge" class="form-label">
                                Surcharge
                            </label>

                            <input type="text" name="surcharge" id="surcharge" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="custom_duty" class="form-label">
                                Custom Duty
                            </label>

                            <input type="text" name="custom_duty" id="custom_duty"
                                class="form-control" readonly>

                        </div>

                    </div>


                    <div class="row">

                        <div class="form-group col-md-6 mb-3">

                            <label for="grand_total" class="form-label">
                                Grand Total
                            </label>

                            <input type="number" step="0.01" name="grand_total" id="grand_total"
                                class="form-control" readonly>

                        </div>

                    </div>


                    {{-- =========================
                    SALE INFORMATION
                ========================== --}}

                    <h6 class="mt-4 mb-3">
                        Sale Information
                    </h6>

                    <div class="row">

                        <div class="form-group col-md-6 mb-3">

                            <label for="selling_price" class="form-label">
                                Selling Price
                            </label>

                            <input type="text" name="selling_price" id="selling_price"
                                class="form-control">

                        </div>


                        <div class="form-group col-md-6 mb-3">

                            <label for="profit" class="form-label">
                                Profit
                            </label>

                            <input type="number" step="0.01" name="profit" id="profit" class="form-control"
                                readonly>

                        </div>

                    </div>


                    {{-- =========================
                    OTHER INFORMATION
                ========================== --}}

                    <h6 class="mt-4 mb-3">
                        Other Information
                    </h6>

                    <div class="row">

                        <div class="form-group col-md-3 mb-3">

                            <label for="bill_no" class="form-label">
                                Bill No
                            </label>

                            <input type="text" name="bill_no" id="bill_no" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="date_of_arriving" class="form-label">
                                Date of Arriving
                            </label>

                            <input type="date" name="date_of_arriving" id="date_of_arriving" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="location" class="form-label">
                                Location
                            </label>

                            <input type="text" name="location" id="location" class="form-control">

                        </div>


                        <div class="form-group col-md-3 mb-3">

                            <label for="customer_name" class="form-label">
                                Customer Name
                            </label>

                            <input type="text" name="customer_name" id="customer_name" class="form-control">

                        </div>

                    </div>


                    {{-- =========================
                    STATUS & DESCRIPTION
                ========================== --}}

                    <div class="row">

                        <div class="form-group col-md-6 mb-3">

                            <label for="status" class="form-label">
                                Status
                            </label>

                            <select name="status" id="status" class="form-select">

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

                                <option value="Sold">
                                    Sold
                                </option>

                            </select>

                        </div>


                        <div class="form-group col-md-6 mb-3">

                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>

                        </div>

                    </div>


                    {{-- SUBMIT --}}

                    <div class="col-md-12 mt-3">

                        <div class="d-md-flex d-grid align-items-center gap-3">

                            <button type="submit" class="btn btn-primary px-4">

                                Save Purchase

                            </button>

                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const buyingFee = document.getElementById('buying_fee');
        const towingFee = document.getElementById('towing_fee');
        const shipping = document.getElementById('shipping');

        const totalAed = document.getElementById('total_aed');
        const clearing = document.getElementById('clearing');
        const surcharge = document.getElementById('surcharge');

        const customDuty = document.getElementById('custom_duty');
        const grandTotal = document.getElementById('grand_total');

        const sellingPrice = document.getElementById('selling_price');
        const profit = document.getElementById('profit');


        // Exchange rate
        const exchangeRate = 3.675;


        function number(value) {
            return parseFloat(value) || 0;
        }


        function calculatePurchase() {

            // --------------------------------
            // 1. Calculate Total USD
            // --------------------------------

            const buying = number(buyingFee.value);
            const towing = number(towingFee.value);
            const ship = number(shipping.value);

            const totalUsd = buying + towing + ship;


            // --------------------------------
            // 2. USD → AED
            // --------------------------------

            const aed = totalUsd * exchangeRate;

            totalAed.value = aed.toFixed(2);


            // --------------------------------
            // 3. Custom Duty
            // --------------------------------

            const duty = (aed + 1472) * 0.055;

            customDuty.value = duty.toFixed(2);


            // --------------------------------
            // 4. Grand Total
            // --------------------------------

            const clearingValue = number(clearing.value);
            const surchargeValue = number(surcharge.value);

            const grand =
                aed +
                clearingValue +
                surchargeValue +
                duty;

            grandTotal.value = grand.toFixed(2);


            // --------------------------------
            // 5. Profit
            // --------------------------------

            const selling = number(sellingPrice.value);

            const calculatedProfit = selling - grand;

            profit.value = calculatedProfit.toFixed(2);
        }


        // Calculate whenever Admin changes a value
        buyingFee.addEventListener('input', calculatePurchase);
        towingFee.addEventListener('input', calculatePurchase);
        shipping.addEventListener('input', calculatePurchase);

        clearing.addEventListener('input', calculatePurchase);
        surcharge.addEventListener('input', calculatePurchase);

        sellingPrice.addEventListener('input', calculatePurchase);


        // Calculate once when page loads
        calculatePurchase();

    });
</script>


@endsection
