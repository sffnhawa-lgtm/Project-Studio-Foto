<div class="col">
    <div class="card h-100 shadow-sm rounded-4 overflow-hidden">
        <div class="position-relative bg-dark" style="height: 11rem;">
            <img src="<?= e($s['gambar']) ?>" alt="<?= e($s['nama_studio']) ?>" class="w-100 h-100 object-fit-cover opacity-75">
            <span class="badge rounded-pill bg-dark bg-opacity-75 text-danger-subtle border border-danger border-opacity-25 position-absolute top-0 end-0 m-3">
                <?= e($s['tema']) ?>
            </span>
        </div>

        <div class="card-body p-4">
            <h4 class="fs-6 fw-bolder mb-1"><?= e($s['nama_studio']) ?></h4>
            <p class="small text-secondary mb-0">
                <i class="fa-solid fa-users text-danger me-1"></i> Kapasitas Maks: <?= (int)$s['kapasitas_maks'] ?> orang
            </p>
            <div class="d-flex justify-content-between align-items-baseline border-top mt-3 pt-3">
                <span class="small text-secondary">Tarif Sewa</span>
                <span class="fw-bolder text-danger"><?= rupiah($s['harga_per_jam']) ?> <small class="fw-normal text-secondary">/jam</small></span>
            </div>
        </div>

        <div class="card-footer bg-transparent border-0 p-4 pt-0">
            <button type="button" onclick="select_studio(<?= (int)$s['id'] ?>)"
                    class="btn btn-dark btn-sm w-100 fw-bold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2"
                    style="--bs-btn-hover-bg: var(--bs-danger); --bs-btn-hover-border-color: var(--bs-danger);">
                <i class="fa-solid fa-calendar-plus"></i> Pilih Studio Ini
            </button>
        </div>
    </div>
</div> 
