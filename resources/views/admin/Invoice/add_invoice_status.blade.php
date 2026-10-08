@extends('admin.admin_dashboard')

@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">

        <!--breadcrumb-->
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

                            Add Invoice Status

                        </li>

                    </ol>

                </nav>

            </div>

        </div>
        <!--end breadcrumb-->


        <div class="card">

            <div class="card-body p-4">

                <h5 class="mb-4">
                    Add Invoice Status
                </h5>


                <form id="myForm" action="{{ route('invoice.status.store') }}" method="post" class="row g-3">

                    @csrf


                    {{-- =====================================================
                         USER + PURCHASE
                    ====================================================== --}}

                    <div class="row">

                        {{-- USER --}}

                        <div class="form-group col-md-6">

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



                        {{-- PURCHASE --}}

                        <div class="form-group col-md-6">

                            <label for="purchase_id" class="form-label">
                                Purchase / Vehicle
                            </label>

                            <select name="purchase_id" id="purchase_id"
                                class="form-select @error('purchase_id') is-invalid @enderror">

                                <option value="">
                                    Select Purchase
                                </option>

                                @foreach ($purchases as $purchase)
                                    <option value="{{ $purchase->id }}" data-user="{{ $purchase->user_id }}"
                                        data-grand-total="{{ $purchase->grand_total ?? 0 }}"
                                        {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>

                                        #{{ $purchase->id }}

                                        -
                                        {{ $purchase->make ?? '' }}
                                        {{ $purchase->model ?? '' }}

                                        -

                                        {{ $purchase->vin ?? 'No VIN' }}

                                    </option>
                                @endforeach

                            </select>


                            {{-- Duplicate / Validation Error --}}

                            @error('purchase_id')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                    </div>



                    {{-- =====================================================
                         GRAND TOTAL + PAID AMOUNT
                    ====================================================== --}}

                    <div class="row">


                        {{-- GRAND TOTAL --}}

                        <div class="form-group col-md-6">

                            <label for="grand_total" class="form-label">

                                Grand Total

                            </label>


                            <input type="text" class="form-control" id="grand_total" placeholder="Select Purchase"
                                readonly>

                        </div>



                        {{-- PAID AMOUNT --}}

                        <div class="form-group col-md-6">

                            <label for="paid_amount" class="form-label">

                                Paid Amount

                            </label>


                            <input type="number" name="paid_amount" class="form-control" id="paid_amount" step="0.01"
                                min="0" placeholder="Enter Paid Amount">

                        </div>

                    </div>



                    {{-- =====================================================
                         REMAINING + DUE DATE
                    ====================================================== --}}

                    <div class="row">


                        {{-- REMAINING AMOUNT --}}

                        <div class="form-group col-md-6">

                            <label for="remaining_amount" class="form-label">

                                Remaining Amount

                            </label>


                            <input type="text" class="form-control" id="remaining_amount" placeholder="0.00" readonly>

                        </div>



                        {{-- DUE DATE --}}

                        <div class="form-group col-md-6">

                            <label for="due_date" class="form-label">

                                Due Date

                            </label>


                            <input type="date" name="due_date" class="form-control" id="due_date">

                        </div>

                    </div>



                    {{-- =====================================================
                         BUTTON
                    ====================================================== --}}

                    <div class="col-md-12">

                        <div class="d-md-flex d-grid align-items-center gap-3">

                            <button type="submit" class="btn btn-primary px-4">

                                Save Invoice Status

                            </button>

                        </div>

                    </div>


                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script>
        $(document).ready(function() {


            /*
            |--------------------------------------------------------------------------
            | USER SELECT
            |--------------------------------------------------------------------------
            */

            $('#user_id').on('change', function() {

                let userId = $(this).val();


                /*
                |--------------------------------------------------------------------------
                | Reset Purchase
                |--------------------------------------------------------------------------
                */

                $('#purchase_id').val('');

                $('#grand_total').val('');

                $('#paid_amount').val('');

                $('#remaining_amount').val('');


                /*
                |--------------------------------------------------------------------------
                | Filter Purchases By User
                |--------------------------------------------------------------------------
                */

                $('#purchase_id option').each(function() {

                    let option = $(this);

                    let purchaseUser = option.data('user');


                    /*
                    |--------------------------------------------------------------------------
                    | Default Option
                    |--------------------------------------------------------------------------
                    */

                    if (!option.val()) {

                        option.show();

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Show User Purchases
                    |--------------------------------------------------------------------------
                    */

                    if (userId && purchaseUser == userId) {

                        option.show();

                    } else {

                        option.hide();

                    }

                });

            });



            /*
            |--------------------------------------------------------------------------
            | PURCHASE SELECT
            |--------------------------------------------------------------------------
            */

            $('#purchase_id').on('change', function() {


                let selectedOption = $(this).find('option:selected');


                /*
                |--------------------------------------------------------------------------
                | Get Grand Total
                |--------------------------------------------------------------------------
                */

                let grandTotal = parseFloat(
                    selectedOption.attr('data-grand-total')
                ) || 0;


                /*
                |--------------------------------------------------------------------------
                | Show Grand Total
                |--------------------------------------------------------------------------
                */

                if (grandTotal > 0) {

                    $('#grand_total').val(
                        'AED ' + grandTotal.toFixed(2)
                    );

                } else {

                    $('#grand_total').val(
                        'AED 0.00'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Reset Paid Amount
                |--------------------------------------------------------------------------
                */

                $('#paid_amount').val('');


                /*
                |--------------------------------------------------------------------------
                | Show Initial Remaining
                |--------------------------------------------------------------------------
                */

                $('#remaining_amount').val(
                    'AED ' + grandTotal.toFixed(2)
                );

            });



            /*
            |--------------------------------------------------------------------------
            | PAID AMOUNT
            |--------------------------------------------------------------------------
            */

            $('#paid_amount').on('input', function() {


                let paidAmount = parseFloat($(this).val()) || 0;


                let selectedOption = $('#purchase_id')
                    .find('option:selected');


                let grandTotal = parseFloat(
                    selectedOption.attr('data-grand-total')
                ) || 0;


                /*
                |--------------------------------------------------------------------------
                | Prevent Paid Amount Greater Than Grand Total
                |--------------------------------------------------------------------------
                */

                if (paidAmount > grandTotal) {

                    $(this).val(grandTotal);

                    paidAmount = grandTotal;

                }


                /*
                |--------------------------------------------------------------------------
                | Calculate Remaining
                |--------------------------------------------------------------------------
                */

                let remaining = grandTotal - paidAmount;


                /*
                |--------------------------------------------------------------------------
                | Prevent Negative Remaining
                |--------------------------------------------------------------------------
                */

                if (remaining < 0) {

                    remaining = 0;

                }


                /*
                |--------------------------------------------------------------------------
                | Show Remaining
                |--------------------------------------------------------------------------
                */

                $('#remaining_amount').val(

                    'AED ' + remaining.toFixed(2)

                );

            });



            /*
            |--------------------------------------------------------------------------
            | FORM VALIDATION
            |--------------------------------------------------------------------------
            */



        });
    </script>
@endsection
