@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
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


            {{-- ============================
        ACTION BUTTONS
    ============================= --}}

            <div class="ms-auto">

                <div class="btn-group">


                    {{-- Export Excel --}}

                    <a href="{{ route('purchases.export') }}" class="btn btn-success">

                        <i class="bx bx-download"></i>

                        Export Excel

                    </a>


                    {{-- Import Excel --}}

                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importExcelModal">

                        <i class="bx bx-upload"></i>

                        Import Excel

                    </button>


                    {{-- Add Purchase --}}

                    <a href="{{ route('add.purchase') }}" class="btn btn-primary">

                        <i class="bx bx-plus"></i>

                        Add Purchase

                    </a>

                </div>

            </div>

        </div>


        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>User Name</th>
                                <th>lot_number</th>
                                <th>vin</th>
                                <th>cylinder</th>
                                <th>buying_fee</th>
                                <th>towing_fee</th>
                                <th>shipping</th>
                                <th>selling_price</th>
                                <th>profit</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($purchases as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->lot_number }}</td>
                                    <td>{{ $item->vin }}</td>
                                    <td>{{ $item->cylinder }}</td>
                                    <td>{{ $item->buying_fee }}</td>
                                    <td>{{ $item->towing_fee }}</td>
                                    <td>{{ $item->shipping }}</td>
                                    <td>{{ $item->selling_price }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <a href="{{ route('edit.purchase', $item->id) }}"
                                            class="btn btn-primary px-4">Edit</a>
                                        <a href="{{ route('delete.purchase', $item->id) }}" id="delete"
                                            class="btn btn-danger px-4">Delete</a>
                                        @if ($item->status != 'Sold')
                                            <a href="{{ route('sale.purchase', $item->id) }}" class="btn btn-success px-4">
                                                Sale
                                            </a>
                                        @else
                                            <span class="badge bg-success">
                                                Sold
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>




    </div>

    <div class="modal fade" id="importExcelModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Import Purchases
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form
                action="{{ route('purchases.import') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    <label class="form-label">
                        Select Excel File
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        accept=".xlsx,.xls,.csv"
                        required>

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

                        Import Excel

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@endsection
