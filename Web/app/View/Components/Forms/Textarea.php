<?php

namespace App\View\Components\Forms;

class Textarea extends BaseComponent
{
    /**
     * @var mixed|null
     */
    public $label;

    /**
     * @var mixed|null
     */
    public $value;

    /**
     * @param null $label
     * @param null $value
     */
    public function __construct($label = null, $value = null)
    {
        $this->label = $label;
        $this->value = $value;
    }
}
