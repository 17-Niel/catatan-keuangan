<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class FinancialRecordDetailLivewire extends Component
{
    public $record;
    public $recordId;

    public function mount($recordId)
    {
        $this->recordId = $recordId;
        $this->loadRecord();
    }

    public function loadRecord()
    {
        // Melakukan findOrFail DAN pengecekan otorisasi
        // Ini memastikan pengguna hanya bisa melihat datanya sendiri
        $this->record = FinancialRecord::where('user_id', Auth::id())
                                            ->findOrFail($this->recordId);
    }

    // --- FUNGSI HAPUS COVER (Perbaikan) ---
    public function deleteCover()
    {
        // Pastikan record ada dan memiliki cover
        if ($this->record && $this->record->cover) {
            
            // --- PERBAIKAN: Menggunakan disk 'public' ---
            // Memeriksa file di storage/app/public/financial_covers
            if (Storage::disk('public')->exists($this->record->cover)) {
                Storage::disk('public')->delete($this->record->cover);
            }
            // --- Akhir Perbaikan ---

            // Update kolom cover di database menjadi null
            $this->record->update(['cover' => null]);

            // Muat ulang data record untuk refresh tampilan
            $this->loadRecord(); 
            
            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Cover berhasil dihapus!']);
        }
    }

    public function render()
    {
        // View ini harus dibuat: resources/views/livewire/financial-record-detail-livewire.blade.php
        return view('livewire.financial-record-detail-livewire');
    }
}