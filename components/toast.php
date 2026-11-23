<!-- Notifikasi (toast): disembunyikan dengan d-none, ditampilkan lewat showToast() di js/dashboard.js -->
<div id="toast-container" class="position-fixed bottom-0 end-0 w-100 p-3 d-none" style="max-width: 28rem; z-index: 1080;">
    <div class="d-flex align-items-center justify-content-between gap-3 p-3 bg-white border rounded-4 shadow-lg">

        <div class="d-flex align-items-start gap-3">
            <div id="toast-icon-bg" class="d-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0" style="width: 2.5rem; height: 2.5rem;">
                <i id="toast-icon"></i>
            </div>
            <div>
                <h5 id="toast-title" class="fw-bold small text-dark mb-0"></h5>
                <p id="toast-message" class="small text-secondary mb-0"></p>
            </div>
        </div>

        <button type="button" class="btn-close" aria-label="Tutup" onclick="hide_toast()"></button>
    </div>
</div>