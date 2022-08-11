<div class="container">
    <div class="row">
        @include('components.menu.card_menu', ['url' => route('ronda.index'), 'bg_color' => '#448673', 'class_icon' => 'bi bi-geo-alt', 'title' =>'Rondas'])
        @can('administrar')
        @include('components.menu.card_menu', ['url' => route('circuito.index'), 'bg_color' => '#66713D', 'class_icon' => 'bi bi-pin-map', 'title' =>'Definir'])
        @endcan
        {{-- @include('components.menu.card_menu', ['url' =>'#', 'bg_color' => '#253234', 'class_icon' => 'bi bi-graph-up-arrow', 'title' =>'Estadísticas']) --}}
        @can('administrar')
        @include('components.menu.card_menu', ['url' => route('user.index'), 'bg_color' => '#51415B', 'class_icon' => 'bi bi-people', 'title' =>'usuarios'])
        {{-- @include('components.menu.card_menu', ['url' => route('config.index'), 'bg_color' => '#C55152', 'class_icon' => 'bi bi-gear-fill', 'title' =>'Configuraciones']) --}}
        @endcan
    </div>
</div>

