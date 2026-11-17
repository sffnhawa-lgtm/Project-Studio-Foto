<div id="toast-container" class="fixed bottom-5 right-5 z-50 max-w-md w-full px-4 hidden transition-all duration-300 transform translate-y-2">
    <div id="toast-box" class="p-4 rounded-2xl border flex items-center justify-between shadow-2xl bg-white/90 backdrop-blur-md">
        <div class="flex items-start space-x-3">
            <div id="toast-icon-bg" class="p-2 rounded-xl text-white">
                <i id="toast-icon" class="text-lg"></i>
            </div>
            <div>
                <h5 id="toast-title" class="font-bold text-sm text-slate-800"></h5>
                <p id="toast-message" class="text-xs text-slate-600 leading-relaxed mt-0.5"></p>
            </div>
        </div>
        <button type="button" onclick="hide_toast()" class="text-slate-400 hover:text-slate-600 p-1 ml-3">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>
