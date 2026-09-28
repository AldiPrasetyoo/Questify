<div
    class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm font-['Nunito'] max-w-4xl mx-auto space-y-8">

    {{-- HEADER INSTRUKSI --}}
    <div class="flex items-center gap-3 mb-2">
        <div
            class="w-10 h-10 rounded-xl bg-[#00c2cb]/10 text-[#00c2cb] flex items-center justify-center text-xl shadow-inner shrink-0">
            📝</div>
        <div>
            <h3 class="text-lg font-black text-slate-800">Form Rumusan Masalah Kelompok (Pertemuan {{ $pertemuanKe }})
            </h3>
            <p class="text-xs font-semibold text-slate-400">Lengkapi perencanaan logika program bersama kelompokmu.</p>
        </div>
    </div>

    {{-- 1. DAFTAR ANGGOTA & PERAN --}}
    <div class="space-y-3">
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider flex items-center gap-1">Daftar
            Anggota & Peran <span class="text-rose-500">*</span></label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div><label class="text-[10px] font-bold text-slate-400 mb-1 block">Juru Kode</label>
                <input type="text" wire:model.live="peran.juru_kode" placeholder="Nama siswa..."
                    class="w-full rounded-xl text-xs py-2.5 px-3 border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb]">
            </div>
            <div><label class="text-[10px] font-bold text-slate-400 mb-1 block">Penyusun Alur</label>
                <input type="text" wire:model.live="peran.penyusun_alur" placeholder="Nama siswa..."
                    class="w-full rounded-xl text-xs py-2.5 px-3 border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb]">
            </div>
            <div><label class="text-[10px] font-bold text-slate-400 mb-1 block">Penguji Program</label>
                <input type="text" wire:model.live="peran.penguji_program" placeholder="Nama siswa..."
                    class="w-full rounded-xl text-xs py-2.5 px-3 border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb]">
            </div>
            <div><label class="text-[10px] font-bold text-slate-400 mb-1 block">Juru Bicara</label>
                <input type="text" wire:model.live="peran.juru_bicara" placeholder="Nama siswa..."
                    class="w-full rounded-xl text-xs py-2.5 px-3 border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb]">
            </div>
        </div>
    </div>

    {{-- 2. ISIAN DINAMIS & TABEL BERDASARKAN PERTEMUAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                Isian 1.1: {{ $pertemuanKe == 3 ? 'Diulang untuk tiap siswa' : 'Data masukan' }} <span
                    class="text-rose-500">*</span>
            </label>
            <textarea wire:model.live="isian1" rows="3"
                placeholder="{{ $pertemuanKe == 3 ? 'Tuliskan proses yang masuk ke dalam blok perulangan...' : 'Sebutkan data apa saja yang perlu dimasukkan ke dalam program...' }}"
                class="w-full rounded-xl text-sm border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb] resize-none"></textarea>
        </div>
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                Isian 1.2:
                {{ $pertemuanKe == 1 ? 'Banyaknya kemungkinan status' : ($pertemuanKe == 2 ? 'Bagian mana yang diulang/cukup satu kali' : 'Setelah seluruh data selesai') }}
                <span class="text-rose-500">*</span>
            </label>
            <textarea wire:model.live="isian2" rows="3"
                placeholder="{{ $pertemuanKe == 1 ? 'Ada berapa banyak kemungkinan status? Sebutkan...' : ($pertemuanKe == 2 ? 'Tuliskan bagian mana yang perlu diulang, dan mana yang cukup sekali...' : 'Tuliskan proses yang dijalankan setelah perulangan berakhir...') }}"
                class="w-full rounded-xl text-sm border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb] resize-none"></textarea>
        </div>
    </div>

    {{-- TABEL PERTEMUAN 1: STATUS KEHADIRAN --}}
    @if($pertemuanKe == 1)
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Tabel Kondisi <span
                class="text-rose-500">*</span></label>
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-[11px] font-black uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Status</th>
                        <th class="p-4">Batas Bawah</th>
                        <th class="p-4">Batas Atas</th>
                        <th class="p-4">Bentuk Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach(['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'alpa' => 'Alpa'] as $key => $label)
                    <tr>
                        <td class="p-4 font-black text-slate-700">{{ $label }}</td>
                        <td class="p-3"><input type="text" wire:model.live="tabelKondisi.{{ $key }}.batas_bawah"
                                placeholder="mis. 0"
                                class="w-20 rounded-xl text-xs text-center border-slate-200 focus:border-[#00c2cb]">
                        </td>
                        <td class="p-3"><input type="text" wire:model.live="tabelKondisi.{{ $key }}.batas_atas"
                                placeholder="mis. 700"
                                class="w-20 rounded-xl text-xs text-center border-slate-200 focus:border-[#00c2cb]">
                        </td>
                        <td class="p-3"><input type="text" wire:model.live="tabelKondisi.{{ $key }}.kondisi"
                                placeholder="mis. jam <= 700"
                                class="w-full rounded-xl text-xs border-slate-200 focus:border-[#00c2cb]"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- TABEL PERTEMUAN 2: ANATOMI PERULANGAN --}}
    @if($pertemuanKe == 2)
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Tabel Anatomi
            Perulangan <span class="text-rose-500">*</span></label>
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-[11px] font-black uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4 w-[25%]">Bagian</th>
                        <th class="p-4 w-[35%]">Isi</th>
                        <th class="p-4 w-[40%]">Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach(['pencacah' => 'Nilai Awal Pencacah', 'berhenti' => 'Kondisi Berhenti', 'perubahan' =>
                    'Perubahan Nilai'] as $key => $label)
                    <tr>
                        <td class="p-4 font-black text-slate-700 text-xs">{{ $label }}</td>
                        <td class="p-3"><input type="text" wire:model.live="tabelAnatomi.{{ $key }}.isi"
                                placeholder="{{ $key == 'pencacah' ? 'mis. i = 1' : ($key == 'berhenti' ? 'mis. i <= jumlah' : 'mis. i++') }}"
                                class="w-full rounded-xl text-xs border-slate-200 focus:border-[#00c2cb]"></td>
                        <td class="p-3"><input type="text" wire:model.live="tabelAnatomi.{{ $key }}.alasan"
                                placeholder="Tuliskan alasannya..."
                                class="w-full rounded-xl text-xs border-slate-200 focus:border-[#00c2cb]"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- TABEL PERTEMUAN 3: PERENCANAAN VARIABEL --}}
    @if($pertemuanKe == 3)
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Tabel Perencanaan
            Variabel <span class="text-rose-500">*</span></label>
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-sm text-left min-w-[600px]">
                <thead class="bg-slate-50 text-[11px] font-black uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Variabel</th>
                        <th class="p-4 text-center">Nilai Awal</th>
                        <th class="p-4">Diletakkan Di Mana</th>
                        <th class="p-4">Kapan Bertambah (Min-Max)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach(['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'alpa' => 'Alpa'] as $key => $label)
                    <tr>
                        <td class="p-4 font-black text-slate-700">{{ $label }}</td>
                        <td class="p-3"><input type="text" wire:model.live="tabelVariabel.{{ $key }}.nilai_awal"
                                placeholder="mis. 0"
                                class="w-16 rounded-xl text-xs text-center border-slate-200 focus:border-[#00c2cb]">
                        </td>
                        <td class="p-3">
                            <select wire:model.live="tabelVariabel.{{ $key }}.letak"
                                class="w-full rounded-xl text-xs border-slate-200 focus:border-[#00c2cb]">
                                <option value="">- Pilih Letak -</option>
                                <option value="sebelum">Sebelum Perulangan</option>
                                <option value="didalam">Di Dalam</option>
                                <option value="setelah">Setelah Selesai</option>
                            </select>
                        </td>
                        <td class="p-3 flex gap-2">
                            <input type="text" wire:model.live="tabelVariabel.{{ $key }}.batas_bawah" placeholder="Min"
                                class="w-16 rounded-xl text-xs text-center border-slate-200 focus:border-[#00c2cb]">
                            <input type="text" wire:model.live="tabelVariabel.{{ $key }}.batas_atas" placeholder="Max"
                                class="w-16 rounded-xl text-xs text-center border-slate-200 focus:border-[#00c2cb]">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- 3. PILIHAN & ALASAN DINAMIS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">
                Pilihan
                {{ $pertemuanKe == 3 ? 'Letak Persentase' : ($pertemuanKe == 2 ? 'Bentuk Perulangan' : 'Bentuk Percabangan') }}
                <span class="text-rose-500">*</span>
            </label>
            <div class="flex flex-col gap-2">
                @php
                if($pertemuanKe == 1) $opsi = ['if_tunggal' => 'If Tunggal', 'if_else' => 'If - Else', 'if_else_if' =>
                'If - Else If'];
                elseif($pertemuanKe == 2) $opsi = ['for' => 'For Loop', 'while' => 'While Loop', 'do_while' => 'Do-While
                Loop'];
                else $opsi = ['sebelum' => 'Sebelum Perulangan', 'didalam' => 'Di Dalam Perulangan', 'setelah' =>
                'Setelah Perulangan Selesai'];
                @endphp
                @foreach($opsi as $val => $label)
                <label
                    class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer hover:bg-slate-50 {{ $pilihanBentuk === $val ? 'bg-[#00c2cb]/10 border-[#00c2cb] text-[#00c2cb]' : 'border-slate-200 text-slate-600' }}">
                    <input type="radio" wire:model.live="pilihanBentuk" value="{{ $val }}"
                        class="text-[#00c2cb] focus:ring-[#00c2cb]">
                    <span class="text-xs font-black">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Alasan Pemilihan
                <span class="text-rose-500">*</span></label>
            <textarea wire:model.live="alasanPilihan" rows="5"
                placeholder="Jelaskan alasan kelompokmu di balik pemilihan logika tersebut..."
                class="w-full rounded-xl text-sm border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb] resize-none"></textarea>
        </div>
    </div>

    {{-- 4. RENCANA KERJA --}}
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">Form
            Rencana Kerja Kelompok <span class="text-rose-500">*</span></label>
        <textarea wire:model.live="rencanaKerja" rows="2"
            placeholder="Siapa yang bertugas menulis kode? Siapa yang mengecek logika? Tuliskan kesepakatan pembagian tugas kalian di sini..."
            class="w-full rounded-xl text-sm border-slate-200 focus:border-[#00c2cb] focus:ring-[#00c2cb] resize-none"></textarea>
    </div>

    {{-- PESAN EVALUASI & TOMBOL --}}
    @if($sudahDicek)
    <div
        class="p-4 rounded-xl border flex items-start gap-3 {{ $semuaBenar ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-amber-50 border-amber-200 text-amber-700' }}">
        <span class="text-xl mt-0.5">{{ $semuaBenar ? '✅' : '⚠️' }}</span>
        <div>
            <h4 class="font-black text-sm">
                {{ $semuaBenar ? 'Kerja Bagus! Seluruh Rencana Tepat' : 'Masih Ada Kekeliruan' }}</h4>
            <p class="text-xs font-semibold mt-1 opacity-90">
                {{ $semuaBenar ? 'Semua logika terstruktur dengan baik.' : 'Periksa kembali isian dan logika tabel yang belum tepat.' }}
            </p>
        </div>
    </div>
    @endif

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
        <button wire:click="cek"
            class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black px-6 py-2.5 rounded-xl text-xs transition-colors">Cek
            Validasi Rencana</button>
        <button wire:click="kirim"
            class="bg-[#ff8a00] hover:bg-orange-600 text-white font-black px-8 py-2.5 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">Kirim
            & Lanjut &rarr;</button>
    </div>
</div>