@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Backup</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body p-4">
                <h5 class="mb-2">System Backup</h5>
                <p class="text-muted mb-4">
                    Download a full database backup. Optionally include uploaded images/files.
                </p>

                <form action="{{ route('admin.backup.create') }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_uploads" value="1" id="include_uploads">
                            <label class="form-check-label" for="include_uploads">
                                Include uploads folder (photos / files)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-data"></i> Create Backup Now
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Saved Backups</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>File</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($files as $key => $file)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $file['name'] }}</td>
                                    <td>{{ $file['size'] }}</td>
                                    <td>{{ $file['date'] }}</td>
                                    <td>
                                        <a href="{{ route('admin.backup.download', $file['name']) }}"
                                           class="btn btn-sm btn-success">Download</a>
                                        <a href="{{ route('admin.backup.delete', $file['name']) }}"
                                           class="btn btn-sm btn-danger"
                                           id="delete">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No backups yet. Create one above.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
