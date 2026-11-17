<!-- 1. Studio -->
<div>
    <label class="form-label" for="form-studio">1. Pilih Ruangan Studio</label>
    <select onchange="calculate_total(); updateSlotStatus();" id="form-studio" name="studio_id" required class="form-control form-select">
        <option value="" disabled <?= $form['studio_id'] ? '' : 'selected' ?>>-- Pilih Studio Foto --</option>
        <?php foreach ($studios as $s): ?>
            <option value="<?= (int)$s['id'] ?>"
                    data-harga="<?= (int)$s['harga_per_jam'] ?>"
                    <?= ($s['id'] == $form['studio_id']) ? 'selected' : '' ?>>
                <?= e($s['nama_studio']) ?> (<?= rupiah($s['harga_per_jam']) ?>/jam)
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- 2. Identitas -->
<div class="identity-group">
    <div class="grid-2-cols">
        <div>
            <label class="form-label" for="form-nama">2. Nama Pemesan / Client</label>
            <input type="text" id="form-nama" name="nama_pemesan" required maxlength="100"
                <?= $is_edit ? '' : 'placeholder="Contoh: Saffanah Syalwa"' ?>
                value="<?= e($form['nama_pemesan']) ?>" class="form-control">
        </div>
        <div>
            <label class="form-label" for="form-hp">Nomor WhatsApp / HP</label>
            <input type="tel" id="form-hp" name="no_hp" required maxlength="20"
                <?= $is_edit ? '' : 'placeholder="Contoh: 081234567890"' ?>
                value="<?= e($form['no_hp']) ?>" class="form-control">
        </div>
    </div>
    <?php if (!$is_edit): ?>
        <label class="check-label">
            <input type="checkbox" id="save-cookie-checkbox" name="remember" checked class="check-input">
            <span class="check-text"><i class="fa-solid cookie-icon"></i> Ingat pengisian ini</span>
        </label>
    <?php endif; ?>
</div>

<!-- 3. Tanggal & slot -->
<div class="slot-box">
    <div class="slot-header">
        <label class="form-label no-margin">3. Tanggal & Slot Jam Sesi</label>
        <div class="slot-legend">
            <span class="legend-item"><span class="dot dot-available"></span> Tersedia</span>
            <span class="legend-item"><span class="dot dot-booked"></span> Terisi</span>
            <span class="legend-item"><span class="dot dot-selected"></span> Dipilih</span>
        </div>
    </div>
    <div class="slot-grid-layout">
        <div>
            <!-- Mode edit: tanpa batas min agar tanggal lama tetap valid -->
            <input type="date" id="form-tanggal" onchange="updateSlotStatus()" name="tanggal"
                <?= $is_edit ? '' : 'min="' . date('Y-m-d') . '"' ?>
                value="<?= e($form['tanggal']) ?>" required class="form-control date-input">
        </div>
        <div class="slot-matrix-wrapper">
            <div id="slot-matrix-container" class="slot-matrix">
                <?php foreach ($all_time_slots as $ts): ?>
                    <button type="button" class="slot-chip"
                            data-id="<?= (int)$ts['id'] ?>"
                            data-slot="<?= e($ts['slot_waktu']) ?>"
                            onclick="selectSlot(this)">
                        <?= e($ts['slot_waktu']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" id="form-jam-selected" name="time_slots_id" value="<?= e($form['time_slots_id']) ?>">
        </div>
    </div>
</div>

<!-- 4. Add-ons -->
<div>
    <label class="form-label mb-2">4. Tambahan Layanan Opsional (Add-Ons)</label>
    <div id="addons-grid" class="addons-grid">
        <?php foreach ($addons as $addon): ?>
            <label class="addon-card">
                <div class="addon-info">
                    <input type="checkbox"
                        name="addons[]"
                        value="<?= (int)$addon['id'] ?>"
                        data-harga="<?= (int)$addon['harga'] ?>"
                        onchange="calculate_total()"
                        <?= in_array($addon['id'], $selected_addon_ids) ? 'checked' : '' ?>
                        class="addon-checkbox w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                    <span class="addon-name"><?= e($addon['nama_addon']) ?></span>
                </div>
                <span class="addon-price">+<?= rupiah($addon['harga']) ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</div>

<!-- Ringkasan harga -->
<div class="summary-box">
    <div class="summary-content">
        <div>
            <p class="summary-label"><?= $is_edit ? 'Total Pembayaran Terbaru' : 'Rincian Estimasi Pembayaran' ?></p>
            <h4 id="total-price-display" class="summary-total">Rp 0</h4>
        </div>
        <div id="price-breakdown-list" class="summary-list">
            <span><?= $is_edit ? 'Menghitung ulang rincian...' : 'Silakan pilih studio & slot jam' ?></span>
        </div>
    </div>
</div>
