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

                        <li class="breadcrumb-item">

                            <a href="{{ route('invoice.status') }}">

                                Invoice Status

                            </a>

                        </li>

                        <li class="breadcrumb-item active">

                            Edit Invoice Status

                        </li>

                    </ol>

                </nav>

            </div>

        </div>
        <!-- End breadcrumb -->


        <div class="card">

            <div class="card-body p-4">

                <h5 class="mb-4">

                    Edit Invoice Status

                </h5>


                <form id="myForm"
                      action="{{ route('invoice.status.update') }}"
                      method="POST"
                      class="row g-3">

                    @csrf


                    {{-- Invoice ID --}}

                    <input type="hidden"
                           name="id"
                           value="{{ $invoiceStatus->id }}">



                    {{-- =====================================================
                         USER + PURCHASE
                    ====================================================== --}}

                    <div class="row">


                        {{-- USER --}}

                        <div class="form-group col-md-6">

                            <label for="user_id"
                                   class="form-label">

                                User Name

                            </label>


                            <select name="user_id"
                                    id="user_id"
                                    class="form-select">

                                <option value="">

                                    Select User

                                </option>


                                @foreach ($users as $user)

                                    <option value="{{ $user->id }}"
                                        {{ $invoiceStatus->user_id == $user->id ? 'selected' : '' }}>

                                        {{ $user->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        {{-- PURCHASE --}}

                        <div class="form-group col-md-6">

                            <label for="purchase_id"
                                   class="form-label">

                                Purchase / Vehicle

                            </label>


                            <select name="purchase_id"
                                    id="purchase_id"
                                    class="form-select">

                                <option value="">

                                    Select Purchase

                                </option>


                                @foreach ($purchases as $purchase)

                                    <option value="{{ $purchase->id }}"
                                            data-user="{{ $purchase->user_id }}"
                                            data-grand-total="{{ $purchase->grand_total ?? 0 }}"

                                        {{ $invoiceStatus->purchase_id == $purchase->id ? 'selected' : '' }}>

                                        #{{ $purchase->id }}

                                        -

                                        {{ $purchase->make ?? '' }}

                                        {{ $purchase->model ?? '' }}

                                        -

                                        {{ $purchase->vin ?? 'No VIN' }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>



                    {{-- =====================================================
                         GRAND TOTAL + PAID
                    ====================================================== --}}

                    <div class="row">


                        {{-- GRAND TOTAL --}}

                        <div class="form-group col-md-6">

                            <label for="grand_total"
                                   class="form-label">

                                Grand Total

                            </label>


                            <input type="text"
                                   class="form-control"
                                   id="grand_total"
                                   value="AED {{ number_format($invoiceStatus->purchase->grand_total ?? 0, 2) }}"
                                   readonly>

                        </div>



                        {{-- PAID AMOUNT --}}

                        <div class="form-group col-md-6">

                            <label for="paid_amount"
                                   class="form-label">

                                Paid Amount

                            </label>


                            <input type="number"
                                   name="paid_amount"
                                   id="paid_amount"
                                   class="form-control"
                                   value="{{ $invoiceStatus->paid_amount }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="Enter Paid Amount">

                        </div>

                    </div>



                    {{-- =====================================================
                         REMAINING + DUE DATE
                    ====================================================== --}}

                    <div class="row">


                        {{-- REMAINING --}}

                        <div class="form-group col-md-6">

                            <label for="remaining_amount"
                                   class="form-label">

                                Remaining Amount

                            </label>


                            @php

                                $grandTotal =
                                    $invoiceStatus->purchase->grand_total ?? 0;

                                $paidAmount =
                                    $invoiceStatus->paid_amount ?? 0;

                                $remaining =
                                    max(0, $grandTotal - $paidAmount);

                            @endphp


                            <input type="text"
                                   class="form-control"
                                   id="remaining_amount"
                                   value="AED {{ number_format($remaining, 2) }}"
                                   readonly>

                        </div>



                        {{-- DUE DATE --}}

                        <div class="form-group col-md-6">

                            <label for="due_date"
                                   class="form-label">

                                Due Date

                            </label>


                            <input type="date"
                                   name="due_date"
                                   id="due_date"
                                   class="form-control"
                                   value="{{ $invoiceStatus->due_date }}">

                        </div>

                    </div>



                    {{-- =====================================================
                         CURRENT STATUS
                    ====================================================== --}}

                    <div class="row">

                        <div class="form-group col-md-6">

                            <label class="form-label">

                                Current Status

                            </label>


                            <div>

                                @if ($invoiceStatus->status == 'Paid')

                                    <span class="badge bg-success fs-6">

                                        Paid

                                    </span>

                                @elseif ($invoiceStatus->status == 'Open')

                                    <span class="badge bg-warning text-dark fs-6">

                                        Open

                                    </span>

                                @elseif ($invoiceStatus->status == 'Overdue')

                                    <span class="badge bg-danger fs-6">

                                        Overdue

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- =====================================================
                         BUTTONS
                    ====================================================== --}}

                    <div class="col-md-12">

                        <div class="d-md-flex d-grid align-items-center gap-3">

                            <button type="submit"
                                    class="btn btn-primary px-4">

                                Update Invoice Status

                            </button>


                            <a href="{{ route('invoice.status') }}"
                               class="btn btn-secondary px-4">

                                Cancel

                            </a>

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
            | Filter Purchases When User Changes
            |--------------------------------------------------------------------------
            */

            $('#user_id').on('change', function() {

                let userId = $(this).val();


                $('#purchase_id').val('');

                $('#grand_total').val('');

                $('#paid_amount').val('');

                $('#remaining_amount').val('');


                $('#purchase_id option').each(function() {

                    let option = $(this);

                    let purchaseUser = option.data('user');


                    if (!option.val()) {

                        option.show();

                        return;

                    }


                    if (userId && purchaseUser == userId) {

                        option.show();

                    } else {

                        option.hide();

                    }

                });

            });



            /*
            |--------------------------------------------------------------------------
            | Purchase Change
            |--------------------------------------------------------------------------
            */

            $('#purchase_id').on('change', function() {

                let selectedOption =
                    $(this).find('option:selected');


                let grandTotal = parseFloat(
                    selectedOption.attr('data-grand-total')
                ) || 0;


                $('#grand_total').val(
                    'AED ' + grandTotal.toFixed(2)
                );


                $('#paid_amount').val('');


                $('#remaining_amount').val(
                    'AED ' + grandTotal.toFixed(2)
                );

            });



            /*
            |--------------------------------------------------------------------------
            | Paid Amount Change
            |--------------------------------------------------------------------------
            */

            $('#paid_amount').on('input', function() {

                let paidAmount =
                    parseFloat($(this).val()) || 0;


                let selectedOption =
                    $('#purchase_id option:selected');


                let grandTotal = parseFloat(
                    selectedOption.attr('data-grand-total')
                ) || 0;


                if (paidAmount > grandTotal) {

                    $(this).val(grandTotal);

                    paidAmount = grandTotal;

                }


                let remaining =
                    grandTotal - paidAmount;


                if (remaining < 0) {

                    remaining = 0;

                }


                $('#remaining_amount').val(

                    'AED ' + remaining.toFixed(2)

                );

            });



            

        });

    </script>

@endsection