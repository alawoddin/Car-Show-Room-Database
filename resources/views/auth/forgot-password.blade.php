<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('backend/assets/images/favicon-32x32.png') }}" type="image/png" />
    <link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('backend/assets/js/pace.min.js') }}"></script>
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">
    <title>Forgot Password</title>
</head>

<body>
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card my-5 my-lg-0 shadow-none border">
                            <div class="card-body">
                                <form action="{{ route('password.email') }}" method="POST">
                                    @csrf
                                    <div class="p-4">
                                        <div class="text-center">
                                            <img src="{{ asset('backend/assets/images/icons/forgot-2.png') }}" width="100" alt="" />
                                        </div>

                                        <h4 class="mt-5 font-weight-bold">Forgot Password?</h4>
                                        <p class="text-muted">
                                            Enter your registered email. Admin and client accounts use the same reset link.
                                        </p>

                                        @if (session('status'))
                                            <div class="alert alert-success">{{ session('status') }}</div>
                                        @endif

                                        <div class="my-4">
                                            <label class="form-label">Email</label>
                                            <input
                                                type="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="example@user.com"
                                                required
                                                autofocus
                                            />
                                            @error('email')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                Email Password Reset Link
                                            </button>
                                            <a href="{{ route('login') }}" class="btn btn-light">
                                                <i class="bx bx-arrow-back me-1"></i> Back to Login
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
</body>

</html>
