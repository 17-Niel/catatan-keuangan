<form wire:submit.prevent="updateCover">
    {{-- Pastikan ID modal ini sama dengan yang dipanggil oleh dispatch('openModal', ['id' => 'editCoverModal']) --}}
    <div class="modal fade" tabindex="-1" id="editCoverModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Cover Bukti Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    
                    {{-- Ganti $editCoverTodoFile menjadi $editCover --}}
                    @if ($editCover)
                        <div class="mb-3">
                            <label class="form-label">Cover Saat Ini:</label>
                            {{-- Menggunakan accessor cover_url dari Model FinancialRecord --}}
                            <img src="{{ Storage::url($editCover) }}" alt="Cover Bukti Saat Ini" style="max-width: 150px; height: auto;" class="img-thumbnail">
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="newCover" class="form-label">Pilih Gambar Baru (Max 1MB) <span class="text-danger">*</span></label>
                        {{-- Properti Livewire untuk file upload adalah $cover --}}
                        <input class="form-control" type="file" id="newCover" wire:model="cover" required>
                        @error('cover')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        {{-- Pratinjau gambar baru --}}
                        @if ($cover)
                            <div class="mt-2">
                                <span wire:loading wire:target="cover">Mengunggah...</span>
                                <img src="{{ $cover->temporaryUrl() }}" style="max-width: 100px; height: auto;" class="img-thumbnail" wire:loading.remove wire:target="cover">
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="modal-footer">
                    {{-- Pastikan wire:click memanggil method resetInput() --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">Batal</button>
                    {{-- Hilangkan pengecekan variabel lama yang error --}}
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Simpan Cover Baru</button>
                </div>
            </div>
        </div>
    </div>
</form>