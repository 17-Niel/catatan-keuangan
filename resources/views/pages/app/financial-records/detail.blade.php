@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800">Detail Catatan Keuangan</h1>
            </div>
        </div>
        
        {{-- Menggunakan komponen Livewire untuk menampilkan detail --}}
        {{-- Mengirim recordId sebagai parameter --}}
        <livewire:financial-record-detail-livewire :recordId="$recordId" />
    </div>
@endsection