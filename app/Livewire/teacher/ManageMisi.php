<?php

namespace App\Livewire\Teacher;

use App\Models\Misi;
use App\Models\Pertemuan;

use Livewire\Component;

class ManageMisi extends Component
{
    public Pertemuan $pertemuan;

    public bool $formTerbuka = false;
    public ?int $editId = null;

    public string $fase = 'pra_kelas';
    public int $urutan = 1;
    public string $judul = '';
    public string $deskripsi = '';
    public ?int $estimasi_menit = null;
    public int $poin_maksimal = 0;
    public ?int $misi_prasyarat_id = null;
    public bool $wajib_kerja_kelompok = false;
    public bool $aktif = true;

    public function mount(Pertemuan $pertemuan)
    {
        $this->pertemuan = $pertemuan;
    }

    protected function rules(): array
    {
        return [
            'fase' => 'required|in:pra_kelas,tatap_muka,pasca_kelas',
            'urutan' => 'required|integer|min:1',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'estimasi_menit' => 'nullable|integer|min:1',
            'poin_maksimal' => 'required|integer|min:0',
            'misi_prasyarat_id' => 'nullable|exists:misis,id',
            'wajib_kerja_kelompok' => 'boolean',
            'aktif' => 'boolean',
        ];
    }

    public function bukaForm(?int $id = null)
    {
        $this->resetValidation();
        $this->formTerbuka = true;
        $this->editId = $id;

        if ($id) {
            $m = Misi::findOrFail($id);
            $this->fill($m->only([
                'fase',
                'urutan',
                'judul',
                'deskripsi',
                'estimasi_menit',
                'poin_maksimal',
                'misi_prasyarat_id',
                'wajib_kerja_kelompok',
                'aktif',
            ]));
        } else {
            $this->reset(['judul', 'deskripsi', 'estimasi_menit', 'misi_prasyarat_id']);
            $this->fase = 'pra_kelas';
            $this->urutan = ((int) $this->pertemuan->misis()->max('urutan')) + 1;
            $this->poin_maksimal = 0;
            $this->wajib_kerja_kelompok = false;
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
        $data['pertemuan_id'] = $this->pertemuan->id;

        if ($this->editId && $this->misi_prasyarat_id && $this->akanMelingkar($this->editId, $this->misi_prasyarat_id)) {
            $this->addError('misi_prasyarat_id', 'Tidak boleh memilih prasyarat yang membentuk lingkaran (misi ini akan mengunci dirinya sendiri secara tidak langsung).');
            return;
        }

        Misi::updateOrCreate(['id' => $this->editId], $data);

        session()->flash('sukses', 'Misi berhasil disimpan.');
        $this->tutupForm();
    }

    /** Telusuri rantai prasyarat dari $calonPrasyaratId ke belakang; kalau ketemu $misiId sendiri, berarti melingkar */
    private function akanMelingkar(int $misiId, int $calonPrasyaratId, int $batasLoncatan = 50): bool
    {
        $sekarang = $calonPrasyaratId;

        while ($sekarang && $batasLoncatan-- > 0) {
            if ($sekarang === $misiId) {
                return true;
            }
            $sekarang = Misi::whereKey($sekarang)->value('misi_prasyarat_id');
        }

        return false;
    }

    public function hapus(int $id)
    {
        Misi::findOrFail($id)->delete();
        session()->flash('sukses', 'Misi dihapus.');
    }

    public function render()
    {
        // Ambil misi dari pertemuan saat ini dan pertemuan-pertemuan sebelumnya
        $kandidatPrasyarat = Misi::with('pertemuan')
            ->whereHas('pertemuan', function ($q) {
                $q->where('urutan', '<=', $this->pertemuan->urutan);
            })
            ->when($this->editId, fn($q) => $q->where('id', '!=', $this->editId))
            ->get()
            ->groupBy(fn($misi) => 'Pertemuan ' . $misi->pertemuan->urutan . ': ' . $misi->pertemuan->judul);

        return view('livewire.teacher.manage-misi', [
            'misis' => $this->pertemuan->misis()
                ->withCount('kontens')
                ->with(['prasyarat.pertemuan']) // eager load pertemuan prasyarat untuk label info
                ->get(),
            'kandidatPrasyarat' => $kandidatPrasyarat,
        ])->layout('layouts.questify', ['title' => 'Kelola Misi']);
    }
}
