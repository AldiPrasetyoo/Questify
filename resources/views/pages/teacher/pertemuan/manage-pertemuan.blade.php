<?php

use App\Models\Pertemuan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component
    {
        public bool $formTerbuka = false;
        public ?int $editId = null;

        public int $urutan = 1;
        public string $judul = '';
        public string $deskripsi = '';
        public string $tujuan_pembelajaran = '';
        public ?string $tanggal_tatap_muka = null;
        public bool $aktif = true;

        protected function rules(): array
        {
            return [
                'urutan' => 'required|integer|min:1',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tujuan_pembelajaran' => 'nullable|string',
                'tanggal_tatap_muka' => 'nullable|date',
                'aktif' => 'boolean',
            ];
        }

        #[Computed]
        public function pertemuans()
        {
            return Pertemuan::orderBy('urutan')->get();
        }

        public function bukaForm(?int $id = null)
        {
            $this->resetValidation();

            $this->formTerbuka = true;
            $this->editId = $id;

            if ($id) {
                $p = Pertemuan::findOrFail($id);

                $this->urutan = $p->urutan;
                $this->judul = $p->judul;
                $this->deskripsi = (string) $p->deskripsi;
                $this->tujuan_pembelajaran = (string) $p->tujuan_pembelajaran;
                $this->tanggal_tatap_muka = $p->tanggal_tatap_muka?->format('Y-m-d');
                $this->aktif = $p->aktif;
            } else {
                $this->reset([
                    'judul',
                    'deskripsi',
                    'tujuan_pembelajaran',
                    'tanggal_tatap_muka',
                ]);

                $this->urutan = (Pertemuan::max('urutan') ?? 0) + 1;
                $this->aktif = true;
            }
        }

        public function tutupForm()
        {
            $this->formTerbuka = false;
            $this->editId = null;
        }

        public function simpan()
        {
            $data = $this->validate();

            if ($this->editId) {
                Pertemuan::findOrFail($this->editId)->update($data);
            } else {
                Pertemuan::create($data);
            }

            session()->flash('sukses', 'Pertemuan berhasil disimpan.');

            $this->tutupForm();
        }

        public function hapus(int $id)
        {
            Pertemuan::findOrFail($id)->delete();

            session()->flash('sukses', 'Pertemuan dihapus.');
        }
    };

?>
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold">Kelola Pertemuan</h1>

        <button wire:click="bukaForm" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">
            + Tambah Pertemuan
        </button>
    </div>

    @if($formTerbuka)
    <div class="bg-white border rounded-xl p-5 mb-6">
        <h2 class="font-semibold mb-4">
            {{ $editId ? 'Edit' : 'Tambah' }} Pertemuan
        </h2>

        <div class="grid gap-3 sm:grid-cols-2">

            <div>
                <label class="text-xs text-slate-500">Urutan</label>
                <input type="number" wire:model="urutan" class="w-full rounded-lg border-slate-300 text-sm">

                @error('urutan')
                <p class="text-rose-600 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs text-slate-500">
                    Tanggal Tatap Muka
                </label>

                <input type="date" wire:model="tanggal_tatap_muka" class="w-full rounded-lg border-slate-300 text-sm">
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Judul</label>

                <input type="text" wire:model="judul" class="w-full rounded-lg border-slate-300 text-sm">

                @error('judul')
                <p class="text-rose-600 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">Deskripsi</label>

                <textarea wire:model="deskripsi" rows="2" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs text-slate-500">
                    Tujuan Pembelajaran
                </label>

                <textarea wire:model="tujuan_pembelajaran" rows="2"
                    class="w-full rounded-lg border-slate-300 text-sm"></textarea>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="aktif">
                Aktif (tampil ke siswa)
            </label>
        </div>

        <div class="mt-4 flex gap-2">
            <button wire:click="simpan" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">
                Simpan
            </button>

            <button wire:click="tutupForm" class="text-sm px-4 py-2 rounded-lg border">
                Batal
            </button>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Judul</th>
                    <th class="p-3">Status</th>
                    <th class="p-3"></th>
                </tr>
            </thead>

            <tbody>
                @forelse($this->pertemuans as $p)
                <tr class="border-t">
                    <td class="p-3">{{ $p->urutan }}</td>

                    <td class="p-3 font-medium">
                        {{ $p->judul }}
                    </td>

                    <td class="p-3">
                        <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $p->aktif
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-slate-100 text-slate-500'
                                }}">
                            {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td class="p-3 text-right space-x-2">
                        <button wire:click="bukaForm({{ $p->id }})" class="text-slate-500 hover:underline">
                            Edit
                        </button>

                        <button wire:click="hapus({{ $p->id }})" wire:confirm="Hapus pertemuan ini?"
                            class="text-rose-600 hover:underline">
                            Hapus
                        </button>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-slate-400">
                        Belum ada pertemuan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>