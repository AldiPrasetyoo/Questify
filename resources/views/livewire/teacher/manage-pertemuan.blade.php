<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold">Kelola Pertemuan</h1>
            <p class="text-xs text-slate-500">Atur modul, urutan, dan daftar misi belajar.</p>
        </div>

        <button wire:click="bukaForm"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
            + Tambah Pertemuan
        </button>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('sukses'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl mb-5">
        {{ session('sukses') }}
    </div>
    @endif

    @if($formTerbuka)
    <div class="bg-white border rounded-xl p-5 mb-6 shadow-sm">
        <h2 class="font-semibold mb-4 text-gray-800">
            {{ $editId ? 'Edit' : 'Tambah' }} Pertemuan
        </h2>

        <div class="grid gap-3 sm:grid-cols-2">
            <div>
                <label class="text-xs text-slate-500">Urutan</label>
                <input type="number" wire:model="urutan" class="w-full rounded-lg border-slate-300 text-sm">
                @error('urutan') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs text-slate-500">Tanggal Tatap Muka</label>
                <input type="date" wire:model="tanggal_tatap_muka" class="w-full rounded-lg border-slate-300 text-sm">
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Judul</label>
                <input type="text" wire:model="judul" class="w-full rounded-lg border-slate-300 text-sm">
                @error('judul') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Deskripsi</label>
                <textarea wire:model="deskripsi" rows="2" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Tujuan Pembelajaran</label>
                <textarea wire:model="tujuan_pembelajaran" rows="2"
                    class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" wire:model="aktif" class="rounded border-slate-300 text-indigo-600">
                    Aktif (tampil ke siswa)
                </label>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button wire:click="simpan"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                Simpan
            </button>
            <button wire:click="tutupForm" class="text-sm px-4 py-2 rounded-lg border hover:bg-slate-50 transition">
                Batal
            </button>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600 text-left">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Judul</th>
                    <th class="p-3">Misi</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($pertemuans as $p)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="p-3 font-semibold text-slate-700">{{ $p->urutan }}</td>

                    <td class="p-3 font-medium text-slate-800">
                        {{ $p->judul }}
                    </td>

                    <td class="p-3 text-slate-600">
                        {{ $p->misis_count ?? 0 }} misi
                    </td>

                    <td class="p-3">
                        <span
                            class="text-xs px-2.5 py-1 rounded-full font-bold {{ $p->aktif ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td class="p-3 text-right space-x-3">
                        {{-- Tombol Kelola Misi (Pastikan rute ini ada di web.php) --}}
                        <a href="{{ route('teacher.misi', $p) }}" class="text-indigo-600 hover:underline
                        font-medium">Kelola Misi</a>

                        <button wire:click="bukaForm({{ $p->id }})"
                            class="text-slate-600 hover:text-indigo-600 font-medium">
                            Edit
                        </button>

                        <button wire:click="hapus({{ $p->id }})"
                            wire:confirm="Hapus pertemuan ini beserta semua misinya?"
                            class="text-rose-600 hover:text-rose-700 font-medium">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                        Belum ada data pertemuan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>