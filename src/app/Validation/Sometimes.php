<?php

namespace App\Validation;

class Sometimes extends Rule
{
    /**
     * Build validation laravel format
     *
     * @return string
     */
    function build(): string
    {
        return 'sometimes';
    }
}