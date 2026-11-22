function scroll_to_catalog(){
    document.getElementById('katalog-section').scrollIntoView({ behavior: 'smooth' });
}

// ----- Katalog -----
document.getElementById('search-input').addEventListener('input', function (){
    const keyword = this.value.toLowerCase().trim();

    document.querySelectorAll('#studio-grid > div').forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(keyword) ? '' : 'none';
    });
});

function filter_by_theme(btn){
    const theme = btn.innerText.trim();

    if (theme === 'Semua Tema'){
        window.location.href = 'index.php';
    } else {
        window.location.href = `?kategori_filter=${encodeURIComponent(theme)}`;
    }
}

function select_studio(studio_id){
    const selectStudio = document.getElementById('form-studio');
    selectStudio.value = studio_id;
    selectStudio.dispatchEvent(new Event('change'));

    document.getElementById('booking-section').scrollIntoView({ behavior: 'smooth' });
}

// ----- Toast -----
function showToast(type, title, msg){
    const iconBg = document.getElementById('toast-icon-bg');
    const icon = document.getElementById('toast-icon');

    if (type === 'error'){
        iconBg.className = "p-2 rounded-xl bg-rose-600 text-white";
        icon.className = "fa-solid fa-triangle-exclamation";
    } else {
        iconBg.className = "p-2 rounded-xl bg-emerald-600 text-white";
        icon.className = "fa-solid fa-circle-check";
    }

    document.getElementById('toast-title').textContent = title;
    document.getElementById('toast-message').textContent = msg;

    document.getElementById('toast-container').classList.remove('hidden');
    setTimeout(() => hide_toast(), 5000);
}

function hide_toast(){
    document.getElementById('toast-container').classList.add('hidden');
}

// ----- Validasi -----
document.getElementById('booking-form').addEventListener('submit', function (e){
    if (!document.getElementById('form-jam-selected').value){
        e.preventDefault();
        showToast('error', 'Pilih Slot Jam!', 'Harap klik salah satu slot waktu hijau yang tersedia.');
    }
});
