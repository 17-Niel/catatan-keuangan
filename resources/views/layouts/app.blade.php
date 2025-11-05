<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Catatan Keuangan') }}</title>

    {{-- Memuat CSS Bootstrap dari folder public/assets/vendor --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    {{-- Memuat Bootstrap Icons dari folder public/assets/vendor --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">
    
    @livewireStyles
    
    {{-- Memuat SweetAlert2 CDN (Disarankan di sini) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    {{-- NAVBAR SEDERHANA (Memperbaiki tampilan Nama Pengguna) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('app.home') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    {{-- MENAMPILKAN NAMA PENGGUNA YANG SEDANG LOGIN --}}
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">
                                Halo, <strong>{{ Auth::user()->name }}</strong>
                            </span>
                        </li>
                        {{-- Menu Logout --}}
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-danger text-white ms-lg-2" href="{{ route('auth.logout') }}">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid">
        @yield('content')
    </main>

    {{-- MEMASTIKAN JS BOOTSTRAP TERLOAD --}}
    <script src="{{ asset('assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    
    {{-- Script untuk Livewire dan SweetAlert2 harus diletakkan sebelum @livewireScripts --}}
    <script>
        // Script SweetAlert2 dan Modal event handlers dari jawaban sebelumnya
        window.addEventListener('show-alert', event => {
            const data = event.detail[0];
            Swal.fire({
                icon: data.type,
                title: data.message,
                showConfirmButton: false,
                timer: 3000
            });
        });
        
        window.addEventListener('closeModal', event => {
            const modalId = event.detail[0].id;
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modal.hide();
            }
        });
        
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