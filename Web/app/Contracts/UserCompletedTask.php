<?php

namespace App\Contracts;

interface UserCompletedTask
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
