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
                        <li class="breadcrumb-item active" aria-current="page">Add Expense </li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add Expense</h5>
                <form id="myForm" action="{{ route('store.expense') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">expense_name</label>
                        <input type="text" name="expense_name" class="form-control" id="expense_name">
                    </div>

                     <div class="form-group col-md-6">
                        <label for="input1" class="form-label">expense amount</label>
                        <input type="text" name="amount" class="form-control" id="amount">
                    </div>


                    
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">expense Description</label>
                        <textarea type="text" name="description" class="form-control" id="description"></textarea>
                    </div>



                    <div class="form-group col-md-6 mb-3">
                        <label for="input1" class="form-label">expense Date</label>
                        <input type="date" name="date" class="form-control" id="date">
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
                    amount: {
                        required: 'Please Enter Expense Amount',
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
                        date: 'Please Enter a valid date'
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
