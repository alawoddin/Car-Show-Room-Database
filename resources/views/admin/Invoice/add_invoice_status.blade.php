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


                <form id="myForm"
                      action="{{ route('invoice.status.store') }}"
                      method="post"
                      class="row g-3">

                    @csrf


                    {{-- =====================================================
                         USER
                    ====================================================== --}}

                    <div class="row">

                        <div class="form-group col-md-6">

                            <label for="user_id" class="form-label">
                                User Name
                            </label>

                            <select name="user_id"
                                    id="user_id"
                                    class="form-select">

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


                        {{-- =================================================
                             PURCHASE
                        ================================================== --}}

                        <div class="form-group col-md-6">

                            <label for="purchase_id" class="form-label">
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
                                            data-user="{{ $purchase->user_id }}">

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
                         AMOUNT + DUE DATE
                    ====================================================== --}}

                    <div class="row">

                        <div class="form-group col-md-6">

                            <label for="amount" class="form-label">
                                Invoice Amount
                            </label>

                            <input type="number"
                                   name="amount"
                                   class="form-control"
                                   id="amount"
                                   step="0.01"
                                   min="0"
                                   placeholder="Enter Invoice Amount">

                        </div>


                        <div class="form-group col-md-6">

                            <label for="due_date" class="form-label">
                                Due Date
                            </label>

                            <input type="date"
                                   name="due_date"
                                   class="form-control"
                                   id="due_date">

                        </div>

                    </div>



                    {{-- =====================================================
                         STATUS + DESCRIPTION
                    ====================================================== --}}

                    <div class="row">

                        <div class="form-group col-md-6">

                            <label for="status" class="form-label">
                                Invoice Status
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select">

                                <option value="">
                                    Select Status
                                </option>

                                <option value="Open">
                                    Open
                                </option>

                                <option value="Paid">
                                    Paid
                                </option>

                                <option value="Overdue">
                                    Overdue
                                </option>

                            </select>

                        </div>


                    

                    </div>



                    {{-- =====================================================
                         BUTTON
                    ====================================================== --}}

                    <div class="col-md-12">

                        <div class="d-md-flex d-grid align-items-center gap-3">

                            <button type="submit"
                                    class="btn btn-primary px-4">

                                Save Invoice Status

                            </button>

                        </div>

                    </div>


                </form>

            </div>

        </div>

    </div>




@endsection