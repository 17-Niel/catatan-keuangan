<form wire:submit.prevent="update">
    <div class="modal fade" tabindex="-1" id="editModal" wire:ignore.self data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Catatan Keuangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        {{-- Tipe Transaksi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="type" required>
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
                                   placeholder="Contoh: 150000" min="1" step="1" required>
                        </div>
                        @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi dengan Trix Editor --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi / Keterangan <span class="text-danger">*</span></label>
                        <div wire:ignore>
                            <input id="editDescription" type="hidden" name="description" wire:model="description">
                            <trix-editor input="editDescription" 
                                        class="trix-content form-control"
                                        placeholder="Tulis deskripsi transaksi di sini..."></trix-editor>
                        </div>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tampilkan Cover lama --}}
                    @if ($editCover)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cover Saat Ini:</label>
                            <div class="text-center">
                                <img src="{{ Storage::url($editCover) }}" alt="Cover Bukti" 
                                     class="img-thumbnail rounded" style="max-height: 150px;">
                                <div class="mt-2">
                                    <small class="text-muted">Gunakan tombol "Ubah Cover" untuk mengganti gambar ini</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check-circle me-1"></i>Perbarui Catatan
                        </span>
                        <span wire:loading>
                            <i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-1"></i>
                            Memperbarui...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trix Editor for edit modal
        const editTrixEditor = document.querySelector('#editModal trix-editor');
        if (editTrixEditor) {
            editTrixEditor.addEventListener('trix-change', function(event) {
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

        // Update Trix editor content when modal opens
        window.addEventListener('openModal', function(event) {
            if (event.detail[0].id === 'editModal') {
                setTimeout(() => {
                    const editor = document.querySelector('#editModal trix-editor');
                    const hiddenInput = document.querySelector('#editModal input[name="description"]');
                    if (editor && hiddenInput) {
                        editor.editor.loadHTML(hiddenInput.value);
                    }
                }, 100);
            }
        });
    });
</script>