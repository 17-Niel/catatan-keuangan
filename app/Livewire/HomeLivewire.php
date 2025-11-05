<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use App\Models\FinancialRecord; // Menggunakan Model baru
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class HomeLivewire extends Component
{
    use WithFileUploads, WithPagination;

    // Properti Model
    public $id;
    #[Rule('required|in:pemasukan,pengeluaran')] 
    public $type;
    #[Rule('required|numeric|min:1')]
    public $amount;
    #[Rule('required|date')]
    public $date;
    #[Rule('required|string|max:255')]
    public $description;
    #[Rule('nullable|image|max:1024')]
    public $cover;

    // Properti lainnya
    public $search = '';
    public $editCover;
    public $isEditMode = false;

    // Reset properti untuk form
    private function resetInput()
    {
        $this->reset(['id', 'type', 'amount', 'date', 'description', 'cover', 'editCover', 'isEditMode']);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    // Dipanggil saat properti $search berubah
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination saat mencari
    }

    // --- CRUD OPERASI ---

    public function create()
    {
        $this->validate();
        
        $fileName = $this->cover ? $this->cover->store('public/financial_covers') : null;

        FinancialRecord::create([
            'user_id' => Auth::user()->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
            'cover' => $fileName,
        ]);

        $this->resetInput();
        
        // Pemicu SweetAlert2
        $this->dispatch('closeModal', ['id' => 'addModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan Keuangan berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($id);
        
        $this->id = $record->id;
        $this->type = $record->type;
        $this->amount = $record->amount;
        $this->date = $record->date->format('Y-m-d'); // Format tanggal untuk input HTML
        $this->description = $record->description;
        $this->editCover = $record->cover;
        $this->isEditMode = true;

        $this->dispatch('openModal', ['id' => 'editModal']);
    }

    public function update()
    {
        $this->validate();

        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($this->id);
        
        $record->update([
            'type' => $this->type,
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
            // Cover tidak diubah di sini, gunakan editCoverModal
        ]);

        $this->resetInput();

        // Pemicu SweetAlert2
        $this->dispatch('closeModal', ['id' => 'editModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan Keuangan berhasil diperbarui!']);
    }
    
    // --- MENGOLAH GAMBAR (Cover) ---
    public function showEditCoverModal($id)
    {
        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($id);
        $this->id = $record->id;
        $this->editCover = $record->cover;
        $this->dispatch('openModal', ['id' => 'editCoverModal']);
    }

    public function updateCover()
    {
        $this->validate(['cover' => 'required|image|max:1024']); // Validasi gambar baru

        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($this->id);

        // Hapus file lama jika ada
        if ($record->cover && Storage::exists($record->cover)) {
            Storage::delete($record->cover);
        }

        // Simpan file baru
        $fileName = $this->cover->store('public/financial_covers');
        $record->update(['cover' => $fileName]);

        $this->resetInput();

        $this->dispatch('closeModal', ['id' => 'editCoverModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Cover berhasil diubah!']);
    }
    
    // --- HAPUS DATA ---

    public function deleteConfirm($id)
    {
        $this->id = $id;
        $this->dispatch('openModal', ['id' => 'deleteModal']);
    }

    public function delete()
    {
        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($this->id);
        
        // Hapus cover dari storage jika ada
        if ($record->cover && Storage::exists($record->cover)) {
            Storage::delete($record->cover);
        }
        
        $record->delete();

        $this->resetInput();
        
        // Pemicu SweetAlert2
        $this->dispatch('closeModal', ['id' => 'deleteModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan Keuangan berhasil dihapus!']);
    }
    
    // --- LOGIKA UTAMA (RENDER) ---

    public function render()
    {
        $query = FinancialRecord::where('user_id', Auth::user()->id);

        // Filter dan Pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'ilike', '%' . $this->search . '%')
                  ->orWhere('amount', '::text', 'ilike', '%' . $this->search . '%');
            });
        }
        
        // Ambil data dengan pagination (20 data per halaman)
        $records = $query->orderBy('date', 'desc')->paginate(20);
        
        // Hitung Statistik
        $totalPemasukan = FinancialRecord::where('user_id', Auth::user()->id)
            ->where('type', 'pemasukan')
            ->sum('amount');
            
        $totalPengeluaran = FinancialRecord::where('user_id', Auth::user()->id)
            ->where('type', 'pengeluaran')
            ->sum('amount');

        return view('livewire.home-livewire', [
            'records' => $records,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $totalPemasukan - $totalPengeluaran,
        ]);
    }
}