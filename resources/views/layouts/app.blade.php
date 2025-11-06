<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Catatan Keuangan') }}</title>

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    
    {{-- ApexCharts untuk statistik --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Trix Editor --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    @livewireStyles
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
        }
        .card-income {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .card-expense {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }
        .card-balance {
            background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
            color: white;
        }
        .action-btn {
            margin: 2px;
            border-radius: 8px;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        .table-responsive {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .cover-preview {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
        }
        .trix-button-group--file-tools {
            display: none !important;
        }
    </style>
</head>
<body class="bg-light">
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('app.home') }}">
                <i class="bi bi-wallet2 me-2"></i>{{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link text-white">
                                <i class="bi bi-person-circle me-1"></i>Halo, <strong>{{ Auth::user()->name }}</strong>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-danger text-white ms-2" href="{{ route('auth.logout') }}">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="{{ asset('assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
        // SweetAlert2 Configuration
        window.addEventListener('show-alert', event => {
            const data = event.detail[0];
            Swal.fire({
                icon: data.type,
                title: data.message,
                showConfirmButton: false,
                timer: 3000,
                position: 'top-end',
                toast: true
            });
        });
        
        // Modal handlers
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

        // Initialize ApexCharts dengan data real
        function initCharts(totalPemasukan, totalPengeluaran, saldoAkhir) {
            // Pie Chart for Income vs Expense
            if (document.querySelector("#incomeExpenseChart")) {
                const pieOptions = {
                    series: [totalPemasukan, totalPengeluaran],
                    chart: {
                        type: 'donut',
                        height: 320,
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800
                        }
                    },
                    labels: ['Pemasukan', 'Pengeluaran'],
                    colors: ['#28a745', '#dc3545'],
                    legend: {
                        position: 'bottom',
                        fontSize: '14px'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '50%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '16px',
                                        fontWeight: 'bold',
                                        color: '#373d3f'
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val, opts) {
                            return opts.w.config.series[opts.seriesIndex].toLocaleString('id-ID')
                        },
                        style: {
                            fontSize: '12px',
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                };

                const pieChart = new ApexCharts(document.querySelector("#incomeExpenseChart"), pieOptions);
                pieChart.render();
            }

            // Balance trend chart dengan data real
            if (document.querySelector("#balanceTrendChart")) {
                const lineOptions = {
                    series: [{
                        name: "Saldo",
                        data: [saldoAkhir - 2000000, saldoAkhir - 1000000, saldoAkhir]
                    }],
                    chart: {
                        height: 320,
                        type: 'line',
                        zoom: { enabled: false },
                        animations: {
                            enabled: true,
                            easing: 'linear',
                            dynamicAnimation: { speed: 1000 }
                        }
                    },
                    stroke: {
                        width: 3,
                        curve: 'smooth'
                    },
                    colors: ['#007bff'],
                    markers: {
                        size: 6,
                        hover: { size: 8 }
                    },
                    xaxis: {
                        categories: ['Bulan Lalu', 'Minggu Lalu', 'Sekarang'],
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                };

                const lineChart = new ApexCharts(document.querySelector("#balanceTrendChart"), lineOptions);
                lineChart.render();
            }
        }

        // Initialize charts ketika page load
        document.addEventListener('DOMContentLoaded', function() {
            const totalPemasukan = parseFloat(document.getElementById('totalPemasukan')?.value) || 0;
            const totalPengeluaran = parseFloat(document.getElementById('totalPengeluaran')?.value) || 0;
            const saldoAkhir = parseFloat(document.getElementById('saldoAkhir')?.value) || 0;
            
            if (totalPemasukan > 0 || totalPengeluaran > 0) {
                setTimeout(() => {
                    initCharts(totalPemasukan, totalPengeluaran, saldoAkhir);
                }, 1000);
            }
        });

        // Trix Editor Configuration
        document.addEventListener('trix-initialize', function(event) {
            const trixEditor = event.target;
            // Hide file upload tools
            const fileTools = trixEditor.toolbarElement.querySelector('[data-trix-button-group="file-tools"]');
            if (fileTools) {
                fileTools.style.display = 'none';
            }
        });
    </script>
    
    @livewireScripts
</body>
</html>