<div>
    <p class="font-medium mb-3">{{ $pertanyaan }}</p>
    <textarea wire:model="jawaban" rows="4" class="w-full rounded-lg border-slate-300 text-sm"
        placeholder="Tulis refleksimu di sini..."></textarea>
    @error('jawaban') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
    <button wire:click="kirim" class="mt-3 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Kirim</button>
</div>