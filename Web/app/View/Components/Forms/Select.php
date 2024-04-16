<?php

namespace App\View\Components\Forms;

class Select extends BaseComponent
{
    /**
     * @var string
     */
    public $name;

    /**
     * Need validate
     *
     * @var boolean
     */
    public $required;

    /**
     * List options of select. Format: key => text
     *
     * @var array[]
     */
    public $options;

    /**
     * @var string
     */
    public $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name,
        $options = [],
        $selected = null
    ) {
        $this->name    = $name;
        $this->options = $options;
        $this->selected = $selected;
    }

    /**
     * Init plugin select2
     *
     * @return string
     */
    public function select2()
    {
        return $this->attributes->has('select2') ? 'data-init-plugin=select2' : '';
    }
}
