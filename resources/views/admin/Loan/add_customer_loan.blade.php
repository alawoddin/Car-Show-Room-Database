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
                    Select admin or client first — then only their sold cars are shown.
                </p>

                <form action="{{ route('customer.loan.store') }}" method="post" class="row g-3">
                    @csrf

                    <div class="form-group col-md-6">
                        <label for="user_id" class="form-label">Owner / Client</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                    @if ($user->role === 'admin') (Admin) @else (Client) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="purchase_id" class="form-label">Sold Vehicle</label>
                        <select name="purchase_id" id="purchase_id"
                            class="form-select @error('purchase_id') is-invalid @enderror" required>
                            <option value="">Select user first</option>
                            @foreach ($purchases as $purchase)
                                @php
                                    $salePrice = (float) (($purchase->selling_price > 0)
                                        ? $purchase->selling_price
                                        : $purchase->grand_total);
                                    $ownerIds = implode(',', $purchase->selectedUserIds());
                                @endphp
                                <option value="{{ $purchase->id }}"
                                    data-owners="{{ $ownerIds }}"
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
                        <small id="no_cars_hint" class="text-muted d-none">
                            No sold cars found for this user (without an existing loan).
                        </small>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="sale_price" class="form-label">Sale Price (Total)</label>
                        <input type="text" class="form-control" id="sale_price" readonly
                            placeholder="Select Vehicle">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="paid_amount" class="form-label">First Payment (optional)</label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                            step="0.01" min="0" placeholder="e.g. 2000"
                            value="{{ old('paid_amount') }}">
                        @error('paid_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="remaining_amount" class="form-label">Remaining</label>
                        <input type="text" class="form-control" id="remaining_amount" readonly
                            placeholder="0.00">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                            value="{{ old('due_date') }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="payment_note" class="form-label">First Payment Note</label>
                        <input type="text" name="payment_note" id="payment_note" class="form-control"
                            placeholder="e.g. first pay, cash"
                            value="{{ old('payment_note') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Save Customer Loan</button>
                        <a href="{{ route('customer.loan') }}" class="btn btn-light px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var $user = $('#user_id');
            var $purchase = $('#purchase_id');
            var $hint = $('#no_cars_hint');
            var allCars = [];

            $purchase.find('option').each(function () {
                var $opt = $(this);
                if (!$opt.val()) {
                    return;
                }
                allCars.push({
                    value: $opt.attr('value'),
                    text: $opt.text(),
                    owners: String($opt.data('owners') || '').split(',').map(function (id) {
                        return String(id).trim();
                    }).filter(Boolean),
                    salePrice: parseFloat($opt.data('sale-price')) || 0
                });
            });

            function filterCars() {
                var userId = String($user.val() || '');
                var previous = $purchase.val();
                var matches = [];

                $purchase.empty();

                if (!userId) {
                    $purchase.append($('<option>', { value: '', text: 'Select user first' }));
                    $hint.addClass('d-none');
                    updateAmounts();
                    return;
                }

                matches = allCars.filter(function (car) {
                    return car.owners.indexOf(userId) !== -1;
                });

                if (matches.length === 0) {
                    $purchase.append($('<option>', { value: '', text: 'No sold cars for this user' }));
                    $hint.removeClass('d-none');
                    updateAmounts();
                    return;
                }

                $hint.addClass('d-none');
                $purchase.append($('<option>', { value: '', text: 'Select Sold Car' }));

                matches.forEach(function (car) {
                    $purchase.append(
                        $('<option>', {
                            value: car.value,
                            text: car.text,
                            'data-sale-price': car.salePrice
                        })
                    );
                });

                if (previous && $purchase.find('option[value="' + previous + '"]').length) {
                    $purchase.val(previous);
                }

                updateAmounts();
            }

            function updateAmounts() {
                var option = $purchase.find('option:selected');
                var sale = parseFloat(option.data('sale-price')) || 0;
                var paid = parseFloat($('#paid_amount').val()) || 0;

                if (sale > 0 && option.val()) {
                    $('#sale_price').val('AED ' + sale.toFixed(2));
                    $('#remaining_amount').val('AED ' + Math.max(0, sale - paid).toFixed(2));
                } else {
                    $('#sale_price').val('');
                    $('#remaining_amount').val('');
                }
            }

            $user.on('change', filterCars);
            $purchase.on('change', updateAmounts);
            $('#paid_amount').on('input', updateAmounts);
            filterCars();
        })();
    </script>
@endsection
