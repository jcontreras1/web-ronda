<?php

namespace App\View\Components\Salida;

use Illuminate\View\Component;

class CardSalida extends Component
{
    public $salida;

    public function __construct($salida)
    {
        $this->salida = $salida;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.salida.card-salida');
    }
}
