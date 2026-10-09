@extends('client.client_dashboard')

@section('client')

<div class="container py-4">

    <div class="card shadow-sm">

        {{-- Chat Header --}}
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-comments me-2"></i>
                Chat with Admin
            </h5>
        </div>

        {{-- Chat Messages --}}
        <div
            id="chatMessages"
            class="card-body"
            style="height: 450px; overflow-y: auto; background: #f8f9fa;"
        >

            @forelse($messages as $message)

                @php
                    $isMine = $message->sender_id === auth()->id();
                @endphp

                <div class="d-flex mb-3 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">

                    <div
                        class="p-3 rounded shadow-sm"
                        style="
                            max-width: 75%;
                            background: {{ $isMine ? '#0d6efd' : '#ffffff' }};
                            color: {{ $isMine ? '#ffffff' : '#212529' }};
                        "
                    >

                        <div class="small fw-bold mb-1">
                            {{ $isMine ? 'You' : ($message->sender->name ?? 'Admin') }}
                        </div>

                        <div style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $message->message }}</div>

                        <div class="small mt-2 text-end"
                             style="opacity: 0.75;">
                            {{ $message->created_at->format('h:i A') }}
                        </div>

                    </div>

                </div>

            @empty

                <div id="emptyMessage" class="text-center text-muted mt-5">
                    <i class="fas fa-comment-dots fa-3x mb-3"></i>
                    <p>No messages yet. Send a message to start chatting!</p>
                </div>

            @endforelse

        </div>

        {{-- Message Input --}}
        <div class="card-footer">

            <form id="chatForm">

                @csrf

                <div class="input-group">

                    <input
                        type="text"
                        id="messageInput"
                        name="message"
                        class="form-control"
                        placeholder="Type your message..."
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
                        Send
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
            const response = await fetch(@json(route('client.send')), {
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
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    errorContainer.textContent =
                        Object.values(data.errors).flat().join(' ');
                } else {
                    errorContainer.textContent =
                        data.message || 'Unable to send message.';
                }

                return;
            }

            if (data.success) {
                // Reload messages from the server
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