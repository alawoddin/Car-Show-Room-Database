@extends('admin.admin_dashboard')

@section('admin')
    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>

    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('customer.loan') }}">Customer Loan</a>
                        </li>
                        <li class="breadcrumb-item active">Add Loan</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-2">Add Customer Loan</h5>
                <p class="text-muted mb-4">
                    For sold cars when the customer pays in parts (first pay, second pay, last pay). Remaining updates automatically.
                </p>

                <form id="myForm" action="{{ route('customer.loan.store') }}" method="post" class="row g-3">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="user_id" class="form-label">User Name</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="purchase_id" class="form-label">Sold Vehicle</label>
                            <select name="purchase_id" id="purchase_id"
                                class="form-select @error('purchase_id') is-invalid @enderror" required>
                                <option value="">Select Sold Car</option>
                                @foreach ($purchases as $purchase)
                                    @php
                                        $salePrice = (float) (($purchase->selling_price > 0)
                                            ? $purchase->selling_price
                                            : $purchase->grand_total);
                                    @endphp
                                    <option value="{{ $purchase->id }}"
                                        data-user="{{ $purchase->user_id }}"
                                        data-sale-price="{{ $salePrice }}"
                                        {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                                        #{{ $purchase->id }} -
                                        {{ $purchase->customer_name ? $purchase->customer_name.' - ' : '' }}
                                        {{ $purchase->make ?? '' }} {{ $purchase->model ?? '' }} -
                                        {{ $purchase->vin ?? 'No VIN' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('purchase_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sale_price" class="form-label">Sale Price (Total)</label>
                            <input type="text" class="form-control" id="sale_price" placeholder="Select Vehicle" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="paid_amount" class="form-label">First Payment (optional)</label>
                            <input type="number" name="paid_amount" class="form-control" id="paid_amount"
                                step="0.01" min="0" placeholder="e.g. 2000"
                                value="{{ old('paid_amount') }}">
                            @error('paid_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input type="text" class="form-control" id="remaining_amount" placeholder="0.00" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control" id="due_date"
                                value="{{ old('due_date') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="payment_note" class="form-label">First Payment Note</label>
                            <input type="text" name="payment_note" class="form-control" id="payment_note"
                                placeholder="e.g. first pay, cash"
                                value="{{ old('payment_note') }}">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Customer Loan</button>
                            <a href="{{ route('customer.loan') }}" class="btn btn-light px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            function updateAmounts() {
                var option = $('#purchase_id option:selected');
                var sale = parseFloat(option.data('sale-price')) || 0;
                var paid = parseFloat($('#paid_amount').val()) || 0;
                var userId = option.data('user');

                if (sale > 0) {
                    $('#sale_price').val('AED ' + sale.toFixed(2));
                    $('#remaining_amount').val('AED ' + Math.max(0, sale - paid).toFixed(2));
                } else {
                    $('#sale_price').val('');
                    $('#remaining_amount').val('');
                }

                if (userId && !$('#user_id').val()) {
                    $('#user_id').val(userId);
                }
            }

            $('#purchase_id').on('change', updateAmounts);
            $('#paid_amount').on('input', updateAmounts);
            updateAmounts();
        })();
    </script>
@endsection
