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
                        <li class="breadcrumb-item active" aria-current="page">Add Invoice</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-2">Add Invoice</h5>
                <p class="text-muted small mb-4">
                    Select <strong>User</strong> (Admin / Client), then enter chassis number, lot number, amount, and date.
                    When paid later, click the <strong>Paid</strong> button on the invoice list.
                </p>

                <form id="myForm" action="{{ route('invoice.status.store') }}" method="post" class="row g-3">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="user_id" class="form-label">User Name (Admin / Client)</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                value="{{ old('cs_number') }}" placeholder="e.g. 12e34r45">
                            @error('cs_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="lot_number" class="form-label">Lot Number</label>
                            <input type="text" name="lot_number" id="lot_number" class="form-control"
                                value="{{ old('lot_number') }}" placeholder="e.g. 12345">
                            @error('lot_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount (AED)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                                class="form-control" value="{{ old('amount') }}" placeholder="e.g. 3000">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="due_date" class="form-label">Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control"
                                value="{{ old('due_date', now()->toDateString()) }}">
                            @error('due_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary px-4">Save Invoice</button>
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
                messages: {
                    user_id: { required: 'Please select user' },
                    cs_number: { required: 'Please enter Chassis Number' },
                    lot_number: { required: 'Please enter Lot Number' },
                    amount: { required: 'Please enter amount' },
                    due_date: { required: 'Please enter date' }
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
