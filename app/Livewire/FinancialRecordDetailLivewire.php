<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads; // <-- PENTING: Tambahkan ini di atas
use Livewire\Attributes\Validate; // <-- PENTING: Tambahkan ini untuk validasi

class FinancialRecordDetailLivewire extends Component
{
    use WithFileUploads; // <-- PENTING: Gunakan Trait ini untuk upload

    public $record;
    public $recordId;

    #[Validate('image|max:2048')] // Aturan validasi: maks 2MB, harus gambar
    public $newCover; // Properti untuk menampung file upload baru

    public function mount($recordId)
    {
        $this->recordId = $recordId;
        $this->loadRecord();
    }

    public function loadRecord()
    {
        // findOrFail + pengecekan otorisasi. Ini sudah SANGAT BAIK.
        // Memastikan pengguna hanya bisa memuat data miliknya.
        try {
            $this->record = FinancialRecord::where('user_id', Auth::id())
                ->findOrFail($this->recordId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika data tidak ditemukan atau bukan milik user, redirect ke home
            session()->flash('error', 'Data catatan tidak ditemukan.');
            return $this->redirectRoute('home', navigate: true);
        }
    }

    // --- FUNGSI UBAH COVER (Logika yang hilang) ---
    public function updateCover()
    {
        // 1. Validasi file
        $this->validateOnly('newCover');

        // 2. Hapus cover lama jika ada
        if ($this->record->cover) {
            Storage::disk('public')->delete($this->record->cover);
        }

        // 3. Simpan cover baru ke 'storage/app/public/financial_covers'
        //    Nama file akan di-hash otomatis untuk keamanan
        $path = $this->newCover->store('financial_covers', 'public');

        // 4. Update database dengan path baru
        $this->record->update([
            'cover' => $path
        ]);

        // 5. Reset input file & muat ulang data
        $this->reset('newCover');
        $this->loadRecord();

        // 6. Kirim notifikasi
        $this->dispatch('close-modal', 'modal-edit-cover'); // Tutup modal
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Cover berhasil diperbarui!']);
    }

    // --- FUNGSI HAPUS COVER (Dari kode Anda, sudah benar) ---
    public function deleteCover()
    {
        if ($this->record && $this->record->cover) {

            if (Storage::disk('public')->exists($this->record->cover)) {
                Storage::disk('public')->delete($this->record->cover);
            }

            $this->record->update(['cover' => null]);
            $this->loadRecord(); // Refresh data

            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Cover berhasil dihapus!']);
        }
    }

    // --- FUNGSI HAPUS CATATAN (Logika yang hilang) ---
    public function deleteRecord()
    {
        try {
           

            // 1. Hapus file cover jika ada
            if ($this->record->cover) {
                if (Storage::disk('public')->exists($this->record->cover)) {
                    Storage::disk('public')->delete($this->record->cover);
                }
            }

            // 2. Hapus record dari database
            $this->record->delete();

            // 3. Beri pesan sukses & redirect ke halaman home
            session()->flash('success', 'Catatan berhasil dihapus.');
            return $this->redirectRoute('home', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', ['type' => 'error', 'message' => 'Gagal menghapus catatan.']);
        }
    }

    public function render()
    {
        // Pastikan view ini ada:
        return view('livewire.financial-record-detail-livewire');
    }
}