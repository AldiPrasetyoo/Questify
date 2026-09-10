<div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold">Kelola Lencana</h1>
        <button wire:click="bukaForm" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">+ Tambah
            Lencana</button>
    </div>

    @if($formTerbuka)
    <div class="bg-white border rounded-xl p-5 mb-6">
        <h2 class="font-semibold mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Lencana</h2>
        <div class="grid gap-3 sm:grid-cols-2">
            <div>
                <label class="text-xs text-slate-500">Kode (unik, huruf/underscore)</label>
                <input type="text" wire:model="kode" class="w-full rounded-lg border-slate-300 text-sm"
                    placeholder="sang_pemecah_kondisi">
                @error('kode') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-slate-500">Ikon (emoji)</label>
                <input type="text" wire:model="ikon" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Nama</label>
                <input type="text" wire:model="nama" class="w-full rounded-lg border-slate-300 text-sm">
                @error('nama') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Deskripsi</label>
                <textarea wire:model="deskripsi" rows="2" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Syarat Perolehan (deskripsi manusiawi — pengecekan otomatis
                    menyusul)</label>
                <input type="text" wire:model="syarat" class="w-full rounded-lg border-slate-300 text-sm"
                    placeholder="Selesaikan semua misi pra-kelas Pertemuan 1 tanpa kesalahan">
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button wire:click="simpan" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Simpan</button>
            <button wire:click="tutupForm" class="text-sm px-4 py-2 rounded-lg border">Batal</button>
        </div>
    </div>
    @endif

    <div class="grid gap-3 sm:grid-cols-2">
        @forelse($lencanas as $l)
        <div class="bg-white rounded-xl border p-4 flex items-start justify-between text-sm">
            <div class="flex gap-3">
                <div class="text-2xl">{{ $l->ikon }}</div>
                <div>
                    <p class="font-medium">{{ $l->nama }}</p>
                    <p class="text-slate-500 text-xs">{{ $l->deskripsi }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $l->users_count }} siswa memiliki</p>
                </div>
            </div>
            <div class="space-x-2 shrink-0">
                <button wire:click="bukaForm({{ $l->id }})" class="text-slate-500 hover:underline">Edit</button>
                <button wire:click="hapus({{ $l->id }})" wire:confirm="Hapus lencana ini?"
                    class="text-rose-600 hover:underline">Hapus</button>
            </div>
        </div>
        @empty
        <p class="text-slate-400 text-sm">Belum ada lencana.</p>
        @endforelse
    </div>
</div>