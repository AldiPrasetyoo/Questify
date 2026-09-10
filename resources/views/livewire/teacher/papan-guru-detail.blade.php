<div class="min-h-screen bg-[#f8fafc] font-['Nunito'] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- PERBANDINGAN TIGA PERTEMUAN --}}
        <div>
            <h2 class="text-2xl font-black text-slate-800 mb-2">Perbandingan Tiga Pertemuan</h2>
            <p class="text-sm font-semibold text-slate-400 mb-6">
                Klik pada salah satu kartu pertemuan untuk melihat rekapitulasi data spesifik per pertemuan.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @foreach($sebaranPerPertemuan as $s)
                <a href="{{ route('papan-guru', $s['pertemuan']->id) }}"
                    class="bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-teal-200 rounded-[2rem] p-7 transition-all group block relative overflow-hidden">

                    <p class="text-slate-400 text-xs font-black uppercase tracking-wider mb-1">
                        Pertemuan {{ $s['pertemuan']->urutan }}
                    </p>
                    <p
                        class="font-black text-xl mb-5 text-slate-800 group-hover:text-[#00c2cb] transition-colors pr-10">
                        {{ $s['pertemuan']->judul }}
                    </p>

                    <p
                        class="text-5xl font-black {{ $s['rata_rata'] !== null && $s['rata_rata'] < 75 ? 'text-[#ff8a00]' : 'text-[#00c2cb]' }}">
                        {{ $s['rata_rata'] !== null ? $s['rata_rata'].'%' : '—' }}
                    </p>
                    <p class="text-slate-400 text-xs font-bold mt-2">
                        rata-rata uji kesiapan ({{ $s['total_mengerjakan'] }} siswa)
                    </p>

                    @if($s['jumlah_di_bawah_ambang'] > 0)
                    <div
                        class="mt-5 inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 text-xs font-extrabold px-3 py-1.5 rounded-xl">
                        <span>⚠</span> {{ $s['jumlah_di_bawah_ambang'] }} siswa di bawah 75%
                    </div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>

        {{-- REFLEKSI SISWA PER KOLOM / KARTU --}}
        <div>
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Catatan Refleksi Siswa</h2>
                    <p class="text-slate-400 text-sm font-semibold mt-0.5">Tanggapan refleksi individual yang ditulis
                        oleh siswa.</p>
                </div>
                <span class="text-xs font-black bg-cyan-50 text-[#00aeb8] px-3.5 py-1.5 rounded-full">
                    {{ count($jawabanRefleksiTeratas) }} Refleksi
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($jawabanRefleksiTeratas as $refleksi)
                <div
                    class="bg-white border border-slate-100 shadow-sm rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition-all">
                    <div>
                        {{-- Header Kartu: Avatar & Nama Siswa --}}
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-teal-400 text-white flex items-center justify-center text-xs font-black shrink-0 shadow-sm">
                                {{ strtoupper(substr($refleksi['nama_siswa'], 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-black text-slate-800 truncate">
                                    {{ $refleksi['nama_siswa'] }}
                                </h4>
                                <span class="text-[10px] font-bold text-slate-400 block">
                                    {{ $refleksi['pertemuan'] ? 'Pertemuan ' . $refleksi['pertemuan'] : 'Misi Belajar' }}
                                    • {{ $refleksi['waktu'] }}
                                </span>
                            </div>
                            <span class="text-base">💬</span>
                        </div>

                        {{-- Isi Refleksi --}}
                        <div class="bg-slate-50 border border-slate-100/80 rounded-xl p-3.5">
                            <p class="text-slate-600 text-xs font-semibold leading-relaxed italic">
                                "{{ $refleksi['teks'] }}"
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div
                    class="col-span-full bg-white border border-dashed border-slate-200 rounded-[2rem] p-12 text-center">
                    <span class="text-3xl block mb-2">📝</span>
                    <p class="text-slate-400 text-sm font-bold">Belum ada tanggapan refleksi dari siswa.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>