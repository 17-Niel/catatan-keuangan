<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use App\Models\FinancialRecord;
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
    #[Rule('required')]
    public $description;
    #[Rule('nullable|image|max:2048')] // Increased to 2MB
    public $cover;

    // Properti lainnya
    public $search = '';
    public $editCover;
    public $isEditMode = false;

    // Reset properti untuk form
    private function resetInput()
    {
        $this->reset(['id', 'type', 'amount', 'date', 'description', 'cover', 'editCover', 'isEditMode']);
        $this->resetErrorBag();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- CRUD OPERATIONS ---

    public function create()
    {
        $this->validate();
        
        // Handle cover upload
        $fileName = null;
        if ($this->cover) {
            $fileName = $this->cover->store('financial_covers', 'public');
        }

        FinancialRecord::create([
            'user_id' => Auth::user()->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
            'cover' => $fileName,
        ]);

        $this->resetInput();
        
        $this->dispatch('closeModal', ['id' => 'addModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan Keuangan berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($id);
        
        $this->id = $record->id;
        $this->type = $record->type;
        $this->amount = $record->amount;
        $this->date = $record->date->format('Y-m-d');
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
        ]);

        $this->resetInput();

        $this->dispatch('closeModal', ['id' => 'editModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan Keuangan berhasil diperbarui!']);
    }
    
    // --- COVER MANAGEMENT ---
    public function showEditCoverModal($id)
    {
        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($id);
        $this->id = $record->id;
        $this->editCover = $record->cover;
        $this->dispatch('openModal', ['id' => 'editCoverModal']);
    }

    public function updateCover()
    {
        $this->validate(['cover' => 'required|image|max:2048']);

        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($this->id);

        // Delete old cover if exists
        if ($record->cover && Storage::disk('public')->exists($record->cover)) {
            Storage::disk('public')->delete($record->cover);
        }

        // Save new cover
        $fileName = $this->cover->store('financial_covers', 'public');
        $record->update(['cover' => $fileName]);

        $this->resetInput();

        $this->dispatch('closeModal', ['id' => 'editCoverModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Cover berhasil diubah!']);
    }
    
    // --- DELETE OPERATIONS ---
    public function deleteConfirm($id)
    {
        $this->id = $id;
        $this->dispatch('openModal', ['id' => 'deleteModal']);
    }

    public function delete()
    {
        $record = FinancialRecord::where('user_id', Auth::id())->findOrFail($this->id);
        
        // Delete cover file if exists
        if ($record->cover && Storage::disk('public')->exists($record->cover)) {
            Storage::disk('public')->delete($record->cover);
        }
        
        $record->delete();

        $this->resetInput();
        
        $this->dispatch('closeModal', ['id' => 'deleteModal']);
        $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan Keuangan berhasil dihapus!']);
    }
    
    // --- MAIN RENDER LOGIC ---
    public function render()
    {
        $query = FinancialRecord::where('user_id', Auth::user()->id);

        // Search functionality
        if ($this->search) {
            $query->where(function ($q) {
                $searchTerm = '%' . $this->search . '%';
                $q->where('description', 'LIKE', $searchTerm)
                  ->orWhere('amount', 'LIKE', $searchTerm);
            });
        }
        
        $records = $query->orderBy('date', 'desc')->paginate(10);
        
        // Calculate statistics
        $totalPemasukan = FinancialRecord::where('user_id', Auth::user()->id)
            ->where('type', 'pemasukan')
            ->sum('amount');
            
        $totalPengeluaran = FinancialRecord::where('user_id', Auth::user()->id)
            ->where('type', 'pengeluaran')
            ->sum('amount');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('livewire.home-livewire', [
            'records' => $records,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
        ]);
    }
}