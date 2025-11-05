@extends('layouts.app')

@section('content')
    {{-- 
      File ini memanggil komponen Livewire 
      dan mengirimkan 'recordId' dari Controller ke komponen Livewire 
    --}}
    @livewire('financial-record-detail-livewire', ['recordId' => $recordId])
@endsection