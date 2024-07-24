@include('layouts.header')
 <div class="container-xxl position-relative bg-white d-flex p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Spinner End -->
        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hotel me-2"></i>Hotel</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{asset('/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">
                            @auth
                            {{ Auth::user()->fullName }}
                            @endauth
                            @guest
                                Huésped
                            @endguest
                        </h6>
                        <span>
                            @auth
                                {{ Auth::user()->getUserTypeFormatted()}}
                            @endauth
                
                        </span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <!-- All users -->
                    <a href="{{route('home')}}" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Escritorio</a>
                    @auth
                        <!-- Recepcionist view -->
                        @if(Auth::user()->userType == 'Receptionist')
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-door-closed me-2"></i>Habitaciones</a>
                                <div class="dropdown-menu bg-transparent border-0">
                                    <a href="#" class="dropdown-item"><i class="fas fa-plus me-2"></i>Reservar habitación</a>
                                    <a href="#" class="dropdown-item"><i class="fas fa-list me-2"></i>Ver reservas</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-swimming-pool me-2"></i>Comodidades</a>
                                <div class="dropdown-menu bg-transparent border-0">
                                    <a href="#" class="dropdown-item"><i class="fas fa-plus me-2"></i>Reservar Comodidad</a>
                                    <a href="#" class="dropdown-item"><i class="fas fa-list me-2"></i>Ver comodidades</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-broom me-2"></i>Limpiezas</a>
                                <div class="dropdown-menu bg-transparent border-0">
                                    <a href="#" class="dropdown-item"><i class="fas fa-plus me-2"></i>Solicitar limpieza</a>
                                    <a href="#" class="dropdown-item"><i class="fas fa-list me-2"></i>Ver limpiezas</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-th me-2"></i>Configuraciones</a>
                                <div class="dropdown-menu bg-transparent border-0">
                                    <a href="{{route('users.index')}}" class="dropdown-item"><i class="fas fa-users me-2"></i>Usuarios</a>
                                    <a href="{{route('rates.index')}}" class="dropdown-item"><i class="fas fa-dollar-sign me-2"></i>Tarifas</a>
                                    <a href="{{route('rooms.index')}}" class="dropdown-item"><i class="fas fa-door-closed me-2"></i>Habitaciones</a>
                                    <a href="{{route('commodities.index')}}" class="dropdown-item"><i class="fas fa-swimming-pool me-2"></i>Comodidades</a>
                                    <a href="{{route('policies.index')}}" class="dropdown-item"><i class="fas fa-concierge-bell me-2"></i>Políticas del hotel</a>
            
                                </div>
                            </div>
                        @elseif (Auth::user()->userType == 'Cleaner')
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-broom me-2"></i>Limpiezas</a>
                        @elseif(Auth::user()->userType == 'Guest')
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-broom me-2"></i>Solicitar limpieza</a>
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-swimming-pool me-2"></i>Ver comodidades</a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->
        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificaciones</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    @auth
                    <div class="nav-item dropdown">                       
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{asset('/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">
                                    {{ Auth::user()->fullName }}
                            </span>
                        </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                    </div>
                    @endauth
                    @guest
                        <div class="navbar-nav mx-3">
                            <a href="{{route('login')}}" class="btn btn-primary">
                                Iniciar sesión
                            </a>
                        </div>     
                    @endguest
                </div>
            </nav>
        <!-- Navbar End -->
        <!-- Page Content Start -->
            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
@include('layouts.footer')