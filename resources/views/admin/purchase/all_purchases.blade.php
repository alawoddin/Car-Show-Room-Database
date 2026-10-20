@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    {{-- Breadcrumb and Action Buttons --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">

                    <li class="breadcrumb-item">
                        <a href="javascript:;">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        All Purchases
                    </li>

                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">

                {{-- Export Excel --}}
                <a href="{{ route('purchases.export') }}"
                   class="btn btn-success">

                    <i class="bx bx-download"></i>
                    Export Excel

                </a>

                {{-- Import Excel --}}
                <button
                    type="button"
                    class="btn btn-info"
                    data-bs-toggle="modal"
                    data-bs-target="#importExcelModal">

                    <i class="bx bx-upload"></i>
                    Import Excel

                </button>

                {{-- Add Purchase --}}
                <a href="{{ route('add.purchase') }}"
                   class="btn btn-primary">

                    <i class="bx bx-plus"></i>
                    Add Purchase

                </a>

            </div>
        </div>

    </div>

    {{-- Success / Error Messages --}}
    @if (session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('message') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Please check the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>
    @endif

    {{-- Purchases Table --}}
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Vehicle Purchases</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="example"
                       class="table table-striped table-bordered align-middle"
                       style="width:100%">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Buying Date</th>
                            <th>Lot Number</th>
                            <th>VIN</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Cylinder</th>
                            <th>Container Number</th>
                            <th>Arrival Date</th>
                            <th>Buying Fee</th>
                            <th>Towing Fee</th>
                            <th>Shipping</th>
                            <th>Grand Total (AED)</th>
                            <th>Selling Price</th>
                            <th>Profit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($purchases as $key => $item)

                            <tr>

                                {{-- Serial Number --}}
                                <td>{{ $key + 1 }}</td>

                                {{-- User Name --}}
                                <td>
                                    {{ $item->user?->name ?? 'Admin Inventory' }}
                                </td>

                                {{-- Buying Date --}}
                                <td>
                                    {{ $item->buying_date
                                        ? \Carbon\Carbon::parse($item->buying_date)->format('d M Y')
                                        : 'N/A' }}
                                </td>

                                {{-- Lot Number --}}
                                <td>
                                    {{ $item->lot_number ?? 'N/A' }}
                                </td>

                                {{-- VIN Link --}}
                                <td>
                                    @if (!empty($item->vin_link))
                                        <a href="{{ $item->vin_link }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           title="Open vehicle details">

                                            {{ $item->vin ?? 'View VIN' }}

                                            <i class="bx bx-link-external"></i>

                                        </a>
                                    @else
                                        {{ $item->vin ?? 'N/A' }}
                                    @endif
                                </td>

                                {{-- Make --}}
                                <td>{{ $item->make ?? 'N/A' }}</td>

                                {{-- Model --}}
                                <td>{{ $item->model ?? 'N/A' }}</td>

                                {{-- Cylinder --}}
                                <td>{{ $item->cylinder ?? 'N/A' }}</td>

                                {{-- Container Number --}}
                                <td>{{ $item->container_number ?? 'N/A' }}</td>

                                {{-- Arrival Date --}}
                                <td>
                                    {{ $item->arrival_date
                                        ? \Carbon\Carbon::parse($item->arrival_date)->format('d M Y')
                                        : 'N/A' }}
                                </td>

                                {{-- Buying Fee --}}
                                <td>
                                    {{ number_format((float) ($item->buying_fee ?? 0), 2) }}
                                </td>

                                {{-- Towing Fee --}}
                                <td>
                                    {{ number_format((float) ($item->towing_fee ?? 0), 2) }}
                                </td>

                                {{-- Shipping --}}
                                <td>
                                    {{ number_format((float) ($item->shipping ?? 0), 2) }}
                                </td>

                                {{-- Grand Total --}}
                                <td>
                                    {{ number_format((float) ($item->grand_total ?? 0), 2) }}
                                </td>

                                {{-- Selling Price --}}
                                <td>
                                    {{ number_format((float) ($item->selling_price ?? 0), 2) }}
                                </td>

                                {{-- Profit --}}
                                <td>
                                    <span class="{{ ($item->profit ?? 0) >= 0
                                        ? 'text-success'
                                        : 'text-danger' }}">

                                        {{ number_format((float) ($item->profit ?? 0), 2) }}

                                    </span>
                                </td>

                                {{-- Status --}}
                                <td>

                                    @php
                                        $statusClass = match ($item->status) {
                                            'Purchased' => 'bg-primary',
                                            'Loaded' => 'bg-info',
                                            'Shipped' => 'bg-warning text-dark',
                                            'Delivered' => 'bg-success',
                                            'On Hand' => 'bg-secondary',
                                            'At UAE' => 'bg-dark',
                                            'Sold' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $item->status ?? 'Unknown' }}
                                    </span>

                                </td>

                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex flex-wrap gap-1">

                                        {{-- Edit --}}
                                        <a href="{{ route('edit.purchase', $item->id) }}"
                                           class="btn btn-sm btn-primary">

                                            <i class="bx bx-edit"></i>
                                            Edit

                                        </a>

                                        {{-- Delete --}}
                                        <a href="{{ route('delete.purchase', $item->id) }}"
                                           class="btn btn-sm btn-danger delete-purchase">

                                            <i class="bx bx-trash"></i>
                                            Delete

                                        </a>

                                        {{-- Sale --}}
                                        @if ($item->status !== 'Sold')

                                            <a href="{{ route('sale.purchase', $item->id) }}"
                                               class="btn btn-sm btn-success">

                                                <i class="bx bx-dollar-circle"></i>
                                                Sale

                                            </a>

                                        @else

                                            <span class="badge bg-success p-2">
                                                Sold
                                            </span>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="18" class="text-center py-4">

                                    <i class="bx bx-car fs-1 text-muted"></i>

                                    <p class="mb-0 mt-2">
                                        No purchases found.
                                    </p>

                                    <a href="{{ route('add.purchase') }}"
                                       class="btn btn-primary btn-sm mt-3">

                                        <i class="bx bx-plus"></i>
                                        Add Your First Purchase

                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

{{-- =====================================================
    IMPORT EXCEL MODAL
====================================================== --}}

<div class="modal fade"
     id="importExcelModal"
     tabindex="-1"
     aria-labelledby="importExcelModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="importExcelModalLabel">
                    Import Purchases
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>

            <form
                action="{{ route('purchases.import') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    <label for="excel_file" class="form-label">
                        Select Excel File
                    </label>

                    <input
                        type="file"
                        name="file"
                        id="excel_file"
                        class="form-control"
                        accept=".xlsx,.xls,.csv"
                        required>

                    <small class="text-muted">
                        Supported formats: XLSX, XLS, CSV.
                    </small>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-info">

                        <i class="bx bx-upload"></i>
                        Import Excel

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- =====================================================
    DELETE CONFIRMATION
====================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-purchase').forEach(function (button) {

        button.addEventListener('click', function (event) {

            event.preventDefault();

            const url = this.getAttribute('href');

            if (confirm('Are you sure you want to delete this purchase?')) {
                window.location.href = url;
            }

        });

    });

});
</script>

@endsection