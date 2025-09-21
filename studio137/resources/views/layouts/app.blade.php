<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        <style>
            .navbar-dark {
                background-color: #2c3e50;
            }
            .sidebar {
                min-height: calc(100vh - 56px);
                background-color: #34495e;
            }
            .sidebar .nav-link {
                color: #ecf0f1;
            }
            .sidebar .nav-link:hover {
                background-color: #2c3e50;
            }
            .card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .status-badge {
                font-size: 0.8rem;
                padding: 0.35rem 0.65rem;
            }
        </style>
    </head>
    <body class="bg-light">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                @auth
                    @if(auth()->user()->isCliente())
                    <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                        <div class="position-sticky pt-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('dashboard') }}">
                                        <i class="fas fa-home me-2"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('client.services') }}">
                                        <i class="fas fa-list-alt me-2"></i>
                                        Servicios Activos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('client.reports') }}">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Informes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('client.payments') }}">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Pagos
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    @endif
                @endauth

                <!-- Main Content Area -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                    @isset($header)
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h4">{{ $header }}</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $header }}</li>
                                </ol>
                            </nav>
                        </div>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Custom Scripts -->
        @stack('scripts')
    </body>
</html>