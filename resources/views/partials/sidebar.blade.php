<nav class="list-group mb-4" aria-label="Account">

    <a href="{{ route('account.show') }}" class="list-group-item d-flex align-items-center py-3 {{ request()->route()->named('account.show') ? 'list-group-item-action active' : '' }}" @if(request()->route()->named('account.show')) aria-current="page" @endif>

        <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="me-1 {{ request()->route()->named('account.show') ? '' : 'text-primary' }}" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>

        My Account
    </a>

    <a href="{{ route('orders.index') }}" class="list-group-item d-flex align-items-center py-3 {{ request()->route()->named('orders.index') ? 'list-group-item-action active' : '' }}" @if(request()->route()->named('orders.index')) aria-current="page" @endif>

        <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" class="me-1 {{ request()->route()->named('orders.index') ? '' : 'text-primary' }}"
            fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6">
            </line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
        </svg>
        My Orders
    </a>

    <a href="{{ route('update.account.create') }}" class="list-group-item d-flex align-items-center py-3 {{ request()->route()->named('update.account.create') ? 'list-group-item-action active' : '' }}" @if(request()->route()->named('update.account.create')) aria-current="page" @endif>
        <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" class="me-1 {{ request()->route()->named('update.account.create') ? '' : 'text-primary' }}" stroke-width="2"
            fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>

        Update Account
    </a>

    <a href="{{ route('update.password.create') }}" class="list-group-item d-flex align-items-center py-3 {{ request()->route()->named('update.password.create') ? 'list-group-item-action active' : '' }}" @if(request()->route()->named('update.password.create')) aria-current="page" @endif>
        <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20" class="me-1 {{ request()->route()->named('update.password.create') ? '' : 'text-primary' }}" 
        stroke="currentColor" stroke-width="2"
            fill="none" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
        </svg>

        Update Password
    </a>

</nav>
