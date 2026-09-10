<div>
    <h1 class="text-xl font-bold mb-6">🏆 Papan Peringkat</h1>
    <ol class="space-y-2">
        @forelse($siswa as $i => $s)
        <li class="flex items-center justify-between bg-white rounded-lg border p-3 text-sm">
            <div class="flex items-center gap-3">
                <span class="font-bold text-slate-400 w-6">{{ $i + 1 }}</span>
                <!-- <span class="font-bold text-slate-400 w-6">{{ $s->name }}</span> -->
                <span>{{ $s->name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-amber-600">{{ $s->lencanas_count }} 🏅</span>
                <span class="font-semibold text-indigo-600">{{ $s->total_poin }} pts</span>
            </div>
        </li>
        @empty
        <div class="text-center py-6 text-slate-400">
            Belum ada data siswa.
        </div>
        @endforelse
    </ol>
</div>