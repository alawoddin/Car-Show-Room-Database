@extends('admin.admin_dashboard')

@section('admin')

<div class="container-fluid py-3">

    <div class="card shadow-sm">

        {{-- Chat Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-1">
                    Chat with {{ $conversation->user->name ?? 'Client' }}
                </h5>

                <small class="text-muted">
                    {{ $conversation->user->email ?? '' }}
                </small>
            </div>

            <a href="{{ route('all.chat') }}" class="btn btn-secondary btn-sm">
                Back to Chats
            </a>

        </div>

        {{-- Messages --}}
        <div
            id="chatMessages"
            class="card-body"
            style="height: 450px; overflow-y: auto; background: #f5f7fb;"
        >

            @forelse($messages as $message)

                @php
                    $isMine = $message->sender_id === auth()->id();
                @endphp

                <div class="d-flex mb-3
                    {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">

                    <div
                        class="rounded p-3 shadow-sm"
                        style="
                            max-width: 75%;
                            background: {{ $isMine ? '#0d6efd' : '#ffffff' }};
                            color: {{ $isMine ? '#ffffff' : '#212529' }};
                        "
                    >

                        <div class="small fw-bold mb-1">
                            {{ $isMine ? 'You (Admin)' : ($message->sender->name ?? 'Client') }}
                        </div>

                        <div style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $message->message }}</div>

                        <div class="small text-end mt-2" style="opacity: .75;">
                            {{ $message->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted mt-5">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>No messages yet. Send the first message to this client.</p>
                </div>

            @endforelse

        </div>

        {{-- Message Form --}}
        <div class="card-footer">

            <form id="chatForm">

                @csrf

                <div class="input-group">

                    <input
                        type="text"
                        id="messageInput"
                        name="message"
                        class="form-control"
                        placeholder="Type your reply..."
                        maxlength="5000"
                        autocomplete="off"
                        required
                    >

                    <button
                        type="submit"
                        id="sendButton"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-paper-plane me-1"></i>
                        Send Reply
                    </button>

                </div>

                <div id="chatError" class="text-danger small mt-2"></div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('chatForm');
    const input = document.getElementById('messageInput');
    const messagesContainer = document.getElementById('chatMessages');
    const sendButton = document.getElementById('sendButton');
    const errorContainer = document.getElementById('chatError');

    // Scroll to the latest message
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        const message = input.value.trim();

        if (!message) {
            return;
        }

        sendButton.disabled = true;
        errorContainer.textContent = '';

        try {

            const response = await fetch(
                @json(route('chat.send', $conversation->id)),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'input[name="_token"]'
                        ).value
                    },
                    body: JSON.stringify({
                        message: message
                    })
                }
            );

            const data = await response.json();

            if (!response.ok) {

                if (data.errors) {
                    errorContainer.textContent =
                        Object.values(data.errors).flat().join(' ');
                } else {
                    errorContainer.textContent =
                        data.message || 'Unable to send reply.';
                }

                return;
            }

            if (data.success) {
                // Reload to display the newly saved message
                window.location.reload();
            }

        } catch (error) {

            errorContainer.textContent =
                'Something went wrong. Please try again.';

        } finally {

            sendButton.disabled = false;

        }

    });

});
</script>

@endsection