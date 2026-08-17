<article class="card border-0 h-100">

    <div class="bg-brown text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mb-3"
        style="width: 5rem; height: 5rem; font-size: 1.35rem;">
        {{ $member['initials'] }}
    </div>

    <div class="card-body px-0">

        <h5 class="card-title mb-1">{{ $member['name'] }}</h5>

        <p class="small text-brown fw-semibold mb-2">{{ $member['role'] }}</p>

        <p class="card-text text-muted mb-2">{{ $member['bio'] }}</p>

        <p class="small text-muted mb-0">
            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2"
                fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            </svg>
            {{ implode(', ', $member['languages']) }}
        </p>

    </div>
</article>
