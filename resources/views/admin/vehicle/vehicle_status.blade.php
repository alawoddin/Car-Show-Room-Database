@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Vehicle Status</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->





        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th>User</th>
                                <th>VIN</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>

                            @forelse($vehicles as $key => $vehicle)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        {{ $vehicle->user->name ?? 'Demo' }}
                                    </td>

                                    <td>
                                        {{ $vehicle->vin }}
                                    </td>

                                    <td>
                                        {{ $vehicle->make }}
                                    </td>

                                    <td>
                                        {{ $vehicle->model }}
                                    </td>

                                    <td>
                                        {{ $vehicle->location ?? '-' }}
                                    </td>

                                    <td>

                                        <span class="badge bg-primary">
                                            {{ $vehicle->status }}
                                        </span>

                                    </td>

                                    <td>

                                        <a href="#" class="btn btn-sm btn-info">
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center">

                                        No vehicles found.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>
            </div>
        </div>




    </div>
@endsection
