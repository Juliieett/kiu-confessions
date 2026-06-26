@if(session('success'))
<div class="flash-banner flash-banner-success alert alert-success alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center gap-2">
        <span class="flash-banner-icon"><i class="bi bi-check-circle-fill"></i></span>
        <div>
            <strong class="d-block">Success</strong>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 flash-toast-container">
    <div id="flashToast" class="toast flash-toast flash-toast-success align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="flash-banner flash-banner-error alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center gap-2">
        <span class="flash-banner-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
        <div>
            <strong class="d-block">Error</strong>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 flash-toast-container">
    <div id="flashToast" class="toast flash-toast flash-toast-error align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

@if(session('success') || session('error'))
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('flashToast');
        if (toastEl) {
            bootstrap.Toast.getOrCreateInstance(toastEl).show();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endpush
@endif
