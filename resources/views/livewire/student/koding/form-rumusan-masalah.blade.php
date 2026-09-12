<div
    class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm font-['Nunito'] max-w-4xl mx-auto space-y-8">

    {{-- HEADER INSTRUKSI --}}
    <div>
        <div class="flex items-center gap-3 mb-2">
            <div
                class="w-10 h-10 rounded-xl bg-[#00c2cb]/10 text-[#00c2cb] flex items-center justify-center text-xl shadow-inner shrink-0">
                📝
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-800">Kartu Masalah: Status Kehadiran</h3>
                <p class="text-xs font-semibold text-slate-400">Lengkapi bersama kelompokmu untuk menentukan status
                    kehadiran otomatis dari jam kedatangan.</p>
            </div>
        </div>
    </div>

    @php
    // Data dipindahkan ke blok PHP agar tidak menyebabkan Malformed Foreach Error
    $daftarStatus = [
    'hadir' => 'Hadir',
    'terlambat' => 'Terlambat',
    'alpa' => 'Alpa'
    ];
    $daftarBentuk = [
    'if_tunggal' => 'If Tunggal',
    'if_else' => 'If - Else',
    'if_else_if' => 'If - Else If'
    ];
    @endphp

    {{-- NAMA KELOMPOK --}}
    <div class="relative">
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
            Nama Kelompok <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <input type="text" wire:model.live="namaKelompok" placeholder="Tuliskan nama/anggota kelompok..."
                class="w-full rounded-xl text-sm py-3 px-4 transition-colors focus:ring-2 focus:ring-[#00c2cb] focus:border-[#00c2cb] pr-10 {{ $errors->has('namaKelompok') ? 'border-rose-300 bg-rose-50 text-rose-600' : 'border-slate-200 bg-slate-50 text-slate-700' }}">

            {{-- Indikator Terisi --}}
            @if(!empty($namaKelompok))
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 font-black animate-fade-in">✔</span>
            @endif
        </div>
        @error('namaKelompok') <p class="text-[10px] font-bold text-rose-500 mt-1.5 flex items-center gap-1">⚠ Wajib
            diisi</p> @enderror
    </div>

    {{-- TABEL KONDISI --}}
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
            Lengkapi Tabel Kondisi <span class="text-rose-500">*</span>
        </label>
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Status</th>
                        <th class="p-4">Batas Bawah</th>
                        <th class="p-4">Batas Atas</th>
                        <th class="p-4">Bentuk Kondisi (C++)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($daftarStatus as $key => $label)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-black text-slate-700">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs 
                                {{ $key == 'hadir' ? 'bg-emerald-100 text-emerald-700' : ($key == 'terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="p-3">
                            <input type="text" wire:model.live="tabelKondisi.{{ $key }}.batas_bawah" placeholder="—"
                                class="w-20 rounded-xl text-xs text-center focus:border-[#00c2cb] focus:ring-[#00c2cb] {{ $errors->has('tabelKondisi.'.$key.'.batas_bawah') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }}">
                        </td>
                        <td class="p-3">
                            <input type="text" wire:model.live="tabelKondisi.{{ $key }}.batas_atas" placeholder="—"
                                class="w-20 rounded-xl text-xs text-center focus:border-[#00c2cb] focus:ring-[#00c2cb] {{ $errors->has('tabelKondisi.'.$key.'.batas_atas') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }}">
                        </td>
                        <td class="p-3 relative">
                            <input type="text" wire:model.live="tabelKondisi.{{ $key }}.kondisi"
                                placeholder='mis. jam <= "07:00"'
                                class="w-full rounded-xl text-xs font-mono focus:border-[#00c2cb] focus:ring-[#00c2cb] pr-8 {{ $errors->has('tabelKondisi.'.$key.'.kondisi') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }}">

                            {{-- Indikator Terisi --}}
                            @if(!empty($tabelKondisi[$key]['kondisi']))
                            <span
                                class="absolute right-6 top-1/2 -translate-y-1/2 text-emerald-500 text-xs font-black">✔</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- BENTUK PERCABANGAN & ALASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                Bentuk Percabangan <span class="text-rose-500">*</span>
            </label>
            <div class="grid grid-cols-3 gap-2">
                @foreach($daftarBentuk as $val => $label)
                <button wire:click="$set('bentukDipilih', '{{ $val }}')"
                    class="py-3 px-2 rounded-xl text-xs font-black transition-all border-2 flex items-center justify-center gap-1.5
                    {{ $bentukDipilih === $val ? 'bg-[#00c2cb]/10 border-[#00c2cb] text-[#00c2cb] shadow-sm' : 'border-slate-200 text-slate-500 hover:border-slate-300 hover:bg-slate-50' }}">
                    @if($bentukDipilih === $val) <span>✔</span> @endif
                    {{ $label }}
                </button>
                @endforeach
            </div>
            @error('bentukDipilih') <p class="text-[10px] font-bold text-rose-500 mt-1.5 flex items-center gap-1">⚠
                Pilih salah satu bentuk percabangan</p> @enderror
        </div>

        <div class="relative">
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                Alasan Pemilihan <span class="text-rose-500">*</span>
            </label>
            <textarea wire:model.live="alasan" rows="3"
                placeholder="Mengapa kelompokmu memilih bentuk percabangan tersebut? Jelaskan logikanya..."
                class="w-full rounded-xl text-sm focus:border-[#00c2cb] focus:ring-[#00c2cb] resize-none pr-8 {{ $errors->has('alasan') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }}"></textarea>

            @if(!empty($alasan))
            <span class="absolute right-3 top-9 text-emerald-500 font-black animate-fade-in">✔</span>
            @endif
            @error('alasan') <p class="text-[10px] font-bold text-rose-500 mt-1 flex items-center gap-1">⚠ Alasan harus
                diisi</p> @enderror
        </div>
    </div>

    {{-- RENCANA KERJA --}}
    <div class="relative">
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
            Rencana Kerja Kelompok <span class="text-rose-500">*</span>
        </label>
        <textarea wire:model.live="rencanaKerja" rows="2"
            placeholder="Siapa yang bertugas menulis kode? Siapa yang mengecek logika? Tuliskan pembagian tugas kalian..."
            class="w-full rounded-xl text-sm focus:border-[#00c2cb] focus:ring-[#00c2cb] resize-none pr-8 {{ $errors->has('rencanaKerja') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }}"></textarea>

        @if(!empty($rencanaKerja))
        <span class="absolute right-3 top-9 text-emerald-500 font-black animate-fade-in">✔</span>
        @endif
        @error('rencanaKerja') <p class="text-[10px] font-bold text-rose-500 mt-1 flex items-center gap-1">⚠ Rencana
            kerja wajib diisi</p> @enderror
    </div>

    {{-- AREA PESAN EVALUASI (SETELAH CEK) --}}
    @if($sudahDicek)
    <div
        class="p-4 rounded-xl border flex items-start gap-3 animate-fade-in-up {{ $semuaBenar ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-amber-50 border-amber-200 text-amber-700' }}">
        <span class="text-xl mt-0.5">{{ $semuaBenar ? '✅' : '⚠️' }}</span>
        <div>
            <h4 class="font-black text-sm">
                {{ $semuaBenar ? 'Kerja Bagus! Seluruh Isian Tepat' : 'Masih Ada Kekeliruan' }}</h4>
            <p class="text-xs font-semibold mt-1 opacity-90">
                {{ $semuaBenar ? 'Data sudah valid dan logikanya terstruktur. Silakan kirimkan hasil diskusi kalian.' : 'Ada bagian yang belum tepat (seperti format kondisi atau batas waktu). Kalian tetap boleh mengirimkannya sekarang, atau memperbaikinya terlebih dahulu.' }}
            </p>
        </div>
    </div>
    @endif

    {{-- TOMBOL AKSI --}}
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
        <button wire:click="cek"
            class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black px-6 py-2.5 rounded-xl text-xs transition-colors">
            Cek Validasi Isian
        </button>
        <button wire:click="kirim"
            class="bg-[#ff8a00] hover:bg-orange-600 text-white font-black px-8 py-2.5 rounded-xl text-xs shadow-md shadow-orange-500/20 transition-all flex items-center gap-2">
            Kirim & Lanjut &rarr;
        </button>
    </div>

</div>