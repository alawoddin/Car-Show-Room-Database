@extends('client.client_dashboard')

@section('client')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .client-wa-wrapper {
        display: flex;
        flex-direction: column;
        height: 75vh;
        min-height: 500px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
    }

    .client-wa-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .client-wa-avatar {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #d1fae5;
        color: #047857;
        font-size: 19px;
        font-weight: 700;
    }

    .client-wa-header h5 {
        margin: 0 0 4px;
        color: #1f2937;
        font-weight: 700;
    }

    .client-wa-status {
        color: #16a34a;
        font-size: 12px;
    }

    .client-wa-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background-color: #efeae2;
        background-image: radial-gradient(
            rgba(120, 113, 108, .08) 1px,
            transparent 1px
        );
        background-size: 20px 20px;
    }

    .client-wa-row {
        display: flex;
        margin-bottom: 14px;
    }

    .client-wa-row.mine {
        justify-content: flex-end;
    }

    .client-wa-row.theirs {
        justify-content: flex-start;
    }

    .client-wa-bubble {
        max-width: min(75%, 550px);
        padding: 11px 14px 7px;
        border-radius: 11px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .07);
        overflow-wrap: anywhere;
    }

    .client-wa-bubble.mine {
        background: #d9fdd3;
        color: #1f2937;
        border-top-right-radius: 3px;
    }

    .client-wa-bubble.theirs {
        background: #fff;
        color: #1f2937;
        border-top-left-radius: 3px;
    }

    .client-wa-sender {
        margin-bottom: 5px;
        color: #15803d;
        font-size: 11px;
        font-weight: 700;
    }

    .client-wa-text {
        white-space: pre-wrap;
        line-height: 1.5;
        font-size: 14px;
    }

    .client-wa-time {
        margin-top: 6px;
        color: #6b7280;
        font-size: 10px;
        text-align: right;
    }

    .client-wa-empty {
        margin-top: 80px;
        text-align: center;
        color: #6b7280;
    }

    .client-wa-composer {
        padding: 14px 18px;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }

    .client-wa-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .client-wa-input {
        flex: 1;
        min-width: 0;
        padding: 13px 18px;
        border: 1px solid #d1d5db;
        border-radius: 30px;
        outline: none;
        background: #fff;
    }

    .client-wa-input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .08);
    }

    .client-wa-send {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border: none;
        border-radius: 50%;
        background: #16a34a;
        color: #fff;
        font-size: 17px;
        cursor: pointer;
    }

    .client-wa-send:hover {
        background: #15803d;
    }

    .client-wa-send:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .client-wa-error {
        margin-top: 7px;
        color: #dc2626;
        font-size: 12px;
    }

    @media (max-width: 600px) {
        .client-wa-wrapper {
            height: 75vh;
            min-height: 450px;
        }

        .client-wa-messages {
            padding: 12px;
        }

        .client-wa-bubble {
            max-width: 90%;
        }

        .client-wa-composer {
            padding: 10px;
        }
    }
</style>


<div class="container-fluid py-3">

    <div class="client-wa-wrapper">

        {{-- CHAT HEADER --}}
        <div class="client-wa-header">

            <div class="client-wa-avatar">
                <i class="fas fa-headset"></i>
            </div>

            <div class="flex-grow-1">

                <h5>Showroom Support</h5>

                <div class="client-wa-status">
                    <i class="fas fa-circle" style="font-size: 7px;"></i>
                    Message the administration team
                </div>

            </div>

            <span class="text-success">
                <i class="fas fa-shield-alt"></i>
            </span>

        </div>


        {{-- MESSAGES --}}
        <div class="client-wa-messages" id="clientWaMessages">

            @forelse($messages as $message)

                @php
                    $isMine = (int) $message->sender_id === (int) auth()->id();
                @endphp

                <div
                    class="client-wa-row {{ $isMine ? 'mine' : 'theirs' }}"
                    data-message-id="{{ $message->id }}"
                >

                    <div class="client-wa-bubble {{ $isMine ? 'mine' : 'theirs' }}">

                        @if(!$isMine)
                            <div class="client-wa-sender">
                                {{ $message->sender->name ?? 'Admin' }}
                            </div>
                        @endif

                        <div class="client-wa-text">{{ $message->message }}</div>

                        <div class="client-wa-time">
                            {{ $message->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>

                </div>

            @empty

                <div class="client-wa-empty" id="clientWaEmpty">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>No messages yet.</p>
                    <p>Send a message to contact the showroom administration.</p>
                </div>

            @endforelse

        </div>


        {{-- MESSAGE INPUT --}}
        <div class="client-wa-composer">

            <form id="clientWaForm" class="client-wa-form">

                @csrf

                <input
                    type="text"
                    id="clientWaInput"
                    name="message"
                    class="client-wa-input"
                    placeholder="Type a message..."
                    maxlength="5000"
                    autocomplete="off"
                    required
                >

                <button
                    type="submit"
                    id="clientWaSend"
                    class="client-wa-send"
                    aria-label="Send message"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>

            </form>

            <div id="clientWaError" class="client-wa-error"></div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('clientWaForm');
    const input = document.getElementById('clientWaInput');
    const sendButton = document.getElementById('clientWaSend');
    const messagesBox = document.getElementById('clientWaMessages');
    const errorBox = document.getElementById('clientWaError');

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).getAttribute('content');

    const currentUserId = Number(@json(auth()->id()));

    const sendUrl = @json(route('client.send'));

    const initialMessageIds = new Set(
        Array.from(messagesBox.querySelectorAll('[data-message-id]'))
            .map(element => Number(element.dataset.messageId))
    );

    let lastMessageId = Math.max(0, ...initialMessageIds);
    let isSending = false;
    let isRefreshing = false;

    messagesBox.scrollTop = messagesBox.scrollHeight;


    function appendMessage(message) {

        const isMine = Number(message.sender_id) === currentUserId;

        const row = document.createElement('div');
        row.className = 'client-wa-row ' + (isMine ? 'mine' : 'theirs');
        row.dataset.messageId = message.id;

        const bubble = document.createElement('div');
        bubble.className =
            'client-wa-bubble ' + (isMine ? 'mine' : 'theirs');

        if (!isMine) {

            const sender = document.createElement('div');
            sender.className = 'client-wa-sender';
            sender.textContent = message.sender_name || 'Admin';

            bubble.appendChild(sender);

        }

        const text = document.createElement('div');
        text.className = 'client-wa-text';
        text.textContent = message.message;

        const time = document.createElement('div');
        time.className = 'client-wa-time';
        time.textContent = message.created_at;

        bubble.appendChild(text);
        bubble.appendChild(time);
        row.appendChild(bubble);

        messagesBox.appendChild(row);

        lastMessageId = Math.max(lastMessageId, Number(message.id));

        const empty = document.getElementById('clientWaEmpty');

        if (empty) {
            empty.remove();
        }

        messagesBox.scrollTop = messagesBox.scrollHeight;

    }


    // Send a message without reloading the page.
    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (isSending) {
            return;
        }

        const message = input.value.trim();

        if (!message) {
            return;
        }

        isSending = true;
        sendButton.disabled = true;
        errorBox.textContent = '';

        try {

            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: message
                })
            });

            const data = await response.json();

            if (!response.ok) {

                if (data.errors) {
                    errorBox.textContent =
                        Object.values(data.errors).flat().join(' ');
                } else {
                    errorBox.textContent =
                        data.message || 'Unable to send the message.';
                }

                return;
            }

            if (data.success) {

                input.value = '';

                // Refresh from the database so the actual message ID
                // and server timestamp are displayed.
                await refreshMessages();

            }

        } catch (error) {

            errorBox.textContent =
                'Network error. Please check your connection and try again.';

        } finally {

            isSending = false;
            sendButton.disabled = false;
            input.focus();

        }

    });


    // Fetch only messages newer than the last displayed message.
    async function refreshMessages() {

        if (isRefreshing || document.hidden) {
            return;
        }

        isRefreshing = true;

        try {

            const response = await fetch(
                @json(route('client.messages')),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (!Array.isArray(data.messages)) {
                return;
            }

            data.messages.forEach(function (message) {

                const id = Number(message.id);

                if (id > lastMessageId) {
                    appendMessage(message);
                }

            });

        } catch (error) {

            // Keep the chat usable if a refresh fails temporarily.

        } finally {

            isRefreshing = false;

        }

    }


    // Check for new messages every 5 seconds.
    setInterval(refreshMessages, 5000);

});
</script>

@endsection