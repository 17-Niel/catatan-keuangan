<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialRecord; // Ganti Model
use Illuminate\Support\Facades\Auth;

class FinancialRecordDetailLivewire extends Component // Ganti nama kelas
{
    public $recordId; // Ganti $todoId menjadi $recordId
    public $record;

    public function mount($recordId)
    {
        $this->recordId = $recordId;
        $this->loadRecord();
    }

    public function loadRecord()
    {
        // Ambil data berdasarkan ID dan pastikan milik user yang login
        $this->record = FinancialRecord::where('user_id', Auth::user()->id)
            ->findOrFail($this->recordId);
    }

    public function render()
    {
        return view('livewire.financial-record-detail-livewire'); 
    }
}