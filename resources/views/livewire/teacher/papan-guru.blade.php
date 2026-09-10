<div class="max-w-7xl mx-auto px-4 py-8 font-['Nunito'] space-y-8">
    {{-- Header --}}
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('papan-guru-detail') }}"
                class="text-xs font-black text-slate-400 hover:text-[#00c2cb] transition-colors">&larr; Kembali ke
                Ringkasan</a>
        </div>
        <h1 class="text-2xl font-black text-slate-800">Papan Guru — {{ $pertemuan->judul }}</h1>
        <p class="text-slate-400 text-sm font-semibold">Progres pra-kelas siswa & jawaban refleksi, dibaca sebelum sesi
            tatap muka.</p>
    </div>

    {{-- Tabel Progres Pra-Kelas --}}
    <div>
        <h2 class="font-extrabold text-slate-700 mb-3 text-base">Progres Pra-Kelas</h2>
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Progres</th>
                        <th class="p-4">Poin Pertemuan Ini</th>
                        <th class="p-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswa as $s)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-black text-slate-800">{{ $s->name }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-32 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#00c2cb] rounded-full transition-all duration-500"
                                        style="width: {{ $s->persen_pra_kelas }}%"></div>
                                </div>
                                <span class="text-xs font-black text-slate-500">{{ $s->persen_pra_kelas }}%</span>
                            </div>
                        </td>
                        <td class="p-4 font-black text-[#ff8a00]">{{ number_format($s->poin_pertemuan_ini) }} pts</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('rapor', ['userId' => $s->id]) }}"
                                class="inline-flex items-center gap-1 bg-teal-50 text-[#00c2cb] hover:bg-[#00c2cb] hover:text-white font-extrabold text-xs px-3.5 py-2 rounded-xl transition-all">
                                Lihat rapor
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-400 font-bold">Belum ada siswa terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Grid Refleksi Siswa Per Kolom --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-extrabold text-slate-700 text-base">Jawaban Refleksi Siswa</h2>
                <p class="text-slate-400 text-xs font-semibold">Tanggapan refleksi individual pra-kelas pada pertemuan
                    ini.</p>
            </div>
            <span class="text-xs font-black bg-cyan-50 text-[#00aeb8] px-3 py-1 rounded-full">
                {{ count($jawabanRefleksi) }} Tanggapan
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($jawabanRefleksi as $r)
            <div
                class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    {{-- Header Kartu Siswa --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-500 to-teal-400 text-white flex items-center justify-center text-xs font-black shrink-0 shadow-sm">
                            {{ strtoupper(substr($r['nama_siswa'], 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-black text-slate-800 truncate">{{ $r['nama_siswa'] }}</h4>
                            <span class="text-[10px] font-bold text-slate-400 block">{{ $r['waktu'] }}</span>
                        </div>
                        <span class="text-base">💬</span>
                    </div>

                    {{-- Pertanyaan Refleksi --}}
                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">
                        {{ $r['pertanyaan'] }}
                    </p>

                    {{-- Isi Jawaban --}}
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                        <p class="text-slate-700 font-semibold text-xs leading-relaxed italic">
                            "{{ $r['teks'] }}"
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                <span class="text-3xl block mb-2">📝</span>
                <p class="text-slate-400 text-sm font-bold">Belum ada jawaban refleksi masuk untuk pertemuan ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>