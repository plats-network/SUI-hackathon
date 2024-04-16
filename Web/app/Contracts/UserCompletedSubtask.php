<?php

namespace App\Contracts;

interface UserCompletedSubtask
{
    /**
     * Id of task
     *
     * @return string
     */
    public function taskId();

    /**
     * User Id
     *
     * @return string
     */
    public function userId();
}
