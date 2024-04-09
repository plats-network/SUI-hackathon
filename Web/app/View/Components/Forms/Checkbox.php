<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $name;

    /**
     * List checkbox
     *
     * @var string['value' => 'label']
     */
    public $list;

    /**
     * Default checked value
     *
     * @var mixed
     */
    public $checked;

    /**
     * Need validate
     *
     * @var boolean
     */
    public $required;

    /**
     * @var array[]
     */
    public $options;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label,
        $name,
        $list = [],
        $checked = null,
        $required = null,
        $options = []
    ) {
        $this->label    = $label;
        $this->name     = $name;
        $this->list     = $list;
        $this->checked  = $checked;
        $this->required = !is_null($required) ? ' required' : '';
        $this->options  = $options;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.forms.checkbox');
    }
}
