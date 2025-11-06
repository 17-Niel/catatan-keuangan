<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-text me-2"></i>Detail Catatan Keuangan
                        </h5>
                        <a href="{{ route('app.home') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if ($record)
                        <div class="row">
                            {{-- Informasi Transaksi --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bi bi-info-circle me-2"></i>Informasi Transaksi
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Tanggal</dt>
                                            <dd class="col-sm-8 fw-bold">{{ $record->date->format('d F Y') }}</dd>

                                            <dt class="col-sm-4 text-muted">Tipe</dt>
                                            <dd class="col-sm-8">
                                                @if ($record->type == 'pemasukan')
                                                    <span class="badge bg-success fs-6 rounded-pill">
                                                        <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger fs-6 rounded-pill">
                                                        <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                                    </span>
                                                @endif
                                            </dd>

                                            <dt class="col-sm-4 text-muted">Jumlah</dt>
                                            <dd class="col-sm-8 fw-bold fs-4 {{ $record->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                                Rp {{ number_format($record->amount, 0, ',', '.') }}
                                            </dd>

                                            <dt class="col-sm-4 text-muted">Deskripsi</dt>
                                            <dd class="col-sm-8">
                                                <div class="border rounded p-3 bg-light">
                                                    {!! $record->description !!}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Bukti Cover --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bi bi-image me-2"></i>Bukti (Cover)
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @if ($record->cover)
                                            <div class="mb-4">
                                                <img src="{{ Storage::url($record->cover) }}" 
                                                     alt="Cover Bukti" 
                                                     class="img-fluid rounded shadow" 
                                                     style="max-height: 300px; max-width: 100%;">
                                            </div>
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                {{-- Tombol Hapus Cover --}}
                                                <button 
                                                    wire:click="deleteCover" 
                                                    wire:confirm="Anda yakin ingin menghapus cover ini?"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash me-1"></i> Hapus Cover
                                                </button>
                                                
                                                {{-- Tombol Ubah Cover --}}
                                                <button 
                                                    wire:click="showEditCoverModal({{ $record->id }})"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-image me-1"></i> Ubah Cover
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-5">
                                                <i class="bi bi-image display-1 opacity-25 mb-3"></i>
                                                <p class="mb-3 fs-5">Tidak ada bukti gambar.</p>
                                                <button 
                                                    wire:click="showEditCoverModal({{ $record->id }})"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i> Tambah Cover
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="{{ route('app.home') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Home
                                    </a>
                                    <button 
                                        wire:click="edit({{ $record->id }})" 
                                        class="btn btn-warning text-white">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Catatan
                                    </button>
                                    <button 
                                        wire:click="deleteConfirm({{ $record->id }})" 
                                        class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus Catatan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger text-center py-4">
                            <i class="bi bi-exclamation-triangle display-4 mb-3"></i>
                            <h5>Gagal memuat data catatan</h5>
                            <p class="mb-0">Catatan tidak ditemukan atau telah dihapus</p>
                            <a href="{{ route('app.home') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Home
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>