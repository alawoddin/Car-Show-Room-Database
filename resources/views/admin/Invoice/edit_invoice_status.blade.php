@extends('admin.admin_dashboard')

@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('invoice.status') }}">Invoice Status</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Invoice</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-2">Edit Invoice</h5>
                <p class="text-muted small mb-4">
                    Update user, chassis number, lot number, amount, or date.
                    To mark payment received, use the <strong>Paid</strong> button.
                </p>

                <form id="myForm" action="{{ route('invoice.status.update') }}" method="POST" class="row g-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $invoiceStatus->id }}">

                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="user_id" class="form-label">User Name (Admin / Client)</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ (int) old('user_id', $invoiceStatus->user_id) === (int) $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                        @if ($user->role === 'admin') (Admin) @else (Client) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="cs_number" class="form-label">Chassis Number</label>
                            <input type="text" name="cs_number" id="cs_number" class="form-control"
                                value="{{ old('cs_number', $invoiceStatus->cs_number) }}">
                            @error('cs_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="lot_number" class="form-label">Lot Number</label>
                            <input type="text" name="lot_number" id="lot_number" class="form-control"
                                value="{{ old('lot_number', $invoiceStatus->lot_number) }}">
                            @error('lot_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount (AED)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                                class="form-control"
                                value="{{ old('amount', $invoiceStatus->amount ?: $invoiceStatus->invoiceTotal()) }}">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="due_date" class="form-label">Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control"
                                value="{{ old('due_date', $invoiceStatus->due_date) }}">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="{{ $invoiceStatus->status }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Update Invoice</button>
                        @if ($invoiceStatus->status !== 'Paid')
                            <a href="{{ route('invoice.status.paid', $invoiceStatus->id) }}"
                                class="btn btn-success px-4"
                                onclick="return confirm('Mark this invoice as Paid?');">
                                Mark as Paid
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    user_id: { required: true },
                    cs_number: { required: true },
                    lot_number: { required: true },
                    amount: { required: true, number: true, min: 0.01 },
                    due_date: { required: true }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
