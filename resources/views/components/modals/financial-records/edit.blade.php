<form wire:submit.prevent="update">
    <div class="modal fade" tabindex="-1" id="editModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Catatan Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    {{-- Tipe Transaksi --}}
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="type" required>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    {{-- Tanggal Transaksi --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" wire:model="date" required>
                        @error('date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jumlah Uang --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" wire:model="amount" placeholder="Contoh: 150000" min="1" required>
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Deskripsi Transaksi --}}
                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" wire:model="description" required></textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    {{-- Tampilkan Cover lama --}}
                    @if ($editCover)
                        <div class="mb-3">
                            <label class="form-label">Cover Saat Ini:</label>
                            <img src="{{ Storage::url($editCover) }}" alt="Cover Bukti" style="max-width: 100px; height: auto;" class="img-thumbnail">
                        </div>
                    @endif

                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Perbarui Catatan</button>
                </div>
            </div>
        </div>
    </div>
</form>