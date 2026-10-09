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


        {{-- MESSAGE CONTAINER --}}
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
                >send
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

    const csrfElement = document.querySelector(
        'meta[name="csrf-token"]'
    );

    if (!csrfElement || !form || !messagesBox) {
        console.error('Chat elements or CSRF token are missing.');
        return;
    }

    const csrfToken = csrfElement.content;

    const currentUserId = Number(@json(auth()->id()));

    const sendUrl = @json(route('client.send'));

    const messagesUrl = @json(route('client.messages'));

    let lastMessageId = 0;
    let isSending = false;
    let refreshPromise = null;

    // Record IDs of messages already rendered by Blade.
    messagesBox.querySelectorAll('[data-message-id]').forEach(function (element) {
        lastMessageId = Math.max(
            lastMessageId,
            Number(element.dataset.messageId)
        );
    });

    // Scroll to the latest message when opening the page.
    messagesBox.scrollTop = messagesBox.scrollHeight;


    /*
    |--------------------------------------------------------------------------
    | Display a message
    |--------------------------------------------------------------------------
    */

    function appendMessage(message) {

        const messageId = Number(message.id);

        // Prevent duplicate messages.
        if (
            messagesBox.querySelector(
                '[data-message-id="' + messageId + '"]'
            )
        ) {
            return;
        }

        const isMine = Number(message.sender_id) === currentUserId;

        const row = document.createElement('div');

        row.className = 'client-wa-row ' + (
            isMine ? 'mine' : 'theirs'
        );

        row.dataset.messageId = messageId;

        const bubble = document.createElement('div');

        bubble.className = 'client-wa-bubble ' + (
            isMine ? 'mine' : 'theirs'
        );

        // Display sender name for admin messages.
        if (!isMine) {

            const sender = document.createElement('div');

            sender.className = 'client-wa-sender';

            sender.textContent = message.sender_name || 'Admin';

            bubble.appendChild(sender);
        }

        const text = document.createElement('div');

        text.className = 'client-wa-text';

        text.textContent = message.message || '';

        const time = document.createElement('div');

        time.className = 'client-wa-time';

        time.textContent = message.created_at || '';

        bubble.appendChild(text);
        bubble.appendChild(time);

        row.appendChild(bubble);

        // Remove the empty-chat placeholder.
        const emptyMessage = document.getElementById('clientWaEmpty');

        if (emptyMessage) {
            emptyMessage.remove();
        }

        messagesBox.appendChild(row);

        lastMessageId = Math.max(lastMessageId, messageId);

        messagesBox.scrollTop = messagesBox.scrollHeight;
    }


    /*
    |--------------------------------------------------------------------------
    | Load messages from the database
    |--------------------------------------------------------------------------
    */

    async function refreshMessages() {

        // Reuse an existing refresh instead of skipping this request.
        if (refreshPromise) {
            return refreshPromise;
        }

        refreshPromise = (async function () {

            try {

                const response = await fetch(messagesUrl, {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                if (!response.ok) {
                    throw new Error(
                        'Could not load messages. HTTP ' + response.status
                    );
                }

                const data = await response.json();

                if (!Array.isArray(data.messages)) {
                    throw new Error('The server did not return a messages array.');
                }

                data.messages.forEach(function (message) {
                    appendMessage(message);
                });

            } catch (error) {

                console.error('Chat refresh error:', error);

            }

        })();

        try {
            await refreshPromise;
        } finally {
            refreshPromise = null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Send message without refreshing the page
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (isSending) {
            return;
        }

        const messageText = input.value.trim();

        if (!messageText) {
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

                credentials: 'same-origin',

                body: JSON.stringify({
                    message: messageText
                })

            });

            const data = await response.json();

            if (!response.ok || !data.success) {

                if (response.status === 419) {
                    errorBox.textContent =
                        'Your session has expired. Refresh the page and try again.';
                } else if (response.status === 401) {
                    errorBox.textContent =
                        'Please log in again to send messages.';
                } else if (data.errors) {
                    errorBox.textContent =
                        Object.values(data.errors).flat().join(' ');
                } else {
                    errorBox.textContent =
                        data.message || 'Unable to send the message.';
                }

                return;
            }

            /*
             * IMPORTANT:
             * Immediately display the sent message using the text
             * and timestamp returned by the server.
             */
            if (data.messageData) {

                appendMessage(data.messageData);

            } else {

                /*
                 * If the controller returns only success, retrieve
                 * the saved message from the database.
                 */
                await refreshMessages();

            }

            input.value = '';

            messagesBox.scrollTop = messagesBox.scrollHeight;

        } catch (error) {

            console.error('Send message error:', error);

            errorBox.textContent =
                'Unable to send the message. Check your connection and try again.';

        } finally {

            isSending = false;

            sendButton.disabled = false;

            input.focus();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Receive admin replies automatically
    |--------------------------------------------------------------------------
    */

    setInterval(function () {

        if (!document.hidden) {
            refreshMessages();
        }

    }, 5000);


    /*
    |--------------------------------------------------------------------------
    | Load any new messages when returning to the browser tab
    |--------------------------------------------------------------------------
    */

    document.addEventListener('visibilitychange', function () {

        if (!document.hidden) {
            refreshMessages();
        }

    });

});
</script>

@endsection