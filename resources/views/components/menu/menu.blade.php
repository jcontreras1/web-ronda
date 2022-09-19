<div class="container mt-3">
    <div class="row">
        @include('components.menu.card_menu', ['url' => route('ronda.index'), 'bg_color' => '#2E121F', 'class_icon' => 'bi bi-geo-alt', 'title' =>'Rondas'])
        @can('administrar')
        @include('components.menu.card_menu', ['url' => route('circuito.index'), 'bg_color' => '#2E121F', 'class_icon' => 'bi bi-pin-map', 'title' =>'Definir Trayectos'])
        @endcan
        @include('components.menu.card_menu', ['url' =>'#', 'bg_color' => '#2E121F', 'class_icon' => 'bi bi-graph-up-arrow', 'title' =>'Estadísticas'])
        @can('administrar')
        @include('components.menu.card_menu', ['url' => route('user.index'), 'bg_color' => '#2E121F', 'class_icon' => 'bi bi-people', 'title' =>'usuarios'])
        @include('components.menu.card_menu', ['url' => route('area.index'), 'bg_color' => '#2E121F', 'class_icon' => 'bi bi-building', 'title' =>'Areas'])
        @include('components.menu.card_menu', ['url' => route('config.index'), 'bg_color' => '#2E121F', 'class_icon' => 'bi bi-gear-fill', 'title' =>'Configuraciones'])
        @endcan
    </div>
</div>

