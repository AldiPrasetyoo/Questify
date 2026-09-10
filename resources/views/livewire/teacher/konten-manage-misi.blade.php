<div>

    <a href="{{ route('teacher.misi', $misi->pertemuan) }}" class="text-sm text-indigo-600 hover:underline">&larr;
        Kembali</a>
    <div class="flex items-center justify-between mt-2 mb-6">
        <h1 class="text-xl font-bold">Konten — {{ $misi->judul }}</h1>
        <button wire:click="bukaForm" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">+ Tambah
            Konten</button>
    </div>

    @if($formTerbuka)
    <div class="bg-white border rounded-xl p-5 mb-6">
        <h2 class="font-semibold mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Konten</h2>
        <div class="grid gap-3 sm:grid-cols-2 mb-3">
            <div>
                <label class="text-xs text-slate-500">Urutan</label>
                <input type="number" wire:model="urutan" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div>
                <label class="text-xs text-slate-500">Tipe Konten</label>
                <select wire:model.live="tipe" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="teks_web">Teks Web</option>
                    <option value="aset_ppt">Aset PPT (gambar slide)</option>
                    <option value="koding">Koding Interaktif</option>
                    <option value="kuis">Kuis</option>
                    <option value="refleksi">Refleksi Terbuka</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Judul (opsional)</label>
                <input type="text" wire:model="judul" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
        </div>

        {{-- Field dinamis sesuai tipe --}}
        @if($tipe === 'teks_web')
        <label class="text-xs text-slate-500">Konten</label>
        <div x-data="{
                        editor: null,
                        init() {
                            this.editor = new Quill(this.$refs.quillEl, {
                                theme: 'snow',
                                modules: { toolbar: [
                                    ['bold', 'italic', 'underline'],
                                    [{ header: [2, 3, false] }],
                                    ['blockquote', 'code-block'],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['link'],
                                    ['clean'],
                                ] },
                            });
                            this.editor.root.innerHTML = this.$wire.konten_html || '';
                            this.editor.on('text-change', () => {
                                this.$wire.set('konten_html', this.editor.root.innerHTML, false);
                            });
                        }
                    }" wire:ignore>
            <div x-ref="quillEl" style="min-height: 220px; background: white;"></div>
        </div>
        @error('konten_html') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        <p class="text-xs text-slate-400 mt-1">Editor visual — hasilnya tetap tersimpan sebagai HTML biasa (bisa
            dizoom/disalin/dicari siswa), bukan gambar.</p>
        @endif

        @if($tipe === 'aset_ppt')
        <label class="text-xs text-slate-500">Gambar Slide (PNG/JPG, idealnya 1600x900)</label>
        <input type="file" wire:model="gambar_upload" class="w-full text-sm">
        @error('gambar_upload') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
        @if($gambar_upload)
        <img src="{{ $gambar_upload->temporaryUrl() }}" class="mt-2 rounded-lg border max-h-48">
        @elseif($path_gambar_lama)
        <img src="{{ Storage::url($path_gambar_lama) }}" class="mt-2 rounded-lg border max-h-48">
        @endif
        @endif

        @if($tipe === 'koding')
        <div class="mb-3">
            <label class="text-xs text-slate-500">Komponen Koding</label>
            <select wire:model="komponen_koding" class="w-full rounded-lg border-slate-300 text-sm">
                @foreach($daftarKomponenKoding as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <label class="text-xs text-slate-500">Konfigurasi (JSON — soal, opsi, jawaban_benar, dst)</label>
        <textarea wire:model="konfigurasi_koding_json" rows="6"
            class="w-full rounded-lg border-slate-300 text-sm font-mono"></textarea>
        @error('konfigurasi_koding_json') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
        @endif

        @if($tipe === 'refleksi')
        <label class="text-xs text-slate-500">Pertanyaan Refleksi</label>
        <textarea wire:model="pertanyaan_refleksi" rows="3"
            class="w-full rounded-lg border-slate-300 text-sm"></textarea>
        @error('pertanyaan_refleksi') <p class="text-rose-600 text-xs">{{ $message }}</p> @enderror
        @endif

        @if($tipe === 'kuis')
        <p class="text-sm text-slate-400 italic">Simpan konten ini dulu, lalu kelola soal-soalnya dari daftar di bawah.
        </p>
        @endif

        <div class="mt-4 flex gap-2">
            <button wire:click="simpan" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Simpan</button>
            <button wire:click="tutupForm" class="text-sm px-4 py-2 rounded-lg border">Batal</button>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Tipe</th>
                    <th class="p-3">Judul</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($kontens as $k)
                <tr class="border-t">
                    <td class="p-3 text-slate-400">{{ $k->urutan }}</td>
                    <td class="p-3">
                        <span
                            class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600">{{ $k->tipe }}</span>
                    </td>
                    <td class="p-3">{{ $k->judul ?: '(tanpa judul)' }}
                        @if($k->tipe === 'kuis') <span class="text-xs text-slate-400">— {{ $k->kuis_soals_count }}
                            soal</span> @endif
                    </td>
                    <td class="p-3 text-right space-x-2">
                        @if($k->tipe === 'kuis')
                        <a href="{{ route('soal.index', $k) }}" class="text-indigo-600 hover:underline">Kelola
                            Soal</a>
                        @endif
                        <button wire:click="bukaForm({{ $k->id }})" class="text-slate-500 hover:underline">Edit</button>
                        <button wire:click="hapus({{ $k->id }})" wire:confirm="Hapus konten ini?"
                            class="text-rose-600 hover:underline">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-slate-400">Belum ada konten.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>