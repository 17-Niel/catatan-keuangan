<div>
    {{-- Hidden inputs untuk data chart --}}
    <input type="hidden" id="totalPemasukan" value="{{ $totalPemasukan }}">
    <input type="hidden" id="totalPengeluaran" value="{{ $totalPengeluaran }}">
    <input type="hidden" id="saldoAkhir" value="{{ $saldoAkhir }}">

    {{-- Statistik Data dengan Design Modern --}}
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="stat-card card-income p-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-arrow-down-circle display-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-1 opacity-90">TOTAL PEMASUKAN</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        <small class="opacity-75">Seluruh pemasukan</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card card-expense p-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-arrow-up-circle display-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-1 opacity-90">TOTAL PENGELUARAN</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                        <small class="opacity-75">Seluruh pengeluaran</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card card-balance p-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-wallet display-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-1 opacity-90">SALDO AKHIR</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                        <small class="opacity-75">Saldo saat ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="chart-container">
                <h5 class="mb-3 text-primary">
                    <i class="bi bi-pie-chart-fill me-2"></i>Rasio Pemasukan vs Pengeluaran
                </h5>
                <div id="incomeExpenseChart"></div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="chart-container">
                <h5 class="mb-3 text-primary">
                    <i class="bi bi-graph-up-arrow me-2"></i>Tren Saldo
                </h5>
                <div id="balanceTrendChart"></div>
            </div>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" 
                       placeholder="Cari berdasarkan deskripsi atau jumlah..." 
                       wire:model.live="search">
            </div>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Tambah Catatan
            </button>
        </div>
    </div>

    {{-- Tabel Data Keuangan --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th width="50">#</th>
                    <th width="120">Tanggal</th>
                    <th width="120">Tipe</th>
                    <th width="150" class="text-end">Jumlah</th>
                    <th>Deskripsi</th>
                    <th width="100">Cover</th>
                    <th width="180" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                <tr>
                    <td class="text-muted">{{ $records->firstItem() + $index }}</td>
                    <td>
                        <strong>{{ $record->date->format('d M Y') }}</strong>
                    </td>
                    <td>
                        <span class="badge bg-{{ $record->type === 'pemasukan' ? 'success' : 'danger' }} rounded-pill">
                            <i class="bi bi-arrow-{{ $record->type === 'pemasukan' ? 'down' : 'up' }}-circle me-1"></i>
                            {{ ucfirst($record->type) }}
                        </span>
                    </td>
                    <td class="text-end fw-bold fs-6 {{ $record->type === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($record->amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 200px;" title="{{ $record->description }}">
                            {!! Str::limit(strip_tags($record->description), 60) !!}
                        </div>
                    </td>
                    <td class="text-center">
                        @if($record->cover)
                            <img src="{{ Storage::url($record->cover) }}" alt="Cover" 
                                 class="img-thumbnail rounded" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- Detail Button (Biru) --}}
                        <a href="{{ route('app.financial.detail', ['financialRecord' => $record->id]) }}" 
                           class="btn btn-sm btn-info action-btn text-white" 
                           title="Detail"
                           data-bs-toggle="tooltip">
                            <i class="bi bi-eye"></i>
                        </a>
                        
                        {{-- Edit Button (Kuning) --}}
                        <button wire:click="edit({{ $record->id }})" 
                                class="btn btn-sm btn-warning action-btn text-white" 
                                title="Edit"
                                data-bs-toggle="tooltip">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        
                        {{-- Cover Button (Hitam) --}}
                        <button wire:click="showEditCoverModal({{ $record->id }})" 
                                class="btn btn-sm btn-dark action-btn text-white" 
                                title="Ubah Cover"
                                data-bs-toggle="tooltip">
                            <i class="bi bi-image"></i>
                        </button>
                        
                        {{-- Delete Button (Merah) --}}
                        <button wire:click="deleteConfirm({{ $record->id }})" 
                                class="btn btn-sm btn-danger action-btn" 
                                title="Hapus"
                                data-bs-toggle="tooltip">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada catatan keuangan</h5>
                        <p class="text-muted">Mulai dengan menambahkan catatan keuangan pertama Anda</p>
                        <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Catatan Pertama
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($records->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Menampilkan <strong>{{ $records->firstItem() }}</strong> - <strong>{{ $records->lastItem() }}</strong> 
            dari <strong>{{ $records->total() }}</strong> data
        </div>
        <div>
            {{ $records->links() }}
        </div>
    </div>
    @endif

    {{-- Include Modals --}}
    @include('components.modals.financial-records.add')
    @include('components.modals.financial-records.edit')
    @include('components.modals.financial-records.delete')
    @include('components.modals.financial-records.edit-cover')
</div>