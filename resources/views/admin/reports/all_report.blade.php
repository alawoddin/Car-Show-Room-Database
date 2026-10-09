@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Start Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                        <h4 class="mb-sm-0">Reports</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);">Reports</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Reports
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Page Title -->


            <div class="row">

                <!-- Search By Date -->
                <div class="col-sm-4">
                    <div class="card">

                        <div class="card-header">
                            <h4 class="card-title mb-0">Search By Date</h4>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('search.by.date') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="date" class="form-label">
                                        Select Date
                                    </label>

                                    <input class="form-control" type="date" name="date" id="date" required>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        Search
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <!-- End Search By Date -->


                <!-- Search By Month -->
                <div class="col-sm-4">
                    <div class="card">

                        <div class="card-header">
                            <h4 class="card-title mb-0">Search By Month</h4>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('search.by.month') }}" method="POST">
                                @csrf

                                <!-- Select Month -->
                                <div class="mb-3">
                                    <label for="month" class="form-label">Select Month</label>

                                    <select name="month" id="month" class="form-select" required>
                                        <option value="">Select Month</option>

                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}">
                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Select Year -->
                                <div class="mb-3">
                                    <label for="year" class="form-label">Select Year</label>

                                    <select name="year" id="year" class="form-select" required>
                                        <option value="">Select Year</option>

                                        @for ($y = now()->year; $y >= 2022; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        Search
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <!-- End Search By Month -->


                <!-- Search By Year -->
                <div class="col-sm-4">
                    <div class="card">

                        <div class="card-header">
                            <h4 class="card-title mb-0">Search By Year</h4>
                        </div>

                        <div class="card-body">

                            {{-- <form action="{{ route('admin.search.byyear') }}" method="POST"> --}}
                            @csrf

                            <div class="mb-3">
                                <label for="year" class="form-label">
                                    Select Year
                                </label>

                                <select name="year" id="year" class="form-select" required>
                                    <option value="">Select Year</option>

                                    @for ($year = now()->year; $year >= 2022; $year--)
                                        <option value="{{ $year }}">
                                            {{ $year }}
                                        </option>
                                    @endfor

                                </select>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>
                            </div>

                            </form>

                        </div>
                    </div>
                </div>
                <!-- End Search By Year -->

            </div>

        </div>
    </div>
@endsection
