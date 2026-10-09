@extends('admin.admin_dashboard')

@section('admin')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .wa-wrapper {
        display: flex;
        height: 78vh;
        min-height: 550px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    /* LEFT SIDEBAR */
    .wa-sidebar {
        width: 360px;
        min-width: 300px;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #e5e7eb;
        background: #fff;
    }

    .wa-sidebar-header {
        padding: 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .wa-sidebar-header h4 {
        font-weight: 700;
        margin-bottom: 15px;
        color: #1f2937;
    }

    .wa-search {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 11px 14px;
        outline: none;
        background: #fff;
    }

    .wa-search:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .wa-conversation-list {
        flex: 1;
        overflow-y: auto;
    }

    .wa-conversation {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        text-decoration: none !important;
        color: #1f2937 !important;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background .2s;
    }

    .wa-conversation:hover {
        background: #f3f8f5;
    }

    .wa-conversation.active {
        background: #e7f5ec;
    }

    .wa-avatar {
        width: 49px;
        height: 49px;
        min-width: 49px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #d1fae5;
        color: #047857;
        font-size: 17px;
        font-weight: 700;
    }

    .wa-customer-info {
        flex: 1;
        min-width: 0;
    }

    .wa-customer-name {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .wa-last-message {
        font-size: 12px;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wa-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 7px;
    }

    .wa-time {
        font-size: 10px;
        color: #6b7280;
        white-space: nowrap;
    }

    .wa-unread {
        min-width: 21px;
        height: 21px;
        padding: 0 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        background: #16a34a;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
    }

    .wa-total-unread {
        background: #dcfce7;
        color: #166534;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    /* RIGHT CHAT PANEL */
    .wa-chat-panel {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        position: relative;
        background: #efeae2;
    }

    .wa-welcome {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 30px;
        text-align: center;
        background: #f8fafc;
    }

    .wa-welcome-icon {
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #dcfce7;
        color: #15803d;
        font-size: 38px;
        margin-bottom: 20px;
    }

    .wa-welcome h3 {
        color: #1f2937;
        font-weight: 700;
    }

    .wa-welcome p {
        max-width: 400px;
        color: #6b7280;
        line-height: 1.7;
    }

    .wa-chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .wa-header-user {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .wa-header-name {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .wa-header-email {
        font-size: 12px;
        color: #6b7280;
        overflow-wrap: anywhere;
    }

    .wa-online-badge {
        color: #15803d;
        background: #dcfce7;
        border-radius: 20px;
        padding: 6px 10px;
        font-size: 11px;
        white-space: nowrap;
    }

    .wa-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background-color: #efeae2;
        background-image: radial-gradient(
            rgba(120, 113, 108, 0.08) 1px,
            transparent 1px
        );
        background-size: 20px 20px;
    }

    .wa-date-label {
        text-align: center;
        margin-bottom: 20px;
    }

    .wa-date-label span {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        background: rgba(255,255,255,.9);
        color: #6b7280;
        font-size: 11px;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }

    .wa-message-row {
        display: flex;
        margin-bottom: 12px;
    }

    .wa-message-row.mine {
        justify-content: flex-end;
    }

    .wa-message-row.theirs {
        justify-content: flex-start;
    }

    .wa-bubble {
        max-width: min(75%, 550px);
        padding: 10px 13px 7px;
        border-radius: 10px;
        box-shadow: 0 1px 1px rgba(0,0,0,.08);
        overflow-wrap: anywhere;
    }

    .wa-bubble.mine {
        background: #d9fdd3;
        color: #1f2937;
        border-top-right-radius: 3px;
    }

    .wa-bubble.theirs {
        background: #fff;
        color: #1f2937;
        border-top-left-radius: 3px;
    }

    .wa-sender {
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #15803d;
    }

    .wa-message-text {
        font-size: 14px;
        line-height: 1.5;
        white-space: pre-wrap;
    }

    .wa-message-time {
        font-size: 10px;
        color: #6b7280;
        text-align: right;
        margin-top: 6px;
    }

    .wa-empty-messages {
        text-align: center;
        color: #6b7280;
        margin-top: 80px;
    }

    .wa-composer {
        padding: 14px 18px;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }

    .wa-composer-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .wa-message-input {
        flex: 1;
        min-width: 0;
        border: 1px solid #d1d5db;
        border-radius: 25px;
        padding: 12px 18px;
        outline: none;
        background: #fff;
    }

    .wa-message-input:focus {
        border-color: #16a34a;
    }

    .wa-send-button {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border: none;
        border-radius: 50%;
        background: #16a34a;
        color: #fff;
        font-size: 17px;
        cursor: pointer;
        transition: background .2s;
    }

    .wa-send-button:hover {
        background: #15803d;
    }

    .wa-send-button:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .wa-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 7px;
    }

    .wa-loading {
        text-align: center;
        color: #6b7280;
        padding: 30px;
    }

    @media (max-width: 900px) {
        .wa-sidebar {
            width: 300px;
            min-width: 260px;
        }

        .wa-messages {
            padding: 15px;
        }
    }

    @media (max-width: 650px) {
        .wa-wrapper {
            height: 75vh;
            min-height: 500px;
        }

        .wa-sidebar {
            width: 100%;
            min-width: 0;
        }

        .wa-chat-panel {
            display: none;
        }

        .wa-wrapper.mobile-chat-open .wa-sidebar {
            display: none;
        }

        .wa-wrapper.mobile-chat-open .wa-chat-panel {
            display: flex;
            width: 100%;
        }

        .wa-bubble {
            max-width: 90%;
        }

        .wa-messages {
            padding: 12px;
        }
    }
</style>

<div class="container-fluid py-3">

    <div class="wa-wrapper" id="waWrapper">

        {{-- LEFT: CUSTOMER LIST --}}
        <aside class="wa-sidebar">

            <div class="wa-sidebar-header">

                <h4>
                    <i class="fas fa-comments text-success me-2"></i>
                    Live Chat
                </h4>

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <small class="text-muted">
                        Customer conversations
                    </small>

                    <span
                        class="wa-total-unread"
                        id="totalUnread"
                        style="{{ $totalUnread > 0 ? '' : 'display:none;' }}"
                    >
                        {{ $totalUnread }} unread
                    </span>

                </div>

                <input
                    type="search"
                    id="customerSearch"
                    class="wa-search"
                    placeholder="Search customers..."
                    autocomplete="off"
                >

            </div>

            <div class="wa-conversation-list" id="conversationList">

                @forelse($conversations as $conversation)

                    @php
                        $customerName = $conversation->user->name ?? 'Unknown Customer';

                        $initials = collect(explode(' ', trim($customerName)))
                            ->filter()
                            ->take(2)
                            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                            ->implode('');

                        $latest = $conversation->latestMessage;
                        $lastMessage = $latest->message ?? 'No messages yet';
                        $lastTime = $latest?->created_at;
                    @endphp

                    <a
                        href="#"
                        class="wa-conversation"
                        data-conversation-id="{{ $conversation->id }}"
                        data-name="{{ strtolower($customerName) }}"
                        data-email="{{ strtolower($conversation->user->email ?? '') }}"
                        data-unread="{{ $conversation->unread_count }}"
                    >

                        <div class="wa-avatar">
                            {{ $initials ?: '?' }}
                        </div>

                        <div class="wa-customer-info">

                            <div class="wa-customer-name">
                                {{ $customerName }}
                            </div>

                            <div class="wa-last-message">
                                {{ $lastMessage }}
                            </div>

                        </div>

                        <div class="wa-meta">

                            <span class="wa-time">
                                {{ $lastTime ? $lastTime->format('h:i A') : '' }}
                            </span>

                            <span
                                class="wa-unread"
                                style="{{ $conversation->unread_count > 0 ? '' : 'display:none;' }}"
                            >
                                {{ $conversation->unread_count }}
                            </span>

                        </div>

                    </a>

                @empty

                    <div class="text-center text-muted p-4">
                        <i class="fas fa-comments fa-2x mb-3"></i>
                        <p>No customer conversations yet.</p>
                    </div>

                @endforelse

            </div>

        </aside>


        {{-- RIGHT: CHAT PANEL --}}
        <section class="wa-chat-panel" id="waChatPanel">

            {{-- Welcome screen --}}
            <div class="wa-welcome" id="waWelcome">

                <div class="wa-welcome-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>

                <h3>Car Showroom Live Chat</h3>

                <p>
                    Select a customer from the list to read messages
                    and reply directly from your dashboard.
                </p>

                <div class="small text-muted">
                    <i class="fas fa-lock me-1"></i>
                    Private customer conversations
                </div>

            </div>


            {{-- Active conversation --}}
            <div
                id="waActiveChat"
                style="display:none; flex-direction:column; height:100%; min-height:0;"
            >

                <div class="wa-chat-header">

                    <div class="wa-header-user">

                        <button
                            type="button"
                            id="waBackButton"
                            class="btn btn-light btn-sm d-md-none"
                            aria-label="Back to customer list"
                        >
                            <i class="fas fa-arrow-left"></i>
                        </button>

                        <div class="wa-avatar" id="waHeaderAvatar">?</div>

                        <div style="min-width:0;">

                            <div class="wa-header-name" id="waHeaderName">
                                Customer
                            </div>

                            <div class="wa-header-email" id="waHeaderEmail"></div>

                        </div>

                    </div>

                    <span class="wa-online-badge">
                        <i class="fas fa-comments me-1"></i>
                        Conversation
                    </span>

                </div>


                <div class="wa-messages" id="waMessages">

                    <div class="wa-loading">
                        Loading messages...
                    </div>

                </div>


                <div class="wa-composer">

                    <form id="waMessageForm" class="wa-composer-form">

                        <input
                            type="text"
                            id="waMessageInput"
                            class="wa-message-input"
                            placeholder="Type a message..."
                            maxlength="5000"
                            autocomplete="off"
                            required
                        >

                        <button
                            type="submit"
                            class="wa-send-button"
                            id="waSendButton"
                            aria-label="Send message"
                        >send
                            <i class="fas fa-paper-plane"></i>
                        </button>

                    </form>

                    <div id="waError" class="wa-error"></div>

                </div>

            </div>

        </section>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.getElementById('waWrapper');
    const searchInput = document.getElementById('customerSearch');
    const conversationList = document.getElementById('conversationList');

    const conversationLinks = Array.from(
        document.querySelectorAll('.wa-conversation')
    );

    const welcome = document.getElementById('waWelcome');
    const activeChat = document.getElementById('waActiveChat');
    const messagesBox = document.getElementById('waMessages');

    const headerName = document.getElementById('waHeaderName');
    const headerEmail = document.getElementById('waHeaderEmail');
    const headerAvatar = document.getElementById('waHeaderAvatar');

    const form = document.getElementById('waMessageForm');
    const messageInput = document.getElementById('waMessageInput');
    const sendButton = document.getElementById('waSendButton');
    const errorBox = document.getElementById('waError');

    const totalUnreadBadge = document.getElementById('totalUnread');

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).getAttribute('content');

    const currentAdminId = Number(@json(auth()->id()));

    const loadUrlTemplate = @json(
        route('chat.messages', ['id' => '__ID__'])
    );

    const sendUrlTemplate = @json(
        route('chat.send', ['id' => '__ID__'])
    );

    let activeConversationId = null;
    let refreshTimer = null;
    let isLoading = false;
    let isSending = false;
    let latestMessageId = 0;


    // Escape text through DOM textContent rather than injecting HTML.
    function makeBubble(message) {

        const isMine = Number(message.sender_id) === currentAdminId;

        const row = document.createElement('div');
        row.className = 'wa-message-row ' + (isMine ? 'mine' : 'theirs');

        const bubble = document.createElement('div');
        bubble.className = 'wa-bubble ' + (isMine ? 'mine' : 'theirs');

        if (!isMine) {
            const sender = document.createElement('div');
            sender.className = 'wa-sender';
            sender.textContent = message.sender_name || 'Customer';
            bubble.appendChild(sender);
        }

        const text = document.createElement('div');
        text.className = 'wa-message-text';
        text.textContent = message.message;
        bubble.appendChild(text);

        const time = document.createElement('div');
        time.className = 'wa-message-time';
        time.textContent = message.created_at;
        bubble.appendChild(time);

        row.appendChild(bubble);

        return row;
    }


    function scrollToBottom() {
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }


    function updateUnreadBadge(link, count) {

        const badge = link.querySelector('.wa-unread');

        link.dataset.unread = count;

        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-flex';
        } else {
            badge.textContent = '';
            badge.style.display = 'none';
        }

    }


    function calculateTotalUnread() {

        let total = 0;

        conversationLinks.forEach(function (link) {
            total += Number(link.dataset.unread || 0);
        });

        totalUnreadBadge.textContent = total + ' unread';
        totalUnreadBadge.style.display = total > 0 ? 'inline-block' : 'none';

    }


    function updateConversationPreview(link, messages) {

        if (!messages || messages.length === 0) {
            return;
        }

        const latest = messages[messages.length - 1];

        const preview = link.querySelector('.wa-last-message');
        const time = link.querySelector('.wa-time');

        preview.textContent = latest.message;
        time.textContent = latest.created_at;

    }


    async function loadConversation(id, markActive = true) {

        if (isLoading) {
            return;
        }

        isLoading = true;

        activeConversationId = Number(id);

        if (markActive) {

            welcome.style.display = 'none';
            activeChat.style.display = 'flex';

            wrapper.classList.add('mobile-chat-open');

            conversationLinks.forEach(function (link) {

                link.classList.toggle(
                    'active',
                    Number(link.dataset.conversationId) === activeConversationId
                );

            });

        }

        errorBox.textContent = '';

        try {

            const url = loadUrlTemplate.replace('__ID__', id);

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(
                    response.status === 403
                        ? 'You are not authorized to access this conversation.'
                        : 'Could not load this conversation.'
                );
            }

            const data = await response.json();

            // Ignore an old response if another conversation was selected.
            if (Number(data.conversation.id) !== activeConversationId) {
                return;
            }

            headerName.textContent = data.conversation.name;
            headerEmail.textContent = data.conversation.email || '';

            const nameParts = data.conversation.name.trim().split(/\s+/);
            headerAvatar.textContent = nameParts
                .slice(0, 2)
                .map(part => part.charAt(0).toUpperCase())
                .join('') || '?';

            const oldScrollHeight = messagesBox.scrollHeight;
            const wasNearBottom =
                messagesBox.scrollHeight - messagesBox.scrollTop
                - messagesBox.clientHeight < 100;

            messagesBox.innerHTML = '';

            if (!data.messages || data.messages.length === 0) {

                const empty = document.createElement('div');
                empty.className = 'wa-empty-messages';
                empty.textContent = 'No messages yet. Send the first message.';
                messagesBox.appendChild(empty);

                latestMessageId = 0;

            } else {

                data.messages.forEach(function (message) {
                    messagesBox.appendChild(makeBubble(message));
                });

                latestMessageId = Number(
                    data.messages[data.messages.length - 1].id
                );

                if (wasNearBottom || oldScrollHeight === 0 || markActive) {
                    scrollToBottom();
                }

            }

            // This conversation was just marked read by the controller.
            const selectedLink = conversationLinks.find(function (link) {
                return Number(link.dataset.conversationId) === activeConversationId;
            });

            if (selectedLink) {
                updateUnreadBadge(selectedLink, 0);
            }

            calculateTotalUnread();

        } catch (error) {

            messagesBox.innerHTML = '';

            const errorMessage = document.createElement('div');
            errorMessage.className = 'wa-empty-messages';
            errorMessage.textContent = error.message;

            messagesBox.appendChild(errorMessage);

        } finally {

            isLoading = false;

        }

    }


    // Select a customer without leaving /admin/chat.
    conversationLinks.forEach(function (link) {

        link.addEventListener('click', function (event) {

            event.preventDefault();

            const id = Number(this.dataset.conversationId);

            if (!id) {
                return;
            }

            if (activeConversationId === id && activeChat.style.display !== 'none') {
                return;
            }

            loadConversation(id);

            if (refreshTimer) {
                clearInterval(refreshTimer);
            }

            refreshTimer = setInterval(function () {

                if (activeConversationId === id && !document.hidden) {
                    loadConversation(id, false);
                }

            }, 5000);

        });

    });


    // Search customer names and emails.
    searchInput.addEventListener('input', function () {

        const search = this.value.toLowerCase().trim();

        conversationLinks.forEach(function (link) {

            const name = link.dataset.name || '';
            const email = link.dataset.email || '';

            link.style.display =
                name.includes(search) || email.includes(search)
                    ? 'flex'
                    : 'none';

        });

    });


    // Send an admin reply.
    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (!activeConversationId || isSending) {
            return;
        }

        const message = messageInput.value.trim();

        if (!message) {
            return;
        }

        isSending = true;
        sendButton.disabled = true;
        errorBox.textContent = '';

        try {

            const url = sendUrlTemplate.replace(
                '__ID__',
                activeConversationId
            );

            const response = await fetch(url, {
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

                messageInput.value = '';

                await loadConversation(activeConversationId, false);

                const link = conversationLinks.find(function (item) {
                    return Number(item.dataset.conversationId) === activeConversationId;
                });

                if (link) {
                    updateConversationPreview(link, [{
                        message: message,
                        created_at: new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                    }]);

                    conversationList.prepend(link);
                }

            }

        } catch (error) {

            errorBox.textContent = 'Network error. Please try again.';

        } finally {

            isSending = false;
            sendButton.disabled = false;
            messageInput.focus();

        }

    });


    // Mobile back button.
    document.getElementById('waBackButton').addEventListener('click', function () {

        wrapper.classList.remove('mobile-chat-open');

        if (refreshTimer) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }

        activeConversationId = null;

    });


    // Refresh unread counts by reloading the list endpoint.
    // The next enhancement can provide a lightweight JSON endpoint for this.
    document.addEventListener('visibilitychange', function () {

        if (!document.hidden && activeConversationId && refreshTimer) {
            loadConversation(activeConversationId, false);
        }

    });

});
</script>

@endsection