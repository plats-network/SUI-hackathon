<?php

namespace App\View\Components\Forms;

class Group extends BaseComponent
{
    /**
     * Label name
     *
     * @var string
     */
    public $label;

    /**
     * Applicable when using Horizontal form. Grids is 12
     *
     * @var null|int
     */
    public $labelCol;

    public function __construct($label = null, $labelCol = null)
    {
        $this->label    = $label;
        $this->labelCol = $labelCol;
    }

    /**
     * @return string
     */
    public function isHorizontal()
    {
        return !is_null($this->labelCol) ? 'row' : '';
    }

    /**
     * @return string
     */
    public function labelCol()
    {
        return 'col-md-' . $this->labelCol;
    }

    /**
     * @return string
     */
    public function inputCol()
    {
        return 'col-md-' . (12 - $this->labelCol);
    }
}
