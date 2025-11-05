<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Catatan Keuangan</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('app.home') }}" class="btn btn-sm btn-outline-secondary mb-3">
                        <i class="bi bi-arrow-left"></i> Kembali ke Home
                    </a>

                    @if ($record)
                        <dl class="row">
                            <dt class="col-sm-3">Tanggal</dt>
                            <dd class="col-sm-9">{{ $record->date->format('d F Y') }}</dd>

                            <dt class="col-sm-3">Tipe</dt>
                            <dd class="col-sm-9">
                                @if ($record->type == 'pemasukan')
                                    <span class="badge bg-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Jumlah</dt>
                            <dd class="col-sm-9">Rp {{ number_format($record->amount, 0, ',', '.') }}</dd>

                            <dt class="col-sm-3">Deskripsi</dt>
                            <dd class="col-sm-9">{{ $record->description }}</dd>

                            <dt class="col-sm-3">Bukti (Cover)</dt>
                            <dd class="col-sm-9">
                                @if ($record->cover)
                                    <div class="mb-2">
                                        <img src="{{ $record->cover_url }}" alt="Cover" class="img-fluid rounded" style="max-height: 300px;">
                                    </div>
                                    {{-- Tombol Hapus Cover --}}
                                    <button 
                                        wire:click="deleteCover" 
                                        wire:confirm="Anda yakin ingin menghapus cover ini?"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus Cover
                                    </button>
                                @else
                                    <span class="text-muted">Tidak ada bukti gambar.</span>
                                @endif
                            </dd>
                        </dl>
                    @else
                        <p class="text-danger">Gagal memuat data catatan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>