  <link rel="stylesheet" href="{{ asset('CSS/Menu.css') }}">
  <nav class="navbar navbar-expand-lg py-3 sticky-top shadow-sm" style="background-color: var(--ColorPrincipal);">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="" style="color: var(--TextoClaro); font-weight: bold;">
        <div style="display: flex; align-items: center; padding: 20px;">
          <img src="{{ asset('IMG/Taquito.png') }}" alt="Logo Taco" style="height: 60px; margin-right: 15px;">
          <h1 style="color: white; font-size: 32px; margin: 0;">EL TACO LOCO</h1>
        </div>

      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-lg-center">
          @auth
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('INICIO') ? 'active' : '' }}" href="{{ route('INICIO') }}"
              style="color: var(--TextoClaro);">
              <i class="bi bi-house-door-fill me-1"></i> Inicio
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('Comanda') ? 'active' : '' }}" href="{{ route('Comanda') }}"
              style="color: var(--TextoClaro);">
              <i class="bi bi-receipt-cutoff me-1"></i> Comandas
            </a>
          </li>
          @if (in_array(auth()->user()->role, ['Administrador']))
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('USUARIOS') ? 'active' : '' }}" href="{{ route('USUARIOS') }}"
              style="color: var(--TextoClaro);">
              <i class="bi bi-people-fill me-1"></i> Usuarios
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('Productos') ? 'active' : '' }}" href="{{ route('Productos') }}"
              style="color: var(--TextoClaro);">
              <i class="bi bi-box-seam-fill me-1"></i> Productos
            </a>
          </li>
          @endif
          <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle btn btn-sm text-white fw-bold" href="#" id="userDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false"
              style="background-color: var(--ColorAcento);">
              <i class="bi bi-person-circle me-1"></i> Opciones
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="{{ route('USUARIOS') }}">
                  <i class="bi bi-key-fill me-1"></i> Restablecer contraseña
                </a> {{-- A futuro enlaza esto a la ruta correcta de reset de contraseña --}}
              </li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-1"></i> Salir
                  </button>
                </form>
              </li>
            </ul>
          </li>

          @else
          <li class="nav-item ms-lg-3">
            <a href="{{ route('login') }}" class="btn btn-sm text-white fw-bold {{ request()->routeIs('login') ? 'active' : '' }}"
              style="background-color: var(--TextoOscuro);">
              <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
            </a>
          </li>

          @if (Route::has('register'))
          <li class="nav-item ms-lg-2">
            <a href="{{ route('register') }}" class="btn btn-sm fw-bold {{ request()->routeIs('register') ? 'active' : '' }}"
              style="background-color: var(--ColorSecundario); color: var(--TextoClaro) !important;">
              <i class="bi bi-person-plus-fill me-1"></i> Registrarse
            </a>
          </li>
          @endif
          @endauth
        </ul>
      </div>
    </div>
  </nav>