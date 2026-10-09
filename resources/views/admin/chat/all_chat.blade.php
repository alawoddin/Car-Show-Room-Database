@extends('admin.admin_dashboard')

@section('admin')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h4 class="mb-0">Client Messages</h4>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Email</th>
                                    <th>Last Message</th>
                                    <th>Last Message Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($conversations as $key => $conversation)

                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            {{ $conversation->user->name ?? 'Unknown Client' }}
                                        </td>

                                        <td>
                                            {{ $conversation->user->email ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ \Illuminate\Support\Str::limit(
                                                $conversation->latestMessage->message ?? 'No messages',
                                                50
                                            ) }}
                                        </td>

                                        <td>
                                            {{ $conversation->last_message_at
                                                ? $conversation->last_message_at->format('d M Y, h:i A')
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            <a
                                                href="{{ route('chat.show', $conversation->id) }}"
                                                class="btn btn-primary btn-sm"
                                            >
                                                <i class="fas fa-comments"></i>
                                                Open Chat
                                            </a>
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No client conversations found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection