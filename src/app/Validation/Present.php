<?php

namespace App\Validation;

class Present extends Rule
{
    /**
     * Build validation laravel format
     *
     * @return string
     */
    function build(): string
    {
        return 'present';
    }
}