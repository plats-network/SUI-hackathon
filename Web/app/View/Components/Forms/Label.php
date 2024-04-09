<?php

namespace App\View\Components\Forms;

class Label extends BaseComponent
{
    /**
     * @var mixed|string
     */
    public $label;

    /**
     * @param string $label
     */
    public function __construct(string $label = '')
    {
        $this->label = $label;
    }
}
