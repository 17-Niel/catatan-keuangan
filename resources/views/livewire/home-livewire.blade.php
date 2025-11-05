<div>
    {{-- Statistik Data --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Pemasukan 💰</h5>
                    <p class="card-text fs-4">Rp. {{ number_format($totalPemasukan, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Pengeluaran 💸</h5>
                    <p class="card-text fs-4">Rp. {{ number_format($totalPengeluaran, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Saldo Akhir 📊</h5>
                    <p class="card-text fs-4">Rp. {{ number_format($saldoAkhir, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Tambah Data --}}
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Tambah Catatan
    </button>

    {{-- Pencarian --}}
    <div class="mb-3">
        <input type="text" wire:model.live="search" class="form-control" placeholder="Cari berdasarkan Deskripsi atau Jumlah...">
    </div>

    {{-- Tabel Data Keuangan --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                <tr>
                    <td>{{ $records->firstItem() + $index }}</td>
                    <td>{{ $record->date->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $record->type === 'pemasukan' ? 'success' : 'danger' }}">
                            {{ ucfirst($record->type) }}
                        </span>
                    </td>
                    <td class="text-end">Rp. {{ number_format($record->amount, 2, ',', '.') }}</td>
                    <td>{{ Str::limit($record->description, 50) }}</td>
                    <td>
                        {{-- Link ke Detail --}}
                        <a href="{{ route('financial.detail', $record->id) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        {{-- Tombol Edit --}}
                        <button wire:click="edit({{ $record->id }})" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        {{-- Tombol Hapus --}}
                        <button wire:click="deleteConfirm({{ $record->id }})" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                        {{-- Tombol Edit Cover (Jika ada) --}}
                        <button wire:click="showEditCoverModal({{ $record->id }})" class="btn btn-sm btn-secondary">
                            <i class="bi bi-image"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data catatan keuangan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $records->links() }}
    </div>

    {{-- Memuat Modals --}}
    @include('components.modals.financial-records.add')
    @include('components.modals.financial-records.edit')
    @include('components.modals.financial-records.delete')
    @include('components.modals.financial-records.edit-cover')
</div>