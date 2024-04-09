<?php

namespace App\View\Components\Forms;

use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.forms.' . Str::kebab(class_basename($this)));
    }

    /**
     * Need validate is required
     *
     * @return string
     */
    public function required()
    {
        return property_exists($this, 'required') && $this->required ? 'required' : '';
    }
}
