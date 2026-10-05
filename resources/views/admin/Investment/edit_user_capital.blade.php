@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User Capital</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit User Capital</h5>
                <form id="myForm" action="{{ route('update.user.capital') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $userCapital->id }}">

                    <div class="row">
                        <div class="form-group col-md-6">
    <label for="user_id" class="form-label">User Name</label>

    <select name="user_id" id="user_id" class="form-select">
        <option value="">Select User</option>

        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                {{ $user->id == $userCapital->user_id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>


                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">Capital amount</label>
                            <input type="text" name="amount" value="{{ $userCapital->amount }}" class="form-control" id="input1">
                        </div>
                    </div>

                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">Date</label>
                            <input type="date" name="date" value="{{ $userCapital->date }}" class="form-control" id="input1">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">Capital Description</label>
                            <textarea type="text" name="description" class="form-control" id="input1">{{ $userCapital->description }}</textarea>
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
            $('#myForm').validate({
                rules: {
                    user_id: {
                        required: true,
                    },
                    amount: {
                        required: true,
                    },
                    date: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },

                },
                messages: {
                    user_id: {
                        required: 'Please Select User',
                    },
                    amount: {
                        required: 'Please Enter Capital Amount',
                    },
                    date: {
                        required: 'Please Enter Date',
                    },
                    description: {
                        required: 'Please Enter Capital Description',
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
@endsection
