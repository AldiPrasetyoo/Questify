<div>

    <a href="{{ route('teacher.pertemuan') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali</a>
    <div class="flex items-center justify-between mt-2 mb-6">
        <h1 class="text-xl font-bold">Misi — {{ $pertemuan->judul }}</h1>
        <button wire:click="bukaForm" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">+ Tambah
            Misi</button>
    </div>

    @if($formTerbuka)
    <div class="bg-white border rounded-xl p-5 mb-6">
        <h2 class="font-semibold mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Misi</h2>
        <div class="grid gap-3 sm:grid-cols-2">
            <div>
                <label class="text-xs text-slate-500">Fase</label>
                <select wire:model="fase" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="pra_kelas">Pra-Kelas</option>
                    <option value="tatap_muka">Tatap Muka</option>
                    <option value="pasca_kelas">Pasca-Kelas</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-500">Urutan</label>
                <input type="number" wire:model="urutan" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Judul</label>
                <input type="text" wire:model="judul" class="w-full rounded-lg border-slate-300 text-sm">
                @error('judul') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Deskripsi</label>
                <textarea wire:model="deskripsi" rows="2" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>
            <div>
                <label class="text-xs text-slate-500">Estimasi (menit)</label>
                <input type="number" wire:model="estimasi_menit" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div>
                <label class="text-xs text-slate-500">Poin Maksimal</label>
                <input type="number" wire:model="poin_maksimal" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Misi Prasyarat (terkunci sampai misi ini selesai)</label>
                <select wire:model="misi_prasyarat_id" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="">— Tanpa prasyarat (langsung terbuka) —</option>
                    @foreach($kandidatPrasyarat as $namaPertemuan => $daftarMisi)
                    <optgroup label="{{ $namaPertemuan }}">
                        @foreach($daftarMisi as $kp)
                        <option value="{{ $kp->id }}">
                            [{{ strtoupper(str_replace('_', ' ', $kp->fase)) }}] {{ $kp->judul }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('misi_prasyarat_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="wajib_kerja_kelompok"> Wajib kerja kelompok
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="aktif"> Aktif
            </label>
        </div>
        <div class="mt-4 flex gap-2">
            <button wire:click="simpan" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Simpan</button>
            <button wire:click="tutupForm" class="text-sm px-4 py-2 rounded-lg border">Batal</button>
        </div>
    </div>
    @endif

    @foreach(['pra_kelas' => 'Pra-Kelas', 'tatap_muka' => 'Tatap Muka', 'pasca_kelas' => 'Pasca-Kelas'] as $key =>
    $label)
    @php $grup = $misis->where('fase', $key); @endphp
    @if($grup->count())
    <h3 class="text-sm font-semibold text-slate-500 mt-6 mb-2">{{ $label }}</h3>
    <div class="bg-white rounded-xl border overflow-hidden mb-4">
        <table class="w-full text-sm">
            <tbody>
                @foreach($grup as $m)
                <tr class="border-t first:border-t-0">
                    <td class="p-3 w-10 text-slate-400">{{ $m->urutan }}</td>
                    <td class="p-3 font-medium">
                        {{ $m->judul }}
                        @if($m->prasyarat)
                        <span class="text-xs text-slate-400 block">
                            🔒 setelah "{{ $m->prasyarat->judul }}"
                            @if($m->prasyarat->pertemuan_id !== $m->pertemuan_id && $m->prasyarat->pertemuan)
                            <span class="text-indigo-500 font-medium">({{ $m->prasyarat->pertemuan->judul }})</span>
                            @endif
                        </span>
                        @endif
                    </td>
                    <td class="p-3 text-slate-400">{{ $m->kontens_count }} konten</td>
                    <td class="p-3 text-right space-x-2">
                        <a href="{{ route('konten.index', $m) }}" class="text-indigo-600 hover:underline">Kelola
                            Konten</a>
                        <button wire:click="bukaForm({{ $m->id }})" class="text-slate-500 hover:underline">Edit</button>
                        <button wire:click="hapus({{ $m->id }})" wire:confirm="Hapus misi ini?"
                            class="text-rose-600 hover:underline">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endforeach
</div>