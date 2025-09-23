<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ 
            auth()->user()->isCliente() ? route('client.dashboard') : 
            (auth()->user()->isDisenadorGrafico() || auth()->user()->isMarketing() ? route('worker.dashboard') : 
            route('dashboard')) 
        }}">
            <i class="fas fa-studio-vinyl me-2"></i>
            Studio137
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- User Info -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        @if(auth()->user()->isDisenadorGrafico())
                        <i class="fas fa-palette me-1"></i> Diseñador Gráfico
                        @elseif(auth()->user()->isMarketing())
                        <i class="fas fa-bullhorn me-1"></i> Community Manager
                        @elseif(auth()->user()->isCliente())
                        <i class="fas fa-user-tie me-1"></i> Cliente
                        @endif
                    </span>
                </li>
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->email }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>