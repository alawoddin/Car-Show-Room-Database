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
                        <li class="breadcrumb-item active" aria-current="page">All Expense</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.expense') }}" class="btn btn-primary px-5">Add Expense </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        @php
    use Illuminate\Support\Str;
@endphp



        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>expense_name</th>
                                <th>amount</th>
                                <th>description</th>
                                <th>data</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($expenses as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->expense_name }}$</td>
                                    <td>{{ $item->amount }}$</td>
                                    <td>{{ Str::limit($item->description, 80) }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>
                                        <a href="{{ route('edit.expense', $item->id) }}" class="btn btn-info">Edit</a>
                                        <a href="{{ route('delete.expense', $item->id) }}" class="btn btn-danger" id="delete">Delete</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>




    </div>
@endsection
