@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    @php
        $amountAed = old('amount', \App\Support\Currency::usdToAed($expense->amount));
    @endphp

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Expense </li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-2">Edit Expense</h5>
                <p class="text-muted small mb-4">
                    Enter amount in <strong>AED</strong>. System converts to
                    <strong>USD $</strong> (× {{ number_format(\App\Support\Currency::rate(), 3) }})
                    and deducts from the selected admin or client capital.
                </p>
                <form id="myForm" action="{{ route('update.expense') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $expense->id }}" />
                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="user_id" class="form-label">User Name (Admin / Client)</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (int) old('user_id', $expense->user_id) === (int) $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                    @if ($user->role === 'admin') (Admin) @else (Client) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="expense_name" class="form-label">Expense Name</label>
                        <input type="text" name="expense_name" value="{{ old('expense_name', $expense->expense_name) }}" class="form-control" id="expense_name">
                    </div>
                </div>

                <div class="row">
                     <div class="form-group col-md-6">
                        <label for="amount" class="form-label">Amount (AED)</label>
                        <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ $amountAed }}" id="amount">
                    </div>
                     <div class="form-group col-md-6">
                        <label for="amount_usd" class="form-label">Amount (USD $)</label>
                        <input type="text" class="form-control" id="amount_usd" readonly>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="description" class="form-label">expense Description</label>
                        <textarea name="description" class="form-control" id="description">{{ old('description', $expense->description) }}</textarea>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="date" class="form-label">expense Date</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $expense->date) }}" id="date">
                    </div>

                </div>

                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var rate = {{ \App\Support\Currency::rate() }};

            function updateUsd() {
                var aed = parseFloat($('#amount').val()) || 0;
                var usd = rate > 0 ? (aed / rate) : 0;
                $('#amount_usd').val(aed > 0 ? '$' + usd.toFixed(2) : '');
            }

            $('#amount').on('input', updateUsd);
            updateUsd();

            $('#myForm').validate({
                rules: {
                    user_id: {
                        required: true,
                    },
                    expense_name: {
                        required: true,
                    },
                    amount: {
                        required: true,
                    },
                    description: {
                        required: true
                    },
                    date: {
                        required: true,
                    }
                },
                messages: {
                    user_id: {
                        required: 'Please Select User',
                    },
                    amount: {
                        required: 'Please Enter Expense Amount (AED)',
                        number: 'Please Enter a valid number'
                    },
                    description: {
                        required: 'Please Enter Expense Description'
                    },
                    date: {
                        required: 'Please Enter Expense Date',
                        date: 'Please Enter a valid date'
                    },
                    expense_name: {
                        required: 'Please Enter expense_name',
                    }
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


@endsection
