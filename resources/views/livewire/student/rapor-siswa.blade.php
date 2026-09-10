<div class="max-w-6xl mx-auto px-4 py-8 font-['Nunito']">

    @if (session()->has('success'))
    <div
        class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-5 py-4 rounded-2xl text-xs font-black flex items-center justify-between">
        <span>✔ {{ session('success') }}</span>
    </div>
    @endif

    {{-- HEADER RAPOR --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <div>
            <span
                class="text-[10px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full">
                Rekapitulasi Akademik
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 mt-2">
                Rapor Peserta Didik — <span class="text-[#00c2cb]">{{ $user->name }}</span>
            </h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">
                Laporan capaian kemajuan belajar, poin perolehan, dan rekomendasi kesiapan post-test.
            </p>
        </div>

        <div class="bg-slate-50 border border-slate-100 px-5 py-3 rounded-2xl flex items-center gap-3 shrink-0">
            <span class="text-2xl">🏆</span>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase">Akumulasi Poin</p>
                <p class="text-xl font-black text-[#ff8a00]">{{ $user->total_poin }} <span
                        class="text-xs text-slate-400 font-bold">Pts</span></p>
            </div>
        </div>
    </div>

    {{-- TABEL RAPOR PERTEMUAN --}}
    <h2 class="text-lg font-black text-slate-800 mb-3 flex items-center gap-2">
        <span>📊</span> Capaian Nilai per Pertemuan
    </h2>
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-400 tracking-wider">
                <tr>
                    <th class="p-4">Pertemuan</th>
                    <th class="p-4 text-center">Pra-Kelas</th>
                    <th class="p-4 text-center">Tatap Muka</th>
                    <th class="p-4 text-center">Pasca-Kelas</th>
                    <th class="p-4 text-center">Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rapors as $r)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-4 font-black text-slate-800">{{ $r->pertemuan->judul }}</td>
                    <td class="p-4 text-center font-bold text-slate-600">
                        {{ $r->nilai_pra_kelas !== null ? $r->nilai_pra_kelas.' pts' : '—' }}
                    </td>
                    <td class="p-4 text-center font-bold text-slate-600">
                        {{ $r->nilai_tatap_muka !== null ? $r->nilai_tatap_muka.' pts' : '—' }}
                    </td>
                    <td class="p-4 text-center font-bold text-slate-600">
                        {{ $r->nilai_pasca_kelas !== null ? $r->nilai_pasca_kelas.' pts' : '—' }}
                    </td>
                    <td class="p-4 text-center font-black text-[#ff8a00]">
                        {{ $r->total_poin }}
                    </td>
                </tr>

                {{-- BAGIAN CATATAN GURU (DIPISAH BERDASARKAN ROLE & MODE EDIT) --}}
                <tr class="bg-slate-50/80">
                    <td colspan="5" class="px-5 py-4 border-t border-slate-100">
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
                    <td colspan="5" class="p-10 text-center text-slate-400 font-bold text-sm">
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
                            ⚠ Disarankan mengulang materi ini sebelum post test
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

            {{-- Teks turun ke bawah secara rapi (break-words) --}}
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

</div>