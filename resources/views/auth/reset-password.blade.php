<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('backend/assets/images/favicon-32x32.png') }}" type="image/png" />
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">
    <title>Reset Password</title>
</head>

<body>
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card my-5 my-lg-0 shadow-none border">
                            <div class="card-body">
                                <form method="POST" action="{{ route('password.store') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <div class="p-4">
                                        <div class="text-center mb-3">
                                            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" width="60" alt="">
                                        </div>

                                        <h4 class="font-weight-bold">Reset Password</h4>
                                        <p class="text-muted">Choose a new password for your admin or client account.</p>

                                        <div class="my-3">
                                            <label class="form-label">Email</label>
                                            <input
                                                type="email"
                                                name="email"
                                                value="{{ old('email', $request->email) }}"
                                                class="form-control @error('email') is-invalid @enderror"
                                                required
                                            />
                                            @error('email')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="my-3">
                                            <label class="form-label">New Password</label>
                                            <input
                                                type="password"
                                                name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required
                                                autocomplete="new-password"
                                            />
                                            @error('password')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="my-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input
                                                type="password"
                                                name="password_confirmation"
                                                class="form-control"
                                                required
                                                autocomplete="new-password"
                                            />
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Reset Password</button>
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
</body>

</html>
