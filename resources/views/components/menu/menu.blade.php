<div class="container">
    <div class="row">
        @can('administrar')
        @include('components.menu.card_menu', ['url' => route('user.index'), 'bg_color' => '#F07621', 'class_icon' => 'bi bi-people', 'title' =>'usuarios'])
        @endcan
        @can('administrar')
        @include('components.menu.card_menu', ['url' => route('config.index'), 'bg_color' => '#C55152', 'class_icon' => 'bi bi-gear-fill', 'title' =>'Configuraciones'])
        @endcan
        @include('components.menu.card_menu', ['url' =>'#', 'bg_color' => '#253234', 'class_icon' => 'bi bi-graph-up-arrow', 'title' =>'Estadísticas'])

    </div>
</div>

