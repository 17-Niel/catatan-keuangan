@extends('layouts.app') 

{{-- 
    File ini adalah 'halaman' yang memuat komponen Livewire.
    $recordId didapat dari FinancialRecordController.
--}}

@section('content')
    <div class="container-fluid">
        {{-- 
            Ini memanggil komponen Livewire yang sudah kita buat 
            dan mengirimkan $recordId ke dalamnya.
        --}}
        <livewire:financial-record-detail-livewire :recordId="$recordId" />
    </div>
@endsection
