<div class="max-w-6xl mx-auto px-4 py-8 font-['Nunito']" x-data="{ modalTerbuka: false, detailPertemuan: null }">

    @if (session()->has('success'))
    <div
        class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-5 py-4 rounded-2xl text-xs font-black flex items-center justify-between">
        <span>✔ {{ session('success') }}</span>
    </div>
    @endif

    {{-- CARI PERTEMUAN TERAKHIR --}}
    @php
    $riwayatTerakhir = \App\Models\Rapor::where('user_id', $user->id)
    ->with('pertemuan')
    ->latest('updated_at')
    ->first();
    $pertemuanTerakhir = $riwayatTerakhir?->pertemuan ?? \App\Models\Pertemuan::where('aktif',
    true)->orderBy('urutan')->first();
    @endphp

    {{-- HEADER RAPOR --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8 bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <div>
            <span
                class="text-[10px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full">
                Rekapitulasi Akademik
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 mt-2">
                Rapor Peserta Didik — <span class="text-[#00c2cb]">{{ $user->name }}</span>
            </h1>
            <p class="text-xs font-semibold text-slate-400 mt-1 mb-4">
                Laporan capaian kemajuan belajar, poin perolehan, durasi waktu, dan rekomendasi kesiapan post-test.
            </p>

            {{-- TOMBOL LANJUTKAN MISI TERAKHIR --}}
            @if($pertemuanTerakhir && auth()->user()->role !== 'teacher')
            <a href="{{ route('pertemuan.show', $pertemuanTerakhir) }}"
                class="inline-flex items-center gap-2 bg-[#00c2cb] hover:bg-teal-500 text-white text-[11px] font-black px-5 py-2.5 rounded-xl shadow-md shadow-teal-500/20 transition-all">
                Lanjutkan ke Pertemuan {{ $pertemuanTerakhir->urutan }}
            </a>
            @endif
        </div>

        <div class="bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl flex items-center gap-4 shrink-0">
            <span class="text-3xl">🏆</span>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase">Akumulasi Poin</p>
                <p class="text-2xl font-black text-[#ff8a00]">{{ $user->total_poin }} <span
                        class="text-sm text-slate-400 font-bold">Pts</span></p>
            </div>
        </div>
    </div>

    {{-- TABEL RAPOR PERTEMUAN --}}
    <h2 class="text-lg font-black text-slate-800 mb-3 flex items-center gap-2">
        <span>📊</span> Capaian Nilai per Pertemuan
    </h2>
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-x-auto mb-8">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-400 tracking-wider">
                <tr>
                    <th class="p-4">Pertemuan</th>
                    <th class="p-4 text-center">Pra-Kelas</th>
                    <th class="p-4 text-center">Tatap Muka</th>
                    <th class="p-4 text-center">Pasca-Kelas</th>
                    <th class="p-4 text-center">Waktu Belajar</th>
                    <th class="p-4 text-center">Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rapors as $r)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    {{-- SEL JUDUL PERTEMUAN (KLIK UNTUK MODAL) --}}
                    <td class="p-4 font-black text-slate-800">
                        <button type="button"
                            data-info="{{ json_encode(['judul' => $r->pertemuan->judul, 'misis' => $r->rincian_waktu_misi]) }}"
                            x-on:click="detailPertemuan = JSON.parse($el.dataset.info); modalTerbuka = true"
                            class="border-b-2 border-dashed border-[#00c2cb]/50 cursor-pointer transition-colors hover:text-[#00c2cb] hover:border-[#00c2cb] text-left focus:outline-none">
                            {{ $r->pertemuan->judul }}
                        </button>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Klik untuk detail
                        </p>
                    </td>

                    <td class="p-4 text-center font-bold text-slate-600">
                        {{ $r->nilai_pra_kelas !== null ? $r->nilai_pra_kelas.' pts' : '—' }}
                    </td>

                    <td class="p-4 text-center font-bold text-slate-600">
                        {{ $r->nilai_tatap_muka !== null ? $r->nilai_tatap_muka.' pts' : '—' }}
                    </td>

                    <td class="p-4 text-center font-bold text-slate-600">
                        {{ $r->nilai_pasca_kelas !== null ? $r->nilai_pasca_kelas.' pts' : '—' }}
                    </td>

                    <td class="p-4 text-center">
                        @if($r->total_durasi)
                        <span
                            class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-xl text-[11px] font-black tracking-wide border border-indigo-100">
                            ⏱️ {{ $r->total_durasi }}
                        </span>
                        @else
                        <span class="text-slate-300 font-bold">—</span>
                        @endif
                    </td>

                    <td class="p-4 text-center font-black text-[#ff8a00]">
                        {{ $r->total_poin }}
                    </td>
                </tr>

                {{-- BAGIAN CATATAN GURU --}}
                <tr class="bg-slate-50/80">
                    <td colspan="6" class="px-5 py-4 border-t border-slate-100">
                        @if(auth()->user()->role === 'teacher')
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Catatan /
                                    Umpan Balik Guru:</span>
                                <button wire:click="toggleEdit({{ $r->pertemuan_id }})"
                                    class="text-xs font-extrabold text-[#00c2cb] hover:underline flex items-center gap-1">
                                    <span>{{ ($isEditing[$r->pertemuan_id] ?? false) ? '✖ Batal' : '✏️ Edit Catatan' }}</span>
                                </button>
                            </div>

                            @if($isEditing[$r->pertemuan_id] ?? false)
                            <div class="space-y-2 pt-1">
                                <textarea wire:model.defer="catatanGuru.{{ $r->pertemuan_id }}" rows="2"
                                    placeholder="Tulis catatan evaluasi untuk pertemuan ini..."
                                    class="w-full text-xs bg-white border border-slate-200 rounded-xl p-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb] resize-none"></textarea>
                                <div class="flex justify-end">
                                    <button wire:click="simpanCatatan({{ $r->pertemuan_id }})"
                                        class="bg-[#00c2cb] hover:bg-teal-500 text-white text-[10px] font-black px-4 py-2 rounded-xl shadow-sm transition-all">
                                        Simpan Catatan
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="pt-1">
                                <p class="text-xs font-semibold text-slate-600 italic">
                                    "{{ $r->catatan_guru ?? 'Belum ada catatan untuk pertemuan ini.' }}"
                                </p>
                            </div>
                            @endif
                        </div>
                        @else
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Catatan Guru:
                            </p>
                            <p class="text-xs font-semibold text-slate-600">
                                {{ $r->catatan_guru ?? 'Belum ada catatan dari guru untuk pertemuan ini.' }}
                            </p>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-slate-400 font-bold text-sm">
                        Belum ada data rapor — selesaikan misi pertama terlebih dahulu untuk melihat rekapitulasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- KESIAPAN POST TEST --}}
    <h2 class="text-lg font-black text-slate-800 mb-3 flex items-center gap-2">
        <span>🎯</span> Analisis Kesiapan Post Test
    </h2>
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-400 tracking-wider">
                <tr>
                    <th class="p-4">Pertemuan</th>
                    <th class="p-4 text-center">Nilai Uji Kesiapan</th>
                    <th class="p-4">Rekomendasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($kesiapan as $k)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-4 font-black text-slate-800">{{ $k['pertemuan']->judul }}</td>
                    <td class="p-4 text-center font-black text-slate-700">
                        {{ $k['persentase'] !== null ? $k['persentase'].'%' : 'Belum dikerjakan' }}
                    </td>
                    <td class="p-4 font-extrabold text-xs">
                        @if($k['perlu_diulang'])
                        <span
                            class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 px-3 py-1.5 rounded-xl">
                            ⚠ Disarankan mengulang materi ini
                        </span>
                        @elseif($k['persentase'] !== null)
                        <span
                            class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-xl">
                            ✔ Sudah cukup siap
                        </span>
                        @else
                        <span class="text-slate-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- LENCANA YANG DIMILIKI --}}
    <h2 class="text-lg font-black text-slate-800 mb-3 flex items-center gap-2">
        <span>🏅</span> Lencana Penghargaan
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($lencanas as $l)
        <div
            class="bg-white border border-slate-100 shadow-sm rounded-2xl p-4 text-center hover:border-teal-200 hover:shadow-md transition-all group flex flex-col items-center justify-center">
            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">
                {{ $l->ikon }}
            </div>
            <div class="text-xs font-black text-slate-700 leading-snug break-words hyphens-auto w-full">
                {{ $l->nama }}
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2rem] border border-slate-100 p-8 w-full text-center">
            <p class="text-slate-400 text-sm font-bold">Belum ada lencana yang diperoleh siswa ini.</p>
        </div>
        @endforelse
    </div>

    {{-- =========================================================================
         MODAL POPUP DETAIL PERTEMUAN DENGAN ACCORDION (ALPINE.JS)
         ========================================================================= --}}
    <div x-show="modalTerbuka" style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="bg-slate-50 w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
            @click.away="modalTerbuka = false" x-transition:enter="transition ease-out duration-300 delay-75"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

            {{-- Header Modal --}}
            <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-[#00c2cb]/10 text-[#00c2cb] flex items-center justify-center text-xl shadow-inner shrink-0">
                        📋
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rincian Performa &
                            Jawaban</p>
                        <h3 class="text-sm font-extrabold text-slate-800" x-text="detailPertemuan?.judul"></h3>
                    </div>
                </div>
                <button @click="modalTerbuka = false"
                    class="text-slate-400 hover:text-rose-500 bg-slate-50 hover:bg-rose-50 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                    <span class="font-bold text-lg">&times;</span>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-6 overflow-y-auto custom-scrollbar">
                <template x-for="(misis, fase) in detailPertemuan?.misis" :key="fase">
                    <div class="mb-6 last:mb-0">
                        {{-- Label Kategori Fase --}}
                        <span
                            class="inline-block text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg border border-slate-200 bg-white text-slate-500 mb-3 shadow-sm"
                            x-text="fase"></span>

                        <div class="space-y-3">
                            <template x-for="misi in misis" :key="misi.judul">
                                {{-- Card Misi dengan state Buka/Tutup --}}
                                <div x-data="{ bukaJawaban: false }"
                                    class="bg-white border border-slate-100 rounded-2xl flex flex-col shadow-sm transition-colors overflow-hidden"
                                    :class="bukaJawaban ? 'border-[#00c2cb]/50 shadow-md' : 'hover:border-[#00c2cb]/30'">

                                    {{-- Area Header Misi (Klik untuk buka detail) --}}
                                    <div @click="bukaJawaban = !bukaJawaban"
                                        class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50/50 transition-colors">
                                        <div class="flex-1">
                                            <div class="font-extrabold text-slate-700 text-sm leading-snug"
                                                x-text="misi.judul"></div>
                                            <div class="text-[10px] font-bold mt-1 flex items-center gap-1"
                                                :class="bukaJawaban ? 'text-[#00c2cb]' : 'text-slate-400'">
                                                <span
                                                    x-text="bukaJawaban ? 'Tutup Detail Jawaban' : 'Lihat Detail Jawaban'"></span>
                                                <span class="text-[8px]" x-text="bukaJawaban ? '▲' : '▼'"></span>
                                            </div>
                                        </div>

                                        {{-- Statistik Misi Global --}}
                                        <div
                                            class="flex items-center gap-4 shrink-0 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                                            <div class="flex flex-col items-end w-16">
                                                <span
                                                    class="text-[9px] text-slate-400 font-black uppercase tracking-wider">Durasi</span>
                                                <span class="font-mono text-xs font-black text-indigo-600"
                                                    x-text="misi.selesai ? misi.durasi : '—'"></span>
                                            </div>
                                            <div class="w-px h-6 bg-slate-200"></div>
                                            <div class="flex flex-col items-start w-12">
                                                <span
                                                    class="text-[9px] text-slate-400 font-black uppercase tracking-wider">Poin
                                                    Total</span>
                                                <span class="font-mono text-xs font-black"
                                                    :class="misi.selesai ? 'text-[#ff8a00]' : 'text-slate-300'"
                                                    x-text="misi.selesai ? '+' + misi.poin : '0'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Area Detail Jawaban per Konten (terbuka jika diklik) --}}
                                    <div x-show="bukaJawaban" x-collapse
                                        class="border-t border-slate-100 bg-slate-50 p-4">
                                        <template x-if="misi.detail_jawaban && misi.detail_jawaban.length > 0">
                                            <div class="space-y-3">
                                                <template x-for="(jawaban, index) in misi.detail_jawaban" :key="index">

                                                    <div class="bg-white border rounded-xl p-3 flex flex-col sm:flex-row gap-4 shadow-sm"
                                                        :class="jawaban.status === 'selesai' ? 'border-slate-200' : 'border-rose-100 bg-rose-50/30'">

                                                        <div class="flex-1 space-y-2">
                                                            <div class="flex items-center gap-2">
                                                                <span x-show="jawaban.status === 'belum'"
                                                                    class="text-xs">🔒</span>
                                                                <span
                                                                    class="text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 px-2 py-1 rounded-md"
                                                                    x-text="jawaban.tipe"></span>
                                                            </div>
                                                            <p class="text-xs font-bold text-slate-700 leading-relaxed"
                                                                x-text="jawaban.pertanyaan"></p>

                                                            <div class="p-3 rounded-lg border"
                                                                :class="jawaban.status === 'selesai' ? 'bg-indigo-50/50 border-indigo-50' : 'bg-rose-50/50 border-rose-100'">
                                                                <span class="text-[9px] font-black uppercase block mb-1"
                                                                    :class="jawaban.status === 'selesai' ? 'text-indigo-400' : 'text-rose-400'">Respons
                                                                    / Status:</span>
                                                                <div class="text-xs font-semibold text-slate-600"
                                                                    x-html="jawaban.teks_jawaban"></div>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="shrink-0 flex items-center sm:items-start sm:flex-col justify-between sm:justify-start gap-1 min-w-[70px] sm:text-right border-t sm:border-t-0 sm:border-l border-slate-100 pt-2 sm:pt-0 sm:pl-4">
                                                            <span
                                                                class="text-[9px] font-black uppercase tracking-wider text-slate-400">Skor
                                                                Diperoleh</span>
                                                            <span class="text-sm font-black"
                                                                :class="jawaban.status === 'selesai' ? 'text-[#ff8a00]' : 'text-slate-300'"
                                                                x-text="'+' + jawaban.poin"></span>
                                                        </div>
                                                    </div>

                                                </template>
                                            </div>
                                        </template>

                                        {{-- Fallback jika misi ternyata belum memiliki isi --}}
                                        <template x-if="!misi.detail_jawaban || misi.detail_jawaban.length === 0">
                                            <div class="text-center py-5">
                                                <span class="text-2xl block mb-2 opacity-40">📂</span>
                                                <p
                                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                    Tidak ada konten di dalam misi ini</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Fallback kosong jika PERTEMUAN sama sekali belum ada aktivitas --}}
                <template x-if="!detailPertemuan?.misis || Object.keys(detailPertemuan.misis).length === 0">
                    <div class="text-center py-10 bg-white rounded-2xl border border-slate-100">
                        <span class="text-3xl opacity-50 block mb-2">📭</span>
                        <p class="text-slate-400 text-sm font-black uppercase tracking-wider mt-2">Belum Dikerjakan</p>
                        <p class="text-xs font-semibold text-slate-400 mt-1">Siswa belum memulai aktivitas apapun pada
                            pertemuan ini.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>