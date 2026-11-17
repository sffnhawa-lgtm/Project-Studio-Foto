<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:shadow-lg transition-all flex flex-col justify-between group">
    <div>
        <div class="relative h-44 overflow-hidden bg-slate-900">
            <img src="<?= e($s['gambar']) ?>" alt="<?= e($s['nama_studio']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90">
            <span class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-md text-rose-300 text-[11px] font-bold px-2.5 py-1 rounded-full border border-rose-500/30">
                <?= e($s['tema']) ?>
            </span>
        </div>
        <div class="p-5">
            <h4 class="font-extrabold text-base text-slate-800"><?= e($s['nama_studio']) ?></h4>
            <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-users text-rose-500 mr-1"></i> Kapasitas Maks: <?= (int)$s['kapasitas_maks'] ?> orang</p>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-baseline justify-between">
                <span class="text-xs text-slate-400 font-medium">Tarif Sewa</span>
                <span class="text-base font-extrabold text-rose-600"><?= rupiah($s['harga_per_jam']) ?> <span class="text-xs font-normal text-slate-400">/jam</span></span>
            </div>
        </div>
    </div>
    <div class="p-5 pt-0">
        <button onclick="select_studio(<?= (int)$s['id'] ?>)" class="w-full bg-slate-900 hover:bg-rose-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="fa-solid fa-calendar-plus"></i> Pilih Studio Ini
        </button>
    </div>
</div>
