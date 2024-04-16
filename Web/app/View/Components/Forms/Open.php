<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Open extends Component
{

    /**
     * Get the view / view contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.forms.open');
    }
}
