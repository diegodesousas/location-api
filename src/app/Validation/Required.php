<?php

namespace App\Validation;

class Required extends Rule
{
    /**
     * Build validation laravel format
     *
     * @return string
     */
    function build(): string
    {
        return 'required';
    }
}