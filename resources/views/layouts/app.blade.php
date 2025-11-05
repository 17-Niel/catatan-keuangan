<!DOCTYPE html>
<html lang="en">
<head>
    {{-- ... Asset CSS lainnya ... --}}
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @livewireStyles
</head>
<body>
    {{-- ... Bagian Navbar dan Konten ... --}}
    
    @yield('content')
    
    {{-- ... Asset JS lainnya ... --}}

    {{-- Script untuk Livewire dan SweetAlert2 --}}
    <script>
        // Event listener untuk SweetAlert2
        window.addEventListener('show-alert', event => {
            const data = event.detail[0];
            Swal.fire({
                icon: data.type, // 'success', 'error', 'warning', 'info'
                title: data.message,
                showConfirmButton: false,
                timer: 3000
            });
        });
        
        // Event listener untuk menutup modal
        window.addEventListener('closeModal', event => {
            const modalId = event.detail[0].id;
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modal.hide();
            }
        });
        
        // Event listener untuk membuka modal
        window.addEventListener('openModal', event => {
            const modalId = event.detail[0].id;
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    </script>
    @livewireScripts
</body>
</html>