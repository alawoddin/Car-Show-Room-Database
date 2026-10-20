@extends('client.client_dashboard')

@section('client')

<div class="setting-body">

    <div class="d-flex align-items-center justify-content-between pb-4">

        <div>
            <h3 class="fs-17 font-weight-semi-bold mb-1">
                My Purchased Vehicles
            </h3>

            <p class="text-muted mb-0">
                View your purchased vehicles and complete purchase information.
            </p>
        </div>

    </div>


    <div class="table-responsive">

        <table id="example"
               class="table table-bordered table-hover"
               style="width:100%">

            <thead>

                <tr>
                    <th>#</th>
                    <th>VIN</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Shared With</th>
                    <th>My Share</th>
                    <th>Color</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                @forelse($vehicles as $key => $vehicle)
                    @php
                        $myShare = $vehicle->buyerShares()
                            ->first(fn ($share) => (int) $share['user']->id === (int) Auth::id());
                    @endphp

                    <tr>

                        <td>
                            {{ $key + 1 }}
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
                            {{ $vehicle->buyerNames() }}
                        </td>

                        <td>
                            ${{ number_format((float) ($myShare['amount'] ?? $vehicle->grand_total), 2) }}
                        </td>

                        <td>
                            {{ $vehicle->color ?? '-' }}
                        </td>

                        <td>
                            {{ $vehicle->location ?? '-' }}
                        </td>

                        <td>
                            {{ $vehicle->status }}
                        </td>

                        <td>

                            <a href="{{ route('client.vehicle.view', $vehicle->id) }}"
                               class="btn theme-btn btn-sm">

                                <i class="la la-eye"></i>
                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="10" class="text-center py-4">

                            No purchased vehicles found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection