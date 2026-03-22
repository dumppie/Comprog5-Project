@extends('layouts.base')
@section('title', 'Verify Email')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-pastry">
                    <div class="card-body p-4 p-md-5 text-center">
                        <i class="fas fa-envelope-open-text fa-3x mb-3" style="color: var(--pastry-caramel);"></i>
                        <h2 class="font-serif mb-3" style="color: var(--pastry-brown);">Verify Your Email</h2>
                        <p class="mb-3">We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Click it to activate your account.</p>
                        <p class="text-muted small mb-4">If you didn't receive it or the link expired, click below to resend.</p>
                        
                        <form method="POST" action="{{ route('verification.send') }}" id="resend-form" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-pastry" id="resend-btn">Resend verification email</button>
                        </form>
                        <p class="mt-4 mb-0">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
document.getElementById('resend-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('resend-btn');
    const originalText = btn.innerHTML;
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: new FormData(this)
        });
        
        const data = await response.json();
        
        if (data.verified) {
            // User is already verified, show notification and redirect
            showNotification(data.message, 'success');
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            // Email sent successfully
            showNotification(data.message, 'success');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to resend verification email. Please try again.', 'danger');
    } finally {
        // Reset button state
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endpush
