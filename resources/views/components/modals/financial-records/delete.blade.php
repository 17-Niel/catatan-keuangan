{{-- resources/views/components/modals/financial-records/delete.blade.php --}}

<div 
    class="modal fade" 
    id="deleteModal" {{-- Pastikan ID ini sesuai dengan data-bs-target di tombol Hapus --}}
    tabindex="-1" 
    aria-labelledby="deleteModalLabel" 
    aria-hidden="true" 
    wire:ignore.self>
    
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">
                    <i class="bi bi-trash-fill text-danger"></i> Hapus Data
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    
                    <h5 class="mt-3">Apakah anda yakin ingin menghapus data ini?</h5>
                    <p class="text-muted">Data yang telah dihapus tidak dapat dikembalikan.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                   
                {{-- 
                  Tombol ini akan memanggil fungsi 'delete' 
                  di FinancialRecordDetailLivewire.php 
                --}}
                <button 
                    type="button" 
                    class="btn btn-danger" 
                    wire:click="delete"
                    wire:loading.attr="disabled">
                    
                    <span wire:loading.remove wire:target="delete">
                        Ya, Hapus
                    </span>
                    <span wire:loading wire:target="delete">
                        Menghapus...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>