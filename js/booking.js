const formatRupiah = (angka) => 'Rp ' + angka.toLocaleString('id-ID');

function calculate_total(){
    const selectStudio = document.getElementById('form-studio');
    const selectedOption = selectStudio.options[selectStudio.selectedIndex];
    const hargaStudio = parseInt(selectedOption.dataset.harga) || 0;

    let totalAddon = 0;
    let breakdownHTML = `<div>• ${selectedOption.text.split('(')[0].trim()}: <b>${formatRupiah(hargaStudio)}</b></div>`;

    document.querySelectorAll('.addon-checkbox:checked').forEach(item => {
        const harga = parseInt(item.dataset.harga);
        const nama = item.nextElementSibling.innerText;
        totalAddon += harga;
        breakdownHTML += `<div>• ${nama}: <b>${formatRupiah(harga)}</b></div>`;
    });

    document.getElementById('total-price-display').innerText = formatRupiah(hargaStudio + totalAddon);

    if (bookingConfig.showBreakdown){
        document.getElementById('price-breakdown-list').innerHTML = breakdownHTML;
    }
}

function selectSlot(btn){
    if (btn.disabled) return;
    document.getElementById('form-jam-selected').value = btn.dataset.id;
    updateSlotStatus();
}

function updateSlotStatus(){
    const studioId = parseInt(document.getElementById('form-studio').value);
    const tanggal = document.getElementById('form-tanggal').value;
    const selectedId = document.getElementById('form-jam-selected').value;

    if (!studioId || !tanggal) return;

    const bookedSlotIds = bookingConfig.pemesanan_data
        .filter(p => parseInt(p.studio_id) === studioId
                  && p.tanggal === tanggal
                  && parseInt(p.id) !== bookingConfig.currentBookingId)
        .map(p => String(p.time_slots_id));

    document.querySelectorAll('.slot-chip').forEach(btn => {
        const isBooked = bookedSlotIds.includes(btn.dataset.id);
        const isSelected = selectedId === btn.dataset.id && !isBooked;

        let icon = '';
        if (isBooked) icon = '<i class="fa-solid fa-lock"></i> ';
        else if (isSelected) icon = '<i class="fa-solid fa-check"></i> ';

        btn.disabled = isBooked;
        btn.classList.toggle('is-selected', isSelected);
        btn.innerHTML = icon + btn.dataset.slot;
    });
}
