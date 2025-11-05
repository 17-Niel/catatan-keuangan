<form wire:submit.prevent="create">
    {{-- Ubah ID Modal agar unik dan konsisten dengan event SweetAlert2 Livewire --}}
    <div class="modal fade" tabindex="-1" id="addModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    
                    {{-- 1. Tipe Transaksi (Pemasukan / Pengeluaran) --}}
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi</label>
                        <select class="form-select" wire:model="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    {{-- 2. Tanggal Transaksi --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" wire:model="date" required>
                        @error('date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 3. Jumlah Uang --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" class="form-control" wire:model="amount" placeholder="Contoh: 50000" min="1" required>
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 4. Deskripsi Transaksi --}}
                    {{-- Menggantikan addTodoDescription. Jika menggunakan Trix, wire:ignore perlu ditambahkan --}}
                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Keterangan</label>
                        <textarea class="form-control" rows="3" wire:model="description" required></textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 5. Unggah Gambar Cover (Opsional) --}}
                    <div class="mb-3">
                        <label for="cover" class="form-label">Gambar Bukti (Opsional)</label>
                        <input class="form-control" type="file" id="cover" wire:model="cover">
                        {{-- Tampilkan pratinjau gambar saat diunggah --}}
                        @if ($cover)
                            <div class="mt-2">
                                <img src="{{ $cover->temporaryUrl() }}" style="max-width: 100px; height: auto;" class="img-thumbnail">
                            </div>
                        @endif
                        @error('cover')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    {{-- Ganti addTodo menjadi create --}}
                    <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                </div>
            </div>
        </div>
    </div>
</form>