<div>
    <!-- Hanya gunakan div utama -->
    <a href="{{ route('student.dashboard') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali</a>

    <h1 class="text-2xl font-bold mt-2 mb-1">{{ $pertemuan->judul }}</h1>
    <p class="text-slate-500 mb-8">{{ $pertemuan->deskripsi }}</p>

    @foreach(['pra_kelas' => 'Fase Pra-Kelas (di rumah)', 'tatap_muka' => 'Fase Tatap Muka (di sekolah)', 'pasca_kelas'
    => 'Fase Pasca-Kelas (pengayaan)'] as $fase => $label)

    @php $misis = $pertemuan->misis->where('fase', $fase); @endphp

    @if($misis->count())
    <h2 class="font-semibold text-slate-700 mt-8 mb-3">{{ $label }}</h2>

    <div class="grid gap-3 sm:grid-cols-2">
        @foreach($misis as $misi)
        @php
        // Memastikan method tersedia di model Misi
        $terbuka = $misi->terbukaUntuk(auth()->user());
        $progres = \App\Models\ProgresMisi::where('user_id', auth()->id())->where('misi_id', $misi->id)->first();
        $selesai = $progres?->status === 'selesai';
        @endphp

        <div
            class="rounded-xl border p-4 flex items-center justify-between {{ $terbuka ? 'bg-white border-slate-200 shadow-sm' : 'bg-slate-50 border-slate-200 opacity-60' }}">
            <div>
                <div class="font-medium text-slate-800">
                    {{ $misi->judul }}
                    @if($selesai)
                    <span class="text-emerald-600 font-bold text-xs ml-2">✔ Selesai</span>
                    @endif
                </div>
                <div class="text-xs text-slate-500 mt-1 font-semibold">
                    ⏱️ {{ $misi->estimasi_menit }} menit · 🏆 {{ $misi->poin_maksimal }} poin
                </div>
            </div>

            @if($terbuka)
            <a href="{{ route('misi.show', $misi) }}"
                class="text-sm font-bold bg-[#00c2cb] hover:bg-teal-500 text-white px-4 py-2 rounded-lg transition">
                Buka
            </a>
            @else
            <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                🔒 Terkunci
            </span>
            @endif
        </div>
        @endforeach
    </div>
    @endif
    @endforeach
</div>