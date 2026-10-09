<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">

            {{-- Mobile Menu --}}
            <div class="mobile-toggle-menu">
                <i class='bx bx-menu'></i>
            </div>

            {{-- Search --}}
            <div class="position-relative search-bar d-lg-block d-none"
                 data-bs-toggle="modal"
                 data-bs-target="#SearchModal">

                <input class="form-control px-5"
                       disabled
                       type="search"
                       placeholder="Search">

                <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 fs-5">
                    <i class='bx bx-search'></i>
                </span>
            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">

                    {{-- Mobile Search --}}
                    <li class="nav-item mobile-search-icon d-flex d-lg-none"
                        data-bs-toggle="modal"
                        data-bs-target="#SearchModal">

                        <a class="nav-link" href="javascript:;">
                            <i class='bx bx-search'></i>
                        </a>
                    </li>

                
                    {{-- Dark Mode --}}
                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;">
                            <i class='bx bx-moon'></i>
                        </a>
                    </li>

                    {{-- ================================================= --}}
                    {{-- CHAT NOTIFICATIONS --}}
                    {{-- ================================================= --}}

                    <li class="nav-item dropdown dropdown-large">

                        {{-- Notification Bell --}}
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">

                            <span class="alert-count"
                                  id="notification-bell-count"
                                  style="display: none;">
                                0
                            </span>

                            <i class='bx bx-bell'></i>
                        </a>

                        {{-- Notification Dropdown --}}
                        <div class="dropdown-menu dropdown-menu-end">

                            {{-- Header --}}
                            <div class="msg-header">

                                <p class="msg-header-title">
                                    Notifications
                                </p>

                                <p class="msg-header-badge"
                                   id="notification-count">
                                    0 New
                                </p>

                            </div>

                            {{-- Notifications List --}}
                            <div class="header-notifications-list"
                                 id="notification-list">

                                <div class="text-center p-3">
                                    Loading notifications...
                                </div>

                            </div>

                            {{-- Footer --}}
                            <div class="text-center msg-footer">

                                <a href="{{ route('all.chat') }}"
                                   class="btn btn-primary w-100">
                                    View All Messages
                                </a>

                            </div>

                        </div>
                    </li>

                    {{-- ================================================= --}}
                    {{-- SHOPPING CART - PLACEHOLDER --}}
                    {{-- ================================================= --}}

                    <li class="nav-item dropdown dropdown-large">

                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">

                            <span class="alert-count">8</span>

                            <i class='bx bx-shopping-bag'></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">

                            <div class="msg-header">
                                <p class="msg-header-title">My Cart</p>
                                <p class="msg-header-badge">10 Items</p>
                            </div>

                            <div class="header-message-list">

                                @for ($i = 1; $i <= 9; $i++)
                                    <a class="dropdown-item" href="javascript:;">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="position-relative">
                                                <div class="cart-product rounded-circle bg-light">
                                                    <img src="{{ asset('assets/images/products/0' . ($i + 1) . '.png') }}"
                                                         alt="Product">
                                                </div>
                                            </div>

                                            <div class="flex-grow-1">
                                                <h6 class="cart-product-title mb-0">
                                                    Men White T-Shirt
                                                </h6>

                                                <p class="cart-product-price mb-0">
                                                    1 X $29.00
                                                </p>
                                            </div>

                                            <div>
                                                <p class="cart-price mb-0">
                                                    $250
                                                </p>
                                            </div>

                                        </div>

                                    </a>
                                @endfor

                            </div>

                            <div class="text-center msg-footer">

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="mb-0">Total</h5>
                                    <h5 class="mb-0 ms-auto">$489.00</h5>
                                </div>

                                <button class="btn btn-primary w-100">
                                    Checkout
                                </button>

                            </div>

                        </div>
                    </li>

                </ul>
            </div>

            {{-- ================================================= --}}
            {{-- ADMIN PROFILE --}}
            {{-- ================================================= --}}

            @php
                $profileData = Auth::user();
            @endphp

            <div class="user-box dropdown px-3">

                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                   href="#"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">

                    <img src="{{ !empty($profileData->photo)
                                ? url('upload/admin_images/' . $profileData->photo)
                                : url('upload/no_image.jpg') }}"
                         class="user-img"
                         alt="User Avatar">

                    <div class="user-info">

                        <p class="user-name mb-0">
                            {{ $profileData->name }}
                        </p>

                        <p class="designattion mb-0">
                            {{ $profileData->email }}
                        </p>

                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('admin.profile') }}">

                            <i class="bx bx-user fs-5"></i>
                            <span>Profile</span>

                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('admin.change.password') }}">

                            <i class="bx bx-cog fs-5"></i>
                            <span>Change Password</span>

                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('admin.logout') }}">

                            <i class="bx bx-log-out-circle"></i>
                            <span>Logout</span>

                        </a>
                    </li>

                </ul>
            </div>

        </nav>
    </div>
</header>


{{-- ================================================= --}}
{{-- CHAT NOTIFICATION JAVASCRIPT --}}
{{-- ================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const notificationCount = document.getElementById('notification-count');
    const notificationList = document.getElementById('notification-list');
    const bellCount = document.getElementById('notification-bell-count');

    if (!notificationCount || !notificationList || !bellCount) {
        return;
    }

    let isLoading = false;

    async function loadNotifications() {

        // Prevent overlapping requests
        if (isLoading) {
            return;
        }

        isLoading = true;

        try {

            const response = await fetch(
                @json(route('chat.notifications')),
                {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    credentials: 'same-origin'
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Notification request failed: ' + response.status
                );
            }

            const data = await response.json();

            const totalUnread = Number(data.totalUnread) || 0;

            const notifications = Array.isArray(data.notifications)
                ? data.notifications
                : [];

            // Update dropdown count
            notificationCount.textContent = totalUnread + ' New';

            // Update bell badge
            bellCount.textContent = totalUnread;

            bellCount.style.display = totalUnread > 0
                ? 'inline-block'
                : 'none';

            // Clear previous notifications
            notificationList.replaceChildren();

            // Empty state
            if (notifications.length === 0) {

                const emptyMessage = document.createElement('div');

                emptyMessage.className = 'text-center p-3';

                emptyMessage.textContent = 'No new messages';

                notificationList.appendChild(emptyMessage);

                return;
            }

            // Render notifications
            notifications.forEach(function (notification) {

                const link = document.createElement('a');

                link.className = 'dropdown-item';

                link.href = notification.url;

                const row = document.createElement('div');

                row.className = 'd-flex align-items-center';

                // Avatar
                const avatarWrapper = document.createElement('div');

                avatarWrapper.className = 'user-online me-3';

                const avatar = document.createElement('div');

                avatar.className =
                    'msg-avatar d-flex align-items-center justify-content-center';

                avatar.style.backgroundColor = '#25D366';
                avatar.style.color = '#ffffff';
                avatar.style.fontWeight = 'bold';
                avatar.style.flexShrink = '0';

                const clientName = notification.client_name || 'Client';

                avatar.textContent = clientName
                    .trim()
                    .split(/\s+/)
                    .slice(0, 2)
                    .map(name => name.charAt(0).toUpperCase())
                    .join('');

                avatarWrapper.appendChild(avatar);

                // Notification content
                const content = document.createElement('div');

                content.className = 'flex-grow-1';

                const title = document.createElement('h6');

                title.className = 'msg-name';

                title.textContent = clientName;

                const time = document.createElement('span');

                time.className = 'msg-time float-end';

                time.textContent = notification.time || '';

                title.appendChild(document.createTextNode(' '));

                title.appendChild(time);

                const message = document.createElement('p');

                message.className = 'msg-info';

                message.textContent = notification.message || '';

                content.appendChild(title);

                content.appendChild(message);

                row.appendChild(avatarWrapper);

                row.appendChild(content);

                link.appendChild(row);

                notificationList.appendChild(link);

            });

        } catch (error) {

            console.error('Unable to load chat notifications:', error);

        } finally {

            isLoading = false;

        }
    }

    // Load when the page opens
    loadNotifications();

    // Refresh every five seconds
    setInterval(loadNotifications, 5000);

});
</script>