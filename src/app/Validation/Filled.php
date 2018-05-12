<?php

namespace App\Validation;

class Filled extends Rule
{
    /**
     * Build validation laravel format
     *
     * @return string
     */
    function build(): string
    {
        return 'filled';
    }
}