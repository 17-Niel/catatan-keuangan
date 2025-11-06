<form wire:submit.prevent="create">
    <div class="modal fade" tabindex="-1" id="addModal" wire:ignore.self data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Catatan Keuangan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        {{-- Tipe Transaksi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="type" required>
                                <option value="">Pilih Tipe</option>
                                <option value="pemasukan">💰 Pemasukan</option>
                                <option value="pengeluaran">💸 Pengeluaran</option>
                            </select>
                            @error('type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Tanggal Transaksi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="date" required
                                   max="{{ now()->format('Y-m-d') }}">
                            @error('date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Jumlah Uang --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" wire:model="amount" 
                                   placeholder="Contoh: 500000" min="1" step="1" required>
                        </div>
                        @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi dengan Trix Editor --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi / Keterangan <span class="text-danger">*</span></label>
                        <div wire:ignore>
                            <input id="description" type="hidden" name="description" wire:model="description">
                            <trix-editor input="description" 
                                        class="trix-content form-control"
                                        placeholder="Tulis deskripsi transaksi di sini..."></trix-editor>
                        </div>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Gunakan editor di atas untuk menulis deskripsi yang lengkap. Format teks didukung.
                        </div>
                    </div>

                    {{-- Unggah Gambar Cover --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Bukti (Opsional)</label>
                        <div class="cover-preview">
                            @if ($cover)
                                <div class="mb-3 text-center">
                                    <img src="{{ $cover->temporaryUrl() }}" 
                                         class="img-thumbnail rounded" 
                                         style="max-height: 200px;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="$set('cover', null)">
                                            <i class="bi bi-x-circle me-1"></i>Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-cloud-arrow-up display-4 text-muted mb-2"></i>
                                    <p class="text-muted mb-2">Klik untuk mengunggah gambar bukti</p>
                                    <input class="form-control" type="file" wire:model="cover" 
                                           accept="image/*" style="display: none;" id="coverInput">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            onclick="document.getElementById('coverInput').click()">
                                        <i class="bi bi-upload me-1"></i>Pilih Gambar
                                    </button>
                                </div>
                            @endif
                        </div>
                        @error('cover')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Format: JPG, PNG, GIF. Maksimal 2MB. Gambar akan disimpan sebagai bukti transaksi.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check-circle me-1"></i>Simpan Catatan
                        </span>
                        <span wire:loading>
                            <i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-1"></i>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trix Editor configuration
        const trixEditor = document.querySelector('trix-editor');
        if (trixEditor) {
            trixEditor.addEventListener('trix-change', function(event) {
                @this.set('description', event.target.innerHTML);
            });

            // Hide file tools in Trix toolbar
            document.addEventListener('trix-initialize', function(event) {
                const fileTools = event.target.toolbarElement.querySelector('[data-trix-button-group="file-tools"]');
                if (fileTools) {
                    fileTools.style.display = 'none';
                }
            });
        }
    });
</script>