<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body mb-2" data-bs-theme="dark">
  <div class="container px-2">
    <a class="navbar-brand" href="{{ route('home') }}">
      {{-- <img src="{{ asset('/img/logo_bgw64.png') }}" alt="{{ config('app.name') }}" width="45"> --}}
      <a class="nav-link" href="{{url('/')}}"><strong class="h4 text-light-emphasis">{{ config('app.name') }}</strong></a>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-moon-stars-fill"></i>
          </a>
          <style type="text/css">
            .navbar .dropdown-menu.show {
              min-width: inherit;
              display: inline-block;
            }
          </style>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" data-toggle="tooltip" title="Claro" data-bs-theme-value="light"><i class="bi bi-sun"></i></button></li>
            <li><button class="dropdown-item" data-toggle="tooltip" title="Oscuro" data-bs-theme-value="dark"><i class="bi bi-moon"></i></button></li>
            <li><button class="dropdown-item" data-toggle="tooltip" title="Automático" data-bs-theme-value="auto"><i class="bi bi-magic"></i></button></li>
          </ul>
        </li>
      </ul>
      <span class="navbar-text">
        @guest
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">{{ __('Ingresar') }}</a>
        </li>
        @else
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle"></i> {{ Auth::user()->nombre }} <span class="caret"></span>
            </a>
            <ul class="dropdown-menu m-0 navbar-dark bg-dark">
              <li><a class="dropdown-item" href="{{ route('user.profile.show') }}">Mi Perfil</a></li>
              <li>              
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  {{ __('Salir') }}
                </a>
              </li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </ul>
          </li>
        </ul>
        @endguest
      </span>
    </div>
  </div>
</nav>