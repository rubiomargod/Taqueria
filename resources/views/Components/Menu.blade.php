<link rel="stylesheet" href="{{ asset('CSS/menu.css') }}">

<nav class="navbar navbar-expand-lg py-3 sticky-top" style="background-color: var(--ColorPrincipal);">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#" style="color: var(--TextoClaro); font-weight: bold;">
      <i class="fas fa-taco me-2" style="font-size: 1.8rem; color: var(--ColorSecundario);"></i>
      <span style="font-size: 1.6rem;">La Taquería Deliciosa</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(100%);"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        @auth
        <!-- Solo se muestran si el usuario está autenticado -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ route('INICIO') }}" style="color: var(--TextoClaro);">
            <i class="fas fa-home me-1"></i> Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" style="color: var(--TextoClaro);">
            <i class="fas fa-book-open me-1"></i> Menú
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" style="color: var(--TextoClaro);">
            <i class="fas fa-users me-1"></i> Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" style="color: var(--TextoClaro);">
            <i class="fas fa-cash-register me-1"></i> Caja
          </a>
        </li>

        <!-- Botón SALIR -->
        <li class="nav-item ms-lg-3">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm" style="background-color: var(--ColorAcento); color: var(--TextoClaro); font-weight: bold;">
              <i class="fas fa-sign-out-alt me-1"></i> Salir
            </button>
          </form>
        </li>
        @else
        <!-- Opciones solo si NO está autenticado -->
        <li class="nav-item ms-lg-3">
          <a href="{{ route('login') }}" class="btn btn-sm" style="background-color: var(--ColorAcento); color: var(--TextoClaro); font-weight: bold;">
            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
          </a>
        </li>

        @if (Route::has('register'))
        <li class="nav-item ms-lg-2">
          <a href="{{ route('register') }}" class="btn btn-sm" style="background-color: var(--ColorSecundario); color: var(--TextoClaro); font-weight: bold;">
            <i class="fas fa-user-plus me-1"></i> Registrarse
          </a>
        </li>
        @endif
        @endauth
      </ul>
    </div>
  </div>
</nav>