<div>

    <a href="{{ route('konten.index', $kontenMisi->misi) }}" class="text-sm text-indigo-600 hover:underline">&larr;
        Kembali</a>
    <div class="flex items-center justify-between mt-2 mb-6">
        <h1 class="text-xl font-bold">Soal — {{ $kontenMisi->judul ?: 'Kuis' }}</h1>
        <button wire:click="bukaForm" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">+ Tambah
            Soal</button>
    </div>

    @if($formTerbuka)
    <div class="bg-white border rounded-xl p-5 mb-6">
        <h2 class="font-semibold mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Soal</h2>
        <div class="grid gap-3">
            <div>
                <label class="text-xs text-slate-500">Urutan</label>
                <input type="number" wire:model="urutan" class="w-32 rounded-lg border-slate-300 text-sm">
            </div>
            <div>
                <label class="text-xs text-slate-500">Pertanyaan</label>
                <textarea wire:model="pertanyaan" rows="2"
                    class="w-full rounded-lg border-slate-300 text-sm"></textarea>
                @error('pertanyaan') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
            </div>
            @foreach(['a', 'b', 'c', 'd'] as $huruf)
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold w-6">{{ strtoupper($huruf) }}</span>
                <input type="text" wire:model="pilihan_{{ $huruf }}" class="flex-1 rounded-lg border-slate-300 text-sm">
            </div>
            @endforeach
            <div>
                <label class="text-xs text-slate-500">Kunci Jawaban</label>
                <select wire:model="kunci_jawaban" class="rounded-lg border-slate-300 text-sm">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-500">Pembahasan (opsional)</label>
                <textarea wire:model="pembahasan" rows="2"
                    class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button wire:click="simpan" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Simpan</button>
            <button wire:click="tutupForm" class="text-sm px-4 py-2 rounded-lg border">Batal</button>
        </div>
    </div>
    @endif

    <div class="space-y-3">
        @forelse($soals as $s)
        <div class="bg-white rounded-xl border p-4 text-sm flex items-start justify-between">
            <div>
                <p class="font-medium">{{ $s->urutan }}. {{ $s->pertanyaan }}</p>
                <ul class="text-slate-500 mt-1 space-y-0.5">
                    @foreach($s->pilihan as $huruf => $teks)
                    <li class="{{ strtoupper($huruf) === $s->kunci_jawaban ? 'text-emerald-600 font-medium' : '' }}">
                        {{ strtoupper($huruf) }}. {{ $teks }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="space-x-2 shrink-0">
                <button wire:click="bukaForm({{ $s->id }})" class="text-slate-500 hover:underline">Edit</button>
                <button wire:click="hapus({{ $s->id }})" wire:confirm="Hapus soal ini?"
                    class="text-rose-600 hover:underline">Hapus</button>
            </div>
        </div>
        @empty
        <p class="text-slate-400 text-sm">Belum ada soal.</p>
        @endforelse
    </div>
</div>