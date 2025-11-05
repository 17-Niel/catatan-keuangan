<div>
    @if ($record)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi</h6>
            </div>
            <div class="card-body">
                <h3 class="text-{{ $record->type === 'pemasukan' ? 'success' : 'danger' }}">
                    {{ ucfirst($record->type) }}
                </h3>
                
                <h1 class="mb-4">Rp. {{ number_format($record->amount, 2, ',', '.') }}</h1>
                
                <p><strong>Tanggal Transaksi:</strong> {{ $record->date->format('d F Y') }}</p>
                <p><strong>Dibuat Oleh:</strong> {{ $record->user->name }}</p>
                
                <hr>
                
                <h4>Deskripsi/Keterangan:</h4>
                <p>{{ $record->description }}</p>

                @if ($record->cover)
                    <h4 class="mt-4">Bukti Gambar:</h4>
                    <div class="mt-2">
                        <img src="{{ $record->cover_url }}" alt="Bukti Transaksi" class="img-fluid" style="max-height: 400px; border-radius: 8px; border: 1px solid #ccc;">
                    </div>
                @endif

            </div>
            <div class="card-footer">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Catatan keuangan tidak ditemukan atau Anda tidak memiliki akses.
        </div>
    @endif
</div>