<?php

namespace App\Validation;

class NotHaveCity extends Rule
{
    /**
     * Build validation laravel format
     *
     * @return string
     */
    function build(): string
    {
        return 'not_have_city';
    }
}