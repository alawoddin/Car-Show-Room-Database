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
                        <li class="breadcrumb-item active" aria-current="page">Add Client</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add Client</h5>
                {{-- <form id="myForm" action="{{ route('store.category') }}" method="post" class="row g-3" enctype="multipart/form-data"> --}}
                @csrf

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Client Name</label>
                        <input type="text" name="name" class="form-control" id="input1">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Client email</label>
                        <input type="text" name="email" class="form-control" id="input1">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Client phone</label>
                        <input type="text" name="phone" class="form-control" id="input1">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Client address</label>
                        <input type="text" name="address" class="form-control" id="input1">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Client password</label>
                        <input type="text" name="password" class="form-control" id="input1">
                    </div>




                    <div class="form-group col-md-6">
                        <label for="input2" class="form-label">Client Image </label>
                        <input class="form-control" name="photo" type="file" id="image">
                    </div>

                </div>

                <div class="col-md-6 mt-3 mb-3">
                    <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Admin"
                        class="rounded-circle p-1 bg-primary" width="80">

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
                    name: {
                        required: true,
                    },
                    photo: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: 'Please Enter Client Name',
                    },
                    photo: {
                        required: 'Please Select Client Image',
                    },
                    email: {
                        required: 'Please Enter Client Email',
                    },
                    address: {
                        required: 'Please Enter Client Address',
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
