<div class="container mt-3">
    <div class="row">
        
        @include('components.menu.card_menu', ['url' => route('ronda.index'), 'bg_color' => '#16213E', 'class_icon' => 'bi bi-geo-alt', 'title' =>'Rondas'])

        {{-- Apartado para jefes --}}
        @can('supervisar')

        @include('components.menu.card_menu', ['url' => route('circuito.index'), 'bg_color' => '#0F3460', 'class_icon' => 'bi bi-pin-map', 'title' =>'Definir Trayectos'])
        
        @include('components.menu.card_menu', ['url' => route('user.index'), 'bg_color' => '#533483', 'class_icon' => 'bi bi-people', 'title' =>'usuarios'])
        
        {{-- Apartado para administradores del sistema --}}
        @can('administrar')

        @include('components.menu.card_menu', ['url' => route('area.index'), 'bg_color' => '#E94560', 'class_icon' => 'bi bi-building', 'title' =>'Areas'])
        
        @include('components.menu.card_menu', ['url' => route('config.index'), 'bg_color' => '#816797', 'class_icon' => 'bi bi-gear-fill', 'title' =>'Configuraciones'])
        
        
        @endcan
        
        @endcan
        
        @include('components.menu.card_menu', ['url' => route('export.index'), 'bg_color' => '#4C3A51', 'class_icon' => 'bi bi-clipboard2-data', 'title' =>'Exportable'])

    </div>
</div>

