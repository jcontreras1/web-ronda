<?php

namespace App\View\Components\Misc;

use Illuminate\View\Component;

class Title extends Component
{
    public $back;

    public $title;

    public function __construct($title, $back = null)
    {

        $this->title = $title;
        $this->back = $back;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.misc.title');
    }
}
